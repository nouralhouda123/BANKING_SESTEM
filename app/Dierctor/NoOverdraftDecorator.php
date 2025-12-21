<?php


namespace App\Dierctor;


class NoOverdraftDecorator extends AccountDecorator
{
    public function withdraw(float $amount): void
    {
        if ($this->getBalance() < $amount) {
            throw new \Exception(
                "Insufficient funds. Account {$this->getId()} does not allow overdraft"
            );
        }
        parent::withdraw($amount);
    }

    public function deposit(float $amount): void
    {
        parent::deposit($amount);
    }
}
