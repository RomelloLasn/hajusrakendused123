<?php

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "<!DOCTYPE html>
<html>
<head>
    <title>Stripe API Key Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .success { background-color: #d4edda; border-color: #c3e6cb; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        .error { background-color: #f8d7da; border-color: #f5c6cb; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; }
        pre { background-color: #f8f9fa; padding: 10px; border-radius: 5px; overflow: auto; }
    </style>
</head>
<body>
    <div class='container'>
        <h1>Stripe API Key Test</h1>";

echo "<h2>Environment</h2>";
echo "<pre>";
echo "APP_ENV: " . env('APP_ENV') . "\n";
echo "APP_DEBUG: " . (env('APP_DEBUG') ? 'true' : 'false') . "\n";
echo "</pre>";

echo "<h2>API Keys from env()</h2>";
echo "<pre>";
echo "STRIPE_KEY (env): " . (env('STRIPE_KEY') ? env('STRIPE_KEY') : 'Not set') . "\n";
echo "STRIPE_SECRET (env): " . (env('STRIPE_SECRET') ? substr(env('STRIPE_SECRET'), 0, 8) . "..." : 'Not set') . "\n";
echo "</pre>";

echo "<h2>API Keys from config()</h2>";
echo "<pre>";
echo "STRIPE_KEY (config): " . (config('services.stripe.key') ? config('services.stripe.key') : 'Not set') . "\n";
echo "STRIPE_SECRET (config): " . (config('services.stripe.secret') ? substr(config('services.stripe.secret'), 0, 8) . "..." : 'Not set') . "\n";
echo "</pre>";

try {
    echo "<h2>Testing Stripe API Connection</h2>";
    $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    $account = $stripe->account->retrieve();
    echo "<div class='success'>Stripe connection successful using env('STRIPE_SECRET').<br>Account: " . $account->email . "</div>";
} catch (\Exception $e) {
    echo "<div class='error'>Stripe Error using env('STRIPE_SECRET'): " . $e->getMessage() . "</div>";
}

try {
    $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
    $account = $stripe->account->retrieve();
    echo "<div class='success'>Stripe connection successful using config('services.stripe.secret').<br>Account: " . $account->email . "</div>";
} catch (\Exception $e) {
    echo "<div class='error'>Stripe Error using config('services.stripe.secret'): " . $e->getMessage() . "</div>";
}

echo "</div></body></html>";
