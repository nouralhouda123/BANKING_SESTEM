<?php

namespace App\Repositories;

use App\Http\Requests\AccountRequest;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class AccountRepository
{

    public function findAccounts(int $userId): ?Account
    {
        return Account::where('id',$userId)->first();
    }

    public function find(int $userId): ?Account
    {
        $user = Auth::user();
        $userId=$user->id;
        return Account::where('user_id',$userId)->first();
    }
    public function updateStatusaccount($request,$Account)
    {
        return $Account->update([
            'status'=>$request->status
        ]);
    }

    public function updateaccount($request,$Account)
    {
        return $Account->update([
            'account_type' => $request->account_type,
            'type' => $request->type,
        ]);
    }

    public function createMain(int $userId,$request): Account
    {return Account::create([
            'user_id' => $userId,
            'account_type' => $request->account_type,
            'type' => $request->type,
            'balance' => $request->balance ?? 0,
            'parent_id' => null,
            'status' => 'active',
        ]);
    }

    public function findAccount(int $userId): ?Account
    {
        return Account::where('id',$userId)->first();
    }

    public function closeAccount($request,$parentModel)
    {
        return $parentModel->update([
            'status'=>'blocked'
        ]);
    }
    //حصول ع حساب الاب للموظف
    public function findByuser_idForEm( $userId)
    {
        return Account::where('id',$userId)->first();
    }

//حصول ع حساب الاب
    public function findByuser_id(int $userId)
    {
        $user = Auth::user();
        $userId=$user->id;
        return Account::where('user_id',$userId)->first();
    }
//حصول ع حساب الابن
    public function findByParent_id(int $userId)
    {
        return Account::where('parent_id',$userId)->get();
    }
    public function getAllaccount( )
    {
        return Account::all();
    }


    public function createSub(int $userId, AccountRequest $request,$account_id): Account
    {
        return Account::create(  [
            'user_id' => $userId,
            'type' => $request->type,
            'parent_id' => $account_id,
            'balance' => $request->balance,
            'account_type' => $request->account_type,
            'status' => 'active',

        ]);
    }

    public function update(Account $account): void
    {
        $account->save();
    }
}
