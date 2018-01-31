FROM quay.io/ukhomeofficedigital/openjdk8

RUN rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm \
 && rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm  \
 && yum update -y \
 && yum -y install php56w php56w-fpm php56w-opcache php56w-intl php56w-common php56w-pear php56w-pdo php56w-dom php56w-pecl-redis git nginx \
 && curl https://curl.haxx.se/ca/cacert.pem > /etc/ssl/certs/cacert.pem \
 && echo >> /etc/ssl/certs/cacert.pem \
 && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
 && yum -y install ruby ruby-rdoc ruby-devel gcc make automake autoconf \
 && gem update --system && gem install --no-rdoc --no-ri compass --version 0.12.7 \
 && ln -s /usr/local/bin/compass /usr/bin/compass

COPY assets/symfony.conf /etc/nginx/sites-available/
COPY assets/php.ini /etc/php.ini
COPY assets/nginx.conf /etc/nginx/
COPY assets/www.conf /etc/php-fpm.d/www.conf

COPY assets/clammit /usr/local/bin/clammit
COPY assets/clammit.cfg /var/www/symfony/clammit.cfg

RUN mkdir /etc/nginx/sites-enabled \
 && ln -s /etc/nginx/sites-available/symfony.conf /etc/nginx/sites-enabled/symfony \
 && mkdir /data \
 && touch /data/hocs-ui-ca.pem

COPY frontend /var/www/symfony

WORKDIR /var/www/symfony

COPY assets/entrypoint.sh entrypoint.sh
RUN chmod +x  entrypoint.sh
ENTRYPOINT ["./entrypoint.sh"]

CMD cat /data/hocs-ui-ca.pem >> /etc/ssl/certs/cacert.pem & clammit -config=/var/www/symfony/clammit.cfg & php-fpm -R -D && nginx

EXPOSE 8080