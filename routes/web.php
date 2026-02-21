<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;

use App\Http\Controllers\DebugController;

// Authentication
Route::get('/login', [AuthController::class, 'showLoginChoice'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/password/forgot', fn() => view('auth.forgot-password'))->name('password.request');
Route::post('/password/forgot', [AuthController::class, 'sendResetLink'])->name('password.email');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::post('/profile/photo', [UserController::class, 'updatePhoto'])->name('profile.update-photo');
    Route::put('/profile', [UserController::class, 'update'])->name('profile.update');

    // Books routes
    Route::resource('books', BookController::class);

    Route::get('loans/create/{book}', [LoanController::class, 'create'])->name('loans.create');
    Route::post('loans/store/{book}', [LoanController::class, 'store'])->name('loans.store');
    Route::post('books/{book}/request', [LoanController::class, 'request'])->name('books.request');
    Route::get('loans/history', [LoanController::class, 'history'])->name('loans.history');
    Route::get('loans/{loan}', [LoanController::class, 'show'])->name('loans.show');
    Route::get('loans/{loan}/print-pickup-code', [LoanController::class, 'printPickupCode'])->name('loans.print-pickup-code');
    Route::get('loans/{loan}/print-detail', [LoanController::class, 'printDetail'])->name('loans.print-detail');
    Route::post('loans/{loan}/return', [LoanController::class, 'return'])->name('loans.return');
    Route::post('loans/{loan}/cancel', [LoanController::class, 'cancel'])->name('loans.cancel');
    Route::post('loans/{loan}/extend', [LoanController::class, 'extend'])->name('loans.extend');

    Route::post('reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/api/books/{book}/reviews', [ReviewController::class, 'getBookReviews'])->name('reviews.get');
    Route::get('/api/loans/{loan}/review', [ReviewController::class, 'getUserReview'])->name('reviews.user');

    // Wishlist routes
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{book}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::get('/api/wishlist/check/{book}', [WishlistController::class, 'check'])->name('wishlist.check');

    // admin & petugas routes
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/admin/petugas', [AdminController::class, 'createPetugas'])->name('admin.petugas.create');
        Route::get('/admin/users', [AdminController::class, 'userList'])->name('admin.users');
        Route::get('/admin/users/manage', [AdminController::class, 'manageUsers'])->name('admin.users.manage');
        Route::get('/admin/users/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/admin/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::patch('/admin/users/{user}/status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.status');
        Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        Route::get('/admin/ratings', [ReviewController::class, 'indexAdmin'])->name('admin.ratings');
        Route::get('/admin/reports/loans/pdf', [ReportController::class, 'loansPdf'])->name('admin.reports.loans.pdf');
        Route::get('/admin/reports/loans/history', [ReportController::class, 'loansHistory'])->name('admin.reports.loans.history');
        Route::get('/admin/reports/loans/history/excel', [ReportController::class, 'loansHistoryExcel'])->name('admin.reports.loans.excel');

        // Add petugas features to admin
        Route::get('/admin/petugas-dashboard', [AdminController::class, 'petugasDashboard'])->name('admin.petugas-dashboard');
        Route::get('/admin/kelola-buku', [BookController::class, 'manageBuku'])->name('admin.kelola-buku');
        Route::get('/admin/loans/{loan}', [LoanController::class, 'petugasShow'])->name('admin.loans.show');

        // Petugas-only loan actions for admin
        Route::post('/admin/loans/{loan}/approve', [LoanController::class, 'approve'])->name('admin.loans.approve');
        Route::post('/admin/loans/{loan}/reject', [LoanController::class, 'reject'])->name('admin.loans.reject');
        Route::post('/admin/loans/{loan}/mark-picked-up', [LoanController::class, 'markPickedUp'])->name('admin.loans.mark-picked-up');
        Route::post('/admin/loans/return-by-barcode', [LoanController::class, 'returnedByBarcode'])->name('admin.loans.return-barcode');
        Route::post('/admin/loans/{loan}/confirm-return-code', [LoanController::class, 'confirmReturnCode'])->name('admin.loans.confirm-return-code');
        Route::post('/admin/loans/{loan}/mark-lost', [LoanController::class, 'markLost'])->name('admin.loans.mark-lost');

        Route::delete('/admin/books/{book}', [BookController::class, 'destroy'])->name('admin.books.destroy');
        Route::put('/admin/books/{book}', [BookController::class, 'update'])->name('admin.books.update');
    });

    Route::middleware('role:petugas')->group(function () {
        Route::get('/petugas', [AdminController::class, 'petugasDashboard'])->name('petugas.dashboard');
        Route::get('/petugas/kelola-buku', [BookController::class, 'manageBuku'])->name('petugas.kelola-buku');
        Route::get('/petugas/ratings', [ReviewController::class, 'indexPetugas'])->name('petugas.ratings');
        Route::get('/petugas/reports/loans/history', [ReportController::class, 'loansHistoryPetugas'])->name('petugas.reports.loans.history');
        Route::get('/petugas/reports/loans/history/excel', [ReportController::class, 'loansHistoryExcelPetugas'])->name('petugas.reports.loans.excel');
        Route::get('/petugas/loans/{loan}', [LoanController::class, 'petugasShow'])->name('petugas.loans.show');

        // Petugas-only loan actions
        Route::post('loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
        Route::post('loans/{loan}/reject', [LoanController::class, 'reject'])->name('loans.reject');
        Route::post('loans/{loan}/mark-picked-up', [LoanController::class, 'markPickedUp'])->name('loans.mark-picked-up');
        Route::post('loans/return-by-barcode', [LoanController::class, 'returnedByBarcode'])->name('loans.return-barcode');
        Route::post('loans/{loan}/confirm-return-code', [LoanController::class, 'confirmReturnCode'])->name('loans.confirm-return-code');

        Route::delete('/petugas/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');
        Route::put('/books/{book}', [BookController::class, 'update'])->name('books.update');
        
        // Petugas notifications
        Route::get('/petugas/notifications/get', [LoanController::class, 'getPetugasNotifications'])->name('petugas.notifications.get');
        Route::post('/petugas/notifications/mark-read', [LoanController::class, 'markPetugasNotificationsAsRead'])->name('petugas.notifications.mark-read');
    });
});

// API Routes for books
Route::middleware('auth')->group(function () {
    Route::get('/api/books/{book}', [BookController::class, 'apiShow']);
    
    // Notification routes
    Route::get('/notifications/get', [LoanController::class, 'getNotifications'])->name('notifications.get');
    Route::post('/notifications/mark-read', [LoanController::class, 'markNotificationsAsRead'])->name('notifications.mark-read');

    // Debug routes
    Route::get('/debug/reviews', [DebugController::class, 'checkReviews'])->name('debug.reviews');
    Route::get('/debug/logs', [DebugController::class, 'getLastLog'])->name('debug.logs');
});

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Public book cover image route (no auth required)
Route::get('books/{book}/cover', [BookController::class, 'cover'])->name('books.cover');
