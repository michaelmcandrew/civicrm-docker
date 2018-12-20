# See documentation at https://***

# Start with a recommended version of PHP.

FROM php:7.1-apache

# libc-client-dev and libkrb5-dev required for the imap php extension
# libicu-dev required for the intl php extension
# libmagickwand-dev required for the imagick php extension
# libmcrypt-dev required for the mcrypt php extension
# libpng-dev required for the gd php extension
# libxml2-dev required for the soap php extension
# iproute2 is required to get the host ip at runtime for XDEBUG_REMOTE_HOST environment variable
# msmtp-mta allows us to globally redirect email via SMTP
# unzip is required for composer

RUN apt-get update \
  && apt-get install -y --no-install-recommends \
  iproute2 \
  less \
  libc-client-dev \
  libicu-dev \
  libkrb5-dev \
  libmagickwand-dev \
  libmcrypt-dev \
  libpng-dev \
  libxml2-dev \
  msmtp-mta \
  mysql-client \
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

# Install imagick and xdebug PECL extensions

RUN pecl install imagick \
  && pecl install xdebug

# Install composer using the method described at https://getcomposer.org/download/ (TODO consider whether this is really necessary - we could remove from this image)

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
  && php -r "if (hash_file('SHA384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" \
  && php composer-setup.php --install-dir=/usr/local/bin --filename=composer\
  && php -r "unlink('composer-setup.php');"

# Create civicrm user

RUN useradd civicrm --home-dir /home/civicrm --create-home 

RUN chown -R civicrm:civicrm /var/www 

ENV APACHE_RUN_USER=civicrm

USER civicrm

RUN composer global require consolidation/cgr

USER root

# Download cv

RUN curl -LsS https://download.civicrm.org/cv/cv.phar -o /usr/local/bin/cv \
  && chmod +x /usr/local/bin/cv

# Download civix

RUN curl -LsS https://download.civicrm.org/civix/civix.phar -o /usr/local/bin/civix \
  && chmod +x /usr/local/bin/civix

# Configure apache

RUN a2enmod rewrite headers

COPY ./apache.conf /etc/apache2/conf-available/civicrm.conf

RUN a2enconf civicrm

# Configure php

RUN cp /usr/local/etc/php/php.ini-production /usr/local/etc/php/php.ini

COPY ./php.ini /usr/local/etc/php/conf.d/civicrm.ini

COPY ./load /usr/local/bin

COPY ./civicrm_dump.php /usr/local/src/civicrm_dump.php

COPY ./docker-civicrm-entrypoint /usr/local/bin

ENTRYPOINT [ "docker-civicrm-entrypoint" ]

CMD ["apache2-foreground"]

