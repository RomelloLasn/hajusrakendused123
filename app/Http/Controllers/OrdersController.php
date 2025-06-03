<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    public function create()
    {
        $sessionId = session('cart_session_id');
        $cart = Cart::where('session_id', $sessionId)->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $items = $cart->items;
        $total = $items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('shop.checkout.index', compact('items', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'payment_method' => 'required|in:bank,card,paypal',
        ]);

        $sessionId = session('cart_session_id');
        $cart = Cart::where('session_id', $sessionId)->first();
        
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        try {
            session()->flash('dummy_order', [
                'id' => rand(1000, 9999),
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'payment_method' => $request->payment_method,
                'total_amount' => $cart->items->sum(function ($item) {
                    return $item->product->price * $item->quantity;
                }),
                'created_at' => now()
            ]);

            $cart->items()->delete();
            $cart->delete();
            session()->forget('cart_session_id');

            return redirect()->route('orders.success')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return redirect()->route('cart.index')->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }

    public function success()
    {
        if (!session()->has('dummy_order')) {
            return redirect()->route('cart.index');
        }

        $order = (object) session()->get('dummy_order');
        return view('shop.orders.success', compact('order'));
    }
} 