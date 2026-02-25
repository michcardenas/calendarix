<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->enum('interval', ['monthly', 'yearly'])->default('monthly');
            $table->integer('max_professionals')->nullable(); // null = ilimitado
            $table->decimal('price_per_additional_professional', 10, 2)->nullable();
            $table->boolean('crm_ia_enabled')->default(true);
            $table->boolean('ecommerce_enabled')->default(true);
            $table->boolean('multi_branch_enabled')->default(false);
            $table->boolean('whatsapp_reminders')->default(true);
            $table->boolean('email_reminders')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};