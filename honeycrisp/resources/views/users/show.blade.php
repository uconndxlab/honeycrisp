@extends('layouts.app')
@section('title', 'Viewing User: ' . $user->name)


@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 my-3">
                <div class="card">
                    <div class="card-header">
                        <h2>User Information</h2>
                    </div>
                    <div class="card-body">
                        <p><strong>Name:</strong> {{ $user->name }}</p>
                        <p><strong>ID:</strong> {{ $user->id }}</p>
                        <p><strong>NetID:</strong> {{ $user->netid }}</p>
                        <p><strong>Email:</strong> {{ $user->email }}</p>
                        <p><strong>Role:</strong> {{ $user->role }}</p>
                        <p><strong>Status:</strong> {{ $user->status }}</p>
                        <p><strong>Price Group:</strong> {{ $user->price_group }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h2>
                    {{ $user->name }}'s Payment
                    Accounts</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Account ID</th>
                            <th>Account Name</th>
                            <th>Account Role</th>
                            <th>Account Number</th>
                            <th>Account Type</th>
                            <th>Account Status</th>
                            <th>Expire Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($user->paymentAccounts as $account)
                            <tr>
                                <td>{{ $account->id }}</td>
                                <td>{{ $account->account_name }}</td>
                                <td>{{ $account->pivot->role }}</td>
                                <td>{{ $account->account_number }}</td>
                                <td>{{ $account->account_type }}</td>
                                <td>{{ $account->account_status }}</td>
                                <td>{{ $account->expiration_date }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">Edit</a>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection