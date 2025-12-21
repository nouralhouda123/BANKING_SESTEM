<?php

namespace App\Transactions\Handlers;

use App\Models\Transaction;

class ValidationHandler extends BaseHandler
{
    protected function check(Transaction $transaction)
    {
        // 1. تحقق من أن المبلغ أكبر من 0
        if ($transaction->amount <= 0) {
            throw new \Exception("Amount must be greater than zero.");
        }

        // 2. تحقق من نوع العملية
        if (!in_array($transaction->type, [
            Transaction::TYPE_DEPOSIT,
            Transaction::TYPE_WITHDRAW,
            Transaction::TYPE_TRANSFER,
        ])) {
            throw new \Exception("Invalid transaction type.");
        }

        // 3. سحب → يجب وجود from_account فقط
        if ($transaction->type === Transaction::TYPE_WITHDRAW) {
            if (!$transaction->from_account_id) {
                throw new \Exception("Withdraw operation requires from_account_id.");
            }
        }

        // 4. إيداع → يجب وجود to_account فقط
        if ($transaction->type === Transaction::TYPE_DEPOSIT) {
            if (!$transaction->to_account_id) {
                throw new \Exception("Deposit operation requires to_account_id.");
            }
        }

        // 5. تحويل → يجب وجود الحسابين
        if ($transaction->type === Transaction::TYPE_TRANSFER) {
            if (!$transaction->from_account_id || !$transaction->to_account_id) {
                throw new \Exception("Transfer operation requires both from_account_id and to_account_id.");
            }
        }
    }
}
