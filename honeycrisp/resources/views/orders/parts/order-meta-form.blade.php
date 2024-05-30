<div class="container">
    <!-- Header Section -->
    <div class="row my-3">
        <div class="col-md-12">
            <h1>{{ isset($order) ? 'Edit Order #' . $order->id : 'Create Order' }}</h1>
            <h2>{{ $facility->name }} ({{ $facility->abbreviation }})</h2>
        </div>
    </div>

    <!-- Form Section -->
    <form action="{{ isset($order) ? route('orders.update', ['order' => $order]) : route('orders.store') }}" method="POST" class="order-meta-form">
        @csrf
        @if (isset($order))
        @method('PUT')
        @endif

        <input type="hidden" readonly name="facility_id" value="{{ $facility->id }}">

        <div class="row pb-3">
            <div class="col-md-6">
                <!-- Order Details Accordion -->
                <div class="accordion my-2" id="facilityInformationAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="facilityInformationHeading">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#facilityInformationCollapse" aria-expanded="true" aria-controls="facilityInformationCollapse">
                                Order Details
                            </button>
                        </h2>
                        <div id="facilityInformationCollapse" class="accordion-collapse collapse show" aria-labelledby="facilityInformationHeading" data-bs-parent="#facilityInformationAccordion">
                            <div class="accordion-body">
                                <div class="form-group my-2">
                                    <label for="title">Title:</label>
                                    <input value="{{ old('title', isset($order) ? $order->title : '') }}" type="text" name="title" id="title" class="form-control">
                                </div>

                                <div class="form-group my-2">
                                    <label for="description">Description:</label>
                                    <textarea name="description" id="description" class="form-control">{{ old('description', isset($order) ? $order->description : '') }}</textarea>
                                </div>

                                <div class="form-group my-2">
                                    <label for="date">Date:</label>
                                    <input type="date" name="date" id="date" class="form-control" value="{{ old('date', isset($order) ? $order->date : '') }}">
                                </div>

                                @if (isset($order))
                                <div class="form-group my-2">
                                    <label for="status">Status:</label>
                                    <select name="status" id="status" class="form-select">
                                        <option value="">Select a Status</option>
                                        <option value="draft" @if (old('status', isset($order) && $order->status == 'draft')) selected @endif>Draft</option>
                                        <option value="pending" @if (old('status', isset($order) && $order->status == 'pending')) selected @endif>Pending</option>
                                        <option value="approved" @if (old('status', isset($order) && $order->status == 'approved')) selected @endif>Approved</option>
                                        <option value="in_progress" @if (old('status', isset($order) && $order->status == 'in_progress')) selected @endif>In Progress</option>
                                        <option value="complete" @if (old('status', isset($order) && $order->status == 'complete')) selected @endif>Complete</option>
                                    </select>
                                </div>
                                @endif



                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <!-- Customer Information Accordion -->
                <div class="accordion my-2" id="customerInformationAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="customerInformationHeading">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#customerInformationCollapse" aria-expanded="true" aria-controls="customerInformationCollapse">
                                Customer Information
                            </button>
                        </h2>
                        <div id="customerInformationCollapse" class="accordion-collapse collapse show" aria-labelledby="customerInformationHeading" data-bs-parent="#customerInformationAccordion">
                            <div class="accordion-body">
                                <div class="form-group my-2">
                                    <label for="user_id">Ordering for User:</label>
                                    <select hx-get="{{ route('orders.create') }}/{{ $facility->abbreviation }}" hx-select="#user_accounts" hx-target="#user_accounts" hx-indicator="#user_accounts" hx-trigger="change" hx-swap="outerHTML" hx-push-url="true" name="user_id" id="user_id" class="form-select">
                                        <option value="">Select a User</option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->netid }}" @if (old('user_id', isset($selected_user) && $selected_user==$user->id)) selected @endif>
                                            {{ $user->name }} ({{ $user->netid }})
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="user_accounts" class="form-group my-2 py-2">

                                    @if ($accounts != null && count($accounts) > 0)
                                    <label for="payment_account">Payment Account:</label>
                                    <select name="payment_account" id="payment_account" class="form-select">
                                        <option value="">Select a Payment Account</option>
                                        @foreach ($accounts as $payment_account)
                                        <option value="{{ $payment_account->id }}" @if (old('payment_account', isset($order) && $order->payment_account == $payment_account->id)) selected @endif>
                                            {{ $payment_account->account_name }} ({{ strtoupper($payment_account->account_type) }}-{{ $payment_account->account_number }})
                                        </option>
                                        @endforeach
                                    </select>
                                    @elseif ($accounts == null)
                                    <div class="alert alert-warning" role="alert">
                                        Select a User to see Payment Accounts
                                    </div>
                                    @elseif (count($accounts) == 0 && $accounts != null)
                                    <div class="alert alert-warning" role="alert">
                                        No Payment Accounts found for this User
                                    </div>
                                    @endif

                                    @if (isset($account_warning))
                                    <div class="alert alert-warning" role="alert">
                                        {{ $account_warning }}
                                    </div>
                                    @endif

                                   
                                </div>

                                <div class="form-group my-2">
                                    <label for="price_group">Price Group:</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked">
                                        <label class="form-check-label" for="flexSwitchCheckChecked">Use External Rates</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-md-6">
                @if (isset($order))
                <button type="submit" id="save-draft" class="btn btn-primary">Save Order Details</button>
                @else
                <button type="submit" id="save-draft" class="btn btn-primary">Save Draft and Add items <i class="bi bi-arrow-right"></i></button>
                @endif
            </div>
        </div>
    </form>
</div>