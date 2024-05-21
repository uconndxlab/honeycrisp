@extends('layouts.app')

@section('title', 'Add Product For Facility: ' . $facility->name)

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

        <h3>Add Product</h3>
        <form action="{{ route('products.store') }}" method="POST">
            @csrf
            <input type="hidden" name="facility_id" value="{{ $facility->id }}">

            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>

            <!-- is active-->
            <div class="form-group">
                <label for="is_active">Is Active</label>
                <select class="form-control" id="is_active" name="is_active" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

            <!-- requires approval -->
            <div class="form-group">
                <label for="requires_approval">Requires Approval</label>
                <select class="form-control" id="requires_approval" name="requires_approval" required>
                    <option value="1">Yes</option>
                    <option value="0">No</option>
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
                <input type="text" class="form-control" id="unit" name="unit" required>
            </div>
            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" class="form-control" id="price" name="price" required>
            </div>

        </form>
        <button type="submit" class="btn btn-primary">Add Product</button>
        <a href="{{ route('facilities.show', $facility->abbreviation) }}" class="btn btn-secondary">Cancel</a>
    </div>

@endsection
