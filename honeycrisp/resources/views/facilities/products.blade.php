@extends('layouts.app')

@section('title', 'Edit Facility')

@section('content')
<div class="container py-2">
    <div class="row my-3">
        <div class="col-md-12">
            <h1>Manage Facility: {{ $facility->name }}</h1>
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
                    <a href="{{route('facilities.edit', $facility->id) }}" class="nav-link">Facility Information</a>

                </li>

                <li class="nav-item">
                    <a href="#facility-products"
                     class="nav-link active"> Products</a>
                </li>


                @if($facility->orders->where('status', 'invoice')->count() > 0)
                <li class="nav-item">
                    {{-- pending invoice exports with count of orders marked invoice --}}
                    <a class="nav-link" href="#invoices">Export Invoices ({{ $facility->orders->where('status', 'invoice')->count() }})</a>
                </li>
                @endif

            </ul>
        </div>
        <div class="col-md-8">
            <h3>Products & Services Available</h3>
            <ul class="nav nav-tabs" id="facilityTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="products-tab" data-bs-toggle="tab" href="#products" role="tab" aria-controls="products" aria-selected="true">Products</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="categories-tab" data-bs-toggle="tab" href="#categories" role="tab" aria-controls="categories" aria-selected="false">Product Categories</a>
                </li>
            </ul>
            <div class="tab-content" id="facilityTabContent">
                <div class="tab-pane fade show active" id="products" role="tabpanel" aria-labelledby="products-tab">
                    <div id="facility-products" class="products-services my-4">
                       
                        <a class="btn btn-primary" href="{{ route('products.create', ['facilityAbbreviation' => $facility->abbreviation]) }}">
                            Add Product
                        </a>

                        {{-- lil search form --}}

                        <form
                        onsubmit="return false" 
                        class="mt-3" action="{{ route('facilities.products', $facility->id) }}" method="GET">
                            <div class="form-group">
                                <input class="form-control" type="text" id="product_search" name="search" 
                                placeholder="Search for a product" aria-label="Product Search" 
                                aria-describedby="search"
                                hx-get="{{ route('facilities.products', $facility->id) }}"
                                hx-trigger="keyup changed delay:500ms"
                                hx-target="#facility_products"
                                hx-select="#facility_products"
                                hx-swap="outerHTML"
                                hx-push-url="false"
                                value="{{ request('search') }}">
                            </div>

                            {{-- lil filter by category --}}
                            <div class="form-group">
                                <select class="form-select mt-3" id="category_filter" name="category_id" 
                                hx-get="{{ route('facilities.products', $facility->id) }}"
                                hx-trigger="change"
                                hx-target="#facility_products"
                                hx-select="#facility_products"
                                hx-swap="outerHTML"
                                aria-label="Product Category Filter"
                                aria-describedby="filter">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>

                        @if ($facility->products->isEmpty())
                        <div class="alert alert-info my-3">
                            <p>No products available at this time.</p>
                        </div>
                        @else
                        <ul id="facility_products" class="list-group my-4">
                            @foreach ($products as $product)
                            @if($product->is_deleted == 0)
                            <li class="list-group-item">
                                <a href="{{ route('products.show', $product->id) }}">
                                    {{ $product->name }} 

                                    <span class="float-end">
                                       {{-- badge for category --}}
                                        @if($product->category)
                                         <span class="badge bg-primary">{{ $product->category->name }}</span>
                                        @endif
                                    </span>
                                </a>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade" id="categories" role="tabpanel" aria-labelledby="categories-tab">
                    <div id="facility-categories" class="product-categories my-4">
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
                </div>
            </div>
        </div>
    </div>
</div>

@endsection