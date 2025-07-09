<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // o negocio_id según tu modelo
            $table->string('nombre');
            $table->string('codigo_barras')->nullable();
            $table->string('marca')->nullable();
            $table->string('unidad_medida')->default('unidad');
            $table->decimal('cantidad', 10, 2)->nullable();
            $table->string('descripcion_breve', 255)->nullable();
            $table->text('descripcion_larga')->nullable();
            $table->string('categoria')->nullable();

            $table->decimal('precio_compra', 10, 2)->nullable();
            $table->decimal('precio_venta', 10, 2)->nullable();
            $table->decimal('precio_promocional', 10, 2)->nullable();
            $table->boolean('activar_oferta')->default(false);

            $table->boolean('controla_inventario')->default(false);
            $table->integer('stock')->nullable();
            $table->integer('stock_minimo')->nullable();

            $table->boolean('estado_publicado')->default(true);
            $table->boolean('mostrar_en_catalogo')->default(true);

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
