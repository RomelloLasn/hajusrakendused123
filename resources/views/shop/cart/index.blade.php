@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Shopping Cart</h1>
            </div>

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(count($items) > 0)
                <div class="card">
                    <div class="card-body">
                        <div class="cart-items">
                            @foreach($items as $item)
                                <div class="cart-item d-flex align-items-center py-3 border-bottom">
                                    <div class="cart-item-image me-4">
                                        @if($item->product->image)
                                            <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="cart-item-details flex-grow-1">
                                        <h5 class="mb-1">{{ $item->product->name }}</h5>
                                        <div class="text-muted small mb-2">${{ number_format($item->product->price, 2) }} each</div>
                                        
                                        <div class="d-flex align-items-center">
                                            <div class="text-muted me-3">
                                                Qty: {{ $item->quantity }}
                                            </div>
                                            
                                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST" class="ms-3">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-link text-danger p-0 border-0">Remove</button>
                                            </form>
                                        </div>
                                    </div>
                                    
                                    <div class="cart-item-total ms-4">
                                        <span class="fw-bold">${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="cart-summary mt-4">
                            <div class="d-flex justify-content-between align-items-center py-3">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-bold">${{ number_format($total, 2) }}</span>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <a href="{{ route('checkout.index') }}" class="btn btn-primary">Continue to Checkout</a>
                                <a href="{{ route('products.index') }}" class="btn btn-link text-muted">Continue Shopping</a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <h4 class="mb-3">Your cart is empty</h4>
                        <a href="{{ route('products.index') }}" class="btn btn-primary">Start Shopping</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
/* Hide spinner buttons from number inputs in some browsers */
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none;
    margin: 0; 
}
input[type=number] {
    -moz-appearance: textfield;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
});
</script>
@endpush
@endsection 