@extends('layouts.app')
@section('title', 'All Reservations')

{{-- grid of all --}}
@section('content')
    <div class="container">
        <div class="row my-3">
            <div class="col-md-12">
                <h1>Reservations</h1>
            </div>
        </div>

        <div class="row">

            <div class="row">
                <div class="col-md-12">
                    @if ($reservations->isEmpty())
                        <p>No reservations found.</p>
                    @else
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Facility</th>
                                    <th>Product</th>
                                    <th>Reservation Start</th>
                                    <th>Reservation End</th>
                                    <th>Status</th>
                                    <th>Account Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reservations as $reservation)
                                    <tr>
                                        <td>{{ $reservation->product->facility->name }}</td>
                                        <td>{{ $reservation->product->name }}</td>
                                        <td>{{ $reservation->reservation_start }}</td>
                                        <td>{{ $reservation->reservation_end }}</td>
                                        <td>{{ $reservation->status }}</td>
                                        <td>{{ $reservation->account_type }}</td>
                                        <td>
                                            <a href="#" class="btn btn-primary">View</a>
                                            <a href="#" class="btn btn-primary">Edit</a>
                                            <form action="{{ route('reservations.destroy', $reservation->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                            `
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    @endsection
