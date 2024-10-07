@extends('layouts.app')

@section('title', 'Edit Facility')

@section('content')
<div class="container py-2">
    <div class="row my-3">
        <div class="col-md-12">
            <h1>Edit Facility</h1>
        </div>
        <!-- Display any form errors here -->
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div class="row">
        <div class="col-md-2 subnav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="#facility-meta" 
                    class="nav-link">Facility Information</a>
                </li>
                <li class="nav-item">
                    <a href="#facility-products"
                     class="nav-link"> Products & Services</a>
                </li>

                <li class="nav-item">
                    <a href="#facility-categories"
                    class="nav-link"> Product Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#staff">Facility Staff</a>
                </li>
            </ul>
        </div>
        <div class="col-md-6">
            <div id="facility-meta" class="facility-information">
                <h3 class="mb-3">{{ $facility->name }} Info</h3>

                <!-- Form for editing the facility -->
                <form action="{{ route('facilities.update', $facility->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Facility Name -->
                    <div class="form-group mb-2">
                        <label for="name">Facility Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $facility->name) }}" required>
                    </div>

                    <!-- Description -->
                    <div class="form-group mb-2">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="5" required>{{ old('description', $facility->description) }}</textarea>
                    </div>

                    

                    <!-- Abbreviation -->
                    <div class="form-group mb-2">
                        <label for="abbreviation">Abbreviation</label>
                        <input type="text" name="abbreviation" id="abbreviation" class="form-control" value="{{ old('abbreviation', $facility->abbreviation) }}" required>
                    </div>

                    <!-- Email -->
                    <div class="form-group mb-2">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $facility->email) }}" required>
                    </div>

                    <!-- Address -->
                    <div class="form-group mb-2">
                        <label for="address">Address</label>
                        <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $facility->address) }}">
                    </div>

                    <!-- Recharge Account -->
                    <div class="form-group mb-2">
                        <label for="recharge_account">Recharge Account</label>
                        <input type="text" name="recharge_account" id="recharge_account" class="form-control" value="{{ old('recharge_account', $facility->recharge_account) }}" required>

                    </div>

                    <!-- Account Type -->
                    <div class="form-group mb-2">
                        <label for="account_type">Account Type</label>
                        <select name="account_type" id="account_type" class="form-select" required>
                            <option value="kfs" @if(old('account_type', $facility->account_type) == 'kfs') selected @endif>KFS</option>
                            <option value="uch" @if(old('account_type', $facility->account_type) == 'uch') selected @endif>Banner/UCH</option>
                            <option value="other" @if(old('account_type', $facility->account_type) == 'other') selected @endif>Other</option>
                        </select>
                    </div>



                    <!-- Submit Button -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save Facility</button>
                    </div>
                </form>
            </div>
            <div id="facility-products" class="products-services my-4">
                <h3>Products & Services Available</h3>
                <a href="{{ route('products.create') }}/{{$facility->abbreviation}} " class="btn btn-primary">Add Product</a>

                @if ($facility->products->isEmpty())
                <div class="alert alert-info my-3">
                    <p>No products available at this time.</p>
                </div>
                @else

                <ul class="list-group my-4">
                    @foreach ($facility->products as $product)
                    @if($product->is_deleted == 0)
                    <li class="list-group-item">
                        <a href="{{ route('products.show', $product->id) }}">
                            {{ $product->name }} 
                        </a>
                    </li>
                    @endif
                    @endforeach

                </ul>
                @endif
            </div>

            <div id="facility-categories" class="product-categories my-4">
                <h3>Product Categories</h3>
                <a href="{{ route('categories.create') }}/{{$facility->abbreviation}}" class="btn btn-primary">Add Category</a>

                @if ($facility->categories->isEmpty())
                <div class="alert alert-info my-3">
                    <p>No categories available at this time.</p>
                </div>
                @else

                <ul class="list-group my-4">
                    @foreach ($facility->categories as $category)
                    <li class="list-group-item">
                        <a href="{{ route('categories.show', $category->id) }}">
                            {{ $category->name }}
                        </a>
                    </li>
                    @endforeach

                </ul>
                @endif
            </div>

            <div class="#staff">
                <h3>Facility Staff</h3>
                
            </div>
        </div>
    </div>
</div>

@endsection