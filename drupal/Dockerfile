FROM michaelmcandrew/civicrm-base 

ENV CIVICRM_UF=Drupal

ARG CIVICRM_VERSION

USER civicrm

ENV PATH="/home/civicrm/.composer/vendor/bin:${PATH}"

RUN cgr drush/drush:~8

RUN drush dl drupal-7 --destination=/var/www --drupal-project-rename=html -y

COPY --chown=civicrm:civicrm ./settings.php /var/www/html/sites/default

COPY --chown=civicrm:civicrm ./civicrm.settings.php /var/www/html/sites/default

RUN mkdir /var/www/html/sites/default/files

RUN cd /var/www/html/sites/all/modules \
  && curl -L https://download.civicrm.org/civicrm-$CIVICRM_VERSION-drupal.tar.gz > civicrm-drupal.tar.gz \
  && tar xzf civicrm-drupal.tar.gz \
  && rm civicrm-drupal.tar.gz

RUN cd /var/www/html/sites/all/modules \
  && curl -L https://download.civicrm.org/civicrm-$CIVICRM_VERSION-l10n.tar.gz > civicrm-l10n.tar.gz \
  && tar xzf civicrm-l10n.tar.gz \
  && rm civicrm-l10n.tar.gz

USER root

COPY ./init ./dump /usr/local/bin/
