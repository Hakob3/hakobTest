FROM php:8.1-fpm

RUN apt update
RUN apt install -y git

RUN apt-get update && apt-get install -y \
        apt-utils \
        libpng-dev \
        libfreetype6-dev \
        libicu-dev \
        libpq-dev \
        libjpeg-dev \
        libzip-dev \
    && docker-php-ext-install -j$(nproc) mysqli \
    && docker-php-ext-install -j$(nproc) pdo_mysql \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install -j$(nproc) intl \
    && docker-php-ext-install -j$(nproc) zip \
    && docker-php-ext-install -j$(nproc) pgsql \
    && docker-php-ext-install -j$(nproc) pdo_pgsql \
    && docker-php-ext-install -j$(nproc) exif \
    && docker-php-ext-configure gd --with-freetype --with-jpeg

#Установка композера
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

#Установка ноды
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash -
RUN apt-get install -y \
  --no-install-recommends nodejs \
  && rm -rf /var/lib/apt/lists/*

#Настройка виртуальной памяти для работы поиска
RUN echo "vm.max_map_count = 262144" > /etc/sysctl.conf

WORKDIR /var/www

