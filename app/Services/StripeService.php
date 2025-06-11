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
            $stripeSecret = env('STRIPE_SECRET');
            $configSecret = config('services.stripe.secret');
            
            // Use env key if available, otherwise fall back to config
            $apiKey = trim($stripeSecret ?: $configSecret);
            
            // Debug logging for stripe key validation
            $keyLength = strlen($apiKey);
            $keyStart = substr($apiKey, 0, 4);
            
            // Check if key looks like a valid Stripe key
            $isValidFormat = preg_match('/^(sk_test_|sk_live_)[A-Za-z0-9]+$/', $apiKey);
            
            Log::debug('Stripe API Key Validation', [
                'env_key_exists' => !empty($stripeSecret),
                'config_key_exists' => !empty($configSecret),
                'key_length' => $keyLength,
                'key_format_valid' => $isValidFormat,
                'key_prefix' => $keyStart,
                'is_test_key' => str_starts_with($apiKey, 'sk_test_'),
            ]);
            
            // Basic validation check
            if (empty($apiKey)) {
                Log::error('Stripe API key is empty');
                throw new \Exception('No Stripe API key provided. Check your STRIPE_SECRET environment variable.');
            }
            
            if (!preg_match('/^(sk_test_|sk_live_)[A-Za-z0-9]+$/', $apiKey)) {
                Log::error('Invalid Stripe API key format detected', ['length' => $keyLength, 'prefix' => $keyStart]);
                throw new \Exception('Invalid Stripe API key format. Key should start with sk_test_ or sk_live_');
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
            Log::info('Creating Stripe checkout session', [
                'items_count' => $lineItems->count(),
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl
            ]);

            // Ensure URLs have https:// scheme
            $baseUrl = config('app.url');
            Log::debug('Base URL from config', ['url' => $baseUrl]);
            
            $success = str_starts_with($successUrl, 'http') ? $successUrl : $baseUrl . '/payment/success';
            $cancel = str_starts_with($cancelUrl, 'http') ? $cancelUrl : $baseUrl . '/checkout';
            
            Log::debug('Final redirect URLs', [
                'success_url' => $success,
                'cancel_url' => $cancel
            ]);
            
            // Format line items for Stripe
            Log::debug('Processing line items', ['raw_items' => $lineItems->toArray()]);
            
            $formattedLineItems = array_map(function($item) {
                $unitAmount = (int)($item->product->price * 100);
                
                Log::debug('Formatting line item', [
                    'product_name' => $item->product->name,
                    'price' => $item->product->price,
                    'unit_amount_cents' => $unitAmount,
                    'quantity' => $item->quantity
                ]);
                
                return [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $item->product->name,
                            'description' => $item->product->description ?? null,
                        ],
                        'unit_amount' => $unitAmount,
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
