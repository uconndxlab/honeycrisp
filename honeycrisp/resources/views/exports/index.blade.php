@extends('layouts.app')

@section('content')
<div>
    <div class="container">
        <h1>Exports</h1>
        <p>List of all system exports.</p>

        {{ $exports->links() }}

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Path</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exports as $export)
                <tr>
                    <td>{{ $export->id }}</td>
                    <td>{{ $export->type }}</td>
                    <td>{{ $export->path }}</td>
                    <td>{{ $export->created_at->format('Y/m/d') }}</td>
                    <td>{{ $export->updated_at->format('Y/m/d') }}</td>
                    <td>
                        <a href="{{ route('exports.show', $export) }}" class="btn btn-primary">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $exports->links() }}
    </div>
</div>
@endsection