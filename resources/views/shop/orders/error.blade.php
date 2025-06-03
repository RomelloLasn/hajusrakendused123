@extends('layouts.app')

@section('title', 'Payment Failed')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <div class="error-content p-4">
                <div class="mb-4">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="#FF3333" stroke-width="2"/>
                        <path d="M8 8L16 16M16 8L8 16" stroke="#FF3333" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>

                <h1 class="h3 mb-4">Payment Failed</h1>
                @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @else
                <p class="text-muted mb-4">We couldn't process your payment. Your order has not been placed and your cart items are still available.</p>
                @endif

                <div class="d-grid gap-2">
                    <a href="{{ route('checkout.index') }}" class="btn btn-primary">Try Again</a>
                    <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">Return to Cart</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 