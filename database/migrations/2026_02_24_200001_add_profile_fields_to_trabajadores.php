<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trabajadores', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('telefono');
            $table->text('bio')->nullable()->after('foto');
            $table->string('especialidades')->nullable()->after('bio');
        });
    }

    public function down(): void
    {
        Schema::table('trabajadores', function (Blueprint $table) {
            $table->dropColumn(['foto', 'bio', 'especialidades']);
        });
    }
};
