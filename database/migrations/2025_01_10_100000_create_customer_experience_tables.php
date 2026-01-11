<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Review Photos table
        Schema::create('review_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained()->onDelete('cascade');
            $table->string('image');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Review Helpful votes
        Schema::create('review_votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('review_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_helpful')->default(true);
            $table->timestamps();
            $table->unique(['review_id', 'user_id']);
        });

        // Product Q&A
        Schema::create('product_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });

        Schema::create('product_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('product_questions')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('answer');
            $table->boolean('is_seller_answer')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->integer('helpful_count')->default(0);
            $table->timestamps();
        });

        // Product Comparison
        Schema::create('product_comparisons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->json('product_ids');
            $table->timestamps();
            $table->index(['user_id', 'session_id']);
        });

        // Size Guide
        Schema::create('size_guides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('type')->default('clothing'); // clothing, shoes, accessories
            $table->json('measurements'); // size chart data
            $table->text('fit_tips')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Wishlist Shares
        Schema::create('wishlist_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('share_token', 64)->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(true);
            $table->integer('view_count')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // Order Tracking Events
        Schema::create('order_tracking_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->string('tracking_number')->nullable();
            $table->string('carrier')->nullable();
            $table->timestamp('event_time');
            $table->timestamps();
            $table->index(['order_id', 'event_time']);
        });

        // Add helpful counts to reviews
        Schema::table('reviews', function (Blueprint $table) {
            $table->integer('helpful_count')->default(0)->after('is_approved');
            $table->integer('not_helpful_count')->default(0)->after('helpful_count');
        });

        // Add tracking fields to orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tracking_number')->nullable()->after('notes');
            $table->string('carrier')->nullable()->after('tracking_number');
            $table->timestamp('shipped_at')->nullable()->after('carrier');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            $table->timestamp('estimated_delivery')->nullable()->after('delivered_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['tracking_number', 'carrier', 'shipped_at', 'delivered_at', 'estimated_delivery']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['helpful_count', 'not_helpful_count']);
        });

        Schema::dropIfExists('order_tracking_events');
        Schema::dropIfExists('wishlist_shares');
        Schema::dropIfExists('size_guides');
        Schema::dropIfExists('product_comparisons');
        Schema::dropIfExists('product_answers');
        Schema::dropIfExists('product_questions');
        Schema::dropIfExists('review_votes');
        Schema::dropIfExists('review_photos');
    }
};
