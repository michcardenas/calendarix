<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bamboo_payment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action'); // create_customer, tokenize, purchase, purchase_retry
            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();
            $table->integer('http_status')->nullable();
            $table->boolean('success')->default(false);
            $table->text('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bamboo_payment_logs');
    }
};
