@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Reserve {{ $product->name }}</h1>

        <div class="card mb-4">
            <div class="card-body">
                <p><strong>Description:</strong> {{ $product->description ?? 'No description available.' }}</p>
                <p><strong>Facility:</strong> {{ $product->facility->name }}</p>
                <p><strong>Schedule Rules:</strong></p>
                @if($scheduleRules->isEmpty())
                    <p>No specific schedule rules for this product.</p>
                @else
                    <ul>
                        @foreach($scheduleRules as $rule)
                            <li>
                                Available on <strong>{{ ucfirst($rule->day) }}</strong>: 
                                {{ $rule->time_of_day_start }} - {{ $rule->time_of_day_end }}
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- show the next 5 days, their availability (based on schedulerules and on the current reservations) --}}

    <div class="card mb-4">
        <div class="card-body">
        <h5>Availability for the next 5 days</h5>
        @for ($i = 0; $i < 5; $i++)
            @php
            $date = \Carbon\Carbon::now()->addDays($i);
            $dayName = $date->format('l');
            $rulesForDay = $scheduleRules->where('day', strtolower($dayName));
            $reservationsForDay = $reservations->where('date', $date->toDateString());
            @endphp
            <div class="mb-3">
            <h6>{{ $date->format('l, F j, Y') }}</h6>
            @if ($rulesForDay->isEmpty())
                <p>No availability for this day.</p>
            @else
                <ul>
                @foreach ($rulesForDay as $rule)
                    @php
                    $available = true;
                    foreach ($reservationsForDay as $reservation) {
                        if ($reservation->time_of_day_start < $rule->time_of_day_end && $reservation->time_of_day_end > $rule->time_of_day_start) {
                        $available = false;
                        break;
                        }
                    }
                    @endphp
                    <li>
                    {{ $rule->time_of_day_start }} - {{ $rule->time_of_day_end }}: 
                    @if ($available)
                        <span class="text-success">Available</span>
                    @else
                        <span class="text-danger">Not Available</span>
                    @endif
                    </li>
                @endforeach
                </ul>
            @endif
            </div>
        @endfor
        </div>
    </div>


    <form action="{{ route('reservations.store') }}" method="POST">
        @csrf
    
        <input type="hidden" name="product_id" value="{{ $product->id }}">
    
        <!-- Date Picker -->
        <div class="mb-3">
            <label for="reservation_date" class="form-label">Select a Date</label>
            <select class="form-select @error('reservation_date') is-invalid @enderror" id="reservation_date" name="reservation_date" required>
                <option value="" disabled selected>Select a date</option>
                @for ($i = 0; $i < 5; $i++)
                    @php
                    $date = \Carbon\Carbon::now()->addDays($i);
                    $dayName = $date->format('l');
                    $rulesForDay = $scheduleRules->where('day', strtolower($dayName));
                    $isAvailable = !$rulesForDay->isEmpty();
                    @endphp
                    @if ($isAvailable)
                        <option value="{{ $date->toDateString() }}">{{ $date->format('l, F j, Y') }}</option>
                    @endif
                @endfor
            </select>
            @error('reservation_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <!-- Time Slot Picker -->
        <div class="mb-3">
            <label for="reservation_start" class="form-label">Select a Start Time</label>
            <select class="form-select @error('reservation_start') is-invalid @enderror" id="reservation_start" name="reservation_start" required>
                <option value="" disabled selected>Select a start time</option>
            </select>
            @error('reservation_start')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="mb-3">
            <label for="reservation_end" class="form-label">Select an End Time</label>
            <select class="form-select @error('reservation_end') is-invalid @enderror" id="reservation_end" name="reservation_end" required>
                <option value="" disabled selected>Select an end time</option>
            </select>
            @error('reservation_end')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <div class="mb-3">
            <label for="notes" class="form-label">Notes (Optional)</label>
            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
            @error('notes')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    
        <button type="submit" class="btn btn-success">Submit Reservation</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
    </form>
    
    </div>

    <script>
        document.getElementById('reservation_date').addEventListener('change', function () {
            const selectedDate = this.value;
            const startTimeSelect = document.getElementById('reservation_start');
            const endTimeSelect = document.getElementById('reservation_end');
    
            // Clear existing options
            startTimeSelect.innerHTML = '<option value="" disabled selected>Select a start time</option>';
            endTimeSelect.innerHTML = '<option value="" disabled selected>Select an end time</option>';
    
            // Fetch availability rules and reservations
            const scheduleRules = @json($scheduleRules);
            const reservations = @json($reservations);
    
            const dayName = new Date(selectedDate).toLocaleDateString('en-US', { weekday: 'long' }).toLowerCase();
            const rulesForDay = scheduleRules.filter(rule => rule.day === dayName);
            const reservationsForDay = reservations.filter(reservation => reservation.date === selectedDate);
    
            // Helper function to check availability
            function isTimeSlotAvailable(start, end) {
                return !reservationsForDay.some(reservation => {
                    return (
                        (start >= reservation.time_of_day_start && start < reservation.time_of_day_end) || // Overlaps existing
                        (end > reservation.time_of_day_start && end <= reservation.time_of_day_end)
                    );
                });
            }
    
            if (rulesForDay.length === 0) {
                startTimeSelect.innerHTML = '<option value="" disabled>No time slots available</option>';
                return;
            }
    
            // Generate time slots for the selected date
            rulesForDay.forEach(rule => {
                const ruleStart = new Date(`1970-01-01T${rule.time_of_day_start}:00`);
                const ruleEnd = new Date(`1970-01-01T${rule.time_of_day_end}:00`);
    
                let currentTime = new Date(ruleStart); // Start from the rule's start time
                while (currentTime < ruleEnd) {
                    const nextTime = new Date(currentTime.getTime() + 30 * 60000); // Add 30 minutes
                    if (nextTime > ruleEnd) break;
    
                    const start = currentTime.toTimeString().slice(0, 5); // "HH:MM"
                    const end = nextTime.toTimeString().slice(0, 5); // "HH:MM"
    
                    if (isTimeSlotAvailable(start, end)) {
                        const startOption = document.createElement('option');
                        startOption.value = start;
                        startOption.textContent = start;
                        startTimeSelect.appendChild(startOption);
                    }
    
                    currentTime = nextTime;
                }
            });
    
            // Populate end time options based on selected start time
            startTimeSelect.addEventListener('change', function () {
                const selectedStart = this.value;
                const rule = rulesForDay.find(rule => {
                    return selectedStart >= rule.time_of_day_start && selectedStart < rule.time_of_day_end;
                });
    
                endTimeSelect.innerHTML = '<option value="" disabled selected>Select an end time</option>';
    
                if (rule) {
                    let currentTime = new Date(`1970-01-01T${selectedStart}:00`);
                    const ruleEnd = new Date(`1970-01-01T${rule.time_of_day_end}:00`);
    
                    while (currentTime < ruleEnd) {
                        const nextTime = new Date(currentTime.getTime() + 30 * 60000);
                        if (nextTime > ruleEnd) break;
    
                        const end = nextTime.toTimeString().slice(0, 5);
    
                        if (isTimeSlotAvailable(selectedStart, end)) {
                            const endOption = document.createElement('option');
                            endOption.value = end;
                            endOption.textContent = end;
                            endTimeSelect.appendChild(endOption);
                        }
    
                        currentTime = nextTime;
                    }
                }
            });
        });
    </script>
    
    
@endsection
