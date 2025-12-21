<?php


namespace App\Transactions\Handlers;


use App\Models\Transaction;

class BalanceCheckHandler
{

protected function check(Transaction $transaction){

    if($transaction->type === Transaction::TYPE_WITHDRAW || $transaction->type === Transaction::TYPE_TRANSFER){


        $fromAccount = $transaction->fromAccount;
        if(! $fromAccount){
            throw new \Exception("From account not found.");

        }

        if($fromAccount->balance < $transaction->amount){
            throw new \Exception("Insufficient balance.");
        }

    }




}




}
