<?php

$latestCiviVer = file_get_contents('https://latest.civicrm.org/stable.php');

$cmses = [
  'drupal',
  'wordpress',
];
$phpVers = [
  '8.1',
  '8.2',
  '8.3',
];

$defaults = [
  "civi" => $latestCiviVer,
  "cms" => "wordpress",
  "php" => "8.1",
];
