<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('resenas', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
            $table->string('nombre_cliente')->nullable()->after('user_id');
            $table->string('email_cliente')->nullable()->after('nombre_cliente');
        });
    }

    public function down(): void
    {
        Schema::table('resenas', function (Blueprint $table) {
            $table->dropColumn(['nombre_cliente', 'email_cliente']);
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
    }
};
