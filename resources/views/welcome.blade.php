<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LibraryID - Perpustakaan Digital Indonesia</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/buttons.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landingpage.css') }}">
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif
</head>
<body>
    <!-- ===== Navbar ===== -->
    <nav class="navbar">
        <div class="container nav-container">
            <a href="#" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="LibraryID Logo">
                <span>LibraryID</span>
            </a>
            <ul class="nav-menu">
                <li><a href="#features">Fitur</a></li>
                <li><a href="#books">Koleksi</a></li>
                <li><a href="#stats">Statistik</a></li>
                @auth
                    <li><a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a></li>
                @else
                    <li><a href="{{ route('login') }}" class="btn btn-primary">Masuk</a></li>
                    @if (Route::has('register'))
                        <li><a href="{{ route('register') }}" class="btn btn-primary">Daftar</a></li>
                    @endif
                @endauth
            </ul>
        </div>
    </nav>

    <!-- ===== Hero Section ===== -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-left">
                    <div class="hero-badge">
                        <span class="badge-text">Perpustakaan Digital Terlengkap</span>
                    </div>
                    <h1>Ribuan Buku dalam <span class="highlight">Genggaman Anda</span></h1>
                    <p>Akses koleksi buku terlengkap kapan saja, pinjam buku di mana saja.</p>
                    <div class="hero-buttons">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg">Mulai Membaca</a>
                        @else
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">Mulai Membaca</a>
                            <a href="#features" class="btn btn-secondary btn-lg">Pelajari Lebih Lanjut</a>
                        @endauth
                    </div>
                </div>
                <div class="hero-right">
                    <div class="hero-image-placeholder">
                        <img src="{{ asset('images/buku_landing.jpg') }}" alt="Buku Landing Page">
                        <!-- Placeholder untuk gambar -->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== Features Section ===== -->
    <section class="features" id="features">
        <div class="container">
            <h2 class="section-title">Fitur Unggulan</h2>
            <p class="section-subtitle">Nikmati pengalaman membaca yang luar biasa</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📖</div>
                    <h3>Koleksi Lengkap</h3>
                    <p>Ribuan buku dari berbagai genre dan kategori, mulai dari fiksi hingga non-fiksi.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">🔍</div>
                    <h3>Pencarian Mudah</h3>
                    <p>Fitur pencarian canggih memudahkan Anda menemukan buku favorit dengan cepat.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">⭐</div>
                    <h3>Ulasan & Rating</h3>
                    <p>Baca ulasan dari pembaca lain dan bagikan pengalaman membaca Anda.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📅</div>
                    <h3>Manajemen Peminjaman</h3>
                    <p>Kelola jadwal peminjaman buku dengan sistem yang fleksibel dan mudah digunakan.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">👥</div>
                    <h3>Komunitas Pembaca</h3>
                    <p>Bergabunglah dengan komunitas pembaca dan diskusikan buku favorit Anda.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon">📱</div>
                    <h3>Akses Dimana Saja</h3>
                    <p>Baca buku kapan saja dan di mana saja dengan perangkat apa pun yang Anda gunakan.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== Books Section ===== -->
    <section class="books" id="books">
        <div class="container">
            <h2 class="section-title">Buku Rekomendasi</h2>
            <p class="section-subtitle">Koleksi pilihan terbaik untuk Anda</p>
            
            <div class="books-grid">
                <div class="book-card">
                    <div class="book-cover">📚</div>
                    <div class="book-info">
                        <p class="book-title">Laskar Pelangi</p>
                        <p class="book-author">Andrea Hirata</p>
                        <a href="#" class="btn btn-primary" style="width: 100%; text-align: center;">Baca Sekarang</a>
                    </div>
                </div>

                <div class="book-card">
                    <div class="book-cover">📖</div>
                    <div class="book-info">
                        <p class="book-title">Saman</p>
                        <p class="book-author">Ayu Utami</p>
                        <a href="#" class="btn btn-primary" style="width: 100%; text-align: center;">Baca Sekarang</a>
                    </div>
                </div>

                <div class="book-card">
                    <div class="book-cover">📕</div>
                    <div class="book-info">
                        <p class="book-title">Rindu</p>
                        <p class="book-author">Leila S. Chudori</p>
                        <a href="#" class="btn btn-primary" style="width: 100%; text-align: center;">Baca Sekarang</a>
                    </div>
                </div>

                <div class="book-card">
                    <div class="book-cover">📗</div>
                    <div class="book-info">
                        <p class="book-title">Moga Bunda Doakan</p>
                        <p class="book-author">Tere Liye</p>
                        <a href="#" class="btn btn-primary" style="width: 100%; text-align: center;">Baca Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== Stats Section ===== -->
    <section class="stats" id="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <h2>10K+</h2>
                    <p>Buku Digital</p>
                </div>
                <div class="stat-item">
                    <h2>50K+</h2>
                    <p>Pembaca Aktif</p>
                </div>
                <div class="stat-item">
                    <h2>100K+</h2>
                    <p>Peminjaman</p>
                </div>
                <div class="stat-item">
                    <h2>4.8★</h2>
                    <p>Rating Pengguna</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== CTA Section ===== -->
    <section class="cta">
        <div class="container">
            <h2>Siap untuk Memulai?</h2>
            <p>Bergabunglah dengan ribuan pembaca lainnya dan jelajahi dunia literatur Indonesia</p>
            @guest
                <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar Sekarang</a>
                    <a href="{{ route('login') }}" class="btn btn-secondary">Sudah Punya Akun?</a>
                </div>
            @else
                <a href="{{ url('/dashboard') }}" class="btn btn-primary">Ke Dashboard</a>
            @endguest
        </div>
    </section>
</body>
</html>
