<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Sellers/Vendors table
        Schema::create('sellers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('store_name');
            $table->string('store_slug')->unique();
            $table->text('store_description')->nullable();
            $table->string('store_logo')->nullable();
            $table->string('store_banner')->nullable();
            $table->string('business_name')->nullable();
            $table->string('business_email');
            $table->string('business_phone');
            $table->text('business_address')->nullable();
            $table->string('gst_number')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_ifsc_code')->nullable();
            $table->string('bank_account_holder')->nullable();
            $table->decimal('commission_rate', 5, 2)->default(10.00); // Platform commission %
            $table->decimal('wallet_balance', 12, 2)->default(0);
            $table->decimal('total_earnings', 12, 2)->default(0);
            $table->decimal('total_withdrawn', 12, 2)->default(0);
            $table->enum('status', ['pending', 'approved', 'suspended', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('total_products')->default(0);
            $table->integer('total_orders')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->timestamps();
        });

        // Add seller_id to products table
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('seller_id')->nullable()->after('id')->constrained('sellers')->onDelete('cascade');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('approved')->after('status');
        });

        // Seller earnings/commissions table
        Schema::create('seller_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_item_id')->nullable();
            $table->decimal('order_amount', 12, 2);
            $table->decimal('commission_rate', 5, 2);
            $table->decimal('commission_amount', 12, 2);
            $table->decimal('seller_amount', 12, 2);
            $table->enum('status', ['pending', 'processed', 'paid', 'cancelled'])->default('pending');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        // Seller payouts table
        Schema::create('seller_payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->onDelete('cascade');
            $table->string('payout_id')->unique();
            $table->decimal('amount', 12, 2);
            $table->enum('payment_method', ['bank_transfer', 'upi', 'razorpay'])->default('bank_transfer');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->string('transaction_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        // Seller reviews table
        Schema::create('seller_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned();
            $table->text('review')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });

        // Seller settings table
        Schema::create('seller_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('default_commission_rate', 5, 2)->default(10.00);
            $table->decimal('minimum_payout_amount', 10, 2)->default(500.00);
            $table->integer('payout_frequency_days')->default(7);
            $table->boolean('auto_approve_sellers')->default(false);
            $table->boolean('auto_approve_products')->default(false);
            $table->text('seller_terms')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['seller_id']);
            $table->dropColumn(['seller_id', 'approval_status']);
        });
        
        Schema::dropIfExists('seller_settings');
        Schema::dropIfExists('seller_reviews');
        Schema::dropIfExists('seller_payouts');
        Schema::dropIfExists('seller_earnings');
        Schema::dropIfExists('sellers');
    }
};
