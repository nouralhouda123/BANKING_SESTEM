<?php
namespace App\Policies;

class AccountFeaturePolicy
{
    public static function featuresFor(string $type): array
    {
        return match ($type) {
            'checking' => [
                'overdraft' => 500,
                'premium' => true,       // إضافة Premium Services
            ],

            'savings' => [
                'no_overdraft' => true,
                'insurance' => true,     // إضافة Insurance
                'premium' => true,       // Premium Services
            ],

            'investment' => [
                'no_overdraft' => true,
                'premium' => true,
            ],

            'loan' => [
                'no_overdraft' => true,
                'insurance' => true,
            ],

            default => [],
        };
    }
}
