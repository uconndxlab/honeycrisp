@extends ('layouts.app')
@section('title', 'Create Category')

@section('content')

<div class="container py-2">
    <div class="row my-3">
        <div class="col-md-12">
            <h1>Create Category</h1>
        </div>
        <!-- Display any form errors here -->
        @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <div class="row">
        <div class="col-md-2 subnav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link">Category Information</a>
                </li>
            </ul>
        </div>
        <div class="col-md-6">
            <div class="facility-information">
                <h3 class="mb-3">Category Info</h3>

                <!-- Form for creating a new category -->
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf

                    <!-- Category Name -->
                    <div class="form-group">
                        <label for="name">Category Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                    </div>

                    <div class="form-group my-2">
                    <!-- Facility Select -->
                    <label for="facility_id">Facility</label>
                    <select name="facility_id" id="facility_id" class="form-select" required>
                        @foreach($facilities as $facility)
                        <option value="{{ $facility->id }}" @if(count($facilities) == 1) selected readonly @endif>{{ $facility->name }}</option>
                        @endforeach
                    </select>
                    </div>

                    <!-- Description -->
                    <div class="form-group" style="margin-top: 1rem;">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" class="form-control" rows="5" required>{{ old('description') }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3">Create Category</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection