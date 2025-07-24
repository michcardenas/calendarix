<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('checkouts', function (Blueprint $table) {
            // Eliminar claves foráneas primero (asegúrate que estos nombres coincidan con los generados por Laravel)
            $table->dropForeign(['producto_id']);
            $table->dropForeign(['servicio_id']);

            // Ahora sí puedes eliminar las columnas
            $table->dropColumn(['producto_id', 'servicio_id', 'cantidad', 'precio_unitario', 'precio_total']);
        });
    }


    public function down()
    {
        Schema::table('checkouts', function (Blueprint $table) {
            $table->unsignedBigInteger('producto_id')->nullable();
            $table->unsignedBigInteger('servicio_id')->nullable();
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 10, 2)->default(0);
            $table->decimal('precio_total', 10, 2)->default(0);
        });
    }
};
