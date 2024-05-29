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
                <input type="text" class="form-control" id="name" name="name">
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>

            <!-- is active-->
            <div class="form-group">
                <label for="is_active">Is Active</label>
                <select class="form-select" id="is_active" name="is_active">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>

            <!-- is_deleted -->
            <div class="form-group">
                <label for="is_deleted">Is Deleted</label>
                <select class="form-select" id="is_deleted" name="is_deleted">
                    <option value="0">No</option>

                    <option value="1">Yes</option>
                </select>
            </div>

            <!-- requires approval -->
            <div class="form-group">
                <label for="requires_approval">Requires Approval</label>
                <select class="form-select" id="requires_approval" name="requires_approval">
                    <option value="0">No</option>
                    <option value="1">Yes</option>

                </select>
            </div>

            <!-- image -->
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control " id="image" name="image_url">
            </div>

            <div class="form-group my-3">
                <label for="price">Price</label>
                <input type="number" class="form-control" id="price" name="unit_price">
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
            <a href="{{ route('facilities.show', $facility->abbreviation) }}" class="btn btn-secondary">Cancel</a>
        </form>

    </div>

@endsection
