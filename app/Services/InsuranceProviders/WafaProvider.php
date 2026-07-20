<?php

namespace App\Services\InsuranceProviders;

class WafaProvider implements InsuranceProviderInterface
{
    public function quote(array $clientData, array $coverageDetails): array
    {
        return [
            'success' => true,
            'provider' => 'Wafa Assurance',
            'quote_id' => 'WAFA-Q-' . uniqid(),
            'premium' => 2650.00,
        ];
    }

    public function createPolicy(array $quoteData): array
    {
        return [
            'success' => true,
            'policy_number' => 'WAFA-POL-' . rand(100000, 999999),
            'status' => 'active',
        ];
    }

    public function renewPolicy(string $policyNumber, array $renewDetails): array
    {
        return [
            'success' => true,
            'new_expiry' => now()->addYear()->toDateString(),
            'status' => 'renewed',
        ];
    }

    public function getStatus(string $policyNumber): string
    {
        return 'active';
    }
}
