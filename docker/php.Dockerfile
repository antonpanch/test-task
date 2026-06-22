ARG PHP_VERSION=8.5

FROM php:${PHP_VERSION}-fpm-alpine AS php-dependencies
RUN docker-php-ext-install mysqli && \
    docker-php-ext-install pdo_mysql

FROM php:${PHP_VERSION}-fpm-alpine AS application
ARG USER_ID=1000
ARG USER_NAME=user
ARG USER_PASSWORD=userpassword
ARG APPLICATION_ENV=development
ARG PHP_INI_DIR=/usr/local/etc/php
ARG EXTENSION_DIR=/usr/local/lib/php/extensions/no-debug-non-zts-20250925
WORKDIR /application
COPY ./application /application
COPY --from=php-dependencies ${PHP_INI_DIR}/conf.d/docker-php-ext-mysqli.ini ${PHP_INI_DIR}/conf.d/docker-php-ext-mysqli.ini
COPY --from=php-dependencies ${PHP_INI_DIR}/conf.d/docker-php-ext-pdo_mysql.ini ${PHP_INI_DIR}/conf.d/docker-php-ext-pdo_mysql.ini
COPY --from=php-dependencies ${EXTENSION_DIR}/mysqli.so ${EXTENSION_DIR}/mysqli.so
COPY --from=php-dependencies ${EXTENSION_DIR}/pdo_mysql.so ${EXTENSION_DIR}/pdo_mysql.so
RUN echo -e "${USER_PASSWORD}\n${USER_PASSWORD}" | adduser -u ${USER_ID} ${USER_NAME} && \
    cp "${PHP_INI_DIR}/php.ini-${APPLICATION_ENV}" "${PHP_INI_DIR}/php.ini"
USER $USER_NAME
CMD ["php", "-S", "0.0.0.0:8800", "-t", "/application/public"]
