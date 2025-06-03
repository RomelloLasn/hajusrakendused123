<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Traits\CartSession;

class ProductController extends Controller
{
    use CartSession;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $products = Product::all();
        $cart = Cart::where('session_id', $this->getCartSessionId())->first();
        $cartQuantities = [];
        
        if ($cart) {
            $cartQuantities = $cart->items->pluck('quantity', 'product_id')->toArray();
        }
        
        return view('shop.products.index', compact('products', 'cartQuantities'));
    }

    public function create()
    {
        return view('shop.products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $validated['image'] = 'images/products/' . $imageName;
        }
        
        Product::create($validated);
        
        return redirect()->route('products.index');
    }

    public function show(Product $product)
    {
        return view('shop.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('shop.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images/products'), $imageName);
            $validated['image'] = 'images/products/' . $imageName;
        }
        
        $product->update($validated);
        
        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index');
    }
}
