<?php

namespace App\Services\InsuranceProviders;

class SanlamProvider implements InsuranceProviderInterface
{
    public function quote(array $clientData, array $coverageDetails): array
    {
        return [
            'success' => true,
            'provider' => 'Sanlam Maroc',
            'quote_id' => 'SANLAM-Q-' . uniqid(),
            'premium' => 2500.00,
        ];
    }

    public function createPolicy(array $quoteData): array
    {
        return [
            'success' => true,
            'policy_number' => 'SANLAM-POL-' . rand(100000, 999999),
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
