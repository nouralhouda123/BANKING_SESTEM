<?php


use App\Models\Account;

class LoanInterestStrategy implements InterestStrategy
{
    public function calculate(Account $account): float
    {
        return $account->balance * 0.10;
    }
}
