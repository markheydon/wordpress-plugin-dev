#!/usr/bin/env bash
set -euo pipefail

cd /workspace

echo "==> Installing Composer dependencies"
if [ -f composer.json ]; then
  composer install --no-interaction --prefer-dist
fi

echo "==> Waiting for WordPress files"
MAX_WORDPRESS_FILE_ATTEMPTS=30
WORDPRESS_FILE_ATTEMPT=0

until [ -f /var/www/html/wp-load.php ]; do
  WORDPRESS_FILE_ATTEMPT=$((WORDPRESS_FILE_ATTEMPT + 1))
  if [ "$WORDPRESS_FILE_ATTEMPT" -ge "$MAX_WORDPRESS_FILE_ATTEMPTS" ]; then
    echo "ERROR: WordPress files did not become available in /var/www/html in time"
    echo "Check the wordpress bind mount at /workspace/wordpress and container startup logs."
    exit 1
  fi

  echo "Waiting for WordPress files... ($WORDPRESS_FILE_ATTEMPT/$MAX_WORDPRESS_FILE_ATTEMPTS)"
  sleep 2
done

echo "==> Waiting for database"
MAX_DB_ATTEMPTS=30
DB_ATTEMPT=0

until wp db check --path=/var/www/html --allow-root >/dev/null 2>&1; do
  DB_ATTEMPT=$((DB_ATTEMPT + 1))
  if [ "$DB_ATTEMPT" -ge "$MAX_DB_ATTEMPTS" ]; then
    echo "ERROR: Database did not become ready in time"
    echo "Check docker logs for the db container and confirm credentials/volumes are correct."
    exit 1
  fi

  echo "Waiting for database... ($DB_ATTEMPT/$MAX_DB_ATTEMPTS)"
  sleep 2
done

echo "==> Database ready"

echo "==> Ensuring writable WordPress content directories"
sudo chmod 2775 /var/www/html/wp-content
sudo install -d -m 2775 -o www-data -g www-data /var/www/html/wp-content/plugins
sudo install -d -m 2775 -o www-data -g www-data /var/www/html/wp-content/upgrade
sudo install -d -m 2775 -o www-data -g www-data /var/www/html/wp-content/languages
sudo install -d -m 2775 -o www-data -g www-data /var/www/html/wp-content/languages/plugins
sudo install -d -m 2775 -o www-data -g www-data /var/www/html/wp-content/languages/themes
sudo install -d -m 2775 -o www-data -g www-data /var/www/html/wp-content/uploads
sudo chgrp -R www-data /var/www/html/wp-content/plugins
sudo chgrp -R www-data /var/www/html/wp-content/languages
sudo chgrp -R www-data /var/www/html/wp-content/uploads
sudo find /var/www/html/wp-content/plugins -type d -exec chmod 2775 {} +
sudo find /var/www/html/wp-content/plugins -type f -exec chmod 0664 {} +
sudo find /var/www/html/wp-content/languages -type d -exec chmod 2775 {} +
sudo find /var/www/html/wp-content/languages -type f -exec chmod 0664 {} +
sudo find /var/www/html/wp-content/uploads -type d -exec chmod 2775 {} +
sudo find /var/www/html/wp-content/uploads -type f -exec chmod 0664 {} +

ensure_uk_locale() {
  echo "==> Installing UK English language pack"
  wp language core install en_GB --path=/var/www/html --allow-root
  wp site switch-language en_GB --path=/var/www/html --allow-root
}

if ! wp core is-installed --path=/var/www/html --allow-root >/dev/null 2>&1; then
  echo "==> Installing WordPress"
  wp core install \
    --path=/var/www/html \
    --url=http://localhost:8080 \
    --title="WordPress Plugin Dev" \
    --admin_user=admin \
    --admin_password=admin \
    --admin_email=admin@example.com \
    --skip-email \
    --allow-root
  ensure_uk_locale
else
  echo "==> WordPress already installed"
  ensure_uk_locale
fi

echo "==> Applying regional settings"
wp option update timezone_string Europe/London --path=/var/www/html --allow-root
wp option update date_format 'j F Y' --path=/var/www/html --allow-root
wp option update time_format 'H:i' --path=/var/www/html --allow-root
wp option update start_of_week 1 --path=/var/www/html --allow-root

echo "==> Done"
echo "Site:  http://localhost:8080"
echo "Admin: http://localhost:8080/wp-admin"
echo "User:  admin"
echo "Pass:  admin"
