#!/usr/bin/php
<?php

$doc = <<<DOC
get_job_logs.php

Usage:
   get_job_logs.php [-h | -q | -d ] [-m methods] [-t task_ids]

-h, --help     Show this help
-q, --quiet    Run quietly
-d, --debug    Show informations.

-m, --methods=methods   Show only tasks defined with a list of methods (separated by commas).
-t, --tasks=task_ids    Filter logs by tasks (separated by commas)
DOC;

chdir(dirname($_SERVER["SCRIPT_FILENAME"]));

include ("../../../inc/includes.php");

include ("./docopt.php");

require ("./logging.php");

/**
 * Process arguments passed to the script
 */

$docopt = new \Docopt\Handler();
$args = $docopt->handle($doc);

$logger = new Logging();
$logger->setLevelFromArgs($args['--quiet'], $args['--debug']);

$logger->debug($args);

/*
 * Get Running Tasks
 */

$pfTask = new PluginFusioninventoryTask();
$logs = $pfTask->getJoblogs();
$logger->info($logs);
