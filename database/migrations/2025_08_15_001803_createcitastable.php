<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->id(); // BIGINT UNSIGNED por defecto en Laravel 8+

            // 🔗 FK hacia la tabla 'negocios' (modelo Empresa)
            $table->foreignId('negocio_id')
                  ->constrained('negocios') // referencia negocios.id
                  ->cascadeOnDelete();

            // (opcional) relación con users si la usas
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Nombre libre si no hay user_id
            $table->string('nombre_cliente')->nullable();

            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->text('notas')->nullable();
            $table->string('estado')->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
