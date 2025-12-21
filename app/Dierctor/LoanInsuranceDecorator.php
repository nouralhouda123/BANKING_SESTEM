<?php
namespace App\Dierctor;

class LoanInsuranceDecorator extends AccountDecorator
{
    protected float $interestRate = 0.05; // 5% دين سنوي

    public function deposit(float $amount): void
    {
        parent::deposit($amount);
        $this->applyLoanInterest();
    }

    public function withdraw(float $amount): void
    {
        parent::withdraw($amount);
        $this->applyLoanInterest();
    }

    private function applyLoanInterest(): void
    {
        $balance = $this->getBalance();
        $interest = $balance * $this->interestRate;
        $this->account->withdraw($interest);
    }
}
