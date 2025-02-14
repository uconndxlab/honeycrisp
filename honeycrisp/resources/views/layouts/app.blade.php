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

    @livewireStyles

</head>

<body>
    <header class="no-print">
        <!-- secondary top navigation above the main navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-secondary">
            <div class="container">
                @if ( Auth::check() )
                    <span class="navbar-text text-white text-mono login-hud">Logged in as: {{ Auth::user()->netid ?? Auth::user()->name }}
                        <span class="badge bg-success">{{ Str::headline(Auth::user()->role ?? 'User')}}</span>
                    </span>
                @else
                    <span class="navbar-text text-white text-mono login-hud">Not currently logged in.</span>
                @endif
                
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav" style="margin-left: auto; display: flex; justify-content: end; width: 100%;">
                        @if ( Auth::check() ) 

                            <li class="nav-item">
                                <a class="nav-link text-white" href="{{ route('logout') }}">Logout</a>
                            </li>
                        @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="nav-link text-white">Login</a>
                        </li>
                        @endif
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
                <div class="collapse navbar-collapse">
                    <ul class="navbar-nav me-auto">
                        @can('admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders.index') }}">Orders</a>
                        </li>
                        @endcan

                        @can('admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reservations.index') }}">Reservations</a>
                        </li>
                        @endcan

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('facilities.index') }}">Facilities</a>
                        </li>
                        @can('admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('payment-accounts.index') }}">Payment Accounts</a>
                        </li>
                        @endcan

                        @can('admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}">Users</a>
                        </li>
                        @endcan
                    </ul>
                    {{-- <div class="dropdown">
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
                    </div> --}}
                    <a href="{{route('users.index')}}" class="btn btn-primary">Start an Order</a>
                </div>
            </div>
        </nav>

    </header>

    <main class="py-4">
        <div class="container py-2">
            <div class="row">
                <div class="col-md-12">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissable">
                        {{ session('success') }}
                        
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger dismissable">
                        {{ session('error') }}
                    </div>
                    @endif

                    @if(session('alert'))
                    <div class="alert alert-warning dismissable">
                        {{ session('alert') }}
                    </div>
                    @endif

                </div>
            </div>
        </div>
        @yield('content')
    </main>
    <footer id="uc-footer" class="site-footer" role="contentinfo">
        <div class="container">
            <ul id="uc-footer-links" class="clearfix text-center">
                                        <li>
                        © <a href="http://uconn.edu">University of Connecticut</a>
                    </li>
                    <li>
                        <a href="http://uconn.edu/disclaimers-privacy-copyright/">Disclaimers, Privacy &amp; Copyright</a>
                    </li>
                    <li>
                        <a href="https://accessibility.uconn.edu/">Accessibility</a>
                    </li>


                <li><a href="https://core.uconn.edu" target="_blank">
                    Contact Information
                </a></li>

                                </ul>
        </div>
    </footer>
    @livewireScripts

    <!-- bootstrap5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>