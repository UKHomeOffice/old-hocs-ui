FROM quay.io/ukhomeofficedigital/hocs-pit

ENV USER ui
ENV USER_ID 1000
ENV GROUP ui

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
