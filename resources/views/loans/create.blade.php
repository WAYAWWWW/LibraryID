<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Buat Peminjaman - LibraryID</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/book.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/loans-create.css') }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif
    
</head>
<body class="sidebar-page">
    @include('partials.user-sidebar')
    <!-- ===== Navbar ===== -->


    <!-- Main Content -->
    <main class="dashboard-container content-with-sidebar">
        <div class="loan-form-container">

            <h2 style="margin-bottom: 10px; color: #333;">Buat Peminjaman Buku</h2>
            <p style="color: #666; margin-bottom: 20px;">Isi form di bawah untuk membuat permintaan peminjaman buku</p>

            @if($book)
                <div class="book-info-card">
                    <h4>{{ $book->title }}</h4>
                    <p><strong>Penulis:</strong> {{ $book->author }}</p>
                    <p><strong>Penerbit:</strong> {{ $book->publisher ?? '-' }}</p>
                    <p><strong>Stok Tersedia:</strong> {{ $book->stock > 0 ? $book->stock . ' buku' : 'Habis' }}</p>
                </div>
            @endif

            @if (auth()->user()->hasActiveLoan())
                <div class="alert alert-danger">
                    <strong>⚠️ Tidak dapat meminjam buku!</strong>
                    <p style="margin: 10px 0 0 0;">Anda masih memiliki {{ auth()->user()->getActiveLoanCount() }} peminjaman aktif yang belum selesai. Harap kembalikan buku sebelumnya terlebih dahulu sebelum meminjam buku lain.</p>
                    <p style="margin: 10px 0 0 0;"><a href="{{ route('loans.history') }}" style="color: #721c24; text-decoration: underline; font-weight: 600;">Lihat peminjaman saya →</a></p>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Terjadi kesalahan:</strong>
                    <ul style="margin: 10px 0 0 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="alert alert-info">
                <strong>ℹ Informasi Penting:</strong>
                <ul style="margin: 10px 0 0 20px; padding: 0;">
                    <li>Peminjam harus menjaga buku dengan baik dan mengembalikannya tepat waktu</li>
                    <li>Apabila peminjam menghilangkan buku/telat mengembalikan buku, peminjam harus membayar denda</li>
                    <li>Tanggal pengembalian maksimal 14 hari dari tanggal peminjaman</li>
                    <li>Permintaan peminjaman akan diproses oleh admin/petugas</li>
                </ul>
            </div>

            <form method="POST" action="{{ route('loans.store', $book->id) }}" id="loanForm" @if(auth()->user()->hasActiveLoan()) style="opacity: 0.6; pointer-events: none;" @endif>
                @csrf

                <div class="form-group">
                    <label for="borrow_date" class="form-label">Tanggal Peminjaman <span style="color: red;">*</span></label>
                    <input 
                        type="date" 
                        id="borrow_date" 
                        name="borrow_date" 
                        class="form-control @error('borrow_date') is-invalid @enderror"
                        value="{{ old('borrow_date') }}"
                        required
                    >
                    <p class="help-text">Pilih tanggal hari ini atau tanggal di masa depan</p>
                    @error('borrow_date')
                        <span class="text-danger" style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="due_date" class="form-label">Tanggal Pengembalian <span style="color: red;">*</span></label>
                    <input 
                        type="date" 
                        id="due_date" 
                        name="due_date" 
                        class="form-control @error('due_date') is-invalid @enderror"
                        value="{{ old('due_date') }}"
                        required
                    >
                    <p class="help-text">Maksimal 14 hari dari tanggal peminjaman</p>
                    @error('due_date')
                        <span class="text-danger" style="color: red; font-size: 12px;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-submit">Buat Permintaan Peminjaman</button>
            </form>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const borrowDateInput = document.getElementById('borrow_date');
            const dueDateInput = document.getElementById('due_date');
            const loanForm = document.getElementById('loanForm');

            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            borrowDateInput.setAttribute('min', today);
            borrowDateInput.value = borrowDateInput.value || today;

            // Update due date constraints when borrow date changes
            borrowDateInput.addEventListener('change', function() {
                const borrowDate = new Date(this.value);
                
                // Set min due date to borrow date
                const minDueDate = new Date(borrowDate);
                borrowDateInput.setAttribute('min', today);

                // Set max due date to 14 days after borrow date
                const maxDueDate = new Date(borrowDate);
                maxDueDate.setDate(maxDueDate.getDate() + 14);

                dueDateInput.setAttribute('min', this.value);
                dueDateInput.setAttribute('max', maxDueDate.toISOString().split('T')[0]);

                // Update due date to minimum if it's before borrow date
                if (dueDateInput.value < this.value) {
                    dueDateInput.value = this.value;
                }
                // Update due date to maximum if it exceeds 14 days
                if (dueDateInput.value > maxDueDate.toISOString().split('T')[0]) {
                    dueDateInput.value = maxDueDate.toISOString().split('T')[0];
                }
            });

            // Trigger initial constraints
            borrowDateInput.dispatchEvent(new Event('change'));

            // Form validation
            loanForm.addEventListener('submit', function(e) {
                const borrowDate = new Date(borrowDateInput.value);
                const dueDate = new Date(dueDateInput.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);

                // Validate borrow date is not in the past
                if (borrowDate < today) {
                    e.preventDefault();
                    alert('Tanggal peminjaman tidak boleh mundur!');
                    borrowDateInput.focus();
                    return false;
                }

                // Validate due date
                if (dueDate < borrowDate) {
                    e.preventDefault();
                    alert('Tanggal pengembalian harus setelah tanggal peminjaman!');
                    dueDateInput.focus();
                    return false;
                }

                // Validate max 14 days
                const daysDiff = Math.floor((dueDate - borrowDate) / (1000 * 60 * 60 * 24));
                if (daysDiff > 14) {
                    e.preventDefault();
                    alert('Tanggal pengembalian maksimal 14 hari dari tanggal peminjaman!');
                    dueDateInput.focus();
                    return false;
                }
            });
        });
    </script>
    <script src="{{ asset('js/user-sidebar.js') }}"></script>
</body>
</html>
