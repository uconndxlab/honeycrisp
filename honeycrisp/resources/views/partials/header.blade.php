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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-info">
                {{ session('status') }}
            </div>
        @endif

        @error('name')
            <div class="alert alert-danger">{{ $message }}</div>
        @enderror

    </div>

</header>
