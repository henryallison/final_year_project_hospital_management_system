# Use an official PHP image
FROM php:8.2-fpm

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl \
    git \
    npm \
    nodejs \
    libzip-dev \
    libpq-dev

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application
COPY . /var/www

# Set permissions
RUN chown -R www-data:www-data /var/www

# Install dependencies (assumes you commit composer.lock and vendor/ is ignored)
RUN composer install --no-dev --optimize-autoloader

# ⚠️ Do NOT run php artisan key:generate here

# Expose port
EXPOSE 8000

# Start Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
