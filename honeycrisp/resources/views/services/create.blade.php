@extends('layouts.app')

@section('title', isset($facility) ? 'Edit Facility' : 'Create Facility')

@section('content')
    <div class="container container-create">
        <div class="row pt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{ isset($facility) ? 'Edit Facility' : 'Create Facility' }}</div>
                    <div class="card-body">
                        <!-- Form for creating/editing a facility -->
                        <!-- create.blade.php -->
                        <form action="{{ route('services.store') }}" method="POST">
                            @csrf
                            <div>
                                <label for="name">Service Name:</label>
                                <input type="text" name="name" id="name" required>
                            </div>
                            <div>
                                <label for="description">Description:</label>
                                <textarea name="description" id="description" required></textarea>
                            </div>
                            <!-- uom = unit of measure -->
                            <div>
                                <label for="uom">Unit of Measure:</label>
                                <input type="text" name="uom" id="uom" required>
                            </div>

                            <!-- internal_price and external_price -->

                            <div>
                                <label for="internal_price">Internal Price:</label>
                                <input type="number" name="internal_price" id="internal_price" required>
                            </div>

                            <div>
                                <label for="external_price">External Price:</label>
                                <input type="number" name="external_price" id="external_price" required>

                            </div>


                            <div>
                                <label for="facility">Select Facility:</label>
                                <select name="facility_id" id="facility" required>
                                    @foreach ($facilities as $facility)
                                        <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit">Create Service</button>
                        </form>

                    </div>
                </div>
            </div>

            <!-- danger zone containing delete button -->
            @if (isset($service))
                <div class="col-md-12 mt-4">
                    <div class="card">
                        <div class="card-header">Danger Zone</div>
                        <div class="card-body">
                            <form action="{{ route('services.destroy', $facility->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete Service</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
