#!/bin/bash

composer install

sed -i  's/${APP_ENV}/'"$APP_ENV"'/' /etc/nginx/sites-enabled/symfony
sed -i  's/${APP_PHP}/'"$APP_PHP"'/' /etc/nginx/sites-enabled/symfony

usermod -u 1000 www-data
chown -R www-data:www-data /var/www/symfony/var/cache /var/www/symfony/var/logs
chmod -R 777 /var/www/symfony/var/cache /var/www/symfony/var/logs

exec "$@"