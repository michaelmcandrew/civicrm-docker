ENV CIVICRM_UF=WordPress

ARG CIVICRM_VERSION

RUN curl https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o /usr/local/bin/wp \
    && chmod +x /usr/local/bin/wp

USER civicrm

RUN wp core download

RUN mkdir /var/www/html/wp-content/uploads

RUN cd /var/www/html/wp-content/plugins \
    && curl -L https://download.civicrm.org/civicrm-{{ civi }}-wordpress.zip > civicrm-wordpress.zip \
    && unzip civicrm-wordpress.zip \
    && rm civicrm-wordpress.zip

RUN cd /var/www/html/wp-content/plugins \
    && curl -L https://download.civicrm.org/civicrm-{{ civi }}-l10n.tar.gz > civicrm-l10n.tar.gz \
    && tar xzf civicrm-l10n.tar.gz \
    && rm civicrm-l10n.tar.gz

USER root

COPY ./civicrm-docker-entrypoint ./civicrm-docker-init ./civicrm-docker-dump ./civicrm-docker-install /usr/local/bin/

COPY --chown=civicrm:civicrm ./wp-config.php /usr/local/etc/civicrm

COPY --chown=civicrm:civicrm ./civicrm.settings.php /usr/local/etc/civicrm

COPY --chown=civicrm:civicrm ./.htaccess /usr/local/etc/civicrm
