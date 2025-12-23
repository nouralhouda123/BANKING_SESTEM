<?php

use App\Models\Account;

class CheckingInterestStrategy implements InterestStrategy
{
    public function calculate(Account $account): float
    {
        return 0;
    }
}

