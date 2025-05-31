FROM php:8.1-apache

ARG WWWUSER
ARG WWWUSER_UID
ARG APP_ENV

RUN apt-get update \
   && apt-get install -y libonig-dev --no-install-recommends \
   && rm -rf /var/lib/apt/lists/*

# Instalar dependencias necesarias para PHP y Apache
RUN apt-get update && \
   apt-get install -y \
   libmcrypt-dev \
   libzip-dev \
   zlib1g-dev \
   libicu-dev \
   libpng-dev \
   libjpeg-dev \
   libfreetype6-dev \
   libssl-dev \
   openssl \
   zip \
   unzip \
   git \
   curl \
   default-mysql-client \
   && rm -rf /var/lib/apt/lists/*

# Instalar extensiones PHP necesarias para Lumen
RUN docker-php-ext-install \
   pdo \
   pdo_mysql \
   zip \
   intl \
   opcache \
   bcmath \
   gd \
   mbstring

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Habilitar mod_rewrite de Apache
RUN apt-get update && \
   apt-get install -y apache2 apache2-utils nano && \
   apt-get clean && \
   rm -rf /var/lib/apt/lists/*
RUN a2enmod rewrite authz_core mpm_prefork

# Crear directorio para la aplicación Lumen
WORKDIR /var/www/html/app
COPY . .

# Ejecutar Composer
RUN if [ "$APP_ENV" = "production" ] ; then cd /var/www/html/app && composer install --no-interaction --optimize-autoloader --no-dev ; else cd /var/www/html/app && composer install --no-interaction ; fi

RUN groupadd -r -g $WWWUSER_UID $WWWUSER 
RUN useradd -r -u $WWWUSER_UID -g $WWWUSER -M -s /usr/sbin/nologin $WWWUSER
# RUN chown -R $WWWUSER:$WWWUSER /var/www/html/app

ENV APACHE_RUN_USER $WWWUSER
ENV APACHE_RUN_GROUP $WWWUSER

# Iniciar Apache
CMD ["/usr/sbin/apache2ctl", "-D", "FOREGROUND"]