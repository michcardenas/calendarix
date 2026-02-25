<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('negocios', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('neg_nombre_comercial');
        });

        // Poblar slugs para registros existentes
        $negocios = DB::table('negocios')->whereNull('slug')->get();
        foreach ($negocios as $negocio) {
            $base = Str::slug($negocio->neg_nombre_comercial ?: $negocio->neg_nombre);
            if (empty($base)) {
                $base = 'negocio';
            }
            $slug = $base;
            $counter = 1;
            while (DB::table('negocios')->where('slug', $slug)->where('id', '!=', $negocio->id)->exists()) {
                $slug = "{$base}-{$counter}";
                $counter++;
            }
            DB::table('negocios')->where('id', $negocio->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('negocios', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
