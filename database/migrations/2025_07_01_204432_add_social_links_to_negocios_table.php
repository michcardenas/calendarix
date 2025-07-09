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
        Schema::table('negocios', function (Blueprint $table) {
            $table->string('neg_facebook')->nullable()->after('neg_pais');
            $table->string('neg_instagram')->nullable()->after('neg_facebook');
        });
    }

    public function down()
    {
        Schema::table('negocios', function (Blueprint $table) {
            $table->dropColumn(['neg_facebook', 'neg_instagram']);
        });
    }
};
