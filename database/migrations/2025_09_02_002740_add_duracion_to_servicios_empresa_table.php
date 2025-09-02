<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('servicios_empresa', function (Blueprint $table) {
            // Texto libre (ej: "30 minutos", "1 Hora")
            $table->string('duracion', 50)->nullable()->after('precio');
        });
    }

    public function down(): void
    {
        Schema::table('servicios_empresa', function (Blueprint $table) {
            $table->dropColumn('duracion');
        });
    }
};