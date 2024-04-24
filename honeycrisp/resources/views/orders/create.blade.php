@extends('layouts.app')

@section('title', 'Create Order')

@section('content')
    <div class="container">
        <h2>Create Order</h2>
        <form action="{{ route('orders.store') }}" method="POST">
            @csrf

            <!-- User ID -->
            <div class="form-group my-2">
                <label for="user_id">Ordering for User:</label>
                <input type="text" name="user_id" id="user_id" class="form-control" value="{{ Auth::id() }}">
            </div>

            <!-- Payment Account -->
            <div class="form-group my-2">
                <label for="payment_account">Payment Account:</label>
                <input type="text" name="payment_account" id="payment_account" class="form-control" value="">
            </div>

            <!-- Facility ID -->
            <div class="form-group my-2">
                <label for="facility_id">Facility (admins only)</label>
                <select name="facility_id" id="facility_id" class="form-control">
                    <!-- Populate facility options from database or use existing facilities -->
                    @foreach ($facilities as $facility)
                        <option value="{{ $facility->id }}">
                            {{ $facility->name }} ({{ $facility->abbreviation }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status -->
            <div class="form-group my-2">
                <label for="status">Status:</label>
                <select name="status" id="status" class="form-control">
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                    <option value="canceled">Canceled</option>
                </select>
            </div>

            <div class="form-group my-2 text-end">
                <button type="submit" class="btn btn-primary">Submit Order</button>
            </div>
        </form>
    </div>

@endsection
