@extends('layouts.app')

@section('title', 'Add Product For Facility: ' . $facility->name)

@section('content')

    <div class="container py-2">
        <div class="row">
            <div class="col-md-12 mb-3">
                <h1>Add a Product ({{ $facility->name }})</h1>
                <a href="{{ route('facilities.edit', $facility->id) }}" class="btn btn-primary">Back to
                    {{ $facility->abbreviation }}</a>
            </div>

            <div class="col-md-6">

                <form action="{{ route('products.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="facility_id" value="{{ $facility->id }}">

                    <div class="form-group my-2">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>

                    <div class="form-group my-2">
                        <label for="category">Category</label>
                        <select class="form-select" id="category" name="category_id">
                            @foreach ($facility->categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                            @if ($facility->categories->isEmpty())
                                <option value="">No categories found</option>
                            @endif
                        </select>
                    </div>

                    <!-- is active-->
                    <div class="form-group my-2">
                        <label for="is_active">Is Active</label>
                        <select class="form-select" id="is_active" name="is_active">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <!-- is_deleted -->
                    <div class="form-group my-2">
                        <label for="is_deleted">Is Deleted</label>
                        <select class="form-select" id="is_deleted" name="is_deleted">
                            <option value="0">No</option>

                            <option value="1">Yes</option>
                        </select>
                    </div>

                    <!-- is a reservation -->
                    <div class="form-group my-2">
                        <label for="can_reserve">Enable Reservations</label>
                        <select class="form-select" id="can_reserve" name="can_reserve">
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>

                    {{-- if can_reserve, show "reservation interval" and "minimum length" --}}

                    {{-- if can_reserve, show "reservation interval" and "minimum length" --}}
                    <div class="form-group" id="reservation_interval"
                        >
                        <label for="reservation_interval">Reservation Interval (minutes)</label>
                        <input type="number" class="form-control" id="reservation_interval" name="reservation_interval"
                            >
                    </div>

                    <div class="form-group" id="minimum_length"
                        >
                        <label for="minimum_reservation_length">Minimum Reservation Length (minutes)</label>
                        <input type="number" class="form-control" id="minimum_reservation_length"
                            name="minimum_reservation_time">

                    </div>

                    <div class="form-group" id="maximum_length"
                        >
                        <label for="maximum_reservation_length">Maximum Reservation Length (minutes)</label>
                        <input type="number" class="form-control" id="maximum_reservation_length"
                            name="maximum_reservation_time">
                    </div>

                    <!-- requires approval -->
                    <div class="form-group my-2">
                        <label for="requires_approval">Requires Approval</label>
                        <select class="form-select" id="requires_approval" name="requires_approval">
                            <option value="0">No</option>
                            <option value="1">Yes</option>

                        </select>
                    </div>

                    <!-- recharge_account will default to the facility's recharge account, but you can override it -->
                    <div class="form-group my-2">
                        <label for="recharge_account">Recharge Account</label>
                        <input type="text" class="form-control" id="recharge_account" name="recharge_account"
                            value="{{ $facility->recharge_account }}">
                    </div>

                <div class="form-group my-2">
                    <label for="recharge_object_code">Recharge Object Code</label>
                    <input type="text" class="form-control" id="recharge_object_code" name="recharge_object_code" value="{{ old('recharge_object_code') }}">
                </div>

                <div class="mb-3">
                    <label for="purchase_price" class="form-label">Purchase Price</label>
                    <input type="number" step="0.01" class="form-control" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price ?? '') }}">
                </div>
                
                <div class="mb-3">
                    <label for="size" class="form-label">Size</label>
                    <input type="text" class="form-control" id="size" name="size" value="{{ old('size', $product->size ?? '') }}">
                </div>

                    <!-- image -->

                    <!-- alert about setting pricing: you can set pricing after creating the product and editing its price groups -->
                    <div class="alert alert-info">
                        <p>You can set pricing after creating the product and editing its price groups.</p>
                    </div>



                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="{{ route('facilities.show', $facility->abbreviation) }}"
                        class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>

    </div>

@endsection
