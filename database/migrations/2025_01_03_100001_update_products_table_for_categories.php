<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Add category_id column
            $table->foreignId('category_id')->nullable()->after('id')->constrained()->onDelete('set null');
            
            // Rename old category column to category_old for backup
            $table->renameColumn('category', 'category_old');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->renameColumn('category_old', 'category');
        });
    }
};
