<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resenas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cita_id')->constrained('citas')->onDelete('cascade');
            $table->foreignId('negocio_id')->constrained('negocios')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('rating');
            $table->text('comentario');
            $table->text('respuesta_negocio')->nullable();
            $table->datetime('respuesta_fecha')->nullable();
            $table->timestamps();
            $table->unique('cita_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resenas');
    }
};
