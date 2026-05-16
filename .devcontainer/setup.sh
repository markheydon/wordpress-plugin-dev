#!/usr/bin/env bash
set -euo pipefail

cd /workspace

if [ -f composer.json ]; then
  composer install --no-interaction --prefer-dist
fi

if [ ! -d /var/www/html/wp-admin ]; then
  echo "WordPress files not found at /var/www/html yet; skipping wp-cli setup."
  exit 0
fi

if ! wp core is-installed --path=/var/www/html --allow-root >/dev/null 2>&1; then
  wp core install \
    --path=/var/www/html \
    --url=http://localhost:8080 \
    --title="WordPress Plugin Dev" \
    --admin_user=admin \
    --admin_password=admin \
    --admin_email=admin@example.com \
    --skip-email \
    --allow-root
fi

wp language core install en_GB --path=/var/www/html --allow-root || true
wp site switch-language en_GB --path=/var/www/html --allow-root || true
wp option update timezone_string Europe/London --path=/var/www/html --allow-root
wp option update date_format 'j F Y' --path=/var/www/html --allow-root
wp option update time_format 'H:i' --path=/var/www/html --allow-root
wp option update start_of_week 1 --path=/var/www/html --allow-root
