FROM php:8.2-apache

# Enable Apache rewrite (for pretty URLs)
RUN a2enmod rewrite

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Change Apache DocumentRoot to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Update Apache config files to use the new DocumentRoot
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
