<?php
function civicrm_dump($state)
{

    $state['start'] = date(DATE_ISO8601);
    $tarName = "/state/{$state['project']}." . date('Ymd\THis') . ".tar.gz";

    $dumpDir = tempnam('/tmp', 'civicrm_dump_');
    `rm $dumpDir; mkdir $dumpDir`;

    foreach ($state['databases'] as $name) {
        foreach (['USER', 'PASS', 'HOST', 'PORT', 'NAME'] as $var) {
            $$var = getenv(strtoupper($name) . '_DB_' . $var);
        }
        `mysqldump -u $USER -p$PASS -h $HOST -P $PORT $NAME --single-transaction --skip-triggers | sed -E "/^\/\*\![[:digit:]]+ DEFINER/d" > {$dumpDir}/{$name}.sql`;
    }
    foreach ($state['directories'] as $name => $path) {
        `tar -czf {$dumpDir}/{$name}.tar.gz -C $path .`;
    }

    $state['end'] = date(DATE_ISO8601);
    file_put_contents("$dumpDir/state.json", json_encode($state, JSON_PRETTY_PRINT));

    `tar -czf $tarName -C $dumpDir .`;

    `rm -r $dumpDir`;

    echo "$tarName\n";
}
