FROM civicrm-base 

ENV CIVICRM_UF="Drupal"

USER civicrm

ENV PATH="/home/civicrm/.composer/vendor/bin:${PATH}"

RUN cgr drush/drush:~8

RUN drush dl drupal-7 --destination=/var/www --drupal-project-rename=html -y

COPY --chown=civicrm:civicrm ./settings.php /var/www/html/sites/default

COPY --chown=civicrm:civicrm ./civicrm.settings.php /var/www/html/sites/default

RUN mkdir /var/www/html/sites/default/files

USER root

COPY ./init ./dump /usr/local/bin/
