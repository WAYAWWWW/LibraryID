<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Peminjaman - Petugas LibraryID</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    
    <link rel="stylesheet" href="{{ asset('css/petugas-loans-show.css') }}">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('petugas.dashboard') }}" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="LibraryID Logo">
            </a>
            <ul class="nav-menu">
                <li><a href="{{ route('petugas.dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('petugas.kelola-buku') }}">Kelola Buku</a></li>
                <li><a href="{{ route('petugas.ratings') }}">Rating User</a></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-logout" style="margin: 0; padding: 0.5rem 1rem; font-size: 0.9rem;">Logout</button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="detail-container">
            <a href="{{ route('petugas.dashboard') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

            <!-- Header -->
            <div class="header">
                <h1>Detail Peminjaman #{{ $loan->id }}</h1>
                <span class="status-badge status-{{ $loan->status }}">
                    @if($loan->status === 'pending')
                        <i class="fas fa-clock"></i> Menunggu
                    @elseif($loan->status === 'approved')
                        <i class="fas fa-key"></i> Siap Diambil
                    @elseif($loan->status === 'active')
                        <i class="fas fa-book-reader"></i> Sedang Dipinjam
                    @elseif($loan->status === 'returned')
                        <i class="fas fa-check-double"></i> Dikembalikan
                    @else
                        {{ ucfirst($loan->status) }}
                    @endif
                </span>
            </div>

            <!-- Status Alert -->
            @if($loan->status === 'pending')
                <div class="alert alert-warning">
                    <i class="fas fa-hourglass-start"></i> <strong>Menunggu Persetujuan</strong> - Silakan review dan setujui atau tolak permintaan ini
                </div>
            @elseif($loan->status === 'approved')
                <div class="alert alert-info">
                    <i class="fas fa-key"></i> <strong>Siap Diambil</strong> - Peminjam sudah menerima kode, menunggu datang mengambil buku
                </div>
            @elseif($loan->status === 'active')
                <div class="alert alert-success">
                    <i class="fas fa-book-reader"></i> <strong>Sedang Dipinjam</strong> - Buku sedang dipinjam, menunggu pengembalian
                </div>
            @elseif($loan->status === 'returned')
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <strong>Sudah Dikembalikan</strong> - Buku telah diterima pada {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y H:i') }}
                </div>
            @endif

            <!-- Borrower Info -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user-circle"></i>
                    <h2>Data Peminjam</h2>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-group">
                            <span class="info-label">Nama Lengkap</span>
                            <span class="info-value">{{ $loan->user->name }}</span>
                        </div>
                        <div class="info-group">
                            <span class="info-label">Email</span>
                            <span class="info-value">{{ $loan->user->email }}</span>
                        </div>
                        @if($loan->user->phone)
                        <div class="info-group">
                            <span class="info-label">Telepon</span>
                            <span class="info-value">{{ $loan->user->phone }}</span>
                        </div>
                        @endif
                        @if($loan->user->address)
                        <div class="info-group">
                            <span class="info-label">Alamat</span>
                            <span class="info-value">{{ $loan->user->address }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Book Info -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-book"></i>
                    <h2>Detail Buku</h2>
                </div>
                <div class="card-body">
                    <div class="book-section">
                        <div class="book-cover">
                            <img src="{{ route('books.cover', $loan->book->id) }}" alt="{{ $loan->book->title }}">
                        </div>
                        <div class="book-details">
                            <h3 class="book-title">{{ $loan->book->title }}</h3>
                            <p style="color: var(--gray-color); margin: 0.5rem 0 1rem 0;">
                                <i class="fas fa-pen-fancy"></i> {{ $loan->book->author }}
                            </p>
                            <div class="book-meta">
                                <div class="meta-item">
                                    <div class="meta-label">Penerbit</div>
                                    <div class="meta-value">{{ $loan->book->publisher ?? '-' }}</div>
                                </div>
                                <div class="meta-item">
                                    <div class="meta-label">Tahun Terbit</div>
                                    <div class="meta-value">{{ $loan->book->publication_year ?? $loan->book->year ?? '-' }}</div>
                                </div>
                                <div class="meta-item">
                                    <div class="meta-label">Halaman</div>
                                    <div class="meta-value">{{ $loan->book->pages ?? '-' }}</div>
                                </div>
                                <div class="meta-item">
                                    <div class="meta-label">Kategori</div>
                                    <div class="meta-value">{{ $loan->book->category ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Schedule -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-calendar"></i>
                    <h2>Jadwal Peminjaman</h2>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-date">{{ $loan->borrow_date ? \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') : '-' }}</div>
                            <div class="timeline-title">Tanggal Peminjaman</div>
                            <div class="timeline-desc">Mulai peminjaman</div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-date">{{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d M Y') : '-' }}</div>
                            <div class="timeline-title">Jatuh Tempo</div>
                            <div class="timeline-desc">Deadline pengembalian</div>
                        </div>

                        @if($loan->return_date)
                        <div class="timeline-item">
                            <div class="timeline-date">{{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}</div>
                            <div class="timeline-title">Tanggal Pengembalian</div>
                            <div class="timeline-desc">Buku dikembalikan</div>
                        </div>
                        @endif
                    </div>

                    @if($loan->borrow_date && $loan->due_date)
                    <div class="info-grid" style="margin-top: 1.5rem;">
                        <div class="info-group">
                            <span class="info-label">Durasi Peminjaman</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($loan->borrow_date)->diffInDays(\Carbon\Carbon::parse($loan->due_date)) }} hari</span>
                        </div>
                        @if($loan->return_date)
                        <div class="info-group">
                            <span class="info-label">Durasi Aktual</span>
                            <span class="info-value">{{ \Carbon\Carbon::parse($loan->borrow_date)->diffInDays(\Carbon\Carbon::parse($loan->return_date)) }} hari</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($loan->fine > 0)
                    <div class="alert alert-warning" style="margin-top: 1.5rem;">
                        <i class="fas fa-money-bill-wave"></i> <strong>Denda:</strong> Rp {{ number_format($loan->fine, 0, ',', '.') }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Pickup Code (for approved loans) -->
            @if($loan->status === 'approved' && !$loan->return_date)
            <div class="pickup-code-box">
                <div style="font-size: 12px; text-transform: uppercase; font-weight: 600; letter-spacing: 1px; opacity: 0.9;">
                    <i class="fas fa-lock"></i> Kode Pengambilan Buku
                </div>
                <div class="pickup-code">{{ $loan->barcode }}</div>
                <div style="font-size: 13px; opacity: 0.9; margin-bottom: 1rem;">
                    Peminjam harus menunjukkan kode ini untuk mengambil buku
                </div>
                <button onclick="copyToClipboard('{{ $loan->barcode }}')" style="padding: 8px 16px; background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.5); color: white; border-radius: 4px; cursor: pointer; font-weight: 600; font-size: 12px;">
                    <i class="fas fa-copy"></i> Salin Kode
                </button>
            </div>
            @endif

            <!-- Actions Section -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-tasks"></i>
                    <h2>Tindakan Petugas</h2>
                </div>
                <div class="card-body">
                    @if($loan->status === 'pending')
                        <div class="alert alert-info" style="background: #d1ecf1; border-left-color: #17a2b8; color: #0c5460; margin-bottom: 1.5rem;">
                            <i class="fas fa-info-circle"></i> <strong>Status: Menunggu Persetujuan</strong>
                            <p style="margin: 0.5rem 0 0 0; font-size: 13px;">Silakan pilih untuk menyetujui atau menolak permintaan peminjaman ini.</p>
                        </div>
                        <div class="action-section">
                            <form method="POST" action="{{ route('loans.approve', $loan->id) }}" style="display: inline; flex: 1;">
                                @csrf
                                <button type="submit" class="btn btn-success" style="width: 100%;">
                                    <i class="fas fa-check-circle"></i> Setujui Peminjaman
                                </button>
                            </form>
                            <form method="POST" action="{{ route('loans.reject', $loan->id) }}" style="display: inline; flex: 1;">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Tolak permintaan peminjaman ini?')" style="width: 100%;">
                                    <i class="fas fa-times-circle"></i> Tolak Peminjaman
                                </button>
                            </form>
                        </div>

                    @elseif($loan->status === 'approved')
                        <div class="alert alert-success" style="background: #d4edda; border-left-color: #28a745; color: #155724; margin-bottom: 1.5rem;">
                            <i class="fas fa-check-circle"></i> <strong>Status: Siap Diambil</strong>
                            <p style="margin: 0.5rem 0 0 0; font-size: 13px;">Peminjam sudah menerima kode pengambilan. Tunggu peminjam datang dan ambil buku, kemudian klik tombol di bawah.</p>
                        </div>
                        <form method="POST" action="{{ route('loans.mark-picked-up', $loan->id) }}" style="width: 100%;">
                            @csrf
                            <button type="submit" class="btn btn-success" style="width: 100%; padding: 14px;">
                                <i class="fas fa-check"></i> Konfirmasi Pengambilan Buku
                            </button>
                        </form>
                        <p style="margin-top: 1rem; font-size: 13px; color: #666; text-align: center;">
                            <i class="fas fa-arrow-down"></i> Klik tombol di atas saat peminjam mengambil buku dengan kode ini
                        </p>

                    @elseif($loan->status === 'active')
                        <div class="alert alert-success" style="background: #d4edda; border-left-color: #28a745; color: #155724; margin-bottom: 1.5rem;">
                            <i class="fas fa-book-reader"></i> <strong>Status: Sedang Dipinjam</strong>
                            <p style="margin: 0.5rem 0 0 0; font-size: 13px;">Buku sedang dipinjam oleh peminjam. Tunggu peminjam mengembalikan buku.</p>
                        </div>
                        <p style="padding: 1rem; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 4px; color: #856404; margin: 0;">
                            <i class="fas fa-hourglass-end"></i> Jatuh Tempo: <strong>{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</strong>
                            @if(now()->gt($loan->due_date))
                                <br><i class="fas fa-exclamation-triangle"></i> <strong>TERLAMBAT</strong> sejak {{ now()->diffInDays($loan->due_date) }} hari
                            @endif
                        </p>

                    @elseif($loan->status === 'returned')
                        <div class="alert alert-success" style="background: #d4edda; border-left-color: #28a745; color: #155724;">
                            <i class="fas fa-check-double"></i> <strong>Status: Sudah Dikembalikan</strong>
                            <p style="margin: 0.5rem 0 0 0; font-size: 13px;">Peminjaman selesai. Buku sudah diterima kembali pada {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y H:i') }}.</p>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle"></i> Status: {{ ucfirst($loan->status) }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('Kode disalin: ' + text);
            }).catch(() => {
                alert('Gagal menyalin kode');
            });
        }
    </script>
</body>
</html>
