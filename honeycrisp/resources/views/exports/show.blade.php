@extends('layouts.app')

@section('content')
<div>
    <div class="container">
        <h1>Export</h1>

        <div class="card">
            <div class="card-header">Export Information</div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $export->id }}</p>
                <p><strong>Type:</strong> {{ $export->type }}</p>
                <p><strong>Path:</strong> {{ $export->path }}</p>
                <p><strong>Created At:</strong> {{ $export->created_at->format('Y/m/d') }}</p>
                <p><strong>Updated At:</strong> {{ $export->updated_at->format('Y/m/d') }}</p>

                <a href="{{ route('exports.download', $export) }}" class="btn btn-primary">Download</a>
            </div>
        </div>

        <pre class="my-5">{{ file_get_contents($export->path) }}</pre>
    </div>
</div>
@endsection