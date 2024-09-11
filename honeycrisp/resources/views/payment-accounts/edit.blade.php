@extends('layouts/app')

@section('title', 'Edit Payment Account')

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <h1>Edit Payment Account: {{ $paymentAccount->account_name }} ({{$paymentAccount->account_type}} - {{$paymentAccount->account_number}})  </h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2 subnav">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="#">Account Info</a></li>
                <li class="nav-item"><a class="nav-link" href="{{route('payment-accounts.authorizedUsers', $paymentAccount->id)}}">Authorized Users</a></li>
            </ul>
        </div>
        <div class="col-md-10">
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
                    <label for="account_owner" class="form-label">Account Owner</label>
                    <input name="account_owner" class="form-control" type="text" disabled value="{{ $paymentAccount->account_owner->name }} ({{ $paymentAccount->account_owner->netid ?? 'No NetID'}})">
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
                    <input required type="date" class="form-control" id="expiration_date" name="expiration_date" value="{{ $paymentAccount->expiration_date }}">
                </div>

                <!-- account fiscal_officer is a foreign key -->
                <div class="mb-3">
                    <label for="fiscal_officer" class="form-label" >Fiscal Officer</label>
                    <input class="form-control" type="text" disabled value="{{ $paymentAccount->fiscal_officer->name }} ({{ $paymentAccount->fiscal_officer->netid ?? 'No NetID'}})">
                </div>

                <!-- fiscal_manager is a foreign key -->
                <div class="mb-3">
                    <label for="fiscal_manager" class="form-label">Fiscal Manager</label>
                    <input class="form-control" type="text" disabled value="{{ $paymentAccount->fiscal_manager->name ?? '-- Not Yet Assigned --' }} ({{ $paymentAccount->fiscal_manager->netid ?? 'No NetID'}})">
                </div>
                <button type="submit" class="btn btn-primary">Update Payment Account</button>
            </form>
        </div>
    </div>
</div>
@endsection