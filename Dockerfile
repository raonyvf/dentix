FROM composer:2.7 AS build

# Install Node.js 22
RUN apt-get update \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app
COPY . .

# Install PHP dependencies and build front-end assets
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build \
    && rm -rf node_modules

FROM php:8.3-cli

# Install system packages and PHP extensions
RUN apt-get update \
    && apt-get install -y git unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=build /usr/bin/composer /usr/local/bin/composer
WORKDIR /app
COPY --from=build /app /app

# Ensure necessary permissions
RUN chmod -R ug+w storage bootstrap/cache

EXPOSE 8080

CMD php artisan migrate --force && php artisan serve --host 0.0.0.0 --port ${PORT:-8080}
