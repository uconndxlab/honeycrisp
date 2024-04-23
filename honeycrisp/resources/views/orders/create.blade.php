@extends('layouts.app')

@section('title', 'Create Order')

@section('content')
<div class="container">
    <h2>Create Order</h2>
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        <!-- User ID -->
        <div class="form-group">
            <label for="user_id">User ID:</label>
            <input type="text" name="user_id" id="user_id" class="form-control" value="{{ Auth::id() }}" readonly>
        </div>

        <!-- Facility ID -->
        <div class="form-group">
            <label for="facility_id">Facility ID:</label>
            <select name="facility_id" id="facility_id" class="form-control">
                <!-- Populate facility options from database or use existing facilities -->
                @foreach($facilities as $facility)
                <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Payment Account -->
        <div class="form-group">
            <label for="payment_account">Payment Account:</label>
            <input type="text" name="payment_account" id="payment_account" class="form-control" value="">
        </div>

        <!-- Status -->
        <div class="form-group">
            <label for="status">Status:</label>
            <input type="text" name="status" id="status" class="form-control" value="pending">
        </div>

        <!-- Tags -->
        <div class="form-group">
            <label for="tags">Tags:</label>
            <input type="text" name="tags" id="tags" class="form-control" value="">
        </div>

        <!-- Items -->
        <div class="form-group">
            <label for="items">Items:</label>
            <textarea name="items" id="items" class="form-control" rows="3"></textarea>
            <small class="form-text text-muted">Enter item names separated by commas.</small>
        </div>

        <button type="submit" class="btn btn-primary">Create Order</button>
    </form>
</div>
@endsection
