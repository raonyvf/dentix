FROM php:8.3-cli-bullseye AS build

# Install required packages, Node.js 22 and Composer
RUN apt-get update \
    && apt-get install -y git unzip libpq-dev curl gnupg \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app
COPY . .

# Ensure Laravel directories exist for Composer scripts
RUN mkdir -p bootstrap/cache storage

# Install PHP dependencies and build front-end assets
RUN composer install --no-dev --optimize-autoloader \
    && npm install \
    && npm run build \
    && rm -rf node_modules

FROM php:8.3-cli-bullseye

# Ensure the application runs in production mode
ENV APP_ENV=production \
    APP_DEBUG=false

# Install system packages and PHP extensions
RUN apt-get update \
    && apt-get install -y git unzip libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=build /usr/local/bin/composer /usr/local/bin/composer
WORKDIR /app
COPY --from=build /app /app

# Ensure writable directories for runtime
RUN mkdir -p storage bootstrap/cache \
    && chmod -R ug+w storage bootstrap/cache

EXPOSE 8080

CMD php artisan migrate --force && php artisan serve --host 0.0.0.0 --port ${PORT:-8080}
