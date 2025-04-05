# Use a imagem base do PHP com Apache
FROM php:8.1-apache

# Instalar dependências
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd mbstring pdo pdo_mysql xml \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Configurar o Xdebug e outras configurações do PHP
RUN echo "zend_extension=xdebug.so" >> /usr/local/etc/php/conf.d/docker-php.ini \
    && echo "xdebug.mode=develop,coverage,debug,profile" >> /usr/local/etc/php/conf.d/docker-php.ini \
    && echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php.ini \
    && echo "xdebug.log=/dev/stdout" >> /usr/local/etc/php/conf.d/docker-php.ini \
    && echo "xdebug.log_level=3" >> /usr/local/etc/php/conf.d/docker-php.ini \
    && echo "xdebug.client_port=9003" >> /usr/local/etc/php/conf.d/docker-php.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php.ini \
    && echo "xdebug.discover_client_host=true" >> /usr/local/etc/php/conf.d/docker-php.ini

# Copiar o código da aplicação
COPY . /var/www/html

# Habilitar o módulo de reescrita do Apache
RUN a2enmod rewrite

# Configurar o DocumentRoot para a pasta public
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Iniciar o Apache
CMD ["apache2-foreground"]