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
                    <div class="card-header">
                        <h2>{{ isset($user) ? 'Edit' : 'Create' }} User</h2>
                    </div>
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
                                <label for="external_rates">Price Group</label>
                                <select name="price_group" id="external_rates" class="form-select">
                                    <option value="">Select External Rates</option>
                                    <!-- internal -->
                                    <!-- external_for_profit -->
                                    <!-- external_non_profit -->

                                    <option value="no" {{ isset($user) && $user->price_group == 'no' ? 'selected' : '' }}>Internal</option>
                                    <option value="external_forprofit" {{ isset($user) && $user->price_group == 'external_forprofit' ? 'selected' : '' }}>External For Profit</option>
                                    <option value="external_nonprofit" {{ isset($user) && $user->price_group == 'external_nonprofit' ? 'selected' : '' }}>External Non Profit</option>

                                </select>
                            </div>

                            
                            <div class="form-group mx-3 bg-light p-2 ">
                                <!-- external_organization -->
                                <label for="external_organization">External Organization</label>
                                <input type="text" name="external_organization" id="external_organization" class="form-control" value="{{ isset($user) ? $user->external_organization : old('external_organization') }}">
                            </div>

                            <div class="form-group mx-3 bg-light p-2 mb-3">
                                <!-- external_customer_id -->
                                <label for="external_customer_id">External Customer ID</label>
                                <input type="text" name="external_customer_id" id="external_customer_id" class="form-control" value="{{ isset($user) ? $user->external_customer_id : old('external_customer_id') }}">
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
                    <h2>Payment Accounts</h2>

                    @if (!$user->paymentAccounts->isEmpty())
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
                    @endif

                    <!-- if no accounts -->
                    @if($user->paymentAccounts->isEmpty())
                        <div class="alert alert-info">
                            <p>No payment accounts found for this user.</p>
                            <a href="{{ route('payment-accounts.create') }}?netid={{ $user->netid }}" class="btn btn-primary">Add Payment Account</a>
                        </div>
                    @endif

                </div>
            </div>
        @endif

        <script>
            // if external rates is no, hide external organization and external customer id
            document.getElementById('external_rates').addEventListener('change', function() {
                if (this.value === 'no') {
                    document.getElementById('external_organization').parentElement.style.display = 'none';
                    document.getElementById('external_customer_id').parentElement.style.display = 'none';
                } else {
                    document.getElementById('external_organization').parentElement.style.display = 'block';
                    document.getElementById('external_customer_id').parentElement.style.display = 'block';
                }
            });

            // on load too 
            if ((document.getElementById('external_rates').value === 'no') || (document.getElementById('external_rates').value === '')){
                document.getElementById('external_organization').parentElement.style.display = 'none';
                document.getElementById('external_customer_id').parentElement.style.display = 'none';
            }
        </script>
    </div>
@endsection
