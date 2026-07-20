<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TenantOnboardingService;

class CreateTestTenant extends Command
{
    protected $signature = 'tenant:test-create {subdomain} {company_name} {email}';
    protected $description = 'Create a test tenant to verify the onboarding flow';

    public function handle(TenantOnboardingService $onboardingService)
    {
        $subdomain = $this->argument('subdomain');
        $companyName = $this->argument('company_name');
        $email = $this->argument('email');

        $this->info("Creating tenant for {$companyName} (subdomain: {$subdomain})...");

        $tenant = $onboardingService->createTenant([
            'company_name' => $companyName,
            'subdomain' => $subdomain,
            'admin_email' => $email,
            'admin_name' => 'Admin ' . $companyName,
            'admin_password' => 'password',
            'plan' => 'premium',
        ]);

        $this->info("Tenant created successfully with ID: {$tenant->id}");
    }
}
