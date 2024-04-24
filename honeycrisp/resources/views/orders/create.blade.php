@extends('layouts.app')

@section('title', 'Create Order')

@section('content')
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <div class="container">

            <div class="row">
                <div class="col-md-12">
                    <h1>Create Order</h1>
                </div>
            </div>

            <div class="row">

                <div class="col-md-6">
                    <div class="accordion my-2" id="facilityInformationAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="facilityInformationHeading">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#facilityInformationCollapse" aria-expanded="true"
                                    aria-controls="facilityInformationCollapse">
                                    Facility Information
                                </button>
                            </h2>
                            <div id="facilityInformationCollapse" class="accordion-collapse collapse show"
                                aria-labelledby="facilityInformationHeading" data-bs-parent="#facilityInformationAccordion">
                                <div class="accordion-body">
                                    <p class="card-text">
                                        <a href="{{ route('facilities.show', $facility->id) }}">
                                            {{ $facility->name }}
                                            ({{ $facility->abbreviation }})
                                        </a>
                                    </p>
                                    <p class="card-text"><a href="mailto:{{ $facility->email }}">{{ $facility->email }}</a>
                                    <p class="card-text">{{ $facility->description }}</p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="col-md-6">
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
                                        value="jrs06005 (Salisbury, Joel R)" readonly>
                                </div>

                                <!-- Payment Account -->
                                <div class="form-group my-2">
                                    <label for="payment_account">Payment Account:</label>
                                    <input type="text" name="payment_account" id="payment_account"
                                        value="KFS-6215250-6610 (WellSCAN PHA 2021)" class="form-control" value="">
                                </div>

                                <!-- Price Group -->

                                <div class="form-group my-2">
                                    <label for="price_group">Price Group:</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="flexSwitchCheckChecked">
                                        <label class="form-check-label" for="flexSwitchCheckChecked">Use External
                                            Rates</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row bg-dark text-white">
            <div class="row my-3 py-3">
                <div class="col-md-8">
                    <h3>Available Items</h3>

                    <!-- little search box start typing and it filters the products -->
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Search for products" aria-label="Search"
                            aria-describedby="button-addon2">
                        <button class="btn btn-secondary text-white" type="button" id="button-addon2">Search</button>
                    </div>

                    <!-- button for add custom product -->
                    <div class="form-group my-2 text-end">
                        <button 
                            data-bs-toggle="modal" 
                            data-bs-target="#addCustomItemModal"
                        type="button" class="btn btn-primary">
                            Add Custom Item
                        </button>
                    </div>

                    <!-- list of products in a ul -->
                    <ul class="list-group">
                        <li class="list-group-item"><a href="#" class="list-group-item-action text-accent">Student
                                Web Development ($25 per hour)</a></li>
                        <li class="list-group-item"><a href="#" class="list-group-item-action text-accent">Facility
                                Director Time ($150 per hour)</a>
                        </li>
                        <li class="list-group-item"><a href="#" class="list-group-item-action text-accent">Senior
                                Application Developer ($105 per hour)</a></li>
                        <li class="list-group-item"><a href="#" class="list-group-item-action text-accent">Graduate
                                Student Times ($51 per hour)</a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Current Order</h5>

                    <div class="alert alert-info">
                        <!-- Cart-like list of products with remove and quantity -->
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Student Web Development <br> $137.50
                                <div class="input-group input-group-sm" style="width: 100px;">
                                    <input type="number" class="form-control" value="5.5" min="1">
                                    <button class="btn btn-danger btn-sm">X</button>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Facility Director Time <br> $300.00
                                <div class="input-group input-group-sm" style="width: 100px;">
                                    <input type="number" class="form-control" value="2.0" min="1">
                                    <button class="btn btn-danger btn-sm">X</button>
                                </div>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Senior Application Developer <br> $315.00
                                <div class="input-group input-group-sm" style="width: 100px;">
                                    <input type="number" class="form-control" value="3.0" min="1">
                                    <button class="btn btn-danger btn-sm">X</button>
                                </div>
                            </li>
                        </ul>


                    </div>

                    <!-- total price align right -->
                    <div class="my-4 text-end">
                        <h5>Total: $752.50</h5>
                    </div>

                    <div class="form-group my-2 text-end">
                        <button type="button" class="btn btn-outline-danger">Clear Order</button>
                        <button type="button" class="btn btn-primary">Save Draft</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal that asks quantity and notes -->
        <div class="modal fade" id="addCustomItemModal" tabindex="-1" aria-labelledby="addCustomItemModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCustomItemModalLabel">Add Item
                            <!-- badge for total price -->
                            <span class="badge bg-primary">$137.50</span>
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body my-2">
                        <div class="form-group my-2">
                            <label for="customItemName">Item Name:</label>
                            <input value="Student Web Development ($25.00 per hour)" disabled type="text" name="customItemName" id="customItemName" class="form-control">
                        </div>

                        <div class="form-group my-2">
                            <label for="customItemPrice">Unit Price:</label>
                            <input type="number" name="customItemPrice" id="customItemPrice" value="25.00" disabled class="form-control">
                        </div>

                        <div class="form-group my-2">
                            <label for="customItemQuantity">Quantity:</label>
                            <input type="number" value="5.5" name="customItemQuantity" id="customItemQuantity" class="form-control">
                        </div>

                        <div class="form-group my-2">
                            <label for="customItemNotes">Notes:</label>
                            <textarea name="customItemNotes" id="customItemNotes" class="form-control">Student web development (Marisa Morneau) for 5.5 hours.</textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Add Item</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
