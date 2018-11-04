FROM civicrm-base

RUN cgr drush/drush:8

RUN drush dl drupal-7 --destination=/var/www --drupal-project-rename=html -y

USER root