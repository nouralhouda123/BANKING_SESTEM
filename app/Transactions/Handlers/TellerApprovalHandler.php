<?php
namespace App\Transactions\Handlers;

use App\Models\Transaction;

class TellerApprovalHandler extends BaseHandler
{
    protected function check(Transaction $transaction)
    {
        if ($transaction->amount >= 500 && $transaction->amount <= 5000) {
            $transaction->status = 'pending';
            $transaction->save();

            return false;
        }
        return true;
    }
}
