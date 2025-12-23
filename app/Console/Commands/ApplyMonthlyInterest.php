<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Account;
use App\Models\Transaction;
use App\Services\InterestCalculator;

class ApplyMonthlyInterest extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'interest:apply';

    /**
     * The console command description.
     */
    protected $description = 'Apply monthly interest to eligible accounts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
//        $accounts = Account::whereIn('type', ['savings', 'loan', 'investment'])
//            ->where('status', 'active')
//            ->where('account_type', 'leaf')
//            ->get();
//
//        foreach ($accounts as $account) {
//
//            $calculator = new InterestCalculator($account);
//            $interest = $calculator->calculate($account);
//
//            // تجاهل الفائدة الصفرية
//            if ($interest <= 0) {
//                continue;
//            }
//
//            // منع التكرار لنفس الشهر
//            $exists = Transaction::where('type', 'interest')
//                ->where('to_account_id', $account->id)
//                ->whereMonth('created_at', now()->month)
//                ->whereYear('created_at', now()->year)
//                ->exists();
//
//            if ($exists) {
//                continue;
//            }

            Transaction::create([
                'type' => 'interest',
                'to_account_id' => 1,
                'amount' => 55,
                'status' => 'approved',
            ]);
        //}

        $this->info('Monthly interest transactions created successfully.');

        return Command::SUCCESS;
    }
}
