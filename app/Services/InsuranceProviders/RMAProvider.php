<?php

namespace App\Services\InsuranceProviders;

class RMAProvider implements InsuranceProviderInterface
{
    public function quote(array $clientData, array $coverageDetails): array
    {
        return [
            'success' => true,
            'provider' => 'RMA',
            'quote_id' => 'RMA-Q-' . uniqid(),
            'premium' => 2400.00,
        ];
    }

    public function createPolicy(array $quoteData): array
    {
        return [
            'success' => true,
            'policy_number' => 'RMA-POL-' . rand(100000, 999999),
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
