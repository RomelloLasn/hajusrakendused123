@extends('layouts.app')

@section('title', 'Shop - Hajusrakendused')

@section('content')
<div class="shop-container">
    <h1 class="shop-title">Shop</h1>
    
    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-md-6 col-lg-4">
            <div class="product-card">
                <div class="product-image-container">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="product-image">
                    @else
                        <div class="no-image">
                            <i class="bi bi-image"></i>
                        </div>
                    @endif
                </div>
                <div class="product-info">
                    <h3 class="product-title">{{ $product->name }}</h3>
                    <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                    <div class="product-footer">
                        <span class="product-price">${{ number_format($product->price, 2) }}</span>
                        <div class="purchase-controls">
                            <div class="quantity-control">
                                <button type="button" class="btn-quantity" onclick="decrementQuantity(this)">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" value="1" min="1" max="{{ max(0, 10 - ($cartQuantities[$product->id] ?? 0)) }}" class="quantity-input" data-product-id="{{ $product->id }}" data-max-quantity="{{ max(0, 10 - ($cartQuantities[$product->id] ?? 0)) }}">
                                <button type="button" class="btn-quantity" onclick="incrementQuantity(this)">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            <form action="{{ route('cart.store', ['product' => $product->id]) }}" method="POST" id="add-to-cart-form-{{ $product->id }}">
                                @csrf
                                <input type="hidden" name="quantity" class="quantity-input-hidden" data-product-id="{{ $product->id }}">
                                <button type="submit" class="btn-add-cart" onclick="updateQuantity(event, {{ $product->id }})">
                                    <i class="bi bi-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
.shop-container {
    padding: 2rem 0;
    max-height: calc(100vh - var(--header-height) - 4rem);
    overflow-y: auto;
    overflow-x: hidden;
}

.shop-container::-webkit-scrollbar {
    width: 8px;
}

.shop-container::-webkit-scrollbar-track {
    background: var(--secondary-bg);
    border-radius: 4px;
}

.shop-container::-webkit-scrollbar-thumb {
    background: var(--primary-color);
    border-radius: 4px;
}

.shop-container::-webkit-scrollbar-thumb:hover {
    background: var(--primary-hover);
}

.shop-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 2rem;
    background: linear-gradient(90deg, var(--text-color), var(--primary-color));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.product-card {
    background: var(--secondary-bg);
    border-radius: 16px;
    overflow: hidden;
    height: 100%;
    border: 1px solid var(--border-color);
}

.product-image-container {
    position: relative;
    padding-top: 75%;
    overflow: hidden;
    background: linear-gradient(145deg, var(--secondary-bg), rgba(26, 26, 26, 0.5));
}

.product-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
    color: #666;
}

.product-info {
    padding: 1.5rem;
}

.product-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-color);
    margin-bottom: 0.5rem;
}

.product-description {
    font-size: 0.9rem;
    color: #999;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.product-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}

.product-price {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--primary-color);
}

.purchase-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.quantity-control {
    display: flex;
    align-items: center;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 8px;
    padding: 0.25rem;
}

.btn-quantity {
    background: transparent;
    border: none;
    color: var(--primary-color);
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    border-radius: 6px;
}

.quantity-input {
    width: 40px;
    text-align: center;
    border: none;
    background: transparent;
    color: var(--text-color);
    font-size: 0.9rem;
    font-weight: 500;
    -moz-appearance: textfield;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.btn-add-cart {
    background: var(--primary-color);
    border: none;
    color: #000;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 1.1rem;
}
</style>

@section('scripts')
<script>
function incrementQuantity(button) {
    const input = button.previousElementSibling;
    const maxQuantity = parseInt(input.dataset.maxQuantity);
    const currentValue = parseInt(input.value);
    if (currentValue < maxQuantity) {
        input.value = currentValue + 1;
    }
}

function decrementQuantity(button) {
    const input = button.nextElementSibling;
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

function updateQuantity(event, productId) {
    event.preventDefault();
    const form = document.getElementById(`add-to-cart-form-${productId}`);
    const quantityInput = document.querySelector(`.quantity-input[data-product-id="${productId}"]`);
    const maxQuantity = parseInt(quantityInput.dataset.maxQuantity);
    const quantity = parseInt(quantityInput.value);
    
    if (quantity > maxQuantity) {
        alert(`You can only add ${maxQuantity} more of this item to your cart.`);
        return;
    }
    
    const hiddenInput = form.querySelector('.quantity-input-hidden');
    hiddenInput.value = quantity;
    console.log('Submitting form with quantity:', quantity);
    form.submit();
}
</script>
@endsection

@endsection 