<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Peminjaman - LibraryID</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:300,400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bookdetail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-sidebar.css') }}">
    
    
    <link rel="stylesheet" href="{{ asset('css/loans-show.css') }}">
</head>
<body class="sidebar-page">
    @include('partials.user-sidebar')
    <!-- ===== Navbar ===== -->


    <!-- Main Content -->
    <div class="main-content content-with-sidebar">
        <div class="detail-container">
            <a href="{{ route('loans.history') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i> Kembali ke Riwayat
            </a>

            <!-- Header -->
            <div class="detail-header">
                <h1>Detail Peminjaman Buku</h1>
                @php
                    $isOverdue = $loan->status === 'active' && $loan->due_date && now()->gt($loan->due_date);
                    $statusClass = $isOverdue ? 'overdue' : $loan->status;
                @endphp
                <span class="status-badge status-{{ $statusClass }}">
                    @if($isOverdue)
                        <i class="fas fa-exclamation-triangle"></i> Terlambat
                    @elseif($loan->status === 'pending')
                        <i class="fas fa-clock"></i> Menunggu Persetujuan
                    @elseif($loan->status === 'approved')
                        <i class="fas fa-key"></i> Siap Diambil
                    @elseif($loan->status === 'active')
                        <i class="fas fa-book-reader"></i> Sedang Dipinjam
                    @elseif($loan->status === 'returned')
                        <i class="fas fa-check-circle"></i> Sudah Dikembalikan
                    @else
                        {{ ucfirst($loan->status) }}
                    @endif
                </span>
            </div>

            <!-- User Information -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-user-circle"></i>
                    <h2>Data Peminjam</h2>
                </div>
                <div class="card-body">
                    <div class="user-info-section">
                        <div class="user-avatar">
                            @if($loan->user->profile_picture)
                                <img src="{{ asset('storage/' . $loan->user->profile_picture) }}" alt="{{ $loan->user->name }}">
                            @else
                                <span class="user-avatar-text">{{ strtoupper(substr($loan->user->name, 0, 1)) }}</span>
                            @endif
                        </div>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-user"></i> Nama Lengkap</span>
                                <span class="info-value">{{ $loan->user->name }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-envelope"></i> Email</span>
                                <span class="info-value secondary">{{ $loan->user->email }}</span>
                            </div>
                            @if($loan->user->phone)
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-phone"></i> Nomor Telepon</span>
                                <span class="info-value secondary">{{ $loan->user->phone }}</span>
                            </div>
                            @endif
                            @if($loan->user->address)
                            <div class="info-item">
                                <span class="info-label"><i class="fas fa-map-marker-alt"></i> Alamat</span>
                                <span class="info-value secondary">{{ $loan->user->address }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Information -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-book"></i>
                    <h2>Detail Buku</h2>
                </div>
                <div class="card-body">
                    <div class="book-info-section">
                        <div class="book-cover">
                            <img src="{{ route('books.cover', $loan->book->id) }}" alt="{{ $loan->book->title }}">
                        </div>
                        <div class="book-details-info">
                            <h3 class="book-title">{{ $loan->book->title }}</h3>
                            <p class="book-author"><i class="fas fa-user-edit"></i> Penulis: {{ $loan->book->author }}</p>
                            <div class="book-meta">
                                <div class="meta-item">
                                    <span class="meta-label">Penerbit</span>
                                    <span class="meta-value">{{ $loan->book->publisher ?? 'Tidak Diketahui' }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Tahun Terbit</span>
                                    <span class="meta-value">{{ $loan->book->publication_year ?? $loan->book->year ?? 'Tidak Diketahui' }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Halaman</span>
                                    <span class="meta-value">{{ $loan->book->pages ?? 'Tidak Diketahui' }}</span>
                                </div>
                                <div class="meta-item">
                                    <span class="meta-label">Kategori</span>
                                    <span class="meta-value">{{ $loan->book->category ?? 'Tidak Diketahui' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loan Timeline -->
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-calendar-alt"></i>
                    <h2>Jadwal Peminjaman</h2>
                </div>
                <div class="card-body">
                    <div class="timeline-section">
                        <div class="timeline-item">
                            <div class="timeline-date">
                                <i class="fas fa-arrow-up"></i>
                                {{ $loan->borrow_date ? \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') : '-' }}
                            </div>
                            <div>
                                <div class="timeline-label">Tanggal Peminjaman</div>
                                <small style="color: #999;">Tanggal mulai peminjaman buku</small>
                            </div>
                        </div>

                        <div class="timeline-item">
                            <div class="timeline-date">
                                <i class="fas fa-clock"></i>
                                {{ $loan->due_date ? \Carbon\Carbon::parse($loan->due_date)->format('d M Y') : '-' }}
                            </div>
                            <div>
                                <div class="timeline-label">Jatuh Tempo</div>
                                <small style="color: #999;">Tanggal deadline pengembalian</small>
                            </div>
                        </div>

                        @if($loan->return_date)
                        <div class="timeline-item">
                            <div class="timeline-date">
                                <i class="fas fa-arrow-down"></i>
                                {{ \Carbon\Carbon::parse($loan->return_date)->format('d M Y') }}
                            </div>
                            <div>
                                <div class="timeline-label">Tanggal Pengembalian</div>
                                <small style="color: #999;">Buku telah dikembalikan</small>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Duration Info -->
                    @if($loan->borrow_date && $loan->due_date)
                    @php
                        $borrowDate = \Carbon\Carbon::parse($loan->borrow_date);
                        $dueDate = \Carbon\Carbon::parse($loan->due_date);
                        $loanDuration = $borrowDate->diffInDays($dueDate);
                        
                        $currentDate = $loan->return_date ? \Carbon\Carbon::parse($loan->return_date) : now();
                        $daysPassed = $borrowDate->diffInDays($currentDate);
                        $progressPercentage = $loanDuration > 0 ? min(100, ($daysPassed / $loanDuration) * 100) : 100;
                    @endphp
                    
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $progressPercentage }}%"></div>
                        </div>
                        <div class="progress-labels">
                            <span>Mulai: {{ $borrowDate->format('d M Y') }}</span>
                            <span>Jatuh Tempo: {{ $dueDate->format('d M Y') }}</span>
                        </div>
                    </div>

                    <div class="duration-info">
                        <div class="duration-card">
                            <div class="duration-label">Durasi Peminjaman</div>
                            <div class="duration-value">{{ $loanDuration }}</div>
                            <small>hari</small>
                        </div>
                        
                        <div class="duration-card">
                            <div class="duration-label">Sudah Berlalu</div>
                            <div class="duration-value">{{ $daysPassed }}</div>
                            <small>hari</small>
                        </div>
                        
                        @if($loan->return_date)
                        <div class="duration-card">
                            <div class="duration-label">Durasi Aktual</div>
                            <div class="duration-value">
                                {{ $borrowDate->diffInDays(\Carbon\Carbon::parse($loan->return_date)) }}
                            </div>
                            <small>hari</small>
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Fine Section -->
                    @if($loan->fine > 0)
                    <div class="fine-badge">
                        <i class="fas fa-exclamation-triangle"></i>
                        Denda: Rp {{ number_format($loan->fine, 0, ',', '.') }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Status Alerts -->
            @if($loan->status === 'pending')
            <div class="alert-box alert-warning">
                <i class="fas fa-hourglass-half"></i>
                <div class="alert-content">
                    <strong>Status: Menunggu Persetujuan</strong>
                    <p>Permintaan peminjaman Anda sedang ditinjau. Anda akan mendapat notifikasi saat ada keputusan dari admin. Estimasi waktu: 1-2 hari kerja.</p>
                </div>
            </div>
            @elseif($loan->status === 'approved')
            <div class="alert-box alert-success">
                <i class="fas fa-check-circle"></i>
                <div class="alert-content">
                    <strong>Status: Siap Diambil</strong>
                    <p>Permintaan Anda telah disetujui! Silakan datang ke perpustakaan dengan kode yang sudah diberikan di bawah.</p>
                </div>
            </div>
            @elseif($loan->status === 'active')
                @if($isOverdue)
                <div class="alert-box alert-danger">
                    <i class="fas fa-exclamation-triangle"></i>
                    <div class="alert-content">
                        <strong>Status: Terlambat!</strong>
                        <p>Buku belum dikembalikan melewati tanggal jatuh tempo. Silakan kembalikan segera untuk menghindari denda yang lebih besar.</p>
                    </div>
                </div>
                @else
                <div class="alert-box alert-success">
                    <i class="fas fa-book-reader"></i>
                    <div class="alert-content">
                        <strong>Status: Sedang Dipinjam</strong>
                        <p>Buku sedang Anda pinjam. Mohon kembalikan sebelum tanggal jatuh tempo: <strong>{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</strong></p>
                    </div>
                </div>
                @endif
            @elseif($loan->status === 'returned')
            <div class="alert-box alert-success">
                <i class="fas fa-check-double"></i>
                <div class="alert-content">
                    <strong>Status: Sudah Dikembalikan</strong>
                    <p>Terima kasih telah mengembalikan buku! Anda bisa membaca ulasan atau meminjam buku lainnya.</p>
                </div>
            </div>
            @endif

            <!-- Pickup/Return Code Section -->
            @if(in_array($loan->status, ['approved', 'active']))
            <div class="pickup-code-section" style="@if($loan->status === 'active') background: linear-gradient(135deg, #ff9a00 0%, #ff6a00 100%); @endif">
                <p class="pickup-code-label">
                    <i class="fas fa-barcode"></i>
                    {{ $loan->status === 'approved' ? 'Kode Pengambilan Buku' : 'Kode Pengembalian Buku' }}
                </p>
                <div class="pickup-code" id="loanCode">
                    {{ $loan->barcode }}
                    <span class="copy-success" id="copySuccess">Kode disalin!</span>
                </div>
                <p class="pickup-code-info">
                    @if($loan->status === 'approved')
                    Salin kode di atas dan tunjukkan ke petugas saat Anda mengambil buku di perpustakaan.
                    @else
                    Salin kode di atas dan tunjukkan ke petugas saat Anda mengembalikan buku. Petugas akan memverifikasi kode ini.
                    @endif
                </p>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center; margin-top: 1rem;">
                    <button class="pickup-code-button" onclick="copyCodeToClipboard()">
                        <i class="fas fa-copy"></i> Salin Kode
                    </button>
                </div>
            </div>
            @endif

            <!-- Return Book Instructions for Active Loans -->
            @if($loan->status === 'active')
            <div class="return-section">
                <div class="return-header">
                    <i class="fas fa-arrow-circle-down"></i>
                    <h2>Kembalikan Buku ke Perpustakaan</h2>
                </div>
                <div class="return-body">
                    <p style="margin: 0 0 1.5rem 0; opacity: 0.9; line-height: 1.6;">
                        Silakan mengikuti langkah-langkah di bawah untuk mengembalikan buku dengan lancar.
                    </p>
                    
                    <div style="background: rgba(255, 255, 255, 0.15); padding: 1.5rem; border-radius: 10px; margin-bottom: 1.5rem; backdrop-filter: blur(5px);">
                        <p style="margin: 0; font-size: 13px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; opacity: 0.9;">
                            <i class="fas fa-tasks"></i> Langkah Pengembalian
                        </p>
                        <ol style="margin: 0.75rem 0 0 0; font-size: 14px; line-height: 1.8; padding-left: 1.5rem;">
                            <li>Datang ke perpustakaan dengan membawa buku</li>
                            <li>Tunjukkan <strong>kode pengembalian di atas</strong> kepada petugas</li>
                            <li>Petugas akan memverifikasi kode dan kondisi buku</li>
                            <li>Jika tepat waktu, tidak ada denda</li>
                            <li>Klik tombol di bawah untuk konfirmasi pengembalian</li>
                        </ol>
                    </div>
                    
                    <form method="POST" action="{{ route('loans.return', $loan) }}" id="returnForm" onsubmit="return confirmReturn()">
                        @csrf
                        <button type="submit" class="btn btn-success" style="width: 100%; padding: 16px; font-size: 16px;">
                            <i class="fas fa-check"></i> Konfirmasi Pengembalian Buku
                        </button>
                    </form>
                </div>
            </div>
            @endif

            <!-- Additional Information for Approved Loans -->
            @if($loan->status === 'approved')
            <div class="section-card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i>
                    <h2>Instruksi Pengambilan Buku</h2>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem;">
                        <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 8px;">
                            <h4 style="margin: 0 0 1rem 0; color: var(--primary-color);"><i class="fas fa-map-marker-alt"></i> Lokasi</h4>
                            <p style="margin: 0; font-size: 14px;">Perpustakaan Utama, Lantai 1, Gedung A</p>
                        </div>
                        <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 8px;">
                            <h4 style="margin: 0 0 1rem 0; color: var(--primary-color);"><i class="fas fa-clock"></i> Jam Operasional</h4>
                            <p style="margin: 0; font-size: 14px;">Senin-Jumat: 08:00 - 17:00<br>Sabtu: 08:00 - 15:00</p>
                        </div>
                        <div style="padding: 1.5rem; background: #f8f9fa; border-radius: 8px;">
                            <h4 style="margin: 0 0 1rem 0; color: var(--primary-color);"><i class="fas fa-user-check"></i> Persyaratan</h4>
                            <p style="margin: 0; font-size: 14px;">Membawa kartu identitas atau kartu perpustakaan</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- PDF Footer (Hanya tampil saat print) -->
            <div class="pdf-footer" style="display: none;">
                <div class="section-divider"></div>
                <p style="font-size: 9pt; color: #666; margin-bottom: 5mm;">
                    <strong>Catatan:</strong> Dokumen ini dicetak secara otomatis dari sistem LibraryID. Validitas dokumen dapat diverifikasi dengan kode peminjaman.
                </p>
                <div class="signature">
                    <div>
                        <div class="signature-line"></div>
                        <p style="margin-top: 2mm;">Tanda Tangan Peminjam</p>
                        <p style="font-size: 8pt; color: #999;">{{ $loan->user->name }}</p>
                    </div>
                    <div>
                        <div class="signature-line"></div>
                        <p style="margin-top: 2mm;">Tanda Tangan Petugas</p>
                        <p style="font-size: 8pt; color: #999;">Perpustakaan LibraryID</p>
                    </div>
                </div>
                <p style="margin-top: 5mm; font-size: 8pt; color: #999;">
                    Dicetak pada: {{ now()->format('d M Y H:i:s') }} | ID Peminjaman: {{ $loan->id }}
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="action-section">
                @if($loan->status === 'pending')
                    <form method="POST" action="{{ route('loans.cancel', $loan) }}" id="cancelForm">
                        @csrf
                        <button type="button" class="btn btn-danger" onclick="confirmCancel()">
                            <i class="fas fa-times"></i> Batalkan Permintaan
                        </button>
                    </form>
                    
                    <!-- Request Extension for Active Loans -->
                    @elseif($loan->status === 'active' && !$isOverdue)
                    <button type="button" class="btn btn-warning" onclick="requestExtension()">
                        <i class="fas fa-calendar-plus"></i> Ajukan Perpanjangan
                    </button>
                    
                    <!-- Pay Fine Button for Overdue Loans -->
                    @elseif($isOverdue)
                    <button type="button" class="btn btn-danger" onclick="payFine()">
                        <i class="fas fa-money-bill-wave"></i> Bayar Denda
                    </button>
                    
                    <!-- Rate Book for Returned Loans -->
                    @elseif($loan->status === 'returned' && !$loan->hasReview())
                    <button type="button" class="btn btn-primary" onclick="rateBook()">
                        <i class="fas fa-star"></i> Beri Rating Buku
                    </button>
                @endif
                
                <!-- Always Show Print Button -->
                <button type="button" class="btn btn-primary" onclick="triggerPrint()" title="Cetak atau simpan sebagai PDF (Ctrl+P)">
                    <i class="fas fa-print"></i> Cetak / Simpan PDF
                </button>
                
                <!-- Admin Actions (if user is admin) -->
                @if(auth()->user()->isAdmin())
                    <div style="width: 100%; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--light-gray);">
                        <h4 style="margin-bottom: 0.5rem; color: var(--gray-color); font-size: 14px;">Admin Actions</h4>
                        <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            @if($loan->status === 'pending')
                                <form method="POST" action="{{ route('admin.loans.approve', $loan) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success" style="padding: 8px 16px; font-size: 13px;">
                                        <i class="fas fa-check"></i> Setujui
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.loans.reject', $loan) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" style="padding: 8px 16px; font-size: 13px;">
                                        <i class="fas fa-times"></i> Tolak
                                    </button>
                                </form>
                            @endif
                            
                            @if($loan->status === 'active')
                                <button type="button" class="btn btn-warning" style="padding: 8px 16px; font-size: 13px;" onclick="markAsLost()">
                                    <i class="fas fa-exclamation-triangle"></i> Tandai Hilang
                                </button>
                            @endif
                            
                            <a href="{{ route('admin.loans.edit', $loan) }}" class="btn btn-primary" style="padding: 8px 16px; font-size: 13px;">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Modal Detail Peminjaman -->
    <div id="loanDetailModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1001; align-items: center; justify-content: center; overflow-y: auto; padding: 2rem 0; backdrop-filter: blur(2px);">
        <div style="background: white; margin: auto; border-radius: 16px; width: 95%; max-width: 750px; box-shadow: 0 25px 50px rgba(0,0,0,0.3); animation: slideUp 0.3s ease;">
            <!-- Modal Header -->
            <div style="background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%); padding: 2.5rem 2rem; border-radius: 16px 16px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="margin: 0 0 0.25rem 0; color: white; font-size: 1.8rem; font-weight: 700;">📋 Detail Peminjaman</h2>
                    <p style="margin: 0; color: rgba(255,255,255,0.9); font-size: 0.9rem;">Konfirmasi dari sistem LibraryID</p>
                </div>
                <button onclick="closeLoanDetailModal()" style="background: rgba(255,255,255,0.2); border: none; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; color: white; font-size: 1.5rem; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">✕</button>
            </div>
            
            <!-- Modal Content (Printable) -->
            <div id="printableContent" style="padding: 2.5rem;">
                <!-- Header Section -->
                <div style="text-align: center; margin-bottom: 2.5rem; padding-bottom: 2rem; border-bottom: 3px solid var(--primary-color);">
                    <div style="background: linear-gradient(135deg, #4361ee10, #3a56d410); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem;">
                        <h3 style="margin: 0 0 0.5rem 0; color: var(--primary-color); font-size: 1.4rem; font-weight: 700;">✓ PEMINJAMAN BERHASIL</h3>
                        <p style="margin: 0; color: #666; font-size: 0.95rem;">Dokumen ini adalah bukti resmi peminjaman buku Anda</p>
                    </div>
                    <p style="margin: 0; color: #999; font-size: 0.85rem;">Tanggal Pembuatan: {{ now()->format('d F Y, H:i') }}</p>
                </div>
                
                <!-- Detail Peminjaman -->
                <div style="margin-bottom: 2.5rem; background: #f8f9fe; padding: 1.5rem; border-radius: 12px; border-left: 4px solid var(--primary-color);">
                    <h4 style="color: var(--primary-color); margin: 0 0 1.25rem 0; font-size: 1.05rem; font-weight: 600;">📋 DETAIL PEMINJAMAN</h4>
                    <div style="display: grid; gap: 1rem;">
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #555;">Kode Barcode:</span>
                            <span style="color: #333; font-family: 'Courier New', monospace; font-weight: 500; font-size: 0.95rem; background: white; padding: 0.5rem 0.75rem; border-radius: 6px; border: 1px solid #ddd;">{{ $loan->barcode }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #555;">Status:</span>
                            <span style="display: inline-block; width: fit-content; padding: 0.4rem 1rem; border-radius: 20px; font-size: 0.9rem; font-weight: 600;
                                @if($loan->status === 'pending')
                                    background: #fff3cd; color: #856404;
                                @elseif($loan->status === 'approved')
                                    background: #cfe2ff; color: #084298;
                                @elseif($loan->status === 'active')
                                    background: #d1ecf1; color: #0c5460;
                                @elseif($loan->status === 'returned')
                                    background: #d4edda; color: #155724;
                                @endif
                            ">{{ ucfirst($loan->status) }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #555;">Dari Tanggal:</span>
                            <span style="color: #333;">{{ \Carbon\Carbon::parse($loan->borrow_date)->format('d F Y') }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #555;">Sampai Tanggal:</span>
                            <span style="color: #333;">{{ \Carbon\Carbon::parse($loan->due_date)->format('d F Y') }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem; padding-top: 0.5rem; border-top: 2px solid rgba(67,97,238,0.2);">
                            <span style="font-weight: 600; color: var(--primary-color);">⏱ Durasi:</span>
                            <span style="color: var(--primary-color); font-weight: 600; font-size: 1.05rem;">
                                @php
                                    $days = \Carbon\Carbon::parse($loan->borrow_date)->diffInDays(\Carbon\Carbon::parse($loan->due_date));
                                @endphp
                                {{ $days }} hari
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Detail User -->
                <div style="margin-bottom: 2.5rem; background: #f0f8ff; padding: 1.5rem; border-radius: 12px; border-left: 4px solid #4cc9f0;">
                    <h4 style="color: #0c5460; margin: 0 0 1.25rem 0; font-size: 1.05rem; font-weight: 600;">👤 DATA PEMINJAM</h4>
                    <div style="display: grid; gap: 1rem;">
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #0c5460;">Nama Lengkap:</span>
                            <span style="color: #333;">{{ $loan->user->name }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #0c5460;">Email:</span>
                            <span style="color: #333; word-break: break-all;">{{ $loan->user->email }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #0c5460;">No. Identitas:</span>
                            <span style="color: #333;">{{ $loan->user->identity_number ?? '—' }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #0c5460;">Telepon:</span>
                            <span style="color: #333;">{{ $loan->user->phone ?? '—' }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Detail Buku -->
                <div style="margin-bottom: 2.5rem; background: #fffaf0; padding: 1.5rem; border-radius: 12px; border-left: 4px solid #f8961e;">
                    <h4 style="color: #856404; margin: 0 0 1.25rem 0; font-size: 1.05rem; font-weight: 600;">📚 DATA BUKU</h4>
                    <div style="display: grid; gap: 1rem;">
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #856404;">Judul:</span>
                            <span style="color: #333; font-weight: 500;">{{ $loan->book->title }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #856404;">Pengarang:</span>
                            <span style="color: #333;">{{ $loan->book->author ?? '—' }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #856404;">Penerbit:</span>
                            <span style="color: #333;">{{ $loan->book->publisher ?? '—' }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #856404;">ISBN:</span>
                            <span style="color: #333; font-family: 'Courier New', monospace;">{{ $loan->book->isbn ?? '—' }}</span>
                        </div>
                        <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                            <span style="font-weight: 600; color: #856404;">Tahun:</span>
                            <span style="color: #333;">{{ $loan->book->year ?? '—' }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Footer Note -->
                <div style="background: linear-gradient(135deg, rgba(67,97,238,0.05), rgba(114,9,183,0.05)); padding: 2rem; border-radius: 12px; margin-top: 2rem; text-align: center; color: #666; font-size: 0.9rem; line-height: 1.8; border-top: 2px solid var(--primary-color);">
                    <p style="margin: 0 0 0.75rem 0; font-weight: 500; color: #333;">✓ Dokumen telah dibuat dan terverifikasi</p>
                    <p style="margin: 0 0 0.75rem 0;">Simpan atau cetak dokumen ini sebagai bukti resmi peminjaman buku.</p>
                    <p style="margin: 0; color: #999; font-size: 0.85rem;">Jika ada pertanyaan, hubungi admin perpustakaan.</p>
                </div>
            </div>
            
            <!-- Modal Footer (Not Printable) -->
            <div style="display: flex; gap: 1rem; padding: 2rem; border-top: 2px solid #f0f0f0; justify-content: flex-end; background: linear-gradient(to right, #f8f9fa, #ffffff);">
                <button type="button" class="modal-btn btn-primary-modal" onclick="printLoanDetail(); return false;">
                    <i class="fas fa-download"></i> Download PDF
                </button>
                <button type="button" class="modal-btn btn-secondary-modal" onclick="closeLoanDetailModal(); return false;">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
    
    
    
    <!-- Notification Popup -->
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
    
    <!-- Modal for Extensions -->
    <div id="extensionModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
        <div style="background: white; padding: 2rem; border-radius: var(--border-radius); max-width: 500px; width: 90%;">
            <h3 style="margin-bottom: 1rem;">Ajukan Perpanjangan Peminjaman</h3>
            <form id="extensionForm" method="POST" action="{{ route('loans.extend', $loan) }}">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Tanggal Pengembalian Baru</label>
                    <input type="date" name="new_due_date" required style="width: 100%; padding: 0.75rem; border: 1px solid var(--light-gray); border-radius: 8px;">
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Alasan Perpanjangan</label>
                    <textarea name="reason" rows="3" style="width: 100%; padding: 0.75rem; border: 1px solid var(--light-gray); border-radius: 8px;"></textarea>
                </div>
                <div style="display: flex; gap: 1rem;">
                    <button type="submit" class="btn btn-primary">Ajukan</button>
                    <button type="button" class="btn btn-danger" onclick="closeExtensionModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Define notification routes
        const notificationRoutes = {
            get: '{{ route("notifications.get") }}',
            markRead: '{{ route("notifications.mark-read") }}',
            csrfToken: '{{ csrf_token() }}',
            loansHistory: '{{ route("loans.history") }}'
        };

        // Define user's loan data
        const userLoansData = {
            overdueCount: {{ isset($overdueLoans) ? $overdueLoans->count() : 0 }},
            pendingCount: {{ isset($pendingLoans) ? $pendingLoans->count() : 0 }},
            activeCount: {{ isset($activeLoans) ? $activeLoans->count() : 0 }}
        };

        // Copy code to clipboard
        function copyCodeToClipboard() {
            const code = document.getElementById('loanCode').textContent.trim();
            navigator.clipboard.writeText(code).then(() => {
                const copySuccess = document.getElementById('copySuccess');
                copySuccess.classList.add('show');
                setTimeout(() => {
                    copySuccess.classList.remove('show');
                }, 2000);
            });
        }

        // Confirm return
        function confirmReturn() {
            @if($isOverdue)
            const fine = {{ $loan->fine > 0 ? $loan->fine : 'calculateFine()' }};
            return confirm(`Buku terlambat dikembalikan. Denda yang harus dibayar: Rp ${formatNumber(fine)}. Lanjutkan pengembalian?`);
            @else
            return confirm('Konfirmasi pengembalian buku ini?');
            @endif
        }

        // Confirm cancel
        function confirmCancel() {
            if (confirm('Batalkan permintaan peminjaman ini?')) {
                document.getElementById('cancelForm').submit();
            }
        }

        // Format number with commas
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Calculate fine for overdue loans
        function calculateFine() {
            @if($loan->due_date && now()->gt($loan->due_date))
                const daysOverdue = Math.floor((new Date() - new Date('{{ $loan->due_date }}')) / (1000 * 60 * 60 * 24));
                return daysOverdue * 3000; // Rp 3,000 per day
            @endif
            return 0;
        }

        // Request extension modal
        function requestExtension() {
            const modal = document.getElementById('extensionModal');
            modal.style.display = 'flex';
            
            // Set minimum date for extension (next day)
            const tomorrow = new Date();
            tomorrow.setDate(tomorrow.getDate() + 1);
            const minDate = tomorrow.toISOString().split('T')[0];
            
            // Set maximum date (30 days from now)
            const maxDate = new Date();
            maxDate.setDate(maxDate.getDate() + 30);
            const maxDateStr = maxDate.toISOString().split('T')[0];
            
            const dateInput = document.querySelector('input[name="new_due_date"]');
            dateInput.min = minDate;
            dateInput.max = maxDateStr;
            dateInput.value = minDate;
        }

        function closeExtensionModal() {
            document.getElementById('extensionModal').style.display = 'none';
        }

        // Loan Detail Modal Functions
        function showLoanDetailModal() {
            document.getElementById('loanDetailModal').style.display = 'flex';
        }

        function closeLoanDetailModal() {
            document.getElementById('loanDetailModal').style.display = 'none';
        }

        function printLoanDetail() {
            // Get the printable content
            const element = document.getElementById('printableContent');
            const loanBarcode = '{{ $loan->barcode }}';
            const fileName = `Peminjaman_${loanBarcode}_${new Date().getTime()}`;
            
            // Options for html2pdf
            const options = {
                margin: 15,
                filename: fileName + '.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2 },
                jsPDF: { format: 'a4', orientation: 'portrait' }
            };
            
            // Create wrapper div with styles
            const wrapper = document.createElement('div');
            wrapper.innerHTML = `
                <div style="font-family: 'Poppins', Arial, sans-serif; padding: 20px; background: white;">
                    ${element.innerHTML}
                </div>
            `;
            
            // Generate PDF and download
            html2pdf().set(options).from(wrapper).save();
        }

        // Auto-show modal if session has showDetailModal flag
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('showDetailModal'))
                showLoanDetailModal();
            @endif
        });

        // Close modal when clicking outside
        document.getElementById('loanDetailModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeLoanDetailModal();
            }
        });

        // Pay fine function
        function payFine() {
            const fine = calculateFine();
            if (confirm(`Bayar denda sebesar Rp ${formatNumber(fine)}?`)) {
                // Here you would typically redirect to payment page
                alert('Redirecting to payment page...');
            }
        }

        // Rate book function
        function rateBook() {
            alert('Fitur rating buku akan segera tersedia!');
        }

        // Mark as lost (admin function)
        function markAsLost() {
            if (confirm('Tandai buku ini sebagai hilang? Ini akan menambahkan denda khusus.')) {
                // Submit form to mark as lost
                fetch('{{ route("admin.loans.mark-lost", $loan) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                }).then(response => {
                    if (response.ok) {
                        alert('Buku ditandai sebagai hilang. Halaman akan direfresh.');
                        location.reload();
                    }
                });
            }
        }

        // Trigger print dialog - works like Ctrl+P
        function triggerPrint() {
            // Langsung buka dialog print seperti Ctrl+P
            window.print();
        }

        // Prepare for print/PDF (legacy - tidak digunakan lagi)
        function prepareForPrint() {
            // Langsung buka dialog print seperti Ctrl+P
            window.print();
        }

        // Auto-refresh page every 5 minutes for pending/approved loans
        @if(in_array($loan->status, ['pending', 'approved']))
        setTimeout(() => {
            location.reload();
        }, 5 * 60 * 1000);
        @endif

        // Show loading state on form submission
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.innerHTML = '<div class="loader"></div>';
                        submitBtn.disabled = true;
                    }
                });
            });
        });
    </script>
    
    <!-- HTML2PDF Library for PDF Export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    
    <script src="{{ asset('js/user-notifications.js') }}"></script>
    <script src="{{ asset('js/user-sidebar.js') }}"></script>
</body>
</html>
