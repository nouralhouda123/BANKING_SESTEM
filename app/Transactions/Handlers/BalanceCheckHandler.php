<?php
namespace App\Transactions\Handlers;

use App\Composition\LeafAccount;
use App\Composition\CompositionAccount;
use App\Factories\AccountFeatureFactory;
use App\Models\Transaction;
use App\Composition\AccountInterface;

class BalanceCheckHandler extends BaseHandler
{
    protected function check(Transaction $transaction): bool
    {
        // الإيداع لا يحتاج تحقق
        if ($transaction->type === 'deposit') return true;

        $accountModel = $transaction->fromAccount;

        // 1️⃣ بناء الحساب (Leaf أو Composite)
        $account = $accountModel->children->isEmpty()
            ? new LeafAccount($accountModel)
            : new CompositionAccount($accountModel);

        // 2️⃣ تطبيق الميزات عبر Factory
        $accountWithFeatures = AccountFeatureFactory::apply($account, $accountModel->type);

        // 3️⃣ تحقق الرصيد حسب الحساب وميزات الـ Decorator
        try {
            if ($transaction->type === 'withdraw') {
                $accountWithFeatures->withdraw($transaction->amount);
            } elseif ($transaction->type === 'transfer') {
                $toAccount = $transaction->toAccount;
                $targetAccount = $toAccount->children->isEmpty()
                    ? new LeafAccount($toAccount)
                    : new CompositionAccount($toAccount);

                $targetWithFeatures = AccountFeatureFactory::apply($targetAccount, $toAccount->type);

                $accountWithFeatures->transfer($targetWithFeatures, $transaction->amount);
            }

            // تحديث الرصيد الحقيقي للحسابات بعد المحاولة
            $accountModel->refresh();

            return true;
        } catch (\Exception $e) {
            return $this->reject($transaction, $e->getMessage());
        }
    }
}
