# Stage 1: Build Node.js frontend (needs PHP for wayfinder plugin)
FROM node:latest AS frontend-build

# Install PHP for wayfinder plugin
RUN apt-get update && apt-get install -y \
    php \
    php-cli \
    php-common \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app
COPY package*.json ./
COPY yarn.lock ./
RUN yarn install --frozen-lockfile
COPY . .
RUN yarn build

# Stage 2: PHP-FPM + Laravel backend
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    curl \
    git

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    gd \
    pdo \
    pdo_pgsql \
    bcmath \
    ctype \
    json \
    mbstring \
    openssl \
    tokenizer

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy entire project
COPY . .

# Copy built frontend assets from Stage 1
COPY --from=frontend-build /app/public/build ./public/build

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /app && chmod -R 755 /app

# Create storage directories
RUN mkdir -p storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Expose PHP-FPM port (internal, not for direct access)
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
