<?php
namespace App\Transactions\Handlers;

use App\Models\Transaction;

class AccountExistenceHandler extends BaseHandler
{
    protected function check(Transaction $transaction): bool
    {
        if (in_array($transaction->type, ['withdraw', 'transfer', 'deposit'])) {
            if (!$transaction->from_account_id || !$transaction->fromAccount) {
                return $this->reject($transaction, "Source account does not exist");
            }
        }

        if ($transaction->type === 'transfer') {
            if (!$transaction->to_account_id || !$transaction->toAccount) {
                return $this->reject($transaction, "Destination account does not exist");
            }
        }

        return true;
    }
}
