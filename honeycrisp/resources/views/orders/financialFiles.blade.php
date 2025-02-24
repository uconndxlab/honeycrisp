@extends('layouts/app')

@section('title', 'Financial Files for Order #' . $order->id)

@section('content')

@include('orders.parts.order-meta-show')


{{-- if the order payment account type is UCH and the facility's recharge account is UCH, this  is a banner-to-banner order --}}
{{-- if the order payment account type is KFS and the facility's recharge account is KFS, this is a KFS-to-KFS account --}}



@if ($payment_account->account_type == 'uch' && $order->facility->account_type == 'uch')
    <div class="alert alert-info">
        Customer account: is a UCH account. Facility account: is a UCH account.
    </div>

    {{-- foreach order item, spit out kfsdebit and kfscredit --}}



@elseif ($payment_account->account_type == 'kfs' && $order->facility->account_type == 'kfs')



    <div class="container">
        <div class="alert alert-info">
            <h3>This is a KFS to KFS Order</h3>
            <p>Customer account is a KFS account. Facility account: is a KFS account.</p>
            <a href="{{ route('orders.financialFiles.download', $order) }}" class="mt-4 btn btn-primary">
                Download <strong>order-{{ $order->id }}.dat</strong>
            </a>
        </div>
        <pre>{{ $order->financialFile() }}</pre>
    </div>

     
@endif

@if ($payment_account->account_type == 'kfs' && $order->facility->account_type == 'uch')
    <div class="alert alert-info">
        Customer account: is a KFS account. Facility account: is a UCH account.
    </div>
@endif


@if ($payment_account->account_type == 'uch' && $order->facility->account_type == 'kfs')
    <div class="alert alert-info">
        Customer account: is a UCH account. Facility account: is a KFS account.
    </div>
@endif

{{-- if the payment account category is tc, it's a TIP company so the object code needs to be 4510--}}
@if ($payment_account->category == 'tc')
    <div class="alert alert-info">
        This is a TIP company. The object code for this order should be 4510.
    </div>
@endif

@endsection