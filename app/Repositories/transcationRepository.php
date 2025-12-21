<?php

namespace App\Repositories;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class transcationRepository extends \App\Repositories\AccountRepository
{
    public  $repo;
    public function __construct(AccountRepository $repo)
    {
        $this->repo = $repo;
    }
    public function createScheduled(array $data, int $userId)
    {
        return Transaction::create([
            'user_id' => $userId,
            'from_account_id' => $data['from_account_id'] ?? null,
            'to_account_id' => $data['to_account_id'] ?? null,
            'amount' => $data['amount'],
            'type' => $data['type'],
            'status' => 'scheduled',
            'scheduled_at' => $data['scheduled_at'],
            'frequency' => $data['frequency'] ?? null,
        ]);
    }

    //تعديل حالة معاملة
    public function updateStatus($request,$transcation)
    {return
        $transcation->update([
            'status'=>$request->status
        ]);
    }

    public function getAll( )
    {
        return  Transaction::all();
    }
    public function getTransactions($userId)
    {
        return Transaction::where('user_id', $userId)->get(); // ⬅️ أضف get()
    }
    // ⬇️ عدل هذه الدالة لتأخذ userId كباراميتر
    public function createTransaction(array $data, int $userId)
    {
        return Transaction::create([
            'from_account_id' => $data['from_account_id'] ?? null,
            'to_account_id' => $data['to_account_id'] ?? null,
            'amount' => $data['amount'],
            'user_id' => $userId, // ⬅️ من الباراميتر
            'type' => $data['type'],
        ]);
    }

}
