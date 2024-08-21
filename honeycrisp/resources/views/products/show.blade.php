@extends('layouts.app')
@section('title', 'Product Details: ' . $product->name)

@section('content')

    <div class="container">
        <div class="row my-4">
            <div class="col-md-8">
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
                        <p><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
                        <div class="m-3 p-3 border border-primary">
                            <h3>Pricing</h3>
                            @if ($product->priceGroups->isEmpty())
                            <p>No price groups found for this product.</p>
                            <a href="{{ route('price-groups.create', $product->id) }}" class="btn btn-primary">Add Price Group</a>
                        @else
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($product->priceGroups as $priceGroup)
                                        <tr>
                                            <td>{{ $priceGroup->name }}</td>
                                            <td>{{ $priceGroup->start_date }}</td>
                                            <td>{{ $priceGroup->end_date }}</td>
                                            <td>{{ $priceGroup->price ?? 'N/A' }}</td>
                                            <td>
                                                <a href="{{ route('price-groups.edit', $priceGroup->id) }}" class="btn btn-primary">Edit</a>
                                                <form action="{{ route('price-groups.destroy', $priceGroup->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <a href="{{ route('price-groups.create', $product->id) }}" class="btn btn-primary">Add Price Group</a>
                        @endif

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