@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div>
        <div class="container">

            <div class="row my-3">
                <div class="col-12">
                    <h1>Users</h1>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">Add User</a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <!-- search form -->
                    <form hx-get="{{ route('users.index') }}" hx-trigger="keyup changed delay:500ms"
                        action="{{ route('users.index') }}" method="GET" hx-select="#user-table" hx-swap="innerHTML"
                        hx-target="#user-table" autocomplete="off">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="search"
                                placeholder="Search by name, netid, or email" value="{{ request('search') }}"
                                hx-trigger="keyup,changed delay:500ms" hx-get="{{ route('users.index') }}"
                                hx-target="#user-table" hx-swap="innerHTML">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" type="submit">Search</button>
                            </div>
                        </div>
                    </form>


                </div>
            </div>

            <div class="row">
                <div class="col-12 d-flex justify-content-end">

                    {{ $users->links() }}

                </div>
                <!-- showing users x-y of z -->
                <div class="col-12">
                    <p>Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users</p>
                </div>
            </div>

            <table id="user-table" class="table table-light table-hover table-bordered align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>NetID</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->netid }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td>

                                <!-- start an order with this user, same as the dropdown in the orders index -->
                                <div class="dropdown d-inline">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Start an Order
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @foreach ($facilities as $facility)
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('orders.create') }}/{{ $facility->abbreviation }}?user_id={{ $user->id }}">{{ $facility->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                {{-- Make a Reservation --}}
                                {{-- list the facilities with reservable products --}}

                                <div class = "dropdown d-inline">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        Make a Reservation
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @foreach ($facilities as $facility)
                                            @if ($facility->products->where('can_reserve', true)->count() > 0)
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('reservations.create', ['facilityAbbreviation' => $facility->abbreviation, 'user_id' => $user->id]) }}">{{ $facility->name }}</a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>

                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-secondary">Edit</a>
                                {{-- <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


        </div>
    </div>

@endsection
