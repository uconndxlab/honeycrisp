<header>
    <div class="container">
        <h1>Cider2</h1>
        <nav>
            <ul>
                <li><a href="/">Facilities</a></li>
            </ul>
        </nav>

        <!-- display the various messages -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-info">
                {{ session('status') }}
            </div>
        @endif
    </div>

</header>
