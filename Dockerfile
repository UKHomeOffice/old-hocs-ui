FROM ubuntu:14.04

RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++ openssl git unzip curl \
                                         nginx \
	                                     php5-fpm php5-cli php5-intl php5-mcrypt php5-apcu php5-gd php5-curl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY assets/symfony.conf /etc/nginx/sites-available/
COPY assets/php.ini /usr/local/etc/php/
COPY assets/nginx.conf /etc/nginx/

RUN rm /etc/nginx/sites-enabled/default
RUN ln -s /etc/nginx/sites-available/symfony.conf /etc/nginx/sites-enabled/symfony

COPY frontend /var/www/symfony

WORKDIR /var/www/symfony

COPY assets/entrypoint.sh entrypoint.sh
RUN chmod +x  entrypoint.sh
ENTRYPOINT ["./entrypoint.sh"]

CMD php5-fpm && nginx

EXPOSE 8080