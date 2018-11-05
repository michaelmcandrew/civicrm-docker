set -xe
docker build -f civicrm-base.dockerfile -t civicrm-base .
docker build -f civicrm-wordpress.dockerfile -t civicrm-wordpress .
docker build -f civicrm-drupal.dockerfile -t civicrm-drupal .
docker build -f civicrm-mysql.dockerfile -t civicrm-mysql .
set +xe
echo "Build process completed successfully"