@extends('layouts.app')

@section('title', 'Create Order')

@section('content')
    <div class="container">
        <div class="row">
            <div clas="col-md-12">
                <h2>Create Order ({{ $facility->abbreviation }}) </h2>
                <!-- card for which facility the order is for -->
                <div class="card my-2">
                    <div class="card-body">
                        <p class="card-text">Facility Name: {{ $facility->name }}</p>
                        <p class="card-text">Facility Email: {{ $facility->email }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">

            <form action="{{ route('orders.store') }}" method="POST">
                @csrf

                <div class="col-md-12">
                    <h3>Order Details</h3>

                    <!-- Customer Information -->
                    <div class="accordion my-2" id="customerInformationAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="customerInformationHeading">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#customerInformationCollapse" aria-expanded="true"
                                    aria-controls="customerInformationCollapse">
                                    Customer Information
                                </button>
                            </h2>
                            <div id="customerInformationCollapse" class="accordion-collapse collapse show"
                                aria-labelledby="customerInformationHeading" data-bs-parent="#customerInformationAccordion">
                                <div class="accordion-body">
                                    <!-- User ID -->
                                    <div class="form-group my-2">
                                        <label for="user_id">Ordering for User:</label>
                                        <input type="text" name="user_id" id="user_id" class="form-control"
                                            value="{{ Auth::id() }}">
                                    </div>

                                    <!-- Payment Account -->
                                    <div class="form-group my-2">
                                        <label for="payment_account">Payment Account:</label>
                                        <input type="text" name="payment_account" id="payment_account"
                                            class="form-control" value="">
                                    </div>

                                    <!-- Price Group -->

                                    <div class="form-group my-2">
                                        <label for="price_group">Price Group:</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
                                            <label class="form-check-label" for="flexSwitchCheckChecked">Use External Rates</label>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row my-3 py-3">
                    <div class="col-md-8">
                        <h3>Available Items</h3>

                        <!-- little search box start typing and it filters the products -->
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Search for products" aria-label="Search"
                                aria-describedby="button-addon2">
                            <button class="btn btn-outline-secondary" type="button" id="button-addon2">Search</button>
                        </div>

                        <!-- button for add custom product -->
                        <div class="form-group my-2 text-end">
                            <button type="button" class="btn btn-primary">Add Custom Item</button>
                        </div>

                        <!-- list of products in a ul -->
                        <ul class="list-group">
                            <li class="list-group-item"><a href="#" class="list-group-item-action">Student Web Development ($25 per hour)</a></li>
                            <li class="list-group-item"><a href="#" class="list-group-item-action">Facility Director Time ($150 per hour)</a></li>
                            <li class="list-group-item"><a href="#" class="list-group-item-action">Senior Application Developer ($150 per hour)</a></li>
                            <li class="list-group-item"><a href="#" class="list-group-item-action">Graduate Student Times ($51 per hour)</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h3>Current Order</h3>
                        
                        <div class="alert alert-info">
                            <p>Order Total: $0.00</p>
                        </div>

                        <div class="form-group my-2 text-end">
                            <button type="submit" class="btn btn-primary">Submit Order</button>
                        </div>
                    </div>
                </div>

              




            </form>
        </div>

    @endsection
