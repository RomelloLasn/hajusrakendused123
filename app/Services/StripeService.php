<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public function __construct()
    {
        try {
            // Get the API key directly from env with fallback to config
            $apiKey = trim(env('STRIPE_SECRET', config('services.stripe.secret')));
            
            // Debug logging for stripe key
            $keyLength = strlen($apiKey);
            $keyStart = substr($apiKey, 0, 4);
            
            Log::debug('Stripe API Key Check', [
                'env_key_length' => $keyLength,
                'config_key_length' => strlen(config('services.stripe.secret')),
                'final_key_length' => $keyLength,
                'env_key_start' => $keyStart,
                'config_key_start' => substr(config('services.stripe.secret'), 0, 4),
                'final_key_start' => $keyStart
            ]);
            
            // Basic validation check
            if (empty($apiKey) || $keyLength < 10) {
                Log::error('Invalid Stripe API key detected');
                throw new \Exception('Invalid API Key provided. Check your STRIPE_SECRET environment variable.');
            }
            
            // Set the API key for Stripe 
            Stripe::setApiKey($apiKey);
            
        } catch (\Exception $e) {
            Log::error('Error initializing Stripe: ' . $e->getMessage());
            // Don't throw here - let the specific methods handle errors
        }
    }

    public function createCheckoutSession($lineItems, $successUrl, $cancelUrl)
    {
        try {
            // Ensure URLs have https:// scheme
            $baseUrl = config('app.url');
            $success = str_starts_with($successUrl, 'http') ? $successUrl : $baseUrl . '/payment/success';
            $cancel = str_starts_with($cancelUrl, 'http') ? $cancelUrl : $baseUrl . '/checkout';
            
            // Format line items for Stripe
            $formattedLineItems = array_map(function($item) {
                return [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $item->product->name,
                            'description' => $item->product->description ?? null,
                        ],
                        'unit_amount' => (int)($item->product->price * 100), // Convert to cents
                    ],
                    'quantity' => $item->quantity,
                ];
            }, $lineItems->all());

            // Create the checkout session
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $formattedLineItems,
                'mode' => 'payment',
                'success_url' => $success,
                'cancel_url' => $cancel,
            ]);
            
            return $session;
            
        } catch (ApiErrorException $e) {
            Log::error('Error creating checkout session: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function createPaymentIntent($amount, $currency = 'eur', $paymentMethodTypes = ['card'])
    {
        try {
            // Convert amount to cents for Stripe (e.g. 10.00 EUR → 1000)
            $amountInCents = $amount * 100;
            
            // Create a PaymentIntent
            $intent = \Stripe\PaymentIntent::create([
                'amount' => $amountInCents,
                'currency' => $currency,
                'payment_method_types' => $paymentMethodTypes,
            ]);
            
            return $intent;
            
        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            throw new \Exception("Payment processing error: " . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('General error with payment intent: ' . $e->getMessage());
            throw new \Exception("Payment processing error: " . $e->getMessage());
        }
    }
    
    public function retrievePaymentIntent($paymentIntentId)
    {
        try {
            return \Stripe\PaymentIntent::retrieve($paymentIntentId);
        } catch (ApiErrorException $e) {
            Log::error('Error retrieving payment intent: ' . $e->getMessage());
            throw new \Exception("Payment not found: " . $e->getMessage());
        }
    }
}
