<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            $user = DB::table('users')->where('id', 1)->orWhere('email', 'salim.moustanir@gmail.com')->first();
            if (!$user) {
                return;
            }

            DB::table('users')->where('id', $user->id)->update([
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
