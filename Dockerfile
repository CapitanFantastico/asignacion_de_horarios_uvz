FROM php:latest-apache

# Instalar extensiones requeridas
RUN docker-php-ext-install mysqli

# Copiar el código de la aplicación al contenedor
COPY . /var/www/html/

# Establecer permisos
RUN chown -R www-data:www-data /var/www/html

# Configuración del servidor Apache
EXPOSE 80
CMD ["apache2-foreground"]
