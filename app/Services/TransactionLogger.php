<?php


namespace App\Services;


use App\Models\Transaction;
use App\Models\TransactionLog;
use Illuminate\Support\Facades\Auth;

class TransactionLogger
{

    public static function log(
        Transaction $transaction,
        string $action,
        ?string $description = null,
        $oldData = null,
        $newData = null
    ): void {
        TransactionLog::create([
            'transaction_id' => $transaction->id,
            'user_id'        => Auth::id(),
            'action'         => $action,
            'description'    => $description,
            'old_data'       => $oldData ? json_encode($oldData) : null,
            'new_data'       => $newData ? json_encode($newData) : null,
        ]);
    }


}
