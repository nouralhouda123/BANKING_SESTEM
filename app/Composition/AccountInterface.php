<?php
namespace App\Composition;

interface AccountInterface
{
    public function withdraw(float $amount): void;
    public function deposit(float $amount): void;
    public function getBalance(): float;
    public function getId(): int;
}
