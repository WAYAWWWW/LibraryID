<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - LibraryID</title>
    
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

    <!-- ===== Dashboard Container ===== -->
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h2>Admin Dashboard</h2>
            <p>Kelola sistem perpustakaan digital dengan fitur petugas</p>
        </div>
        <div class="dashboard-content">
            <!-- Stats Cards -->
            <div class="stats-cards">
                <div class="stat-card stat-card-green">
                    <div class="stat-card-content">
                        <div class="stat-label">Total Stok Buku</div>
                        <div class="stat-number">{{ \App\Models\Book::count() }}</div>
                        <div class="stat-description">{{ \App\Models\Book::count() }} judul buku</div>
                    </div>
                    <div class="stat-icon"><img src="{{ asset('images/Lbuku.png') }}" alt="logo buku"></div>
                </div>

                <div class="stat-card stat-card-blue">
                    <div class="stat-card-content">
                        <div class="stat-label">Total User</div>
                        <div class="stat-number">{{ $users->total() ?? 0 }}</div>
                        <div class="stat-description">User terbaru</div>
                    </div>
                    <div class="stat-icon"><img src="{{ asset('images/Luser.png') }}" alt="logo user"></div>
                </div>

                <div class="stat-card stat-card-orange">
                    <div class="stat-card-content">
                        <div class="stat-label">Peminjaman Aktif</div>
                        <div class="stat-number">{{ \App\Models\Loan::where('status', 'approved')->count() }}</div>
                        <div class="stat-description">Sedang dipinjam</div>
                    </div>
                    <div class="stat-icon"><img src="{{ asset('images/Lcatat.png') }}" alt="logo catat"></div>
                </div>

                <div class="stat-card stat-card-yellow">
                    <div class="stat-card-content">
                        <div class="stat-label">Menunggu Konfirmasi</div>
                        <div class="stat-number">{{ \App\Models\Loan::where('status', 'pending')->count() }}</div>
                        <div class="stat-description">Perlu ditindak lanjuti</div>
                    </div>
                    <div class="stat-icon">⚠️</div>
                </div>

                <div class="stat-card stat-card-purple" style="background: linear-gradient(135deg, #9c27b0 0%, #7b1fa2 100%); color: white;">
                    <div class="stat-card-content">
                        <div class="stat-label">Proses Pengembalian</div>
                        <div class="stat-number">{{ \App\Models\Loan::where('status', 'returning')->count() }}</div>
                        <div class="stat-description">Menunggu konfirmasi kode</div>
                    </div>
                    <div class="stat-icon">🔐</div>
                </div>
            </div>

            <!-- Tab Menu untuk Peminjaman (Fitur Petugas) -->
            <div class="loans-tabs-container" style="margin-top: 3rem;">
                <div class="tabs-menu">
                    <button class="tab-button active" data-tab="pending">Perlu Disetujui</button>
                    <button class="tab-button" data-tab="ready-pickup">Siap Diambil</button>
                    <button class="tab-button" data-tab="active">Sedang Dipinjam</button>
                    <button class="tab-button" data-tab="returned">Sudah Dikembalikan</button>
                </div>

                <!-- Perlu Disetujui -->
                <div id="pending" class="tab-content active">
                    <div class="table-container">
                        <h3 class="table-title">Peminjaman yang Menunggu Persetujuan</h3>
                        @php
                            $allLoans = \App\Models\Loan::all();
                        @endphp
                        @if($allLoans->where('status', 'pending')->count() > 0)
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Peminjam</th>
                                        <th>Buku</th>
                                        <th>Tanggal Diajukan</th>
                                        <th>Durasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allLoans->where('status', 'pending') as $loan)
                                    <tr>
                                        <td><strong>{{ $loan->user->name ?? 'N/A' }}</strong></td>
                                        <td>{{ $loan->book->title ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($loan->created_at)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($loan->borrow_date)->diffInDays(\Carbon\Carbon::parse($loan->due_date)) }} hari</td>
                                        <td>
                                            <div class="action-buttons">
                                                <form method="POST" action="{{ route('admin.loans.approve', $loan->id) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="action-btn approve" title="Setujui">✓</button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.loans.reject', $loan->id) }}" style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="action-btn reject" title="Tolak">✕</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-text">Tidak ada peminjaman yang menunggu persetujuan</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Buku Siap Diambil -->
                <div id="ready-pickup" class="tab-content">
                    <div class="table-container">
                        <h3 class="table-title">Verifikasi Kode Pengambilan Buku</h3>
                        <p style="color:#666; margin-bottom:1.5rem; font-size:14px;">
                            Minta user menunjukkan <strong>kode pengambilan</strong> dari halaman mereka, lalu masukkan kode di bawah untuk melihat detail dan mengonfirmasi pengambilan.
                        </p>

                        <form method="GET" action="{{ route('admin.dashboard') }}" style="margin-bottom:1.5rem;">
                            <div style="display:grid; grid-template-columns:1fr auto; gap:1rem;">
                                <div>
                                    <label style="display:block; margin-bottom:0.5rem; font-weight:600; color:#333;">
                                        <i class="fas fa-key"></i> Kode Pengambilan Buku
                                    </label>
                                    <input type="text" name="pickup_code" value="{{ request('pickup_code') }}" class="form-control" placeholder="Masukkan kode pengambilan dari user..." required>
                                </div>
                                <div style="display:flex; align-items:flex-end;">
                                    <button type="submit" style="padding:12px 24px; background:#007bff; color:white; border:none; border-radius:4px; font-weight:600; cursor:pointer; font-size:14px;">
                                        Cari Peminjaman
                                    </button>
                                </div>
                            </div>
                        </form>

                        @php
                            $pickupLoan = null;
                            if(request('pickup_code')){
                                $pickupLoan = $allLoans->where('status','approved')->firstWhere('barcode', request('pickup_code'));
                            }
                        @endphp

                        @if(request('pickup_code'))
                            @if($pickupLoan)
                                <div style="margin-top:1rem; padding:1.5rem; border-radius:8px; background:#f8fafc; border:1px solid #e2e8f0;">
                                    <h4 style="margin-top:0; margin-bottom:1rem;">Detail Peminjaman</h4>
                                    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; margin-bottom:1.5rem;">
                                        <div>
                                            <div style="font-size:12px; text-transform:uppercase; color:#64748b; font-weight:600;">Nama Peminjam</div>
                                            <div style="font-weight:600;">{{ $pickupLoan->user->name ?? 'N/A' }}</div>
                                        </div>
                                        <div>
                                            <div style="font-size:12px; text-transform:uppercase; color:#64748b; font-weight:600;">Judul Buku</div>
                                            <div style="font-weight:600;">{{ $pickupLoan->book->title ?? 'N/A' }}</div>
                                        </div>
                                        <div>
                                            <div style="font-size:12px; text-transform:uppercase; color:#64748b; font-weight:600;">Tanggal Disetujui</div>
                                            <div>{{ \Carbon\Carbon::parse($pickupLoan->updated_at)->format('d/m/Y') }}</div>
                                        </div>
                                        <div>
                                            <div style="font-size:12px; text-transform:uppercase; color:#64748b; font-weight:600;">Kode Pengambilan</div>
                                            <div style="font-family:monospace; font-weight:700;">{{ $pickupLoan->barcode }}</div>
                                        </div>
                                    </div>

                                    <form method="POST" action="{{ route('admin.loans.mark-picked-up', $pickupLoan->id) }}">
                                        @csrf
                                        <button type="submit" style="padding:12px 24px; background:#28a745; color:white; border:none; border-radius:4px; font-weight:600; cursor:pointer; font-size:14px;">
                                            ✓ Konfirmasi Buku Sudah Diambil
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="empty-state">
                                    <div class="empty-state-text">Kode tidak ditemukan atau status peminjaman bukan "approved".</div>
                                </div>
                            @endif
                        @else
                            <div class="empty-state">
                                <div class="empty-state-text">Masukkan kode pengambilan untuk melihat data peminjaman.</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sedang Dipinjam -->
                <div id="active" class="tab-content">
                    <div class="table-container">
                        <h3 class="table-title">Peminjaman Sedang Berlangsung</h3>
                        @if($allLoans->whereIn('status', ['active','returning'])->count() > 0)
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Peminjam</th>
                                        <th>Buku</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Sisa Hari</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allLoans->whereIn('status', ['active','returning']) as $loan)
                                    <tr>
                                        <td><strong>{{ $loan->user->name ?? 'N/A' }}</strong></td>
                                        <td>{{ $loan->book->title ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($loan->borrow_date)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}</td>
                                        <td>
                                            @php
                                                $daysLeft = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($loan->due_date)->startOfDay(), false);
                                            @endphp
                                            @if($daysLeft < 0)
                                                <strong style="color: #dc3545;">{{ abs($daysLeft) }} hari terlambat</strong>
                                                <div style="font-size:12px;color:#dc3545;">Perkiraan denda: Rp {{ number_format(abs($daysLeft) * 30000, 0, ',', '.') }}</div>
                                            @elseif($daysLeft <= 3)
                                                <strong style="color: #ffc107;">{{ $daysLeft }} hari</strong>
                                            @else
                                                <strong style="color: #28a745;">{{ $daysLeft }} hari</strong>
                                            @endif
                                        </td>
                                        <td>
                                            @if($loan->status === 'active')
                                                <span class="status-badge active" style="background: #d4edda; color: #155724;">Sedang Dipinjam</span>
                                            @else
                                                <span class="status-badge active" style="background: #fff3cd; color: #856404;">Dalam Pengembalian</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-text">Tidak ada peminjaman aktif</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Sudah Dikembalikan -->
                <div id="returned" class="tab-content">
                    <div class="table-container">
                        <h3 class="table-title">Buku Sudah Dikembalikan</h3>
                        @if($allLoans->where('status', 'returned')->count() > 0)
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Peminjam</th>
                                        <th>Buku</th>
                                        <th>Tanggal Peminjaman</th>
                                        <th>Tanggal Pengembalian</th>
                                        <th>Denda</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allLoans->where('status', 'returned') as $loan)
                                    <tr>
                                        <td><strong>{{ $loan->user->name ?? 'N/A' }}</strong></td>
                                        <td>{{ $loan->book->title ?? 'N/A' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($loan->borrow_date)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($loan->return_date)->format('d/m/Y') }}</td>
                                        <td>
                                            @if(($loan->fine ?? 0) > 0)
                                                <strong style="color: #dc3545;">Rp {{ number_format($loan->fine, 0, ',', '.') }}</strong>
                                            @else
                                                <span style="color: #28a745;">Rp 0</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-text">Belum ada buku yang dikembalikan</div>
                            </div>
                        @endif
                    </div>

                    <!-- Form untuk Input Kode Pengembalian -->
                    <div style="background: white; padding: 2rem; border-radius: 8px; margin-top: 2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                        <h3 style="margin-top: 0; margin-bottom: 0.5rem;">
                            <i class="fas fa-barcode"></i> Konfirmasi Pengembalian Buku
                        </h3>
                        <p style="color: #666; margin: 0 0 1.5rem 0; font-size: 14px;">
                            <strong>Cara Kerja:</strong> User akan datang dengan kode pengembalian yang telah disalin dari sistem. 
                            Minta user menunjukkan kode tersebut, kemudian scan/input kode di bawah untuk mengonfirmasi pengembalian.
                        </p>
                        
                        <div style="background: #e7f3ff; border-left: 4px solid #007bff; padding: 1rem; border-radius: 4px; margin-bottom: 1.5rem;">
                            <strong style="color: #004085;">📝 Langkah Admin/Petugas:</strong>
                            <ol style="margin: 0.5rem 0 0 1.5rem; font-size: 13px; color: #004085;">
                                <li>User datang dengan buku dan kode pengembalian</li>
                                <li>Verifikasi kondisi buku fisik</li>
                                <li>Scan atau input kode pengembalian di form di bawah</li>
                                <li>System otomatis hitung denda jika terlambat</li>
                                <li>Cetak atau kasih tahu denda kepada user (jika ada)</li>
                            </ol>
                        </div>
                        
                        <form method="POST" action="{{ route('admin.loans.return-barcode') }}">
                            @csrf
                            <div style="display: grid; grid-template-columns: 1fr auto; gap: 1rem;">
                                <div>
                                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: #333;">
                                        <i class="fas fa-barcode"></i> Kode Pengembalian / Barcode
                                    </label>
                                    <input type="text" name="barcode" 
                                        class="form-control" 
                                        placeholder="Scan barcode atau ketik kode pengembalian dari user..." 
                                        autofocus
                                        style="padding: 12px; border: 2px solid #28a745; border-radius: 4px; font-size: 15px; font-family: monospace; font-weight: 600;">
                                </div>
                                <div style="display: flex; align-items: flex-end;">
                                    <button type="submit" style="padding: 12px 24px; background: #28a745; color: white; border: none; border-radius: 4px; font-weight: 600; cursor: pointer; font-size: 14px; transition: 0.2s;">
                                        ✓ Konfirmasi Return
                                    </button>
                                </div>
                            </div>
                            
                            @if($errors->has('barcode'))
                            <div style="margin-top: 1rem; padding: 12px; background: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; border-radius: 4px; font-size: 13px;">
                                <strong>❌ Error:</strong> {{ $errors->first('barcode') }}
                            </div>
                            @endif
                        </form>
                        
                        @if(session('status'))
                        <div style="margin-top: 1rem; padding: 12px; background: #d4edda; border: 1px solid #c3e6cb; color: #155724; border-radius: 4px; font-size: 13px;">
                            <strong>✅ Sukses:</strong> {{ session('status') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', function() {
                const tabName = this.getAttribute('data-tab');
                
                // Hide all tabs
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.remove('active');
                });
                
                // Remove active class from all buttons
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Show selected tab
                document.getElementById(tabName).classList.add('active');
                
                // Add active class to clicked button
                this.classList.add('active');
            });
        });
        // Auto-refresh ringan untuk update data setiap 20 detik
        setInterval(function(){
            if(!document.hidden){ location.reload(); }
        }, 20000);
    </script>
</body>
</html>
