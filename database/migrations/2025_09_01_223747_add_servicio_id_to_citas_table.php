<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            // Si tu tabla se llama distinto, ajusta el nombre
            $table->foreignId('servicio_id')
                ->nullable() // ponlo nullable si ya tienes citas históricas sin servicio
                ->constrained('servicios_empresa')
                ->nullOnDelete()   // si eliminan el servicio, la cita queda con servicio_id = null
                ->after('negocio_id');

            // OPCIONAL: guarda el precio "congelado" al momento de la reserva
            // Recomendación: guardar en centavos (integer) para evitar floats
            $table->integer('precio_cerrado')->nullable()->after('servicio_id');
        });
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            if (Schema::hasColumn('citas', 'precio_cerrado')) {
                $table->dropColumn('precio_cerrado');
            }
            $table->dropConstrainedForeignId('servicio_id'); // elimina FK + columna
        });
    }
};