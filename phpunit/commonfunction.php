<?php

function load_mysql($dbuser='', $dbhost='', $dbdefault='', $dbpassword='', $file = NULL) {

   if (!file_exists($file)) {
      return array(1,"ERROR: File '{$file}' does not exist !");
   }

   if ( empty($dbuser) || empty($dbhost) || empty($dbdefault) ) {
      return array(2,"ERROR: missing mysql parameters (user='{$dbuser}', host='{$dbhost}', dbname='{$dbdefault}')");
   }
   $cmd = array('mysql');

   $cmd[] = "-h ".$dbhost;

   $cmd[] = "-u ".$dbuser;

   if (!empty($dbpassword)) {
      $cmd[] = "-p'".urldecode($dbpassword)."'";
   }

   $cmd[] = $dbdefault;
   $cmd[] = " < ". $file;


   $cmd_flattened = implode(' ', $cmd);
   $returncode = 0;
   $output = array();
   exec(
      $cmd_flattened,
      $output,
      $returncode
   );
   array_unshift($output,"Output of '{$cmd_flattened}'");
   return array($returncode, $output );
}
