# CiviCRM on docker

An opinionated repository for production hosting of CiviCRM on Docker (your opinions on how it could be improved are very welcome).

All images are based php-apache.

An appropriate version of php is selected for you.

# Tag structure

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

