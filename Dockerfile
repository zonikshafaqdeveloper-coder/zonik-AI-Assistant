FROM php:8.1-apache

ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
WORKDIR /var/www/html

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        git \
        unzip \
        zip \
        curl \
        ca-certificates \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libonig-dev \
        libxml2-dev \
        default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql mbstring zip exif pcntl bcmath gd \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY --from=node:18-bookworm-slim /usr/local/bin/node /usr/local/bin/node
COPY --from=node:18-bookworm-slim /usr/local/bin/npm /usr/local/bin/npm
COPY --from=node:18-bookworm-slim /usr/local/lib/node_modules /usr/local/lib/node_modules

COPY composer.json composer.lock ./
COPY app ./app
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader --no-scripts

COPY package.json package-lock.json webpack.mix.js ./
COPY resources ./resources
COPY public ./public
RUN npm ci --no-audit --no-fund \
    && npm run production \
    && rm -rf node_modules

COPY . .

RUN composer dump-autoload --optimize \
    && php artisan package:discover --ansi \
    && mkdir -p \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/testing \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

COPY docker/render-entrypoint.sh /usr/local/bin/render-entrypoint
RUN chmod +x /usr/local/bin/render-entrypoint

EXPOSE 10000

ENTRYPOINT ["render-entrypoint"]
CMD ["apache2-foreground"]
