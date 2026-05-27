FROM php:8.2-apache

# Fix "More than one MPM loaded" error: edit apache2.conf directly so only
# mpm_prefork is loaded before any a2dismod/a2enmod commands run.
RUN sed -i 's/^LoadModule mpm_event_module/# LoadModule mpm_event_module/' /etc/apache2/apache2.conf && \
    sed -i 's/^LoadModule mpm_worker_module/# LoadModule mpm_worker_module/' /etc/apache2/apache2.conf && \
    sed -i 's/^# LoadModule mpm_prefork_module/LoadModule mpm_prefork_module/' /etc/apache2/apache2.conf

# Ensure only mpm_prefork is active (mod_php is incompatible with mpm_event/mpm_worker)
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load /etc/apache2/mods-enabled/mpm_*.conf \
    && a2enmod mpm_prefork \
    && apache2ctl -t

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy all project files
COPY . /var/www/html/

# Set Apache DocumentRoot to /public
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

# Allow .htaccess overrides
RUN echo '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>' >> /etc/apache2/sites-available/000-default.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

EXPOSE 80
