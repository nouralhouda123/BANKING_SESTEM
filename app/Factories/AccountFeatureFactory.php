<?php
namespace App\Factories;

use App\Composition\AccountInterface;
use App\Dierctor\InsuranceDecorator;
use App\Dierctor\NoOverdraftDecorator;
use App\Dierctor\OverdraftProtectionDecorator;
use App\Dierctor\PremiumServicesDecorator;
use App\Policies\AccountFeaturePolicy;

class AccountFeatureFactory
{
    public static function apply(AccountInterface $account, string $type): AccountInterface
    {
        $features = AccountFeaturePolicy::featuresFor($type);

        foreach ($features as $feature => $value) {
            match ($feature) {
                'overdraft' => $account = new OverdraftProtectionDecorator($account, $value),
                'no_overdraft' => $account = new NoOverdraftDecorator($account),
                'insurance' => $account = new InsuranceDecorator($account),
                'premium' => $account = new PremiumServicesDecorator($account),
                default => null,
            };
        }

        return $account;
    }
}
