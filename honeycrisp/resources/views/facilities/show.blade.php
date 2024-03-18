@extends('layouts.app')

@section('title', 'Single Facility View')

@section('content')
    <!-- card for creating a new facility -->

    <div class="container container-single-facility">

        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h2>{{ $facility->name }} ({{ $facility->abbreviation }})
                            <span class="badge bg-primary">{{ $facility->status }}</span>
                        </h2>
                    </div>
                    <div class="card-body">
                        <p><a href="/facilities/{{ $facility->id }}/edit" class="btn btn-primary">Edit</a></p>
                        <p><a href="/facilities" class="btn btn-secondary">Back to all facilities</a></p>
                        <p>{{ $facility->description }}</p>
                    </div>
                </div>

                <!-- list of services offered by the facility -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3>Services Offered</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @forelse ($facility->services as $service)
                                <li class="list-group-item">{{ $service->name }} - {{ $service->description }}</li>
                            @empty
                                <li class="list-group-item">No services offered at this time</li>
                            @endforelse
                        </ul>


                    </div>

                    <div class="card-footer">
                        <a href="
                        /facilities/{{ $facility->id }}/addservice" class="btn btn-primary">Add a Service</a>
                    </div>

                </div>

                <!-- list of products offered by the facility -->
                <div class="card">
                    <div class="card-header">
                        <h3>Products Offered</h3>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @forelse ($facility->products as $product)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $product->name }} - {{ $product->description }}
                                    <button class="btn btn-primary">Add to Order</button>
                                </li>
                            @empty
                                <li class="list-group-item">No products offered at this time</li>
                            @endforelse
                        </ul>
                    </div>

                    <div class="card-footer">
                        <a href="/products/create" class="btn btn-primary">Add a Product</a>
                    </div>


                </div>


            </div>

        </div>

    </div>

@endsection
