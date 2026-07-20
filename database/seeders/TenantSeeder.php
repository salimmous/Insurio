<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AgenceBranch;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        try {
            // Reset cached roles and permissions
            app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

            // 1. Create Roles and Permissions
            $this->call(PermissionSeeder::class);
            $adminRole = Role::where('name', 'agency-admin')->first();
            $commercialRole = Role::where('name', 'agent-commercial')->first();

            // 2. Create a default Branch
            $branch = AgenceBranch::firstOrCreate(
                ['nom' => 'Siège Principal'],
                [
                    'adresse' => 'Casablanca, Maroc',
                    'telephone' => '+212 5 22 00 00 00',
                    'responsable' => tenant('name') ?? 'Directeur',
                    'statut' => 'active',
                ]
            );

            // 3. Create the Agency Admin user from tenant config
            $adminEmail = tenant('admin_email') ?? 'admin@' . tenant('id') . '.com';
            $adminName = tenant('admin_name') ?? 'Admin ' . tenant('name');
            $adminPassword = tenant('admin_password') ?? 'password';

            $user = User::updateOrCreate(
                ['email' => $adminEmail],
                [
                    'name' => $adminName,
                    'password' => Hash::make($adminPassword),
                    'branch_id' => $branch->id,
                ]
            );

            $user->assignRole($adminRole);

            // 4. Create some demo branches & agents for local/testing environment
            if (app()->environment('local', 'testing', 'development')) {
                $branch2 = AgenceBranch::firstOrCreate(
                    ['nom' => 'Succursale Rabat'],
                    [
                        'adresse' => 'Rabat, Maroc',
                        'telephone' => '+212 5 37 00 00 00',
                        'responsable' => 'Karim Alami',
                        'statut' => 'active',
                    ]
                );

                $agentUser = User::updateOrCreate(
                    ['email' => 'agent@' . tenant('id') . '.com'],
                    [
                        'name' => 'Agent Rachid',
                        'password' => Hash::make('password'),
                        'branch_id' => $branch2->id,
                    ]
                );
                $agentUser->assignRole($commercialRole);
            }
        } catch (\Throwable $e) {
            echo "SEEDER EXCEPTION: " . $e->getMessage() . "\n";
            echo $e->getTraceAsString() . "\n";
            throw $e;
        }
    }
}
