FROM php:8.2-fPM-alpine

RUN apk add --no-cache \
    linux-headers \
    build-base \
    autoconf \
    curl-dev \
    libzip-dev \
    zip \
    unzip

RUN docker-php-ext-install pdo_mysql zip
RUN pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

RUN chown -R www-data:www-data /var/www

EXPOSE 9000
CMD ["php-fpm"]
