@extends('layouts.app')

@section('title', $product->name . ' - Shop - Hajusrakendused')

@section('content')
<div class="product-detail">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="product-image-container">
                @if($product->image)
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="product-image">
                @else
                <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 400px;">
                    <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                </div>
                @endif
            </div>
        </div>
        <div class="col-lg-6">
            <div class="product-info">
                <h1 class="product-title">{{ $product->name }}</h1>
                <p class="product-description">{{ $product->description }}</p>
                <div class="price-section">
                    <span class="price">${{ number_format($product->price, 2) }}</span>
                </div>
                
                <div class="action-section mt-4">
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="d-flex align-items-center gap-3">
                        @csrf
                        <div class="quantity-control">
                            <button type="button" class="btn-quantity" onclick="decrementQuantity()">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" class="quantity-input">
                            <button type="button" class="btn-quantity" onclick="incrementQuantity()">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <button type="submit" class="btn btn-cyan">
                            Add to Cart
                        </button>
                    </form>
                </div>

                @if($product->details)
                <div class="product-details mt-5">
                    <h3 class="details-title">Product Details</h3>
                    <div class="details-content">
                        {!! $product->details !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.product-detail {
    padding: 2rem 0;
}

.product-image-container {
    background: var(--secondary-bg);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
}

.product-image {
    width: 100%;
    height: auto;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-image:hover {
    transform: scale(1.02);
}

.product-info {
    padding: 1rem;
}

.product-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    color: var(--text-color);
}

.product-description {
    font-size: 1.1rem;
    color: #999;
    margin-bottom: 2rem;
    line-height: 1.6;
}

.price-section {
    margin-bottom: 2rem;
}

.price {
    font-size: 2rem;
    font-weight: 600;
    color: var(--primary-color);
}

.quantity-control {
    display: flex;
    align-items: center;
    background: var(--secondary-bg);
    border-radius: 8px;
    padding: 0.25rem;
    border: 1px solid var(--border-color);
}

.btn-quantity {
    background: transparent;
    border: none;
    color: var(--primary-color);
    font-size: 1.2rem;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-quantity:hover {
    color: var(--text-color);
}

.quantity-input {
    width: 50px;
    text-align: center;
    border: none;
    background: transparent;
    color: var(--text-color);
    font-size: 1.1rem;
    font-weight: 500;
    -moz-appearance: textfield;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.details-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: var(--text-color);
}

.details-content {
    color: #999;
    line-height: 1.6;
}
</style>

@section('scripts')
<script>
function incrementQuantity() {
    const input = document.getElementById('quantity');
    input.value = parseInt(input.value) + 1;
}

function decrementQuantity() {
    const input = document.getElementById('quantity');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}
</script>
@endsection

@endsection 