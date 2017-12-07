FROM quay.io/ukhomeofficedigital/openjdk8

RUN rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm
RUN rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm
RUN yum update -y

RUN yum -y install php56w php56w-fpm php56w-opcache php56w-intl php56w-common php56w-pdo php56w-dom git nginx

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN curl https://curl.haxx.se/ca/cacert.pem > /etc/ssl/certs/cacert.pem

COPY assets/symfony.conf /etc/nginx/sites-available/
COPY assets/php.ini /etc/php.ini
COPY assets/nginx.conf /etc/nginx/

RUN mkdir /etc/nginx/sites-enabled
RUN ln -s /etc/nginx/sites-available/symfony.conf /etc/nginx/sites-enabled/symfony

COPY frontend /var/www/symfony

WORKDIR /var/www/symfony

COPY assets/entrypoint.sh entrypoint.sh
RUN chmod +x  entrypoint.sh
ENTRYPOINT ["./entrypoint.sh"]

CMD php-fpm && nginx

EXPOSE 80