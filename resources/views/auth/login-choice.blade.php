@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - LibraryID</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/logc.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>
    <!-- ===== Navbar ===== -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="/" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="LibraryID Logo">
            </a>
            <ul class="nav-menu">
                <li><a href="/">Beranda</a></li>
                <li><a href="{{ route('register') }}">Daftar</a></li>
            </ul>
        </div>
    </nav>

    <!-- ===== Login Container ===== -->
    <div class="login-wrapper">
        <!-- Left Side -->
        <div class="login-left">
            <div class="login-heading">Selamat datang! Masuk ke akun Anda</div>

            <div class="login-card">
                <!-- Display Error Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="email">Username/Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <button type="submit" class="login-button">LOGIN</button>
                    <div class="login-footer">
                        Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right Side (Image Placeholder) -->
        <div class="login-right">
            <img src="{{ asset('images/perpustakaan.jpg') }}" alt="gambar">
        </div>
    </div>
</body>
</html>

