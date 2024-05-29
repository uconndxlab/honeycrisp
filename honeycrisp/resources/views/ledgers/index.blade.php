@extends('layouts/app')
@section('title', 'All Ledgers')

@section('content')

    <div class="container">
        <div class="row my-3">
            <div class="col-md-12">
                <h1>Ledgers</h1>
                <!-- filter UI and a spot for an admin menu like "Add Facility" go here -->

                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <input type="text" id="filter" name="filter" class="form-control"
                            placeholder="Search for a Ledger">
                    </div>
                    <div>
                        <a href={{ route('ledgers.create') }}  class="btn btn-primary">Start a Ledger</a>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ledger ID</th>
                            <th>Facility</th>
                            <th>Ledger Date</th>
                            <th>Ledger Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ledgers as $ledger)
                            <tr>
                                <td>{{ $ledger->id }}</td>
                                
                                <td>{{ $ledger->order_date }}</td>
                                <td>{{ $ledger->order_status }}</td>
                                <td>
                                    <a href="{{ route('ledgers.show', $ledger->id) }}" class="btn btn-primary">View</a>
                                    <a href="{{ route('ledgers.edit', $ledger->id) }}" class="btn btn-secondary">Edit</a>
                                    <form action="{{ route('ledgers.destroy', $ledger->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection