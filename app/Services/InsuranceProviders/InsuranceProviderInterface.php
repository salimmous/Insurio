<?php

namespace App\Services\InsuranceProviders;

interface InsuranceProviderInterface
{
    /**
     * Get a quote for a client and coverage details.
     */
    public function quote(array $clientData, array $coverageDetails): array;

    /**
     * Create a new policy on the provider's side.
     */
    public function createPolicy(array $quoteData): array;

    /**
     * Renew an existing policy.
     */
    public function renewPolicy(string $policyNumber, array $renewDetails): array;

    /**
     * Query the status of a policy.
     */
    public function getStatus(string $policyNumber): string;
}
