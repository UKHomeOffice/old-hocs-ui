#!/bin/bash

composer install

sed -i  's/${APP_ENV}/'"$APP_ENV"'/' /etc/nginx/sites-enabled/symfony
sed -i  's/${APP_PHP}/'"$APP_PHP"'/' /etc/nginx/sites-enabled/symfony

exec "$@"