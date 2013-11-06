#!/usr/bin/php
<?php
// Drop unused files from the internal repository.
//
// This script should be called periodicly. Espcially if you
// synchronize different repositories.
chdir(dirname($_SERVER["SCRIPT_FILENAME"]));

include ("../../../inc/includes.php");

function filepartIsUsed($file) {
   global $DB;


   $stack = array();
   if (!preg_match('/repository\/.\/..\/(......)(.*)\.gz$/', $file, $stack)) {
      return TRUE;
   }

   $sql = sprintf("SELECT id FROM glpi_plugin_fusinvdeploy_fileparts WHERE shortsha512='%s' AND sha512='%s'",
         $DB->escape($stack[1]),
         $DB->escape($stack[1].$stack[2]));

   $result = $DB->query($sql);
   return ($DB->numrows($result)>0);
}

class MyRecursiveFilterIterator extends RecursiveFilterIterator {

   public function accept() {
      return !preg_match('/^\./',  $this->current()->getFilename());
   }

}

$dirItr    = new RecursiveDirectoryIterator(GLPI_PLUGIN_DOC_DIR.'/fusinvdeploy/files/repository/');
$filterItr = new MyRecursiveFilterIterator($dirItr);
$itr       = new RecursiveIteratorIterator($filterItr, RecursiveIteratorIterator::SELF_FIRST);
foreach ($itr as $filePath => $fileInfo) {
   if ($fileInfo->isFile() && !filepartIsUsed($fileInfo->getPathname())) {
      unlink($fileInfo->getPathname());
   }
}
