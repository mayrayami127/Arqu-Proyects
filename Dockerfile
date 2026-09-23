FROM php:8.2-apache
COPY frontend/ /var/www/html/
COPY conexion.php /var/www/html/
RUN echo "DirectoryIndex inicio.php index.php index.html" > /etc/apache2/conf-available/directory-index.conf && a2enconf directory-index
EXPOSE 80
