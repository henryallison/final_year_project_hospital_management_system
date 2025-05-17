#!/bin/bash
set -e

# Run migrations if needed
# php artisan migrate --force

# Clear and cache config
php artisan config:clear
php artisan config:cache

# Optimize the application
php artisan optimize:clear
php artisan optimize
php artisan view:cache
php artisan event:cache
