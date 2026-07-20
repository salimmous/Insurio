<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Landlord\PlatformAdmin;
use Illuminate\Support\Facades\Hash;

class LandlordSeeder extends Seeder
{
    public function run(): void
    {
        PlatformAdmin::updateOrCreate(
            ['email' => 'superadmin@insurio.com'],
            [
                'name' => 'Insurio Super Admin',
                'password' => Hash::make('password'),
            ]
        );
    }
}
