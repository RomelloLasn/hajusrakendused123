<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;

class StripeService
{
    public function __construct()
    {
        // Get the API key directly from .env for reliability
        $apiKey = env('STRIPE_SECRET', config('services.stripe.secret'));
        
        // Debug logging to troubleshoot key loading issues
        \Illuminate\Support\Facades\Log::debug('Stripe API Key Check', [
            'env_key_length' => env('STRIPE_SECRET') ? strlen(env('STRIPE_SECRET')) : 0,
            'config_key_length' => config('services.stripe.secret') ? strlen(config('services.stripe.secret')) : 0,
            'final_key_length' => $apiKey ? strlen($apiKey) : 0,
            'env_key_start' => env('STRIPE_SECRET') ? substr(env('STRIPE_SECRET'), 0, 4) : 'none',
            'config_key_start' => config('services.stripe.secret') ? substr(config('services.stripe.secret'), 0, 4) : 'none',
            'final_key_start' => $apiKey ? substr($apiKey, 0, 4) : 'none'
        ]);
        
        // Check if key is valid before setting
        if (empty($apiKey) || strlen($apiKey) < 10) {
            \Illuminate\Support\Facades\Log::error('Invalid Stripe API key detected');
            throw new \Exception('Invalid API Key provided. Check your STRIPE_SECRET environment variable.');
        }
        
        Stripe::setApiKey($apiKey);
    }

    public function createPaymentIntent($amount, $currency = 'eur')
    {
        try {
            return PaymentIntent::create([
                'amount' => $amount * 100,
                'currency' => $currency,
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);
        } catch (ApiErrorException $e) {
            throw $e;
        }
    }
    
    public function createCheckoutSession($items, $total, $customerInfo)
    {
        try {
            $lineItems = [];
            
            foreach ($items as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $item->product->name,
                        ],
                        'unit_amount' => $item->product->price * 100,
                    ],
                    'quantity' => $item->quantity,
                ];
            }
            
            return Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.index'),
                'customer_email' => $customerInfo['email'] ?? null,
                'metadata' => [
                    'first_name' => $customerInfo['first_name'] ?? '',
                    'last_name' => $customerInfo['last_name'] ?? '',
                    'phone' => $customerInfo['phone'] ?? '',
                ],
            ]);
        } catch (ApiErrorException $e) {
            throw $e;
        }
    }

    public function retrievePaymentIntent($paymentIntentId)
    {
        try {
            return PaymentIntent::retrieve($paymentIntentId);
        } catch (ApiErrorException $e) {
            throw $e;
        }
    }
    
    public function retrieveCheckoutSession($sessionId)
    {
        try {
            return Session::retrieve($sessionId);
        } catch (ApiErrorException $e) {
            throw $e;
        }
    }
} 