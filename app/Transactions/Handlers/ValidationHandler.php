<?php
namespace App\Transactions\Handlers;

use App\Models\Transaction;

class ValidationHandler extends BaseHandler
{
    protected function check(Transaction $transaction): bool
    {
        if ($transaction->amount <= 0) {
            return $this->reject($transaction, "Amount must be greater than zero");
        }

        if (!in_array($transaction->type, ['withdraw', 'deposit', 'transfer', 'interest'])) {
            return $this->reject($transaction, "Invalid transaction type");
        }

        return true;
    }
}
