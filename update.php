<?php
$repos = ['civi', 'hub', '3sd'];
$projectDir = __DIR__;

$commands = [];

foreach ($repos as $repo) {
  $commands[] = "git -C $projectDir pull $repo master";
}
$commands[] = "php $projectDir/generate.php";
$commands[] = "php $projectDir/buildAndPublish.php";
$commands[] = "git -C $projectDir add .";
$commands[] = "git -C $projectDir commit -m 'auto: updating generated files'";
foreach ($repos as $repo) {
  $commands[] = "git -C $projectDir push $repo";
}

foreach ($commands as $command) {
  echo "+{$command}\n";
  echo `$command`;
}
