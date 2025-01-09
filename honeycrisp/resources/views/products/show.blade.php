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
                        <form class="my-2" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline;">
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
        <div class="my-5">
            <h3>Reservation Availability</h3>
    
            <p class="mb-2">To allow users to reserve this product or equipment, it must have a defined set of schedule rules.</p>
            <a href="{{ route('schedule-rules.create', ['product_id' => $product->id]) }}" class="btn btn-primary mb-4">Add Schedule Rule</a>
    
            @if ($product->scheduleRules->isEmpty())
                <div class="alert alert-warning">No schedule rules found for this product.</div>
            @else
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Day</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product->scheduleRules as $rule)
                            <tr>
                                <td>{{ ucfirst($rule->day) }}</td>
                                <td>{{ $rule->time_of_day_start }}</td>
                                <td>{{ $rule->time_of_day_end }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

            <div class="mt-5">
                <h3>Reservations</h3>
                

                <a href="{{ route('reservations.create.product', ['product' => $product->id]) }}" class="btn btn-primary mb-2">Add Reservation</a>

                @if ($product->reservations->isEmpty())
                    <p>No reservations found for this product.</p>
                @else
                    @foreach ($product->reservations as $reservation)
                        <div class="card mb-3">
                            <div class="card-body">
                                <p><strong>Reservation Start:</strong> {{ $reservation->reservation_start }}</p>
                                <p><strong>Reservation End:</strong> {{ $reservation->reservation_end }}</p>
                                <p><strong>Status:</strong> {{ $reservation->status }}</p>
                                <p><strong>Account Type:</strong> {{ $reservation->account_type }}</p>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-primary">View</a>
                                <a href="{{ route('reservations.edit', $reservation->id) }}" class="btn btn-primary">Edit</a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @endif

    </div>

@endsection