<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define Permissions
        $permissions = [
            // Clients
            'clients.view',
            'clients.create',
            'clients.edit',
            'clients.delete',

            // Contracts
            'contracts.view',
            'contracts.create',
            'contracts.edit',
            'contracts.approve',
            'contracts.cancel',

            // Finance
            'expenses.view',
            'expenses.create',
            'commissions.view',
            'payments.manage',

            // Documents
            'documents.upload',
            'documents.delete',
        ];

        // Create Permissions
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // Define Roles & Permissions mapping
        $rolePermissions = [
            // New Roles
            'Super Admin' => $permissions,
            'Agency Owner' => $permissions,
            'Agency Manager' => [
                'clients.view', 'clients.create', 'clients.edit',
                'contracts.view', 'contracts.create', 'contracts.edit', 'contracts.approve',
                'expenses.view', 'commissions.view', 'payments.manage',
                'documents.upload'
            ],
            'Agent' => [
                'clients.view', 'clients.create', 'clients.edit',
                'contracts.view', 'contracts.create',
                'documents.upload'
            ],
            'Accountant' => [
                'clients.view', 'contracts.view',
                'expenses.view', 'expenses.create', 'commissions.view', 'payments.manage'
            ],
            'Employee' => [
                'clients.view', 'contracts.view', 'documents.upload'
            ],

            // Legacy Roles (keep for backward compatibility with existing tests)
            'agency-admin' => $permissions,
            'responsable-succursale' => [
                'clients.view', 'clients.create', 'clients.edit',
                'contracts.view', 'contracts.create', 'contracts.edit', 'contracts.approve',
                'expenses.view', 'commissions.view', 'payments.manage',
                'documents.upload'
            ],
            'agent-commercial' => [
                'clients.view', 'clients.create',
                'contracts.view', 'contracts.create',
                'documents.upload'
            ],
            'comptable' => [
                'clients.view', 'contracts.view',
                'expenses.view', 'expenses.create', 'commissions.view', 'payments.manage'
            ],
            'consultation' => [
                'clients.view', 'contracts.view'
            ],
        ];

        // Create Roles & Assign Permissions
        foreach ($rolePermissions as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($perms);
        }
    }
}
