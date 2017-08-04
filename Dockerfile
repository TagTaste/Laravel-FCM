FROM php:7.1-fpm
RUN apt-get update -y && apt-get install -y openssl
RUN docker-php-ext-install pdo pdo_mysql mbstring
WORKDIR /code
COPY ./ /code
VOLUME /code