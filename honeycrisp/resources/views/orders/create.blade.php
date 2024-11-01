@extends('layouts.app')

@section('title', 'Create Order')

@section('content')

    @include ('orders.parts.order-meta-form')

    <!-- Action Buttons -->
    <div class="row d-none" id="action-buttons">
        <div class="col-md-6">
            <button type="submit" id="save-draft" 
            form="order-meta-form"
            class="btn btn-primary btn-lg">
                @if (isset($order))
                    Save Order Details
                @else
                    Add items <i class="bi bi-arrow-right"></i>
                @endif
            </button>
        </div>
    </div>


<script>
    // only show action buttons if the order-meta-form is valid

    const orderMetaForm = document.getElementById('order-meta-form');
    const actionButtons = document.getElementById('action-buttons');

    orderMetaForm.addEventListener('input', function() {
        if (orderMetaForm.checkValidity()) {
            actionButtons.classList.remove('d-none');
        } else {
            actionButtons.classList.add('d-none');
        }
    });
</script>
@endsection
