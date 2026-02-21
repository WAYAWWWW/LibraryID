<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rating User - LibraryID</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/variables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nav.css') }}">
    <link rel="stylesheet" href="{{ asset('css/petugas-ratings.css') }}">

    

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/js/app.js'])
    @endif
</head>
<body>
    <nav class="navbar">
        <div class="nav-container">
            <a href="{{ route('welcome') }}" class="logo">
                <img src="{{ asset('images/logo.png') }}" alt="LibraryID Logo">
            </a>
            <ul class="nav-menu">
                <li><a href="{{ route('petugas.dashboard') }}">Petugas dashboard</a></li>
                <li><a href="{{ route('petugas.kelola-buku') }}">Kelola Buku</a></li>
                <li><a href="{{ route('petugas.ratings') }}">Rating User</a></li>
                <li><a href="{{ route('petugas.reports.loans.history') }}">Laporan</a></li>
                @if(auth()->user()->isAdmin())
                    <li><a href="{{ route('admin.dashboard') }}">Admin Panel</a></li>
                @endif
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

    <div class="ratings-page">
        <div class="ratings-header">
            <h1>Rating User</h1>
            <p>Tekan rating untuk melihat komentar lengkap.</p>
        </div>

        <div class="ratings-table-wrapper">
            <table class="ratings-table">
                <thead>
                    <tr>
                        <th>Nama User</th>
                        <th>Buku</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @if($reviews && $reviews->count() > 0)
                        @foreach($reviews as $review)
                            <tr class="rating-row">
                                <td>{{ $review->user->name ?? '-' }}</td>
                                <td>{{ $review->book->judul ?? '-' }}</td>
                                <td>
                                    <button type="button" class="rating-action" data-toggle="rating">
                                        {{ str_repeat('⭐', (int) $review->rating) }}
                                    </button>
                                </td>
                                <td class="rating-comment">
                                    {{ $review->comment ? \Illuminate\Support\Str::limit($review->comment, 40) : '-' }}
                                </td>
                                <td>{{ optional($review->created_at)->format('d/m/Y') }}</td>
                            </tr>
                            <tr class="rating-comment-row">
                                <td colspan="5" class="rating-comment-cell">
                                    <div class="rating-comment-title">Komentar</div>
                                    @if($review->comment)
                                        <div>{{ $review->comment }}</div>
                                    @else
                                        <div class="rating-comment-empty">Tidak ada komentar.</div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: var(--gray-400);">
                                Belum ada rating dari user
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if($reviews && $reviews->hasPages())
            <div style="margin-top: 1.5rem;">
                {{ $reviews->links() }}
            </div>
        @endif
    </div>

    <script>
        document.querySelectorAll('[data-toggle="rating"]').forEach(button => {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                const row = button.closest('tr');
                const nextRow = row?.nextElementSibling;
                if (!nextRow || !nextRow.classList.contains('rating-comment-row')) {
                    return;
                }

                const isOpen = nextRow.style.display === 'table-row';
                document.querySelectorAll('.rating-comment-row').forEach(r => r.style.display = 'none');
                nextRow.style.display = isOpen ? 'none' : 'table-row';
            });
        });
    </script>
</body>
</html>
