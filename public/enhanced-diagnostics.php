<?php
// Set error reporting to maximum
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>Enhanced Laravel Diagnostics</h1>";
echo "<pre>";

// PHP Version and Extensions
echo "=== PHP ENVIRONMENT ===\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Loaded Extensions:\n";
$extensions = get_loaded_extensions();
sort($extensions);
foreach ($extensions as $ext) {
    echo "- $ext\n";
}

// File System
echo "\n=== FILE SYSTEM ACCESS ===\n";
$testFile = __DIR__ . '/../storage/logs/test_write.log';
$storageWritable = is_writable(__DIR__ . '/../storage');
$publicWritable = is_writable(__DIR__);
$testWrite = @file_put_contents($testFile, "Test write at " . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

echo "Storage directory writable: " . ($storageWritable ? "YES" : "NO") . "\n";
echo "Public directory writable: " . ($publicWritable ? "YES" : "NO") . "\n";
echo "Test file write: " . ($testWrite !== false ? "SUCCESS" : "FAILED") . "\n";

// Laravel Bootstrap test
echo "\n=== LARAVEL BOOTSTRAP ===\n";
try {
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../bootstrap/app.php';
    echo "Laravel autoloader: SUCCESS\n";
    echo "Laravel app bootstrap: SUCCESS\n";
    
    echo "Environment: " . app()->environment() . "\n";
    echo "Debug mode: " . (config('app.debug') ? "ON" : "OFF") . "\n";
    
    // Database connection
    echo "\n=== DATABASE ===\n";
    try {
        $result = DB::select('SELECT 1');
        echo "Database connection: SUCCESS\n";
        
        // Test orders table
        try {
            $hasOrdersTable = Schema::hasTable('orders');
            echo "Orders table exists: " . ($hasOrdersTable ? "YES" : "NO") . "\n";
            
            if ($hasOrdersTable) {
                $hasPaymentIntentColumn = Schema::hasColumn('orders', 'payment_intent_id');
                echo "payment_intent_id column exists: " . ($hasPaymentIntentColumn ? "YES" : "NO") . "\n";
            }
        } catch (\Exception $e) {
            echo "Schema test error: " . $e->getMessage() . "\n";
        }
    } catch (\Exception $e) {
        echo "Database connection: FAILED - " . $e->getMessage() . "\n";
    }
    
    // Routes test
    echo "\n=== ROUTES ===\n";
    try {
        $routes = Route::getRoutes();
        $namedRoutes = [];
        foreach ($routes as $route) {
            $name = $route->getName();
            if ($name) {
                $namedRoutes[] = $name;
            }
        }
        
        echo "Total routes: " . count($routes) . "\n";
        echo "Named routes: " . count($namedRoutes) . "\n";
        
        // Check for specific routes
        $webNewsStore = in_array('web.news.store', $namedRoutes);
        echo "web.news.store route exists: " . ($webNewsStore ? "YES" : "NO") . "\n";
    } catch (\Exception $e) {
        echo "Routes test error: " . $e->getMessage() . "\n";
    }
    
    // Stripe test
    echo "\n=== STRIPE CONFIGURATION ===\n";
    try {
        $stripeKey = env('STRIPE_KEY', 'NOT SET');
        $stripeSecret = env('STRIPE_SECRET', 'NOT SET');
        
        echo "Stripe public key exists: " . (!empty($stripeKey) && $stripeKey != 'NOT SET' ? "YES" : "NO") . "\n";
        if (!empty($stripeKey) && $stripeKey != 'NOT SET') {
            echo "Stripe key starts with: " . substr($stripeKey, 0, 5) . "..." . "\n";
        }
        
        echo "Stripe secret exists: " . (!empty($stripeSecret) && $stripeSecret != 'NOT SET' ? "YES" : "NO") . "\n";
        if (!empty($stripeSecret) && $stripeSecret != 'NOT SET') {
            echo "Stripe secret starts with: " . substr($stripeSecret, 0, 5) . "..." . "\n";
        }
    } catch (\Exception $e) {
        echo "Stripe config test error: " . $e->getMessage() . "\n";
    }
    
} catch (\Exception $e) {
    echo "BOOTSTRAP ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " (Line: " . $e->getLine() . ")\n";
    echo "\nStack Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";
