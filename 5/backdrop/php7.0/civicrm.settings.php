<?php
global $civicrm_root, $civicrm_setting, $civicrm_paths;

// CMS specific stuff
define('CIVICRM_UF', 'Backdrop');
$civicrm_root = '/var/www/html/modules/civicrm/';
define('CIVICRM_TEMPLATE_COMPILEDIR', '/var/www/html/files/civicrm/templates_c/');

// Constants set by env variables
define('CIVICRM_DSN', 'mysql://' . getenv('CIVICRM_DB_USER') . ':' . getenv('CIVICRM_DB_PASS') . '@' . getenv('CIVICRM_DB_HOST') . '/' . getenv('CIVICRM_DB_NAME') . '?new_link=true');
define('CIVICRM_UF_DSN', 'mysql://' . getenv('BACKDROP_DB_USER') . ':' . getenv('BACKDROP_DB_PASS') . '@' . getenv('BACKDROP_DB_HOST') . '/' . getenv('BACKDROP_DB_NAME') . '?new_link=true');
define('CIVICRM_UF_BASEURL', getenv('BASE_URL'));
define('CIVICRM_SITE_KEY', getenv('CIVICRM_SITE_KEY'));

// Predefined constants
define('CIVICRM_LOGGING_DSN', CIVICRM_DSN);
define('CIVICRM_DOMAIN_ID', 1);
define('CIVICRM_MAIL_SMARTY', 0);
define('CIVICRM_DB_CACHE_CLASS', 'ArrayCache');
define('CIVICRM_PSR16_STRICT', FALSE);
define('CIVICRM_DEADLOCK_RETRIES', 3);

// Include path
$include_path = '.' . PATH_SEPARATOR .
  $civicrm_root . PATH_SEPARATOR .
  $civicrm_root . DIRECTORY_SEPARATOR . 'packages' . PATH_SEPARATOR .
  get_include_path();
if (set_include_path($include_path) === false) {
  echo "Could not set the include path<p>";
  exit();
}

// Clean URLs
if (!defined('CIVICRM_CLEANURL')) {
  if (function_exists('variable_get') && variable_get('clean_url', '0') != '0') {
    define('CIVICRM_CLEANURL', 1);
  } elseif (function_exists('config_get') && config_get('system.core', 'clean_url') != 0) {
    define('CIVICRM_CLEANURL', 1);
  } elseif (function_exists('get_option') && get_option('permalink_structure') != '') {
    define('CIVICRM_CLEANURL', 1);
  } else {
    define('CIVICRM_CLEANURL', 0);
  }
}

// More stuff that probably shouldn't be in a settings file
ini_set('auto_detect_line_endings', '1');
$memLimitString = trim(ini_get('memory_limit'));
$memLimitUnit   = strtolower(substr($memLimitString, -1));
$memLimit       = (int) $memLimitString;
switch ($memLimitUnit) {
  case 'g':
    $memLimit *= 1024;
  case 'm':
    $memLimit *= 1024;
  case 'k':
    $memLimit *= 1024;
}
if ($memLimit >= 0 and $memLimit < 134217728) {
  ini_set('memory_limit', '128M');
}
require_once 'CRM/Core/ClassLoader.php';
CRM_Core_ClassLoader::singleton()->register();
