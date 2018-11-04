set -xe
docker build -f civicrm-base.dockerfile -t civicrm-base .
docker build -f civicrm-wordpress.dockerfile -t civicrm-wordpress .
docker build -f civicrm-drupal.dockerfile -t civicrm-drupal .
set +xe
echo "\nBuild process completed successfully"