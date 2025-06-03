<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MarkerController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\MonsterPageController;
use App\Http\Controllers\ApiViewerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\SpotifyController;
use App\Http\Controllers\NewsController;

// Show news at /monsters
Route::get('/monsters', [NewsController::class, 'index'])->name('monsters.index');

// Optional: keep /news route if you want
Route::get('/news', [NewsController::class, 'index'])->name('news.index');

Route::get('/', function () {
    return view('home');
});

Route::get('/maps', [MarkerController::class, 'index'])->name('maps.index');
Route::resource('markers', MarkerController::class);
Route::post('/markers/map', [MarkerController::class, 'storeFromMap'])->name('markers.storeFromMap');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::resource('blogs', BlogController::class);
Route::resource('comments', CommentController::class);

Route::middleware(['web', 'auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/users/{user}/ban', [AdminController::class, 'banUser'])->name('admin.users.ban');
    Route::post('/admin/users/{user}/unban', [AdminController::class, 'unbanUser'])->name('admin.users.unban');
    Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
});

Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
    
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
    Route::post('/cart/add/{product}', [CartController::class, 'store'])->name('cart.store');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
    
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/payment/create-intent', [PaymentController::class, 'createPaymentIntent'])->name('payment.create-intent');
    Route::get('/payment/success', [PaymentController::class, 'handlePaymentSuccess'])->name('payment.success');
    Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
    
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/success', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/orders/error', [OrderController::class, 'error'])->name('orders.error');
});

Route::middleware(['auth'])->get('/test/success', function() {
    $order = (object)[
        'id' => rand(1000, 9999),
        'email' => 'test@example.com',
        'first_name' => 'Test',
        'last_name' => 'User',
        'phone' => '1234567890',
        'payment_method' => 'card',
        'total_amount' => 99.99,
        'created_at' => now()
    ];
    
    session()->flash('order', $order);
    return redirect()->route('orders.success');
})->name('test.success');

Route::get('/weather', [WeatherController::class, 'index'])->name('weather.index');
Route::get('/weather/get', [WeatherController::class, 'getWeather'])->name('weather.get');



Route::get('/api-viewer', [ApiViewerController::class, 'index'])->name('api-viewer.index');

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
});

Route::get('/spotify', [SpotifyController::class, 'index'])->name('spotify.index');
Route::get('/spotify/callback', [SpotifyController::class, 'callback'])->name('spotify.callback');
Route::post('/spotify/add-tracks', [SpotifyController::class, 'addTracks'])->name('spotify.add-tracks');