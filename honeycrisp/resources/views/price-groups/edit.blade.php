@extends('layouts.app')

@section('title', 'Edit Price Group')

@section('content')

<div class="container py-2">
    <div class="row">
        <div class="col-md-12 mb-3">
            <h1>Edit Price Group for Product: {{ $priceGroup->product->name }}</h1>
            <a href="{{ route('products.show', $priceGroup->product) }}" class="btn btn-primary">Back to Product</a>
        </div>

        <div class="col-md-8">

            <form action="{{ route('price-groups.update', $priceGroup->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="product_id" value="{{ $priceGroup->product->id }}">

                <div class="form-group my-2">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $priceGroup->name) }}" required>
                </div>

                <div class="form-group my-2">
                    <label for="description">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $priceGroup->description) }}</textarea>
                </div>

                <div class="form-group my-2">
                    <label for="price">Price</label>
                    <input type="number" step="0.50" class="form-control" id="price" name="price" value="@dollars(old('price', $priceGroup->price))" required>
                </div>

                <div class="form-group my-2">
                    <label for="start_date">Start Date</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $priceGroup->start_date) }}" required>
                </div>

                <div class="form-group my-2">
                    <label for="end_date">End Date</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $priceGroup->end_date ? $priceGroup->end_date : '') }}">
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('products.show', $priceGroup->product->id) }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

@endsection
