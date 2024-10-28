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
            <div class="col-12">
                <!-- search form -->
                <form hx-get="{{ route('payment-accounts.index') }}" hx-trigger="keyup changed delay:500ms"
                    action="{{ route('payment-accounts.index') }}" method="GET" hx-select="#payment-account-table"
                    hx-swap="innerHTML" hx-target="#payment-account-table" autocomplete="off">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="search"
                            placeholder="Search by account name, type, or owner" value="{{ request('search') }}"
                            hx-trigger="keyup,changed delay:500ms" hx-get="{{ route('payment-accounts.index') }}"
                            hx-target="#payment-account-table" hx-swap="innerHTML">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>



        {{ $paymentAccounts->links() }}
        <!-- total accounts -->
        <div class="row">
            <div class="col">
                <p>Total Accounts: {{ $paymentAccounts->total() }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <table id="payment-account-table" class="table">
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
                                <td>{{ $paymentAccount->owner()->name ?? 'WTF' }}</td>

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

        {{ $paymentAccounts->links() }}

    </div>

@endsection
