<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trabajadores', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->unique(['negocio_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::table('trabajadores', function (Blueprint $table) {
            $table->dropUnique(['negocio_id', 'email']);
            $table->unique(['email']);
        });
    }
};
