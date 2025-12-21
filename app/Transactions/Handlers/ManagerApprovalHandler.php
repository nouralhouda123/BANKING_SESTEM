<?php


namespace App\Transactions\Handlers;
use App\Models\Transaction;

class ManagerApprovalHandler extends BaseHandler
{

    protected function check(Transaction $transaction)
    {
        if ($transaction->amount > 5000) {
            $transaction->status = Transaction::STATUS_PENDING; // يحتاج موافقة Manager
        }
    }


}
