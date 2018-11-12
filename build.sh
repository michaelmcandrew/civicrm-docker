set -xe

CIVICRM_VERSION=$(curl -s https://latest.civicrm.org/stable.php)

docker build -t michaelmcandrew/civicrm-base base
docker build -t michaelmcandrew/civicrm-wordpress wordpress --build-arg CIVICRM_VERSION=$CIVICRM_VERSION
docker build -t michaelmcandrew/civicrm-drupal drupal --build-arg CIVICRM_VERSION=$CIVICRM_VERSION
docker build -t michaelmcandrew/civicrm-mysql mysql
set +xe
echo "Build process completed successfully"
