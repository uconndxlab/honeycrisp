@extends('layouts.app')

@section('title', 'Facilities')

@section('content')
<div class="container">
    <div class="row my-3">
        <div class="col-md-12">
            <h1>Facilities</h1>
            <!-- filter UI and a spot for an admin menu like "Add Facility" go here -->

            <div class="d-flex justify-content-between align-items-center">
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
        @foreach ($facilities->sortBy('name') as $facility)
        <div class="col-md-4">
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">{{ $facility->name }} ({{$facility->abbreviation}}) </h5>
                    <p class="card-text my-2">{{ $facility->description }}</p>
                    <p class="card-text my-2">{{ $facility->email }}</p>
                    <p class="card-text my-2">
                    <address>{{ $facility->address }}</address>
                    </p>
                    <a href="{{ route('facilities.show', $facility->id) }}" class="btn btn-primary">View</a>
                    @can('admin')
                    <a href="{{ route('facilities.edit', $facility->id) }}" class="btn btn-secondary">Manage</a>
                    {{-- <form action="{{ route('facilities.destroy', $facility->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this facility?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form> --}}
                    @endcan
                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>
</div>
@endsection