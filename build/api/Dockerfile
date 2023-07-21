ARG COMPOSER_VERSION=2.5.4
ARG UNIT_VERSION=1.30.0-php8.2

FROM composer:${COMPOSER_VERSION} as composer_builder

WORKDIR /deps

COPY composer.json .
COPY composer.lock .

RUN composer install --optimize-autoloader --prefer-dist --no-ansi --no-interaction --no-progress --no-scripts

FROM unit:${UNIT_VERSION} as api

ARG USER=backend
ARG GROUP=backend

RUN groupadd --gid 1000 ${GROUP} && \
    useradd --uid 1000 --gid ${GROUP} --shell /bin/bash --create-home ${USER}

ENV TZ=UTC

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

WORKDIR /app

COPY --chown=${USER}:${GROUP} . .
COPY --from=composer_builder --chown=${USER}:${GROUP} /deps/vendor vendor

COPY build/api/config.json /docker-entrypoint.d/config.json
COPY build/api/php.ini /etc/php.ini

RUN apt-get update && \
    apt-get install -y libpq-dev && \
    pecl install redis && \
    docker-php-ext-enable redis && \
    docker-php-ext-install pdo pgsql pdo_pgsql opcache && \
    apt-get --purge -y remove gcc make && \
    apt-get -y autoremove && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN php artisan route:cache

EXPOSE 80
