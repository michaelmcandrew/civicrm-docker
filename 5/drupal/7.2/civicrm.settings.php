<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 5                                                  |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2018                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the GNU Affero General Public License           |
 | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the GNU Affero General Public License for more details.        |
 |                                                                    |
 | You should have received a copy of the GNU Affero General Public   |
 | License and the CiviCRM Licensing Exception along                  |
 | with this program; if not, contact CiviCRM LLC                     |
 | at info[AT]civicrm[DOT]org. If you have questions about the        |
 | GNU Affero General Public License or the licensing of CiviCRM,     |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 * CiviCRM Configuration File.
 */
global $civicrm_root, $civicrm_setting, $civicrm_paths;

define('CIVICRM_UF', 'Drupal');
define('CIVICRM_UF_DSN', 'mysql://' . getenv('DRUPAL_DB_USER') . ':' . getenv('DRUPAL_DB_PASS') . '@' . getenv('DRUPAL_DB_HOST') . '/' . getenv('DRUPAL_DB_NAME') . '?new_link=true');
define('CIVICRM_DSN', 'mysql://' . getenv('CIVICRM_DB_USER') . ':' . getenv('CIVICRM_DB_PASS') . '@' . getenv('CIVICRM_DB_HOST') . '/' . getenv('CIVICRM_DB_NAME') . '?new_link=true');
define('CIVICRM_LOGGING_DSN', CIVICRM_DSN);
$civicrm_root = '/var/www/html/sites/all/modules/civicrm/';
define('CIVICRM_TEMPLATE_COMPILEDIR', '/var/www/html/sites/default/files/civicrm/templates_c/');
define('CIVICRM_UF_BASEURL', getenv('BASE_URL'));
define('CIVICRM_SITE_KEY', getenv('CIVICRM_SITE_KEY'));
define('CIVICRM_PSR16_STRICT', FALSE);
define('CIVICRM_DEADLOCK_RETRIES', 3);
$include_path = '.'           . PATH_SEPARATOR .
  $civicrm_root . PATH_SEPARATOR .
  $civicrm_root . DIRECTORY_SEPARATOR . 'packages' . PATH_SEPARATOR .
  get_include_path();
if (set_include_path($include_path) === false) {
  echo "Could not set the include path<p>";
  exit();
}

if (!defined('CIVICRM_CLEANURL')) {
  if (function_exists('variable_get') && variable_get('clean_url', '0') != '0') {
    define('CIVICRM_CLEANURL', 1);
  } elseif (function_exists('config_get') && config_get('system.core', 'clean_url') != 0) {
    define('CIVICRM_CLEANURL', 1);
  } else {
    define('CIVICRM_CLEANURL', 0);
  }
}

// force PHP to auto-detect Mac line endings
ini_set('auto_detect_line_endings', '1');

// make sure the memory_limit is at least 64 MB
$memLimitString = trim(ini_get('memory_limit'));
$memLimitUnit   = strtolower(substr($memLimitString, -1));
$memLimit       = (int)$memLimitString;
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
