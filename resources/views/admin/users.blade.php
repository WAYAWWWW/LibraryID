<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen Petugas - LibraryID</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-users.css') }}">
    
    
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif
</head>
<body>
    <!-- ===== Navbar ===== -->
 <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('welcome') }}" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="LibraryID Logo">
            </a>
            <ul class="nav-menu">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
                
                <!-- Dropdown untuk Manajemen -->
                <li class="admin-navbar-dropdown">
                    <a href="#">Manajemen</a>
                    <div class="dropdown-menu">
                        <a href="{{ route('admin.users') }}">Kelola Petugas</a>
                        <a href="{{ route('admin.users.manage') }}">Kelola User</a>
                        <a href="{{ route('admin.ratings') }}">Rating User</a>
                        <a href="{{ route('admin.kelola-buku') }}">Kelola Buku</a>
                        <a href="{{ route('books.create') }}">Tambah Buku</a>
                    </div>
                </li>
                
                <!-- Dropdown untuk Laporan -->
                <li class="admin-navbar-dropdown">
                    <a href="#">Laporan</a>
                    <div class="dropdown-menu">
                        <a href="{{ route('admin.reports.loans.pdf') }}" target="_blank">Laporan PDF</a>
                        <a href="{{ route('admin.reports.loans.history') }}">Riwayat Peminjaman</a>
                    </div>
                </li>
            </ul>
                <div class="nav-right">
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <span>Logout</span>
                    </button>
                </form>
                </div>
            </div>
        </nav>

    <!-- ===== Petugas Management ===== -->
    <div class="petugas-management">
        <div class="management-header">
            <h1>Manajemen Petugas</h1>
            <p>Kelola akun petugas perpustakaan</p>
        </div>

        <div class="management-content">
            <!-- Form Tambah Petugas -->
            <div class="management-card">
                <div class="card-header">
                    <span class="icon"></span>
                    Tambah Petugas Baru
                </div>
                
                <form action="{{ route('admin.petugas.create') }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name" 
                            placeholder="Masukkan nama lengkap petugas" 
                            required
                        >
                        @error('name')
                            <span style="color: #dc2626; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email">Username</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            placeholder="Masukkan username" 
                            required
                        >
                        @error('email')
                            <span style="color: #dc2626; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Masukkan password" 
                            required
                        >
                        @error('password')
                            <span style="color: #dc2626; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit">
                         Daftarkan Petugas
                    </button>
                </form>
            </div>

            <!-- Daftar Petugas -->
            <div class="management-card">
                <div class="card-header">
                    📋 Daftar Petugas
                </div>

                @if($petugas && count($petugas) > 0)
                    <div class="petugas-list">
                        @foreach($petugas as $staff)
                            <div class="petugas-item">
                                <div class="petugas-icon">👤</div>
                                <div class="petugas-info">
                                    <div class="petugas-name">{{ $staff->name }}</div>
                                    <div class="petugas-email">{{ $staff->email }}</div>
                                </div>
                                <div class="petugas-badge">Petugas</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">📭</div>
                        <div class="empty-state-text">Belum ada petugas terdaftar</div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</body>
</html>
