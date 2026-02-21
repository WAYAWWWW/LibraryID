<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wishlist - LibraryID</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-sidebar.css') }}">
    
    
    <link rel="stylesheet" href="{{ asset('css/wishlist-index.css') }}">
</head>
<body class="sidebar-page">
    @include('partials.user-sidebar')
    <!-- ===== Navbar ===== -->


    <!-- Main Content -->
    <div class="wishlist-container content-with-sidebar">
        <div class="wishlist-header">
            <h1>❤️ Wishlist Saya</h1>
            <p>Buku-buku yang ingin Anda baca</p>
        </div>

        @if($wishlistBooks->isEmpty())
            <div class="empty-state">
                <i class="fas fa-heart-broken"></i>
                <h2>Wishlist Anda Kosong</h2>
                <p>Tambahkan buku ke wishlist untuk menyimpan buku favorit Anda</p>
                <a href="{{ route('books.index') }}">Jelajahi Buku</a>
            </div>
        @else
            <div class="wishlist-grid">
                @foreach($wishlistBooks as $book)
                    <div class="wishlist-card">
                        <div class="wishlist-book-cover">
                            <img src="{{ route('books.cover', $book->id) }}" alt="{{ $book->title }}">
                            <button class="wishlist-remove-btn" onclick="removeFromWishlist({{ $book->id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="wishlist-book-info">
                            <h3 class="wishlist-book-title">{{ $book->title }}</h3>
                            <p class="wishlist-book-author">{{ $book->author }}</p>
                            <div class="wishlist-book-actions">
                                <a href="{{ route('books.show', $book->id) }}" class="btn-view-detail">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        function removeFromWishlist(bookId) {
            if (confirm('Hapus buku dari wishlist?')) {
                fetch(`/wishlist/${bookId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload page
                        location.reload();
                    } else {
                        alert('Gagal menghapus dari wishlist');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan');
                });
            }
        }
    </script>
    <script src="{{ asset('js/user-sidebar.js') }}"></script>
</body>
</html>
