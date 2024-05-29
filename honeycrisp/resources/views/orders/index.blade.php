@extends('layouts/app')
@section('title', 'All Orders')

@section('content')
    <div class="container">

        <div class="row my-3">
            <div class="col-md-12">
                <h1>Orders</h1>
                <!-- filter UI and a spot for an admin menu like "Add Facility" go here -->

                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex">                   

                        
                    </div>
                 

                    <div>
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
        </div>

        <div class="row">

            <div class="col-md-2 subnav">
                <h5>Order Status:</h5>
                <!-- subnav of order statuses: draft, pending, approved, in progress, complete, ledgered, archived -->
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link" href="#">Draft</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Pending</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Approved</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">In Progress</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Complete</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Ledgered</a>
                        </li>

                        <!-- archive, muted and right-aligned -->
                        <li class="nav-item">
                            <a class="nav-link text-muted" href="#">Archived</a>
                        </li>
                    </ul>
                
            </div>
            <div class="col-md-10">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Facility</th>
                            <th>Order Date</th>
                            <th>Order Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->facility->name }}</td>
                                <td>{{ $order->order_date }}</td>
                                <td>{{ $order->order_status }}</td>
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
            </div>
        </div>

    </div>
    </div>
@endsection
