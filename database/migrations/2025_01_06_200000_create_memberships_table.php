<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Membership Plans
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'yearly'])->default('monthly');
            $table->integer('duration_days');
            $table->boolean('free_shipping')->default(false);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->integer('early_access_days')->default(0); // Days before public sale
            $table->boolean('priority_support')->default(false);
            $table->boolean('exclusive_products')->default(false);
            $table->json('features')->nullable(); // Additional features as JSON
            $table->integer('sort_order')->default(0);
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // User Subscriptions
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('membership_plan_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'cancelled', 'expired', 'pending'])->default('pending');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancellation_reason')->nullable();
            $table->boolean('auto_renew')->default(true);
            $table->string('payment_method')->nullable();
            $table->string('razorpay_subscription_id')->nullable();
            $table->decimal('amount_paid', 10, 2);
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('ends_at');
        });

        // Subscription Payments History
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_subscription_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('INR');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->string('razorpay_payment_id')->nullable();
            $table->string('razorpay_order_id')->nullable();
            $table->json('payment_details')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // Early Access Sales
        Schema::create('early_access_sales', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->dateTime('member_access_at'); // When members can access
            $table->dateTime('public_access_at'); // When public can access
            $table->dateTime('ends_at')->nullable();
            $table->decimal('member_discount', 5, 2)->default(0); // Extra discount for members
            $table->json('applicable_categories')->nullable();
            $table->json('applicable_products')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Add membership fields to users table
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_member')->default(false)->after('role');
            $table->timestamp('membership_expires_at')->nullable()->after('is_member');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_member', 'membership_expires_at']);
        });
        
        Schema::dropIfExists('early_access_sales');
        Schema::dropIfExists('subscription_payments');
        Schema::dropIfExists('user_subscriptions');
        Schema::dropIfExists('membership_plans');
    }
};
