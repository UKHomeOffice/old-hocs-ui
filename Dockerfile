# See https://github.com/docker-library/php/blob/master/7.1/fpm/Dockerfile
FROM ubuntu:14.04

RUN apt-get update && apt-get install -y zlib1g-dev libicu-dev g++ openssl git unzip curl \
                                         nginx \
	                                     php5-fpm php5-cli php5-intl php5-mcrypt php5-apcu php5-gd php5-curl

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD assets/php.ini /usr/local/etc/php/

RUN rm /etc/nginx/sites-enabled/default
ADD assets/symfony.conf /etc/nginx/sites-available/
RUN ln -s /etc/nginx/sites-available/symfony.conf /etc/nginx/sites-enabled/symfony

ADD assets/nginx.conf /etc/nginx/

# install xdebug
#RUN pecl install xdebug
#RUN docker-php-ext-enable xdebug
#RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.remote_connect_back=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

ADD frontend /var/www/symfony

WORKDIR /var/www/symfony

RUN composer install --no-plugins --no-scripts

RUN usermod -u 1000 www-data
RUN chown -R www-data:www-data /var/www/symfony/var/cache /var/www/symfony/var/logs
RUN chmod -R 777 /var/www/symfony/var/cache /var/www/symfony/var/logs



CMD php5-fpm && nginx

EXPOSE 80