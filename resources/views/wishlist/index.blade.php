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
    
    <style>
        .wishlist-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .wishlist-header {
            margin-bottom: 2rem;
        }

        .wishlist-header h1 {
            font-size: 2rem;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .wishlist-header p {
            color: #64748b;
            font-size: 1rem;
        }

        .wishlist-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .wishlist-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .wishlist-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
        }

        .wishlist-book-cover {
            width: 100%;
            height: 280px;
            background: #f1f5f9;
            overflow: hidden;
            position: relative;
        }

        .wishlist-book-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .wishlist-remove-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(220, 52, 69, 0.9);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            transition: background 0.3s ease;
        }

        .wishlist-remove-btn:hover {
            background: rgba(220, 52, 69, 1);
        }

        .wishlist-book-info {
            padding: 1.5rem;
        }

        .wishlist-book-title {
            font-size: 1rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .wishlist-book-author {
            font-size: 0.875rem;
            color: #64748b;
            margin-bottom: 1rem;
        }

        .wishlist-book-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-view-detail {
            flex: 1;
            padding: 0.75rem;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            text-align: center;
            font-size: 0.9rem;
            transition: background 0.3s ease;
        }

        .btn-view-detail:hover {
            background: #2563eb;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #64748b;
        }

        .empty-state i {
            font-size: 3rem;
            color: #cbd5e1;
            margin-bottom: 1rem;
        }

        .empty-state h2 {
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .empty-state a {
            display: inline-block;
            margin-top: 1.5rem;
            padding: 0.75rem 2rem;
            background: #3b82f6;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .empty-state a:hover {
            background: #2563eb;
        }
    </style>
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
