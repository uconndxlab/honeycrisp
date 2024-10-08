@extends('layouts.app')

@section('content')
<div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3>Create Schedule Rule</h3>
                <form action="{{ route('schedule-rules.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div>
                        <p>{{ $product->name }}</p>
                    </div>

                    <div class="form-group">
                        <label for="day_of_week">Day(s) of the Week</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="day_of_week[]" id="monday" value="monday">
                            <label class="form-check-label" for="monday">Monday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="day_of_week[]" id="tuesday" value="tuesday">
                            <label class="form-check-label" for="tuesday">Tuesday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="day_of_week[]" id="wednesday" value="wednesday">
                            <label class="form-check-label" for="wednesday">Wednesday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="day_of_week[]" id="thursday" value="thursday">
                            <label class="form-check-label" for="thursday">Thursday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="day_of_week[]" id="friday" value="friday">
                            <label class="form-check-label" for="friday">Friday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="day_of_week[]" id="saturday" value="saturday">
                            <label class="form-check-label" for="saturday">Saturday</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="day_of_week[]" id="sunday" value="sunday">
                            <label class="form-check-label" for="sunday">Sunday</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="start_minutes">Start Minutes</label>
                        <input type="number" name="start_minutes" id="start_minutes" class="form-control" min="0" max="1439">
                    </div>

                    <div class="form-group">
                        <label for="end_minutes">End Minutes</label>
                        <input type="number" name="end_minutes" id="end_minutes" class="form-control" min="0" max="1439">
                    </div>

                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection