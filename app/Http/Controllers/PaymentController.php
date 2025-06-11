<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Traits\CartSession;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    use CartSession;
    
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    public function createPaymentIntent(Request $request)
    {
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:0.5',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'required|string|max:20',
        ]);

        try {
            // First, check if Stripe keys are properly configured
            if (empty(env('STRIPE_SECRET'))) {
                Log::error('Stripe secret key is not set in environment');
                return response()->json(['error' => 'Payment processing is not properly configured'], 500);
            }
            
            $sessionId = $this->getCartSessionId();
            $cart = Cart::where('session_id', $sessionId)->first();
            
            if (!$cart || $cart->items->isEmpty()) {
                return response()->json(['error' => 'Your cart is empty'], 400);
            }
            
            $customerInfo = [
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
            ];
            
            session()->put('checkout_customer_info', $customerInfo);
            
            try {
                $successUrl = config('app.url') . '/payment/success';
                $cancelUrl = config('app.url') . '/checkout';
                
                $checkoutSession = $this->stripeService->createCheckoutSession(
                    $cart->items, 
                    $successUrl,
                    $cancelUrl
                );
                
                return response()->json([
                    'id' => $checkoutSession->id,
                    'url' => $checkoutSession->url,
                ]);
            } catch (\Stripe\Exception\ApiErrorException $stripeError) {
                Log::error('Stripe API Error: ' . $stripeError->getMessage());
                return response()->json(['error' => 'Payment processing error: ' . $stripeError->getMessage()], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error creating checkout session: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function handlePaymentSuccess(Request $request)
    {
        if (!$request->has('session_id')) {
            Log::error('No session_id provided in payment success callback');
            return redirect()->route('orders.error')->with('error', 'Payment session information is missing');
        }
        
        try {
            $session = $this->stripeService->retrieveCheckoutSession($request->session_id);
            Log::info('Checkout session status: ' . $session->payment_status);
            
            if ($session->payment_status !== 'paid') {
                Log::error('Payment not completed: ' . $session->payment_status);
                return redirect()->route('orders.error')->with('error', 'Payment was not completed');
            }
            
            $customerInfo = session()->get('checkout_customer_info', []);
            
            $sessionId = $this->getCartSessionId();
            $cart = Cart::where('session_id', $sessionId)->first();
            
            if (!$cart || $cart->items->isEmpty()) {
                // Even if the cart is empty, we should still handle a successful payment
                Log::warning('Cart is empty but payment was successful');
                
                $dummyOrder = (object) [
                    'id' => rand(1000, 9999),
                    'email' => $customerInfo['email'] ?? $session->customer_details->email ?? '',
                    'first_name' => $customerInfo['first_name'] ?? ($session->metadata->first_name ?? ''),
                    'last_name' => $customerInfo['last_name'] ?? ($session->metadata->last_name ?? ''),
                    'phone' => $customerInfo['phone'] ?? ($session->metadata->phone ?? ''),
                    'payment_method' => 'card',
                    'payment_intent_id' => $session->payment_intent,
                    'total_amount' => $session->amount_total / 100,
                    'status' => 'completed',
                    'created_at' => now()
                ];
                
                session()->flash('order', $dummyOrder);
                return redirect()->route('orders.success');
            }

            $total = $cart->items->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            DB::beginTransaction();

            try {
                $order = new Order([
                    'email' => $customerInfo['email'] ?? $session->customer_details->email ?? '',
                    'first_name' => $customerInfo['first_name'] ?? ($session->metadata->first_name ?? ''),
                    'last_name' => $customerInfo['last_name'] ?? ($session->metadata->last_name ?? ''),
                    'phone' => $customerInfo['phone'] ?? ($session->metadata->phone ?? ''),
                    'payment_method' => 'card',
                    'payment_intent_id' => $session->payment_intent,
                    'total_amount' => $total,
                    'status' => ($session->payment_status === 'paid') ? 'completed' : 'pending',
                ]);

                $order->save();
                Log::info('Order created: ' . $order->id);

                foreach ($cart->items as $item) {
                    $orderItem = new OrderItem([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                    ]);
                    $orderItem->save();
                }

                $cart->items()->delete();
                $cart->delete();
                session()->forget('cart_session_id');

                DB::commit();
                
                session()->flash('order', $order);
                Log::info('Order processed successfully: ' . $order->id);
                return redirect()->route('orders.success');
            } catch (\Exception $dbException) {
                DB::rollBack();
                Log::error('Database error: ' . $dbException->getMessage());
                
                // Create dummy order anyway if the payment was successful
                $dummyOrder = (object) [
                    'id' => rand(1000, 9999),
                    'email' => $customerInfo['email'] ?? $session->customer_details->email ?? '',
                    'first_name' => $customerInfo['first_name'] ?? ($session->metadata->first_name ?? ''),
                    'last_name' => $customerInfo['last_name'] ?? ($session->metadata->last_name ?? ''),
                    'phone' => $customerInfo['phone'] ?? ($session->metadata->phone ?? ''),
                    'payment_method' => 'card',
                    'payment_intent_id' => $session->payment_intent,
                    'total_amount' => $total,
                    'status' => 'completed',
                    'created_at' => now(),
                    'items' => $cart->items->map(function ($item) {
                        return (object) [
                            'product_name' => $item->product->name,
                            'quantity' => $item->quantity,
                            'price' => $item->product->price
                        ];
                    })->toArray()
                ];
                
                session()->flash('order', $dummyOrder);
                Log::info('Dummy order created for successful payment');
                return redirect()->route('orders.success');
            }
        } catch (\Exception $e) {
            Log::error('Error handling payment success: ' . $e->getMessage());
            return redirect()->route('orders.error')->with('error', $e->getMessage());
        }
    }

    public function processPayment(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:card',
            'payment_intent_id' => 'required|string',
        ]);

        $sessionId = $this->getCartSessionId();
        $cart = Cart::where('session_id', $sessionId)->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            Log::info('Processing payment for intent: ' . $request->payment_intent_id);
            
            $paymentIntent = PaymentIntent::retrieve($request->payment_intent_id);
            Log::info('Retrieved payment intent status: ' . $paymentIntent->status);
            
            if (!in_array($paymentIntent->status, ['succeeded', 'processing'])) {
                Log::error('Payment failed with status: ' . $paymentIntent->status);
                return redirect()->route('orders.error')->with('error', 'Payment failed with status: ' . $paymentIntent->status);
            }

            $total = $cart->items->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            $orderDetails = [
                'items' => $cart->items->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                    ];
                }),
                'total' => $total
            ];

            DB::beginTransaction();

            try {
                $order = new Order([
                    'email' => $request->email,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'payment_method' => 'card',
                    'payment_intent_id' => $paymentIntent->id,
                    'total_amount' => $paymentIntent->amount / 100,
                    'status' => 'completed',
                ]);

                $order->save();
                Log::info('Order created: ' . $order->id);

                foreach ($cart->items as $item) {
                    $orderItem = new OrderItem([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $item->product->price,
                    ]);
                    $orderItem->save();
                }

                $cart->items()->delete();
                $cart->delete();
                session()->forget('cart_session_id');

                DB::commit();
                
                session()->flash('order', $order);
                Log::info('Order processed successfully: ' . $order->id);
                return redirect()->route('orders.success');
            } catch (\Exception $dbException) {
                DB::rollBack();
                Log::error('Database error: ' . $dbException->getMessage());
                
                $dummyOrder = (object) [
                    'id' => rand(1000, 9999),
                    'email' => $request->email,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'payment_method' => 'card',
                    'payment_intent_id' => $paymentIntent->id,
                    'total_amount' => $total,
                    'status' => 'completed',
                    'created_at' => now(),
                    'items' => $orderDetails['items']
                ];
                
                session()->flash('order', $dummyOrder);
                Log::info('Dummy order created for successful payment');
                return redirect()->route('orders.success');
            }
        } catch (\Exception $e) {
            Log::error('Error processing payment: ' . $e->getMessage());
            
            try {
                $paymentIntentCheck = PaymentIntent::retrieve($request->payment_intent_id);
                if (in_array($paymentIntentCheck->status, ['succeeded', 'processing'])) {
                    Log::info('Payment was successful despite error: ' . $paymentIntentCheck->status);
                    
                    $dummyOrder = (object) [
                        'id' => rand(1000, 9999),
                        'email' => $request->email,
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'phone' => $request->phone,
                        'payment_method' => 'card',
                        'payment_intent_id' => $request->payment_intent_id,
                        'total_amount' => $cart->items->sum(function ($item) {
                            return $item->product->price * $item->quantity;
                        }),
                        'status' => 'completed',
                        'created_at' => now()
                    ];
                    
                    session()->flash('order', $dummyOrder);
                    return redirect()->route('orders.success');
                }
            } catch (\Exception $checkException) {
                Log::error('Error checking payment intent: ' . $checkException->getMessage());
            }
            
            return redirect()->route('orders.error')->with('error', 'An error occurred while processing your payment: ' . $e->getMessage());
        }
    }
} 