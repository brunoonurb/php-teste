FROM php:7.3-apache

# Enable apache rewrite
COPY ./apache/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/app

RUN apt update

RUN apt install -y git libzip-dev zip
RUN apt install -y imagemagick 
RUN apt update && apt install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev  zip  libzip-dev\
        vim
        
RUN docker-php-ext-install gd mysqli zip mysqli pdo pdo_mysql && \
    docker-php-ext-enable gd mysqli pdo_mysql

# RUN a2enmod rewrite

# RUN rm -rf /var/www/html && \
#     ln -s /app /var/www/html

RUN pecl install xdebug && docker-php-ext-enable xdebug

RUN echo "xdebug.mode=off" | tee -a /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini > /dev/null && \
    echo "xdebug.start_with_request=yes" | tee -a /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini > /dev/null && \
    echo "xdebug.client_host=host.docker.internal" | tee -a /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini > /dev/null


COPY ./ /var/www/app
# RUN mkdir vendor


RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --no-scripts

# WORKDIR /var/www/app
RUN composer update 
RUN composer dumpautoload -o 

RUN rm -rf /var/lib/apt/lists/*
RUN chmod -R 777 /var/www/app
# RUN chmod 777 /var/www/app/vendor/mpdf/mpdf/tmp
RUN service apache2 restart

EXPOSE 8081