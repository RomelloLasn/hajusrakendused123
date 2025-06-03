<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Traits\CartSession;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    use CartSession;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sessionId = $this->getCartSessionId();
        $cart = Cart::where('session_id', $sessionId)->first();
        
        $items = [];
        $total = 0;
        
        if ($cart) {
        $items = $cart->items;
        $total = $items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        }
        
        return view('shop.cart.index', compact('items', 'total'));
    }

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $sessionId = $this->getCartSessionId();
        
        $cart = Cart::firstOrCreate(['session_id' => $sessionId]);
        
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $validated['product_id'])
            ->first();
            
        if ($cartItem) {
            $cartItem->quantity += $validated['quantity'];
            $cartItem->save();
        } else {
            $cartItem = new CartItem([
                'cart_id' => $cart->id,
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
            $cartItem->save();
        }
        
        return redirect()->route('cart.index');
    }
    
    public function updateQuantity(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);
        
        $sessionId = $this->getCartSessionId();
        $cart = Cart::where('session_id', $sessionId)->first();
        
        if (!$cart) {
            return response()->json(['error' => 'Cart not found'], 404);
        }
        
        $cartItem = CartItem::where('id', $id)->where('cart_id', $cart->id)->first();
        
        if (!$cartItem) {
            return response()->json(['error' => 'Item not found'], 404);
        }
        
        $cartItem->quantity = $request->quantity;
        $cartItem->save();
        
        $items = $cart->items;
        $cartTotal = $items->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        $itemTotal = number_format($cartItem->product->price * $cartItem->quantity, 2);
        $cartTotal = number_format($cartTotal, 2);
        
        return response()->json([
            'quantity' => $cartItem->quantity,
            'itemTotal' => $itemTotal,
            'cartTotal' => $cartTotal
        ]);
    }
    
    public function destroy($id)
    {
        $sessionId = $this->getCartSessionId();
        $cart = Cart::where('session_id', $sessionId)->first();
        
        if (!$cart) {
        return redirect()->route('cart.index');
        }
        
        CartItem::where('id', $id)->where('cart_id', $cart->id)->delete();
        
        return redirect()->route('cart.index')->with('success', 'Item removed from cart!');
    }
    
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1|max:10'
        ]);
        
        $quantity = $request->input('quantity', 1);
        $sessionId = $this->getCartSessionId();
        
        $cart = Cart::firstOrCreate(
            ['session_id' => $sessionId],
            ['user_id' => Auth::id()]
        );
        
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();
            
        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }
        
        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }
    
    /**
     * Clear cart
     */
    public function clearCart()
    {
        $sessionId = $this->getCartSessionId();
        $cart = Cart::where('session_id', $sessionId)->first();
        
        if ($cart) {
            CartItem::where('cart_id', $cart->id)->delete();
        }
        
        session()->forget('cart_session_id');
        
        return redirect()->route('cart.index');
    }
}
