FROM richarvey/nginx-php-fpm:3.1.6

WORKDIR /var/www/html

COPY . .

# Image config
ENV WEBROOT=/var/www/html/public
ENV SKIP_COMPOSER=0
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1

# Laravel
ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr

RUN composer install --no-dev --optimize-autoloader

RUN php artisan config:clear || true
RUN php artisan cache:clear || true
RUN php artisan route:clear || true
RUN php artisan view:clear || true

RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080