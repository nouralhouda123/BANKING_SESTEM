<?php


namespace App\Strategies;


class InvestmentInterestStrategy implements InterestStrategy
{
    public function calculate(float $balance): float {
        return $balance * 0.10; // فائدة مضافة كمديونية 10%
    }
}
