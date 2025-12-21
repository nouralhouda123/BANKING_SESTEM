<?php


namespace App\Strategies;


use App\Models\Account;

interface InterestStrategy
{
    public function calculate(Account $account);
}
