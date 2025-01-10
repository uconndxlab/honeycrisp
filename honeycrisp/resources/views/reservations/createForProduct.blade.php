@extends('layouts.app')

@section('content')
    <div class="container">
        @include ('reservations.parts.reservation-meta-form')
        <div class="card mb-4">
            <div class="card-header">
            <h5 class="card-title">Product Details</h5>
            </div>
            <div class="card-body">
            <p><strong>Description:</strong> {{ $product->description ?? 'No description available.' }}</p>
            <p><strong>Schedule Rules:</strong></p>
            @if ($scheduleRules->isEmpty())
                <p>No specific schedule rules for this product.</p>
            @else
                <ul>
                @foreach ($scheduleRules as $rule)
                    <li>
                    Available on <strong>{{ ucfirst($rule->day) }}</strong>:
                    {{ $rule->time_of_day_start }} - {{ $rule->time_of_day_end }}
                    </li>
                @endforeach
                </ul>
            @endif

            <p><strong>Reservation Interval:</strong> {{ $product->reservation_interval }} minutes</p>
            <p><strong>Minimum Reservation Duration:</strong> {{ $product->minimum_reservation_time }} minutes</p>
            <p><strong>Maximum Reservation Duration:</strong> {{ $product->maximum_reservation_time }} minutes</p>
            <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}/minute</p>
            </div>
        </div>





            <input type="hidden" name="product_id" value="{{ $product->id }}">


            <!-- Date Picker -->
            <div class="mb-3">
                <label for="reservation_date" class="form-label">Select a Date</label>

                {{-- next available date --}}
                <strong>Next available date:</strong>
                {{-- show the next available date based on the schedule rules --}}
                @if ($scheduleRules->isNotEmpty())
                    @php
                        $nextAvailableDate = \Carbon\Carbon::now();
                        $found = false;
                        for ($i = 0; $i < 7; $i++) {
                            $dayName = strtolower($nextAvailableDate->format('l'));
                            $ruleForDay = $scheduleRules->firstWhere('day', $dayName);
                            if ($ruleForDay) {
                                $nextAvailableDate->setTimeFromTimeString($ruleForDay->time_of_day_start);
                                $found = true;
                                break;
                            }
                            $nextAvailableDate->addDay();
                        }
                    @endphp
                    @if ($found)
                        {{ $nextAvailableDate->format('l, F j, Y') }}
                    @else
                        No available dates.
                    @endif

                @else
                   <div class="alert alert-warning" role="alert">
                        No available dates.
                    </div>
                @endif
                


                    <input type="date" class="form-control" id="reservation_date" name="reservation_date" required
                        hx-get="{{ route('reservations.create.product', ['product' => $product->id]) }}"
                        hx-target="#reservation_times" hx-select="#reservation_times" hx-swap="outerHTML" hx-push-url="true"
                        hx-trigger="change" value="{{ request('reservation_date') }}"
                        min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
            </div>

                <div id="reservation_times">

            @if (request('reservation_date'))
            @php
                $selectedDate = \Carbon\Carbon::parse(request('reservation_date'));
                $dayName = $selectedDate->format('l');
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
                                hx-get="{{ route('reservations.create.product', ['product' => $product->id]) }}?reservation_date={{ request('reservation_date') }}"
                                hx-target="#reservation_times" hx-select="#reservation_times" hx-swap="outerHTML"
                                hx-push-url="true" hx-trigger="change">
                                <option value="" disabled {{ request('reservation_start') ? '' : 'selected' }}>Select
                                    a start time</option>
                                @foreach ($rulesForDay as $rule)
                                    @php
                                        $intervalStart = \Carbon\Carbon::createFromTimeString($rule->time_of_day_start);
                                        $intervalEnd = \Carbon\Carbon::createFromTimeString($rule->time_of_day_end);
                                    @endphp
                                    @while ($intervalStart->lt($intervalEnd))
                                        @php
                                            $nextTime = $intervalStart->copy()->addMinutes(30);
                                            $isAvailable = !$reservations
                                                ->where('date', request('reservation_date'))
                                                ->where('time_of_day_start', '<=', $intervalStart->format('H:i:s'))
                                                ->where('time_of_day_end', '>', $intervalStart->format('H:i:s'))
                                                ->count();
                                        @endphp
                                        @if ($isAvailable)
                                            <option value="{{ $intervalStart->format('H:i:s') }}"
                                                {{ request('reservation_start') === $intervalStart->format('H:i:s') ? 'selected' : '' }}>
                                                {{ $intervalStart->format('g:i A') }}
                                            </option>
                                        @endif
                                        @php $intervalStart = $nextTime; @endphp
                                    @endwhile
                                @endforeach
                            </select>
                        </div>


                        <div class="mb-3">
                            <label for="reservation_end" class="form-label">Select an End Time</label>
                            <select class="form-select" id="reservation_end" name="reservation_end" required>
                                <option value="" disabled {{ request('reservation_end') ? '' : 'selected' }}>Select
                                    an end time</option>
                                @if (request('reservation_start'))
                                    @php
                                        $selectedStart = \Carbon\Carbon::createFromTimeString(
                                            request('reservation_start'),
                                        );
                                        $ruleForStart = $rulesForDay->firstWhere(
                                            'time_of_day_start',
                                            '<=',
                                            $selectedStart->format('H:i:s'),
                                        );
                                        $intervalEnd = $ruleForStart
                                            ? \Carbon\Carbon::createFromTimeString($ruleForStart->time_of_day_end)
                                            : null;
                                    @endphp
                                    @if ($intervalEnd)
                                        @while ($selectedStart->lt($intervalEnd))
                                            @php
                                                $nextTime = $selectedStart->copy()->addMinutes(30);
                                                $isAvailable = !$reservations
                                                    ->where('date', request('reservation_date'))
                                                    ->where('time_of_day_start', '<=', $selectedStart->format('H:i:s'))
                                                    ->where('time_of_day_end', '>', $selectedStart->format('H:i:s'))
                                                    ->count();
                                            @endphp
                                            @if ($isAvailable)
                                                <option value="{{ $nextTime->format('H:i:s') }}"
                                                    {{ request('reservation_end') === $nextTime->format('H:i:s') ? 'selected' : '' }}>
                                                    {{ $nextTime->format('g:i A') }}
                                                </option>
                                            @endif
                                            @php $selectedStart = $nextTime; @endphp
                                        @endwhile
                                    @endif
                                @endif
                            </select>
                            @endif
                        </div>

                @else
                    <div class="alert alert-warning" role="alert">
                        Please pick a date to see available times.
                    </div>
            @endif


            <button type="submit" class="btn btn-success">Submit Reservation</button>
        </form>

    </div>
@endsection
