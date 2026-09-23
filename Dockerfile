FROM php:8.2-apache
COPY frontend/ /var/www/html/
COPY conexion.php /var/www/
EXPOSE 80
