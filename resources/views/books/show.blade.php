<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $book->title }} - LibraryID</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bookdetail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-sidebar.css') }}">
</head>
<body class="sidebar-page">
    @include('partials.user-sidebar')

    <!-- Main Content Area -->
    <div class="main-content content-with-sidebar">
        <div class="detail-container">
            <div class="book-detail-wrapper">
                <div class="book-detail-content">
                    <!-- Book Cover -->
                    <div class="book-cover-detail">
                        <img src="{{ route('books.cover', $book->id) }}" alt="{{ $book->title }}">
                    </div>

                    <!-- Book Info -->
                    <div class="book-info-detail">
                        <h1>{{ $book->title }}</h1>
                        
                        <!-- Author -->
                        <div class="book-author">Penulis : {{ $book->author ?? 'Tidak Diketahui' }}</div>

                        <!-- Genre Tags -->
                        <div class="genre-tags">
                            @if($book->category)
                                <span class="genre-tag">{{ strtoupper($book->category) }}</span>
                            @endif
                        </div>

                        <!-- Detail Buku Section -->
                        <div class="detail-buku-section">
                            <h2 class="detail-buku-title">Detail Buku</h2>
                            <div class="detail-buku-content">
                                <div class="detail-item">
                                    <span class="detail-label">Penerbit & Tahun terbit :</span>
                                    <span class="detail-value">{{ $book->publisher ?? 'Tidak Diketahui' }}, {{ $book->publication_year ?? $book->year ?? 'Tidak Diketahui' }}</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Halaman :</span>
                                    <span class="detail-value">{{ $book->pages ?? 'Tidak Diketahui' }} Halaman</span>
                                </div>
                                <div class="detail-item">
                                    <span class="detail-label">Jumlah Buku Tersedia :</span>
                                    <span class="detail-value">{{ $book->stock }} Buku</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            @if(auth()->check() && auth()->user()->hasActiveLoan())
                                <button class="btn-pinjam" disabled style="opacity: 0.5; cursor: not-allowed;" title="Anda masih memiliki peminjaman aktif">
                                    ⚠️ Selesaikan Peminjaman Lain
                                </button>
                                <div style="font-size: 14px; color: #dc3545; margin-top: 10px; padding: 10px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px;">
                                    <strong>Perhatian:</strong> Anda masih memiliki {{ auth()->user()->getActiveLoanCount() }} peminjaman aktif. Harap kembalikan buku sebelumnya terlebih dahulu.
                                </div>
                            @elseif($book->stock > 0)
                                <form action="{{ route('books.request', $book) }}" method="POST" style="flex: 1;">
                                    @csrf
                                    <button type="submit" class="btn-pinjam">Pinjam Buku</button>
                                </form>
                            @else
                                <button class="btn-pinjam" disabled style="opacity: 0.5; cursor: not-allowed;">Stok Habis</button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Divider Line -->
                <hr class="divider-line">

                <!-- Synopsis Section -->
                @if($book->synopsis || $book->description)
                    <div class="synopsis-section">
                        <h2 class="synopsis-title">Sinopsis</h2>
                        <p class="synopsis-text">{{ $book->synopsis ?? $book->description ?? 'Tidak ada sinopsis' }}</p>
                    </div>
                @endif

                <!-- Divider Line -->
                <hr class="divider-line">

                <!-- Reviews Section -->
                <div class="reviews-section">
                    <h2 class="reviews-title">Ulasan</h2>
                    <div class="reviews-list">
                        @forelse($reviews as $review)
                            <div class="review-item">
                                <div class="review-header">
                                    <img src="{{ asset('images/profile.png') }}" alt="User Avatar" class="review-avatar">
                                    <div class="review-user-info">
                                        <div class="review-username">{{ $review->user->name }}</div>
                                        <div class="review-rating">
                                            @for($i = 0; $i < $review->rating; $i++)
                                                <span class="star-sm">★</span>
                                            @endfor
                                            @for($i = $review->rating; $i < 5; $i++)
                                                <span class="star-sm" style="opacity: 0.3;">★</span>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <p class="review-text">{{ $review->comment }}</p>
                            </div>
                        @empty
                            <p style="text-align: center; color: #999; padding: 2rem;">Belum ada ulasan untuk buku ini</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Wishlist functionality
        document.querySelector('.btn-wishlist')?.addEventListener('click', function() {
            alert('Buku ditambahkan ke wishlist!');
        });
        
    </script>
    <script src="{{ asset('js/user-sidebar.js') }}"></script>
</body>
</html>
