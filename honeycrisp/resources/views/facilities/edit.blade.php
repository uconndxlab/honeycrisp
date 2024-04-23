@extends('layouts.app')

@section('title', 'Edit Facility')

@section('content')
<div class="container py-2">
    <div class="row">
        <div class="col-md-12">
            <h1>Edit Facility</h1>
        </div>

        <div class="col-md-6">
            <!-- Display any form errors here -->
            @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Form for editing the facility -->
            <form action="{{ route('facilities.update', $facility->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Facility Name -->
                <div class="form-group mb-2">
                    <label for="name">Facility Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $facility->name) }}" required>
                </div>

                <!-- Description -->
                <div class="form-group mb-2">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="5" required>{{ old('description', $facility->description) }}</textarea>
                </div>

                <!-- Abbreviation -->
                <div class="form-group mb-2">
                    <label for="abbreviation">Abbreviation</label>
                    <input type="text" name="abbreviation" id="abbreviation" class="form-control" value="{{ old('abbreviation', $facility->abbreviation) }}" required>
                </div>

                <!-- Email -->
                <div class="form-group mb-2">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $facility->email) }}" required>
                </div>

                <!-- Address -->
                <div class="form-group mb-2">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $facility->address) }}">
                </div>

                <!-- Recharge Account -->
                <div class="form-group mb-2">
                    <label for="recharge_account">Recharge Account</label>
                    <input type="text" name="recharge_account" id="recharge_account" class="form-control" value="{{ old('recharge_account', $facility->recharge_account) }}" required>

                </div>



                <!-- Submit Button -->
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Update Facility</button>
                </div>
            </form>
        </div>
        @endsection

    </div>
</div>