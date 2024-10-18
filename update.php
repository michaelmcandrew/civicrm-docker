<?php
$repos = ['civi', 'hub', '3sd'];

foreach ($repos as $repo) {
  $commands[] = "git -C $projectDir pull $repo";
}

$commands = [];
$projectDir = __DIR__;
$commands[] = "git -C $projectDir pull";
$commands[] = "php $projectDir/generate.php";
$commands[] = "php $projectDir/buildAndPublish.php";
$commands[] = "git -C $projectDir add .";
$commands[] = "git -C $projectDir commit -m 'auto: updating generated files'";
foreach ($repos as $repo) {
  $commands[] = "git -C $projectDir push $repo";
}

foreach ($commands as $command) {
  echo "+{$command}";
  echo `$command`;
}
