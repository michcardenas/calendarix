<?php

// database/migrations/2025_09_03_000001_add_trabajador_id_to_citas_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
public function up(): void
{
    Schema::table('citas', function (Blueprint $table) {
        if (!Schema::hasColumn('citas', 'trabajador_id')) {
            $table->unsignedBigInteger('trabajador_id')->nullable()->after('servicio_id');
        } else {
            // por si ya existe, nos aseguramos que sea nullable
            $table->unsignedBigInteger('trabajador_id')->nullable()->change();
        }

        $table->index(['negocio_id', 'trabajador_id', 'fecha'], 'citas_neg_trab_fecha_idx');
    });

    // Limpia valores inválidos ANTES de crear la FK
    DB::statement("
        UPDATE citas c
        LEFT JOIN trabajadores t ON t.id = c.trabajador_id
        SET c.trabajador_id = NULL
        WHERE c.trabajador_id IS NOT NULL AND t.id IS NULL
    ");

    Schema::table('citas', function (Blueprint $table) {
        // Crea la FK permitiendo NULL y dejando SET NULL al eliminar trabajador
        $table->foreign('trabajador_id', 'citas_trabajador_id_foreign')
            ->references('id')->on('trabajadores')
            ->cascadeOnUpdate()
            ->nullOnDelete();
    });
}

public function down(): void
{
    Schema::table('citas', function (Blueprint $table) {
        $table->dropForeign('citas_trabajador_id_foreign');
        $table->dropIndex('citas_neg_trab_fecha_idx');
        $table->dropColumn('trabajador_id');
    });
}
};