#!/usr/bin/env bash
set -euo pipefail

# Install PHP and Composer if missing
if ! command -v php >/dev/null; then
  apt-get update
  apt-get install -y php-cli unzip curl
fi

if ! command -v composer >/dev/null; then
  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan migrate --force
