#!/usr/bin/php
<?php

$doc = <<<DOC
prepare_jobs.php

Usage:
   prepare_jobs.php [-h | -q | -d ]

-h, --help     show this help
-q, --quiet    run quietly
-d, --debug    display more execution messages

DOC;

chdir(dirname($_SERVER["SCRIPT_FILENAME"]));

include ("../../../inc/includes.php");

include ("./docopt.php");

require ("./logging.php");

$_SESSION["glpicronuserrunning"] = 1;

/**
 * Process arguments passed to the script
 */

$docopt = new \Docopt\Handler();
$args = $docopt->handle($doc);

$logger = new Logging();
$logger->setLevelFromArgs($args['--quiet'], $args['--debug']);

$task = new PluginFusioninventoryTask();

$task->cronTaskscheduler();
