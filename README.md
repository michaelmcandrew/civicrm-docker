# CiviCRM on docker

An opinionated repository for production hosting of CiviCRM on Docker (your opinions on how it could be improved are very welcome).

**Health warning:** this repository is under development and is not yet stable - use it in production at your own risk!

## Quick start

1. Create a new project from an example in the `compose-examples` directory, for example:

`cp -r compose-examples/wordpress ~/src/new-site`

1. Edit the `.env` file as desired

2. Install CiviCRM (and the associated CMS)

`docker-compose exec -u civicrm civicrm init`

## Documentation

See the [docs](docs) directory for documentation.

