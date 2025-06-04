<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StripeDebugController extends Controller
{
    public function testStripeKeys()
    {
        $output = [];
        
        // Check environment
        $output['env'] = [
            'app_env' => env('APP_ENV'),
            'app_debug' => env('APP_DEBUG') ? 'true' : 'false',
        ];
        
        // Check Stripe keys
        $output['stripe_keys'] = [
            'env_key_exists' => !empty(env('STRIPE_KEY')),
            'env_key_length' => env('STRIPE_KEY') ? strlen(env('STRIPE_KEY')) : 0,
            'env_key_start' => env('STRIPE_KEY') ? substr(env('STRIPE_KEY'), 0, 4) . '...' : 'null',
            'env_secret_exists' => !empty(env('STRIPE_SECRET')),
            'env_secret_length' => env('STRIPE_SECRET') ? strlen(env('STRIPE_SECRET')) : 0,
            'env_secret_start' => env('STRIPE_SECRET') ? substr(env('STRIPE_SECRET'), 0, 4) . '...' : 'null',
        ];
        
        // Check config
        $output['config'] = [
            'config_key_exists' => !empty(config('services.stripe.key')),
            'config_key_length' => config('services.stripe.key') ? strlen(config('services.stripe.key')) : 0,
            'config_key_start' => config('services.stripe.key') ? substr(config('services.stripe.key'), 0, 4) . '...' : 'null',
            'config_secret_exists' => !empty(config('services.stripe.secret')),
            'config_secret_length' => config('services.stripe.secret') ? strlen(config('services.stripe.secret')) : 0,
            'config_secret_start' => config('services.stripe.secret') ? substr(config('services.stripe.secret'), 0, 4) . '...' : 'null',
        ];
        
        // Test Stripe connection
        try {
            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
            $account = $stripe->account->retrieve();
            $output['stripe_test'] = [
                'success' => true,
                'message' => 'Connection successful',
                'account_email' => $account->email,
            ];
        } catch (\Exception $e) {
            $output['stripe_test'] = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            
            // Try with config value
            try {
                $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
                $account = $stripe->account->retrieve();
                $output['stripe_test_config'] = [
                    'success' => true,
                    'message' => 'Connection successful using config value',
                    'account_email' => $account->email,
                ];
            } catch (\Exception $e) {
                $output['stripe_test_config'] = [
                    'success' => false,
                    'message' => $e->getMessage(),
                ];
            }
        }
        
        // Log results for server-side reference
        Log::info('Stripe Debug Test', $output);
        
        return view('stripe.debug', ['output' => $output]);
    }
}
