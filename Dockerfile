FROM php:8.3-cli

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Node for building frontend assets
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader
RUN npm ci && npm run build

RUN touch database/database.sqlite
RUN chmod -R 775 storage bootstrap/cache database
RUN php artisan migrate --force

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000