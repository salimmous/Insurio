<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            DB::table('users')->where('id', 1)->update([
                'first_login' => false,
                'activated_at' => now(),
                'status' => 'active',
            ]);

            DB::table('users')->where('email', 'salim.moustanir@gmail.com')->update([
                'first_login' => false,
                'activated_at' => now(),
                'status' => 'active',
            ]);
        }
    }

    public function down(): void
    {
    }
};
