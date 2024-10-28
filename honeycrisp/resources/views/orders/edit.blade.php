@extends('layouts.app')
@section('title', 'Edit Order')

@section('content')
    @include('orders.parts.order-meta-form')

    @include('orders.parts.order-items')

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-md-6">
            <button form="order-meta-form" type="submit" id="save-draft" class="btn btn-primary">
                @if (isset($order))
                    Save Order Details
                @else
                    Save Draft and Add items <i class="bi bi-arrow-right"></i>
                @endif
            </button>
        </div>
    </div>

@endsection
