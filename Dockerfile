FROM php:8.4-cli

WORKDIR /app

# Install system dependencies including libicu-dev for intl and libzip-dev for zip
RUN apt-get update && apt-get install -y \
    git unzip curl libzip-dev zip libicu-dev nodejs npm \
    && docker-php-ext-install pdo pdo_mysql intl zip

COPY . .

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install

RUN npm install && npm run build

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}
