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

        <form action="{{ route('reservations.store') }}" method="POST">
            @csrf

            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="mb-3">
                <label for="reservation_start" class="form-label">Reservation Start</label>
                <input type="datetime-local" class="form-control" id="reservation_start" name="reservation_start" required>
            </div>

            <div class="mb-3">
                <label for="reservation_end" class="form-label">Reservation End</label>
                <input type="datetime-local" class="form-control" id="reservation_end" name="reservation_end" required>
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Notes (Optional)</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Submit Reservation</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
