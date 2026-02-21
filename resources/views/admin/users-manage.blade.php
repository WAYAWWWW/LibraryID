<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Manajemen User - LibraryID</title>

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
            content: ' ?';
            font-size: 0.7em;
        }

        .users-management {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
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

        .users-table-wrapper {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            margin-top: 1.5rem;
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

        .status-badge {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-active {
            background: #dcfce7;
            color: #15803d;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .btn-action {
            padding: 0.5rem 0.75rem;
            border: none;
            border-radius: 0.375rem;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-edit {
            background: #dbeafe;
            color: #1e40af;
        }

        .btn-toggle {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-delete {
            background: #fecaca;
            color: #991b1b;
        }

        .alert-status {
            margin-top: 1rem;
            padding: 0.75rem 1rem;
            background: #ecfeff;
            border: 1px solid #a5f3fc;
            color: #155e75;
            border-radius: 0.5rem;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
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

    <div class="users-management">
        <div class="management-header">
            <h1>Manajemen User</h1>
            <p>Edit, hapus, atau nonaktifkan akun user.</p>
        </div>

        @if(session('status'))
            <div class="alert-status">{{ session('status') }}</div>
        @endif

        <div class="users-table-wrapper">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Gmail</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($users && $users->count() > 0)
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="status-badge {{ $user->is_active ? 'status-active' : 'status-inactive' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a class="btn-action btn-edit" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                                        <form method="POST" action="{{ route('admin.users.status', $user) }}" style="display:inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn-action btn-toggle" onclick="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }} akun ini?')">
                                                {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.delete', $user) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action btn-delete" onclick="return confirm('Yakin hapus user ini?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem; color: var(--gray-400);">
                                Tidak ada user terdaftar
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if($users && $users->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</body>
</html>
