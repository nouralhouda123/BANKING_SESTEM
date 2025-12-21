<?php
namespace App\Dierctor;

use App\Dierctor\AccountDecorator;
use Illuminate\Support\Facades\Log;

class InsuranceDecorator extends AccountDecorator
{
    public function withdraw(float $amount): void
    {
        parent::withdraw($amount);
        $this->logInsuranceOperation("withdraw", $amount);
    }

    public function deposit(float $amount): void
    {
        parent::deposit($amount);
        $this->logInsuranceOperation("deposit", $amount);
    }

    private function logInsuranceOperation(string $type, float $amount): void
    {
        Log::info("Insurance: {$type} of {$amount} on account {$this->account->getId()}");
    }
}
