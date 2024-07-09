@extends('layouts/app')

@section('title', 'Edit Payment Account')

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <h1>Edit Payment Account</h1>
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
            <form action="{{ route('payment-accounts.update', $paymentAccount->id) }}" method="POST">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <!-- user_id is a foreign key -->
                <div class="mb-3">
                    <label for="account_owner" class="form-label">User ID</label>
                    <select class="form-select" id="account_owner" name="account_owner">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ $user->id == $paymentAccount->account_owner ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="account_name" class="form-label">Account Name</label>
                    <input type="text" class="form-control" id="account_name" name="account_name" value="{{ $paymentAccount->account_name }}">
                </div>

                <!-- type which is enum -->
                <div class="mb-3">
                    <label for="account_type" class="form-label">Type</label>
                    <select class="form-select" id="type" name="account_type">
                        <option value="kfs" {{ $paymentAccount->account_type == 'kfs' ? 'selected' : '' }}>KFS</option>
                        <option value="uch" {{ $paymentAccount->account_type == 'uch' ? 'selected' : '' }}>Banner/UCH</option>
                        <option value="other" {{ $paymentAccount->account_type == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="account_number" class="form-label">Account Number</label>
                    <input type="text" class="form-control" id="account_number" name="account_number" value="{{ $paymentAccount->account_number }}">
                </div>

                <div class="mb-3">
                    <label for="expiration_date" class="form-label">Expiration Date</label>
                    <input type="date" class="form-control" id="expiration_date" name="expiration_date" value="{{ $paymentAccount->expiration_date }}">
                </div>

                <!-- account fiscal_officer is a foreign key -->
                <div class="mb-3">
                    <label for="fiscal_officer" class="form-label">Fiscal Officer</label>
                    <strong>Feature not yet implemented</strong>
                </div>

                <!-- fiscal_manager is a foreign key -->
                <div class="mb-3">
                    <label for="fiscal_manager" class="form-label">Fiscal Manager</label>
                    <strong>Feature not yet implemented</strong>
                </div>

                <!-- authorized users multi select -->
                <div class="mb-3">
                    <label for="authorized_users" class="form-label">Authorized Users</label>
                    <strong>Feature not yet implemented</strong>
                </div>

                <button type="submit" class="btn btn-primary">Update Payment Account</button>
            </form>
        </div>
    </div>
</div>
@endsection