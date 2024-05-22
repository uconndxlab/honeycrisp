@extends('layouts.app')

@section('title', 'Facility Details')

@section('content')
    <div class="container">

        <div class="row my-4">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h2>{{ $facility->name }} ({{ $facility->abbreviation }})</h2>
                    </div>
                    <div class="card-body">
                        <p><strong>Status:</strong> {{ $facility->status }}</p>
                        <p><strong>Description:</strong> {{ $facility->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <h3>Products & Services Available</h3>
        <a href="{{ route('products.create') }}/{{$facility->abbreviation}} " class="btn btn-primary">Add Product</a>

        @if ($facility->products->isEmpty())
            <p>No products available at this time.</p>
        @else

            <ul class="my-4" >
                @foreach ($facility->products as $product)
                    <li>
                        <a href="{{ route('products.show', $product->id) }}">
                            {{ $product->name }} - {{ $product->description }}
                        </a>
                    </li>
                @endforeach

            </ul>
        @endif


        <h3>Orders</h3>
        <div>
            <p><a href="{{ route('orders.create') }}/{{ $facility->abbreviation }}" class="btn btn-primary">
                    Create Order</a></p>
        </div>
        @if ($facility->orders->isEmpty())
            <p>No orders have been placed at this facility.</p>
        @else
            <ul>
                @foreach ($facility->orders as $order)
                    <li>{{ $order->user->name }} - {{ $order->status }}</li>
                @endforeach
            </ul>
        @endif


    </div>
@endsection
