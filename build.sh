set -xe
docker build -t civicrm-base base
docker build -t civicrm-wordpress wordpress
docker build -t civicrm-drupal drupal
docker build -t civicrm-mysql mysql
set +xe
echo "Build process completed successfully"