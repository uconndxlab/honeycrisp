<div class="container">
    <!-- Header Section -->
    <div class="row my-3">
        <div class="col-md-12">
            <h1>{{ isset($order) ? 'Edit Order #' . $order->id : 'Create Order' }}
                <!-- status badge -->
                @if (isset($order))
                    <span class="badge badge-{{ $order->status_color }}">{{ $order->status }}</span>
                @endif
            </h1>
            <h2>{{ $facility->name }} ({{ $facility->abbreviation }})</h2>
        </div>
    </div>

    <!-- Form Section -->
    <form action="{{ isset($order) ? route('orders.update', ['order' => $order]) : route('orders.store') }}"
        method="POST" class="order-meta-form">
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
                                                <option value="{{ $slug }}"
                                                    @if (old('status', isset($order) && $order->status == $slug)) selected @endif>
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
                                <div class="form-group my-2">
                                    <label for="user_id">Ordering for User:</label>
                                    <select hx-get="{{ route('orders.create') }}/{{ $facility->abbreviation }}"
                                        hx-select="#user_accounts" hx-target="#user_accounts"
                                        hx-indicator="#user_accounts" hx-trigger="change" hx-swap="outerHTML"
                                        hx-push-url="true" name="user_id" id="user_id" class="form-select">
                                        <option value="">Select a User</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->netid }}"
                                                @if (old('user_id', isset($selected_user) && $selected_user == $user->id)) selected @endif>
                                                {{ $user->name }} ({{ $user->netid }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div id="user_accounts" class="form-group my-2">

                                    @if ($accounts != null && count($accounts) > 0)
                                        <label for="payment_account">Payment Account:</label>
                                        <select name="payment_account" id="payment_account" class="form-select">
                                            <option value="">Select a Payment Account</option>
                                            @foreach ($accounts as $payment_account)
                                                <option value="{{ $payment_account->id }}"
                                                    @if (old('payment_account', isset($order) && $order->payment_account == $payment_account->id)) selected @endif>
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

                                    @if (isset($account_warning))
                                        <div class="alert alert-warning" role="alert">
                                            {{ $account_warning }}
                                        </div>
                                    @endif


                                </div>

                                <div class="form-group my-2">
                                    <label for="price_group">Price Group:</label>
                                    <!-- dropdown for internal price group, external_nonprofit, external_forprofit -->
                                    <select name="price_group" id="price_group" class="form-select">
                                        <option value="">Select a Price Group</option>
                                        <option value="internal" @if (old('price_group', isset($order) && $order->price_group == 'internal')) selected @endif>
                                            Internal</option>
                                        <option value="external_nonprofit"
                                            @if (old('price_group', isset($order) && $order->price_group == 'external_nonprofit')) selected @endif>External Nonprofit
                                        </option>
                                        <option value="external_forprofit"
                                            @if (old('price_group', isset($order) && $order->price_group == 'external_forprofit')) selected @endif>External For-Profit
                                        </option>
                                    </select>
                                </div>

                                <!-- extrernal company name -->
                                <div class="form-group my-2">
                                    <label for="external_company_name">External Company Name:</label>
                                    <input
                                        value="{{ old('company_name', isset($order) ? $order->user->external_organization : '') }}"
                                        type="text" name="external_company_name" id="external_company_name"
                                        class="form-control">


                                    <!-- if external, show external_customer_id -->
                                    @if (old('price_group', isset($order) && $order->price_group) == 'external_nonprofit' ||
                                            old('price_group', isset($order) && $order->price_group) == 'external_forprofit')
                                        <label for="external_customer_id">External Customer ID:</label>
                                        <input
                                            value="{{ old('external_customer_id', isset($order) ? $order->user->external_customer_id : '') }}"
                                            type="text" name="external_customer_id" id="external_customer_id"
                                            class="form-control">
                                    @endif

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
                    <button type="submit" id="save-draft" class="btn btn-primary">Save Draft and Add items <i
                            class="bi bi-arrow-right"></i></button>
                @endif
            </div>
        </div>
    </form>

    <script>
        // external company name should only be visible, required, and enabled when price group is external
        document.addEventListener('DOMContentLoaded', function() {
            const priceGroup = document.getElementById('price_group');
            const externalCompanyName = document.getElementById('external_company_name');
            const externalCustomerId = document.getElementById('external_customer_id');

            externalCompanyName.parentElement.classList.add('d-none');

            priceGroup.addEventListener('change', function() {
                if (priceGroup.value === 'external_nonprofit' || priceGroup.value ===
                    'external_forprofit') {
                    externalCompanyName.required = true;
                    externalCompanyName.disabled = false;
                    externalCustomerId.required = true;
                    externalCustomerId.disabled = false;


                    externalCompanyName.parentElement.classList.remove('d-none');

                } else {
                    externalCompanyName.required = false;
                    externalCompanyName.disabled = true;

                    externalCustomerId.required = false;
                    externalCustomerId.disabled = true;
                    externalCompanyName.parentElement.classList.add('d-none');
                }
            });

            if (priceGroup.value === 'external_nonprofit' || priceGroup.value === 'external_forprofit') {
                externalCompanyName.required = true;
                externalCompanyName.disabled = false;
                externalCustomerId.required = true;
                externalCustomerId.disabled = false;

                externalCompanyName.parentElement.classList.remove('d-none');
            } else {
                externalCompanyName.required = false;
                externalCompanyName.disabled = true;

                externalCustomerId.required = false;
                externalCustomerId.disabled = true;

                externalCompanyName.parentElement.classList.add('d-none');
            }
        });
    </script>

</div>
