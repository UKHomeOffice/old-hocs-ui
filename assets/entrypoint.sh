#!/bin/bash

sed -i  's/${APP_ENV}/'"$APP_ENV"'/' /etc/nginx/sites-enabled/symfony
sed -i  's/${APP_PHP}/'"$APP_PHP"'/' /etc/nginx/sites-enabled/symfony

curl https://curl.haxx.se/ca/cacert.pem > /etc/ssl/certs/cacert.pem
echo >> /etc/ssl/certs/cacert.pem
cat /data/hocs-ui-ca.pem >> /etc/ssl/certs/cacert.pem

exec "$@"