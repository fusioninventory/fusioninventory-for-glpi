#!/usr/bin/php
<?php
// Drop unused files from the internal repository.
//
// This script should be called periodically via cron. Especially if you
// synchronize multiple repositories.
//
// /<path to plugin fusioninventory>/scripts/cleanup_repository.php

$doc = <<<DOC
cleanup_repository.php

Usage:
   cleanup_repository.php [-n] [-h | -q | -d ]

-h, --help     show this help
-q, --quiet    run quietly
-d, --debug    display more execution messages
-n, --dry-run  just show what will be done

DOC;

error_reporting( E_ALL  );
ini_set("display_errors", 'stderr');
ini_set("log_errors", true);

chdir(dirname($_SERVER["SCRIPT_FILENAME"]));

include ("../../../inc/includes.php");

include ("./docopt.php");

require ("./logging.php");


define ( 'MANIFESTS_PATH',
   implode(
      DIRECTORY_SEPARATOR,
      [ GLPI_PLUGIN_DOC_DIR, 'fusioninventory', 'files' , 'manifests' ]
   )
);
define ( 'REPOSITORY_PATH',
   implode(
      DIRECTORY_SEPARATOR,
      [ GLPI_PLUGIN_DOC_DIR, 'fusioninventory', 'files', 'repository' ]
   )
);

class MyRecursiveFilterIterator extends RecursiveFilterIterator {


   public function accept() {
      return !preg_match('/^\./', $this->current()->getFilename());
   }


}


/**
 * Get every files used at least by one package.
 */


function getManifestsUsed($logger) {
   global $DB;

   $result = [];

   $orders = $DB->request('glpi_plugin_fusioninventory_deployorders');

   foreach ($orders as $order_data) {

      $logger->debug(" Get Files from Order ID ". $order_data['id']);
      $order_config = json_decode($order_data['json']);

      if (isset($order_config->jobs)
              && isset($order_config->jobs->associatedFiles)
              && count($order_config->jobs->associatedFiles) > 0) {
         foreach ($order_config->jobs->associatedFiles as $manifest) {
            $logger->debug($manifest);
            if (!in_array($manifest, $result)) {
               $result[] = $manifest;
            }
         }
      }
   }

   return $result;
}


/**
 * Get every files used at least by one package.
 */


function getManifestsRegistered($logger) {
   global $DB;

   $result = [];

   $files = $DB->request('glpi_plugin_fusioninventory_deployfiles');

   foreach ($files as $file_data) {

      $logger->debug(" Get File ID ". $file_data['id']);

      $logger->debug($file_data);
      if (!in_array($file_data['sha512'], $result)) {
         $result[] = $file_data['sha512'];
      }
   }
   return $result;
}


/**
 * Get the manifest files list in the repository.
 */


function getManifests($logger) {

   $result = [];

   $manifests = new DirectoryIterator( MANIFESTS_PATH );

   foreach ($manifests as $manifest) {
      if ($manifest->isFile()) {
         $logger->debug( $manifest->getFilename() );
         $result[] = $manifest->getFilename();
      }
   }
   return $result;
}


/**
 * Remove invalid manifests from repository.
 * This will remove the fileparts from repository
 */


