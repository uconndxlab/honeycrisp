@extends('layouts.app')

@if(isset($user))
    @section('title', 'Edit User: ' . $user->name)
@endif

@section('title', 'Create User')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">Create User</div>
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            @foreach ($errors->all() as $error)
                                <p class="mb-0">{{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                        <form method="POST" action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}">
                            @csrf
                            @if(isset($user))
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $user->id }}">
                            @endif
                            <div class="form-group mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ isset($user) ? $user->name : old('name') }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ isset($user) ? $user->email : old('email') }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="netid">NetID</label>
                                <input type="text" name="netid" id="netid" class="form-control" value="{{ isset($user) ? $user->netid : old('net
id') }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="role">Role</label>
                                <select name="role" id="role" class="form-select" required>
                                    <option value="">Select Role</option>
                                    <option value="admin" {{ isset($user) && $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ isset($user) && $user->role == 'user' ? 'selected' : '' }}>User</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="">Select Status</option>
                                    <option value="active" {{ isset($user) && $user->status == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ isset($user) && $user->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label for="external_rates">External Rates</label>
                                <select name="external_rates" id="external_rates" class="form-select" required>
                                    <option value="">Select External Rates</option>
                                    <option value="no" {{ isset($user) && $user->external_rates == 'no' ? 'selected' : '' }}>No</option>

                                    <option value="yes" {{ isset($user) && $user->external_rates == 'yes' ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>


                            @if(!isset($user))
                                <div class="form-group mb-3">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
                                </div>
                            @endif
                            <button type="submit" class="btn btn-primary">{{ isset($user) ? 'Update' : 'Create' }}</button>
                        </form>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($user))
            <div class="row mt-3">
                <div class="col-md-12">
                    <h2>Accounts</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Account Name</th>
                                <th>Account Type</th>
                                <th>Account Number</th>
                                <th>Expiration Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user->paymentAccounts as $account)
                                <tr>
                                    <td>{{ $account->account_name }}</td>
                                    <td>{{ $account->account_type }}</td>
                                    <td>{{ $account->account_number }}</td>
                                    <td>{{ $account->expiration_date }}</td>
                                    <td>
                                        <a href="{{ route('payment-accounts.edit', $account->id) }}" class="btn btn-primary">Edit</a>
                                        <form action="{{ route('payment-accounts.destroy', $account->id) }}" method="POST" class="d-inline">
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
        @endif
    </div>
@endsection
