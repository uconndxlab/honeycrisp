@extends('layouts/app')
@section('title', 'All Orders')

@section('content')
    <div class="container">
        <div class="row my-3">
            <div class="col-md-12">
                <h1>Orders</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Filter Orders</h5>
                    </div>

                    <div class="card-body">
                        <!-- filter UI, like a search bar "Search by netid, order title, or order id", as well as date range filter -->
                        <form action="{{ route('orders.index') }}" method="GET" hx-boost="true" hx-trigger="change"
                            hx-target="#orderResults" hx-swap="outerHTML" hx-select="#orderResults">
                            <div class="row my-3 align-items-center">
                                <div class="col">
                                    <label for="search">Facility:</label>
                                    <!-- dropdown to filter by facility -->
                                    <select class="form-select" id="facility_id" name="facility_id">
                                        <option value="">All Facilities</option>
                                        @foreach ($facilities as $facility)
                                            <option value="{{ $facility->id }}"
                                                {{ $facility->id == request('facility_id') ? 'selected' : '' }}>
                                                {{ $facility->abbreviation }} - {{ $facility->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <!-- status options -->
                                <div class="col">
                                    <label for="status">Status:</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Filter by Status</option>
                                        @foreach ($status_options as $slug => $name)
                                            <option value="{{ $slug }}"
                                                {{ $slug == $selected_status ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col">
                                    <label for="start_date">Date Range:</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="col">
                                    <label for="end_date">to</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                        value="{{ request('end_date') }}">
                                </div>


                                <div class="col">
                                    <label for="search">Internal/External:</label>
                                    <!-- price group filter -->
                                    <select class="form-select" id="price_group" name="price_group">
                                        <option value="">All Price Groups</option>
                                        <option value="internal"
                                            {{ request('price_group') == 'internal' ? 'selected' : '' }}>
                                            Internal
                                        </option>
                                        <option value="external_forprofit"
                                            {{ request('price_group') == 'external_forprofit' ? 'selected' : '' }}>
                                            External For Profit</option>
                                        <option value="external_nonprofit"
                                            {{ request('price_group') == 'external_nonprofit' ? 'selected' : '' }}>
                                            External Non Profit</option>
                                    </select>
                                </div>

                                <!-- filter by paymentAccount account_type -->
                                <div class="col">
                                    <label for="search">Account Type:</label>
                                    <select class="form-select" id="account_type" name="account_type">
                                        <option value="">All</option>
                                        @foreach ($account_types as $account_type)
                                            <option value="{{ $account_type }}"
                                                {{ $account_type == request('account_type') ? 'selected' : '' }}>
                                                {{ @strToUpper($account_type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col align-items-center mt-4">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>

                            <!-- search bar -->
                            <div class="row my-3">
                                <div class="col">
                                    <div class="input-group">
                                        <input hx-get="{{ route('orders.index') }}" hx-target="#orderResults"
                                            hx-indicator=".loading" hx-select="#orderResults" hx-trigger="keyup"
                                            hx-swap="outerHTML" placeholder="Search by netid, order title, or order id"
                                            type="text" class="form-control" id="search" name="search"
                                            value="{{ request('search') }}">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                <div id="orderResults" class="card mt-5">
                    <div class="card-header">
                        <div class="d-flex">
                            <h5>Orders</h5>
                            <div class="active-filters ms-4">
                                @if (request('facility_id'))
                                    <span class="badge bg-secondary">
                                        Facility: {{ $data['facility']->abbreviation }}
                                        <a href="{{ route('orders.index', [
                                            'status' => request('status'),
                                            'facility_id' => null,
                                            'search' => request('search'),
                                            'start_date' => request('start_date'),
                                            'end_date' => request('end_date'),
                                            'price_group' => request('price_group'),
                                            'account_type' => request('account_type'),
                                        ]) }}"
                                            class="text-white ms-2">&times;</a>
                                    </span>
                                @endif

                                @if (request('status'))
                                    <span class="badge bg-secondary">
                                        Status: {{ $status_options[request('status')] }}
                                        <a href="{{ route('orders.index', [
                                            'status' => null,
                                            'facility_id' => request('facility_id'),
                                            'search' => request('search'),
                                            'start_date' => request('start_date'),
                                            'end_date' => request('end_date'),
                                            'price_group' => request('price_group'),
                                            'account_type' => request('account_type'),
                                        ]) }}"
                                            class="text-white ms-2">&times;</a>
                                    </span>
                                @endif


                                @if (request('start_date') && request('end_date'))
                                    <span class="badge bg-secondary">
                                        Date Range: {{ request('start_date') }} to {{ request('end_date') }}
                                        <a href="{{ route('orders.index', [
                                            'status' => request('status'),
                                            'facility_id' => request('facility_id'),
                                            'search' => request('search'),
                                            'start_date' => null,
                                            'end_date' => null,
                                            'price_group' => request('price_group'),
                                            'account_type' => request('account_type'),
                                        ]) }}"
                                            class="text-white ms-2">&times;</a>
                                    </span>
                                @endif

                                @if (request('price_group'))
                                    <span class="badge bg-secondary">
                                        Price Group: {{ request('price_group') }}
                                        <a href="{{ route('orders.index', [
                                            'status' => request('status'),
                                            'facility_id' => request('facility_id'),
                                            'search' => request('search'),
                                            'start_date' => request('start_date'),
                                            'end_date' => request('end_date'),
                                            'price_group' => null,
                                            'account_type' => request('account_type'),
                                        ]) }}"
                                            class="text-white ms-2">&times;</a>
                                    </span>
                                @endif

                                @if (request('account_type'))
                                    <span class="badge bg-secondary">
                                        Account Type: {{ request('account_type') }}
                                        <a href="{{ route('orders.index', [
                                            'status' => request('status'),
                                            'facility_id' => request('facility_id'),
                                            'search' => request('search'),
                                            'start_date' => request('start_date'),
                                            'end_date' => request('end_date'),
                                            'price_group' => request('price_group'),
                                            'account_type' => null,
                                        ]) }}"
                                            class="text-white ms-2">&times;</a>
                                    </span>
                                @endif

                            </div>
                        </div>

                        @if ($orders->count() == 0)
                            <p>No orders found matching your criteria.</p>
                        @else
                            <small class="d-block py-2">There are <span class="badge bg-dark">{{ $orders->count() }}</span>
                                orders on this page, out of <span class="badge bg-dark">{{ $orders->total() }}</span>
                                total matching your search.</small>
                        @endif
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Order#</th>
                                    <th>User</th>
                                    <th>Title</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->facility->abbreviation }} - {{ $order->id }}</td>
                                        <td>{{ $order->customer->name }}
                                            ({{ strtoupper(optional($order->paymentAccount)->account_type) }})</td>
                                        <td>{{ $order->title }} <span
                                                class="badge badge-{{ $order->status_color }}">{{ $order->status }}</span>
                                        </td>
                                        <td>{{ $order->date }}</td>
                                        <td>$@dollars($order->total)</td>
                                        <td>
                                            <a href="{{ route('orders.show', $order->id) }}"
                                                class="btn btn-primary">View</a>
                                            <a href="{{ route('orders.edit', $order->id) }}"
                                                class="btn btn-secondary">Edit</a>

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4"><strong>Total</strong></td>
                                    <td>$@dollars($orders->sum('total'))</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="pagination">
                                {{ $orders->appends(request()->except('page'))->links() }}
                            </div>
                            <div class="exports">
                                <a href="{{ route('orders.export', ['order_ids' => $orders->pluck('id')->toArray()]) }}"
                                    class="btn btn-success">
                                    <i class="bi bi-file-earmark-spreadsheet"></i> Export Current Results as CSV
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('click', function() {
            var checkboxes = document.getElementsByName('order_id[]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        });
    </script>



@endsection
