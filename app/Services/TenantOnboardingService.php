<?php

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Str;

class TenantOnboardingService
{
    /**
     * Create and provision a new Tenant.
     *
     * @param array $data
     * @return Tenant
     */
    public function createTenant(array $data): Tenant
    {
        $id = $data['subdomain'] ?? Str::slug($data['company_name']);
        
        // Define database name
        $dbName = 'tenant_' . str_replace('-', '_', $id);

        // Create the tenant record in Landlord database
        $tenant = Tenant::create([
            'id' => $id,
            'name' => $data['company_name'],
            'db_name' => $dbName,
            'status' => $data['status'] ?? 'active',
            'plan' => $data['plan'] ?? 'basic',
            'created_by' => auth('platform')->id() ?? null,
            // Stored in the data JSON field for TenantSeeder to use
            'admin_email' => $data['admin_email'],
            'admin_name' => $data['admin_name'],
            'admin_password' => $data['admin_password'],
            'subscription_start_date' => $data['subscription_start_date'] ?? null,
            'subscription_end_date' => $data['subscription_end_date'] ?? null,
            'rent_amount' => isset($data['rent_amount']) ? (float)$data['rent_amount'] : null,
        ]);

        // Get the central domain (default to sc7mosa1422.universe.wf)
        $centralDomain = config('tenancy.central_domains.2') ?? 'sc7mosa1422.universe.wf';
        $domainName = $data['subdomain'] . '.' . $centralDomain;

        // Associate domain (triggers domain events)
        $tenant->domains()->create([
            'domain' => $domainName,
        ]);

        // Automatically replace empty cPanel directory with a symlink to public_html
        $tenantDir = "/home/sc7mosa1422/" . $domainName;
        if (file_exists($tenantDir)) {
            if (is_dir($tenantDir) && !is_link($tenantDir)) {
                $this->deleteDir($tenantDir);
            }
        }
        if (!file_exists($tenantDir)) {
            @symlink("/home/sc7mosa1422/public_html", $tenantDir);
        }

        // Associate custom domain if provided
        if (!empty($data['custom_domain'])) {
            $customDomain = strtolower(trim($data['custom_domain']));
            $tenant->domains()->create([
                'domain' => $customDomain,
            ]);

            $customTenantDir = "/home/sc7mosa1422/" . $customDomain;
            if (file_exists($customTenantDir)) {
                if (is_dir($customTenantDir) && !is_link($customTenantDir)) {
                    $this->deleteDir($customTenantDir);
                }
            }
            if (!file_exists($customTenantDir)) {
                @symlink("/home/sc7mosa1422/public_html", $customTenantDir);
            }
        }

        return $tenant;
    }

    /**
     * Delete a directory recursively.
     *
     * @param string $dirPath
     * @return void
     */
    private function deleteDir(string $dirPath): void
    {
        if (!is_dir($dirPath)) {
            return;
        }
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDir($file);
            } else {
                @unlink($file);
            }
        }
        @rmdir($dirPath);
    }
}
