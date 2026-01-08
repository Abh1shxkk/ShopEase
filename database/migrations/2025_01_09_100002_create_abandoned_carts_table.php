<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('abandoned_carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('cart_total', 10, 2)->default(0);
            $table->integer('items_count')->default(0);
            $table->json('cart_snapshot')->nullable(); // Store cart items at time of abandonment
            $table->enum('status', ['pending', 'reminded', 'recovered', 'expired'])->default('pending');
            $table->integer('reminder_count')->default(0);
            $table->timestamp('last_reminder_at')->nullable();
            $table->timestamp('recovered_at')->nullable();
            $table->foreignId('recovered_order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->string('recovery_token')->unique()->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'status']);
        });

        // Abandoned cart reminder logs
        Schema::create('abandoned_cart_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('abandoned_cart_id')->constrained()->cascadeOnDelete();
            $table->integer('reminder_number'); // 1st, 2nd, 3rd reminder
            $table->string('channel')->default('email'); // email, sms
            $table->enum('status', ['sent', 'opened', 'clicked', 'failed'])->default('sent');
            $table->timestamp('sent_at');
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('clicked_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abandoned_cart_reminders');
        Schema::dropIfExists('abandoned_carts');
    }
};
