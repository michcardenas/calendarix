<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('checkouts', function (Blueprint $table) {
            $table->unsignedBigInteger('servicio_id')->nullable()->after('producto_id');

            // Si quieres mantener integridad referencial:
            $table->foreign('servicio_id')->references('id')->on('servicios_empresa')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('checkouts', function (Blueprint $table) {
            $table->dropForeign(['servicio_id']);
            $table->dropColumn('servicio_id');
        });
    }
};
