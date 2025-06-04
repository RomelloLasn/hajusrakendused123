<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Checking Stripe configuration:\n";
echo "STRIPE_KEY: " . config('services.stripe.key') . "\n";
echo "STRIPE_SECRET: " . substr(config('services.stripe.secret'), 0, 8) . "...\n";

try {
    $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
    $account = $stripe->account->retrieve();
    echo "Stripe connection successful. Account: " . $account->email . "\n";
} catch (\Exception $e) {
    echo "Stripe Error: " . $e->getMessage() . "\n";
}
