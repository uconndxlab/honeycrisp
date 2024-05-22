@extends('layouts.app')

@section('title', 'Edit Product For Facility: ' . $product->facility->name)

@section('content')

    <div class="container">
        <div class="row my-4">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header">
                        <h2>{{ $product->facility->name }} ({{ $product->facility->abbreviation }})</h2>
                    </div>
                    <div class="card-body">
                        <p><strong>Status:</strong> {{ $product->facility->status }}</p>
                        <p><strong>Description:</strong> {{ $product->facility->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <h3>Edit Product</h3>
        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="facility_id" value="{{ $product->facility->id }}">

            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ $product->description }}</textarea>
            </div>

            <!-- is active-->
            <div class="form-group">
                <label for="is_active">Is Active</label>
                <select class="form-select" id="is_active" name="is_active">
                    <option value="1" {{ $product->is_active ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$product->is_active ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <!-- is_deleted -->
            <div class="form-group">
                <label for="is_deleted">Is Deleted</label>
                <select class="form-select" id="is_deleted" name="is_deleted">
                    <option value="1" {{ $product->is_deleted ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$product->is_deleted ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <!-- requires approval -->
            <div class="form-group">
                <label for="requires_approval">Requires Approval</label>
                <select class="form-select" id="requires_approval" name="requires_approval">
                    <option value="1" {{ $product->requires_approval ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !$product->requires_approval ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <!-- image -->
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control " id="image" name="image_url">
            </div>


            <!-- unit and price -->
            <div class="form-group">
                <label for="unit">Unit</label>
                <input type="text" class="form-control" id="unit" name="unit" required value="{{ $product->unit }}">
            </div>
            <div class="form-group my-3">
                <label for="price">Price</label>
                <input type="number" class="form-control" id="price" name="unit_price" value="{{ $product->unit_price }}">
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="{{ route('facilities.show', $product->facility->abbreviation) }}" class="btn btn-secondary">Cancel</a>
        </form>

    </div>

@endsection
