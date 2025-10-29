FROM php:8.2-fpm-alpine

# Sistema e dependências para extensões e build
RUN apk add --no-cache \
    bash \
    oniguruma-dev \
    libzip-dev \
    sqlite-dev \
    icu-dev \
    autoconf \
    gcc \
    g++ \
    make \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    zlib-dev \
    zip \
    curl \
    openssh-client \
    ca-certificates \
    bash

# Instala extensões PHP
RUN docker-php-ext-configure zip && \
    docker-php-ext-install pdo pdo_mysql pdo_sqlite zip intl bcmath

# GD
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype && \
    docker-php-ext-install gd || true

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copia composer* primeiro para aproveitar cache de build
COPY composer.json composer.lock ./
RUN if [ -f composer.json ]; then composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader || true; fi

# Copia aplicação
COPY . /var/www/html

RUN mkdir -p storage bootstrap/cache database || true
RUN chown -R www-data:www-data storage bootstrap/cache database || true

EXPOSE 9000

ENTRYPOINT ["/usr/bin/dumb-init", "--"]
CMD ["php-fpm"]
