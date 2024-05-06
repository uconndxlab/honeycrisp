<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://unpkg.com/htmx.org@1.9.12" integrity="sha384-ujb1lZYygJmzgSwoxRggbCHcjc0rB2XoQrxeTUQyRjrOnlCoYta87iKBWq3EsdM2" crossorigin="anonymous"></script>
    <!-- bootstrap5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <!-- bootstrap5 Icons CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>@yield('title') - Honeycrisp</title>
    <!-- Your CSS and JS imports go here -->
</head>

<body>
    <header>
        <!-- secondary top navigation above the main navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-secondary">
            <div class="container">
                <span class="navbar-text text-white text-mono login-hud">Logged in as: jrs06005
                    <span class="badge bg-success">Admin</span>
                </span>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav" style="margin-left: auto; display: flex; justify-content: end; width: 100%;">
                        <li>
                            <div class="dropdown">
                                <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                    Manage
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <!-- Replace the href="#" with appropriate routes like orders/create/{facility->abbreviation} -->
                                    @foreach($facilities as $facility)
                                    <li>
                                        <a class="dropdown-item" href=" {{ route('orders.create') }}/{{ $facility->abbreviation }}
                                ">{{ $facility->name }}</a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#">My Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#">Settings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white" href="#">Logout</a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>

        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <span class="hc-logo"></span>
                    Honeycrisp</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('facilities.index') }}">Facilities</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('payment-accounts.index') }}">Payment Accounts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}">Users</a>
                        </li>
                    </ul>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Start an Order
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <!-- Replace the href="#" with appropriate routes like orders/create/{facility->abbreviation} -->
                            @foreach($facilities as $facility)
                            <li><a class="dropdown-item" href=" {{ route('orders.create') }}/{{ $facility->abbreviation }}
                                ">{{ $facility->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

    </header>

    <main>
        <div class="container py-2">
            <div class="row">
                <div class="col-md-12">
                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if(session('alert'))
                    <div class="alert alert-warning">
                        {{ session('alert') }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
        @yield('content')
    </main>

    <footer>
        <!-- Your footer content goes here -->
    </footer>
    <!-- bootstrap5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>