<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique();
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->string('color_code')->nullable(); // Hex color for display
            $table->string('material')->nullable();
            $table->decimal('price', 10, 2)->nullable(); // Override product price
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->string('image')->nullable(); // Variant-specific image
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Index for faster lookups
            $table->index(['product_id', 'is_active']);
            $table->index(['size', 'color', 'material']);
        });

        // Add has_variants flag to products table
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('has_variants')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('has_variants');
        });
        
        Schema::dropIfExists('product_variants');
    }
};
