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
    
    <style>
        :root {
            --primary-color: #4361ee;
            --primary-dark: #3a56d4;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --dark-color: #212529;
            --gray-color: #6c757d;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            color: var(--dark-color);
            line-height: 1.6;
            min-height: 100vh;
        }

        .main-content {
            min-height: calc(100vh - 80px);
            padding: 2rem 1rem;
        }

        .detail-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 2rem;
            padding: 10px 20px;
            background: white;
            color: var(--primary-color);
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid rgba(67, 97, 238, 0.1);
            transition: all 0.2s ease;
        }

        .back-btn:hover {
            transform: translateX(-5px);
            background: var(--primary-color);
            color: white;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-returned {
            background: #d4edda;
            color: #155724;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 1.2rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .card-header h2 {
            font-size: 1.1rem;
            font-weight: 600;
            margin: 0;
        }

        .card-body {
            padding: 1.5rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .info-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .info-label {
            font-size: 12px;
            color: var(--gray-color);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 15px;
            color: var(--dark-color);
            font-weight: 500;
        }

        .action-section {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }

        .btn {
            padding: 10px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn-success:hover {
            background: #1e7e34;
            transform: translateY(-2px);
        }

        .btn-danger {
            background: var(--danger-color);
            color: white;
        }

        .btn-danger:hover {
            background: #bd2130;
            transform: translateY(-2px);
        }

        .book-section {
            display: flex;
            gap: 2rem;
            align-items: flex-start;
        }

        .book-cover {
            width: 120px;
            height: 160px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            flex-shrink: 0;
        }

        .book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .book-details {
            flex-grow: 1;
        }

        .book-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin: 0 0 0.5rem 0;
        }

        .book-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .meta-item {
            padding: 0.75rem;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid var(--primary-color);
        }

        .meta-label {
            font-size: 11px;
            color: var(--gray-color);
            text-transform: uppercase;
            font-weight: 600;
        }

        .meta-value {
            font-size: 14px;
            color: var(--dark-color);
            font-weight: 500;
            margin-top: 0.3rem;
        }

        .timeline {
            position: relative;
            padding-left: 25px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, var(--primary-color), var(--primary-dark));
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
            padding-left: 1.5rem;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 0;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: var(--primary-color);
            border: 2px solid white;
        }

        .timeline-date {
            font-size: 13px;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 0.3rem;
        }

        .timeline-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark-color);
        }

        .timeline-desc {
            font-size: 13px;
            color: var(--gray-color);
            margin-top: 0.2rem;
        }

        .alert {
            padding: 1rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            border-left: 4px solid;
        }

        .alert-info {
            background: #e7f3ff;
            border-left-color: var(--primary-color);
            color: var(--primary-color);
        }

        .alert-warning {
            background: #fff3cd;
            border-left-color: #ffc107;
            color: #856404;
        }

        .alert-success {
            background: #d4edda;
            border-left-color: var(--success-color);
            color: #155724;
        }

        .pickup-code-box {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .pickup-code {
            font-size: 2.5rem;
            font-weight: 800;
            font-family: 'Courier New', monospace;
            letter-spacing: 0.2em;
            margin: 1rem 0;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }

            .book-section {
                flex-direction: column;
                align-items: flex-start;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .action-section {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
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
