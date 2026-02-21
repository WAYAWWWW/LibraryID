<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'user_id',
        'book_id',
        'status',
        'borrow_date',
        'due_date',
        'return_date',
        'fine',
        'barcode',
        'return_code',
    ];

    protected $dates = [
        'borrow_date',
        'due_date',
        'return_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function hasReview(): bool
    {
        return $this->review()->exists();
    }

    public function isOverdue(): bool
    {
        if (! $this->due_date) return false;
        return now()->startOfDay()->gt($this->due_date);
    }

    public function computeFine(int $perDay = 30000): int
    {
        if (! $this->due_date) return 0;
        $due = \Illuminate\Support\Carbon::parse($this->due_date)->startOfDay();
        $now = now()->startOfDay();
        if ($now->lte($due)) return 0;
        $days = $due->diffInDays($now, true);
        return $days * $perDay;
    }

    public static function syncOverdueFines(int $perDay = 30000): void
    {
        self::whereIn('status', ['active', 'returning'])
            ->whereNotNull('due_date')
            ->whereDate('due_date', '<', now()->toDateString())
            ->chunkById(200, function ($loans) use ($perDay) {
                foreach ($loans as $loan) {
                    $fine = $loan->computeFine($perDay);
                    if ((int) $loan->fine !== (int) $fine) {
                        $loan->fine = $fine;
                        $loan->save();
                    }
                }
            });
    }

    public static function syncReturnedFines(int $perDay = 30000): void
    {
        self::where('status', 'returned')
            ->whereNotNull('return_date')
            ->chunkById(200, function ($loans) use ($perDay) {
                foreach ($loans as $loan) {
                    if ($loan->due_date) {
                        $due = \Illuminate\Support\Carbon::parse($loan->due_date)->startOfDay();
                    } elseif ($loan->borrow_date) {
                        $due = \Illuminate\Support\Carbon::parse($loan->borrow_date)->startOfDay()->addDays(14);
                    } else {
                        continue;
                    }

                    $returned = \Illuminate\Support\Carbon::parse($loan->return_date)->startOfDay();

                    $fine = $returned->gt($due) ? $due->diffInDays($returned, true) * $perDay : 0;
                    if ((int) $loan->fine !== (int) $fine) {
                        $loan->fine = $fine;
                        $loan->save();
                    }
                }
            });
    }
}
