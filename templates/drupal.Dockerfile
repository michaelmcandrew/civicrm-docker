ENV CIVICRM_UF=Drupal

USER civicrm

ENV PATH="/home/civicrm/.composer/vendor/bin:${PATH}"

RUN cgr drush/drush:~8

RUN drush dl drupal-7 --destination=/var/www --drupal-project-rename=html -y

RUN mkdir /var/www/html/sites/default/files

RUN cd /var/www/html/sites/all/modules \
    && curl -L https://download.civicrm.org/civicrm-{{ civi }}-drupal.tar.gz > civicrm-drupal.tar.gz \
    && tar xzf civicrm-drupal.tar.gz \
    && rm civicrm-drupal.tar.gz

RUN cd /var/www/html/sites/all/modules \
    && curl -L https://download.civicrm.org/civicrm-{{ civi }}-l10n.tar.gz > civicrm-l10n.tar.gz \
    && tar xzf civicrm-l10n.tar.gz \
    && rm civicrm-l10n.tar.gz

USER root

COPY ./civicrm-docker-entrypoint ./civicrm-docker-init ./civicrm-docker-dump ./civicrm-docker-install /usr/local/bin/

COPY --chown=civicrm:civicrm ./settings.php /usr/local/etc/civicrm

COPY --chown=civicrm:civicrm ./civicrm.settings.php /usr/local/etc/civicrm

