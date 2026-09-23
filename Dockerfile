FROM php:8.2-apache
COPY frontend/ /var/www/html/
COPY conexion.php /var/www/html/
RUN sed -i 's/DirectoryIndex index.php index.html/DirectoryIndex inicio.php index.php index.html/g' /etc/apache2/mods-enabled/dir.conf
EXPOSE 80
