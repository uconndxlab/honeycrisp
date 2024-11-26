@extends('layouts.app')

@section('title', 'Facility Details')

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

                    <div class="card-footer">
                        @can('admin')
                        <a href="{{ route('facilities.edit', $facility->id) }}" class="btn btn-secondary">Manage</a>
                        {{-- <form action="{{ route('facilities.destroy', $facility->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form> --}}
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        <div id="facility-meta" class="row my-4">
            <div class="col-md-12">
                <h3>Products & Services Available</h3>

                @if ($facility->products->isEmpty())
                    <p>No products available at this time.</p>
                @else
                    @foreach ($facility->products->groupBy('category_id') as $categoryId => $products)
                        @php
                            $categoryName = $products->first()->category
                                ? $products->first()->category->name
                                : 'Uncategorized';
                        @endphp
                        <h4>{{ $categoryName }}</h4>
                        <ul class="list-group my-4">
                            @foreach ($products as $product)
                                <li class="list-group-item">
                                    <a href="{{ route('products.show', $product->id) }}">
                                        {{ $product->name }} - (${{ $product->unit_price }} each)
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endforeach

                @endif
            </div>
        </div>

        <div id="facility-categories" class="row my-4">
            <div class="col-md-12">
                <h3>Product Categories</h3>

                @if ($facility->categories->isEmpty())
                    <p>No categories available at this time.</p>
                @else
                    <ul class="list-group">
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
@endsection
