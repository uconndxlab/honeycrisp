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
            <!-- filter UI, like a search bar "Search by netid, order title, or order id", as well as date range filter -->
            <form action="{{ route('orders.index') }}" method="GET">
                <div class="row mb-3">
                    <div class="col-md-2">
                        <!-- dropdown to filter by facility -->
                        <select class="form-select" id="facility_id" name="facility_id">
                            <option value="">All Facilities</option>
                            @foreach ($facilities as $facility)
                                <option value="{{ $facility->id }}">{{ $facility->abbreviation }} -
                                    
                                    {{ $facility->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">

                        <input type="text" class="form-control" id="search" name="search" value=""
                            placeholder="Search by NetID, Order Title, or Order ID">
                    </div>
                    <div class="col-md-2">
              
                        <input type="date" class="form-control" id="start_date" name="start_date" value="">
                    </div>
                    <div class="col-md-2">
                   
                        <input type="date" class="form-control" id="end_date" name="end_date" value="">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
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
                        <th>Order Date</th>
                        <th>Order Status</th>
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
            @endif
        </div>
    </div>
</div>



@endsection
