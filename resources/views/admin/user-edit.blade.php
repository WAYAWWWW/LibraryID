<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit User - LibraryID</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

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

        .edit-user {
            max-width: 700px;
            margin: 0 auto;
            padding: 2rem;
        }

        .card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .card h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .card p {
            color: var(--gray-500);
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
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

        .form-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn-save {
            padding: 0.875rem 1.25rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel {
            padding: 0.875rem 1.25rem;
            background: #e2e8f0;
            color: #1e293b;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            text-decoration: none;
        }
    </style>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('welcome') }}" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="LibraryID Logo">
            </a>
            <ul class="nav-menu">
                <li><a href="{{ route('admin.dashboard') }}">Dashboard Admin</a></li>
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

    <div class="edit-user">
        <div class="card">
            <h1>Edit User</h1>
            <p>Perbarui informasi akun user.</p>

            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <span style="color: #dc2626; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Username</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <span style="color: #dc2626; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password Baru (opsional)</label>
                    <input type="password" id="password" name="password" placeholder="Kosongkan jika tidak diubah">
                    @error('password')
                        <span style="color: #dc2626; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save">Simpan Perubahan</button>
                    <a href="{{ route('admin.users.manage') }}" class="btn-cancel">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
