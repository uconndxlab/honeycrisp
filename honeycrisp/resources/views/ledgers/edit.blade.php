@extends('layouts.app')

@section('title', 'Create Ledger')

@section('content')
 
@include('ledgers.parts.ledger-meta-form')

<div class="container my-4">
    <!-- table of orders for this facility with checkboxes to select orders to add to ledger -->
    <div class="row">
        <div class="col-md-12">
            <h3>Orders</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Select</th>
                        <th scope="col">Order ID</th>
                        <th scope="col">Order Date</th>
                        <th scope="col">Title</th>
                        <th scope="col">Order Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>
                            <input type="checkbox" name="order_id[]" value="{{ $order->id }}">
                        </td>
                        <td>{{ $order->id }}</td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}">{{ $order->title }}</a>
                        </td>
                        <td>{{ $order->date }}</td>
                        <td>${{ number_format($order->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>





@endsection