<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Kelola Buku - LibraryID</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/petbuk.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-kelola-buku.css') }}">
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif

    
</head>
<body>
    <!-- ===== Navbar Admin ===== -->
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

    <!-- ===== Kelola Buku Container ===== -->
    <div class="kelola-buku-container">
        <div class="kelola-header">
            <h2>Kelola Buku</h2>
            <div class="search-section">
            <input type="text" id="searchInput" class="search-bar" placeholder="Cari buku..." />
        </div>

        <div class="books-grid">
            @forelse($books as $book)
                <div class="book-card" data-book-title="{{ strtolower($book->title) }}">
                    <div class="book-image-wrapper">
                        <img src="{{ route('books.cover', $book->id) }}" alt="{{ $book->title }}" class="book-image">
                    </div>
                    <div class="book-info">
                        <span class="book-code">{{ $book->id }}</span>
                        <span class="book-category">{{ $book->category }}</span>
                        <h3 class="book-title">{{ $book->title }}</h3>
                        <p class="book-author">Pengarang: {{ $book->author }}</p>
                        <p class="book-publisher">Penerbit: {{ $book->publisher }}</p>
                        <p class="book-year">Tahun: {{ $book->publication_year ?? $book->year }}</p>
                        <div class="book-rating" id="rating-{{ $book->id }}">
                            <span class="rating-label">Rating: </span>
                            <span class="rating-stars">⭐ <span class="rating-value">-</span></span>
                            <span class="rating-count">(<span class="review-count">0</span> ulasan)</span>
                        </div>
                        <div class="book-stock">
                            <span class="stock-label">Stok: {{ $book->stock }}</span>
                        </div>
                        <div class="book-actions">
                            <button class="action-btn edit-btn" data-book-id="{{ $book->id }}" onclick="openEditModal({{ $book->id }})">✎</button>
                            <form method="POST" action="{{ route('books.destroy', $book->id) }}" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus buku ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn delete-btn">🗑</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state-books">
                    <div class="empty-state-text">Belum ada buku yang ditambahkan</div>
                    <a href="{{ route('books.create') }}" class="btn-add-book">Tambah Buku Pertama</a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- ===== Edit Modal ===== -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Buku</h3>
                <button class="close-btn" onclick="closeEditModal()">&times;</button>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="editKodeBuku">Kode Buku</label>
                    <input type="text" id="editKodeBuku" name="id" readonly>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editKategori">Kategori</label>
                        <input type="text" id="editKategori" name="category" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="editJudulBuku">Judul Buku</label>
                    <input type="text" id="editJudulBuku" name="title" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editPengarang">Pengarang</label>
                        <input type="text" id="editPengarang" name="author" required>
                    </div>
                    <div class="form-group">
                        <label for="editPenerbit">Penerbit</label>
                        <input type="text" id="editPenerbit" name="publisher" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="editTahun">Tahun Terbit</label>
                        <input type="number" id="editTahun" name="publication_year">
                    </div>
                    <div class="form-group">
                        <label for="editStok">Stok</label>
                        <input type="number" id="editStok" name="stock" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="editURLGambar">URL Gambar</label>
                    <input type="url" id="editURLGambar" name="cover_image_url" placeholder="https://...">
                    <div class="image-preview">
                        <img id="editImagePreview" src="" alt="Preview" style="display: none;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn-save">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Load ratings for all books
        function loadBookRatings() {
            const books = document.querySelectorAll('.book-card');
            books.forEach(card => {
                const bookId = card.querySelector('.book-code').textContent;
                fetch(`/api/books/${bookId}/reviews`)
                    .then(response => response.json())
                    .then(data => {
                        const ratingElement = document.getElementById(`rating-${bookId}`);
                        if (ratingElement) {
                            const ratingValue = ratingElement.querySelector('.rating-value');
                            const reviewCount = ratingElement.querySelector('.review-count');
                            
                            if (data.averageRating > 0) {
                                ratingValue.textContent = data.averageRating.toFixed(1);
                                reviewCount.textContent = data.totalReviews;
                            } else {
                                ratingValue.textContent = 'Belum ada rating';
                                reviewCount.textContent = '0';
                            }
                        }
                    })
                    .catch(error => console.error('Error loading rating:', error));
            });
        }

        // Load ratings when page loads
        document.addEventListener('DOMContentLoaded', loadBookRatings);

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const bookCards = document.querySelectorAll('.book-card');

        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            bookCards.forEach(card => {
                const title = card.getAttribute('data-book-title');
                if (title.includes(searchTerm)) {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        // Modal functionality
        function openEditModal(bookId) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            
            // Fetch book data
            fetch(`/api/books/${bookId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editKodeBuku').value = data.id;
                    document.getElementById('editJudulBuku').value = data.title;
                    document.getElementById('editKategori').value = data.category || '';
                    document.getElementById('editPengarang').value = data.author || '';
                    document.getElementById('editPenerbit').value = data.publisher || '';
                    document.getElementById('editTahun').value = data.publication_year || data.year || '';
                    document.getElementById('editStok').value = data.stock || 0;
                    
                    // Clear URL field and show existing image if available
                    document.getElementById('editURLGambar').value = '';
                    const preview = document.getElementById('editImagePreview');
                    
                    if (data.cover_image) {
                        preview.src = `/storage/${data.cover_image}`;
                        preview.style.display = 'block';
                    } else {
                        preview.style.display = 'none';
                    }
                    
                    form.action = `/books/${bookId}`;
                    modal.style.display = 'block';
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Gagal memuat data buku');
                });
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('editModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Image preview
        document.getElementById('editURLGambar').addEventListener('change', function() {
            const url = this.value;
            const preview = document.getElementById('editImagePreview');
            if (url) {
                preview.src = url;
                preview.style.display = 'block';
            }
        });

        // Add readonly styling to kode buku field
        const kodeBukuField = document.getElementById('editKodeBuku');
        if (kodeBukuField) {
            kodeBukuField.style.backgroundColor = '#f0f0f0';
            kodeBukuField.style.cursor = 'not-allowed';
        }
    </script>
</body>
</html>
