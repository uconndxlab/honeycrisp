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
                        <p><strong>Can Reserve:</strong> {{ $product->can_reserve ? 'Yes' : 'No' }}</p>
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
                                            <td>@dollars($priceGroup->price)</td>
                                            <td>
                                                @can('update-facility', $product->facility)
                                                <a href="{{ route('price-groups.edit', $priceGroup->id) }}" class="btn btn-primary">Edit</a>
                                                <form action="{{ route('price-groups.destroy', $priceGroup->id) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                </form>
                                                @endcan
                                            </td>

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            @can('update-facility', $product->facility)
                            <a href="{{ route('price-groups.create', $product->id) }}" class="btn btn-primary">Add Price Group</a>
                            @endcan
                        @endif

                    </div>
                    <div class="card-footer">
                        @can('update-facility', $product)
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        @if ( $product->can_reserve )
            <div class="mt-5">
                <h3>Schedule Rules</h3>

                <p class="mb-2">In order for users to reserve products and equipment, the product must have a set of schedule rules.</p>

                @if ( $product->scheduleRules->isEmpty() )
                    <p class="mb-2">No schedule rules found for this product.</p>

                    <a href="{{ route('schedule-rules.create', ['product_id' => $product->id]) }}" class="btn btn-primary">Add Schedule Rule</a>
                @else
                    @dump($product->scheduleRules)
                @endif
            </div>

            <div class="mt-5">
                <h3>Reservations</h3>
    
                @if ($product->reservations->isEmpty())
                    <p>No reservations found for this product.</p>
                @else
                    @dump($product->reservations)
                @endif
            </div>
        @endif

    </div>

@endsection