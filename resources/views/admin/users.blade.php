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
    
    <style>
        .admin-navbar-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .dropdown-menu {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 4px;
            top: 100%;
            left: 0;
        }
        
        .dropdown-menu a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.2s;
        }
        
        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }
        
        .admin-navbar-dropdown:hover .dropdown-menu {
            display: block;
        }
        
        .admin-navbar-dropdown > a::after {
            content: ' ▼';
            font-size: 0.7em;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-control:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }

        .action-buttons form {
            display: inline;
            margin: 0;
        }

        .action-buttons form button {
            background: none;
            border: none;
            padding: 6px 10px;
            cursor: pointer;
            font-size: 16px;
            transition: transform 0.2s ease;
        }

        .action-buttons form button:hover {
            transform: scale(1.2);
        }

        .action-buttons form button.approve {
            color: #28a745;
        }

        .action-buttons form button.reject {
            color: #dc3545;
        }
        .petugas-management {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .management-header {
            margin-bottom: 2.5rem;
        }

        .management-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .management-header p {
            color: var(--gray-500);
            font-size: 0.95rem;
        }

        .management-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .management-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
        }

        .card-header .icon {
            font-size: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .form-group input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid var(--gray-200);
            border-radius: 0.5rem;
            font-family: 'Poppins', sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(16, 185, 129, 0.3);
        }

        .petugas-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .petugas-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #f3f4f6;
            border-radius: 0.75rem;
            border-left: 4px solid var(--primary);
        }

        .petugas-icon {
            font-size: 1.5rem;
            min-width: 2.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .petugas-info {
            flex: 1;
        }

        .petugas-name {
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.25rem;
        }

        .petugas-email {
            font-size: 0.85rem;
            color: var(--gray-500);
        }

        .petugas-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            background: #dcfce7;
            color: #15803d;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: var(--gray-400);
        }

        .empty-state-text {
            font-size: 0.95rem;
        }

        .users-section {
            margin-top: 3rem;
        }

        .users-header {
            margin-bottom: 1.5rem;
        }

        .users-header h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .users-table-wrapper {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .users-table {
            width: 100%;
            border-collapse: collapse;
        }

        .users-table thead {
            background: var(--gray-50);
            border-bottom: 2px solid var(--gray-200);
        }

        .users-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.95rem;
        }

        .users-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--gray-200);
            color: var(--gray-700);
        }

        .users-table tbody tr:hover {
            background: var(--gray-50);
        }

        .role-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .role-admin {
            background: #fee2e2;
            color: #991b1b;
        }

        .role-petugas {
            background: #dcfce7;
            color: #15803d;
        }

        .role-user {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-delete {
            padding: 0.5rem 0.75rem;
            background: #fecaca;
            color: #991b1b;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-delete:hover {
            background: #fca5a5;
        }

        @media (max-width: 768px) {
            .management-content {
                grid-template-columns: 1fr;
            }

            .management-header h1 {
                font-size: 1.5rem;
            }

            .users-table {
                font-size: 0.9rem;
            }

            .users-table th,
            .users-table td {
                padding: 0.75rem 0.5rem;
            }
        }
    </style>
    
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
