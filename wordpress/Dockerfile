FROM civicrm-base

ENV CIVICRM_UF="WordPress"

RUN curl https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o /usr/local/bin/wp \
  && chmod +x /usr/local/bin/wp

USER civicrm

RUN wp core download

COPY ./wp-config.php /var/www/html

COPY ./civicrm.settings.php /var/www/html

USER root
