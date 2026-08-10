# =============================================================================
# Imagine de productie pentru RENTeaza.
# Build-ul se face in intregime aici: nimic nu se instaleaza la runtime.
# Se construieste din radacina proiectului:
#   docker build -f docker/production/Dockerfile .
# =============================================================================

# ---------------------------------------------------------------- base: PHP
FROM docker.io/library/php:8.3-fpm-bookworm AS base

ARG DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        zip \
        curl \
        ca-certificates \
        libzip-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libicu-dev \
        libxml2-dev \
        default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        pdo_mysql \
        bcmath \
        gd \
        zip \
        intl \
        exif \
        pcntl \
        opcache \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=docker.io/library/composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# ------------------------------------------------------- vendor: cod + PHP deps
FROM base AS vendor

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_MEMORY_LIMIT=-1

# intai doar fisierele de dependinte, ca layerul sa se refoloseasca la rebuild
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --no-interaction \
        --no-progress

COPY . .

RUN composer dump-autoload --optimize --classmap-authoritative --no-dev \
    && php artisan package:discover --ansi

# ------------------------------------------------------------ frontend: Vite
FROM docker.io/library/node:22-bookworm-slim AS frontend

WORKDIR /var/www/html

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
# resources/js/app.js importa ZiggyVue direct din vendor
COPY --from=vendor /var/www/html/vendor/tightenco/ziggy ./vendor/tightenco/ziggy

# ATENTIE: variabilele VITE_* se "coc" in bundle la BUILD, nu se citesc la runtime.
# In Coolify trebuie bifate ca Build Variable, altfel raman goale in JS-ul livrat.
ARG VITE_APP_NAME=RENTeaza
ARG VITE_AWS_PUBLIC_URL
ARG VITE_MAPBOX_TOKEN
ENV VITE_APP_NAME=${VITE_APP_NAME} \
    VITE_AWS_PUBLIC_URL=${VITE_AWS_PUBLIC_URL} \
    VITE_MAPBOX_TOKEN=${VITE_MAPBOX_TOKEN}

RUN npm run build

# --------------------------------------------------------------- runtime final
FROM base AS runtime

RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx \
        supervisor \
    && rm -f /etc/nginx/sites-enabled/default \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY docker/production/php.ini        /usr/local/etc/php/conf.d/99-renteaza.ini
COPY docker/production/nginx.conf     /etc/nginx/conf.d/default.conf
COPY docker/production/supervisord.conf /etc/supervisor/supervisord.conf
COPY docker/production/entrypoint.sh  /usr/local/bin/entrypoint

COPY --from=vendor   --chown=www-data:www-data /var/www/html              /var/www/html
COPY --from=frontend --chown=www-data:www-data /var/www/html/public/build /var/www/html/public/build

RUN chmod +x /usr/local/bin/entrypoint \
    && mkdir -p \
        storage/app/public \
        storage/app/private \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

ENTRYPOINT ["entrypoint"]
CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]
