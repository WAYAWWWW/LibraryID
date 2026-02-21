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
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100vw;
            height: 100vh;
            background: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            color: #333;
            overflow-x: hidden;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Admin Navbar Dropdown Styles */
        .admin-navbar-dropdown {
            position: relative;
            display: inline-block;
        }
        
        .dropdown-menu {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 4px;
            top: 100%;
            left: 0;
        }
        
        .dropdown-menu a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: background-color 0.2s;
        }
        
        .dropdown-menu a:hover {
            background-color: #f1f1f1;
        }
        
        .admin-navbar-dropdown:hover .dropdown-menu {
            display: block;
        }
        
        .admin-navbar-dropdown > a::after {
            content: ' ▼';
            font-size: 0.7em;
        }

        .form-container {
            flex: 1;
            width: 100%;
            padding: 0;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            background: #f8f9fa;
        }

        .form-header {
            padding: 2rem 0 1rem 0;
            margin-bottom: 0;
            background: white;
            border-bottom: 1px solid #e5e7eb;
        }

        .form-header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .form-header h1 {
            font-size: 2rem;
            color: #1f2937;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: #6b7280;
            font-size: 1rem;
        }

        .form-card-wrapper {
            flex: 1;
            padding: 0;
            background: #f8f9fa;
        }

        .form-card {
            max-width: 100%;
            margin: 0;
            background: white;
            border-radius: 0;
            padding: 2rem;
            box-shadow: none;
            border: none;
            min-height: calc(100vh - 180px);
        }

        .form-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0;
        }

        .form-body {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 3rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 1024px) {
            .form-body {
                gap: 2rem;
            }
        }

        @media (max-width: 768px) {
            .form-body {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .form-header-content {
                padding: 0 1rem;
            }
            
            .form-card {
                padding: 1.5rem;
            }
        }

        @media (max-width: 640px) {
            .form-card {
                padding: 1rem;
            }
        }

        .upload-section {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .image-upload-box {
            width: 100%;
            aspect-ratio: 3/4;
            border: 2px dashed #e5e7eb;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9fafb;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .image-upload-box:hover {
            border-color: #2563eb;
            background: #eff6ff;
        }

        .image-upload-box input[type="file"] {
            /* Hidden - we use separate input element below */
        }

        .upload-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            pointer-events: none;
        }

        .upload-icon {
            font-size: 3rem;
            color: #9ca3af;
        }

        .upload-text {
            color: #6b7280;
            font-size: 0.95rem;
            text-align: center;
        }

        .image-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .form-fields {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-label {
            font-size: 0.95rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .form-input,
        .form-textarea,
        .form-select {
            padding: 0.875rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.95rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
        }

        .form-input:focus,
        .form-textarea:focus,
        .form-select:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background: white;
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 640px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .form-actions {
            display: flex;
            gap: 1rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
            margin-top: 1rem;
        }

        .btn {
            padding: 0.875rem 1.75rem;
            border-radius: 8px;
            border: none;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-family: 'Poppins', sans-serif;
            flex: 1;
        }

        .btn-submit {
            background: #2563eb;
            color: white;
        }

        .btn-submit:hover {
            background: #1d4ed8;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .btn-cancel {
            background: #e5e7eb;
            color: #374151;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-cancel:hover {
            background: #d1d5db;
        }

        .error-message {
            color: #dc2626;
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .success-message {
            background: #dcfce7;
            color: #16a34a;
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #16a34a;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            max-width: 1200px;
            margin: 0 auto 1.5rem;
        }

        .alert {
            background: #fee2e2;
            color: #dc2626;
            padding: 1rem 1.25rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            border-left: 4px solid #dc2626;
            max-width: 1200px;
            margin: 0 auto 1.5rem;
        }

        .alert ul {
            margin: 0;
            padding-left: 1.25rem;
        }

        .alert ul li {
            margin-bottom: 0.5rem;
        }

        .form-hint {
            font-size: 0.85rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        .required {
            color: #dc2626;
        }

        /* Message container styling */
        .message-container {
            width: 100%;
            padding: 0;
            background: #f8f9fa;
        }

        .message-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem 2rem;
        }

        @media (max-width: 768px) {
            .message-content {
                padding: 1rem;
            }
        }
    </style>
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
