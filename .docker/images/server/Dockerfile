ARG BASE_IMAGE=php:8.3-fpm

FROM ${BASE_IMAGE} AS base

ARG GID=1000
ARG UID=1000

ENV APP_HOME=/srv/app

USER root
RUN apt update && apt install -y \
  vim \
  sudo \
  libicu-dev \
  libpq-dev \
  zip \
  librabbitmq-dev


RUN docker-php-ext-install ctype \
  iconv

RUN docker-php-ext-configure intl \
  && docker-php-ext-install intl

RUN docker-php-ext-configure opcache --enable-opcache \
  && docker-php-ext-install opcache

#RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
#  && docker-php-ext-install pdo pdo_pgsql pgsql

#RUN pecl install amqp \
#  && docker-php-ext-enable amqp
# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

FROM base AS dev_test

# Enable XDebug
RUN pecl install xdebug-3.3.1
RUN docker-php-ext-enable xdebug

FROM dev_test AS dev

#Update ini
RUN mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

# Hack for dev mode (docker user must have the same id with docker internal user)
RUN groupadd serveruser \
         --gid $GID
RUN useradd serveruser \
         --create-home \
         --gid $GID \
         --shell /bin/bash \
         --uid $UID \
  && usermod -a -G sudo serveruser \
  && echo 'ALL ALL = (ALL) NOPASSWD: ALL' >> /etc/sudoers \
  && echo 'serveruser:serveruser' | chpasswd

RUN mkdir -p /srv/app
RUN chown -R serveruser:serveruser /srv/app

USER serveruser

WORKDIR /srv/app/


# Add scripts to the global path
ENV PATH="${PATH}:/srv/app/vendor/bin"

FROM dev AS dev_worker

CMD [ "bin/runWorkes.sh" ]
