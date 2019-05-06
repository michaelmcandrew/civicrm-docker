<?php
$settings['hash_salt'] = getenv('BACKDROP_HASH_SALT');
$database = 'mysql://' . getenv('BACKDROP_DB_USER') . ':' . getenv('BACKDROP_DB_PASS') . '@' . getenv('BACKDROP_DB_HOST') . ':' . getenv('BACKDROP_DB_PORT') . '/' . getenv('BACKDROP_DB_NAME') . '';
$config_directories['active'] = '../config/active';
$config_directories['staging'] = '../config/staging';
