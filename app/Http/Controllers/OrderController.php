<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\User;
use App\Traits\CartSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use CartSession;
    
    public function create()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        
        return view('shop.checkout.index', compact('total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:card',
        ]);

        $sessionId = $this->getCartSessionId();
        $cart = Cart::where('session_id', $sessionId)->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        try {
            $total = $cart->items->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            $paymentSuccess = true; 
            
            if (!$paymentSuccess) {
                return redirect()->route('orders.error');
            }

            session()->flash('order', [
                'id' => rand(1000, 9999),
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'payment_method' => $request->payment_method,
                'total_amount' => $total,
                'created_at' => now()
            ]);

            $cart->items()->delete();
            $cart->delete();
            session()->forget('cart_session_id');

            return redirect()->route('orders.success');
        } catch (\Exception $e) {
            return redirect()->route('orders.error');
        }
    }

    public function success()
    {
        if (session()->has('order')) {
            $order = (object) session()->get('order');
        } elseif (session()->has('dummy_order')) {
            $order = (object) session()->get('dummy_order');
        } else {
            
            return redirect()->route('cart.index')->with('error', 'No order information found. Please try again.');
        }
        
        
        $sessionId = $this->getCartSessionId();
        $cart = Cart::where('session_id', $sessionId)->first();
        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
            session()->forget('cart_session_id');
        }
        
        return view('shop.orders.success', compact('order'));
    }
    
    public function error()
    {
        return view('shop.orders.error');
    }
}
