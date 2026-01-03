# Changement de 8.2 vers 8.3
FROM php:8.3-apache

# 1. Installation des dépendances système et des extensions PHP requises
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    libpq-dev \
    libpng-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl zip pdo pdo_pgsql gd bcmath

# 2. Activation du module rewrite d'Apache pour Laravel
RUN a2enmod rewrite

# 3. Configuration du dossier racine vers /public pour Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Définir le répertoire de travail et copier le projet
WORKDIR /var/www/html
COPY . /var/www/html

# 5. Installation de Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# 6. Installation des dépendances PHP (avec ignore-platform-reqs par sécurité)
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# 7. Droits d'accès cruciaux pour le stockage et le cache Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]