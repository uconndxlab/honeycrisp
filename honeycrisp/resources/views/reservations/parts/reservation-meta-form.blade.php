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
        $defaultTitle = 'Rsrvtn: ' . $product->name . ' (' . $facility->abbreviation . ')';

    @endphp
@endif


<div class="container">
    <!-- Header Section -->
    <div id="order-actions" class="row my-3">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <h1>{{ isset($order) ? 'Edit Reservation #' . $order->id : 'Create Reservation' }}
                    <!-- status badge -->
                    @if (isset($order))
                        <span class="badge badge-{{ $order->status_color }}">{{ Str::headline($order->status) }}</span>
                    @endif
                </h1>
                <h2>{{ $facility->name }} ({{ $facility->abbreviation }})</h2>
                


            </div>

            <h2>{{ $product->name }}</h2>

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

    {{-- Reservation Form --}}
    <form action="{{ route('reservations.store') }}" method="POST">

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
                                    <input required
                                        value="{{ old('title', isset($order) ? $order->title : $defaultTitle) }}"
                                        type="text" name="title" id="title" class="form-control">
                                </div>

                                <div class="form-group my-2">
                                    <label for="description">Description: (notes to Facility)</label>
                                    <textarea name="description" id="description" class="form-control">{{ old('description', isset($order) ? $order->description : '') }}</textarea>
                                </div>

                                <div class="form-group my-2">
                                    <label for="date">Date of Submission*:</label>
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
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title">Instrument Details</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Availability</strong></p>
                        @if ($scheduleRules->isEmpty())
                            <p>No specific schedule rules for this product.</p>
                        @else
                            <ul>
                                @foreach ($product->scheduleRules as $rule)
                                    <li>
                                        <strong>{{ ucfirst($rule->day) }}</strong>:
                                        {{ $rule->time_of_day_start }} - {{ $rule->time_of_day_end }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
        
                        <p><strong>Reservation Interval:</strong> {{ $product->reservation_interval }} minutes</p>
                        <p><strong>Minimum Reservation Duration:</strong> {{ $product->minimum_reservation_time }} minutes</p>
                        <p><strong>Maximum Reservation Duration:</strong> {{ $product->maximum_reservation_time }} minutes</p>
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
                                        <livewire:account-search :accounts="$accounts" :selected-account="$selected_account" />
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

                                    <livewire:user-search name="additional_users" id="additional_users"
                                        :initial-users="$initialSelectedUsers" :exclude="$usersToExclude" />

                                </div>


                                <div class="form-group my-2">
                                    <label for="price_group">Price Group*:</label>
                                    <select id="price_group" name="price_group_id" class="form-select">
                                        @foreach ($product->priceGroups as $priceGroup)
                                            <option value="{{ $priceGroup->id }}">
                                                {{ $priceGroup->name }} ($@dollars($priceGroup->price * 60))
                                            </option>
                                        @endforeach
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
        </div>
