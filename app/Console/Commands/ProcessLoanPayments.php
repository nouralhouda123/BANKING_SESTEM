<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\transcationService;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProcessLoanPayments extends Command
{
    protected $signature = 'app:process-loan-payments';
    protected $description = 'Process scheduled/recurring loan payments';
    protected transcationService $transactionService;

    public function __construct(transcationService $transactionService)
    {
        parent::__construct();
        $this->transactionService = $transactionService;
    }
    public function handle()
    {
        $transactions = Transaction::where('status', 'scheduled')
            ->where('scheduled_at', '<=', now())
            ->get();

        foreach ($transactions as $transaction) {
            DB::transaction(function () use ($transaction) {
                $transaction->status = 'pending';
                $transaction->save();

                // تمرير المعاملة لسلسلة المعالجة
                $this->transactionService->buildChain()->handle($transaction);

                // إذا كانت متكررة، تحديث الموعد التالي
                if ($transaction->frequency) {
                    $transaction->scheduled_at = $this->calculateNextRun(
                        $transaction->scheduled_at,
                        $transaction->frequency
                    );
                    $transaction->status = 'scheduled';
                    $transaction->save();
                }
            });

            $this->info("Processed scheduled transaction #{$transaction->id}");
        }
    }

    private function calculateNextRun($currentDate, $frequency)
    {
        return match ($frequency) {
            'daily' => Carbon::parse($currentDate)->addDay(),
            'weekly' => Carbon::parse($currentDate)->addWeek(),
            'monthly' => Carbon::parse($currentDate)->addMonth(),
            default => null,
        };
    }
}
