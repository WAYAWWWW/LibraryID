@section('content')
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - LibraryID</title>
    
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
        <div class="container nav-container">
            <a href="#" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="LibraryID Logo">
                <span>LibraryID</span>
            </a>
            <ul class="nav-menu">

                @auth
                    <li><a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a></li>
                @else
                    <li><a href="{{ route('welcome') }}" class="btn btn-primary">Beranda</a></li>
                    @if (Route::has('register'))
                        <li><a href="{{ route('login') }}" class="btn btn-primary">Kembali</a></li>
                    @endif
                @endauth
            </ul>
        </div>
    </nav>

    <!-- ===== Auth Container ===== -->
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Daftar</h1>
                <p>Bergabunglah dengan perpustakaan digital Indonesia</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="auth-form">
                @csrf

                <!-- Nama Lengkap -->
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input 
                        type="text" 
                        class="form-control @error('name') is-invalid @enderror" 
                        id="name" 
                        name="name" 
                        value="{{ old('name') }}"
                        placeholder="Masukkan nama Anda"
                        required 
                        autofocus
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}"
                        placeholder="nama@email.com"
                        required 
                        autocomplete="email"
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <input 
                        type="password" 
                        class="form-control @error('password') is-invalid @enderror" 
                        id="password" 
                        name="password" 
                        placeholder="Minimal 8 karakter"
                        required 
                        autocomplete="new-password"
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                    <input 
                        type="password" 
                        class="form-control @error('password_confirmation') is-invalid @enderror" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        placeholder="Ulangi kata sandi Anda"
                        required 
                        autocomplete="new-password"
                    >
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    Daftar Akun
                </button>

                <!-- Login Link -->
                <div class="auth-footer">
                    <p style="margin-top: 1rem;">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
                </div>
            </form> 
        </div>
    </div>
</body>
</html>
