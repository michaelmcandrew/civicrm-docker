<?php

$latestCiviVer = file_get_contents('https://latest.civicrm.org/stable.php');

$cmses = [
  'wordpress',
  'drupal',
];
$phpVers = [
  '8.4', 
  '8.3',
  '8.2',
  '8.1',
];

$defaults = [
  "civi" => $latestCiviVer,
  "cms" => "wordpress",
  "php" => "8.3",
];
