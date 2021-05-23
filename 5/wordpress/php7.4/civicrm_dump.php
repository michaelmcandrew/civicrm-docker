<?php

function civicrm_dump($state) {

  $state['start'] = date(DATE_ISO8601);
  $tarName = "{$state['project']}." . date('Ymd\THis') . ".tar.gz";

  $dumpDir = tempnam('/tmp', 'civicrm_dump_');
  // tempnam creates this as a file, so we need to delete it first
  `rm $dumpDir`;
  `mkdir $dumpDir`;

  foreach ($state['databases'] as $name) {
    foreach (['USER', 'PASS', 'HOST', 'PORT', 'NAME'] as $var) {
      $envVar = strtoupper($name) . '_DB_' . $var;
      if (getenv($envVar) === FALSE) {
        die("Error: environment variable '$envVar' was not found.\n");
      }
      $$var = getenv(strtoupper($name) . '_DB_' . $var);
    }
    `mysqldump -u $USER -p$PASS -h $HOST -P $PORT $NAME --no-tablespaces --single-transaction --skip-triggers | sed -E "/^\/\*\![[:digit:]]+ DEFINER/d" > {$dumpDir}/{$name}.sql`;
  }
  foreach ($state['directories'] as $name => $path) {
    `tar -czf {$dumpDir}/{$name}.tar.gz -C $path .`;
  }

  $state['end'] = date(DATE_ISO8601);
  file_put_contents("$dumpDir/state.json", json_encode($state, JSON_PRETTY_PRINT));

  `tar -czf /state/$tarName -C $dumpDir .`;

  `rm -r $dumpDir`;

  echo "$tarName\n";
}
