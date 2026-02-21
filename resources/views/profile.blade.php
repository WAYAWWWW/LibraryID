<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil Saya - LibraryID</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user-sidebar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
</head>
<body class="sidebar-page">
    @include('partials.user-sidebar')

    <!-- Main Content -->
    <main class="main-container content-with-sidebar">
        <!-- Sidebar Profile -->
        <aside class="profile-sidebar">
            <div class="avatar-container">
                @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" class="avatar">
                @else
                    <div class="avatar-placeholder">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <button class="edit-avatar-btn" onclick="openPhotoModal()">
                    <i class="fas fa-camera"></i>
                </button>
            </div>
            
            <h2 class="user-name">{{ $user->name }}</h2>
            <p class="user-email">{{ $user->email }}</p>
            
            <div class="user-role 
                @if($user->role === 'admin') role-admin
                @elseif($user->role === 'petugas') role-petugas
                @else role-user
                @endif">
                @if($user->role === 'admin') Administrator
                @elseif($user->role === 'petugas') Petugas
                @else Member
                @endif
            </div>
            
            <div class="sidebar-stats">
                <div class="stat-item">
                    <span class="stat-value">{{ $user->loans_count ?? 0 }}</span>
                    <span class="stat-label">Peminjaman</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">{{ $user->active_loans_count ?? 0 }}</span>
                    <span class="stat-label">Aktif</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">{{ $user->created_at?->format('d/m/Y') }}</span>
                    <span class="stat-label">Bergabung</span>
                </div>
            </div>
            
            <div class="sidebar-actions">
                <button class="action-btn primary" onclick="openEditModal()">
                    <i class="fas fa-edit"></i>
                    <span>Edit Profil</span>
                </button>
                <a href="{{ route('dashboard') }}" class="action-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Kembali</span>
                </a>
                <form action="{{ route('logout') }}" method="POST" style="width:100%;">
                    @csrf
                    <button type="submit" class="action-btn danger">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Profile Content -->
        <section class="profile-content">
            <div class="content-header">
                <h2>Informasi Profil</h2>
                <button class="edit-profile-btn" onclick="openEditModal()">
                    <i class="fas fa-edit"></i>
                    <span>Edit Profil</span>
                </button>
            </div>
            
            <div class="info-grid">
                <div class="info-card">
                    <div class="info-icon icon-email">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <span class="info-label">Email</span>
                    <p class="info-value">{{ $user->email }}</p>
                </div>
                
                <div class="info-card">
                    <div class="info-icon icon-name">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="info-label">Nama Lengkap</span>
                    <p class="info-value">{{ $user->name }}</p>
                </div>
                
                <div class="info-card">
                    <div class="info-icon icon-role">
                        <i class="fas fa-user-tag"></i>
                    </div>
                    <span class="info-label">Role / Jabatan</span>
                    <p class="info-value">
                        @if($user->role === 'admin') Administrator
                        @elseif($user->role === 'petugas') Petugas Perpustakaan
                        @else Anggota Perpustakaan
                        @endif
                    </p>
                </div>
                
                <div class="info-card">
                    <div class="info-icon icon-date">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <span class="info-label">Bergabung Sejak</span>
                    <p class="info-value">{{ $user->created_at->format('d F Y') }}</p>
                </div>
                
                <div class="info-card">
                    <div class="info-icon icon-status">
                        <i class="fas fa-circle"></i>
                    </div>
                    <span class="info-label">Status Akun</span>
                    <p class="info-value">
                        <span class="status-badge @if($user->is_active) status-active @else status-inactive @endif">
                            <i class="fas @if($user->is_active) fa-check-circle @else fa-times-circle @endif"></i>
                            @if($user->is_active) Aktif @else Tidak Aktif @endif
                        </span>
                    </p>
                </div>
                
                <div class="info-card">
                    <div class="info-icon icon-loans">
                        <i class="fas fa-book-reader"></i>
                    </div>
                    <span class="info-label">Total Peminjaman</span>
                    <p class="info-value">{{ $user->loans_count ?? 0 }} buku</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Modal Edit Foto -->
    <div id="photoModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Ubah Foto Profil</h3>
                <button class="close-modal" onclick="closePhotoModal()">&times;</button>
            </div>
            
            <form action="{{ route('profile.update-photo') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="profile_photo">Pilih Foto Baru</label>
                    <div class="file-input-container">
                        <label class="file-input-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span>Klik untuk upload foto (Max 2MB)</span>
                            <input type="file" id="profile_photo" name="profile_photo" accept="image/*" required class="file-input">
                        </label>
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closePhotoModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Upload Foto</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Profil -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Profil</h3>
                <button class="close-modal" onclick="closeEditModal()">&times;</button>
            </div>
            
            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ $user->name }}" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ $user->email }}" class="form-control" required>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>© {{ date('Y') }} LibraryID. All rights reserved.</p>
    </footer>

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
        
        // Modal Functions
        function openPhotoModal() {
            document.getElementById('photoModal').style.display = 'flex';
        }

        function closePhotoModal() {
            document.getElementById('photoModal').style.display = 'none';
        }

        function openEditModal() {
            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const photoModal = document.getElementById('photoModal');
            const editModal = document.getElementById('editModal');
            
            if (event.target === photoModal) {
                closePhotoModal();
            }
            if (event.target === editModal) {
                closeEditModal();
            }
        }

        // File input preview
        document.getElementById('profile_photo').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Pilih file...';
            const label = document.querySelector('.file-input-label span');
            label.textContent = fileName;
        });

    </script>
    <script src="{{ asset('js/user-sidebar.js') }}"></script>
</body>
</html>
