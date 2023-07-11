import os

# Define releases and variants
latest_civicrm_release = os.popen(
    "curl -s https://latest.civicrm.org/stable.php"
).read()
civi_releases = [latest_civicrm_release]
cms_variants = [
    "drupal",
    "wordpress",
]  # Skip backdrop for now since Dockerfile is broken
php_releases = ["8.0", "8.1", "8.2", "8.3"]
defaults = {"civi": latest_civicrm_release, "cms": "wordpress", "php": "8.1"}
