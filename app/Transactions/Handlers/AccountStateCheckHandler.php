<?php
namespace App\Transactions\Handlers;

use App\Models\Transaction;

class AccountStateCheckHandler extends BaseHandler
{
    protected function check(Transaction $transaction): bool
    {
        if ($transaction->from_account_id) {
            $account = $transaction->fromAccount;

            if ($transaction->type === 'withdraw' && !$account->state->canWithdraw()) {
                return $this->reject(
                    $transaction,
                    "The account does not allow withdrawals ({$account->state->getName()})"
                );
            }

            if ($transaction->type === 'transfer' && !$account->state->canTransfer()) {
                return $this->reject(
                    $transaction,
                    "The account does not allow transfers ({$account->state->getName()})"
                );
            }
        }

        if ($transaction->to_account_id && in_array($transaction->type, ['deposit', 'transfer'])) {
            $account = $transaction->toAccount;

            if (!$account->state->canDeposit()) {
                return $this->reject(
                    $transaction,
                    "The account does not allow deposits ({$account->state->getName()})"
                );
            }
        }

        return true;
    }
}
