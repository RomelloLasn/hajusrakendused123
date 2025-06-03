@extends('layouts.app')

@section('title', 'Order Confirmed')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <div class="progress mb-4" style="height: 4px; background: rgba(255,255,255,0.1);">
                <div class="progress-bar" role="progressbar" style="width: 100%; background: #0066FF;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
            </div>

            <div class="success-content p-4">
                <div class="mb-4 text-success">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="12" cy="12" r="10" stroke="#28a745" stroke-width="2"/>
                        <path d="M8 12L11 15L16 9" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <h1 class="h3 mb-4">Payment Successful!</h1>
                <p class="mb-4">Your payment has been processed successfully through Stripe. Thank you for your order!</p>

                
                

                @if(isset($order->items) && count($order->items) > 0)
                <div class="order-items text-start p-4 mb-4 border rounded">
                    <h5 class="mb-3">Order Items</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Quantity</th>
                                    <th class="text-end">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name ?? 'Product #'.$item->product_id }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td class="text-end">${{ number_format($item->price, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif

                <div class="d-grid gap-2">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Continue Shopping</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 