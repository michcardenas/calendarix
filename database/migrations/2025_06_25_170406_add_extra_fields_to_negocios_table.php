<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('negocios', function (Blueprint $table) {
            if (!Schema::hasColumn('negocios', 'neg_nombre_comercial')) {
                $table->string('neg_nombre_comercial')->nullable();
            }

            if (!Schema::hasColumn('negocios', 'neg_sitio_web')) {
                $table->string('neg_sitio_web')->nullable();
            }

            if (!Schema::hasColumn('negocios', 'neg_categorias')) {
                $table->json('neg_categorias')->nullable();
            }

            if (!Schema::hasColumn('negocios', 'neg_equipo')) {
                $table->string('neg_equipo')->nullable();
            }

            if (!Schema::hasColumn('negocios', 'neg_direccion')) {
                $table->string('neg_direccion')->nullable();
            }

            if (!Schema::hasColumn('negocios', 'neg_virtual')) {
                $table->boolean('neg_virtual')->default(false);
            }

            if (!Schema::hasColumn('negocios', 'neg_direccion_confirmada')) {
                $table->boolean('neg_direccion_confirmada')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('negocios', function (Blueprint $table) {
            $table->dropColumn([
                'neg_nombre_comercial',
                'neg_sitio_web',
                'neg_categorias',
                'neg_equipo',
                'neg_direccion',
                'neg_virtual',
                'neg_direccion_confirmada',
            ]);
        });
    }
};

