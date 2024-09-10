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
        <div class="col-md-2 subnav">
            <!-- subnav of order statuses: draft, pending, approved, in progress, complete, ledgered, archived -->
            <ul class="nav flex-column">
                <!-- loop through status_options, which is an associative array of status['slug'] = 'Status Name' -->
                @foreach ($status_options as $slug => $name)
                    <li class="nav-item">
                        <a class="nav-link {{ $slug == $selected_status ? 'active' : '' }}
                        @if ($slug == 'archived' || $slug == 'canceled') text-muted @endif"
                            href="{{ route('orders.index', ['status' => $slug]) }}">{{ $name }}</a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="col-md-10">
            <div class="active-filters py-2">

                @if (request('facility_id'))
                    <span class="badge bg-secondary">
                        Facility: {{ $data['facility']->abbreviation }}
                        <a href="{{ route('orders.index', ['status' => request('status'),
                        'facility_id' => null, 'search' => request('search'), 'start_date' => request('start_date'), 'end_date' => request('end_date')]) }}"
                            class="text-white ms-2">&times;</a>
                    </span>
                @endif

                
                @if (request('start_date') && request('end_date'))
                    <span class="badge bg-secondary">
                        Date Range: {{ request('start_date') }} to {{ request('end_date') }}
                        <a href="{{ route('orders.index', [
                        'status' => request('status'),
                        'facility_id' => request('facility_id'), 'search' => request('search'), 'start_date' => null, 'end_date' => null]) }}"
                            class="text-white ms-2">&times;</a>
                    </span>
                @endif

                @if (request('price_group'))
                    <span class="badge bg-secondary">
                        Price Group: {{ request('price_group') }}
                        <a href="{{ route('orders.index', [
                        'status' => request('status'),
                        'facility_id' => request('facility_id'), 'search' => request('search'), 'start_date' => request('start_date'), 'end_date' => request('end_date'), 'price_group' => null]) }}"
                            class="text-white ms-2">&times;</a>
                    </span>
                @endif

            </div>

            <!-- filter UI, like a search bar "Search by netid, order title, or order id", as well as date range filter -->
            <form action="{{ route('orders.index') }}" method="GET">
                <div class="row my-3 align-items-center">
                    <div class="col">
                        <label for="search">Facility:</label>
                        <!-- dropdown to filter by facility -->
                        <select class="form-select" id="facility_id" name="facility_id">
                            <option value="">All Facilities</option>
                            @foreach ($facilities as $facility)
                                <option value="{{ $facility->id }}" {{ $facility->id == request('facility_id') ? 'selected' : '' }}>
                                    {{ $facility->abbreviation }} - {{ $facility->name }}
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
                        <label for="search">Internal/External:</label>
                        <!-- price group filter -->
                        <select class="form-select" id="price_group" name="price_group">
                            <option value="">All Price Groups</option>
                            <option value="internal" {{ request('price_group') == 'internal' ? 'selected' : '' }}>Internal</option>
                            <option value="external_for_profit" {{ request('price_group') == 'external_for_profit' ? 'selected' : '' }}>External For Profit</option>
                            <option value="external_non_profit" {{ request('price_group') == 'external_non_profit' ? 'selected' : '' }}>External Non Profit</option>
                        </select>
                    </div>
                    <div class="col align-items-center mt-4">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>

            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="select-all"> 
                            #</th>
                        <th>Facility</th>
                        <th>User</th>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>

                            <td><input type="checkbox" name="order_id[]" value="{{ $order->id }}"> 
                                {{ $order->id }}</td>

                            <td>{{ $order->facility->abbreviation }}</td>
                            <td>{{ $order->user->name }}</td>
                            <td>{{ $order->title }}</td>
                            <td>{{ $order->date }}</td>
                            <td>
                                <span class="badge badge-{{ $order->status_color }}">{{ $order->status }}</span>
                            </td>
                            <td>$@dollars($order->total)</td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">View</a>
                                <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-secondary">Edit</a>
                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if ($orders->count() == 0)
                <p>No orders found matching your criteria.</p>
            @else

            <small class="d-block py-2">There are <span class="badge bg-dark">{{ $orders->count() }}</span> orders on this page, out of
                <span class="badge bg-dark">{{ $orders->total() }}</span> total matching your search.</small>
                
            @endif

        </div>
    </div>
</div>



@endsection
