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

            <div class="mb-3">
                <label for="reservation_start" class="form-label">Reservation Start</label>
                <input type="datetime-local" class="form-control @error('reservation_start') is-invalid @enderror" id="reservation_start" name="reservation_start" value="{{ old('reservation_start') }}" required>
                @error('reservation_start')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="reservation_end" class="form-label">Reservation End</label>
                <input type="datetime-local" class="form-control @error('reservation_end') is-invalid @enderror" id="reservation_end" name="reservation_end" value="{{ old('reservation_end') }}" required>
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

            <script>
                document.getElementById('reservation_start').addEventListener('change', function() {
                    let startTime = new Date(this.value);
                    let endTime = new Date(startTime.getTime() + 30 * 60000); // Add 30 minutes
                    document.getElementById('reservation_end').value = endTime.toISOString().slice(0, 16);
                });
            </script>

            <button type="submit" class="btn btn-success">Submit Reservation</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
