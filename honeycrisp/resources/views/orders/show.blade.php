@extends('layouts.app')

@section('title', $order->title . ' Order')

@section('content')
    <div class="container">
        <!-- Header Section -->
        <div class="row my-3">
            <div class="col-md-12">
                <h1>{{ $order->title }} Order</h1>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to All Orders</a>
                    <a href="{{ route('orders.edit', ['order' => $order]) }}" class="btn btn-primary">Edit Order</a>
                </div>
            </div>
        </div>

        <!-- Order Details Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h2>Order Details</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p><strong>Order ID:</strong> {{ $order->id }}</p>
                        <p><strong>Order Title:</strong> {{ $order->title }}</p>
                        <p><strong>Order Description:</strong> {{ $order->description }}</p>
                        <p><strong>Order User:</strong> {{ $order->user->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p><strong>Facility:</strong> {{ $order->facility->name }}</p>
                        <p><strong>Order Status:</strong> {{ $order->status }}</p>
                        <p><strong>Order Date:</strong> {{ $order->created_at->format('m/d/Y') }}</p>
                        <p><strong>Order Total:</strong> ${{ number_format($order->total, 2) }}</p>
                        <p><strong>Payment Account:</strong> {{ $payment_account->formatted() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items Section -->
        <div class="card">
            <div class="card-header">
                <h2>Order Items</h2>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>${{ number_format($item->price, 2) }}</td>
                                <td>${{ number_format($item->quantity * $item->price, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
