ENV CIVICRM_UF=Backdrop

USER civicrm

ENV PATH="/home/civicrm/.composer/vendor/bin:${PATH}"

RUN cgr drush/drush:~8

RUN mkdir -p /home/civicrm/.drush/commands \
    && cd /home/civicrm/.drush/commands \
    && curl -L https://github.com/backdrop-contrib/drush/releases/download/0.1.0/drush.zip > drush.zip \
    && unzip drush.zip -d backdrop \
    && rm drush.zip

RUN cd /var/www \
    && rmdir html \
    && drush dlb backdrop --path="html"

RUN cd /var/www/html/modules \
    && curl -L https://download.civicrm.org/civicrm-{{ civi }}-backdrop.tar.gz > civicrm-backdrop.tar.gz \
    && tar xzf civicrm-backdrop.tar.gz \
    && rm civicrm-backdrop.tar.gz

RUN cd /var/www/html/modules \
    && curl -L https://download.civicrm.org/civicrm-{{ civi }}-l10n.tar.gz > civicrm-l10n.tar.gz \
    && tar xzf civicrm-l10n.tar.gz \
    && rm civicrm-l10n.tar.gz

USER root

COPY ./civicrm-docker-init ./civicrm-docker-dump /usr/local/bin/

COPY --chown=civicrm:civicrm ./settings.php /usr/local/etc/civicrm

COPY --chown=civicrm:civicrm ./civicrm.settings.php /usr/local/etc/civicrm

