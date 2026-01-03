<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['card', 'upi', 'netbanking', 'wallet']);
            $table->string('label')->nullable(); // "Personal Card", "Office UPI"
            $table->string('last_four')->nullable(); // Last 4 digits of card
            $table->string('card_brand')->nullable(); // Visa, Mastercard, etc.
            $table->string('upi_id')->nullable();
            $table->string('bank_name')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
