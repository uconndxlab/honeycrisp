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
                        <form action="{{ isset($facility) ? route('facilities.update', $facility->id) : route('facilities.store') }}" method="POST">
                            @csrf
                            @if(isset($facility))
                                @method('PUT')
                            @endif

                            <!-- Facility Name -->
                            <div class="form-group my-2">
                                <label for="name">Facility Name</label>
                                <input type="text" name="name" id="name" class="form-control" value="{{ isset($facility) ? $facility->name : old('name') }}" required>
                            </div>

                            <!-- Description -->
                            <div class="form-group my-2">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control" rows="5" required>{{ isset($facility) ? $facility->description : old('description') }}</textarea>
                            </div>

                            <!-- Abbreviation -->
                            <div class="form-group my-2">
                                <label for="abbreviation">Abbreviation</label>
                                <input type="text" name="abbreviation" id="abbreviation" class="form-control" value="{{ isset($facility) ? $facility->abbreviation : old('abbreviation') }}" required>
                            </div>

                            <!-- Status -->
                            <div class="form-group my-2">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control" required>
                                    <option value="active" {{ (isset($facility) && $facility->status == 'active') || old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ (isset($facility) && $facility->status == 'inactive') || old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group my-4">
                                <button type="submit" class="btn btn-primary">{{ isset($facility) ? 'Update Facility' : 'Create Facility' }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
