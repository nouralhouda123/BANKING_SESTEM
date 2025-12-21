<?php
namespace App\Dierctor;

use App\Composition\AccountInterface;
use App\Dierctor\AccountDecorator;
use Illuminate\Support\Facades\Log;

class PremiumServicesDecorator extends AccountDecorator
{
    public function withdraw(float $amount): void
    {
        parent::withdraw($amount);
        $this->logPremiumOperation("withdraw", $amount);
    }

    public function deposit(float $amount): void
    {
        parent::deposit($amount);
        $bonus = $amount * 0.01;
        $this->account->deposit($bonus);
        $this->logPremiumOperation("deposit with bonus", $amount + $bonus);
    }

    private function logPremiumOperation(string $type, float $amount): void
    {
        Log::info("Premium operation: {$type}, Amount: {$amount}");
    }
}
