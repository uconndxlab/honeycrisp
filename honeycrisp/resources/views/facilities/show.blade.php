@extends('layouts.app')

@section('title', 'Facility Details')

@section('content')
<div class="container">
    <h2>{{ $facility->name }} ({{ $facility->abbreviation }})</h2>
    <p><strong>Status:</strong> {{ $facility->status }}</p>
    <p><strong>Description:</strong> {{ $facility->description }}</p>

    <h3>Products Available</h3>
    @if ($facility->products->isEmpty())
    <p>No products available at this time.</p>
    @else
    <ul>
        @foreach ($facility->products as $product)
        <li>{{ $product->name }} - {{ $product->description }}</li>
        @endforeach
    </ul>
    @endif

    <p><a href="{{ route('facilities.edit', $facility->id) }}">Edit Facility</a></p>
    <p><a href="{{ route('facilities.index') }}">Back to All Facilities</a></p>

    <form action="{{ route('facilities.destroy', $facility->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete Facility</button>

    </form>

    <h3>Orders</h3>
    <div>
        <p><a href="{{ route('orders.create') }}">Create Order</a></p>
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
