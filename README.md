# CiviCRM on docker

An opinionated repository for production hosting of CiviCRM on Docker (your opinions on how it could be improved are very welcome).

All images are based php-apache.

An appropriate version of php is selected for you.

# Tags

The following tags are available:

<!---START_TAGS-->
* `5.13.1-drupal-php7.3` `5.13-drupal-php7.3` `5-drupal-php7.3` `5.13.1-php7.3` `5.13-php7.3` `5-php7.3` `drupal-php7.3` `php7.3` [(5/drupal/7.3)](5/drupal/7.3)
* `5.13.1-drupal-php7.2` `5.13-drupal-php7.2` `5-drupal-php7.2` `5.13.1-drupal` `5.13-drupal` `5-drupal` `5.13.1-php7.2` `5.13-php7.2` `5-php7.2` `5.13.1` `5.13` `5` `drupal-php7.2` `drupal` `php7.2` `latest` [(5/drupal/7.2)](5/drupal/7.2)
* `5.13.1-drupal-php7.1` `5.13-drupal-php7.1` `5-drupal-php7.1` `5.13.1-php7.1` `5.13-php7.1` `5-php7.1` `drupal-php7.1` `php7.1` [(5/drupal/7.1)](5/drupal/7.1)
* `5.13.1-drupal-php7.0` `5.13-drupal-php7.0` `5-drupal-php7.0` `5.13.1-php7.0` `5.13-php7.0` `5-php7.0` `drupal-php7.0` `php7.0` [(5/drupal/7.0)](5/drupal/7.0)
* `5.13.1-drupal-php5.6` `5.13-drupal-php5.6` `5-drupal-php5.6` `5.13.1-php5.6` `5.13-php5.6` `5-php5.6` `drupal-php5.6` `php5.6` [(5/drupal/5.6)](5/drupal/5.6)
* `5.13.1-wordpress-php7.3` `5.13-wordpress-php7.3` `5-wordpress-php7.3` `wordpress-php7.3` [(5/wordpress/7.3)](5/wordpress/7.3)
* `5.13.1-wordpress-php7.2` `5.13-wordpress-php7.2` `5-wordpress-php7.2` `5.13.1-wordpress` `5.13-wordpress` `5-wordpress` `wordpress-php7.2` `wordpress` [(5/wordpress/7.2)](5/wordpress/7.2)
* `5.13.1-wordpress-php7.1` `5.13-wordpress-php7.1` `5-wordpress-php7.1` `wordpress-php7.1` [(5/wordpress/7.1)](5/wordpress/7.1)
* `5.13.1-wordpress-php7.0` `5.13-wordpress-php7.0` `5-wordpress-php7.0` `wordpress-php7.0` [(5/wordpress/7.0)](5/wordpress/7.0)
* `5.13.1-wordpress-php5.6` `5.13-wordpress-php5.6` `5-wordpress-php5.6` `wordpress-php5.6` [(5/wordpress/5.6)](5/wordpress/5.6)
* `5.13.1-backdrop-php7.3` `5.13-backdrop-php7.3` `5-backdrop-php7.3` `backdrop-php7.3` [(5/backdrop/7.3)](5/backdrop/7.3)
* `5.13.1-backdrop-php7.2` `5.13-backdrop-php7.2` `5-backdrop-php7.2` `5.13.1-backdrop` `5.13-backdrop` `5-backdrop` `backdrop-php7.2` `backdrop` [(5/backdrop/7.2)](5/backdrop/7.2)
* `5.13.1-backdrop-php7.1` `5.13-backdrop-php7.1` `5-backdrop-php7.1` `backdrop-php7.1` [(5/backdrop/7.1)](5/backdrop/7.1)
* `5.13.1-backdrop-php7.0` `5.13-backdrop-php7.0` `5-backdrop-php7.0` `backdrop-php7.0` [(5/backdrop/7.0)](5/backdrop/7.0)
* `5.13.1-backdrop-php5.6` `5.13-backdrop-php5.6` `5-backdrop-php5.6` `backdrop-php5.6` [(5/backdrop/5.6)](5/backdrop/5.6)
<!---END_TAGS-->

civicrm:version/cms/php-version

# Usage

Select the version of CiviCRM and the CMS that you want to use, e.g. `civicrm:5-drupal` or `civicrm:5.13-backdrop`.

You can optionally select the PHP version as well e.g. civicrm:5-php7.3, though most users should be happy with the default.

# docker-compose

## Quick start

1. Create a new project from an example in the `compose-examples` directory, for example:

`cp -r compose-examples/wordpress ~/src/new-site`

1. Edit the `.env` file as desired

2. Install CiviCRM (and the associated CMS)

`docker-compose exec -u civicrm civicrm init`

# Reverse proxy

TODO: add quick start for a reverse proxy.

# MySQL

The images are designed to be used with whatever MySQL backend you like.

A simple mysql image based on  the official `mysql:5.6` and suitable for CiviCRM can be found at `michaelmcandrew/mysql-civicrm`. See the mysql folder of this repository for more info.

## Source code

For convenience, a zip of the original source code of CiviCRM and the selected CMS can be found at `/usr/local/src`.

## Administration

Steps up update this repository. These are scripted and run automatically when a new release of CiviCRM or any of the CMSes, or an update of the base php image they are derived from.

`generate.py` automates the generation of Dockerfiles (updates combos.json).
`build.py` builds images based on the dockerfiles (based on combos.json).
`publish.py` publishes these images on dockerhub (based on combos.json).

