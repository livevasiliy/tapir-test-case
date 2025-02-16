# Use the official PHP image as base
FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libssl-dev \
    libmcrypt-dev \
    zlib1g-dev \
    libonig-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    pkg-config \
    libmagickwand-dev \
    libsqlite3-dev \
    libsqlite3-0 \
    librabbitmq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring zip exif pcntl bcmath curl xml sockets \
    && pecl install amqp \
    && docker-php-ext-enable amqp

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy application
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Install Node.js 16
RUN curl -fsSL https://deb.nodesource.com/setup_16.x | bash -
RUN apt-get install -y nodejs

# Install global laravel echo server for working with Websocket
RUN npm i -g laravel-echo-server

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
