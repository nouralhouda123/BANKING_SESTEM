<?php


namespace App\Strategies;


class CheckingInterestStrategy implements InterestStrategy
{
    public function calculate(float $balance): float {
        return 0.0;
    }
}
