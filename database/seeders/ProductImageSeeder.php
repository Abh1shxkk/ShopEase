<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        // Product images from Unsplash (free to use)
        $productImages = [
            // Electronics
            ['name' => 'Wireless Headphones', 'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=800', 'gender' => 'unisex'],
            ['name' => 'Smart Watch Pro', 'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=800', 'gender' => 'unisex'],
            ['name' => 'Bluetooth Speaker', 'image' => 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=800', 'gender' => 'unisex'],
            
            // Fashion
            ['name' => 'Premium Backpack', 'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800', 'gender' => 'unisex'],
            ['name' => 'Minimalist Wallet', 'image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=800', 'gender' => 'men'],
            ['name' => 'Sunglasses Classic', 'image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=800', 'gender' => 'unisex'],
            
            // Sports
            ['name' => 'Running Shoes', 'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800', 'gender' => 'men'],
            ['name' => 'Yoga Mat Premium', 'image' => 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=800', 'gender' => 'women'],
            
            // Home
            ['name' => 'Coffee Maker Deluxe', 'image' => 'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?w=800', 'gender' => 'unisex'],
            
            // Books
            ['name' => 'Programming Book', 'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=800', 'gender' => 'unisex'],
        ];

        foreach ($productImages as $data) {
            Product::where('name', $data['name'])->update([
                'image' => $data['image'],
                'gender' => $data['gender'],
            ]);
        }

        // Add more products with images
        $newProducts = [
            // Women's Products
            ['name' => 'Elegant Handbag', 'description' => 'Luxurious leather handbag with gold-tone hardware and spacious interior.', 'price' => 245.00, 'category_old' => 'Fashion', 'stock' => 30, 'status' => 'active', 'gender' => 'women', 'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=800'],
            ['name' => 'Floral Summer Dress', 'description' => 'Light and breezy floral print dress perfect for summer occasions.', 'price' => 89.99, 'discount_price' => 69.99, 'category_old' => 'Fashion', 'stock' => 45, 'status' => 'active', 'gender' => 'women', 'image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=800'],
            ['name' => 'Women\'s Sneakers', 'description' => 'Comfortable and stylish sneakers for everyday wear.', 'price' => 119.99, 'category_old' => 'Fashion', 'stock' => 60, 'status' => 'active', 'gender' => 'women', 'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=800'],
            ['name' => 'Pearl Necklace', 'description' => 'Classic freshwater pearl necklace with sterling silver clasp.', 'price' => 175.00, 'category_old' => 'Accessories', 'stock' => 25, 'status' => 'active', 'gender' => 'women', 'image' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=800'],
            ['name' => 'Silk Scarf', 'description' => 'Hand-printed silk scarf with elegant patterns.', 'price' => 85.00, 'category_old' => 'Accessories', 'stock' => 40, 'status' => 'active', 'gender' => 'women', 'image' => 'https://images.unsplash.com/photo-1520903920243-00d872a2d1c9?w=800'],
            
            // Men's Products
            ['name' => 'Classic Leather Watch', 'description' => 'Timeless leather strap watch with Swiss movement.', 'price' => 299.00, 'discount_price' => 249.00, 'category_old' => 'Accessories', 'stock' => 35, 'status' => 'active', 'gender' => 'men', 'image' => 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=800'],
            ['name' => 'Men\'s Leather Jacket', 'description' => 'Premium genuine leather jacket with quilted lining.', 'price' => 395.00, 'category_old' => 'Fashion', 'stock' => 20, 'status' => 'active', 'gender' => 'men', 'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800'],
            ['name' => 'Oxford Dress Shoes', 'description' => 'Handcrafted leather oxford shoes for formal occasions.', 'price' => 225.00, 'category_old' => 'Fashion', 'stock' => 40, 'status' => 'active', 'gender' => 'men', 'image' => 'https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?w=800'],
            ['name' => 'Casual Polo Shirt', 'description' => 'Premium cotton polo shirt with classic fit.', 'price' => 65.00, 'category_old' => 'Fashion', 'stock' => 80, 'status' => 'active', 'gender' => 'men', 'image' => 'https://images.unsplash.com/photo-1625910513413-5fc45e80b5b5?w=800'],
            ['name' => 'Leather Belt', 'description' => 'Full grain leather belt with brushed metal buckle.', 'price' => 55.00, 'category_old' => 'Accessories', 'stock' => 100, 'status' => 'active', 'gender' => 'men', 'image' => 'https://images.unsplash.com/photo-1624222247344-550fb60583dc?w=800'],
            
            // Unisex Products
            ['name' => 'Travel Duffel Bag', 'description' => 'Spacious canvas duffel bag perfect for weekend getaways.', 'price' => 145.00, 'category_old' => 'Accessories', 'stock' => 50, 'status' => 'active', 'gender' => 'unisex', 'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800'],
            ['name' => 'Wireless Earbuds', 'description' => 'True wireless earbuds with active noise cancellation.', 'price' => 179.00, 'discount_price' => 149.00, 'category_old' => 'Electronics', 'stock' => 70, 'status' => 'active', 'gender' => 'unisex', 'image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=800'],
            ['name' => 'Minimalist Desk Lamp', 'description' => 'Modern LED desk lamp with adjustable brightness.', 'price' => 79.00, 'category_old' => 'Home', 'stock' => 45, 'status' => 'active', 'gender' => 'unisex', 'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800'],
            ['name' => 'Ceramic Plant Pot', 'description' => 'Handmade ceramic pot perfect for indoor plants.', 'price' => 35.00, 'category_old' => 'Home', 'stock' => 90, 'status' => 'active', 'gender' => 'unisex', 'image' => 'https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=800'],
            ['name' => 'Aromatherapy Candle Set', 'description' => 'Set of 3 soy wax candles with essential oils.', 'price' => 45.00, 'category_old' => 'Home', 'stock' => 60, 'status' => 'active', 'gender' => 'unisex', 'image' => 'https://images.unsplash.com/photo-1602607753754-e8e0e5e5e5e5?w=800'],
        ];

        foreach ($newProducts as $product) {
            Product::create($product);
        }

        $this->command->info('Product images seeded successfully!');
    }
}
