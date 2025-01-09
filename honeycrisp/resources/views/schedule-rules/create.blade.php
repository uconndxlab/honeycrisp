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
                        <p><strong>Product:</strong> {{ $product->name }}</p>
                        <p><strong>Reservation Interval:</strong> {{ $product->reservation_interval }} minutes</p>
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

                    @php
                        // Generate time options based on the product's reservation interval
                        $interval = (int) $product->reservation_interval; // Interval in minutes
                        $timeOptions = [];
                        $currentTime = \Carbon\Carbon::createFromTime(0, 0, 0); // Start at 12:00 AM
                        $endOfDay = \Carbon\Carbon::createFromTime(23, 59, 59); // End at 11:59 PM

                        while ($currentTime <= $endOfDay) {
                            $timeOptions[] = $currentTime->format('H:i');
                            $currentTime->addMinutes($interval);
                        }
                    @endphp

                    <div class="form-group">
                        <label for="time_of_day_start">Start Time</label>
                        <select class="form-control" id="time_of_day_start" name="time_of_day_start">
                            <option value="">Select Start Time</option>
                            @foreach($timeOptions as $time)
                                <option value="{{ $time }}" {{ old('time_of_day_start') === $time ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromFormat('H:i', $time)->format('g:i A') }}
                                </option>
                            @endforeach
                        </select>
                        @error('time_of_day_start')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="time_of_day_end">End Time</label>
                        <select class="form-control" id="time_of_day_end" name="time_of_day_end">
                            <option value="">Select End Time</option>
                            @foreach($timeOptions as $time)
                                <option value="{{ $time }}" {{ old('time_of_day_end') === $time ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::createFromFormat('H:i', $time)->format('g:i A') }}
                                </option>
                            @endforeach
                        </select>
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
