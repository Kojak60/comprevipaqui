#!/bin/sh
set -e
if ! command -v composer >/dev/null 2>&1; then
  curl -sS https://getcomposer.org/installer | php -- --install-dir=/tmp --filename=composer
  PHP_COMPOSER="php /tmp/composer"
else
  PHP_COMPOSER="composer"
fi
$PHP_COMPOSER install --no-dev --optimize-autoloader --no-interaction --prefer-dist --working-dir=core
