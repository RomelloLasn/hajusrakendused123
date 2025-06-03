@extends('layouts.app')

@section('title', 'Order Confirmed')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-success mb-4">
                <div class="card-body text-center p-5">
                    <div class="mb-4 text-success">
                        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="12" cy="12" r="10" stroke="#28a745" stroke-width="2"/>
                            <path d="M8 12L11 15L16 9" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>

                    <h1 class="display-5 mb-4">Thank You!</h1>
                    <h2 class="h3 mb-4">Your order has been confirmed</h2>
                    <p class="mb-4 lead">Your payment was processed successfully through Stripe.</p>

                    <div class="alert alert-success mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-envelope me-2"></i>
                            <strong>Order confirmation sent</strong>
                        </div>
                        <p class="mb-0">A confirmation email has been sent to <strong>{{ $order->email }}</strong></p>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between">
                                <span>Order #{{ $order->id }}</span>
                                <span>{{ $order->created_at->format('F j, Y') }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <h5 class="h6 mb-2">Customer Information</h5>
                                    <p class="mb-0">{{ $order->first_name }} {{ $order->last_name }}</p>
                                    <p class="mb-0">{{ $order->email }}</p>
                                    <p class="mb-0">{{ $order->phone }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <h5 class="h6 mb-2">Order Summary</h5>
                                    <p class="mb-0"><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
                                    <p class="mb-0"><strong>Payment Method:</strong> Credit Card</p>
                                    <p class="mb-0"><strong>Total:</strong> €{{ number_format($order->total_amount, 2) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(isset($order->items) && count($order->items) > 0)
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            Order Details
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Quantity</th>
                                            <th class="text-end">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product_name ?? ($item->product->name ?? 'Product #'.($item->product_id ?? '')) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td class="text-end">€{{ number_format($item->price ?? 0, 2) }}</td>
                                        </tr>
                                        @endforeach
                                        <tr class="table-light">
                                            <td colspan="2" class="text-end"><strong>Total</strong></td>
                                            <td class="text-end"><strong>€{{ number_format($order->total_amount, 2) }}</strong></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            Order Summary
                        </div>
                        <div class="card-body p-4 text-center">
                            <p class="mb-0">Your purchase has been completed successfully.</p>
                            <h4 class="mt-3">Total Amount: <strong>€{{ number_format($order->total_amount, 2) }}</strong></h4>
                        </div>
                    </div>
                    @endif

                    <div class="d-grid gap-2 col-lg-8 mx-auto">
                        <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">Continue Shopping</a>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <p class="text-muted">
                    <i class="bi bi-question-circle me-1"></i>
                    If you have any questions about your order, please contact our support team.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection 