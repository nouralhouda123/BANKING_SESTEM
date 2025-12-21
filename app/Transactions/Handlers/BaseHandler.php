<?php

namespace App\Transactions\Handlers;

use App\Models\Transaction;

abstract class BaseHandler
{
    protected $next;

    // ربط الهاندلر التالي
    public function setNext(BaseHandler $handler): BaseHandler
    {
        $this->next = $handler;
        return $handler;
    }

    // تنفيذ الهاندلر
    public function handle(Transaction $transaction)
    {
        // تنفيذ الفحص الخاص بهذا الهاندلر
        $this->check($transaction);

        // إذا يوجد هاندلر بعده → أرسل له
        if ($this->next) {
            return $this->next->handle($transaction);
        }

        return $transaction;
    }

    // كل هاندلر يجب أن يكتب check()
    abstract protected function check(Transaction $transaction);
}
