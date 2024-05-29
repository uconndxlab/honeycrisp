@extends('layouts.app')

@section('title', 'Ledger Meta Form')

@section('content')
    <div class="container">
        <!-- Header Section -->
        <div class="row my-3">
            <div class="col-md-12">
                <h1>Ledger Meta Form</h1>
            </div>
        </div>

        <!-- Form Section -->
        <form 
            action="{{ route('ledgers.store') }}" 
            method="POST" 
            class="ledger-meta-form"
        >
            @csrf

            <div class="row pb-3">
                <div class="col-md-6">
                    <!-- Ledger Meta Form Fields -->

                    <!-- facility select -->


                    <div class="form-group my-2">
                        <label for="facility">Facility:</label>
                        <select name="facility_id" id="facility" class="form-select">
                            <option value="">Select a Facility</option>
                            @foreach($facilities as $facility)
                                <option value="{{ $facility->id }}" @if (old('facility', isset($ledger) && $ledger->facility_id == $facility->id)) selected @endif>{{ $facility->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group my-2">
                        <label for="title">Title:</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', isset($ledger) ? $ledger->title : '') }}">
                    </div>


                    <div class="form-group my-2">
                        <label for="description">Description:</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description', isset($ledger) ? $ledger->description : '') }}</textarea>
                    </div>

                    <div class="form-group my-2">
                        <label for="date">Date:</label>
                        <input type="date" name="date" id="date" class="form-control" value="{{ old('date', isset($ledger) ? $ledger->date : '') }}">
                    </div>

                    <div class="form-group my-2">
                        <label for="status">Status:</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">Select a Status</option>
                            <option value="draft" @if (old('status', isset($ledger) && $ledger->status == 'draft')) selected @endif>Draft</option>
                            <option value="pending" @if (old('status', isset($ledger) && $ledger->status == 'pending')) selected @endif>Pending</option>
                            <option value="approved" @if (old('status', isset($ledger) && $ledger->status == 'approved')) selected @endif>Approved</option>
                            <option value="in_progress" @if (old('status', isset($ledger) && $ledger->status == 'in_progress')) selected @endif>In Progress</option>
                            <option value="complete" @if (old('status', isset($ledger) && $ledger->status == 'complete')) selected @endif>Complete</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="row">
                <div class="col-md-6">
                    <button type="reset" id="clear-ledger" class="btn btn-danger">Clear Ledger</button>
                    @if (isset($ledger))
                        <button type="submit" id="update-ledger" class="btn btn-primary">Update Ledger Meta <i class="bi bi-check"></i></button>
                    @else
                        <button type="submit" id="save-ledger" class="btn btn-primary">Add Orders <i class="bi bi-arrow-right"></i></button>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection
