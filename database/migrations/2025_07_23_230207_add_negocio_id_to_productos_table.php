<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->foreignId('negocio_id')
                  ->nullable()
                  ->after('user_id') // opcional: coloca el campo después de user_id
                  ->constrained('negocios')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['negocio_id']);
            $table->dropColumn('negocio_id');
        });
    }
};
