# ---------- 1) Build des assets (Vite) ----------
FROM node:22-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# ---------- 2) Application PHP + Apache ----------
FROM php:8.3-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
      git unzip libzip-dev libpng-dev libjpeg-dev libfreetype6-dev libicu-dev libpq-dev libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql pdo_pgsql bcmath gd zip intl mbstring opcache \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY docker/uploads.ini /usr/local/etc/php/conf.d/uploads.ini
COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

COPY . .
COPY --from=assets /app/public/build ./public/build
RUN composer dump-autoload --optimize --no-dev \
    && mkdir -p storage/framework/{cache,sessions,views} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/entrypoint.sh /entrypoint.sh
RUN sed -i 's/\r$//' /entrypoint.sh && chmod +x /entrypoint.sh

EXPOSE 10000
CMD ["/entrypoint.sh"]
