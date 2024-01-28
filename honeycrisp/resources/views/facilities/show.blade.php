@extends('layouts.app')

@section('title', 'Single Facility View')

@section('content')
    <!-- card for creating a new facility -->


    <div class="container container-single-facility">
        <h2> 
            {{ $facility->name }}
        </h2>
        <p>
            {{ $facility->description }}
        </p>
      
    </div>


@endsection
