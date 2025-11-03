FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
      git bash curl unzip icu icu-dev \
      libzip libzip-dev \
      oniguruma oniguruma-dev \
      libpng libpng-dev \
      libjpeg-turbo libjpeg-turbo-dev \
      freetype freetype-dev \
      postgresql-dev \
      $PHPIZE_DEPS \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j"$(nproc)" \
      pdo_mysql pdo_pgsql mbstring exif pcntl bcmath opcache intl zip gd \
  && pecl install redis \
  && docker-php-ext-enable redis \
  && apk del --no-cache $PHPIZE_DEPS

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_HOME=/root/.composer
ENV PATH="${COMPOSER_HOME}/vendor/bin:${PATH}"

WORKDIR /var/www