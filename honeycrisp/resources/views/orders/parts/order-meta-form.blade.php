@if (isset($order))
    @php
        $selected_account = $accounts->firstWhere('id', $order->payment_account_id);
        $selected_user = $order->user;

        // if the order has a price group, set
        $selected_price_group = $order->price_group;

    @endphp
@else
    @php
        $selected_account = $accounts->firstWhere('id', request('payment_account_id'));
        $selected_price_group = $selected_user->price_group;

    @endphp
@endif


<div class="container">
    <!-- Header Section -->
    <div id="order-actions" class="row my-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h1>{{ isset($order) ? 'Edit Order #' . $order->id : 'Create Order' }}
                    <!-- status badge -->
                    @if (isset($order))
                        <span class="badge badge-{{ $order->status_color }}">{{ Str::headline($order->status) }}</span>
                    @endif
                </h1>

                <!-- if order is quote, a button to send to customer -->
                <div>

                    <!-- view financial files -->
                    <a href="{{ route('orders.financialFiles', ['order' => $order]) }}"
                        class="btn btn-outline-primary {{ !isset($order) ? 'disabled' : '' }}">
                        <i class="bi bi-journal"></i> View Financial Files</a>
                        

                    @if (isset($order))
                        <a href="{{ route('orders.sendToCustomer', ['order' => $order]) }}"
                            class="btn btn-outline-primary ">
                            <i class="bi bi-envelope"></i> Send to Customer</a>
                    @endif

                    <button form="order-meta-form" type="submit" id="save-order" 
                    class="btn btn-primary disabled">
                        @if (isset($order))
                            Save Order Details
                        @else
                            Save Draft and Add items <i class="bi bi-arrow-right"></i>
                        @endif
                    </button>
                </div>
            </div>
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

    <form id="order-meta-form"
        action="{{ isset($order) ? route('orders.update', ['order' => $order]) : route('orders.store') }}"
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
                                    <label for="title">Title*:</label>
                                    <input required value="{{ old('title', isset($order) ? $order->title : '') }}"
                                        type="text" name="title" id="title" class="form-control">
                                </div>

                                <div class="form-group my-2">
                                    <label for="description">Description*:</label>
                                    <textarea required name="description" id="description" class="form-control">{{ old('description', isset($order) ? $order->description : '') }}</textarea>
                                </div>

                                <div class="form-group my-2">
                                    <label for="date">Date*:</label>
                                    <input type="date" name="date" id="date" class="form-control"
                                        value="{{ old('date', isset($order) ? $order->date : now()->format('Y-m-d')) }}">
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


                                <!-- if it's not internal, add fields for mailing address and purchase order -->

                                @if ($selected_price_group != 'internal')
                                    <div class="form-group my-2">
                                        <label for="mailing_address">Mailing Address:</label>
                                        <textarea name="mailing_address" id="mailing_address" class="form-control">{{ old('mailing_address', isset($order) ? $order->mailing_address : '') }}</textarea>
                                    </div>

                                    <div class="form-group my-2">
                                        <label for="purchase_order_number">Purchase Order:</label>
                                        <input type="text" name="purchase_order_number" id="purchase_order_number"
                                            class="form-control"
                                            value="{{ old('purchase_order', isset($order) ? $order->purchase_order_number : '') }}">
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



                                @if (isset($order) && $order->user_id != null or isset($selected_user))
                                    <div class="form-group my-2">
                                        <label for="user_id">Customer:</label>
                                        <input type="hidden" name="user_id" id="user_id"
                                            value="{{ isset($order) ? $order->customer->id : $selected_user->id }}">
                                        <input type="text" name="user_name" id="user_name"
                                            value="{{ isset($order) ? $order->customer->name : $selected_user->name }} ({{ isset($order) ? $order->customer->netid : $selected_user->netid }})"
                                            class="form-control" disabled>
                                    </div>
                                @else
                                    <div class="alert alert-warning" role="alert">
                                        Select a customer first to see payment accounts and start an order.
                                        <a href="{{ route('users.index') }}">Select A Customer</a>
                                    </div>
                                @endif

                                <div id="user_accounts" class="form-group my-2">
                                    @if ($accounts != null && count($accounts) > 0)
                                        <label for="payment_account_id">Payment Account:</label>
                                        <select name="payment_account_id" id="payment_account_id" class="form-select"
                                            hx-get="{{ route('orders.create') }}/{{ $facility->abbreviation }}?user_id={{ isset($order) ? $order->customer->id : $selected_user->id }}"
                                            hx-select="#user_accounts" hx-target="#user_accounts" hx-push-url="true">



                                            <option value="">Select a Payment Account</option>
                                            @foreach ($accounts->sortBy('account_name') as $payment_account)
                                                <option
                                                    {{ old('payment_account_id', isset($selected_account) ? $selected_account->id : '') == $payment_account->id ? 'selected' : '' }}
                                                    value="{{ $payment_account->id }}">
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
                                            No Payment Accounts found for this user. <a
                                                href="{{ route('payment-accounts.create', ['netid' => request()->netid]) }}">Add
                                                One</a>
                                        </div>
                                    @endif

                                    @if (isset($account_warning_array) && count($account_warning_array) > 0)
                                        <div class="alert alert-{{ $account_warning_array['type'] }}" role="alert">
                                            {{ $account_warning_array['warning'] }}
                                        </div>
                                    @endif
                                </div>

                                <div class="form-group my-2">
                                    <label for="additional_users">Additional Users:</label>
                                    @php 
                                        $initialSelectedUsers = [];
                                        if (isset($order)) {
                                            $initialSelectedUsers = $order->users->pluck('id')->toArray();
                                        }

                                        $usersToExclude = [];
                                        // exclude the customer from the list of users
                                        if (isset($order)) {
                                            $usersToExclude[] = $order->customer->id;
                                        } else {
                                            $usersToExclude[] = $selected_user->id;
                                        }
                                    @endphp

                                    <livewire:user-search name="additional_users" id="additional_users" :initial-users="$initialSelectedUsers" :exclude="$usersToExclude" />

                                </div>
                                

                                <div class="form-group my-2">
                                    <label for="price_group">Price Group*:</label>
                                    <select required name="price_group" id="price_group" class="form-select">
                                        <option value="">Select a Price Group</option>
                                        <option value="internal"
                                            {{ old('price_group', $selected_price_group == 'internal') ? 'selected' : '' }}>
                                            Internal</option>

                                        <option value="external_nonprofit"
                                            {{ old('price_group', $selected_price_group == 'external_nonprofit') ? 'selected' : '' }}>
                                            External Non-Profit</option>

                                        <option value="external_forprofit"
                                            {{ old('price_group', $selected_price_group == 'external_forprofit') ? 'selected' : '' }}>
                                            External For-Profit</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <!-- order log -->
            @if (isset($order))
                <div class="row">
                    <div class="col-md-12">
                        <div class="accordion my-2" id="orderLogAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="orderLogHeading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#orderLogCollapse" aria-expanded="true"
                                        aria-controls="orderLogCollapse">
                                        Order Log
                                    </button>
                                </h2>
                                <div id="orderLogCollapse" class="accordion-collapse collapse show"
                                    aria-labelledby="orderLogHeading" data-bs-parent="#orderLogAccordion">
                                    <div class="accordion-body">
                                        <ul class="list-group list-group-flush">
                                            @foreach ($order->logs as $log)
                                                <li class="list-group list-group-item">
                                                    <strong>{{ optional($log->user)->netid }} </strong>
                                                    <strong>{{ $log->created_at->format('m/d/Y h:i A') }}</strong> -
                                                    {{ $log->message }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
    </form>
</div>
