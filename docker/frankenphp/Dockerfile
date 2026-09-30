
FROM dunglas/frankenphp:php8.5

RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    intl \
    zip \
    bcmath \
    pcntl \
    opcache \
    sockets \
    excimer \
    pcov \
    @composer

RUN cp $PHP_INI_DIR/php.ini-production $PHP_INI_DIR/php.ini && \
    echo "upload_max_filesize = 50M" > $PHP_INI_DIR/conf.d/custom-uploads.ini && \
    echo "post_max_size = 50M" >> $PHP_INI_DIR/conf.d/custom-uploads.ini

WORKDIR /app

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data

RUN mkdir -p /var/www/.config/psysh /var/www/.cache/composer /data/caddy /config/caddy \
        && chown -R www-data:www-data /var/www/.config /var/www/.cache /app /data/caddy /config/caddy

COPY --chown=www-data:www-data . .

USER www-data

EXPOSE 80 443

