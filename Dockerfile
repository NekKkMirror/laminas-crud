FROM php:8.1-cli AS base

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libpq-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_pgsql \
        mbstring \
        xml \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN useradd -m -u 1000 laminas
WORKDIR /app

RUN mkdir /app/data /app/module && \
    chown -R laminas:laminas /app

USER laminas

FROM base AS dev

COPY --chown=laminas:laminas . /app

RUN composer install --no-scripts --no-autoloader && composer dump-autoload

RUN chmod -R 775 /app/data /app/module

from dev AS test