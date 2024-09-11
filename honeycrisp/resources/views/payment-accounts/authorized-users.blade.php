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
                <li class="nav-item active"><a class="nav-link" href="#">Authorized Users</a></li>
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
            
            <div class="table-responsive">
                <!-- account owner -->
                <div class="mb-3">
                    <label for="account_owner" class="form-label">Account Owner</label>
                    <input name="account_owner" class="form-control" type="text" disabled value="{{ $paymentAccount->account_owner->name }} ({{ $paymentAccount->account_owner->netid ?? 'No NetID'}})">
                </div>

                <!-- fiscal manager -->
                <div class="mb-3">
                    <label for="fiscal_officer" class="form-label">Fiscal Officer</label>
                    <input name="fiscal_officer" class="form-control" type="text" disabled value="{{ $paymentAccount->fiscal_officer->name }} ({{ $paymentAccount->fiscal_officer->netid ?? 'No NetID'}})">
                </div>

                <h3>Authorized Users</h3>
                <!-- quick form to add a new authorized user with just netid -->
                <div class="mb-3">
                    <form action="{{ route('payment-accounts.authorizedUsers.store', $paymentAccount) }}" method="POST">
                        @csrf
                        <div class="input-group">
                            <input type="text" class="form-control" name="netid" placeholder="NetID">
                            <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                    </form>
                </div>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>NetID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paymentAccount->authorized_users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->netid }}</td>
                                <td>
                                    <form action="{{ route('payment-accounts.authorizedUsers.destroy', ['paymentAccount' => $paymentAccount->id, 'user' => $user->id]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger">Remove</button>
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