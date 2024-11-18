@extends('layouts.app')
@section('title', 'Edit Order')

@section('content')
    @include('orders.parts.order-meta-form')

    @include('orders.parts.order-items')

    <form action="{{ route('orders.destroy', $order->id) }}" method="POST" class="ms-auto" style="display: inline-block; float: right;" onsubmit="return confirm('Are you sure you want to delete this order?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">Delete This Order</button>
    </form>
    <script>
        // when the form gets changed at all, remove btn-disabled class from the submit button
        const orderMetaForm = document.getElementById('order-meta-form');
        const submitBtn = document.getElementById('save-order');
        const payment_account = document.getElementById('payment_account_search');
    
        orderMetaForm.addEventListener('input', function() {
            submitBtn.classList.remove('disabled');
        });

        payment_account.addEventListener('input', function() {
            submitBtn.classList.remove('disabled');
        });


    
        // when the form gets submitted, add the btn-disabled class to the submit button
        orderMetaForm.addEventListener('submit', function() {
            submitBtn.classList.add('disabled');
        });
    </script>

@endsection



