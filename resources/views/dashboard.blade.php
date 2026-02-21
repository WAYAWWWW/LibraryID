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
    <link rel="stylesheet" href="{{ asset('css/dashboard-inline.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-sidebar.css') }}">
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif

    

</head>
<body class="sidebar-page">
    @include('partials.user-sidebar')

    <!-- ===== Dashboard Container ===== -->
    <div class="dashboard-container content-with-sidebar">
        <!-- Search Bar -->
        <div class="search-section">
            <input type="text" class="search-bar" placeholder="Cari buku..." />
        </div>

        <!-- Carousel Slider -->
        <div class="carousel-section">
            <div class="carousel-container">
                <div class="carousel-wrapper">
                    <div class="carousel-slide">
                        <img src="https://images.unsplash.com/photo-1506880018603-83d5b814b5a6?w=800&h=400&fit=crop" alt="gambar-slider">
                    </div>
                    <div class="carousel-slide">
                        <img src="https://images.unsplash.com/photo-150784272343-583f20270319?w=800&h=400&fit=crop" alt="gambar-slider">
                    </div>
                    <div class="carousel-slide">
                        <img src="https://images.unsplash.com/photo-1495446815901-a7297e3ffe02?w=800&h=400&fit=crop" alt="gambar-slider">
                    </div>
                </div>
                <button class="carousel-btn carousel-prev" onclick="moveCarousel(-1)">❮</button>
                <button class="carousel-btn carousel-next" onclick="moveCarousel(1)">❯</button>
                <div class="carousel-dots">
                    <span class="dot active" onclick="goToSlide(0)"></span>
                    <span class="dot" onclick="goToSlide(1)"></span>
                    <span class="dot" onclick="goToSlide(2)"></span>
                </div>
            </div>
        </div>

        <!-- Baru Terakhir di Baca Section -->
        <div class="last-borrowed">
            <h2 class="section-title">Terakhir di pinjam</h2>
            <div class="books-scroll">
                @foreach($recentLoans as $loan)
                <div class="book-card-small">
                    <a href="{{ route('books.show', $loan->book->id) }}" style="text-decoration: none; color: inherit;">
                        <div class="book-cover-small">
                            <img src="{{ route('books.cover', $loan->book->id) }}" 
                                 alt="{{ $loan->book->title }}">
                        </div>
                        <div class="book-info-small">
                            <h3 class="book-title-small">{{ $loan->book->title }}</h3>
                            <p class="book-date-small">{{ \Carbon\Carbon::parse($loan->created_at)->diffForHumans() }}</p>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Rekomendasi Section -->
        <div class="rekomendasi-section">
            <h2 class="section-title">Rekomendasi</h2>
            <div class="books-grid">
                @foreach($recommendedBooks as $book)
                <div class="book-card">
                    <a href="{{ route('books.show', $book->id) }}" style="text-decoration: none; color: inherit;">
                        <div class="book-cover">
                            <img src="{{ route('books.cover', $book->id) }}" 
                                 alt="{{ $book->title }}">
                        </div>
                        <div class="book-info">
                            <h3 class="book-title">{{ $book->title }}</h3>
                            <p class="book-author">{{ $book->author }}</p>
                            @if($book->category && is_object($book->category))
                            <span class="book-category">{{ $book->category->name }}</span>
                            @endif
                            <div class="book-rating" data-book-id="{{ $book->id }}">
                                <span class="rating-stars">⭐⭐⭐⭐⭐</span>
                                <span class="rating-value">-</span>
                                <span class="rating-text">(0)</span>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Popup Notification Modal -->
    <div id="notificationPopup" class="notification-popup-modal">
        <div class="notification-popup-content">
            <div class="notification-header">
                <h3><i class="fas fa-bell"></i> Notifikasi Peminjaman</h3>
                <span class="close-popup" onclick="closeNotificationPopup()">&times;</span>
            </div>
            <div class="notification-list">
                <div class="notification-empty" id="emptyNotification">
                    <i class="fas fa-bell-slash"></i>
                    <p>Tidak ada notifikasi saat ini</p>
                </div>
                <div id="notificationItems"></div>
            </div>
            <div class="notification-footer">
                <a href="{{ route('loans.history') }}" class="btn-view-all">
                    <i class="fas fa-history"></i> Lihat Semua Peminjaman
                </a>
            </div>
        </div>
    </div>

    <!-- Scripts -->
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
            const badgeSidebar = document.getElementById('notificationBadge');
            const notificationItems = document.getElementById('notificationItems');
            const emptyState = document.getElementById('emptyNotification');
            
            unreadNotifications = data.unread_count || 0;
            
            // Update badge in sidebar
            if (unreadNotifications > 0) {
                badgeSidebar.textContent = unreadNotifications;
                badgeSidebar.style.display = 'flex';
            } else {
                badgeSidebar.style.display = 'none';
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
            
            // Load ratings for recommended books
            loadRecommendedBooksRatings();
        });

        // Carousel functionality
        let currentSlide = 0;

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
    </script>
    <script src="{{ asset('js/user-notifications.js') }}"></script>
    <script src="{{ asset('js/user-sidebar.js') }}"></script>
    <script src="{{ asset('js/carousel.js') }}"></script>
</body>
</html>
