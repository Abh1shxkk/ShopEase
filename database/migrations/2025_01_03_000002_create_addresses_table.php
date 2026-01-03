<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('label')->default('Home'); // Home, Office, etc.
            $table->string('name'); // Recipient name
            $table->string('phone');
            $table->text('address_line_1');
            $table->text('address_line_2')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('pincode');
            $table->string('country')->default('India');
            $table->string('landmark')->nullable();
            $table->boolean('is_default')->default(false);
            $table->enum('type', ['shipping', 'billing', 'both'])->default('both');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
