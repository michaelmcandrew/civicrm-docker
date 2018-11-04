FROM civicrm-base 

USER civicrm

ENV PATH="/home/civicrm/.composer/vendor/bin:${PATH}"

RUN composer global require consolidation/cgr

RUN cgr drush/drush:8

RUN drush dl drupal-7 --destination=/var/www --drupal-project-rename=html -y

USER root