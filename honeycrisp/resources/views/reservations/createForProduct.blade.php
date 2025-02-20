@extends('layouts.app')
@section('title', 'Create Reservation')
@section('content')
    <div class="container">
        @include ('reservations.parts.reservation-meta-form')
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title">Instrument Details</h5>
            </div>
            <div class="card-body">
                <p><strong>Description:</strong> {{ $product->description ?? 'No description available.' }}</p>
                <p><strong>Availability</strong></p>
                @if ($scheduleRules->isEmpty())
                    <p>No specific schedule rules for this product.</p>
                @else
                    <ul>
                        @foreach ($product->scheduleRules as $rule)
                            <li>
                                <strong>{{ ucfirst($rule->day) }}</strong>:
                                {{ $rule->time_of_day_start }} - {{ $rule->time_of_day_end }}
                            </li>
                        @endforeach
                    </ul>
                @endif

                <p><strong>Reservation Interval:</strong> {{ $product->reservation_interval }} minutes</p>
                <p><strong>Minimum Reservation Duration:</strong> {{ $product->minimum_reservation_time }} minutes</p>
                <p><strong>Maximum Reservation Duration:</strong> {{ $product->maximum_reservation_time }} minutes</p>
            </div>
        </div>





        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div id="reservation_scheduling">
            <div class="row my-3">
                <div class="col-md-6">
                    <!-- Date Picker -->
                    <div id="reservation_date">
                        <label for="reservation_date" class="form-label">Select a Date</label> <br>

                        {{-- next available date --}}


                        <input type="date" class="form-control" id="reservation_date" name="reservation_date" required
                            hx-get="{{ route('reservations.create.product', ['product' => $product->id]) }}?user_id={{ request('user_id') }}"
                            hx-target="#reservation_times" hx-select="#reservation_times" hx-swap="outerHTML"
                            hx-push-url="true" hx-trigger="change" value="{{ $reservation_date }}"
                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div id="reservation_times" class="mb-3">
                        @if ($reservation_date)
                            @php
                                $dayName = strtolower(\Carbon\Carbon::parse($reservation_date)->format('l'));
                                $rulesForDay = $scheduleRules->where('day', strtolower($dayName));
                            @endphp
                            @if ($rulesForDay->isEmpty())
                                <div class="alert alert-warning" role="alert">
                                    No times available that day.
                                </div>
                            @else
                                <!-- Time Slot Picker -->
                                <div class="mb-3">
                                    <label for="reservation_start" class="form-label">Select a Start Time</label>
                                    <select class="form-select" id="reservation_start" name="reservation_start" required
                                        hx-get="{{ route('reservations.create.product', ['product' => $product->id]) }}?reservation_date={{ request('reservation_date') }}&user_id={{ request('user_id') }}"
                                        hx-target="#reservation_times" hx-select="#reservation_times" hx-swap="outerHTML"
                                        hx-push-url="true" hx-trigger="change">
                                        <option value="" disabled
                                            {{ request('reservation_start') ? '' : 'selected' }}>
                                            Select
                                            a start time</option>

                                        @foreach ($availableStartTimes as $time)
                                            <option value="{{ $time }}"
                                                {{ request('reservation_start') === $time ? 'selected' : '' }}>
                                                {{ $time }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="reservation_end" class="form-label">Select an End Time</label>
                                    <select 
                                    hx-get="{{ route('reservations.create.product', ['product' => $product->id]) }}?reservation_date={{ request('reservation_date') }}&reservation_start={{ request('reservation_start') }}&user_id={{ request('user_id') }}"
                                    hx-target="#reservation_times" hx-select="#reservation_times" hx-swap="outerHTML"
                                    hx-push-url="true" hx-trigger="change"
                                    class="form-select" id="reservation_end" name="reservation_end" required>
                                        <option value="" disabled {{ request('reservation_end') ? '' : 'selected' }}>
                                            Select
                                            an end time</option>
                                        
                                        @foreach ($availableEndTimes as $time)
                                            <option value="{{ $time }}"
                                                {{ request('reservation_end') === $time ? 'selected' : '' }}>
                                                {{ $time }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        @else
                            <div class="alert alert-warning" role="alert">
                                Please pick a date to see available times.
                            </div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-success">Submit Reservation</button>
                </div>
            </div>

            <div id="reservation_calendar" class="row my-4">
                @php
                    $daysOfWeek = collect();
                    $currentDate = request('reservation_date')
                        ? \Carbon\Carbon::parse(request('reservation_date'))->startOfWeek()
                        : \Carbon\Carbon::now()->startOfWeek();

                    for ($i = 0; $i < 7; $i++) {
                        $daysOfWeek->push($currentDate->copy());
                        $currentDate->addDay();
                    }

                    $timeSlots = collect();
                    $startTime = \Carbon\Carbon::createFromTimeString('00:00');
                    $endTime = \Carbon\Carbon::createFromTimeString('23:30');

                    while ($startTime->lte($endTime)) {
                        $timeSlots->push($startTime->copy());
                        $startTime->addMinutes(30);
                    }
                @endphp

            </div>




        </div>

    @endsection
