#!/bin/bash
set -e

# Install dependencies
composer install --no-dev --optimize-autoloader

# Optimize Laravel
php artisan optimize:clear
php artisan optimize
php artisan view:cache
php artisan event:cache

# Migrate database (optional)
# php artisan migrate --force
