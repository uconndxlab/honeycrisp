@extends('layouts.app')

@section('title', $order->title . ' Order')


@section('content')

@include('orders.parts.order-meta-show');

    <div class="container">
        <div class="row">

            <div class="col-md-12">

                <!-- Order Items Section -->
                <div class="card">
                    <div class="card-header">
                        <h2>Order Items</h2>
                    </div>
                    <div class="card-body">
                        @if ($order->items->count() == 0)
                            <div class="alert alert-info">No items have been added to this order yet.</div>
                        @else

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
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>${{ number_format($item->quantity * $item->price, 2) }}</td>
                                    </tr>
                                @endforeach

                                <tr>
                                    <td colspan="3" class="text-start"><strong>Order Total:</strong></td>
                                    <td><strong>${{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        @endif
                    </div>
                </div>
            </div>
        @endsection
