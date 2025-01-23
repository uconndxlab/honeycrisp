@extends('layouts.app')
@section('title', 'Edit Order')

@section('content')
    @include('orders.parts.order-meta-form')

    @include('orders.parts.order-items')


                <!-- order log -->
                @if (isset($order))
                <div class="row">
                    <div class="col-md-12">
                        <div class="accordion my-2" id="orderLogAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="orderLogHeading">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#orderLogCollapse" aria-expanded="true"
                                        aria-controls="orderLogCollapse">
                                        Order Log
                                    </button>
                                </h2>
                                <div id="orderLogCollapse" class="accordion-collapse collapse show"
                                    aria-labelledby="orderLogHeading" data-bs-parent="#orderLogAccordion">
                                    <div class="accordion-body">
                                        <ul class="list-group list-group-flush">
                                            @foreach ($order->logs as $log)
                                                <li class="list-group list-group-item">
                                                    <strong>{{ optional($log->user)->netid }} </strong>
                                                    <strong>{{ $log->created_at->format('m/d/Y h:i A') }}</strong> -
                                                    {{ $log->message }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

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



