ENV CIVICRM_UF=WordPress

ARG CIVICRM_VERSION

RUN curl https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o /usr/local/bin/wp \
    && chmod +x /usr/local/bin/wp

USER civicrm

RUN mkdir /home/civicrm/wp && wp core download --path=/home/civicrm/wp

RUN mkdir /home/civicrm/wp/wp-content/uploads

RUN cd /home/civicrm/wp/wp-content/plugins \
    && curl -L https://download.civicrm.org/civicrm-{{ civi }}-wordpress.zip > civicrm-wordpress.zip \
    && unzip civicrm-wordpress.zip \
    && rm civicrm-wordpress.zip

RUN cd /home/civicrm/wp/wp-content/plugins \
    && curl -L https://download.civicrm.org/civicrm-{{ civi }}-l10n.tar.gz > civicrm-l10n.tar.gz \
    && tar xzf civicrm-l10n.tar.gz \
    && rm civicrm-l10n.tar.gz

USER root

COPY ./civicrm-docker-entrypoint ./civicrm-docker-init ./civicrm-docker-dump ./civicrm-docker-install /usr/local/bin/

COPY --chown=civicrm:civicrm ./wp-config.php /usr/local/etc/civicrm

COPY --chown=civicrm:civicrm ./civicrm.settings.php /usr/local/etc/civicrm

COPY --chown=civicrm:civicrm ./.htaccess /usr/local/etc/civicrm

RUN id civicrm
