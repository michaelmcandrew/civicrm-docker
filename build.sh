set -xe
docker build -f civicrm-base.dockerfile -t civicrm-base .
docker build -f civicrm-drupal.dockerfile -t civicrm-drupal .
docker build -f civicrm-wordpress.dockerfile -t civicrm-wordpress .
set +xe
echo "\nBuild process completed successfully"