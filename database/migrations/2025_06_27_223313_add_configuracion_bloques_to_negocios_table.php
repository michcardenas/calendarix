<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('negocios', function (Blueprint $table) {
            $table->text('configuracion_bloques')->nullable();
        });
    }

    public function down()
    {
        Schema::table('negocios', function (Blueprint $table) {
            $table->dropColumn('configuracion_bloques');
        });
    }
};