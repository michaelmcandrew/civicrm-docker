<?php
$images = json_decode(file_get_contents(__DIR__ . '/combos.json'));

$builder = "amd_and_arm";
$platforms = "linux/arm64,linux/amd64";

$commands = [];

foreach ($images as $image) {
  $tags = implode(array_map(fn($t)=> '--tag ' . $t, $image->tags));
  $commands[] = "docker build --builder $builder --platform $platforms $image->dir $tags --push";
}
foreach ($commands as $command){
    `$command`;
}
