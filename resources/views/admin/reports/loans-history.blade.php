<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Peminjaman - Admin LibraryID</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashpet.css') }}">
    <link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif
    
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

        .filter-section {
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid #e5e7eb;
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: flex-end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #374151;
            font-size: 0.9rem;
        }

        .form-group select,
        .form-group input {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            font-family: inherit;
        }

        .form-group select:focus,
        .form-group input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
        }

        .filter-button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .filter-button:hover {
            background: #0056b3;
        }

        .reset-button {
            padding: 10px 20px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.2s;
        }

        .reset-button:hover {
            background: #5a6268;
        }

        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #cfe2ff;
            color: #084298;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-returning {
            background: #fff3cd;
            color: #856404;
        }

        .status-returned {
            background: #d4edda;
            color: #155724;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
        }

        .pagination-container a,
        .pagination-container span {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            text-decoration: none;
            color: #007bff;
            transition: background 0.2s;
        }

        .pagination-container a:hover {
            background: #f0f0f0;
        }

        .pagination-container .active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .pagination-container .disabled {
            color: #ccc;
            cursor: not-allowed;
        }
    </style>
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
                <li>
                   <div class="nav-right"><button type="submit" class="btn-logout">
                        <span>Logout</span>
                    </button></div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- ===== Dashboard Container ===== -->
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2>Riwayat Peminjaman</h2>
            <p>Lihat semua riwayat peminjaman buku dari user</p>
        </div>
        <div class="dashboard-content">
            <!-- Filter Section -->
            <div class="filter-section">
                <h3 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.1rem; font-weight: 600;">Filter Data</h3>
                <form method="GET" action="{{ route('admin.reports.loans.history') }}">
                    <div class="filter-row">
                        <div class="form-group">
                            <label for="user_id">Pilih User</label>
                            <select name="user_id" id="user_id">
                                <option value="">-- Semua User --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status">Status Peminjaman</label>
                            <select name="status" id="status">
                                <option value="">-- Semua Status --</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Sedang Dipinjam</option>
                                <option value="returning" {{ request('status') == 'returning' ? 'selected' : '' }}>Dalam Pengembalian</option>
                                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>

                        <div style="display: flex; gap: 0.5rem;">
                            <button type="submit" class="filter-button">🔍 Filter</button>
                            <a href="{{ route('admin.reports.loans.history') }}" class="reset-button">Reset</a>
                            <a href="{{ route('admin.reports.loans.excel', request()->query()) }}" class="filter-button" style="background:#28a745;">Export Excel</a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabel Riwayat Peminjaman -->
            <div class="table-container">
                <h3 class="table-title">
                    📋 Riwayat Peminjaman 
                    @if(request('user_id'))
                        <span style="font-size: 0.8rem; font-weight: normal; color: #666;">
                            - {{ \App\Models\User::find(request('user_id'))?->name }}
                        </span>
                    @endif
                </h3>

                @if($loans->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Peminjam</th>
                                <th>Buku</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Tanggal Peminjaman</th>
                                <th>Jatuh Tempo</th>
                                <th>Tanggal Kembali</th>
                                <th>Denda</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($loans as $loan)
                            <tr>
                                <td>
                                    <strong>{{ $loan->user->name ?? 'N/A' }}</strong>
                                    <br><small style="color: #999;">{{ $loan->user->email ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $loan->book->title ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($loan->created_at)->format('d/m/Y H:i') }}</td>
                                <td>{{ $loan->borrow_date ? \Carbon\Carbon::parse($loan->borrow_date)->format('d/m/Y') : '-' }}</td>
                                <td>{{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if($loan->return_date)
                                        {{ \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') }}
                                    @else
                                        <span style="color: #999;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if(($loan->fine ?? 0) > 0)
                                        <strong style="color: #dc3545;">Rp {{ number_format($loan->fine, 0, ',', '.') }}</strong>
                                    @else
                                        <span style="color: #28a745;">Rp 0</span>
                                    @endif
                                </td>
                                <td>
                                    @switch($loan->status)
                                        @case('pending')
                                            <span class="status-badge status-pending">⏳ Menunggu</span>
                                            @break
                                        @case('approved')
                                            <span class="status-badge status-approved">✓ Disetujui</span>
                                            @break
                                        @case('active')
                                            <span class="status-badge status-active">📖 Dipinjam</span>
                                            @break
                                        @case('returning')
                                            <span class="status-badge status-returning">↩️ Pengembalian</span>
                                            @break
                                        @case('returned')
                                            <span class="status-badge status-returned">✓ Dikembalikan</span>
                                            @break
                                        @case('rejected')
                                            <span class="status-badge status-rejected">✕ Ditolak</span>
                                            @break
                                        @default
                                            <span class="status-badge">{{ ucfirst($loan->status) }}</span>
                                    @endswitch
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="pagination-container">
                        @if ($loans->onFirstPage())
                            <span class="disabled">← Sebelumnya</span>
                        @else
                            <a href="{{ $loans->previousPageUrl() }}">← Sebelumnya</a>
                        @endif

                        @foreach ($loans->getUrlRange(1, $loans->lastPage()) as $page => $url)
                            @if ($page == $loans->currentPage())
                                <span class="active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if ($loans->hasMorePages())
                            <a href="{{ $loans->nextPageUrl() }}">Selanjutnya →</a>
                        @else
                            <span class="disabled">Selanjutnya →</span>
                        @endif
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-text">Tidak ada data riwayat peminjaman</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Auto-refresh ringan untuk update data setiap 30 detik
        setInterval(function(){
            if(!document.hidden){ location.reload(); }
        }, 30000);
    </script>
</body>
</html>
