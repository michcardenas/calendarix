<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorariosLaboralesTable extends Migration
{
    public function up()
    {
        Schema::create('horarios_laborales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('negocio_id');
            $table->tinyInteger('dia_semana'); // 0 = Lunes, 6 = Domingo
            $table->time('hora_inicio')->nullable();
            $table->time('hora_fin')->nullable();
            $table->boolean('activo')->default(false);
            $table->timestamps();

            $table->foreign('negocio_id')->references('id')->on('negocios')->onDelete('cascade');
            $table->unique(['negocio_id', 'dia_semana']); // Para evitar duplicados
        });
    }

    public function down()
    {
        Schema::dropIfExists('horarios_laborales');
    }
}

