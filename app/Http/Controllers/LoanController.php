<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\LoanApproved;
use PDF;

class LoanController extends Controller
{
    public function create(Book $book)
    {
        return view('loans.create', compact('book'));
    }

    public function store(Request $request, Book $book)
    {
        $user = Auth::user();
        if (!$user) return redirect('/login');

        // Check if user has active loans
        if ($user->hasActiveLoan()) {
            return back()->withErrors(['active_loan' => 'Anda masih memiliki peminjaman aktif. Harap kembalikan buku sebelumnya terlebih dahulu.']);
        }

        if ($book->stock < 1) {
            return back()->withErrors(['stock' => 'Stok tidak tersedia']);
        }

        // Validate input
        $data = $request->validate([
            'borrow_date' => 'required|date|date_format:Y-m-d',
            'due_date' => 'required|date|date_format:Y-m-d|after:borrow_date',
        ], [
            'borrow_date.required' => 'Tanggal peminjaman harus diisi',
            'borrow_date.date' => 'Format tanggal peminjaman tidak valid',
            'due_date.required' => 'Tanggal pengembalian harus diisi',
            'due_date.date' => 'Format tanggal pengembalian tidak valid',
            'due_date.after' => 'Tanggal pengembalian harus setelah tanggal peminjaman',
        ]);

        // Server-side validation: prevent past dates
        $today = date('Y-m-d');
        if ($data['borrow_date'] < $today) {
            return back()->withErrors(['borrow_date' => 'Tanggal peminjaman tidak boleh mundur']);
        }

        // Server-side validation: max 14 days
        $borrowDate = \Carbon\Carbon::parse($data['borrow_date']);
        $dueDate = \Carbon\Carbon::parse($data['due_date']);
        $daysDiff = $borrowDate->diffInDays($dueDate);

        if ($daysDiff > 14) {
            return back()->withErrors(['due_date' => 'Tanggal pengembalian maksimal 14 hari dari tanggal peminjaman']);
        }

        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'pending',
            'borrow_date' => $data['borrow_date'],
            'due_date' => $data['due_date'],
            'barcode' => Str::upper(Str::random(10)),
        ]);

        return redirect()->route('loans.show', $loan)
            ->with('status', 'Permintaan peminjaman berhasil dibuat. Menunggu konfirmasi dari admin/petugas.')
            ->with('showDetailModal', true);
    }

    public function request(Request $request, Book $book)
    {
        $user = Auth::user();
        if (!$user) return redirect('/login');

        // Check if user has active loans
        if ($user->hasActiveLoan()) {
            return back()->withErrors(['active_loan' => 'Anda masih memiliki peminjaman aktif. Harap kembalikan buku sebelumnya terlebih dahulu.']);
        }

        if ($book->stock < 1) {
            return back()->withErrors(['stock' => 'Stok tidak tersedia']);
        }

        $data = $request->validate([
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after:borrow_date',
        ]);

        // server-side prevent past dates
        if (strtotime($data['borrow_date']) < strtotime(date('Y-m-d'))) {
            return back()->withErrors(['borrow_date' => 'Tanggal peminjaman tidak boleh mundur']);
        }

        $loan = Loan::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'pending',
            'borrow_date' => $data['borrow_date'],
            'due_date' => $data['due_date'],
            'barcode' => Str::upper(Str::random(10)),
        ]);

        return redirect()->route('loans.show', $loan);
    }

    public function show(Loan $loan)
    {
        return view('loans.show', compact('loan'));
    }

    public function approve(Loan $loan, Request $request)
    {
        // Status must be pending to approve
        if ($loan->status !== 'pending') {
            return back()->withErrors(['error' => 'Hanya peminjaman dengan status menunggu yang dapat disetujui']);
        }

        $loan->status = 'approved';
        $loan->save();

        // Decrement book stock when approved (reserved for user)
        $book = $loan->book;
        if ($book && $book->stock > 0) {
            $book->decrement('stock');
        }

        // Send notification email
        try{
            Mail::to($loan->user->email)->send(new LoanApproved($loan));
        } catch(\Exception $e){
            // ignore mail errors for now
        }

        return redirect()->back()->with('status','Peminjaman disetujui! Status: Menunggu Pengambilan. Berikan Kode Peminjaman kepada user.');
    }

    public function reject(Loan $loan)
    {
        $loan->status = 'rejected';
        $loan->save();
        return redirect()->back();
    }

    public function returnedByBarcode(Request $request)
    {
        $barcode = $request->input('barcode');

        // Prioritaskan kode pengembalian (return_code) yang dibawa user
        $loan = Loan::where('return_code', $barcode)->where('status', 'returning')->first();

        // Jika tidak ada, fallback ke barcode lama untuk kompatibilitas
        if (!$loan) {
            $loan = Loan::where('barcode', $barcode)->where('status', 'active')->first();
        }

        if (!$loan) {
            return back()->withErrors(['barcode' => 'Kode tidak ditemukan atau status peminjaman tidak valid untuk pengembalian']);
        }

        $loan->status = 'returned';
        $loan->return_date = now();
        
        // Calculate fine if overdue
        if ($loan->return_date) {
            $dueDate = $loan->due_date
                ? \Carbon\Carbon::parse($loan->due_date)->startOfDay()
                : ($loan->borrow_date ? \Carbon\Carbon::parse($loan->borrow_date)->startOfDay()->addDays(14) : null);

            if ($dueDate && $loan->return_date->startOfDay()->gt($dueDate)) {
                $overduedays = $dueDate->diffInDays($loan->return_date->startOfDay(), true);
                $loan->fine = $overduedays * 30000; // 30000 per hari
            } else {
                $loan->fine = 0;
            }
        }
        
        $loan->save();

        // increment stock
        $book = $loan->book;
        if ($book) $book->increment('stock');

        $message = 'Buku berhasil dikonfirmasi dikembalikan dengan kode: ' . $barcode;
        if ($loan->fine > 0) {
            $message .= '. Denda: Rp ' . number_format($loan->fine, 0, ',', '.');
        }

        return redirect()->back()->with('status', $message);
    }

    public function history()
    {
        $user = Auth::user();

        Loan::syncOverdueFines();
        Loan::syncReturnedFines();
        
        // Get all loans for the user
        $loans = Loan::where('user_id', $user->id)->with(['book'])->latest()->paginate(15);
        
        // Calculate statistics
        $totalLoans = Loan::where('user_id', $user->id)->count();
        $activeLoans = Loan::where('user_id', $user->id)
                            ->whereIn('status', ['pending', 'approved', 'active'])
                            ->count();
        $returnedLoans = Loan::where('user_id', $user->id)
                             ->where('status', 'returned')
                             ->count();
        $overdueLoans = Loan::where('user_id', $user->id)
                            ->where('status', 'active')
                            ->where('due_date', '<', now())
                            ->count();
        
        return view('loans.history', compact(
            'loans',
            'totalLoans',
            'activeLoans', 
            'returnedLoans',
            'overdueLoans'
        ));
    }

    public function return(Loan $loan)
    {
        // Check authorization
        if ($loan->user_id !== Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak memiliki akses']);
        }

        // Check status - only active loans can be returned
        if ($loan->status !== 'active') {
            return back()->withErrors(['error' => 'Hanya peminjaman yang sedang berlangsung dapat dikembalikan']);
        }

        // Generate return code (6 character random)
        $returnCode = strtoupper(Str::random(6));
        
        $loan->status = 'returning';
        $loan->return_code = $returnCode;
        $loan->save();

        // Redirect to return code confirmation page
        return redirect()->route('loans.show', $loan)->with('status', 'Proses pengembalian dimulai. Tunjukkan kode di bawah ke petugas.');
    }

    public function confirmReturnCode(Loan $loan, Request $request)
    {
        // Check status - must be 'returning'
        if ($loan->status !== 'returning') {
            return back()->withErrors(['error' => 'Peminjaman tidak dalam proses pengembalian']);
        }

        $request->validate([
            'return_code' => 'required|string',
        ]);

        // Verify return code
        if ($request->return_code !== $loan->return_code) {
            return back()->withErrors(['error' => 'Kode pengembalian tidak sesuai']);
        }

        // Complete return process
        $loan->status = 'returned';
        $loan->return_date = now();
        
        // Calculate fine if overdue
        if ($loan->return_date) {
            $dueDate = $loan->due_date
                ? \Carbon\Carbon::parse($loan->due_date)->startOfDay()
                : ($loan->borrow_date ? \Carbon\Carbon::parse($loan->borrow_date)->startOfDay()->addDays(14) : null);

            if ($dueDate && $loan->return_date->startOfDay()->gt($dueDate)) {
                $overduedays = $dueDate->diffInDays($loan->return_date->startOfDay(), true);
                $loan->fine = $overduedays * 30000; // 30000 per hari
            } else {
                $loan->fine = 0;
            }
        }
        
        $loan->save();

        // Increment book stock
        if ($loan->book) {
            $loan->book->increment('stock');
        }

        $message = 'Pengembalian buku berhasil dikonfirmasi!';
        if ($loan->fine > 0) {
            $message .= ' Denda: Rp ' . number_format($loan->fine, 0, ',', '.');
        }

        return back()->with('status', $message);
    }

    public function cancel(Loan $loan)
    {
        // Check authorization
        if ($loan->user_id !== Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak memiliki akses']);
        }

        // Check status
        if (!in_array($loan->status, ['pending', 'booked'])) {
            return back()->withErrors(['error' => 'Hanya pemesanan yang belum disetujui dapat dibatalkan']);
        }

        $loan->status = 'cancelled';
        $loan->save();

        if (request()->expectsJson()) {
            return response()->json(['message' => 'Pesanan berhasil dibatalkan', 'status' => 'success']);
        }

        return back()->with('status', 'Pesanan berhasil dibatalkan');
    }

    public function markPickedUp(Loan $loan)
    {
        // Status must be approved to mark as picked up
        if ($loan->status !== 'approved') {
            return back()->withErrors(['error' => 'Hanya peminjaman yang sudah disetujui dapat diambil']);
        }

        // Set borrow_date and status to active when user picks up the book
        $loan->status = 'active';
        $loan->borrow_date = now();
        $loan->save();

        return back()->with('success', 'Buku berhasil diambil! Peminjaman dimulai sejak hari ini.');
    }

    public function markLost(Loan $loan)
    {
        // Only active or pending loans can be marked as lost
        if (!in_array($loan->status, ['active', 'approved', 'picked-up', 'pending'])) {
            return response()->json(['error' => 'Hanya peminjaman aktif yang dapat ditandai sebagai hilang'], 400);
        }

        // Calculate replacement cost (assuming book price or a standard replacement fee)
        // You may need to adjust this based on your business logic
        $replacementFine = 500000; // 500,000 IDR default replacement cost

        // Mark loan as lost
        $loan->status = 'lost';
        $loan->fine = $replacementFine;
        $loan->save();

        // Return stock to available
        $loan->book->stock += 1;
        $loan->book->save();

        return response()->json(['success' => 'Buku berhasil ditandai sebagai hilang']);
    }

    public function petugasShow(Loan $loan)
    {
        $loan->load(['user', 'book']);
        return view('petugas.loans.show', compact('loan'));
    }

    /**
     * Get overdue notifications for current user
     */
    public function getNotifications()
    {
        $user = Auth::user();
        
        // Get overdue active loans
        $overdueLoans = Loan::where('user_id', $user->id)
            ->where('status', 'active')
            ->whereDate('due_date', '<', now()->toDateString())
            ->with('book')
            ->orderBy('due_date', 'asc')
            ->get();

        // Format notifications
        $notifications = $overdueLoans->map(function($loan) {
            $daysOverdue = now()->diffInDays($loan->due_date);
            
            return [
                'id' => $loan->id,
                'book_title' => $loan->book->title,
                'days_overdue' => abs($daysOverdue),
                'is_read' => false,
                'created_at' => $loan->updated_at->toIso8601String(),
                'due_date' => $loan->due_date,
                'loan_id' => $loan->id
            ];
        });

        return response()->json([
            'unread_count' => count($notifications),
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark notifications as read
     */
    public function markNotificationsAsRead()
    {
        $user = Auth::user();
        
        // Update session to mark notifications as read
        session(['notifications_read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi telah ditandai sebagai dibaca'
        ]);
    }

    /**
     * Get notifications untuk petugas
     * Menampilkan:
     * 1. Pending loans (perlu dikonfirmasi)
     * 2. Overdue loans (belum dikembalikan dan terlambat)
     */
    public function getPetugasNotifications()
    {
        $notifications = collect();

        // 1. Get pending loans (perlu dikonfirmasi admin/petugas)
        $pendingLoans = Loan::where('status', 'pending')
            ->with(['user', 'book'])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($pendingLoans as $loan) {
            $notifications->push([
                'id' => $loan->id,
                'type' => 'pending',
                'user_name' => $loan->user->name,
                'user_id' => $loan->user->id,
                'book_title' => $loan->book->title,
                'message' => 'Meminta konfirmasi peminjaman',
                'is_read' => false,
                'created_at' => $loan->created_at->toIso8601String(),
                'loan_id' => $loan->id
            ]);
        }

        // 2. Get overdue active loans (belum dikembalikan dan sudah terlambat)
        $overdueLoans = Loan::where('status', 'active')
            ->whereDate('due_date', '<', now()->toDateString())
            ->with(['user', 'book'])
            ->orderBy('due_date', 'asc')
            ->get();

        foreach ($overdueLoans as $loan) {
            $daysOverdue = now()->diffInDays($loan->due_date);
            $notifications->push([
                'id' => $loan->id,
                'type' => 'overdue',
                'user_name' => $loan->user->name,
                'user_id' => $loan->user->id,
                'book_title' => $loan->book->title,
                'message' => 'Terlambat ' . abs($daysOverdue) . ' hari mengembalikan buku',
                'days_overdue' => abs($daysOverdue),
                'is_read' => false,
                'created_at' => $loan->updated_at->toIso8601String(),
                'due_date' => $loan->due_date->toDateString(),
                'loan_id' => $loan->id
            ]);
        }

        // Sort by created_at descending
        $notifications = $notifications->sortByDesc('created_at')->values();

        return response()->json([
            'unread_count' => count($notifications),
            'notifications' => $notifications
        ]);
    }

    /**
     * Mark petugas notifications as read
     */
    public function markPetugasNotificationsAsRead()
    {
        session(['petugas_notifications_read_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi telah ditandai sebagai dibaca'
        ]);
    }

    /**
     * Print loan pickup code to PDF
     */
    public function printPickupCode(Loan $loan)
    {
        // Check authorization - user can only print their own loans
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        // Only approved loans can be printed
        if ($loan->status !== 'approved') {
            abort(403, 'Hanya peminjaman dengan status "Siap Diambil" yang dapat dicetak');
        }

        $pdf = PDF::loadView('loans.show', ['loan' => $loan])
            ->setPaper('A4')
            ->setOption('margin-top', 15)
            ->setOption('margin-right', 15)
            ->setOption('margin-bottom', 15)
            ->setOption('margin-left', 15)
            ->setOption('print-media-type', true);
        
        $filename = 'kode-pengambilan-' . $loan->barcode . '-' . now()->format('Ymd-His') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Print loan detail to PDF (for all statuses)
     */
    public function printDetail(Loan $loan)
    {
        // Check authorization - user can only print their own loans
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        $pdf = PDF::loadView('loans.show', ['loan' => $loan])
            ->setPaper('A4')
            ->setOption('margin-top', 15)
            ->setOption('margin-right', 15)
            ->setOption('margin-bottom', 15)
            ->setOption('margin-left', 15)
            ->setOption('print-media-type', true);
        
        $filename = 'detail-peminjaman-' . $loan->id . '-' . now()->format('Ymd-His') . '.pdf';
        
        return $pdf->download($filename);
    }

    /**
     * Extend loan due date
     */
    public function extend(Request $request, Loan $loan)
    {
        // Check authorization - user can only extend their own loans
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        // Validate that loan is in active status
        if (!in_array($loan->status, ['pending', 'approved', 'picked-up'])) {
            return back()->withErrors(['extend' => 'Hanya peminjaman yang aktif yang dapat diperpanjang']);
        }

        // Validate input
        $data = $request->validate([
            'new_due_date' => 'required|date|date_format:Y-m-d|after:today',
            'reason' => 'nullable|string|max:500',
        ], [
            'new_due_date.required' => 'Tanggal pengembalian baru harus diisi',
            'new_due_date.date' => 'Format tanggal tidak valid',
            'new_due_date.after' => 'Tanggal pengembalian harus di masa depan',
        ]);

        // Validate that new due date is not before current due date
        if ($data['new_due_date'] <= $loan->due_date->format('Y-m-d')) {
            return back()->withErrors(['new_due_date' => 'Tanggal pengembalian baru harus setelah tanggal pengembalian saat ini']);
        }

        // Update loan due date
        $loan->update([
            'due_date' => $data['new_due_date'],
        ]);

        return redirect()->route('loans.show', $loan)->with('success', 'Perpanjangan peminjaman berhasil diajukan');
    }
}
