@extends('layouts/app')
@section('title', 'All Orders')

@section('content')
<div class="container">

    <div class="row my-3">
        <div class="col-md-12">
            <h1>Orders</h1>
            <!-- filter UI and a spot for an admin menu like "Add Facility" go here -->

            <div class="d-flex justify-content-between align-items-center">

                <!-- create order with facility dropdown -->
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                        Start an Order
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <!-- Replace the href="#" with appropriate routes like orders/create/{facility->abbreviation} -->
                        @foreach($facilities as $facility)
                        <li><a class="dropdown-item" href=" {{ route('orders.create') }}/{{ $facility->abbreviation }}
                                    ">{{ $facility->name }}</a></li>
                        @endforeach
                    </ul>
                </div>

            </div>

        </div>
    </div>

    <div class="row">

        <div class="col-md-2 subnav">
            <!-- subnav of order statuses: draft, pending, approved, in progress, complete, ledgered, archived -->
            <ul class="nav flex-column">
                <!-- loop through status_options, which is an associative array of status['slug'] = 'Status Name' -->
                @foreach($status_options as $slug => $name)
                <li class="nav-item">
                    <a class="nav-link {{ $slug == $selected_status ? 'active' : '' }}" 
                    href="{{ route('orders.index', ['status' => $slug]) }}">{{ $name }}</a>
                </li>
                @endforeach
            </ul>


        </div>
        <div class="col-md-10">
            <table class="table">
                <thead>
                    <tr>
                        <th>Order ID</th>

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
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->facility->abbreviation }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td>{{ $order->title }}</td>
                        <td>{{ $order->date }}</td>
                        <td>
                            <span class="badge badge-{{ $order->status_color }}">{{ $order->status }}</span>
                        </td>
                        </td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">View</a>
                            <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-secondary">Edit</a>
                            <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display: inline;">
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
</div>
@endsection