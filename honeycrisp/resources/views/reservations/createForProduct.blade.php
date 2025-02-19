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
                                    <select class="form-select" id="reservation_end" name="reservation_end" required>
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

            <div id="reservation_calendar" class="row my-4 d-none">
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

                <div class="table-responsive">
                    <table class="table table-hover text-center">
                        <thead class="thead-light position-sticky top-0 bg-white">
                            <tr>
                                <th>Time</th>
                                @foreach ($daysOfWeek as $day)
                                    <th>{{ $day->format('l j') }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody hx-boot="true">
                            @php
                                $selectedStartTime = request()->query('reservation_start'); // Selected start time
                                $selectedStartDate = request()->query('reservation_date'); // Selected start date
                                $hiddenRows = [];
                                $lastUnavailableTime = null;
                            @endphp
                            @foreach ($timeSlots as $timeSlot)
                                @php
                                    $isRowEmpty = true;
                                    foreach ($daysOfWeek as $day) {
                                        $dayName = strtolower($day->format('l'));
                                        $rulesForDay = $scheduleRules->where('day', $dayName);
                                        $isAvailable = false;
                                        $isBooked = false;

                                        foreach ($rulesForDay as $rule) {
                                            $intervalStart = \Carbon\Carbon::createFromTimeString(
                                                $rule->time_of_day_start,
                                            );
                                            $intervalEnd = \Carbon\Carbon::createFromTimeString($rule->time_of_day_end);

                                            if ($timeSlot->between($intervalStart, $intervalEnd)) {
                                                $isBooked = $reservations
                                                    ->filter(function ($reservation) use ($timeSlot, $day) {
                                                        $reservationStart = \Carbon\Carbon::parse(
                                                            $reservation->reservation_start,
                                                        );
                                                        $reservationEnd = \Carbon\Carbon::parse(
                                                            $reservation->reservation_end,
                                                        );

                                                        return $reservationStart->between(
                                                            $day->copy()->setTimeFrom($timeSlot),
                                                            $day->copy()->setTimeFrom($timeSlot)->addMinutes(30),
                                                        ) ||
                                                            $reservationEnd->between(
                                                                $day->copy()->setTimeFrom($timeSlot),
                                                                $day->copy()->setTimeFrom($timeSlot)->addMinutes(30),
                                                            ) ||
                                                            ($reservationStart->lte(
                                                                $day->copy()->setTimeFrom($timeSlot),
                                                            ) &&
                                                                $reservationEnd->gte(
                                                                    $day
                                                                        ->copy()
                                                                        ->setTimeFrom($timeSlot)
                                                                        ->addMinutes(30),
                                                                ));
                                                    })
                                                    ->count();

                                                $isAvailable = !$isBooked;
                                                break;
                                            }
                                        }

                                        if ($isAvailable) {
                                            $isRowEmpty = false;
                                            break;
                                        }
                                    }

                                    // Track empty rows for consolidation
                                    if ($isRowEmpty) {
                                        if ($lastUnavailableTime === null) {
                                            $lastUnavailableTime = $timeSlot;
                                        }
                                        $hiddenRows[] = $timeSlot;
                                        continue;
                                    }

                                    // Insert "Unavailable until..." row before next available time
                                    if (!empty($hiddenRows)) {
                                        echo "<tr class='bg-light text-muted'><td colspan='" .
                                            (count($daysOfWeek) + 1) .
                                            "'>
                                            Unavailable until " .
                                            $timeSlot->format('g:i A') .
                                            '</td></tr>';
                                        $hiddenRows = [];
                                        $lastUnavailableTime = null;
                                    }
                                @endphp

                                <tr>
                                    <td>{{ $timeSlot->format('g:i A') }}</td>
                                    @foreach ($daysOfWeek as $day)
                                        @php
                                            $dayName = strtolower($day->format('l'));
                                            $rulesForDay = $scheduleRules->where('day', $dayName);
                                            $isAvailable = false;
                                            $isBooked = false;
                                            $isSelected = false;
                                            $isDisabled = false;

                                            foreach ($rulesForDay as $rule) {
                                                $intervalStart = \Carbon\Carbon::createFromTimeString(
                                                    $rule->time_of_day_start,
                                                );
                                                $intervalEnd = \Carbon\Carbon::createFromTimeString(
                                                    $rule->time_of_day_end,
                                                );

                                                if ($timeSlot->between($intervalStart, $intervalEnd)) {
                                                    $isBooked = $reservations
                                                        ->filter(function ($reservation) use ($timeSlot, $day) {
                                                            $reservationStart = \Carbon\Carbon::parse($reservation->reservation_start);
                                                            $reservationEnd = \Carbon\Carbon::parse($reservation->reservation_end);

                                                            return $reservation->date == $day->format('Y-m-d') &&
                                                                $reservationStart->lte($day->copy()->setTimeFrom($timeSlot)->addMinutes(30)) &&
                                                                $reservationEnd->gte($day->copy()->setTimeFrom($timeSlot));
                                                        })
                                                        ->count() > 0;

                                                    $isAvailable = !$isBooked;
                                                    break;
                                                }
                                            }

                                            // If a start time is selected, enforce "only later slots on that day"
                                            if ($selectedStartTime && $selectedStartDate == $day->format('Y-m-d')) {
                                                $selectedStart = \Carbon\Carbon::createFromTimeString(
                                                    $selectedStartTime,
                                                );

                                                if ($timeSlot->equalTo($selectedStart)) {
                                                    $isSelected = true;
                                                } elseif ($timeSlot->lt($selectedStart)) {
                                                    $isDisabled = true; // Disable earlier slots on this day
                                                }
                                            }

                                            // If a start time is selected, disable all other days
                                            if ($selectedStartTime && $selectedStartDate != $day->format('Y-m-d')) {
                                                $isDisabled = true;
                                            }
                                        @endphp

                                        @php
                                            $selectedStart = request()->query('reservation_start');
                                            $selectedEnd = request()->query('reservation_end');
                                        @endphp

                                        <td
                                            class="{{ $isSelected || ($selectedStart && $selectedEnd && $selectedStartDate == $day->format('Y-m-d') && $timeSlot->between(\Carbon\Carbon::parse($selectedStart), \Carbon\Carbon::parse($selectedEnd)->subMinute())) ? 'table-primary' : ($isAvailable ? ($isDisabled ? 'table-secondary unavailable' : 'table-success') : ($isBooked ? 'bg-warning' : 'bg-light')) }}">
                                            @if ($isSelected)
                                                <strong>Start: {{ $timeSlot->format('g:i A') }}</strong>
                                            @elseif (
                                                $selectedStart &&
                                                    $selectedEnd &&
                                                    $selectedStartDate == $day->format('Y-m-d') &&
                                                    $timeSlot->between(\Carbon\Carbon::parse($selectedStart), \Carbon\Carbon::parse($selectedEnd)->subMinute()))
                                                <strong></strong>
                                                {{-- if this is the last timeslot in the range, say "End" --}}

                                                {{-- if this is the first timeslot in the range, say "Start" --}}
                                            @elseif ($isBooked)
                                                <span class="text-danger">Booked</span>
                                            @elseif ($isAvailable && !$isDisabled)
                                                <a hx-get="{{ route('reservations.create.product', ['product' => $product->id]) }}?reservation_date={{ $day->format('Y-m-d') }}&reservation_start={{ request()->query('reservation_start') ?? $timeSlot->format('H:i:s') }}{{ request()->query('reservation_start') ? '&reservation_end=' . $timeSlot->format('H:i:s') : '' }}"
                                                    hx-target="#reservation_scheduling" hx-select="#reservation_scheduling"
                                                    hx-swap="outerHTML" hx-push-url="true"
                                                    href="{{ route('reservations.create.product', ['product' => $product->id]) }}?reservation_date={{ $day->format('Y-m-d') }}&reservation_start={{ request()->query('reservation_start') ?? $timeSlot->format('H:i:s') }}{{ request()->query('reservation_start') ? '&reservation_end=' . $timeSlot->format('H:i:s') : '' }}"
                                                    class="text-white">
                                                    Available
                                                </a>
                                            @else
                                                <span class="text-muted"></span>
                                            @endif

                                            @if (
                                                $selectedEnd &&
                                                    $selectedEnd == $timeSlot->copy()->addMinutes(30)->format('H:i:s') &&
                                                    $selectedStartDate == $day->format('Y-m-d'))
                                                <strong>End:
                                                    {{ $timeSlot->copy()->addMinutes(30)->format('g:i A') }}</strong>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>




                    </table>
                </div>
            </div>




        </div>

    @endsection
