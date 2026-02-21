<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Riwayat Peminjaman - LibraryID</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-sidebar.css') }}">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f8fafc;
            color: #334155;
            line-height: 1.5;
            min-height: 100vh;
        }

        .main-content {
            min-height: 100vh;
            padding: 2rem 1rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header */
        .page-header {
            margin-bottom: 2.5rem;
        }

        .page-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .page-subtitle {
            font-size: 1rem;
            color: #64748b;
            font-weight: 400;
        }

        /* Stats Cards */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .stat-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-icon.total { background: #e0f2fe; color: #0369a1; }
        .stat-icon.active { background: #dcfce7; color: #166534; }
        .stat-icon.returned { background: #f1f5f9; color: #475569; }
        .stat-icon.overdue { background: #fee2e2; color: #991b1b; }

        .stat-info {
            flex: 1;
        }

        .stat-number {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 500;
        }

        /* Tabs */
        .tabs {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            margin-bottom: 2rem;
            overflow: hidden;
        }

        .tab-headers {
            display: flex;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 0.5rem;
            gap: 0.25rem;
        }

        .tab-header {
            flex: 1;
            padding: 0.875rem 1rem;
            background: none;
            border: none;
            font-family: 'Poppins', sans-serif;
            font-size: 0.875rem;
            font-weight: 500;
            color: #64748b;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .tab-header:hover {
            background: #f1f5f9;
            color: #475569;
        }

        .tab-header.active {
            background: white;
            color: #3b82f6;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .tab-badge {
            background: #e2e8f0;
            color: #475569;
            padding: 0.125rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            min-width: 24px;
        }

        .tab-header.active .tab-badge {
            background: #3b82f6;
            color: white;
        }

        .tab-content {
            padding: 2rem;
        }

        .tab-panel {
            display: none;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .tab-panel.active {
            display: block;
        }

        /* Loan Cards */
        .loans-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .loan-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid #e2e8f0;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .loan-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: #cbd5e1;
        }

        .loan-card.overdue {
            border-left: 4px solid #ef4444;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        }

        .loan-card.active {
            border-left: 4px solid #10b981;
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        }

        .loan-card.returned {
            border-left: 4px solid #94a3b8;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .loan-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.25rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .loan-title-section {
            flex: 1;
            min-width: 300px;
        }

        .loan-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.375rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .loan-author {
            font-size: 0.875rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .loan-status {
            padding: 0.375rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-active {
            background: #d1fae5;
            color: #065f46;
        }

        .status-overdue {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-returned {
            background: #f1f5f9;
            color: #475569;
        }

        .loan-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
            padding-top: 1.25rem;
            border-top: 1px solid #e2e8f0;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            gap: 0.375rem;
        }

        .detail-label {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 0.375rem;
        }

        .detail-value {
            font-size: 0.875rem;
            color: #1e293b;
            font-weight: 500;
        }

        .countdown-timer {
            padding: 1rem;
            background: white;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            margin: 1rem 0;
            text-align: center;
        }

        .countdown-timer.overdue {
            background: #fee2e2;
            border-color: #fecaca;
        }

        .countdown-timer.warning {
            background: #fef3c7;
            border-color: #fde68a;
        }

        .countdown-label {
            font-size: 0.75rem;
            color: #64748b;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .countdown-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #3b82f6;
            margin-bottom: 0.25rem;
        }

        .countdown-timer.overdue .countdown-value {
            color: #ef4444;
        }

        .countdown-timer.warning .countdown-value {
            color: #f59e0b;
        }

        .countdown-unit {
            font-size: 0.75rem;
            color: #64748b;
        }

        .alert-overdue {
            background: #fee2e2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 0.875rem;
            border-radius: 8px;
            font-size: 0.875rem;
            margin: 1rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .loan-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.625rem 1.25rem;
            border: none;
            border-radius: 8px;
            font-family: 'Poppins', sans-serif;
            font-size: 0.875rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-secondary {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .btn-secondary:hover {
            background: #e2e8f0;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #64748b;
        }

        .empty-state-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        .empty-state-title {
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #475569;
        }

        .empty-state-description {
            font-size: 0.875rem;
            max-width: 300px;
            margin: 0 auto;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 1.5rem 1rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .tab-headers {
                flex-wrap: wrap;
            }

            .tab-header {
                flex: 1 0 calc(50% - 0.5rem);
            }

            .tab-content {
                padding: 1.5rem;
            }

            .loan-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .loan-title-section {
                min-width: 100%;
            }

            .loan-details {
                grid-template-columns: 1fr;
            }

            .loan-actions {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }

        @media (max-width: 480px) {
            .tab-header {
                flex: 1 0 100%;
            }

            .tab-content {
                padding: 1rem;
            }
        }
    </style>
</head>
<body class="sidebar-page">
    @include('partials.user-sidebar')
    <!-- Main Content -->
    <div class="main-content content-with-sidebar">
        <div class="container">
            <!-- Header -->
            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-history"></i>
                    Riwayat Peminjaman
                </h1>
                <p class="page-subtitle">Kelola dan pantau semua peminjaman buku Anda</p>
            </div>

            <!-- Stats -->
            <div class="stats-container">
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-icon total">
                            <i class="fas fa-book-reader"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number">{{ $totalLoans }}</div>
                            <div class="stat-label">Total Peminjaman</div>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-icon active">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number">{{ $activeLoans }}</div>
                            <div class="stat-label">Sedang Dipinjam</div>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-icon returned">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number">{{ $returnedLoans }}</div>
                            <div class="stat-label">Sudah Dikembalikan</div>
                        </div>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-icon overdue">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stat-info">
                            <div class="stat-number">{{ $overdueLoans }}</div>
                            <div class="stat-label">Melewati Batas</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <div class="tab-headers">
                    <button class="tab-header active" onclick="openTab('pending')">
                        <i class="fas fa-clock"></i>
                        Dalam Proses
                        <span class="tab-badge">{{ $loans->whereIn('status', ['pending', 'booked', 'approved'])->count() }}</span>
                    </button>
                    
                    <button class="tab-header" onclick="openTab('active')">
                        <i class="fas fa-book-open"></i>
                        Sedang Dipinjam
                        <span class="tab-badge">{{ $activeLoans }}</span>
                    </button>
                    
                    <button class="tab-header" onclick="openTab('returning')">
                        <i class="fas fa-hourglass-half"></i>
                        Dalam Pengembalian
                        <span class="tab-badge">{{ $loans->where('status', 'returning')->count() }}</span>
                    </button>
                    
                    <button class="tab-header" onclick="openTab('returned')">
                        <i class="fas fa-check-circle"></i>
                        Dikembalikan
                        <span class="tab-badge">{{ $returnedLoans }}</span>
                    </button>
                </div>
                
                <div class="tab-content">
                    <!-- Pending Loans -->
                    <div id="tab-pending" class="tab-panel active">
                        <div class="loans-list">
                            @forelse($loans->whereIn('status', ['pending', 'booked', 'approved']) as $loan)
                                <div class="loan-card">
                                    <div class="loan-header">
                                        <div class="loan-title-section">
                                            <h3 class="loan-title">
                                                <i class="fas fa-clock"></i>
                                                {{ $loan->book->title }}
                                            </h3>
                                            <p class="loan-author">
                                                <i class="fas fa-user-edit"></i>
                                                {{ $loan->book->author }}
                                            </p>
                                        </div>
                                        <span class="loan-status status-pending">
                                            @if($loan->status === 'pending')
                                                Menunggu Konfirmasi
                                            @elseif($loan->status === 'approved')
                                                Proses Pengambilan Buku
                                            @else
                                               Dipesan
                                            @endif
                                        </span>
                                    </div>
                                    
                                    <div class="loan-details">
                                        <div class="detail-item">
                                            <span class="detail-label">
                                                <i class="fas fa-calendar-plus"></i>
                                                Tanggal
                                            </span>
                                            <span class="detail-value">
                                                {{ \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') }}
                                            </span>
                                        </div>
                                        
                                        <div class="detail-item">
                                            <span class="detail-label">
                                                <i class="fas fa-calendar-check"></i>
                                                Jatuh Tempo
                                            </span>
                                            <span class="detail-value">
                                                {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="loan-actions">
                                        <a href="{{ route('loans.show', $loan) }}" class="btn btn-primary">
                                            <i class="fas fa-eye"></i>
                                            Lihat Detail
                                        </a>
                                        
                                        @if($loan->status === 'pending')
                                            <form method="POST" action="{{ route('loans.cancel', $loan) }}" style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-secondary" onclick="return confirm('Batalkan peminjaman ini?')">
                                                    <i class="fas fa-times"></i>
                                                    Batalkan
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <h4 class="empty-state-title">Tidak ada peminjaman dalam proses</h4>
                                    <p class="empty-state-description">
                                        Semua peminjaman Anda sedang berjalan dengan baik
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Active Loans -->
                    <div id="tab-active" class="tab-panel">
                        <div class="loans-list">
                            @forelse($loans->where('status', 'active') as $loan)
                                <div class="loan-card {{ $loan->isOverdue() ? 'overdue' : 'active' }}">
                                    <div class="loan-header">
                                        <div class="loan-title-section">
                                            <h3 class="loan-title">
                                                <i class="fas fa-book-open"></i>
                                                {{ $loan->book->title }}
                                            </h3>
                                            <p class="loan-author">
                                                <i class="fas fa-user-edit"></i>
                                                {{ $loan->book->author }}
                                            </p>
                                        </div>
                                        <span class="loan-status {{ $loan->isOverdue() ? 'status-overdue' : 'status-active' }}">
                                            {{ $loan->isOverdue() ? 'Terlambat' : 'Aktif' }}
                                        </span>
                                    </div>
                                    
                                    <div class="loan-details">
                                        <div class="detail-item">
                                            <span class="detail-label">
                                                <i class="fas fa-calendar-plus"></i>
                                                Tanggal
                                            </span>
                                            <span class="detail-value">
                                                {{ \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') }}
                                            </span>
                                        </div>
                                        
                                        <div class="detail-item">
                                            <span class="detail-label">
                                                <i class="fas fa-calendar-check"></i>
                                                Jatuh Tempo
                                            </span>
                                            <span class="detail-value">
                                                {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                                            </span>
                                        </div>
                                        
                                        <div class="detail-item">
                                            <span class="detail-label">
                                                <i class="fas fa-barcode"></i>
                                                Kode
                                            </span>
                                            <span class="detail-value">
                                                {{ $loan->barcode }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    @if($loan->due_date)
                                        <div class="countdown-timer {{ $loan->isOverdue() ? 'overdue' : (\Carbon\Carbon::parse($loan->due_date)->diffInDays(now()) <= 3 ? 'warning' : '') }}">
                                            <div class="countdown-label">
                                                {{ $loan->isOverdue() ? 'Terlambat' : 'Sisa waktu' }}
                                            </div>
                                            <div class="countdown-value" data-due-date="{{ $loan->due_date }}">
                                                0
                                            </div>
                                            <div class="countdown-unit">hari</div>
                                        </div>
                                    @endif
                                    
                                    @if($loan->isOverdue())
                                        <div class="alert-overdue">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            Buku sudah melewati tanggal jatuh tempo
                                        </div>
                                    @endif
                                    
                                    <div class="loan-actions">
                                        <a href="{{ route('loans.show', $loan) }}" class="btn btn-primary">
                                            <i class="fas fa-eye"></i>
                                            Lihat Detail
                                        </a>
                                        
                                        <form method="POST" action="{{ route('loans.return', $loan) }}" style="display: inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-success" onclick="return confirm('Mulai proses pengembalian buku ini? Anda akan mendapatkan kode pengembalian untuk ditunjukkan ke petugas.')">
                                                <i class="fas fa-arrow-down"></i>
                                                Kembalikan
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-book-open"></i>
                                    </div>
                                    <h4 class="empty-state-title">Tidak ada peminjaman aktif</h4>
                                    <p class="empty-state-description">
                                        Mulailah meminjam buku dari katalog kami
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Returning Loans -->
                    <div id="tab-returning" class="tab-panel">
                        <div class="loans-list">
                            @forelse($loans->where('status', 'returning') as $loan)
                                <div class="loan-card" style="border-left: 4px solid #f59e0b;">
                                    <div class="loan-header">
                                        <div class="loan-title-section">
                                            <h3 class="loan-title">
                                                <i class="fas fa-book-open"></i>
                                                {{ $loan->book->title }}
                                            </h3>
                                            <p class="loan-author">
                                                <i class="fas fa-user-edit"></i>
                                                {{ $loan->book->author }}
                                            </p>
                                        </div>
                                        <span class="loan-status" style="background: #fef3c7; color: #92400e;">
                                            Dalam Pengembalian
                                        </span>
                                    </div>
                                    
                                    <div class="loan-details">
                                        <div class="detail-item">
                                            <span class="detail-label">
                                                <i class="fas fa-barcode"></i>
                                                Kode Pengembalian
                                            </span>
                                            <span class="detail-value" style="font-weight: 600; color: #f59e0b; font-family: monospace;">
                                                {{ $loan->return_code }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div style="background: #fef3c7; border: 1px solid #fcd34d; padding: 1rem; border-radius: 4px; margin-bottom: 1rem; color: #92400e; font-size: 13px;">
                                        <i class="fas fa-info-circle"></i>
                                        Tunjukkan kode di atas ke petugas perpustakaan untuk menyelesaikan pengembalian.
                                    </div>
                                    
                                    <div class="loan-actions">
                                        <a href="{{ route('loans.show', $loan) }}" class="btn btn-primary">
                                            <i class="fas fa-eye"></i>
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <h4 class="empty-state-title">Tidak ada peminjaman dalam pengembalian</h4>
                                    <p class="empty-state-description">
                                        Belum ada buku yang sedang dalam proses pengembalian
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    
                    <!-- Returned Loans -->
                    <div id="tab-returned" class="tab-panel">
                        <div class="loans-list">
                            @forelse($loans->where('status', 'returned') as $loan)
                                <div class="loan-card returned">
                                    <div class="loan-header">
                                        <div class="loan-title-section">
                                            <h3 class="loan-title">
                                                <i class="fas fa-check-circle"></i>
                                                {{ $loan->book->title }}
                                            </h3>
                                            <p class="loan-author">
                                                <i class="fas fa-user-edit"></i>
                                                {{ $loan->book->author }}
                                            </p>
                                        </div>
                                        <span class="loan-status status-returned">
                                            Dikembalikan
                                        </span>
                                    </div>
                                    
                                    <div class="loan-details">
                                        <div class="detail-item">
                                            <span class="detail-label">
                                                <i class="fas fa-calendar-plus"></i>
                                                Tanggal Pinjam
                                            </span>
                                            <span class="detail-value">
                                                {{ \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') }}
                                            </span>
                                        </div>
                                        
                                        <div class="detail-item">
                                            <span class="detail-label">
                                                <i class="fas fa-calendar-check"></i>
                                                Jatuh Tempo
                                            </span>
                                            <span class="detail-value">
                                                {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                                            </span>
                                        </div>
                                        
                                        <div class="detail-item">
                                            <span class="detail-label">
                                                <i class="fas fa-calendar-minus"></i>
                                                Dikembalikan
                                            </span>
                                            <span class="detail-value">
                                                {{ $loan->return_date ? \Carbon\Carbon::parse($loan->return_date)->format('d M Y') : '-' }}
                                            </span>
                                        </div>
                                        
                                        <div class="detail-item">
                                            <span class="detail-label">
                                                <i class="fas fa-exclamation-triangle"></i>
                                                Denda
                                            </span>
                                            @if($loan->fine > 0)
                                                <span class="detail-value" style="color: #ef4444;">
                                                    Rp {{ number_format($loan->fine, 0, ',', '.') }}
                                                </span>
                                            @else
                                                <span class="detail-value" style="color: #16a34a;">
                                                    -
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Rating Section -->
                                    <div style="padding-top: 1.25rem; border-top: 1px solid #e2e8f0; margin-top: 1.5rem;">
                                        <h4 style="font-size: 0.875rem; font-weight: 600; color: #1e293b; margin-bottom: 1rem;">
                                            <i class="fas fa-star"></i> Berikan Rating
                                        </h4>
                                        <div class="rating-form" data-loan-id="{{ $loan->id }}" data-book-id="{{ $loan->book->id }}">
                                            <div class="rating-status" style="display: none; padding: 1rem; background: #dcfce7; border: 1px solid #86efac; border-radius: 8px; margin-bottom: 1rem;">
                                                <div style="color: #166534; font-weight: 600; margin-bottom: 0.5rem;">
                                                    ✓ Rating Anda Tersimpan
                                                </div>
                                                <div class="existing-rating" style="color: #166534; font-size: 0.875rem;"></div>
                                                <div style="margin-top: 0.75rem;">
                                                    <a href="{{ route('books.show', $loan->book->id) }}" class="btn btn-secondary">
                                                        <i class="fas fa-eye"></i>
                                                        Lihat Detail
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="rating-input-section">
                                                <div style="margin-bottom: 1rem;">
                                                    <label style="font-size: 0.875rem; color: #64748b; font-weight: 500; margin-bottom: 0.5rem; display: block;">
                                                        Rating (1-5 bintang)
                                                    </label>
                                                    <div style="display: flex; gap: 0.5rem;">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <button class="rating-star" data-rating="{{ $i }}" style="background: none; border: none; font-size: 2rem; cursor: pointer; color: #cbd5e1; transition: color 0.2s ease;">
                                                                <i class="fas fa-star"></i>
                                                            </button>
                                                        @endfor
                                                    </div>
                                                    <input type="hidden" class="rating-value" value="0">
                                                </div>

                                                <div style="margin-bottom: 1rem;">
                                                    <label style="font-size: 0.875rem; color: #64748b; font-weight: 500; margin-bottom: 0.5rem; display: block;">
                                                        Komentar (opsional)
                                                    </label>
                                                    <textarea class="rating-comment" placeholder="Bagikan pengalaman Anda membaca buku ini..." style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-family: 'Poppins', sans-serif; font-size: 0.875rem; resize: vertical; min-height: 100px; color: #334155;"></textarea>
                                                </div>

                                                <button class="btn btn-primary submit-rating" style="width: 100%; justify-content: center;">
                                                    <i class="fas fa-paper-plane"></i>
                                                    Kirim Rating
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="loan-actions">
                                        <a href="{{ route('loans.show', $loan) }}" class="btn btn-primary">
                                            <i class="fas fa-eye"></i>
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h4 class="empty-state-title">Tidak ada peminjaman yang dikembalikan</h4>
                                    <p class="empty-state-description">
                                        Semua peminjaman Anda sedang dalam proses
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <div id="notificationPopup" class="notification-popup-modal" style="display: none;">
            <div class="notification-popup-content">
                <div class="notification-header">
                    <h3>Notifikasi Peminjaman</h3>
                    <span class="close-popup" onclick="closeNotificationPopup()">&times;</span>
                </div>
                <div class="notification-list">
                    <div class="notification-empty" id="emptyState">
                        <p>Tidak ada notifikasi saat ini</p>
                    </div>
                    <div id="notificationItems"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentSlide = 0;
        let unreadNotifications = 0;
        
        function moveCarousel(direction) {
            const slides = document.querySelectorAll('.carousel-slide');
            const dots = document.querySelectorAll('.dot');
            const totalSlides = slides.length;
            
            currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
            updateCarousel();
        }
        
        function goToSlide(index) {
            currentSlide = index;
            updateCarousel();
        }
        
        function updateCarousel() {
            const wrapper = document.querySelector('.carousel-wrapper');
            const slides = document.querySelectorAll('.carousel-slide');
            const dots = document.querySelectorAll('.dot');
            
            wrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
            
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentSlide);
            });
        }

        // Fungsi untuk mengambil data notifikasi dari backend
        function fetchNotifications() {
            fetch('{{ route("notifications.get") }}', {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateNotificationUI(data);
            })
            .catch(error => {
                console.error('Error fetching notifications:', error);
                // Fallback: tampilkan notifikasi dummy jika backend belum ready
                loadDemoNotifications();
            });
        }

        // Fungsi untuk update UI notifikasi
        function updateNotificationUI(data) {
            const badge = document.getElementById('notificationBadge');
            const notificationItems = document.getElementById('notificationItems');
            const emptyState = document.getElementById('emptyState');
            
            unreadNotifications = data.unread_count || 0;
            
            // Update badge
            if (unreadNotifications > 0) {
                badge.textContent = unreadNotifications;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
            
            // Update notification list
            if (data.notifications && data.notifications.length > 0) {
                emptyState.style.display = 'none';
                notificationItems.innerHTML = '';
                
                data.notifications.forEach(notification => {
                    const item = createNotificationItem(notification);
                    notificationItems.appendChild(item);
                });
            } else {
                emptyState.style.display = 'block';
                notificationItems.innerHTML = '';
            }
        }

        // Fungsi untuk membuat elemen notifikasi
        function createNotificationItem(notification) {
            const item = document.createElement('div');
            item.className = 'notification-item ' + (notification.is_read ? '' : 'unread');
            
            const daysOverdue = notification.days_overdue || 0;
            const statusClass = daysOverdue > 7 ? 'critical' : daysOverdue > 3 ? 'warning' : 'info';
            
            item.innerHTML = `
                <div class="notification-item-left">
                    <div class="notification-icon ${statusClass}">
                        <i class="icon-warning">⚠️</i>
                    </div>
                </div>
                <div class="notification-item-content">
                    <div class="notification-item-title">${notification.book_title}</div>
                    <div class="notification-item-message">
                        Terlambat ${daysOverdue} hari
                    </div>
                    <div class="notification-item-meta">
                        ${new Date(notification.created_at).toLocaleDateString('id-ID')}
                    </div>
                </div>
                <div class="notification-item-action">
                    <a href="{{ route('loans.history') }}" class="btn-return-book">Kembalikan</a>
                </div>
            `;
            
            return item;
        }

        // Demo notifikasi (untuk testing)
        function loadDemoNotifications() {
            const demoData = {
                unread_count: 2,
                notifications: [
                    {
                        id: 1,
                        book_title: 'Surat untuk Senja',
                        days_overdue: 5,
                        is_read: false,
                        created_at: new Date().toISOString()
                    },
                    {
                        id: 2,
                        book_title: 'Informatika Kelas X',
                        days_overdue: 2,
                        is_read: false,
                        created_at: new Date().toISOString()
                    }
                ]
            };
            updateNotificationUI(demoData);
        }

        function closeNotificationPopup() {
            document.getElementById('notificationPopup').style.display = 'none';
            // Mark notifications as read
            markNotificationsAsRead();
        }

        function showNotificationPopup(event) {
            event.preventDefault();
            document.getElementById('notificationPopup').style.display = 'block';
        }

        function markNotificationsAsRead() {
            fetch('{{ route("notifications.mark-read") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({})
            })
            .catch(error => console.error('Error marking notifications as read:', error));
        }

        // Tutup popup ketika klik di luar
        window.addEventListener('click', function(event) {
            const popup = document.getElementById('notificationPopup');
            if (event.target === popup) {
                closeNotificationPopup();
            }
        });

        // Load notifikasi saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            fetchNotifications();
            // Refresh notifikasi setiap 30 detik
            setInterval(fetchNotifications, 30000);
        });
        
        // Tab functionality
        function openTab(tabName) {
            // Hide all tab panels
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.remove('active');
            });
            
            // Remove active class from all tab headers
            document.querySelectorAll('.tab-header').forEach(header => {
                header.classList.remove('active');
            });
            
            // Show selected tab panel
            document.getElementById(`tab-${tabName}`).classList.add('active');
            
            // Add active class to clicked tab header
            event.currentTarget.classList.add('active');
        }

        // Update countdown timers
        function updateCountdownTimers() {
            document.querySelectorAll('.countdown-value').forEach(element => {
                const dueDate = new Date(element.dataset.dueDate);
                const now = new Date();
                const diffTime = dueDate - now;
                const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
                
                element.textContent = Math.abs(diffDays);
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateCountdownTimers();
            
            // Update every minute
            setInterval(updateCountdownTimers, 60000);
            
            // Add hover effects to loan cards
            document.querySelectorAll('.loan-card').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-4px)';
                    this.style.boxShadow = '0 8px 16px rgba(0, 0, 0, 0.1)';
                });
                
                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = 'none';
                });
            });

            // Rating functionality
            document.querySelectorAll('.rating-form').forEach(form => {
                const loanId = form.dataset.loanId;
                const bookId = form.dataset.bookId;
                const stars = form.querySelectorAll('.rating-star');
                const ratingValue = form.querySelector('.rating-value');
                const submitBtn = form.querySelector('.submit-rating');
                const commentField = form.querySelector('.rating-comment');
                const ratingInputSection = form.querySelector('.rating-input-section');
                const ratingStatus = form.querySelector('.rating-status');

                // Check if already rated
                checkExistingRating(loanId, form);

                // Star click handler
                stars.forEach(star => {
                    star.addEventListener('click', function(e) {
                        e.preventDefault();
                        const rating = this.dataset.rating;
                        ratingValue.value = rating;

                        // Update visual
                        stars.forEach((s, idx) => {
                            if (idx < rating) {
                                s.style.color = '#f59e0b';
                            } else {
                                s.style.color = '#cbd5e1';
                            }
                        });
                    });

                    // Hover effect
                    star.addEventListener('mouseenter', function() {
                        const rating = this.dataset.rating;
                        stars.forEach((s, idx) => {
                            if (idx < rating) {
                                s.style.color = '#fbbf24';
                            } else {
                                s.style.color = '#e5e7eb';
                            }
                        });
                    });
                });

                form.addEventListener('mouseleave', function() {
                    const currentRating = ratingValue.value;
                    stars.forEach((s, idx) => {
                        if (idx < currentRating) {
                            s.style.color = '#f59e0b';
                        } else {
                            s.style.color = '#cbd5e1';
                        }
                    });
                });

                // Submit rating
                submitBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const rating = ratingValue.value;
                    if (rating == 0) {
                        alert('Silakan pilih rating terlebih dahulu');
                        return;
                    }

                    const comment = form.querySelector('.rating-comment').value;
                    const loanCard = form.closest('.loan-card');
                    
                    submitBtn.disabled = true;
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '⏳ Menyimpan...';

                    fetch('{{ route("reviews.store") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            loan_id: parseInt(loanId),
                            book_id: parseInt(bookId),
                            rating: parseInt(rating),
                            comment: comment
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            // Reset form
                            ratingValue.value = 0;
                            form.querySelector('.rating-comment').value = '';
                            stars.forEach(s => s.style.color = '#cbd5e1');
                            
                            // Hide input section and show status
                            ratingInputSection.style.display = 'none';
                            ratingStatus.style.display = 'block';
                            updateRatingDisplay(data.review, form);
                        } else {
                            console.error('Error response:', data);
                            alert('❌ ' + (data.message || 'Terjadi kesalahan saat menyimpan rating'));
                            submitBtn.innerHTML = originalText;
                            submitBtn.style.background = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('❌ Terjadi kesalahan saat menyimpan rating. Cek konsol untuk detail.');
                        submitBtn.innerHTML = originalText;
                        submitBtn.style.background = '';
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                    });
                });
            });

            function checkExistingRating(loanId, form) {
                fetch('/api/loans/' + loanId + '/review')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.review) {
                            // Already rated - hide input and show existing rating
                            const ratingInputSection = form.querySelector('.rating-input-section');
                            const ratingStatus = form.querySelector('.rating-status');
                            
                            ratingInputSection.style.display = 'none';
                            ratingStatus.style.display = 'block';
                            updateRatingDisplay(data.review, form);
                        }
                    })
                    .catch(error => console.error('Error checking rating:', error));
            }

            function updateRatingDisplay(review, form) {
                const existingRating = form.querySelector('.existing-rating');
                const stars = `${'⭐'.repeat(review.rating)}`;
                
                let ratingText = `<div style="margin-bottom: 0.5rem;">Rating: ${stars}</div>`;
                
                if (review.comment) {
                    ratingText += `<div style="font-style: italic; margin-top: 0.5rem;">
                        <strong>Komentar:</strong> "${review.comment}"
                    </div>`;
                }
                
                ratingText += `<div style="font-size: 0.75rem; color: #666; margin-top: 0.75rem;">
                    Rating diberikan pada: ${new Date(review.created_at).toLocaleDateString('id-ID')}
                </div>`;
                
                existingRating.innerHTML = ratingText;
            }
        });

        // Define notification routes untuk digunakan di user-notifications.js
        const notificationRoutes = {
            get: '{{ route("notifications.get") }}',
            markRead: '{{ route("notifications.mark-read") }}',
            csrfToken: '{{ csrf_token() }}',
            loansHistory: '{{ route("loans.history") }}'
        };

        // Define user's loan data
        const userLoansData = {
            overdueCount: {{ isset($overdueLoans) ? $overdueLoans : 0 }},
            pendingCount: {{ isset($pendingLoans) ? $pendingLoans : 0 }},
            activeCount: {{ isset($activeLoans) ? $activeLoans : 0 }}
        };

        // Debug function - type this in console to get info
        window.debugReview = function() {
            console.log('=== Review Debug Info ===');
            const forms = document.querySelectorAll('.rating-form');
            console.log('Found rating forms:', forms.length);
            forms.forEach((form, idx) => {
                console.log(`Form ${idx}:`, {
                    loanId: form.dataset.loanId,
                    bookId: form.dataset.bookId,
                });
            });
            fetch('/debug/reviews')
                .then(r => r.json())
                .then(data => console.log('Server reviews data:', data));
        };
    </script>
    <script src="{{ asset('js/user-notifications.js') }}"></script>
    <script src="{{ asset('js/user-sidebar.js') }}"></script>
</body>
</html>
