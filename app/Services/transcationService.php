<?php
namespace App\Services;
use App\DTO\TransferResult;
use App\Http\Requests\ScheduledTransactionRequest;
use App\Http\Requests\TransferRequest;
use App\Models\Transaction;
use App\Repositories\AccountRepository;
use App\Repositories\transcationRepository;
use App\Transactions\Handlers\AccountExistenceHandler;
use App\Transactions\Handlers\AccountStateCheckHandler;
use App\Transactions\Handlers\ValidationHandler;
use App\Transactions\Handlers\BalanceCheckHandler;
use App\Transactions\Handlers\AutoApprovalHandler;
use App\Transactions\Handlers\TellerApprovalHandler;
use App\Transactions\Handlers\ManagerApprovalHandler;
use App\Transactions\Handlers\ExcuationProccess;
use Illuminate\Support\Facades\Auth;

class transcationService
{
    public transcationRepository $repo;

    public function __construct(NotificationAddTranscationWithBigBalance $notificationService,transcationRepository $repo)
    {
        $this->notificationService = $notificationService;

        $this->repo = $repo;
    }

    public function processTransfer(TransferRequest $request): TransferResult
    {
        $user = Auth::user();

        $transaction = $this->repo->createTransaction($request->validated(), $user->id);

        if ($transaction->amount >= 5000) {
            $this->quickNotify($transaction);
        }
        $this->buildChain()->handle($transaction);
        $transaction->refresh();

        return $this->buildResult($transaction);
    }

    private function quickNotify(Transaction $transaction)
    {
        $manager = \App\Models\User::role('director')->first();
        if ($manager) {
            $result = $this->notificationService->sendNotification(
                $manager,
                'Large Transaction Pending Approval',
                "Transaction #{$transaction->id} for amount {$transaction->amount} requires your approval.",
                'large_transaction',
                [
                    'transaction_id' => $transaction->id,
                    'amount' => $transaction->amount,
                    'from_account_id' => $transaction->from_account_id,
                    'to_account_id' => $transaction->to_account_id,
                    'type' => $transaction->type,
                ]
            );

        }
    }       //عرض كل معاملات
    public function getAll(){
        $transcation = $this->repo->getAll();
        return $transcation;
    }

    public function createScheduledTransaction(ScheduledTransactionRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        $transaction = $this->repo->createScheduled($data, $user->id);

        return $transaction;
    }

    public function buildChain()
    {
        $validation = new ValidationHandler();
        $existence = new AccountExistenceHandler();
        $stateCheck = new AccountStateCheckHandler();
        $balanceCheck = new BalanceCheckHandler();
        $autoApproval = new AutoApprovalHandler();
        $manager = new ManagerApprovalHandler($this->notificationService);
        $execution = new ExcuationProccess($this->repo);

        $validation
            ->setNext($existence)
            ->setNext($stateCheck)
            ->setNext($balanceCheck)
            ->setNext($autoApproval)
            ->setNext($manager)
            ->setNext($execution);
        return $validation;

    }
    private function buildResult(Transaction $transaction): TransferResult
    {
        $status = match ($transaction->status) {
            'completed', 'approved' => 'success',
            'pending'   => 'pending',
            'rejected'  => 'failed',
            default     => 'info',
        };

        $message = match ($transaction->status) {
            'pending' =>
            "معاملتك بقيمة {$transaction->amount} بانتظار الموافقة.",

            'completed', 'approved' => match($transaction->type) {
                'deposit' => "تم إيداع {$transaction->amount} بنجاح.",
                'withdraw' => "تم سحب {$transaction->amount} بنجاح.",
                'transfer' => "تم تحويل {$transaction->amount} بنجاح.",
                default => "تم تنفيذ المعاملة بنجاح."
            },

            'rejected' =>
            $transaction->rejection_reason ?: "تم رفض المعاملة.",

            default =>
            "تمت معالجة المعاملة.",
        };

        return new TransferResult($status, $message, $transaction);
    }
    public function get(){
        $user = Auth::user();
        $userId=$user->id;
        $parentModel = $this->repo->getTransactions($userId);
        return $parentModel;
    }

    //////////
    public function UpdateTransaction($request,$id): Transaction
    {
        $transaction = \App\Models\Transaction::find($id);
        if (!$transaction) throw new \Exception("Transaction not found");

        if ($transaction->status !== 'pending') {
            throw new \Exception("Transaction state must be pending to approve");
        }

      //  // تغيير الحالة للموافقة
     //   $transaction->status = 'approved';
        $transcation=$this->repo->updateStatus($request,$transaction);
    //    $transcation->save();

        // تمرير المعاملة للسلسلة ليتم تنفيذها
        $chain = $this->buildChain();
        $chain->handle($transaction);

        return $transaction->fresh();
    }

}
