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

                        <hr class="my-4">

                        <h5 class="card-title mb-3">Payment Details</h5>
                        <div class="mb-4">
                            <p class="text-muted small mb-2">Enter your card information below:</p>
                            <div id="card-element" class="form-control p-3" style="height: auto; min-height: 3em;"></div>
                            <div id="card-errors" class="text-danger mt-2"></div>
                            <div class="mt-2 text-muted small">
                                <i class="bi bi-shield-lock me-1"></i> Your payment information is secured with Stripe
                            </div>
                        </div>
                        
                        <button type="submit" id="submit-button" class="btn btn-primary btn-lg w-100">
                            <span id="button-text">Pay ${{ number_format($total, 2) }}</span>
                            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script src="{{ asset('js/stripe-helper.js') }}"></script>
<script>
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();
    
    const style = {
        base: {
            fontSize: '16px',
            color: '#32325d',
        }
    };
    
    const card = elements.create('card', {
        style: style,
        hidePostalCode: true
    });
    card.mount('#card-element');
    
    card.addEventListener('change', function(event) {
        const displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });
    
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    const originalButtonText = buttonText.textContent;
    
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        startProcessing('submit-button', 'spinner', 'button-text', 'Processing...');
        
        const customerData = {
            first_name: document.getElementById('first_name').value,
            last_name: document.getElementById('last_name').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
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
            
            const data = await response.json();
            
            if (data.error) {
                displayError('card-errors', data.error);
                stopProcessing('submit-button', 'spinner', 'button-text', originalButtonText);
                return;
            }
            
            const result = await stripe.confirmCardPayment(data.clientSecret, {
                payment_method: {
                    card: card,
                    billing_details: {
                        name: customerData.first_name + ' ' + customerData.last_name,
                        email: customerData.email,
                        phone: customerData.phone
                    }
                }
            });
            
            if (result.error) {
                displayError('card-errors', result.error.message);
                stopProcessing('submit-button', 'spinner', 'button-text', originalButtonText);
            } else {
                console.log('Payment result:', result);
                console.log('Payment status:', result.paymentIntent.status);
                
                buttonText.textContent = "Payment successful! Processing order...";
                
                if (result.paymentIntent.status === 'succeeded') {
                    const orderForm = new FormData();
                    orderForm.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                    orderForm.append('first_name', customerData.first_name);
                    orderForm.append('last_name', customerData.last_name);
                    orderForm.append('email', customerData.email);
                    orderForm.append('phone', customerData.phone);
                    orderForm.append('payment_method', 'card');
                    orderForm.append('payment_intent_id', result.paymentIntent.id);
                    
                    try {
                        const orderResponse = await fetch('{{ route('payment.process') }}', {
                            method: 'POST',
                            body: orderForm
                        });
                        
                        console.log('Order response status:', orderResponse.status);
                        
                        window.location = '{{ route('orders.success') }}';
                    } catch (orderError) {
                        console.error('Error submitting order:', orderError);
                        window.location = '{{ route('orders.success') }}';
                    }
                } else {
                    displayError('card-errors', 'Payment was not successful. Please try again.');
                    stopProcessing('submit-button', 'spinner', 'button-text', originalButtonText);
                }
            }
        } catch (error) {
            console.error('Payment error:', error);
            displayError('card-errors', 'An error occurred. Please try again.');
            stopProcessing('submit-button', 'spinner', 'button-text', originalButtonText);
        }
    });
</script>
@endsection 