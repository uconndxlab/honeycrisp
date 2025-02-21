@extends('layouts.app')
@section('title', 'Create Reservation')
@section('content')
    <div class="container">
        @include ('reservations.parts.reservation-meta-form')

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
                            hx-target="#reservation_scheduling" hx-select="#reservation_scheduling" hx-swap="outerHTML"
                            hx-push-url="true" hx-trigger="change" value="{{ $reservation_date }}"
                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                    </div>

                    <div id="reservation_calendar" class="row my-4">
                        <div class="col-md-12">
        
                            @if ($reservation_date)
                                @php
                                    $startOfDay = \Carbon\Carbon::parse($reservation_date)->startOfDay();
                                    $endOfDay = \Carbon\Carbon::parse($reservation_date)->endOfDay();
                                    $interval = $product->reservation_interval;
                                    $times = \Carbon\CarbonPeriod::create($startOfDay, $interval . ' minutes', $endOfDay);
                                @endphp
                                <div class="card">
                                    <div class="card-header bg-primary text-white">
                                        <h4 class="mb-0">
                                            @if ($reservation_start and empty($reservation_end))
                                                End Times Available for {{ $reservation_date }} starting at
                                                {{ $reservation_start }}
                                            @elseif (empty($reservation_start))
                                                Start Times Available for {{ $reservation_date }}
                                            @else
                                                Reservation Ready for Submission
                                            @endif
                                        </h4>
                                    </div>
                                    <div class="card-body p-2">
                                        <div class="alert alert-info" role="alert">
                                            @if (empty($reservation_start))
                                                Select your <strong>start time</strong> to see available end times.
                                            @elseif (empty($reservation_end))
                                                Select your <strong>end time</strong> to complete your reservation.
                                            @else
                                                {{$reservation_date}} <strong>{{ $reservation_start }}</strong> to <strong>{{ $reservation_end }}</strong>.
                                            @endif
                                        </div>
                                        <div class="d-flex flex-wrap">
                                            @if ($reservation_start && empty($reservation_end))
                                                <div class="time-block selected">
                                                    <span>{{ $reservation_start }}</span>
                                                </div>
        
                                                @foreach ($availableEndTimes as $time)
                                                    <div class="time-block available {{ request('reservation_end') === $time ? 'selected' : '' }}">
                                                        <span>
                                                            <a 
                                                            hx-get="{{ route('reservations.create.product', ['product' => $product->id]) }}?reservation_date={{ request('reservation_date') }}&reservation_start={{ request('reservation_start') }}&reservation_end={{ $time }}&user_id={{ request('user_id') }}"
                                                            hx-target="#reservation_scheduling" hx-select="#reservation_scheduling" hx-swap="outerHTML"
                                                            hx-push-url="true"
                                                            href="{{ route('reservations.create.product', ['product' => $product->id]) }}?reservation_date={{ request('reservation_date') }}&reservation_start={{ request('reservation_start') }}&reservation_end={{ $time }}&user_id={{ request('user_id') }}">
                                                                {{ $time }}
                                                            </a>
                                                        </span>
                                                    </div>
                                                @endforeach
                                            @elseif ($reservation_date && empty($reservation_start))
                                                @foreach ($availableStartTimes as $time)
                                                    <div class="time-block available {{ request('reservation_start') === $time ? 'selected' : '' }}">
                                                        <span>
                                                            <a 
                                                            hx-get="{{ route('reservations.create.product', ['product' => $product->id]) }}?reservation_date={{ request('reservation_date') }}&reservation_start={{ $time }}&user_id={{ request('user_id') }}"
                                                            hx-target="#reservation_scheduling" hx-select="#reservation_scheduling" hx-swap="outerHTML"
                                                            hx-push-url="true"
                                                            href="{{ route('reservations.create.product', ['product' => $product->id]) }}?reservation_date={{ request('reservation_date') }}&reservation_start={{ $time }}&user_id={{ request('user_id') }}">
                                                                {{ $time }}
                                                            </a>
                                                        </span>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
        
                            <style>
                                .time-block {
                                    width: 100px;
                                    margin: 5px;
                                    padding: 10px;
                                    border-radius: 5px;
                                    text-align: center;
                                }
        
                                .available {
                                    background-color: #6c757d;
                                    color: white;
                                }
        
                                .selected {
                                    background-color: #007bff;
                                    color: white;
                                }
        
                                .available a {
                                    color: white;
                                    text-decoration: none;
                                }
        
                                .unavailable {
                                    display: none;
                                    /* faded grey like disabled */
                                    background-color: #f8f9fa;
        
        
                                    color: #ccc;
                                }
                            </style>
                        </div>
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
                                        hx-target="#reservation_scheduling" hx-select="#reservation_scheduling" hx-swap="outerHTML"
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
                                        hx-target="#reservation_scheduling" hx-select="#reservation_scheduling" hx-swap="outerHTML"
                                        hx-push-url="true" hx-trigger="change" class="form-select" id="reservation_end"
                                        name="reservation_end" required>
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
                    <a
                    hx-get="{{ route('reservations.create.product', ['product' => $product->id]) }}?user_id={{ request('user_id') }}"
                    hx-target="#reservation_scheduling" hx-select="#reservation_scheduling" hx-swap="outerHTML"
                    hx-push-url="true" 
                    href="{{ route('reservations.create.product', ['product' => $product->id]) }}?user_id={{ request('user_id') }}"
                    class="btn btn-secondary ms-2">Change</a>
                </div>
            </div>
            

    @endsection
