<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Loan;
use Illuminate\Support\Facades\Auth;

class DebugController extends Controller
{
    public function checkReviews()
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Not authenticated']);
        }

        $userId = Auth::id();
        $reviews = Review::where('user_id', $userId)->get();
        $loans = Loan::where('user_id', $userId)->where('status', 'returned')->get();

        return response()->json([
            'user_id' => $userId,
            'reviews_count' => $reviews->count(),
            'reviews' => $reviews,
            'returned_loans_count' => $loans->count(),
            'returned_loans' => $loans->map(fn($l) => [
                'id' => $l->id,
                'book_id' => $l->book_id,
                'book_title' => $l->book?->title,
                'status' => $l->status,
            ]),
        ]);
    }

    public function getLastLog()
    {
        $logFile = storage_path('logs/laravel.log');
        if (!file_exists($logFile)) {
            return 'Log file not found';
        }

        $lines = file($logFile);
        $lastLines = array_slice($lines, -50);
        
        return response()->json([
            'last_50_lines' => $lastLines,
        ]);
    }
}
