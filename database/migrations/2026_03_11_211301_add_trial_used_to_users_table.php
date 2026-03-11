<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('trial_used')->default(false)->after('bamboo_unique_id');
        });

        // Backfill: marcar trial_used para usuarios que ya tuvieron trial
        DB::table('users')
            ->whereIn('id', function ($q) {
                $q->select('user_id')->from('subscriptions')->where('is_trial', true);
            })
            ->update(['trial_used' => true]);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('trial_used');
        });
    }
};
