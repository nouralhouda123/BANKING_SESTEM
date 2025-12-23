<?php
namespace App\Transactions\Handlers;
use App\Services\TransactionLogger;

use App\Models\Transaction;
use App\Repositories\AccountRepository;
use App\Composition\AccountInterface;
use App\Composition\LeafAccount;
use App\Composition\CompositionAccount;
use App\Factories\AccountFeatureFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExcuationProccess extends BaseHandler
{
    protected AccountRepository $repo;

    public function __construct(AccountRepository $repo)
    {
        $this->repo = $repo;
    }

    protected function check(Transaction $transaction): bool
    {
        if ($transaction->status !== 'approved'&& $transaction->type !== 'interest') return true;


        try {
            DB::transaction(function () use ($transaction) {
                match ($transaction->type) {
                    'deposit'  => $this->deposit($transaction),
                    'withdraw' => $this->withdraw($transaction),
                    'transfer' => $this->transfer($transaction),
                    'interest' => $this->applyInterest($transaction), // ← أضيفي هذا
                    default    => throw new \LogicException("Invalid transaction type"),
                };

                $transaction->status = 'completed';
                $transaction->save();
                ////الاضافة لل
                // 🔹 AUDIT LOG — execution success
                TransactionLogger::log(
                    $transaction,
                    'executed',
                    'Transaction executed successfully',
                    ['previous_status' => 'approved'],
                    ['current_status' => 'completed']
                );
            });

            return true;
        } catch (\Throwable $e) {
            // 🔹 AUDIT LOG — execution failed
            TransactionLogger::log(
                $transaction,
                'failed',
                $e->getMessage()
            );

            return $this->reject($transaction, $e->getMessage());
        }
    }

    private function deposit(Transaction $transaction): void
    {
        $to = $this->repo->find($transaction->to_account_id);
        $wrapped = $this->wrapWithFeatures($to);
        $wrapped->deposit($transaction->amount);
        $this->persist($wrapped);
    }

    private function withdraw(Transaction $transaction): void
    {
        $from = $this->repo->find($transaction->from_account_id);
        if (!$from || $from->user_id !== Auth::id()) {
            throw new \LogicException("Unauthorized");
        }

        $wrapped = $this->wrapWithFeatures($from);
        $wrapped->withdraw($transaction->amount);
        $this->persist($wrapped);
    }

    private function transfer(Transaction $transaction): void
    {
        $from = $this->repo->find($transaction->from_account_id);
        $to   = $this->repo->find($transaction->to_account_id);
        if (!$from || !$to) throw new \LogicException("Source or destination account not found");

        $fromWrapped = $this->wrapWithFeatures($from);
        $toWrapped   = $this->wrapWithFeatures($to);

        DB::transaction(function () use ($fromWrapped, $toWrapped, $transaction) {
            $fromWrapped->transfer($toWrapped, $transaction->amount);
        });

        $this->persist($fromWrapped);
        $this->persist($toWrapped);
    }

    private function wrap($accountModel): AccountInterface
    {
        if (!$accountModel->relationLoaded('children')) {
            $accountModel->load('children');
        }

        return $accountModel->children->isEmpty()
            ? new LeafAccount($accountModel)
            : new CompositionAccount($accountModel);
    }

    private function wrapWithFeatures($accountModel): AccountInterface
    {
        $account = $this->wrap($accountModel);
        return AccountFeatureFactory::apply($account, $accountModel->type);
    }

    private function persist(AccountInterface $account): void
    {
        $account->getModel()->save();
    }








    private function applyInterest(Transaction $transaction): void
    {
        $to = $this->repo->find($transaction->to_account_id);

        if (!$to) {
            throw new \LogicException("Target account not found for interest");
        }

        // الفائدة تعامل كإيداع
        $to->balance += $transaction->amount;
        $to->save();
    }

}
