@extends('layouts.app')

@section('title', 'Order #' . $order->id . ' - Hajusrakendused')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h1>Order #{{ $order->id }}</h1>
        <p class="text-muted">
            <small>Order placed {{ $order->created_at->format('F j, Y, g:i a') }}</small>
        </p>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Shop
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Order Items</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td class="text-center">{{ $product->quantity }}</td>
                                <td class="text-end">${{ number_format($product->price, 2) }}</td>
                                <td class="text-end">${{ number_format($product->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total:</td>
                                <td class="text-end fw-bold">${{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Name:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                <p class="mb-1"><strong>Email:</strong> {{ $order->email }}</p>
                <p><strong>Phone:</strong> {{ $order->phone }}</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Order Status</h5>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>Order ID:</strong> #{{ $order->id }}</p>
                <p class="mb-1"><strong>Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
                <p><strong>Payment Status:</strong> <span class="badge bg-warning">{{ ucfirst($order->payment_status) }}</span></p>
            </div>
        </div>
    </div>
</div>
@endsection 