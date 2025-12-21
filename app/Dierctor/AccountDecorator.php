<?php
namespace App\Dierctor;
use App\Composition\AccountInterface;
abstract class AccountDecorator implements AccountInterface
{
    protected AccountInterface $account;
    public function __construct(AccountInterface $account)
    {
        $this->account = $account;
    }
    public function withdraw(float $amount): void
    {
        $this->account->withdraw($amount);
    }
    public function deposit(float $amount): void
    {
        $this->account->deposit($amount);
    }
    public function transfer(AccountInterface $target, float $amount): void
    {
        $this->account->transfer($target, $amount);
    }
    public function getBalance(): float
    {
        return $this->account->getBalance();
    }

    public function getId(): int
    {
        return $this->account->getId();
    }

    public function addChild(AccountInterface $child): void
    {
        $this->account->addChild($child);
    }

    public function removeChild(int $childId): void
    {
        $this->account->removeChild($childId);
    }

    public function getChild(int $childId): ?AccountInterface
    {
        return $this->account->getChild($childId);
    }

    public function getModel()
    {
        return $this->account->getModel();
    }
}

