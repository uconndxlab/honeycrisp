@extends('layouts.app')

@section('title', 'Create Order')

@section('content')
<form action="{{ route('orders.store') }}" method="POST">
    @csrf
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <h1>Create Order</h1>
                <!-- facility name and abbreviation -->
                <h2>{{ $facility->name }} ({{ $facility->abbreviation }})</h2>
            </div>
        </div>

        <div class="row pb-3">

            <div class="col-md-6">
                <div class="accordion my-2" id="facilityInformationAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="facilityInformationHeading">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#facilityInformationCollapse" aria-expanded="true" aria-controls="facilityInformationCollapse">
                                Order Details
                            </button>
                        </h2>
                        <div id="facilityInformationCollapse" class="accordion-collapse collapse show" aria-labelledby="facilityInformationHeading" data-bs-parent="#facilityInformationAccordion">
                            <div class="accordion-body">
                                <!-- form for order details like title, description, status, and date -->
                                <div class="form-group my-2">
                                    <label for="title">Title:</label>
                                    <input value="WellSCAN Project April 2024" type="text" name="title" id="title" class="form-control">
                                </div>

                                <div class="form-group my-2">
                                    <label for="description">Description:</label>
                                    <textarea name="description" id="description" class="form-control">This order is for the WellSCAN project in April 2024.</textarea>
                                </div>

                                <div class="form-group" my-2>
                                    <label for="status">Status:</label>
                                    <select name="status" id="status" class="form-select">
                                        <option selected value="draft">Draft</option>
                                        <option value="submitted">Submitted</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>

                                <div class="form-group my-2">
                                    <label for="date">Date:</label>
                                    <input type="date" name="date" id="date" class="form-control">
                                </div>

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
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#customerInformationCollapse" aria-expanded="true" aria-controls="customerInformationCollapse">
                                Customer Information
                            </button>
                        </h2>
                        <div id="customerInformationCollapse" class="accordion-collapse collapse show" aria-labelledby="customerInformationHeading" data-bs-parent="#customerInformationAccordion">
                            <div class="accordion-body">
                                <!-- User ID -->
                                <div class="form-group my-2">
                                    <label for="user_id">Ordering for User:</label>
                                    <select 
                                    hx-get = "{{ route('orders.create') }}/{{ $facility->abbreviation }}"
                                    hx-select="#user_accounts"
                                    hx-target="#user_accounts"
                                    hx-indicator="#user_accounts"
                                    hx-trigger="change"
                                    hx-swap="outerHTML"
                                    hx-push-url="true"
                                    name="user_id" id="user_id" class="form-select">
                                        <option value="">Select a User</option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->netid }}" @if ($selected_user==$user->id) selected @endif>{{ $user->name }} ({{ $user->netid }})</option>
                                        @endforeach

                                    </select>
                                </div>

                                <!-- Payment Account  is not null -->
                                <div id="user_accounts" class="form-group my-2">
                                    @if ($accounts != null)
                                    <label for="payment_account">Payment Account:</label>
                                    <select
                                    name="payment_account" id="payment_account" class="form-select">
                                        <option value="">Select a Payment Account</option>
                                        @foreach ($accounts as $payment_account)
                                        <option value="{{ $payment_account }}">{{ $payment_account->account_name }} ({{ strtoupper($payment_account->account_type) }}-{{ $payment_account->account_number }})</option>
                                        @endforeach

                                    </select>
                                    @else
                                    <div class="alert alert-warning" role="alert">
                                        Select a User to see Payment Accounts
                                    </div>
                                    @endif
                                </div>

                                <!-- Price Group -->

                                <div class="form-group my-2">
                                    <label for="price_group">Price Group:</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked">
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

        <!-- Products Section -->
        <div class="bg-dark">
            <div class="container">
                <div class="row bg-dark text-white">
                    <div class="row my-3 py-3">
                        <div class="col-md-8">
                            <h3>Available Products</h3>

                            <!-- little search box start typing and it filters the products -->
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Search for products" aria-label="Search" aria-describedby="button-addon2">
                                <button class="btn btn-secondary text-white" type="button" id="button-addon2">Search</button>
                            </div>

                            <!-- button for add custom product -->
                            <div class="form-group my-2 text-end">
                                <button data-bs-toggle="modal" data-bs-target="#addCustomItemModal" type="button" class="btn btn-primary">
                                    Add Custom Item
                                </button>
                            </div>

                            <!-- list of products in a ul -->
                            <ul class="list-group">

                                @foreach ($facility->products as $product)
                                <li class="list-group-item">
                                    <a href="#" class="list-group-item-action add-product" 
                                       data-id="{{ $product->id }}" 
                                       data-name="{{ $product->name }}" 
                                       data-price="{{ $product->unit_price }}" 
                                       data-unit="{{ $product->unit }}">{{ $product->name }} (${{ $product->unit_price }} per {{ $product->unit }})</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-md-4">

                            <div class="alert alert-info">
                                <h5 class="text-dark">Current Order</h5>

                                <!-- Cart-like list of products with remove and quantity -->
                                <ul class="list-group" id="current-order-items">
                                    <!-- dynamically added items will appear here -->
                                </ul>
                            </div>

                            <!-- total price align right -->
                            <div class="my-4 text-end">
                                <h5 class="text-white" id="total-price">Total: $0.00</h5>
                            </div>

                            <div class="form-group my-2 text-end">
                                <button type="button" class="btn btn-outline-light" id="clear-order">Clear Order</button>
                                <button type="submit" class="btn btn-primary" id="save-draft">Save Draft</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modal that asks quantity and notes -->
                <div class="modal fade" id="addCustomItemModal" tabindex="-1" aria-labelledby="addCustomItemModalLabel" aria-hidden="true">
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
                                <button type="button" class="btn btn-primary add-item">Add Item</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addItemModal = new bootstrap.Modal(document.getElementById('addCustomItemModal'));

    // Initialize an empty array to store order items
    let orderItems = [];

    // Function to update the order items list and total price
    function updateOrderSummary() {
        const orderItemsContainer = document.getElementById('current-order-items');
        const totalPriceElement = document.getElementById('total-price');

        // Clear the current list
        orderItemsContainer.innerHTML = '';

        let total = 0;

        // Populate the order items
        orderItems.forEach((item, index) => {
            const itemElement = document.createElement('li');
            itemElement.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');

            itemElement.innerHTML = `
                ${item.name} <br> $${item.price} x ${item.quantity}
                <div class="input-group input-group-sm" style="width: 100px;">
                    <input type="number" class="form-control quantity" value="${item.quantity}" min="1" data-index="${index}">
                    <button class="btn btn-danger btn-sm remove-item" data-index="${index}">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            `;

            orderItemsContainer.appendChild(itemElement);

            // Add to total price
            total += item.price * item.quantity;
        });

        // Update total price
        totalPriceElement.textContent = `Total: $${total.toFixed(2)}`;
    }

    // Event listener for adding a product
    document.querySelectorAll('.add-product').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const price = parseFloat(this.getAttribute('data-price'));
            const unit = this.getAttribute('data-unit');

            // Add item to the orderItems array
            orderItems.push({
                id,
                name,
                price,
                quantity: 1
            });

            // Update the order summary
            updateOrderSummary();
        });
    });

    // Event listener for removing an item
    document.getElementById('current-order-items').addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-item') || event.target.parentNode.classList.contains('remove-item')) {
            const index = event.target.getAttribute('data-index') || event.target.parentNode.getAttribute('data-index');
            orderItems.splice(index, 1);
            updateOrderSummary();
        }
    });

    // Event listener for changing quantity
    document.getElementById('current-order-items').addEventListener('input', function(event) {
        if (event.target.classList.contains('quantity')) {
            const index = event.target.getAttribute('data-index');
            const quantity = parseFloat(event.target.value);
            orderItems[index].quantity = quantity;
            updateOrderSummary();
        }
    });

    // Event listener for clearing the order
    document.getElementById('clear-order').addEventListener('click', function() {
        orderItems = [];
        updateOrderSummary();
    });

    // Event listener for saving the draft
    document.getElementById('save-draft').addEventListener('click', function() {
        const orderForm = document.querySelector('form');

        // Create hidden inputs for each order item
        orderItems.forEach((item, index) => {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = `items[${index}][id]`;
            idInput.value = item.id;

            const nameInput = document.createElement('input');
            nameInput.type = 'hidden';
            nameInput.name = `items[${index}][name]`;
            nameInput.value = item.name;

            const priceInput = document.createElement('input');
            priceInput.type = 'hidden';
            priceInput.name = `items[${index}][price]`;
            priceInput.value = item.price;

            const quantityInput = document.createElement('input');
            quantityInput.type = 'hidden';
            quantityInput.name = `items[${index}][quantity]`;
            quantityInput.value = item.quantity;

            orderForm.appendChild(idInput);
            orderForm.appendChild(nameInput);
            orderForm.appendChild(priceInput);
            orderForm.appendChild(quantityInput);
        });

        // Submit the form
        orderForm.submit();
    });
});
</script>

@endsection
