@extends('layouts.app')
@section('title', 'Product Details: ' . $product->name)

@section('content')

    <div class="container">
        <div class="row my-4">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h2>{{ $product->name }}</h2>
                        <!-- offered by -->
                        <p>Offered by: <a href="{{ route('facilities.show', $product->facility->id) }}">{{ $product->facility->name }}</a></p>
                    </div>
                    <div class="card-body">
                        <p><strong>Description:</strong> {{ $product->description }}</p>
                        <p><strong>Is Active:</strong> {{ $product->is_active ? 'Yes' : 'No' }}</p>
                        <p><strong>Is Deleted:</strong> {{ $product->is_deleted ? 'Yes' : 'No' }}</p>
                        <p><strong>Requires Approval:</strong> {{ $product->requires_approval ? 'Yes' : 'No' }}</p>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection