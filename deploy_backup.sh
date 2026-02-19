#!/bin/bash

# Navigate to project directory (based on observed path)
cd ~/domains/hfburdwan.in/public_html/dashboard || exit

# Pull latest changes (including backup config)
echo "Pulling latest changes..."
git pull

# Install dependencies (spatie/laravel-backup)
echo "Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Run migrations
echo "Running migrations..."
php artisan migrate --force

# Clear and cache config
echo "Clearing cache..."
php artisan optimize:clear
php artisan config:cache

# Test backup
echo "Testing backup..."
php artisan backup:run --only-db

echo "Deployment complete! Please set up the Cron Job in Hostinger Panel:"
echo "* * * * * php $(pwd)/artisan schedule:run >> /dev/null 2>&1"
