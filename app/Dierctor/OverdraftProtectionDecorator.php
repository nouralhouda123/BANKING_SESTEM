<?php
namespace App\Dierctor;

use App\Composition\AccountInterface;
use App\Dierctor\AccountDecorator;

class OverdraftProtectionDecorator extends AccountDecorator
{
    protected float $limit;
    protected float $interestRate = 0.005;

    public function __construct(AccountInterface $account, float $limit)
    {
        parent::__construct($account);
        $this->limit = $limit;
    }

    public function withdraw(float $amount): void
    {
        if ($this->getBalance() + $this->limit < $amount) {
            throw new \Exception("Insufficient funds with overdraft limit ({$this->limit})");
        }
        parent::withdraw($amount);
        $this->applyInterest();
    }

    public function deposit(float $amount): void
    {
        parent::deposit($amount);
        $this->applyInterest();
    }

    private function applyInterest(): void
    {
        $interest = $this->getBalance() * $this->interestRate;
        $this->account->deposit($interest);
    }
}
