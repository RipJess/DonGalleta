# Usar la imagen oficial de PHP con Apache
FROM php:8.2-apache

# Instalar dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libonig-dev \
    unzip \
    && docker-php-ext-install pdo_mysql

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Instalar FPDF con Composer
RUN composer require setasign/fpdf

# Copiar archivos del proyecto al contenedor
WORKDIR /var/www/html
COPY . .

# Copiar configuración personalizada de Apache
COPY www/config/apache-config.conf /etc/apache2/sites-available/000-default.conf

# Habilitar mod_rewrite para URLs amigables
RUN a2enmod rewrite

# Configurar permisos correctos
RUN chown -R www-data:www-data /var/www/html

# Exponer el puerto 80
EXPOSE 80

# Comando por defecto al iniciar el contenedor
CMD ["apache2-foreground"]
