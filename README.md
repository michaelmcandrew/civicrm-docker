# CiviCRM on docker

An opinionated repository for production hosting of CiviCRM on Docker (your opinions on how it could be improved are very welcome).

All images are based php-apache.

In most cases, just choose the CMS that you would like to use, e.g. `civicrm:wordpress`. Everything else (php version, etc.) will be set to sensible defaults. You can get more specific with one of the tags below.

Please share your experiences using these images so we can improve them as we go. Questions about how to use these images are very welcome in the issue queue. 

## Tags

The following tags are available:

<!--START_TAGS-->
- 6, 6-php8.3, 6-wordpress, 6-wordpress-php8.3, 6.4, 6.4-php8.3, 6.4-wordpress, 6.4-wordpress-php8.3, 6.4.0, 6.4.0-php8.3, 6.4.0-wordpress, 6.4.0-wordpress-php8.3, latest, php8.3, wordpress, wordpress-php8.3 [6/wordpress/php8.3](6/wordpress/php8.3)
- 6-php8.2, 6-wordpress-php8.2, 6.4-php8.2, 6.4-wordpress-php8.2, 6.4.0-php8.2, 6.4.0-wordpress-php8.2, php8.2, wordpress-php8.2 [6/wordpress/php8.2](6/wordpress/php8.2)
- 6-php8.1, 6-wordpress-php8.1, 6.4-php8.1, 6.4-wordpress-php8.1, 6.4.0-php8.1, 6.4.0-wordpress-php8.1, php8.1, wordpress-php8.1 [6/wordpress/php8.1](6/wordpress/php8.1)
- 6-drupal, 6-drupal-php8.3, 6.4-drupal, 6.4-drupal-php8.3, 6.4.0-drupal, 6.4.0-drupal-php8.3, drupal, drupal-php8.3 [6/drupal/php8.3](6/drupal/php8.3)
- 6-drupal-php8.2, 6.4-drupal-php8.2, 6.4.0-drupal-php8.2, drupal-php8.2 [6/drupal/php8.2](6/drupal/php8.2)
- 6-drupal-php8.1, 6.4-drupal-php8.1, 6.4.0-drupal-php8.1, drupal-php8.1 [6/drupal/php8.1](6/drupal/php8.1)
<!--END_TAGS-->

## 'Quick' start

There are a couple of options for getting started. Here are a couple of common workflows that I use.

If I plan on doing a fair amount of custom development as part of a project, I like to start with a 'mono repo' on the host machine containing everything from the CMS root downwards (excluding files with credentials in and folders that include uploads via the web server). This makes working with IDEs, debugging, etc. easier.

If you aren't planning on doing much custom development (e.g. maybe you are just installing already existing modules and extensions) it might make more sense to take advantage of the CMS and CiviCRM already installed on the image (at `/var/www/html`).

To get a local project up and running, choose your CMS flavour from the [docker-compose](docker-compose) and copy the entire directory to somewhere like `~/projects/backdrop-example-project`

1. Copy the `dev.dist.env` file to `.env`. and edit as appropriate (choose sensible passwords, set the base url and other required environment variables, e.g. the domain you want the site to be available at.
2. Copy `docker-compose.dev.dist.yml` to `docker-compose.yml` and assign port numbers as appropriate (see _Reverse proxies_ for more details).
3. _If you are mounting a mono repo from the host to the container_ (see above), place it in the `src` folder, and uncomment the `./src:/var/www/html` volume.
4. _If you want development site to be available at a memorable address and/or you want to serve it via https_, configure it now.
5. Run `docker-compose up -d`
6. Install a new site with something like `docker-compose exec civicrm civicrm-docker-install` .

Note that if you already have a dump containing the database and file dump (in the format expected by civicrm-docker) you do not need to install the CMS and CiviCRM. You can just initialise it with `docker-compose exec civicrm civicrm-docker-install` and then follow the load instructions below.

## Dumping state

Running `docker-compose exec civicrm civicrm-docker-dump` will place a database and file dump in the `/state` folder of the container (which is mapped to the `./state` sub-folder in your docker-compose root on the host).

## Loading state

Running `docker-compose exec civicrm civicrm-docker-load` will load an existing database dump from the `/state` folder of the container (which is mapped to the `./state` sub-folder in your docker-compose root on the host.

# MySQL

The images are MySQL flavour agnostic.

## Administration

To update the images on Docker Hub that are defined in this repository, run the `update.php` script. 

This calls the following processes in order:

- `generate.php` automates the generation of Dockerfiles (updates combos.json).
- `buildAndPublish.php` builds images based on the dockerfiles (based on combos.json) and publishes them.

Note: The `buildAndPublish.php` script publishes multi-architecture images to docker hub using commands that look like this: 

`docker build --builder amd_and_arm --platform linux/amd64,linux/arm64 <Dockerfile> --tag image:1 --tag image:1.0 --push`

Hence, you'll need to run this on a host that can build `linux/arm64` and `linux/amd64` images. You'll also need to set up a builder called `amd_and_arm`.

Note:`update.php` also pulls from and pushes to the various repositories for this code (3SD's Gitlab, CiviCRM's Gitlab, and Github).

### Configuring multiple native nodes

1. Choose a primary Docker instance that will run the build (and not the platform architecture)
2. Create other Docker instances that can build natively on the other architectures that you want. For example if you want to build for `amd` and `arm`, and your primary Docker instance is on `arm`, then create a docker instance on an `amd` machine.
3. Create a *Context* on the primary machine that allows you to access the other Docker service with `docker context create <name> --docker "host=ssh://<ssh_connection_details>"`. You can test that this worked by listing docker images using the other services' context, e.g. `docker --context=<name> image ls` should show images on the other service. It should also have created a builder on the primary machine which you can see with `docker builder ls`.
4. Create a builder that can build for both architectures with the following commands (assuming you are on `arm` and the other context is on `amd`)
5. `docker buildx create --platform arm64 --name amd_and_arm` creates a new context
6. `docker buildx create --platform amd64 --append --name amd_and_arm <context_name>` appends the amd builder.
7. Test all is as it should be with `docker builder ls`. It should output something like the following:
Buildkit 

```
NAME/NODE          DRIVER/ENDPOINT                   STATUS    BUILDKIT   PLATFORMS
amd_and_arm        docker-container                                       
 \_ amd_and_arm0    \_ unix:///var/run/docker.sock   running   v0.15.2    linux/arm64*, linux/arm/v7, linux/arm/v6
 \_ amd_and_arm1    \_ almond                        running   v0.15.2    linux/amd64*, linux/amd64/v2, linux/amd64/v3, linux/amd64/v4, linux/386
```

### Building for multiple architectures

