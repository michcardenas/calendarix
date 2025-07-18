<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNegPortadaToNegociosTable extends Migration
{
    public function up()
    {
        Schema::table('negocios', function (Blueprint $table) {
            $table->string('neg_portada')->nullable()->after('neg_imagen');
        });
    }

    public function down()
    {
        Schema::table('negocios', function (Blueprint $table) {
            $table->dropColumn('neg_portada');
        });
    }
}
