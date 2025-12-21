<?php
namespace App\Transactions\Handlers;

use App\Models\Transaction;

abstract class BaseHandler
{
    protected ?BaseHandler $next = null;

    public function setNext(BaseHandler $handler): BaseHandler
    {
        $this->next = $handler;
        return $handler;
    }

    public function handle(Transaction $transaction)
    {
        if ($transaction->status === 'rejected') {
            return $transaction;
        }

        $continue = $this->check($transaction);

        if ($continue === false) {
            return $transaction;
        }

        if ($this->next) {
            return $this->next->handle($transaction);
        }

        return $transaction;
    }

    abstract protected function check(Transaction $transaction);

    protected function reject(Transaction $transaction, string $reason): bool
    {
        $transaction->status = 'rejected';
        $transaction->rejection_reason = $reason;
        $transaction->save();

        return false;
    }
}
