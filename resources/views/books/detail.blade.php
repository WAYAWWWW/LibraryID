<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $book->title }} - LibraryID</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
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
                                <a href="{{ route('loans.create', $book) }}" class="btn-pinjam" style="display: inline-block; text-align: center; text-decoration: none;">Pinjam Buku</a>
                            @else
                                <button class="btn-pinjam" disabled style="opacity: 0.5; cursor: not-allowed;">Stok Habis</button>
                            @endif
                        </div>

                        <!-- Wishlist Button -->
                        <button id="wishlistBtn" class="btn-wishlist" onclick="toggleWishlist({{ $book->id }})" style="margin-top: 1rem; width: 100%; padding: 0.75rem; background: #ef4444; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 1rem; font-weight: 600; transition: all 0.3s ease;">
                            <i class="fas fa-heart"></i> Tambah ke Wishlist
                        </button>
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

            // Check wishlist status
            checkWishlistStatus({{ $book->id }});
        });
        
        // Wishlist functionality
        document.querySelector('.btn-wishlist')?.addEventListener('click', function() {
            alert('Buku ditambahkan ke wishlist!');
        });
        
        function toggleWishlist(bookId) {
            const btn = document.getElementById('wishlistBtn');
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
                        btn.innerHTML = '<i class="fas fa-heart"></i> Tambah ke Wishlist';
                        btn.style.background = '#ef4444';
                        showNotification('Dihapus dari wishlist', 'success');
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
                        btn.innerHTML = '<i class="fas fa-heart"></i> Hapus dari Wishlist';
                        btn.style.background = '#dc2626';
                        showNotification('Ditambahkan ke wishlist', 'success');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        function checkWishlistStatus(bookId) {
            fetch(`/api/wishlist/check/${bookId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const btn = document.getElementById('wishlistBtn');
                if (data.inWishlist) {
                    btn.classList.add('in-wishlist');
                    btn.innerHTML = '<i class="fas fa-heart"></i> Hapus dari Wishlist';
                    btn.style.background = '#dc2626';
                }
            })
            .catch(error => console.error('Error checking wishlist:', error));
        }

        function showNotification(message, type = 'info') {
            // Simple notification - bisa disesuaikan dengan notification system yang sudah ada
            alert(message);
        }
        
    </script>
    <script src="{{ asset('js/user-sidebar.js') }}"></script>
</body>
</html>
