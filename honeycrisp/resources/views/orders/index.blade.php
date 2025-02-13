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
                <!-- Filter Orders -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Filter Orders</h5>
                    </div>
                    <div class="card-body">
                        {{-- little horizontal nav for the facilities --}}
                        <ul class="nav nav-pills mb-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request('facility_id') ? '' : 'active' }}" href="{{ route('orders.index') }}">All Facilities</a>
                            </li>
                            @foreach ($facilities as $facility)
                                <li class="nav-item">
                                    <a class="nav-link {{ request('facility_id') == $facility->id ? 'active' : '' }}" href="{{ route('orders.index', ['facility_id' => $facility->id]) }}">
                                        {{ $facility->abbreviation }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <form action="{{ route('orders.index') }}" method="GET">
                            <div class="row my-3 align-items-center">
                                {{-- <div class="col">
                                    <label for="facility_id">Facility:</label>
                                    <select class="form-select" id="facility_id" name="facility_id">
                                        <option value="">All Facilities</option>
                                        @foreach ($facilities as $facility)
                                            <option value="{{ $facility->id }}" {{ $facility->id == request('facility_id') ? 'selected' : '' }}>
                                                {{ $facility->abbreviation }} - {{ $facility->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> --}}

                                <div class="col">
                                    <label for="status">Status:</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">All Active</option>
                                        @foreach ($status_options as $slug => $name)
                                            <option value="{{ $slug }}" {{ $slug == $selected_status ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col">
                                    <label for="start_date">Date Range:</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                                </div>

                                <div class="col">
                                    <label for="end_date">to</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                                </div>

                                <div class="col">
                                    <label for="price_group">Internal/External:</label>
                                    <select class="form-select" id="price_group" name="price_group">
                                        <option value="">All Price Groups</option>
                                        <option value="internal" {{ request('price_group') == 'internal' ? 'selected' : '' }}>Internal</option>
                                        <option value="external_forprofit" {{ request('price_group') == 'external_forprofit' ? 'selected' : '' }}>External For Profit</option>
                                        <option value="external_nonprofit" {{ request('price_group') == 'external_nonprofit' ? 'selected' : '' }}>External Non Profit</option>
                                    </select>
                                </div>

                                <div class="col">
                                    <label for="account_type">Account Type:</label>
                                    <select class="form-select" id="account_type" name="account_type">
                                        <option value="">All</option>
                                        @foreach ($account_types as $account_type)
                                            <option value="{{ $account_type }}" {{ $account_type == request('account_type') ? 'selected' : '' }}>
                                                {{ strtoupper($account_type) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col align-items-center mt-4">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Orders Table -->
                <form action="{{ route('orders.bulkUpdate') }}" method="POST">
                    @csrf
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Orders</h5>
                            <div>
                                <select name="status" class="form-select d-inline-block w-auto" required>
                                    <option value="" disabled selected>Bulk Update Status</option>
                                    @foreach ($status_options as $slug => $name)
                                        <option value="{{ $slug }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary">Apply</button>
                            </div>
                        </div>
                        <div class="card-body">

                            @if ($orders->isEmpty())
                                <div class="alert alert-info">No orders found.</div>

                            @else

                            <table class="table table-striped table-bordered align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th class="text-center" style="width: 40px;">
                                            <input type="checkbox" id="select-all">
                                        </th>
                                        <th class="text-center" style="width: 100px;">Order#</th>
                                        <th class="text-center" style="width: 120px;">Date</th>
                                        <th>User</th>
                                        <th>Title</th>

                                        <th class="text-end" style="width: 120px;">Total</th>
                                        <th class="text-center" style="width: 150px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" name="order_ids[]" value="{{ $order->id }}">
                                            </td>

                                            <td class="text-center">
                                                <strong>{{ $order->facility->abbreviation }} - {{ $order->id }}</strong>
                                            </td>

                                            <td class="text-center">
                                                {{ $order->date }}
                                            </td>
                                            <td>
                                                {{ $order->customer->name }}
                                                @if ($order->paymentAccount)
                                                    <small class="text-muted">({{ strtoupper(optional($order->paymentAccount)->account_type) }})</small>
                                                @endif
                                                <br>
                                                @if ($order->price_group == 'internal')
                                                    <span class="badge bg-primary">Internal</span>
                                                @elseif ($order->price_group == 'external_forprofit')
                                                    <span class="badge bg-danger">External For Profit</span>
                                                @elseif ($order->price_group == 'external_nonprofit')
                                                    <span class="badge bg-success">External Non Profit</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $order->title }}
                                                <span class="badge bg-{{ $order->status_color }}">{{ ucfirst($order->status) }}</span>
                                            </td>

                                            <td class="text-end">
                                                $@dollars($order->total)
                                            </td>
                                            <td class="text-center">
                                                @can('see-order', $order)
                                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                @endcan
                                                @can('update-order', $order)
                                                    <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            

                            @endif


                            <div class="d-flex justify-content-between align-items-center">
                                <div>{{ $orders->appends(request()->except('page'))->links() }}</div>
                                <small>Total: $@dollars($orders->sum('total'))</small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('click', function () {
            const checkboxes = document.querySelectorAll('input[name="order_ids[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });
    </script>
@endsection
