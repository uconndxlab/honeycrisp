@extends ('layouts.app')

@section ('title', 'View Payment Account: ' . $paymentAccount->account_name)

@section ('content')

<div class="container">
    <div class="row">
        <div class="col">
            <h1>View Payment Account</h1>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <dl>
                <dt>Account Owner</dt>
                <dd>{{ $paymentAccount->owner()->name }}</dd>
                <dt>Account Name</dt>
                <dd>{{ $paymentAccount->account_name }}</dd>
                <dt>Type</dt>
                <dd>{{ $paymentAccount->account_type }}</dd>
                <dt>Account Number</dt>
                <dd>{{ $paymentAccount->account_number }}</dd>
                <dt>Expiration Date</dt>
                <dd>{{ $paymentAccount->expiration_date }}</dd>
            </dl>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <a href="{{ route('payment-accounts.edit', $paymentAccount->id) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('payment-accounts.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>
</div>

@endsection
