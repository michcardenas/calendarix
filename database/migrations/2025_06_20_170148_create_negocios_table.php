<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('negocios', function (Blueprint $table) {
            $table->id();
            $table->string('neg_nombre');
            $table->string('neg_apellido');
            $table->string('neg_email')->unique();
            $table->string('neg_telefono');
            $table->string('neg_pais')->nullable();
            $table->boolean('neg_acepto')->default(false);
            $table->timestamps();
            $table->string('neg_imagen')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('negocios');
    }
};
