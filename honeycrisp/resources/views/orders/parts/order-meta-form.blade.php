<div class="container">
    <!-- Header Section -->
    <div class="row my-3">
        <div class="col-md-12">
            <h1>{{ isset($order) ? 'Edit Order #' . $order->id : 'Create Order' }}
                <!-- status badge -->
                @if (isset($order))
                    <span class="badge badge-{{ $order->status_color }}">{{ Str::headline($order->status) }}</span>
                @endif
                <!-- if order is quote, a button to send to customer -->
                @if (isset($order) && $order->status == 'quote')
                    <a href="{{ route('orders.sendToCustomer', ['order' => $order]) }}" class="btn btn-outline-primary">
                        <i class="bi bi-envelope"></i> Send to Customer</a>
                @endif
            </h1>
            <h2>{{ $facility->name }} ({{ $facility->abbreviation }})</h2>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ isset($order) ? route('orders.update', ['order' => $order]) : route('orders.store') }}"
        method="POST" class="order-meta-form">
        @csrf
        @if (isset($order))
            @method('PUT')
        @else
            @method('POST')
        @endif

        <input type="hidden" name="facility_id" value="{{ $facility->id }}">

        <div class="row pb-3">
            <div class="col-md-6">
                <!-- Order Details Accordion -->
                <div class="accordion my-2" id="facilityInformationAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="facilityInformationHeading">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#facilityInformationCollapse" aria-expanded="true"
                                aria-controls="facilityInformationCollapse">
                                Order Details
                            </button>
                        </h2>
                        <div id="facilityInformationCollapse" class="accordion-collapse collapse show"
                            aria-labelledby="facilityInformationHeading" data-bs-parent="#facilityInformationAccordion">
                            <div class="accordion-body">
                                <div class="form-group my-2">
                                    <label for="title">Title:</label>
                                    <input value="{{ old('title', isset($order) ? $order->title : '') }}" type="text"
                                        name="title" id="title" class="form-control">
                                </div>

                                <div class="form-group my-2">
                                    <label for="description">Description:</label>
                                    <textarea name="description" id="description" class="form-control">{{ old('description', isset($order) ? $order->description : '') }}</textarea>
                                </div>

                                <div class="form-group my-2">
                                    <label for="date">Date:</label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="{{ old('date', isset($order) ? $order->date : '') }}">
                                </div>

                                @if (isset($order))
                                    <div class="form-group my-2">
                                        <label for="status">Status:</label>
                                        <select name="status" id="status" class="form-select">
                                            <option value="">Select a Status</option>
                                            @foreach ($status_options as $slug => $name)
                                                @if ($slug == 'sent_to_kfs' && $order->status != 'sent_to_kfs')
                                                    @continue
                                                @endif
                                                <option value="{{ $slug }}"
                                                    {{ old('status', $order->status) == $slug ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
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
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#customerInformationCollapse" aria-expanded="true"
                                aria-controls="customerInformationCollapse">
                                Customer Information
                            </button>
                        </h2>
                        <div id="customerInformationCollapse" class="accordion-collapse collapse show"
                            aria-labelledby="customerInformationHeading" data-bs-parent="#customerInformationAccordion">
                            <div class="accordion-body">
                                @if ((isset($order) && $order->user_id != null) or (isset($selected_user)))
                                    <div class="form-group my-2">
                                        <label for="user_id">User:</label>
                                        <input type="hidden" name="user_id" id="user_id"
                                            value="{{ isset($order) ? $order->user_id : $selected_user->id }}">
                                        <input type="text" name="user_name" id="user_name"
                                            value="{{ isset($order) ? $order->user->name : $selected_user->name }} ({{ isset($order) ? $order->user->netid : $selected_user->netid }})"
                                            class="form-control" disabled>
                                    </div>
                                    <!-- select a new user -->
                                @else
                                    <div class="alert alert-warning" role="alert">
                                        Select a user first to see payment accounts and start an order.
                                        <a href="{{ route('users.index') }}">Select A User</a>
                                    
                                    </div>
                                @endif

                                <div id="user_accounts" class="form-group my-2">
                                    @if ($accounts != null && count($accounts) > 0)
                                        <label for="payment_account_id">Payment Account:</label>
                                        <select name="payment_account_id" id="payment_account_id" class="form-select">
                                            <option value="">Select a Payment Account</option>
                                            @foreach ($accounts as $payment_account)
                                                <option value="{{ $payment_account->id }}"
                                                    {{ old('payment_account_id', isset($order) && $order->payment_account_id == $payment_account->id) ? 'selected' : '' }}>
                                                    {{ $payment_account->account_name }}
                                                    ({{ strtoupper($payment_account->account_type) }}-{{ $payment_account->account_number }})
                                                </option>
                                            @endforeach
                                        </select>
                                    @elseif ($accounts == null)
                                        <div class="alert alert-warning" role="alert">
                                            Select a User to see Payment Accounts
                                        </div>
                                    @elseif (count($accounts) == 0 && $accounts != null)
                                        <div class="alert alert-warning" role="alert">
                                            No Payment Accounts found for this user.
                                        </div>
                                    @endif

                                    @if (isset($account_warning_array) && count($account_warning_array) > 0)
                                        <div class="alert alert-{{ $account_warning_array['type'] }}" role="alert">
                                            {{ $account_warning_array['warning'] }}
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group my-2">
                                    <label for="price_group">Price Group:</label>
                                    <select name="price_group" id="price_group" class="form-select">
                                        <option value="">Select a Price Group</option>
                                        <option value="internal"
                                            {{ old('price_group', isset($order) && $order->price_group == 'internal') ? 'selected' : '' }}>
                                            Internal</option>

                                        <option value="external_non_profit"
                                            {{ old('price_group', isset($order) && $order->price_group == 'external_non_profit') ? 'selected' : '' }}>
                                            External Non-Profit</option>

                                        <option value="external_for_profit"
                                            {{ old('price_group', isset($order) && $order->price_group == 'external_for_profit') ? 'selected' : '' }}>
                                            External For-Profit</option>
                                    </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-md-6">
                <button type="submit" id="save-draft" class="btn btn-primary">
                    @if (isset($order))
                        Save Order Details
                    @else
                        Save Draft and Add items <i class="bi bi-arrow-right"></i>
                    @endif
                </button>
            </div>
        </div>
    </form>
</div>