function removeInvalidManifests($logger, $dryrun, $invalid_manifests, $valid_manifests) {

   $logger->info("Removing ".count($invalid_manifests)." invalid manifests");

   $invalid_fileparts = [];
   foreach ($invalid_manifests as $manifest) {
      $filepath = implode( DIRECTORY_SEPARATOR, [MANIFESTS_PATH,$manifest] );
      if (file_exists($filepath)) {
         $file = fopen($filepath, 'r');
         while (($buffer = fgets($file)) !== false) {
            $buffer = trim($buffer);
            if (!in_array($buffer, $invalid_fileparts)) {
               $invalid_fileparts[] = $buffer;
            }
         }
         fclose($file);
      }
   }

   //Excluding valid fileparts shared with invalid fileparts.
   foreach ($valid_manifests as $manifest) {
      $filepath = implode( DIRECTORY_SEPARATOR, [MANIFESTS_PATH,$manifest] );
      //No need to process the file if it doesn't exist.
      if (file_exists($filepath)) {
         $file = fopen($filepath, 'r');
         while (($buffer = fgets($file)) !== false) {
            $buffer = trim($buffer);
            // Exclude the valid filepart from invalid list if it exists in the latter.
            if (($index = array_search($buffer, $invalid_fileparts)) !== false) {
               unset( $invalid_fileparts[$index] );
            }
         }
         fclose($file);
      }
   }

   /**
    * Removing fileparts
    */
   $logger->info( count($invalid_fileparts) . " resulting invalid fileparts from manifests.");

   $fileparts_repository = new RecursiveDirectoryIterator( REPOSITORY_PATH );
   $fileparts_iterator = new RecursiveIteratorIterator($fileparts_repository);

   $total = 0;
   foreach ($fileparts_iterator as $filepart) {
      if ($filepart->isFile() && in_array($filepart->getFilename(), $invalid_fileparts)) {
         $logger->debug( "Start removing " . $filepart->getFileName() .".");
         if (!$dryrun) {
            $logger->debug( "Removing " . $filepart->getPathName() .".");
            unlink($filepart->getPathName());
            $total += 1;
         } else {
            $logger->debug( "Will remove " . $filepart->getPathName() .".");
         }
      }
   }

   $logger->info( $total . " fileparts have been removed.");
}


/**
 * Unregister the invalid manifests from database.
 */
function unregisterInvalidManifests($logger, $dryrun, $invalid_manifests) {

   $logger->info("Unregistering ".count($invalid_manifests)." manifests from database.");

   $pfDeployFile = new PluginFusioninventoryDeployFile();

   foreach ($invalid_manifests as $manifest) {
      $short_sha512 = substr($manifest, 0, 6);
      $data = $pfDeployFile->find(['shortsha512' => $short_sha512]);
      foreach ($data as $config) {
         $pfDeployFile->getFromDB($config['id']);
         $logger->info("Unregister file " . $pfDeployFile->fields['name']);
         if (!$dryrun) {
            $pfDeployFile->deleteFromDB();
         }
      }
   }

}


/**
 * Process arguments passed to the script
 */

$docopt = new \Docopt\Handler();
$args = $docopt->handle($doc);

$loglevel = Logging::$LOG_INFO;
$dryrun = $args['--dry-run'];
if ($args['--quiet']) {
   $loglevel = Logging::$LOG_QUIET;
} else if ($args['--debug']) {
   $loglevel = Logging::$LOG_DEBUG;
} else {
   $loglevel = Logging::$LOG_INFO;
}
$logger = new Logging($loglevel);


/**
 * Just do some debug with arguments scanned by docopt
 */

$logger->debug( "Script " . $_SERVER['argv'][0] . "called with following arguments:");
foreach ($args as $k=>$v) {
   $logger->debug( $k.': '.json_encode($v));
}


/**
 * Get every manifests in use in packages
 */

$manifests_used = getManifestsUsed($logger);
$logger->info(count($manifests_used) . " manifest(s) used in packages.");
$logger->debug($manifests_used);


/**
 * Get every manifests registered in database
 */

$manifests_registered = getManifestsRegistered($logger);
$logger->info( count($manifests_registered) . " manifest(s) registered in database.");
$logger->debug($manifests_registered);


/**
 * Get every manifests stored in the repository
 */

$manifests = getManifests($logger);
$logger->info( count($manifests). " manifest(s) in repository.");
$logger->debug($manifests);


/**
 * Get invalid registered manifests in database (ie. those manifests are no longer in use in any
 * packages).
 */

$invalid_manifests = array_diff($manifests_registered, $manifests_used);
$logger->info( count($invalid_manifests) . " invalid manifest(s).");
$logger->debug($invalid_manifests );


/**
 * Get valid registered manifests in database (ie. still in use by packages).
 */

$valid_manifests = array_diff($manifests_registered, $invalid_manifests);
$logger->info( count($valid_manifests) . " valid manifest(s).");
$logger->debug($valid_manifests );

removeInvalidManifests($logger, $dryrun, $invalid_manifests, $valid_manifests);
unregisterInvalidManifests($logger, $dryrun, $invalid_manifests);

$logger->info( 'Memory used : ' .number_format(memory_get_usage(true)/1024/1024, 3) . 'MiB');
$logger->info( 'Memory used (emalloc): ' .number_format(memory_get_usage()/1024/1024, 3) . 'MiB');

