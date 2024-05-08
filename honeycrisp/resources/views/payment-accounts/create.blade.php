@extends('layouts/app')

@section('title', 'Create Payment Account')

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <h1>Create Payment Account</h1>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <!-- errors -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('payment-accounts.store') }}" method="POST">
                @csrf

                <!-- user_id is a foreign key -->
                <div class="mb-3">
                    <label for="account_owner" class="form-label">Account Owner</label>
                    <small>(You can add authorized users to this account later.)</small>
                    <select class="form-select" id="account_owner" name="account_owner">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="account_name" class="form-label">Account Name</label>
                    <input type="text" class="form-control" id="account_name" name="account_name">
                </div>

                <!-- type which is enum -->
                <div class="mb-3">
                    <label for="account_type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="account_type">
                        <option value="kfs">KFS</option>
                        <option value="uch">Banner/UCH</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="account_number" class="form-label">Account Number</label>
                    <input type="text" class="form-control" id="account_number" name="account_number">
                </div>

                <div class="mb-3">
                    <label for="expiration_date" class="form-label">Expiration Date</label>
                    <input type="date" class="form-control" id="expiration_date" name="expiration_date">
                </div>


                <button type="submit" class="btn btn-primary">Create Payment Account</button>
            </form>
        </div>
    </div>
</div>
@endsection