<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::firstOrCreate(
            ['email' => 'admin@shopease.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Normal User
        User::firstOrCreate(
            ['email' => 'user@shopease.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('user123'),
                'role' => 'user',
            ]
        );

        // Sample Products
        $products = [
            ['name' => 'Wireless Headphones', 'description' => 'Premium wireless headphones with noise cancellation and 30-hour battery life.', 'price' => 129.99, 'discount_price' => 99.99, 'category' => 'Electronics', 'stock' => 50, 'status' => 'active'],
            ['name' => 'Smart Watch Pro', 'description' => 'Advanced smartwatch with health monitoring, GPS, and water resistance.', 'price' => 299.99, 'category' => 'Electronics', 'stock' => 35, 'status' => 'active'],
            ['name' => 'Premium Backpack', 'description' => 'Durable laptop backpack with USB charging port and anti-theft design.', 'price' => 89.99, 'category' => 'Fashion', 'stock' => 100, 'status' => 'active'],
            ['name' => 'Running Shoes', 'description' => 'Lightweight running shoes with responsive cushioning for maximum comfort.', 'price' => 159.99, 'discount_price' => 129.99, 'category' => 'Sports', 'stock' => 75, 'status' => 'active'],
            ['name' => 'Minimalist Wallet', 'description' => 'Slim leather wallet with RFID blocking technology.', 'price' => 49.99, 'category' => 'Fashion', 'stock' => 200, 'status' => 'active'],
            ['name' => 'Sunglasses Classic', 'description' => 'Polarized sunglasses with UV400 protection and titanium frame.', 'price' => 79.99, 'category' => 'Fashion', 'stock' => 60, 'status' => 'active'],
            ['name' => 'Bluetooth Speaker', 'description' => 'Portable waterproof speaker with 360-degree sound.', 'price' => 69.99, 'category' => 'Electronics', 'stock' => 45, 'status' => 'active'],
            ['name' => 'Yoga Mat Premium', 'description' => 'Extra thick eco-friendly yoga mat with carrying strap.', 'price' => 39.99, 'category' => 'Sports', 'stock' => 80, 'status' => 'active'],
            ['name' => 'Coffee Maker Deluxe', 'description' => 'Programmable coffee maker with built-in grinder and thermal carafe.', 'price' => 199.99, 'discount_price' => 149.99, 'category' => 'Home', 'stock' => 25, 'status' => 'active'],
            ['name' => 'Programming Book', 'description' => 'Complete guide to modern web development with practical examples.', 'price' => 44.99, 'category' => 'Books', 'stock' => 150, 'status' => 'inactive'],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}
