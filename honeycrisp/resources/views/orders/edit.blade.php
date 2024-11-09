@extends('layouts.app')
@section('title', 'Edit Order')

@section('content')
    @include('orders.parts.order-meta-form')

    @include('orders.parts.order-items')


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



