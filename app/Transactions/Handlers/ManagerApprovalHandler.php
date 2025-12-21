<?php
namespace App\Transactions\Handlers;

use App\Models\Transaction;
use App\Services\NotificationAddTranscationWithBigBalance;
use App\Models\User;
use Illuminate\Support\Facades\Log;


class ManagerApprovalHandler extends BaseHandler
{
    protected NotificationAddTranscationWithBigBalance $notificationService;

    public function __construct(NotificationAddTranscationWithBigBalance $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    protected function check(Transaction $transaction)
    {

        if ($transaction->status === 'approved') {
            \Log::info('Transaction already approved, skipping manager check');
            return true;
        }

        // إذا كانت معلقة مسبقاً، توقف السلسلة
        if ($transaction->status === 'pending') {
            \Log::info('Transaction already pending approval');
            return false;
        }

        // تحقق إذا كانت تحتاج موافقة مدير
        if ($transaction->amount > 5000) {
            \Log::info('Transaction requires manager approval', [
                'transaction_id' => $transaction->id,
                'amount' => $transaction->amount
            ]);

            // تغيير الحالة إلى pending
            $transaction->update(['status' => 'pending']);

            // إرسال إشعار للمدير
            $manager = User::role('director')->first();
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

                \Log::info('Notification sent to manager', [
                    'result' => $result,
                    'manager_id' => $manager->id
                ]);
            }

            // إرجاع false لوقف السلسلة
            return false;
        }

        // إذا لم تكن تحتاج موافقة، اتركها للسلسلة
        \Log::info('Transaction does not need manager approval');
        return true;
    }
}
