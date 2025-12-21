<?php


namespace App\Strategies;


class SavingsInterestStrategy implements InterestStrategy
{
    public function calculate(float $balance): float {
        return $balance * 0.05; // فائدة 5%
    }
}
