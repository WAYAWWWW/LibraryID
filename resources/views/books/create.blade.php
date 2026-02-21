<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Petugas Dashboard - LibraryID</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashpet.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/books-create.css') }}">
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif

    
</head>
<body>
    <!-- ===== Navbar ===== -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('welcome') }}" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="LibraryID Logo">
            </a>
            @if($isAdmin ?? false)
                <!-- Admin Navbar -->
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
                    <li>
                       <div class="nav-right"><form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-logout">
                                <span>Logout</span>
                            </button>
                        </form></div>
                    </li>
                </ul>
            @else
                <!-- Petugas Navbar -->
                <ul class="nav-menu">
                    <li><a href="{{ route('petugas.dashboard') }}">Petugas dashboard</a></li>
                    <li><a href="{{ route('books.create') }}">Tambah buku</a></li>
                    <li><a href="{{ route('petugas.kelola-buku') }}">Kelola Buku</a></li>
                    <li><a href="{{ route('petugas.ratings') }}">Rating User</a></li>
                    <li><a href="{{ route('petugas.reports.loans.history') }}">Laporan</a></li>
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('dashboard') }}">Dashboard user</a></li>
                    @endif
                    <li>
                        <div class="nav-right"><form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-logout">
                                <span>Logout</span>
                            </button>
                        </form></div>
                    </li>
                </ul>
                 <a href="#" class="notification-trigger" onclick="showNotificationPopup(event)">
                            <i class="bi bi-bell"></i>
                            <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                      </a>
            @endif
        </div>
    </nav>
    <!-- ===== Form Container ===== -->
    <div class="form-container">
        <div class="form-header">
            <div class="form-header-content">
                <h1>📚 Tambah Buku Baru</h1>
                <p>Masukkan informasi lengkap untuk menambahkan buku ke perpustakaan</p>
            </div>
        </div>

        <div class="form-card-wrapper">
            <div class="form-card">
                <div class="form-content">
                    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="form-body">
                            <!-- Left: Image Upload -->
                            <div class="upload-section">
                                <label for="cover_image" class="image-upload-box" id="imageUploadBox">
                                    <div class="upload-placeholder" id="uploadPlaceholder">
                                        <div class="upload-icon">📷</div>
                                        <div class="upload-text">Masukan Gambar</div>
                                    </div>
                                    <img id="imagePreview" class="image-preview" style="display: none;">
                                </label>
                                <input type="file" id="cover_image" name="cover_image" accept="image/*" style="display: none;">
                                <div class="form-hint">Format bebas (gambar apa saja), maksimal 5MB.</div>
                                @error('cover_image')
                                    <div class="error-message">
                                        <span>✕</span>
                                        <span>{{ $message }}</span>
                                    </div>
                                @enderror
                            </div>

                            <!-- Right: Form Fields -->
                            <div class="form-fields">
                                <!-- Judul Buku -->
                                <div class="form-group">
                                    <label for="title" class="form-label">
                                        Judul Buku <span class="required">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="title" 
                                        name="title" 
                                        class="form-input"
                                        placeholder="Masukkan judul buku"
                                        value="{{ old('title') }}"
                                        required
                                    >
                                    @error('title')
                                        <div class="error-message">
                                            <span>✕</span>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <!-- Penulis Buku -->
                                <div class="form-group">
                                    <label for="author" class="form-label">
                                        Penulis Buku <span class="required">*</span>
                                    </label>
                                    <input 
                                        type="text" 
                                        id="author" 
                                        name="author" 
                                        class="form-input"
                                        placeholder="Masukkan nama penulis"
                                        value="{{ old('author') }}"
                                        required
                                    >
                                    @error('author')
                                        <div class="error-message">
                                            <span>✕</span>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <!-- Penerbit & Tahun -->
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="publisher" class="form-label">
                                            Penerbit <span class="required">*</span>
                                        </label>
                                        <input 
                                            type="text" 
                                            id="publisher" 
                                            name="publisher" 
                                            class="form-input"
                                            placeholder="Nama penerbit"
                                            value="{{ old('publisher') }}"
                                            required
                                        >
                                        @error('publisher')
                                            <div class="error-message">
                                                <span>✕</span>
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="publication_year" class="form-label">
                                            Tahun Terbit <span class="required">*</span>
                                        </label>
                                        <input 
                                            type="number" 
                                            id="publication_year" 
                                            name="publication_year" 
                                            class="form-input"
                                            placeholder="Contoh: 2024"
                                            value="{{ old('publication_year') }}"
                                            min="1900"
                                            max="{{ date('Y') + 1 }}"
                                            required
                                        >
                                        @error('publication_year')
                                            <div class="error-message">
                                                <span>✕</span>
                                                <span>{{ $message }}</span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Halaman Buku -->
                                <div class="form-group">
                                    <label for="pages" class="form-label">
                                        Jumlah Halaman <span class="required">*</span>
                                    </label>
                                    <input 
                                        type="number" 
                                        id="pages" 
                                        name="pages" 
                                        class="form-input"
                                        placeholder="Masukkan jumlah halaman"
                                        value="{{ old('pages') }}"
                                        min="1"
                                        required
                                    >
                                    @error('pages')
                                        <div class="error-message">
                                            <span>✕</span>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <!-- Jumlah Buku -->
                                <div class="form-group">
                                    <label for="quantity" class="form-label">
                                        Jumlah Buku <span class="required">*</span>
                                    </label>
                                    <input 
                                        type="number" 
                                        id="quantity" 
                                        name="quantity" 
                                        class="form-input"
                                        placeholder="Berapa banyak buku yang ditambahkan?"
                                        value="{{ old('quantity', 1) }}"
                                        min="1"
                                        max="999"
                                        required
                                    >
                                    <div class="form-hint">Jumlah kopian buku yang tersedia</div>
                                    @error('quantity')
                                        <div class="error-message">
                                            <span>✕</span>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <!-- Deskripsi -->
                                <div class="form-group">
                                    <label for="description" class="form-label">
                                        Deskripsi Buku
                                    </label>
                                    <textarea 
                                        id="description" 
                                        name="description" 
                                        class="form-textarea"
                                        placeholder="Masukkan deskripsi singkat tentang buku (opsional)"
                                    >{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="error-message">
                                            <span>✕</span>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>

                                <!-- Kategori -->
                                <div class="form-group">
                                    <label for="category" class="form-label">
                                        Kategori <span class="required">*</span>
                                    </label>
                                    <select 
                                        id="category" 
                                        name="category" 
                                        class="form-select"
                                        required
                                    >
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="Fiksi" {{ old('category') == 'Fiksi' ? 'selected' : '' }}>Fiksi</option>
                                        <option value="Non-Fiksi" {{ old('category') == 'Non-Fiksi' ? 'selected' : '' }}>Non-Fiksi</option>
                                        <option value="Edukasi" {{ old('category') == 'Edukasi' ? 'selected' : '' }}>Edukasi</option>
                                        <option value="Referensi" {{ old('category') == 'Referensi' ? 'selected' : '' }}>Referensi</option>
                                        <option value="Seni & Budaya" {{ old('category') == 'Seni & Budaya' ? 'selected' : '' }}>Seni & Budaya</option>
                                        <option value="Teknologi" {{ old('category') == 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
                                    </select>
                                    @error('category')
                                        <div class="error-message">
                                            <span>✕</span>
                                            <span>{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="form-actions">
                            <a href="{{ route('petugas.dashboard') }}" class="btn btn-cancel">
                                <span>✕</span>
                                <span>Batal</span>
                            </a>
                            <button type="submit" class="btn btn-submit">
                                <span>✓</span>
                                <span>Tambah Buku</span>
                            </button>
                        </div>
                    </form>
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
        });
        
        // Image Preview
        const imageUploadBox = document.getElementById('imageUploadBox');
        const imageInput = document.getElementById('cover_image');
        const imagePreview = document.getElementById('imagePreview');
        const uploadPlaceholder = document.getElementById('uploadPlaceholder');
        const maxImageSize = 5 * 1024 * 1024;

        function resetImageInput() {
            imageInput.value = '';
            imagePreview.src = '';
            imagePreview.style.display = 'none';
            uploadPlaceholder.style.display = 'flex';
        }


        function handleImageChange(files) {
            if (files.length > 0) {
                const file = files[0];
                // Validate file type
                if (!file.type.startsWith('image/')) {
                    alert('Silakan pilih file gambar');
                    resetImageInput();
                    return;
                }

                if (file.size > maxImageSize) {
                    alert('Ukuran gambar terlalu besar. Maksimal 5MB.');
                    resetImageInput();
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = (event) => {
                    imagePreview.src = event.target.result;
                    imagePreview.style.display = 'block';
                    uploadPlaceholder.style.display = 'none';
                };
                reader.readAsDataURL(file);
            }
        }

        imageUploadBox.addEventListener('click', (e) => {
            // Prevent triggering when clicking on the image preview
            if (e.target !== imagePreview) {
                imageInput.click();
            }
        });

        imageInput.addEventListener('change', (e) => {
            handleImageChange(e.target.files);
        });

        // Drag and drop
        imageUploadBox.addEventListener('dragover', (e) => {
            e.preventDefault();
            e.stopPropagation();
            imageUploadBox.style.borderColor = '#2563eb';
            imageUploadBox.style.background = '#eff6ff';
        });

        imageUploadBox.addEventListener('dragleave', (e) => {
            e.preventDefault();
            e.stopPropagation();
            imageUploadBox.style.borderColor = '#e5e7eb';
            imageUploadBox.style.background = '#f9fafb';
        });

        imageUploadBox.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            imageUploadBox.style.borderColor = '#e5e7eb';
            imageUploadBox.style.background = '#f9fafb';
            
            const files = e.dataTransfer.files;
            
            if (files.length > 0) {
                const file = files[0];

                if (!file.type.startsWith('image/')) {
                    alert('Silakan pilih file gambar');
                    resetImageInput();
                    return;
                }

                if (file.size > maxImageSize) {
                    alert('Ukuran gambar terlalu besar. Maksimal 5MB.');
                    resetImageInput();
                    return;
                }

                // Directly set to input - this works when input is not inside label
                imageInput.files = files;
                
                // Trigger change event manually
                const event = new Event('change', { bubbles: true });
                imageInput.dispatchEvent(event);
            }
        });

        // Prevent default drag behavior on document
        document.addEventListener('dragover', (e) => {
            e.preventDefault();
        });
        document.addEventListener('drop', (e) => {
            e.preventDefault();
        });
    </script>
</body>
</html>
