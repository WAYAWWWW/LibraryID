<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loan;
use Carbon\Carbon;

class ComputeFines extends Command
{
    protected $signature = 'loans:compute-fines';
    protected $description = 'Compute fines for overdue loans';

    public function handle()
    {
        $perDay = 30000; // RP per day
        $query = Loan::whereIn('status', ['active', 'returning']);

        $query->chunkById(200, function ($loans) use ($perDay) {
            foreach ($loans as $loan) {
                $due = $loan->due_date ? Carbon::parse($loan->due_date) : null;
                if (!$due) {
                    continue;
                }

                if (Carbon::now()->greaterThan($due)) {
                    $days = Carbon::now()->diffInDays($due);
                    $loan->fine = $days * $perDay;
                } else {
                    $loan->fine = 0;
                }

                $loan->save();
            }
        });
        $this->info('Fines computed.');
        return 0;
    }
}
