@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Checkout</h1>
                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Cart
                </a>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Order Summary</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th class="text-end">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-end">${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">Total</th>
                                <th class="text-end">${{ number_format($total, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">Customer Information</h5>
                    <form id="payment-form">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                    id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                    id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                    id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div id="payment-errors" class="alert alert-danger d-none mb-4"></div>
                        
                        <button type="submit" id="submit-button" class="btn btn-primary btn-lg w-100">
                            <span id="button-text">Proceed to Payment</span>
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                        
                        <div class="mt-3 text-center text-muted small">
                            <i class="bi bi-shield-lock me-1"></i> Your payment will be securely processed by Stripe
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script src="{{ asset('js/stripe-helper.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure the Stripe key is not empty
        const stripeKey = '{{ config('services.stripe.key') }}';
        
        if (!stripeKey) {
            console.error('Stripe publishable key is missing');
            displayError('payment-errors', 'Payment configuration error. Please contact support.');
            return;
        }
        
        const stripe = Stripe(stripeKey);
        
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');
        const originalButtonText = buttonText.textContent;
        
        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            
            // Clear any previous errors
            clearError('payment-errors');
            
            // Validate form
            const firstName = document.getElementById('first_name').value;
            const lastName = document.getElementById('last_name').value;
            const email = document.getElementById('email').value;
            const phone = document.getElementById('phone').value;
            
            if (!firstName || !lastName || !email || !phone) {
                displayError('payment-errors', 'Please fill out all required fields');
                return;
            }
            
            startProcessing('submit-button', 'spinner', 'button-text', 'Processing...');
            
            const customerData = {
                first_name: firstName,
                last_name: lastName,
                email: email,
                phone: phone,
                amount: {{ $total }}
            };
            
            try {
                const response = await fetch('{{ route('payment.create-intent') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(customerData)
                });
                
                let data;
                try {
                    data = await response.json();
                } catch (e) {
                    console.error('Error parsing JSON response:', e);
                    throw new Error('Could not parse server response');
                }
                
                if (!response.ok || data.error) {
                    throw new Error(data.error || 'Failed to create payment session');
                }
                
                // Redirect to Stripe Checkout
                if (data.url) {
                    console.log('Redirecting to Stripe Checkout: ', data.url);
                    window.location.href = data.url;
                } else {
                    throw new Error('No checkout URL received from server');
                }
                
            } catch (error) {
                console.error('Payment error:', error);
                displayError('payment-errors', error.message || 'An error occurred. Please try again.');
                stopProcessing('submit-button', 'spinner', 'button-text', originalButtonText);
            }
        });
    });
</script>
@endsection 