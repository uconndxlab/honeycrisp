@extends('layouts.app')

@section('title', 'Facilities')

@section('content')
    <!-- card for creating a new facility -->

    <div class="container container-create">
        <div class="accordion mb-5">
            <div class="accordion-item">
                <h3 class="accordion-header" id="create-facility-heading">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#create-facility-collapse" aria-expanded="true" aria-controls="create-facility-collapse">
                        Create Facility
                    </button>
                </h3>
                <div id="create-facility-collapse" class="accordion-collapse collapse collapsed" aria-labelledby="create-facility-heading">
                    <div class="accordion-body">
                        <!-- form for creating a new facility route(facilities.store) -->
                        <form action="{{ route('facilities.store') }}" method="POST">
                            @csrf

                            <p>
                                <!-- form fields for creating a facility -->
                                <label for="name">Facility Name</label>
                                <input type="text" name="name" id="name" required>
                            </p>

                            <p>
                                <!-- description -->
                                <label for="description">Description</label>
                                <textarea name="description" id="description" cols="30" rows="10"></textarea>
                            </p>

                            <p>
                                <!-- abbreviation -->
                                <label for="abbreviation">Abbreviation</label>
                                <input type="text" name="abbreviation" id="abbreviation" required>
                            </p>

                            <p> <!f-- status (enum active/intactive) -->
                                <label for="status">Status</label>
                                <select name="status" id="status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </p>

                            <p>
                                <button type="submit">Create Facility</button>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

   

    <div class="container container-facilities">
        <h2>All Facilities</h2>
        <ul>
            @foreach ($facilities as $facility)
                <li><a href="/facilities/{{ $facility->id }}">{{ $facility->name }}</a> ({{ $facility->abbreviation }})
                    </li>
            @endforeach
        </ul>
    </div>


@endsection
