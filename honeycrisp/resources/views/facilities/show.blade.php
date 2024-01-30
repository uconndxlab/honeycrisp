@extends('layouts.app')

@section('title', 'Single Facility View')

@section('content')
    <!-- card for creating a new facility -->


    <div class="container container-single-facility">
        <h2> 
            {{ $facility->name }} ({{ $facility->abbreviation }})
        </h2>

        <p>
            {{ $facility->status }}
        </p>

        <div>
            {{ $facility->description }}
        </div>

        <p>
            <a href="/facilities/{{ $facility->id }}/edit">Edit</a>
        </p>

        <p>
    



        <form action="/facilities/{{ $facility->id }}" method="POST">
            @csrf
            @method('DELETE')

            <button type="submit">Delete</button>
        </form>
      
    </div>


@endsection
