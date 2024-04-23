@extends('layouts.app')

@section('title', 'Facilities')

@section('content')
    <div class="container py-2">
        <div class="row">
            <div class="col-md-12">
                <h1>Facilities</h1>
                <!-- filter UI and a spot for an admin menu like "Add Facility" go here -->

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <input type="text" id="filter" name="filter" class="form-control" placeholder="Search for a facility">
                    </div>
                    <div>
                        <a href={{ route('facilities.create') }} id="addFacility" class="btn btn-primary">Add Facility</a>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="row">
            <!-- Your facilities list goes here -->
        </div>
    </div>
@endsection
