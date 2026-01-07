<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Product Bundles
        Schema::create('product_bundles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->decimal('original_price', 10, 2)->default(0);
            $table->decimal('bundle_price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Bundle Items (products in a bundle)
        Schema::create('bundle_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained('product_bundles')->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['bundle_id', 'product_id']);
        });

        // Frequently Bought Together (auto-generated based on orders)
        Schema::create('frequently_bought_together', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('related_product_id')->constrained('products')->onDelete('cascade');
            $table->integer('purchase_count')->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(5);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['product_id', 'related_product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frequently_bought_together');
        Schema::dropIfExists('bundle_items');
        Schema::dropIfExists('product_bundles');
    }
};
