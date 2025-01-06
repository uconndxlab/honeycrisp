@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Reservable Products</h1>

        @if($products->isEmpty())
            <p>No products available for reservation at this facility.</p>
        @else
            <ul class="list-group">
                @foreach($products as $product)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <strong>{{ $product->name }}</strong> <br>
                            {{ $product->description ?? 'No description available.' }}
                        </span>
                        <a href="{{ route('reservations.create.product', $product->id) }}" class="btn btn-primary">
                            Reserve
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif

        <div class="mt-3">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
@endsection
