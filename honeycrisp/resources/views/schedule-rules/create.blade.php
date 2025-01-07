@extends('layouts.app')

@section('content')
<div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Create Schedule Rule</h3>

                {{-- Display any success messages --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Display validation errors --}}
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('schedule-rules.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div>
                        <p>{{ $product->name }}</p>
                    </div>

                    <div class="form-group">
                        <label for="day_of_week">Day(s) of the Week</label>
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="day[]" id="{{ $day }}" value="{{ $day }}" {{ in_array($day, old('day', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="{{ $day }}">{{ ucfirst($day) }}</label>
                            </div>
                        @endforeach
                        @error('day')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="time_of_day_start">Start Time</label>
                        <input type="time" class="form-control" id="time_of_day_start" name="time_of_day_start" value="{{ old('time_of_day_start') }}">
                        @error('time_of_day_start')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="time_of_day_end">End Time</label>
                        <input type="time" class="form-control" id="time_of_day_end" name="time_of_day_end" value="{{ old('time_of_day_end') }}">
                        @error('time_of_day_end')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
