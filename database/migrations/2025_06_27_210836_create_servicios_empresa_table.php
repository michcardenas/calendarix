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
        Schema::create('servicios_empresa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('negocio_id'); // Relación con el negocio
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->decimal('precio', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('negocio_id')->references('id')->on('negocios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servicios_empresa');
    }
};
