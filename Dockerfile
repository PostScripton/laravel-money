FROM composer:2.2.6 as composer
FROM php:8.1-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache git \
    openssh-client \
    unzip

ENV PS1 '\[\033[0;32m\][laravel-money] \[\033[1;35m\]\u@docker\[\033[0m\]:\[\033[0;34m\]\w\[\033[0m\]# '

WORKDIR /src

COPY composer.json ./

RUN composer install && \
    composer dump-autoload --optimize
