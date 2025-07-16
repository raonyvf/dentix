#!/usr/bin/env bash
set -euo pipefail

# Install PHP and Composer if missing
run_as_root() {
  if [ "$(id -u)" -ne 0 ] && command -v sudo >/dev/null; then
    sudo "$@"
  else
    "$@"
  fi
}

if ! command -v php >/dev/null; then
  run_as_root apt-get update
  run_as_root apt-get install -y php-cli unzip curl
fi

if ! command -v composer >/dev/null; then
  curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan migrate --force
