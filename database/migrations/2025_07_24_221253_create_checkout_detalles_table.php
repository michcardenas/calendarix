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
        Schema::create('checkout_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checkout_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->unsignedBigInteger('servicio_id')->nullable();
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('precio_total', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_detalles');
    }
};
