# See documentation at https://***

# Start with a recommended version of PHP.

FROM php:7.1-apache

# The following packages are required for PHP extensions that are
# requirements for CiviCRM.
#
# | apt package       | PHP extension |
# | :---------------- | :------------ |
# | libc-client-dev   | imap          |
# | libicu-dev        | intl          |
# | libkrb5-dev       | imap          |
# | libmagickwand-dev | imagick       |
# | libmcrypt-dev     | mcrypt        |
# | libpng-dev        | gd            |
# | libxml2-dev       | soap          |
#
# unzip is required for composer

RUN apt-get update \
  && apt-get install -y --no-install-recommends \
  libc-client-dev \
  libicu-dev \
  libkrb5-dev \
  libmagickwand-dev \
  libmcrypt-dev \
  libpng-dev \
  libxml2-dev \
  unzip \
  && rm -r /var/lib/apt/lists/*

# CiviCRM requires the following PHP extensions (some of which are already
# enabled in the Docker image:
#
# * curl (already enabled)
# * gd
# * gettext
# * imap
# * intl
# * json (already enabled)
# * mbstring (already enabled)w
# * mcrypt
# * mysqli
# * openssl (already enabled)
# * pdo_mysql
# * phar (already enabled)
# * posix (already enabled)
# * soap
# * zip

# Install php extensions

RUN docker-php-ext-install bcmath \
  && docker-php-ext-install gd \
  && docker-php-ext-install gettext \
  && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
  && docker-php-ext-install imap \
  && docker-php-ext-install intl \
  && docker-php-ext-install mysqli \
  && docker-php-ext-install pdo_mysql \
  && docker-php-ext-install soap \
  && docker-php-ext-install zip

# Install imagick PECL extension

RUN pecl install imagick

# Enable apache modules

RUN a2enmod rewrite headers

# Copy and enable CiviCRM apache conf

COPY ./civicrm.conf /etc/apache2/conf-available/civicrm.conf

RUN a2enconf civicrm

# Install composer using the method described at https://getcomposer.org/download/

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php -r "if (hash_file('SHA384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
  && php composer-setup.php --install-dir=/usr/local/bin --filename=composer\
  && php -r "unlink('composer-setup.php');"

# Copy utility scripts

COPY ./utils/download_civicrm.sh /usr/local/bin

# Create civicrm user

RUN useradd civicrm --home-dir /home/civicrm --create-home 

RUN chown -R civicrm:civicrm /var/www 

# Enter userspace

USER civicrm

ENV PATH="/home/civicrm/.composer/vendor/bin:${PATH}"

ENV APACHE_RUN_USER=civicrm

# see https://github.com/consolidation/cgr
RUN composer global require consolidation/cgr

RUN cgr civicrm/cv

