<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display user's wishlist
     */
    public function index()
    {
        $user = Auth::user();
        $wishlistBooks = Wishlist::where('user_id', $user->id)
            ->with('book')
            ->latest()
            ->get()
            ->pluck('book');

        return view('wishlist.index', compact('wishlistBooks'));
    }

    /**
     * Add book to wishlist
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:buku,id',
        ]);

        $user = Auth::user();
        $bookId = $request->book_id;

        // Check if already in wishlist
        $exists = Wishlist::where('user_id', $user->id)
            ->where('book_id', $bookId)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Buku sudah ada di wishlist',
            ], 400);
        }

        try {
            Wishlist::create([
                'user_id' => $user->id,
                'book_id' => $bookId,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Buku ditambahkan ke wishlist',
            ]);
        } catch (\Exception $e) {
            \Log::error('Wishlist add error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan ke wishlist',
            ], 500);
        }
    }

    /**
     * Remove book from wishlist
     */
    public function destroy($bookId)
    {
        $user = Auth::user();

        try {
            Wishlist::where('user_id', $user->id)
                ->where('book_id', $bookId)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Buku dihapus dari wishlist',
            ]);
        } catch (\Exception $e) {
            \Log::error('Wishlist remove error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus dari wishlist',
            ], 500);
        }
    }

    /**
     * Check if book is in user's wishlist
     */
    public function check($bookId)
    {
        $user = Auth::user();

        $exists = Wishlist::where('user_id', $user->id)
            ->where('book_id', $bookId)
            ->exists();

        return response()->json([
            'success' => true,
            'inWishlist' => $exists,
        ]);
    }
}
