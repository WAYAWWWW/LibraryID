<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loan;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        // Get 5-7 most recent loans for this user
        $recentLoans = Loan::where('user_id', $user->id)
            ->with('book')
            ->orderBy('created_at', 'desc')
            ->limit(7)
            ->get();
        
        // Get 15 recommended books with highest average rating using subquery
        $booksWithRatings = \App\Models\Book::select('buku.*')
            ->selectRaw('(SELECT COALESCE(AVG(rating), 0) FROM ulasanbuku WHERE ulasanbuku.book_id = buku.id) as avg_rating')
            ->orderByRaw('(SELECT COALESCE(AVG(rating), 0) FROM ulasanbuku WHERE ulasanbuku.book_id = buku.id) DESC')
            ->limit(15)
            ->get();
        
        $recommendedBooks = $booksWithRatings;
        
        return view('dashboard', compact('recentLoans', 'recommendedBooks'));
    }

    public function profile()
    {
        $user = auth()->user();
        $loansCount = Loan::where('user_id', $user->id)->count();
        $activeLoansCount = Loan::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved', 'active', 'returning'])
            ->count();
        $user->loans_count = $loansCount;
        $user->active_loans_count = $activeLoansCount;

        return view('profile', compact('user'));
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = auth()->user();

        // Delete old photo if exists
        if ($user->profile_picture && file_exists(public_path('storage/' . $user->profile_picture))) {
            unlink(public_path('storage/' . $user->profile_picture));
        }

        // Store new photo
        if ($request->file('profile_photo')) {
            $file = $request->file('profile_photo');
            $fileName = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('profile-photos', $fileName, 'public');
            $user->update(['profile_picture' => $filePath]);
        }

        return redirect()->route('profile')->with('success', 'Foto profil berhasil diperbarui');
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:user,email,' . auth()->id(),
        ]);

        $user = auth()->user();
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui');
    }
}
