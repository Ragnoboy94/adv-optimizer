FROM php:8.3-fpm-alpine
RUN apk add --no-cache bash git unzip icu-dev oniguruma-dev libpq-dev $PHPIZE_DEPS \
 && docker-php-ext-install pdo_pgsql intl mbstring bcmath pcntl \
 && docker-php-ext-enable opcache \
 && apk del --no-cache $PHPIZE_DEPS
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
RUN addgroup -S laravel && adduser -S laravel -G laravel && chown -R laravel:laravel /var/www/html
USER laravel
CMD ["php-fpm"]
