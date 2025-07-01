<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::table('users', function (Blueprint $table) {
            $table->string('dni')->nullable()->after('email');
            $table->string('celular')->nullable()->after('dni');
            $table->string('pais')->nullable()->after('celular');
            $table->string('ciudad')->nullable()->after('pais');
            $table->string('foto')->nullable()->after('ciudad');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['dni', 'celular', 'pais', 'ciudad', 'foto']);
        });
    }
};
