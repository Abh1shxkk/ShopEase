#!/usr/bin/env bash
set -e

echo "=========================================="
echo "🚀 ShopEase Build Script for Render.com"
echo "=========================================="

echo ""
echo "📦 Step 1: Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

echo ""
echo "📦 Step 2: Installing Node.js dependencies..."
npm ci --legacy-peer-deps

echo ""
echo "🔨 Step 3: Building frontend assets..."
npm run build

echo ""
echo "⚙️ Step 4: Caching Laravel configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ""
echo "🗄️ Step 5: Running database migrations..."
php artisan migrate --force

echo ""
echo "🔗 Step 6: Creating storage link..."
php artisan storage:link || true

echo ""
echo "=========================================="
echo "✅ Build completed successfully!"
echo "=========================================="
