<?php
$databases = array (
  'default' =>
  array (
    'default' =>
    array (
      'database' => getenv('DRUPAL_DB_NAME'),
      'username' => getenv('DRUPAL_DB_USER'),
      'password' => getenv('DRUPAL_DB_PASS'),
      'host' => getenv('DRUPAL_DB_HOST'),
      'port' => getenv('DRUPAL_DB_PORT'),
      'driver' => 'mysql',
      'prefix' => '',
    ),
  ),
);

$update_free_access = FALSE;

$drupal_hash_salt = getenv('DRUPAL_HASH_SALT');

$base_url = getenv('BASE_URL');

ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
ini_set('session.gc_maxlifetime', 200000);
ini_set('session.cookie_lifetime', 2000000);

$conf['404_fast_paths_exclude'] = '/\/(?:styles)|(?:system\/files)\//';
$conf['404_fast_paths'] = '/\.(?:txt|png|gif|jpe?g|css|js|ico|swf|flv|cgi|bat|pl|dll|exe|asp)$/i';
$conf['404_fast_html'] = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>404 Not Found</title></head><body><h1>Not Found</h1><p>The requested URL "@path" was not found on this server.</p></body></html>';

$conf['reverse_proxy'] = TRUE;
$conf['reverse_proxy_addresses'] = [$_SERVER['REMOTE_ADDR']];

if(file_exists('settings.extra.php')){
    require_once 'settings.extra.php';
}
