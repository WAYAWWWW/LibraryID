<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard - LibraryID</title>
    
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
    
    <link rel="stylesheet" href="{{ asset('css/books-index.css') }}">
</head>
<body class="sidebar-page">
    @include('partials.user-sidebar')


    <!-- Main Content -->
    <main class="dashboard-container content-with-sidebar">
      <div class="search-section">
            <input type="text" id="searchInput" class="search-bar" placeholder="Cari buku..." />
        </div>
        <!-- Category Filters -->
        <section class="category-section">
            <h2 class="section-title">Category</h2>
            <div class="category-filters">
                <button class="category-btn active" data-category="all">Semua</button>
                <button class="category-btn" data-category="Fiksi">Fiksi</button>
                <button class="category-btn" data-category="Sejarah">Sejarah</button>
                <button class="category-btn" data-category="Pendidikan">Pendidikan</button>
                <button class="category-btn" data-category="Romansa">Romansa</button>
                <button class="category-btn" data-category="Non-Fiksi">Non-Fiksi</button>
                <button class="category-btn" data-category="Drama">Drama</button>
                <button class="category-btn" data-category="Komedi">Komedi</button>
                <button class="category-btn" data-category="Biografi">Biografi</button>
                <button class="category-btn" data-category="Panduan">Panduan</button>
                <button class="category-btn" data-category="Teknologi">Teknologi</button>
                <button class="category-btn" data-category="Masakan">Masakan</button>
                <button class="category-btn" data-category="Kesehatan">Kesehatan</button>
            </div>
        </section>

        <!-- Semua Buku Section -->
        <section class="book-section">
            <div class="section-header">
                <h2 class="section-title">Semua Buku</h2>
            </div>
            <div class="book-grid" id="bookGrid">
                @forelse($books as $book)
                    <div class="book-card" data-book-id="{{ $book->id }}" data-category="{{ $book->category }}" data-title="{{ strtolower($book->title) }}" data-author="{{ strtolower($book->author) }}">
                        <div class="book-cover">
                            <button class="wishlist-btn-card" onclick="toggleWishlist(event, {{ $book->id }})" title="Tambah ke wishlist">
                                <i class="fas fa-heart"></i>
                            </button>
                            <img src="{{ route('books.cover', $book->id) }}" alt="{{ $book->title }}">
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">{{ $book->title }}</h3>
                            <p class="book-author">Penulis: {{ $book->author }}</p>
                            @if($book->stock > 5)
                                <div class="book-status available">Tersedia</div>
                            @elseif($book->stock > 0)
                                <div class="book-status limited">Terbatas ({{ $book->stock }})</div>
                            @else
                                <div class="book-status out-of-stock">Habis</div>
                            @endif
                            <div class="book-rating" data-book-id="{{ $book->id }}">
                                <div class="rating-stars">
                                    <span class="stars-display">⭐⭐⭐⭐⭐</span>
                                </div>
                                <div class="rating-info">
                                    <span class="rating-average">-</span>
                                    <span class="rating-count">(0 ulasan)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; padding: 2rem;">
                        <p>Tidak ada buku tersedia</p>
                    </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            <div style="margin-top: 2rem; display: flex; justify-content: center;">
                {{ $books->links() }}
            </div>
        </section>
    </main>
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
        let unreadNotifications = 0;

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

        // Fungsi untuk mengambil data notifikasi dari backend
        function fetchNotifications() {
            fetch(notificationRoutes.get, {
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
                    <a href="${notificationRoutes.loansHistory}" class="btn-return-book">Kembalikan</a>
                </div>
            `;
            
            return item;
        }

        function closeNotificationPopup() {
            document.getElementById('notificationPopup').style.display = 'none';
            // Mark notifications as read
            markNotificationsAsRead();
        }

        function showNotificationPopup(event) {
            event.preventDefault();
            document.getElementById('notificationPopup').style.display = 'block';
            fetchNotifications();
        }

        function markNotificationsAsRead() {
            fetch(notificationRoutes.markRead, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': notificationRoutes.csrfToken,
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

            // Load ratings for each book
            loadBookRatings();

            // Filter category books
            const categoryBtns = document.querySelectorAll('.category-btn');
            const bookCards = document.querySelectorAll('.book-card');
            const searchInput = document.getElementById('searchInput');

            categoryBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    categoryBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    
                    const selectedCategory = this.dataset.category;
                    
                    bookCards.forEach(card => {
                        if (selectedCategory === 'all' || card.dataset.category === selectedCategory) {
                            card.style.display = '';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            // Search functionality
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const selectedCategory = document.querySelector('.category-btn.active').dataset.category;
                
                bookCards.forEach(card => {
                    const title = card.dataset.title;
                    const author = card.dataset.author;
                    const category = card.dataset.category;
                    
                    const matchesSearch = title.includes(searchTerm) || author.includes(searchTerm);
                    const matchesCategory = selectedCategory === 'all' || category === selectedCategory;
                    
                    if (matchesSearch && matchesCategory) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });

            // Click on book card to go to book detail
            bookCards.forEach(card => {
                card.addEventListener('click', function(e) {
                    // Don't navigate jika yang diklik adalah wishlist button
                    if (e.target.closest('.wishlist-btn-card')) {
                        return;
                    }
                    const bookId = this.dataset.bookId;
                    window.location.href = '/books/' + bookId;
                });
            });
        });

        function loadBookRatings() {
            const ratingContainers = document.querySelectorAll('.book-rating');
            
            ratingContainers.forEach(container => {
                const bookId = container.dataset.bookId;
                
                fetch('/api/books/' + bookId + '/reviews')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateRatingDisplay(container, data);
                        }
                    })
                    .catch(error => console.error('Error loading rating for book', bookId, ':', error));
            });
        }

        function updateRatingDisplay(container, data) {
            const starsDisplay = container.querySelector('.stars-display');
            const ratingAverage = container.querySelector('.rating-average');
            const ratingCount = container.querySelector('.rating-count');
            
            const avgRating = data.averageRating || 0;
            const totalReviews = data.totalReviews || 0;
            
            // Display stars based on average rating
            if (avgRating > 0) {
                const fullStars = Math.round(avgRating);
                const stars = '⭐'.repeat(fullStars) + '☆'.repeat(5 - fullStars);
                starsDisplay.textContent = stars;
            } else {
                starsDisplay.textContent = '☆☆☆☆☆';
            }
            
            // Display rating info
            if (avgRating > 0) {
                ratingAverage.textContent = avgRating.toFixed(1);
                ratingCount.textContent = `(${totalReviews} ${totalReviews === 1 ? 'ulasan' : 'ulasan'})`;
            } else {
                ratingAverage.textContent = '-';
                ratingCount.textContent = '(belum ada ulasan)';
            }
        }

        // Wishlist functionality
        function toggleWishlist(event, bookId) {
            event.stopPropagation();
            const btn = event.target.closest('.wishlist-btn-card');
            const isInWishlist = btn.classList.contains('in-wishlist');

            if (isInWishlist) {
                // Remove from wishlist
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
                        btn.classList.remove('in-wishlist');
                        btn.title = 'Tambah ke wishlist';
                    }
                })
                .catch(error => console.error('Error:', error));
            } else {
                // Add to wishlist
                fetch('/wishlist', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ book_id: bookId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        btn.classList.add('in-wishlist');
                        btn.title = 'Hapus dari wishlist';
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        // Check wishlist status on page load
        document.addEventListener('DOMContentLoaded', function() {
            const bookCards = document.querySelectorAll('.book-card');
            bookCards.forEach(card => {
                const bookId = card.dataset.bookId;
                const wishlistBtn = card.querySelector('.wishlist-btn-card');
                
                fetch(`/api/wishlist/check/${bookId}`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.inWishlist) {
                        wishlistBtn.classList.add('in-wishlist');
                        wishlistBtn.title = 'Hapus dari wishlist';
                    }
                })
                .catch(error => console.error('Error checking wishlist:', error));
            });
        });
    </script>
    <script src="{{ asset('js/user-notifications.js') }}"></script>
    <script src="{{ asset('js/user-sidebar.js') }}"></script>
</body>
</html>
