import os

# Define releases and variants
latest_civicrm_release = os.popen(
    "curl -s https://latest.civicrm.org/stable.php"
).read()
civi_releases = [latest_civicrm_release]
cms_variants = ["drupal", "wordpress", "backdrop"]
php_releases = ["7.3", "7.2", "7.1", "7.0", "5.6"]
defaults = {"civi": latest_civicrm_release, "cms": "drupal", "php": "7.2"}
