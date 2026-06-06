FROM php:8.2-apache

# PDO SQLite + mod_rewrite
RUN docker-php-ext-install pdo pdo_sqlite && a2enmod rewrite

# AllowOverride All — potrebné pre .htaccess
RUN sed -i 's|AllowOverride None|AllowOverride All|g' /etc/apache2/apache2.conf

# Skopíruj appku
COPY todo/ /var/www/html/todo/

# Vytvor data/ priečinok a nastav oprávnenia
RUN mkdir -p /var/www/html/todo/data && \
    chown -R www-data:www-data /var/www/html/todo && \
    chmod 777 /var/www/html/todo/data

# Startup skript — Railway injektuje $PORT
COPY apache-start.sh /usr/local/bin/apache-start.sh
RUN sed -i 's/\r//' /usr/local/bin/apache-start.sh && chmod +x /usr/local/bin/apache-start.sh

EXPOSE 80
CMD ["/usr/local/bin/apache-start.sh"]
