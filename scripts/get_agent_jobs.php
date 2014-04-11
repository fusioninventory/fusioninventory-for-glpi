#!/usr/bin/php
<?php

$doc = <<<DOC
get_agent_jobs.php

Usage:
   get_agent_jobs.php [-h | -q | -d ] [--methods=methods] <device_ids>...

-h, --help     show this help
-q, --quiet    run quietly
-d, --debug    display more execution messages
device_ids     the agent's device_ids registered in GLPI
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

$agent = new PluginFusioninventoryAgent();
$computer = new Computer();


$task = new PluginFusioninventoryTask();
$staticmisc_methods = PluginFusioninventoryStaticmisc::getmethods();

$methods = array();
foreach($staticmisc_methods as $method) {
   $methods[$method['method']] = $method['method'];
}

foreach($args['<device_ids>'] as $device_id) {
   $logger->info("Get infos for Agent '$device_id' ...");
   $infos = $agent->InfosByKey($device_id);
   $logger->debug($infos);
   if ( count($infos) == 0 ) {
      $logger->error("Agent $device_id not found");
   } else {
      $logger->info($infos);
   }
   $logger->info("Get prepared jobs for Agent '$device_id'");
   $jobstates = $task->getTaskjobstatesForAgent($infos['id'], $methods, array('read_only'=>true));
   $logger->info($jobstates);
}
