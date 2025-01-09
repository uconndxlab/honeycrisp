@extends('layouts.app')

@section('title', 'Edit Product For Facility: ' . $product->facility->name)

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
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
                    <textarea class="form-control" id="description" name="description"
                        rows="3">{{ $product->description }}</textarea>
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <select class="form-select" id="category" name="category_id">
                        <option value="">Select a category</option>
                        @foreach($product->facility->categories as $category)
                        <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : ''
                            }}>{{ $category->name }}</option>
                        @endforeach
                        @if ($product->facility->categories->isEmpty()) <option value="">No categories found</option>
                        @endif
                    </select>
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

                <div class="form-group my-2">
                    <label for="can_reserve">Enable Reservations</label>
                    <select class="form-select" id="can_reserve" name="can_reserve">
                        <option value="0" @selected(!$product->can_reserve)>No</option>
                        <option value="1" @selected($product->can_reserve)>Yes</option>
                    </select>
                </div>

                {{-- if can_reserve, show "reservation interval" and "minimum length" --}}
                <div class="form-group" id="reservation_interval" style="display: {{ $product->can_reserve ? 'block' : 'none' }}">
                    <label for="reservation_interval">Reservation Interval (minutes)</label>
                    <input type="number" class="form-control" id="reservation_interval" name="reservation_interval"
                        value="{{ $product->reservation_interval }}">
                </div>

                <div class="form-group" id="minimum_length" style="display: {{ $product->can_reserve ? 'block' : 'none' }}">
                    <label for="minimum_reservation_length">Minimum Reservation Length (minutes)</label>
                    <input type="number" class="form-control" id="minimum_reservation_length"
                        name="minimum_reservation_time" value="{{ $product->minimum_reservation_time }}">

                </div>

                <div class="form-group" id="maximum_length" style="display: {{ $product->can_reserve ? 'block' : 'none' }}">
                    <label for="maximum_reservation_length">Maximum Reservation Length (minutes)</label>
                    <input type="number" class="form-control" id="maximum_reservation_length" name="maximum_reservation_time"
                        value="{{ $product->maximum_reservation_time }}">
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
                <div class="form-group mb-3">
                    <label for="image">Image</label>
                    <input type="file" class="form-control " id="image" name="image_url">
                </div>

                <!-- recharge_account will default to the facility's recharge account, but you can override it -->
                <div class="form-group my-2">
                    <label for="recharge_account">Recharge Account</label>
                    <input type="text" class="form-control" id="recharge_account" name="recharge_account"
                        value="{{ $product->recharge_account }}">
                </div>



                
                <button type="submit" class="btn btn-primary">Update Product</button>
                <a href="{{ route('facilities.show', $product->facility->abbreviation) }}"
                    class="btn btn-secondary">Cancel</a>
            </form>

        </div>
    </div>
</div>

@endsection