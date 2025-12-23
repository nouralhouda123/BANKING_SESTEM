<?php

namespace App\Services;

use App\Models\Account;
use App\Strategies\InterestStrategy;
use App\Strategies\SavingsInterestStrategy;
use App\Strategies\CheckingInterestStrategy;
use App\Strategies\LoanInterestStrategy;
use App\Strategies\InvestmentInterestStrategy;


class InterestCalculator
{
    /**
     * @var InterestStrategy
     */
    protected $strategy;

    public function __construct(Account $account)
    {
        $this->strategy = $this->resolveStrategy($account);
    }

    protected function resolveStrategy(Account $account)
    {
        if ($account->type === 'savings') {
            return new SavingsInterestStrategy();
        }

        if ($account->type === 'checking') {
            return new CheckingInterestStrategy();
        }

        if ($account->type === 'loan') {
            return new LoanInterestStrategy();
        }

        if ($account->type === 'investment') {
            return new InvestmentInterestStrategy();
        }

        throw new \Exception('Unsupported account type');
    }

    public function calculate(Account $account)
    {
        return $this->strategy->calculate($account);
    }
}
