@extends('layouts.app')

@section('title', 'Create Price Rule for Product')

@section('content')

<div class="container">
    <h1>Create Price Rule for Product: {{ $product->name }}</h1>
    <form action="{{ route('price-groups.store') }}" method="POST">
        @csrf

        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div class="form-group mb-3">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>


        <div class="form-group mb-3">
            <label for="price">Price @if ($product->can_reserve) (per hour) @endif</label>
            {{-- note: use hourly rate. per-minute rate will be calculated --}}
            <span class="text-muted">({{ $product->can_reserve ? 'hourly' : 'flat' }} rate)</span>
            <input type="number" class="form-control" id="price" name="price" step="0.01" required>
        </div>


        <div class="form-group mb-3">
            <label for="start_date">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="@fiscalYearStart" required>
        </div>

        <div class="form-group mb-3">
            <label for="end_date">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="@fiscalYearEnd" required>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
    </form>


</div>




@endsection
