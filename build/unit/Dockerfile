# Stage 1. Build deps
FROM composer:2.5.4 as builder

WORKDIR /deps

COPY composer.json .
COPY composer.lock .

RUN composer install --optimize-autoloader --prefer-dist --no-ansi --no-interaction --no-progress --no-scripts

# Stage 2. Server configuration
FROM nginx/unit:1.29.1-php8.1

ARG USER=backend
ARG GROUP=backend
ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

WORKDIR /app

RUN groupadd --gid 1000 $GROUP && \
    useradd --uid 1000 --gid $GROUP --shell /bin/bash --create-home $USER

RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pgsql pdo_pgsql opcache && \
    apt-get --purge -y remove gcc make && \
    apt-get -y autoremove && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY --chown=$USER:$GROUP . .
COPY --from=builder --chown=$USER:$GROUP /deps/vendor vendor

COPY build/unit/config.json /docker-entrypoint.d/config.json
COPY build/unit/php.ini /etc/php.ini

EXPOSE 8080
