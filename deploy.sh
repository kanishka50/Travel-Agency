#!/bin/bash
set -e

echo "Deploying application..."

# Install dependencies
composer install --no-dev --optimize-autoloader

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Build frontend assets
npm ci
npm run build

# Set permissions
chmod -R 775 storage bootstrap/cache

echo "Deployment complete!"