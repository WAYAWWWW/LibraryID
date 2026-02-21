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

    <style>
        .ratings-page {
            width: 100%;
            max-width: none;
            padding: 2rem 3rem;
        }

        .ratings-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .ratings-header p {
            color: var(--gray-500);
            font-size: 0.95rem;
        }

        .ratings-table-wrapper {
            background: white;
            border-radius: 1rem;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
            margin-top: 1.5rem;
        }

        .ratings-table {
            width: 100%;
            border-collapse: collapse;
        }

        .ratings-table thead {
            background: var(--gray-50);
            border-bottom: 2px solid var(--gray-200);
        }

        .ratings-table th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--gray-700);
            font-size: 0.95rem;
        }

        .ratings-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--gray-200);
            color: var(--gray-700);
            vertical-align: top;
        }

        .rating-row:hover {
            background: #f8fafc;
        }

        .rating-action {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.4rem 0.75rem;
            border-radius: 999px;
            border: 1px solid #f1f5f9;
            background: #fff7ed;
            color: #92400e;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .rating-action:hover {
            background: #ffedd5;
            border-color: #fed7aa;
        }

        .rating-comment {
            color: var(--gray-600);
            font-size: 0.9rem;
        }

        .rating-comment-row {
            display: none;
            background: #f8fafc;
        }

        .rating-comment-cell {
            padding: 1.25rem 1.5rem;
            color: var(--gray-700);
        }

        .rating-comment-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--gray-800);
        }

        .rating-comment-empty {
            color: var(--gray-400);
            font-style: italic;
        }

        @media (max-width: 768px) {
            .ratings-page {
                padding: 1.5rem;
            }

            .ratings-table th,
            .ratings-table td {
                padding: 0.75rem 0.6rem;
            }
        }
    </style>

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
