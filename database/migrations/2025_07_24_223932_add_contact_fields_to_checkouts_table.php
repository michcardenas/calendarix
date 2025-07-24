<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('checkouts', function (Blueprint $table) {
            $table->string('nombre')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
        });
    }

    public function down()
    {
        Schema::table('checkouts', function (Blueprint $table) {
            $table->dropColumn(['nombre', 'email', 'telefono', 'direccion']);
        });
    }
};
