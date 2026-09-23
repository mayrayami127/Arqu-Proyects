FROM php:8.2-apache
RUN echo "DirectoryIndex inicio.php index.php index.html" >> /etc/apache2/apache2.conf
COPY frontend/ /var/www/html/
COPY conexion.php /var/www/html/
EXPOSE 80
