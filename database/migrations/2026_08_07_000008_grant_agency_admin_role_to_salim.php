<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('roles') && Schema::hasTable('model_has_roles')) {
            // Ensure agency-admin role exists
            $role = DB::table('roles')->where('name', 'agency-admin')->where('guard_name', 'web')->first();
            if (!$role) {
                $roleId = DB::table('roles')->insertGetId([
                    'name' => 'agency-admin',
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $roleId = $role->id;
            }

            // Find user 1 or salim
            $user = DB::table('users')->where('id', 1)->orWhere('email', 'salim.moustanir@gmail.com')->first();
            if ($user) {
                DB::table('model_has_roles')->where('model_id', $user->id)->where('model_type', 'App\\Models\\User')->delete();
                DB::table('model_has_roles')->insert([
                    'role_id' => $roleId,
                    'model_type' => 'App\\Models\\User',
                    'model_id' => $user->id,
                ]);
            }
        }
    }

    public function down(): void
    {
    }
};
