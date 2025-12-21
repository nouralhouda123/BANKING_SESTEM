<?php
namespace App\Composition;

use App\Models\Account;

class LeafAccount implements AccountInterface
{
    protected  $model;

    public function __construct(Account $account)
    {
        $this->model = $account;
    }

    public function withdraw(float $amount): void
    {
        if ($this->model->balance < $amount) {
            throw new \Exception("Insufficient funds");
        }
        $this->model->balance -= $amount;
        $this->model->save();
    }

    public function deposit(float $amount): void
    {
        $this->model->balance += $amount;
        $this->model->save();
    }

    public function getBalance(): float
    {
        return (float)$this->model->balance;
    }

    public function getId(): int
    {
        return $this->model->id;
    }
}
