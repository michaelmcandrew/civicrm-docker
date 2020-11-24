# Getting started

In short, you'll need to:

1. Choose a base image (e.g. `civicrm-wordpress` or `civicrm-drupal`).
2. Create a `Dockerfile` and `docker-compose.yml` file for your project and configure as appropriate.
3. Create an `.env` file with your environmental configuration (see the examples `.env` files for inspiration).
4. Spin up your containers and initialise the project.

Each of the above steps is explained in more detail below. You may also wish to consult the pages on `deploying features` and `mantaining your site`.

## Images

We currently build images for running CiviCRM with WordPress and Drupal. These are more or less 'out-of-the-box' downloads of the CMS and CiviCRM with some configuration tweaks that make them more suitable for Docker hosting.

If you're interested in the details, the Dockerfiles should be pretty self explanatory.

The settings files have been tweaked to receive all environmental config from environment variables.

### The `civicrm-wordpress` image

WordPress is installed at `/var/www/html`.

CiviCRM (including the l10n files) is installed at `/var/www/html/wp-content/plugins/civcrm`.

`wp-cli` is installed at `/usr/local/bin`.

TODO: disable downloading from the web via FTP or similar in wp-config.

### The `civicrm-drupal` image

Drupal is installed at `/var/www/html`.

CiviCRM (including the l10n files) is installed at `/var/www/html/sites/all/modules/civcrm`.

`drush` is installed at `/usr/local/bin`.

### The `civicrm-base` image

The CMS specific images are built from a `civicrm-base` image, which satisfies CiviCRM's dependecies and is built from the offical docker `php:strech` image.

`cv` is installed at `/usr/local/bin`.

Note that this image is not designed to be used directly to run a CiviCRM site. You should use the CMS specific image for the CMS that you want to use.

## Dockerfiles

While it is possible to use the CMS images above to host a CiviCRM site with no further modifications, most users will want to further extend their sites with extensions, plugins, modules, etc.

There are various approaches to doing this. One is add the relevant code via a Dockerfile.

Here is an example Dockerfile for Drupal that downloads the `views`, `webform`, and `webform_civicrm` Drupal modules.

```docker
FROM civicrm:drupal

RUN drush dl views webform webform_civicrm
```
