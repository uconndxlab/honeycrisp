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
                        <select class="form-select" id="facility" name="facility">
                            <option value="all">All Facilities</option>
                            @foreach($facilities as $facility)
                            <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                            @endforeach
                        </select>

                        <select class="form-select ms-2" id="status" name="status">
                            <option value="all">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>

                        <div class="d-flex ms-3 align-items-center">
                            <label for="start_date">Dates: </label>
                            <input type="date" class="form-control ms-2" id="start_date" name="start_date">
                            <input type="date" class="form-control ms-2" id="end_date" name="end_date">
                        </div>

                        <button class="btn btn-primary ms-2">Filter</button>
                        
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
            <div class="col">
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
