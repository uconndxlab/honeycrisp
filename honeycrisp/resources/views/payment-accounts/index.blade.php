@extends('layouts/app')
@section('title', 'Payment Accounts')

@section('content')
    <div class="container">
        <div class="row my-3">
            <div class="col">
                <h1>Payment Accounts</h1>
                <a href="{{ route('payment-accounts.create') }}" class="btn btn-primary">Add Payment Account</a>

            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Account Name</th>
                            <th>Account Type</th>
                            <th>Account Number</th>
                            <th>Owner</th>

                            <th>Expiration Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($paymentAccounts as $paymentAccount)
                            <tr>
                                <td>{{ $paymentAccount->account_name }}</td>
                                <td>{{ strtoupper($paymentAccount->account_type) }}</td>
                                <td>{{ $paymentAccount->account_number }}</td>
                                <td>{{ $paymentAccount->owner()->name }}</td>

                                <td>{{ $paymentAccount->expiration_date }}</td>
                                <td>
                                    <a href="{{ route('payment-accounts.show', $paymentAccount->id) }}"
                                        class="btn btn-primary">View</a>
                                    <a href="{{ route('payment-accounts.edit', $paymentAccount->id) }}"
                                        class="btn btn-secondary">Edit</a>
                                    <form action="{{ route('payment-accounts.destroy', $paymentAccount->id) }}"
                                        method="POST" style="display: inline;">
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

@endsection
