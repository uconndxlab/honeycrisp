@extends('layouts.app')

@section('title', 'Facilities')

@section('content')
<!-- card for creating a new facility -->

<div class="container container-create">
    <!-- form for creating a new facility route(facilities.store) -->
    <form
    
    action="{{ route('facilities.store') }}"
    method="POST">
        @csrf

        <!-- form fields for creating a facility -->
        <label for="name">Facility Name</label>
        <input type="text" name="name" id="name" required>

        <!-- description -->
        <label for="description">Description</label>
        <textarea name="description" id="description" cols="30" rows="10"></textarea>


        <button type="submit">Create Facility</button>
    </form>
</div>

    <div class="container container-facilities">
        <h2>All Facilities</h2>
        <ul>
            @foreach ($facilities as $facility)
                <li><a href="/facilities/{{ $facility->id }}">{{ $facility->name }}</a></li>
            @endforeach
        </ul>
    </div>


@endsection
