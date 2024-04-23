@extends('layouts.app')

@section('title', 'Create Facility')

@section('content')
    <div class="container py-2">
        <div class="row">
            <div class="col-md-12">
                <h1>Create a Facility</h1>
                <!-- filter UI and a spot for an admin menu like "Add Facility" go here -->


                
            </div>
        </div>

        <div class="row">
            <!-- name, abbreviation, image, payment source, and address fields go here -->

            <div class="col-md-6">
                <form action="{{ route('facilities.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="abbreviation" class="form-label">Abbreviation</label>
                        <input type="text" class="form-control" id="abbreviation" name="abbreviation" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="text" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="recharge_account" class="form-label">Recharge Destination Account:</label>
                        <input type="text" class="form-control" id="recharge_account" name="recharge_account" required>

                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <input type="text" class="form-control" id="address" name="address">
                    </div>
                    <button type="submit" class="btn btn-primary">Create Facility</button>
                </form>
            </div>
        </div>

        </div>
    </div>
@endsection
