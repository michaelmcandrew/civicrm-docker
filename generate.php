<?php

require_once __DIR__ . '/variables.php';

$civiVersionBits = explode('.', $latestCiviVer);
$civiMajorVer = $civiVersionBits[0];
$civiMinorVer = $civiVersionBits[1];
$civiPatchVer = $civiVersionBits[2];

$cmsSettingsFiles = ['drupal' => 'settings.php', 'wordpress' => 'wp-config.php'];


$combos = [];
foreach ($cmses as $cms) {
  foreach ($phpVers as $phpVer) {
    $combos["$latestCiviVer/$cms/$phpVer"] = [
      'dir' => "$civiMajorVer/$cms/php$phpVer",
      'tags' => [['civi' => $latestCiviVer, 'cms' => $cms, 'php' => 'php' . $phpVer]],
      'variables' => ['civi' => $latestCiviVer, 'cms' => $cms, 'php' => $phpVer],
    ];
  }
}

$tags = [];

foreach ($combos as $k => $combo) {
  // default cms
  foreach ($combos[$k]['tags'] as $tag) {
    if ($tag['cms'] == $defaults['cms']) {
      $combos[$k]['tags'][] = ['cms' => ''] + $tag;
    }
  }

  // default php version
  foreach ($combos[$k]['tags'] as $tag) {
    if ($tag['php'] == 'php' . $defaults['php']) {
      $combos[$k]['tags'][] = ['php' => ''] + $tag;
    }
  }

  // default civicrm version
  foreach ($combos[$k]['tags'] as $tag) {
    if ($tag['civi'] == $defaults['civi']) {
      $combos[$k]['tags'][] = ['civi' => ''] + $tag;
    }
  }

  // minor alias
  foreach ($combos[$k]['tags'] as $tag) {
    if ($tag['civi'] == $defaults['civi']) {
      $combos[$k]['tags'][] = ['civi' => "$civiMajorVer.$civiMinorVer"] + $tag;
    }
  }

  // major alias
  foreach ($combos[$k]['tags'] as $tag) {
    if ($tag['civi'] == $defaults['civi']) {
      $combos[$k]['tags'][] = ['civi' => $civiMajorVer] + $tag;
    }
  }
  $implodedTags = [];
  foreach ($combos[$k]['tags'] as &$tag) {
    $newTag = implode('-', array_filter($tag));
    $implodedTags[] = $newTag ? $newTag : 'latest';
  }
  $combos[$k]['tags'] = $implodedTags;
  sort($combos[$k]['tags']);
}
file_put_contents(__DIR__ . '/' . 'combos.json', json_encode($combos, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

$projectDir = __DIR__;
`rm -r $projectDir/5`;


foreach ($combos as $combo) {
  $cms = $combo['variables']['cms'];
  $comboDir = __DIR__ . '/' . $combo['dir'];
  `mkdir -p $comboDir`;
  $dockerFile = inc("$projectDir/templates/Dockerfile", $combo['variables']);
  file_put_contents("$comboDir/Dockerfile", $dockerFile);
  `cp $projectDir/templates/$cms.civicrm.settings.php $comboDir/civicrm.settings.php`;
  $cmsSettingsFile = $cmsSettingsFiles[$cms];
  `cp $projectDir/templates/$cms.$cmsSettingsFile $comboDir/$cmsSettingsFile`;
  `cp $projectDir/templates/$cms.civicrm-docker-init $comboDir/civicrm-docker-init`;
  `cp $projectDir/templates/$cms.civicrm-docker-dump $comboDir/civicrm-docker-dump`;
  `cp $projectDir/templates/civicrm-docker-load $comboDir/civicrm-docker-load`;
  `cp $projectDir/templates/$cms.civicrm-docker-install $comboDir/civicrm-docker-install`;
  `cp $projectDir/templates/.my.cnf $comboDir`;
  `cp $projectDir/templates/apache.conf $comboDir`;
  `cp $projectDir/templates/apache-sites-available-default.conf $comboDir`;
  `cp $projectDir/templates/civicrm_dump.php $comboDir`;
  `cp $projectDir/templates/civicrm-docker-entrypoint $comboDir`;
  `cp $projectDir/templates/msmtp-wrapper $comboDir`;
}

$readme = file_get_contents($projectDir . '/README.md');
$startTagsToken = '<!--START_TAGS-->';
$endTagsToken = '<!--END_TAGS-->';
$startPos = strpos($readme, $startTagsToken) + strlen($startTagsToken);
$endPos = strpos($readme, $endTagsToken);


$readmeStart = substr($readme, 0, $startPos);
$readmeEnd = substr($readme, $endPos);

$tagsText = "\n";
foreach ($combos as $combo) {
  $tagsText .= '- ' . implode(', ', $combo['tags']) . " [" . $combo['dir'] . "](" . $combo['dir'] . ")\n";
}
file_put_contents($projectDir . '/README.md', $readmeStart . $tagsText . $readmeEnd);

function inc($file, $vars) {
  ob_start();
  include $file;
  return ob_get_clean();
}
