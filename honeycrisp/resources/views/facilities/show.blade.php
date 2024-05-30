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

        <div class="row my-4">
            <div class="col-md-12">
                <h3>Products & Services Available</h3>

                @if ($facility->products->isEmpty())
                    <p>No products available at this time.</p>
                @else
                    <ul class="list-group my-4">
                        @foreach ($facility->products as $product)
                            <li class="list-group-item">
                                <a href="{{ route('products.show', $product->id) }}">
                                    {{ $product->name }} - (${{ $product->unit_price }} each)
                                </a>
                            </li>
                        @endforeach

                    </ul>
                @endif
            </div>

        </div>

    </div>
@endsection
