<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Get all categories
        $categories = Category::pluck('id', 'name')->toArray();
        
        // Update products with category_id based on category_old
        foreach ($categories as $name => $id) {
            DB::table('products')
                ->where('category_old', $name)
                ->update(['category_id' => $id]);
        }
    }

    public function down(): void
    {
        // Reset category_id to null
        DB::table('products')->update(['category_id' => null]);
    }
};
