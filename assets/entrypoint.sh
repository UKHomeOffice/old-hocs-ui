#!/bin/bash

sed -i  's/${APP_ENV}/'"$APP_ENV"'/' /etc/nginx/sites-enabled/symfony
sed -i  's/${APP_PHP}/'"$APP_PHP"'/' /etc/nginx/sites-enabled/symfony

composer install
bin/console assets:install --symlink
bin/console assetic:dump --no-debug

exec "$@"