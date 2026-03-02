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
        Schema::table('negocios', function (Blueprint $table) {
            $table->decimal('neg_latitud', 10, 7)->nullable()->after('neg_direccion');
            $table->decimal('neg_longitud', 10, 7)->nullable()->after('neg_latitud');
        });
    }

    public function down(): void
    {
        Schema::table('negocios', function (Blueprint $table) {
            $table->dropColumn(['neg_latitud', 'neg_longitud']);
        });
    }
};
