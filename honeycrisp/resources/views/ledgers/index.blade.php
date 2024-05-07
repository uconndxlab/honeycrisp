@extends('layouts/app')
@section('title', 'All Ledgers')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col">
                <h1>All Ledgers</h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ledger ID</th>
                            <th>Facility</th>
                            <th>Order Date</th>
                            <th>Order Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ledgers as $ledger)
                            <tr>
                                <td>{{ $ledger->id }}</td>
                                <td>{{ $ledger->facility->name }}</td>
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
        <div class="row">
            <div class="col">
                <a href="{{ route('ledgers.create') }}" class="btn btn-primary">Add Ledger</a>
            </div>
        </div>
    </div>
</div>