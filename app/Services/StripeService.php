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
        Stripe::setApiKey(config('services.stripe.secret'));
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