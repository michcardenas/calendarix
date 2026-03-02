<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('bamboo_customer_id')->nullable()->after('foto');
            $table->string('bamboo_unique_id')->nullable()->after('bamboo_customer_id');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('bamboo_token')->nullable()->after('is_trial');
            $table->timestamp('cancelled_at')->nullable()->after('bamboo_token');
        });

        // Cambiar status de enum a string para soportar más estados
        // SQLite no soporta ALTER COLUMN, así que usamos el approach de Laravel
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('status', 20)->default('active')->change();
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['bamboo_token', 'cancelled_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['bamboo_customer_id', 'bamboo_unique_id']);
        });
    }
};
