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
                        <input type="text" id="filter" name="filter" class="form-control"
                            placeholder="Search for a facility">
                    </div>
                    <div>
                        <a href={{ route('facilities.create') }} id="addFacility" class="btn btn-primary">Add Facility</a>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            @if ($facilities->isEmpty())
            <div class="col-md-12 pt-4">
                <div class="alert alert-info" role="alert">
                No facilities found.
                </div>
            </div>
            @else
            @foreach ($facilities as $facility)
                <div class="col-md-4">
                <div class="card mt-4">
                    <div class="card-body">
                    <h5 class="card-title">{{ $facility->name }}</h5>
                    <p class="card-text">{{ $facility->description }}</p>
                    <p class="card-text">{{ $facility->email }}</p>
                    <p class="card-text">{{ $facility->address }}</p>
                    <p class="card-text">{{ $facility->abbreviation }}</p>
                    <p class="card-text">{{ $facility->recharge_account }}</p>
                    <a href="{{ route('facilities.show', $facility->id) }}" class="btn btn-primary">View</a>
                    <a href="{{ route('facilities.edit', $facility->id) }}" class="btn btn-secondary">Edit</a>
                    <form action="{{ route('facilities.destroy', $facility->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                    </div>
                </div>
                </div>
            @endforeach
            @endif
        </div>
        </div>
    </div>
@endsection
