<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Stock notification requests from customers (notify me when back in stock)
        Schema::create('stock_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('email');
            $table->boolean('notified')->default(false);
            $table->timestamp('notified_at')->nullable();
            $table->timestamps();
            
            $table->unique(['product_id', 'email']);
        });

        // Add low stock threshold to products
        Schema::table('products', function (Blueprint $table) {
            $table->integer('low_stock_threshold')->default(5)->after('stock');
            $table->boolean('hide_when_out_of_stock')->default(true)->after('low_stock_threshold');
        });

        // Inventory alerts log for admin
        Schema::create('inventory_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['low_stock', 'out_of_stock', 'restocked']);
            $table->integer('stock_level');
            $table->boolean('email_sent')->default(false);
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_alerts');
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['low_stock_threshold', 'hide_when_out_of_stock']);
        });
        Schema::dropIfExists('stock_notifications');
    }
};
