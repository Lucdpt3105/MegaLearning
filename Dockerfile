# ============================================
# Stage 1: Build frontend assets (Node.js)
# ============================================
FROM node:20-alpine AS node-builder

WORKDIR /build

# Copy package files first for better caching
COPY package.json package-lock.json* ./
RUN npm ci

# Copy source files and build
COPY vite.config.js ./
COPY resources ./resources
COPY postcss.config.js* tailwind.config.js* ./
RUN npm run build


# ============================================
# Stage 2: Install PHP dependencies
# ============================================
FROM composer:2 AS composer-builder

WORKDIR /build

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    --ignore-platform-reqs

COPY . .
RUN composer dump-autoload --optimize --no-dev


# ============================================
# Stage 3: Production image (PHP-FPM + Nginx)
# ============================================
FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
        intl \
        opcache

# PHP production config
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# OPcache configuration for production
RUN echo "opcache.enable=1" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.memory_consumption=128" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.interned_strings_buffer=8" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.max_accelerated_files=10000" >> "$PHP_INI_DIR/conf.d/opcache.ini" \
    && echo "opcache.validate_timestamps=0" >> "$PHP_INI_DIR/conf.d/opcache.ini"

# PHP upload/memory limits
RUN echo "upload_max_filesize=64M" >> "$PHP_INI_DIR/conf.d/uploads.ini" \
    && echo "post_max_size=64M" >> "$PHP_INI_DIR/conf.d/uploads.ini" \
    && echo "memory_limit=256M" >> "$PHP_INI_DIR/conf.d/uploads.ini"

WORKDIR /var/www/html

# Copy application code
COPY --from=composer-builder /build /var/www/html

# Copy built frontend assets
COPY --from=node-builder /build/public/build /var/www/html/public/build

# Copy Nginx config
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Copy entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Create required directories and set permissions
RUN mkdir -p storage/framework/{sessions,views,cache} \
    && mkdir -p storage/logs \
    && mkdir -p bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Expose port
EXPOSE 8080

ENTRYPOINT ["/entrypoint.sh"]
