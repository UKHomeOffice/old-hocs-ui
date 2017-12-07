#!/bin/bash

composer install
bin/console assets:install --symlink
bin/console assetic:dump

sed -i  's/${APP_ENV}/'"$APP_ENV"'/' /etc/nginx/sites-enabled/symfony
sed -i  's/${APP_PHP}/'"$APP_PHP"'/' /etc/nginx/sites-enabled/symfony

useradd www-data
usermod -u 1000 www-data
chown -R www-data:www-data /var/www/symfony/var/cache /var/www/symfony/var/logs
chmod -R 777 /var/www/symfony/var/cache /var/www/symfony/var/logs

exec "$@"