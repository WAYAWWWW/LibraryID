<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function indexAdmin()
    {
        $reviews = Review::with(['user', 'book'])
            ->whereNotNull('rating')
            ->latest()
            ->paginate(20);

        return view('admin.ratings', compact('reviews'));
    }

    public function indexPetugas()
    {
        $reviews = Review::with(['user', 'book'])
            ->whereNotNull('rating')
            ->latest()
            ->paginate(20);

        return view('petugas.ratings', compact('reviews'));
    }

    public function store(Request $request)
    {
        try {
            // Log incoming request
            \Log::info('Review store request:', [
                'user_id' => Auth::id(),
                'data' => $request->all(),
            ]);

            $validated = $request->validate([
                'loan_id' => 'required|exists:peminjaman,id',
                'book_id' => 'required|exists:buku,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string|max:500',
            ]);

            \Log::info('Validation passed:', $validated);

            // Verify that the loan belongs to the authenticated user
            $loan = Loan::findOrFail($validated['loan_id']);
            if ($loan->user_id !== Auth::id()) {
                \Log::warning('Unauthorized loan access attempt', [
                    'user_id' => Auth::id(),
                    'loan_user_id' => $loan->user_id,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses ke peminjaman ini',
                ], 403);
            }

            // Check if user already reviewed this book via this loan
            $existingReview = Review::where('loan_id', $validated['loan_id'])
                ->where('user_id', Auth::id())
                ->first();

            if ($existingReview) {
                // Update existing review
                $existingReview->update([
                    'rating' => $validated['rating'],
                    'comment' => $validated['comment'] ?? '',
                ]);
                $review = $existingReview;
                $message = 'Rating berhasil diperbarui';
                \Log::info('Review updated:', $review->toArray());
            } else {
                // Create new review
                $validated['user_id'] = Auth::id();
                $review = Review::create($validated);
                $message = 'Rating berhasil disimpan';
                \Log::info('Review created:', $review->toArray());
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'review' => $review,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Validation failed:', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', array_merge(...array_values($e->errors())) ?? []),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Review Store Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTrace(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getBookReviews($bookId)
    {
        $reviews = Review::where('book_id', $bookId)
            ->whereNotNull('rating')
            ->with('user')
            ->latest()
            ->get();

        $averageRating = Review::where('book_id', $bookId)
            ->whereNotNull('rating')
            ->avg('rating');

        $totalReviews = Review::where('book_id', $bookId)
            ->whereNotNull('rating')
            ->count();

        return response()->json([
            'success' => true,
            'averageRating' => $averageRating ? round($averageRating, 1) : 0,
            'totalReviews' => $totalReviews,
            'reviews' => $reviews,
        ]);
    }

    public function getUserReview($loanId)
    {
        $review = Review::where('loan_id', $loanId)
            ->where('user_id', Auth::id())
            ->first();

        return response()->json([
            'success' => true,
            'review' => $review,
        ]);
    }
}
