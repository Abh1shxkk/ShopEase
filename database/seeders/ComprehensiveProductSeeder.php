<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ComprehensiveProductSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        Product::query()->forceDelete();
        Category::query()->delete();

        // Create Categories
        $categories = [
            ['name' => 'Men\'s Clothing', 'slug' => 'mens-clothing', 'description' => 'Premium menswear collection', 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Women\'s Clothing', 'slug' => 'womens-clothing', 'description' => 'Elegant women\'s fashion', 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Footwear', 'slug' => 'footwear', 'description' => 'Shoes, sneakers & boots', 'is_active' => true, 'sort_order' => 3],
            ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Bags, watches & jewelry', 'is_active' => true, 'sort_order' => 4],
            ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Gadgets & tech accessories', 'is_active' => true, 'sort_order' => 5],
            ['name' => 'Home & Living', 'slug' => 'home-living', 'description' => 'Home decor & essentials', 'is_active' => true, 'sort_order' => 6],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $category = Category::create($cat);
            $categoryIds[$cat['slug']] = $category->id;
        }

        $products = $this->getProducts($categoryIds);

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('✓ Created ' . count($categories) . ' categories');
        $this->command->info('✓ Created ' . count($products) . ' products with images');
    }

    private function getProducts($categoryIds): array
    {
        return [
            // Men's Clothing
            ['name' => 'Classic White Oxford Shirt', 'description' => 'Crisp cotton oxford shirt with button-down collar.', 'price' => 89.00, 'category_id' => $categoryIds['mens-clothing'], 'category_old' => 'Fashion', 'gender' => 'men', 'stock' => 50, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1596755094514-f87e34085b2c?w=600'],
            ['name' => 'Navy Blue Blazer', 'description' => 'Tailored wool-blend blazer with notch lapels.', 'price' => 295.00, 'discount_price' => 245.00, 'category_id' => $categoryIds['mens-clothing'], 'category_old' => 'Fashion', 'gender' => 'men', 'stock' => 30, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=600'],
            ['name' => 'Slim Fit Chinos', 'description' => 'Comfortable stretch cotton chinos in khaki.', 'price' => 75.00, 'category_id' => $categoryIds['mens-clothing'], 'category_old' => 'Fashion', 'gender' => 'men', 'stock' => 80, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1473966968600-fa801b869a1a?w=600'],
            ['name' => 'Cashmere V-Neck Sweater', 'description' => 'Luxuriously soft pure cashmere sweater.', 'price' => 195.00, 'category_id' => $categoryIds['mens-clothing'], 'category_old' => 'Fashion', 'gender' => 'men', 'stock' => 25, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=600'],
            ['name' => 'Denim Jacket', 'description' => 'Classic indigo denim jacket with vintage wash.', 'price' => 125.00, 'category_id' => $categoryIds['mens-clothing'], 'category_old' => 'Fashion', 'gender' => 'men', 'stock' => 45, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1576995853123-5a10305d93c0?w=600'],
            ['name' => 'Premium Polo Shirt', 'description' => 'Pique cotton polo with embroidered logo.', 'price' => 65.00, 'category_id' => $categoryIds['mens-clothing'], 'category_old' => 'Fashion', 'gender' => 'men', 'stock' => 100, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1625910513413-5fc45e80b5b5?w=600'],
            ['name' => 'Leather Bomber Jacket', 'description' => 'Genuine leather bomber with ribbed cuffs.', 'price' => 450.00, 'discount_price' => 375.00, 'category_id' => $categoryIds['mens-clothing'], 'category_old' => 'Fashion', 'gender' => 'men', 'stock' => 15, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=600'],
            ['name' => 'Linen Summer Shirt', 'description' => 'Breathable pure linen shirt for warm weather.', 'price' => 85.00, 'category_id' => $categoryIds['mens-clothing'], 'category_old' => 'Fashion', 'gender' => 'men', 'stock' => 60, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1598033129183-c4f50c736f10?w=600'],
            ['name' => 'Wool Overcoat', 'description' => 'Double-breasted wool overcoat in camel.', 'price' => 395.00, 'category_id' => $categoryIds['mens-clothing'], 'category_old' => 'Fashion', 'gender' => 'men', 'stock' => 20, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1544923246-77307dd628b8?w=600'],
            ['name' => 'Graphic Print T-Shirt', 'description' => 'Soft cotton tee with artistic print.', 'price' => 45.00, 'category_id' => $categoryIds['mens-clothing'], 'category_old' => 'Fashion', 'gender' => 'men', 'stock' => 120, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=600'],
            ['name' => 'Tailored Dress Pants', 'description' => 'Wool-blend dress pants with pressed crease.', 'price' => 145.00, 'category_id' => $categoryIds['mens-clothing'], 'category_old' => 'Fashion', 'gender' => 'men', 'stock' => 40, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1624378439575-d8705ad7ae80?w=600'],
            ['name' => 'Hooded Sweatshirt', 'description' => 'Premium fleece hoodie with kangaroo pocket.', 'price' => 95.00, 'category_id' => $categoryIds['mens-clothing'], 'category_old' => 'Fashion', 'gender' => 'men', 'stock' => 75, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=600'],

            // Women's Clothing
            ['name' => 'Silk Blouse', 'description' => 'Elegant silk blouse with bow tie neck.', 'price' => 145.00, 'category_id' => $categoryIds['womens-clothing'], 'category_old' => 'Fashion', 'gender' => 'women', 'stock' => 40, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1564257631407-4deb1f99d992?w=600'],
            ['name' => 'Floral Midi Dress', 'description' => 'Romantic floral print dress with flowing silhouette.', 'price' => 125.00, 'discount_price' => 99.00, 'category_id' => $categoryIds['womens-clothing'], 'category_old' => 'Fashion', 'gender' => 'women', 'stock' => 55, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1572804013309-59a88b7e92f1?w=600'],
            ['name' => 'High-Waist Trousers', 'description' => 'Tailored wide-leg trousers in classic black.', 'price' => 110.00, 'category_id' => $categoryIds['womens-clothing'], 'category_old' => 'Fashion', 'gender' => 'women', 'stock' => 65, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=600'],
            ['name' => 'Cashmere Cardigan', 'description' => 'Soft cashmere cardigan with pearl buttons.', 'price' => 225.00, 'category_id' => $categoryIds['womens-clothing'], 'category_old' => 'Fashion', 'gender' => 'women', 'stock' => 30, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1434389677669-e08b4cac3105?w=600'],
            ['name' => 'Little Black Dress', 'description' => 'Timeless LBD with elegant neckline.', 'price' => 175.00, 'category_id' => $categoryIds['womens-clothing'], 'category_old' => 'Fashion', 'gender' => 'women', 'stock' => 35, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=600'],
            ['name' => 'Denim Skinny Jeans', 'description' => 'High-rise skinny jeans with stretch comfort.', 'price' => 89.00, 'category_id' => $categoryIds['womens-clothing'], 'category_old' => 'Fashion', 'gender' => 'women', 'stock' => 90, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=600'],
            ['name' => 'Wool Blend Coat', 'description' => 'Elegant belted coat in camel.', 'price' => 295.00, 'discount_price' => 249.00, 'category_id' => $categoryIds['womens-clothing'], 'category_old' => 'Fashion', 'gender' => 'women', 'stock' => 25, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=600'],
            ['name' => 'Striped Breton Top', 'description' => 'Classic French-style striped top.', 'price' => 55.00, 'category_id' => $categoryIds['womens-clothing'], 'category_old' => 'Fashion', 'gender' => 'women', 'stock' => 80, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=600'],
            ['name' => 'Pleated Maxi Skirt', 'description' => 'Flowing pleated skirt in dusty rose.', 'price' => 95.00, 'category_id' => $categoryIds['womens-clothing'], 'category_old' => 'Fashion', 'gender' => 'women', 'stock' => 45, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1583496661160-fb5886a0uj9a?w=600'],
            ['name' => 'Knit Turtleneck', 'description' => 'Cozy ribbed turtleneck in cream.', 'price' => 85.00, 'category_id' => $categoryIds['womens-clothing'], 'category_old' => 'Fashion', 'gender' => 'women', 'stock' => 70, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=600'],
            ['name' => 'Satin Slip Dress', 'description' => 'Luxurious satin slip dress for evening.', 'price' => 165.00, 'category_id' => $categoryIds['womens-clothing'], 'category_old' => 'Fashion', 'gender' => 'women', 'stock' => 30, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1566174053879-31528523f8ae?w=600'],

            // Footwear
            ['name' => 'White Leather Sneakers', 'description' => 'Clean minimalist sneakers in premium leather.', 'price' => 145.00, 'category_id' => $categoryIds['footwear'], 'category_old' => 'Footwear', 'gender' => 'unisex', 'stock' => 100, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600'],
            ['name' => 'Oxford Dress Shoes', 'description' => 'Handcrafted leather oxfords.', 'price' => 275.00, 'category_id' => $categoryIds['footwear'], 'category_old' => 'Footwear', 'gender' => 'men', 'stock' => 35, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1614252235316-8c857d38b5f4?w=600'],
            ['name' => 'Running Shoes Pro', 'description' => 'High-performance running shoes.', 'price' => 165.00, 'discount_price' => 139.00, 'category_id' => $categoryIds['footwear'], 'category_old' => 'Footwear', 'gender' => 'unisex', 'stock' => 80, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600'],
            ['name' => 'Suede Chelsea Boots', 'description' => 'Classic Chelsea boots in tan suede.', 'price' => 225.00, 'category_id' => $categoryIds['footwear'], 'category_old' => 'Footwear', 'gender' => 'men', 'stock' => 40, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1638247025967-b4e38f787b76?w=600'],
            ['name' => 'Stiletto Heels', 'description' => 'Elegant pointed-toe heels in black patent.', 'price' => 195.00, 'category_id' => $categoryIds['footwear'], 'category_old' => 'Footwear', 'gender' => 'women', 'stock' => 45, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1543163521-1bf539c55dd2?w=600'],
            ['name' => 'Canvas Slip-Ons', 'description' => 'Casual canvas slip-ons for summer.', 'price' => 55.00, 'category_id' => $categoryIds['footwear'], 'category_old' => 'Footwear', 'gender' => 'unisex', 'stock' => 120, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=600'],
            ['name' => 'Ankle Boots', 'description' => 'Chic leather ankle boots with block heel.', 'price' => 185.00, 'category_id' => $categoryIds['footwear'], 'category_old' => 'Footwear', 'gender' => 'women', 'stock' => 50, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1608256246200-53e635b5b65f?w=600'],
            ['name' => 'Loafers Classic', 'description' => 'Penny loafers in burgundy leather.', 'price' => 195.00, 'category_id' => $categoryIds['footwear'], 'category_old' => 'Footwear', 'gender' => 'men', 'stock' => 55, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1582897085656-c636d006a246?w=600'],
            ['name' => 'Platform Sandals', 'description' => 'Trendy platform sandals with ankle strap.', 'price' => 125.00, 'discount_price' => 99.00, 'category_id' => $categoryIds['footwear'], 'category_old' => 'Footwear', 'gender' => 'women', 'stock' => 60, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1603487742131-4160ec999306?w=600'],
            ['name' => 'High-Top Sneakers', 'description' => 'Retro-inspired high-tops in black and white.', 'price' => 115.00, 'category_id' => $categoryIds['footwear'], 'category_old' => 'Footwear', 'gender' => 'unisex', 'stock' => 75, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=600'],
            ['name' => 'Hiking Boots', 'description' => 'Waterproof hiking boots with superior grip.', 'price' => 185.00, 'category_id' => $categoryIds['footwear'], 'category_old' => 'Footwear', 'gender' => 'unisex', 'stock' => 40, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1520219306100-ec4afeeefe58?w=600'],
            ['name' => 'Ballet Flats', 'description' => 'Comfortable leather ballet flats in nude.', 'price' => 95.00, 'category_id' => $categoryIds['footwear'], 'category_old' => 'Footwear', 'gender' => 'women', 'stock' => 65, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1566150905458-1bf1fc113f0d?w=600'],

            // Accessories
            ['name' => 'Leather Tote Bag', 'description' => 'Spacious leather tote with interior pockets.', 'price' => 245.00, 'category_id' => $categoryIds['accessories'], 'category_old' => 'Accessories', 'gender' => 'women', 'stock' => 35, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=600'],
            ['name' => 'Classic Aviator Sunglasses', 'description' => 'Timeless aviator frames with polarized lenses.', 'price' => 165.00, 'category_id' => $categoryIds['accessories'], 'category_old' => 'Accessories', 'gender' => 'unisex', 'stock' => 80, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600'],
            ['name' => 'Automatic Watch', 'description' => 'Swiss automatic movement watch.', 'price' => 495.00, 'discount_price' => 425.00, 'category_id' => $categoryIds['accessories'], 'category_old' => 'Accessories', 'gender' => 'men', 'stock' => 20, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=600'],
            ['name' => 'Silk Scarf', 'description' => 'Hand-printed silk scarf with artistic pattern.', 'price' => 125.00, 'category_id' => $categoryIds['accessories'], 'category_old' => 'Accessories', 'gender' => 'women', 'stock' => 50, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1520903920243-00d872a2d1c9?w=600'],
            ['name' => 'Leather Wallet', 'description' => 'Bifold wallet in full-grain leather.', 'price' => 85.00, 'category_id' => $categoryIds['accessories'], 'category_old' => 'Accessories', 'gender' => 'men', 'stock' => 90, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=600'],
            ['name' => 'Pearl Drop Earrings', 'description' => 'Freshwater pearl earrings with sterling silver.', 'price' => 145.00, 'category_id' => $categoryIds['accessories'], 'category_old' => 'Accessories', 'gender' => 'women', 'stock' => 40, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=600'],
            ['name' => 'Canvas Backpack', 'description' => 'Durable canvas backpack with leather trim.', 'price' => 125.00, 'category_id' => $categoryIds['accessories'], 'category_old' => 'Accessories', 'gender' => 'unisex', 'stock' => 70, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=600'],
            ['name' => 'Leather Belt', 'description' => 'Classic leather belt with brushed silver buckle.', 'price' => 75.00, 'category_id' => $categoryIds['accessories'], 'category_old' => 'Accessories', 'gender' => 'men', 'stock' => 100, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1624222247344-550fb60583dc?w=600'],
            ['name' => 'Gold Chain Necklace', 'description' => 'Delicate gold-plated chain necklace.', 'price' => 95.00, 'category_id' => $categoryIds['accessories'], 'category_old' => 'Accessories', 'gender' => 'women', 'stock' => 55, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=600'],
            ['name' => 'Crossbody Bag', 'description' => 'Compact crossbody bag in quilted leather.', 'price' => 175.00, 'category_id' => $categoryIds['accessories'], 'category_old' => 'Accessories', 'gender' => 'women', 'stock' => 45, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1548036328-c9fa89d128fa?w=600'],
            ['name' => 'Wool Fedora Hat', 'description' => 'Classic wool fedora with grosgrain ribbon.', 'price' => 85.00, 'category_id' => $categoryIds['accessories'], 'category_old' => 'Accessories', 'gender' => 'unisex', 'stock' => 35, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1514327605112-b887c0e61c0a?w=600'],

            // Electronics
            ['name' => 'Wireless Headphones', 'description' => 'Premium over-ear headphones with ANC.', 'price' => 299.00, 'discount_price' => 249.00, 'category_id' => $categoryIds['electronics'], 'category_old' => 'Electronics', 'gender' => 'unisex', 'stock' => 60, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=600'],
            ['name' => 'Smart Watch', 'description' => 'Feature-rich smartwatch with health tracking.', 'price' => 349.00, 'category_id' => $categoryIds['electronics'], 'category_old' => 'Electronics', 'gender' => 'unisex', 'stock' => 45, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=600'],
            ['name' => 'Wireless Earbuds', 'description' => 'True wireless earbuds with premium sound.', 'price' => 179.00, 'category_id' => $categoryIds['electronics'], 'category_old' => 'Electronics', 'gender' => 'unisex', 'stock' => 80, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=600'],
            ['name' => 'Portable Speaker', 'description' => 'Waterproof Bluetooth speaker with 360° sound.', 'price' => 129.00, 'category_id' => $categoryIds['electronics'], 'category_old' => 'Electronics', 'gender' => 'unisex', 'stock' => 70, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=600'],
            ['name' => 'Laptop Stand', 'description' => 'Ergonomic aluminum laptop stand.', 'price' => 79.00, 'category_id' => $categoryIds['electronics'], 'category_old' => 'Electronics', 'gender' => 'unisex', 'stock' => 55, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=600'],
            ['name' => 'Wireless Charger', 'description' => 'Fast wireless charging pad.', 'price' => 45.00, 'category_id' => $categoryIds['electronics'], 'category_old' => 'Electronics', 'gender' => 'unisex', 'stock' => 100, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1586816879360-004f5b0c51e5?w=600'],
            ['name' => 'Mechanical Keyboard', 'description' => 'RGB mechanical keyboard with tactile switches.', 'price' => 149.00, 'category_id' => $categoryIds['electronics'], 'category_old' => 'Electronics', 'gender' => 'unisex', 'stock' => 40, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1511467687858-23d96c32e4ae?w=600'],
            ['name' => 'Webcam HD', 'description' => '1080p HD webcam with built-in microphone.', 'price' => 89.00, 'category_id' => $categoryIds['electronics'], 'category_old' => 'Electronics', 'gender' => 'unisex', 'stock' => 65, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1587826080692-f439cd0b70da?w=600'],
            ['name' => 'Power Bank', 'description' => '20000mAh portable charger with fast charging.', 'price' => 59.00, 'category_id' => $categoryIds['electronics'], 'category_old' => 'Electronics', 'gender' => 'unisex', 'stock' => 90, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=600'],
            ['name' => 'USB-C Hub', 'description' => 'Multi-port USB-C hub with HDMI.', 'price' => 69.00, 'category_id' => $categoryIds['electronics'], 'category_old' => 'Electronics', 'gender' => 'unisex', 'stock' => 75, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1625723044792-44de16ccb4e9?w=600'],

            // Home & Living
            ['name' => 'Ceramic Vase Set', 'description' => 'Set of 3 minimalist ceramic vases.', 'price' => 85.00, 'category_id' => $categoryIds['home-living'], 'category_old' => 'Home', 'gender' => 'unisex', 'stock' => 50, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1578500494198-246f612d3b3d?w=600'],
            ['name' => 'Scented Candle Set', 'description' => 'Luxury soy candles in lavender, vanilla.', 'price' => 55.00, 'category_id' => $categoryIds['home-living'], 'category_old' => 'Home', 'gender' => 'unisex', 'stock' => 80, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1602607753754-e8e0e5e5e5e5?w=600'],
            ['name' => 'Throw Blanket', 'description' => 'Cozy knit throw blanket in cream.', 'price' => 95.00, 'category_id' => $categoryIds['home-living'], 'category_old' => 'Home', 'gender' => 'unisex', 'stock' => 45, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600'],
            ['name' => 'Desk Organizer', 'description' => 'Bamboo desk organizer with compartments.', 'price' => 45.00, 'category_id' => $categoryIds['home-living'], 'category_old' => 'Home', 'gender' => 'unisex', 'stock' => 70, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?w=600'],
            ['name' => 'Plant Pot Set', 'description' => 'Set of 3 terracotta pots with saucers.', 'price' => 35.00, 'category_id' => $categoryIds['home-living'], 'category_old' => 'Home', 'gender' => 'unisex', 'stock' => 90, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1485955900006-10f4d324d411?w=600'],
            ['name' => 'Table Lamp', 'description' => 'Modern LED table lamp with adjustable brightness.', 'price' => 89.00, 'category_id' => $categoryIds['home-living'], 'category_old' => 'Home', 'gender' => 'unisex', 'stock' => 55, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=600'],
            ['name' => 'Wall Art Print', 'description' => 'Abstract art print on premium paper.', 'price' => 65.00, 'category_id' => $categoryIds['home-living'], 'category_old' => 'Home', 'gender' => 'unisex', 'stock' => 40, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1513519245088-0e12902e35a6?w=600'],
            ['name' => 'Coffee Table Book', 'description' => 'Stunning photography book on architecture.', 'price' => 75.00, 'category_id' => $categoryIds['home-living'], 'category_old' => 'Home', 'gender' => 'unisex', 'stock' => 35, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=600'],
            ['name' => 'Cushion Cover Set', 'description' => 'Set of 2 linen cushion covers in sage.', 'price' => 45.00, 'category_id' => $categoryIds['home-living'], 'category_old' => 'Home', 'gender' => 'unisex', 'stock' => 60, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1584100936595-c0654b55a2e2?w=600'],
            ['name' => 'Storage Basket', 'description' => 'Handwoven seagrass basket for storage.', 'price' => 55.00, 'category_id' => $categoryIds['home-living'], 'category_old' => 'Home', 'gender' => 'unisex', 'stock' => 65, 'status' => 'active', 'image' => 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?w=600'],
        ];
    }
}
