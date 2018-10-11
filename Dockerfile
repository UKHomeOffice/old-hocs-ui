FROM quay.io/ukhomeofficedigital/openjdk8

ENV USER ui
ENV USER_ID 1000
ENV GROUP ui

RUN yum -y --setopt=tsflags=nodocs update && yum -y --setopt=tsflags=nodocs install httpd && yum clean all

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

RUN groupadd -r ${GROUP} && \
    useradd -r -u ${USER_ID} -g ${GROUP} ${USER} -d /var/www/symfony && \
    chown -R ${USER}:${GROUP} /var/www/symfony

RUN useradd www-data && \
    usermod -u 1001 www-data && \
    chown -R www-data:www-data /var/www/symfony/var/cache /var/www/symfony/var/logs && \
    chmod -R 777 /var/www/symfony/var/cache /var/www/symfony/var/logs && \
    chmod -R 777 /var/lib/nginx/ && \
    chmod -R 777 /var/run/php-fpm/ && \
    chmod -R 777 /etc/ssl/certs/ && \
    chmod -R 777 /var/log/ && \
    chmod -R 777 /run/ && \
    chmod -R 777 /etc/nginx/
    
ENTRYPOINT ["./entrypoint.sh"]

EXPOSE 8080

USER ${USER_ID}

CMD cat /data/hocs-ui-ca.pem >> /etc/ssl/certs/cacert.pem && php-fpm && nginx
