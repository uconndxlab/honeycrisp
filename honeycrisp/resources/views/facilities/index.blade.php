@extends('layouts.app')

@section('title', 'Facilities')

@section('content')
    <!-- card for creating a new facility -->

    <div class="container container-facilities">
        <h2>All Facilities</h2>
        
        <div class="row">
            <div class="col-12">
                <p>Welcome to HoneyCrisp, the centralized ordering system for shared facilities. Please select the shared facility below to view and purchase services and products offered by the facility.</p>
            </div>
        </div>


        <ul>
            @foreach ($facilities as $facility)
                <li><a href="/facilities/{{ $facility->id }}">{{ $facility->name }}</a> ({{ $facility->abbreviation }})
                    </li>
            @endforeach
        </ul>
    </div>


@endsection
