<?php

function load_mysql_file($dbuser='', $dbhost='', $dbdefault='', $dbpassword='', $file = NULL) {

   if (!file_exists($file)) {
      return array(
         'returncode' => 1,
         'output' => array("ERROR: File '{$file}' does not exist !")
      );
   }

   $result = construct_mysql_options($dbuser, $dbhost, $dbpassword, 'mysql');

   if (is_array($result)) {
      return $result;
   }

   $cmd = $result . " " . $dbdefault . " < ". $file ." 2>&1";


   $returncode = 0;
   $output = array();
   exec(
      $cmd,
      $output,
      $returncode
   );
   array_unshift($output,"Output of '{$cmd}'");
   return array(
      'returncode'=>$returncode,
      'output' => $output
   );
}

function mysql_dump($dbuser = '', $dbhost = '', $dbpassword = '', $dbdefault = '', $file = NULL) {
   if (is_null($file) or empty($file)) {
      return array(
         'returncode' => 1,
         'output' => array("ERROR: mysql_dump()'s file argument must neither be null nor empty")
      );
   }

   if (empty($dbdefault)) {
      return array(
         'returncode' => 2,
         'output' => array("ERROR: mysql_dump() is missing dbdefault argument.")
      );
   }

   $result = construct_mysql_options($dbuser, $dbhost, $dbpassword, 'mysqldump');
   if (is_array($result)) {
      return $result;
   }

   $cmd = $result . ' --opt '. $dbdefault.' > ' . $file;
   $returncode = 0;
   $output = array();
   exec(
      $cmd,
      $output,
      $returncode
   );
   array_unshift($output, "Output of '{$cmd}'");
   return array(
      'returncode'=>$returncode,
      'output' => $output
   );
}

function construct_mysql_options($dbuser='', $dbhost='', $dbpassword='', $cmd_base='mysql') {
   $cmd = array();

   if ( empty($dbuser) || empty($dbhost)) {
      return array(
         'returncode' => 2,
         'output' => array("ERROR: missing mysql parameters (user='{$dbuser}', host='{$dbhost}')")
      );
   }
   $cmd = array($cmd_base);

   if (strpos($dbhost, ':') !== FALSE) {
      $dbhost = explode( ':', $dbhost);
      if ( !empty($dbhost[0]) ) {
         $cmd[] = "--host ".$dbhost[0];
      }
      if ( is_numeric($dbhost[1]) ) {
         $cmd[] = "--port ".$dbhost[1];
      } else {
         // The dbhost's second part is assumed to be a socket file if it is not numeric.
         $cmd[] = "--socket ".$dbhost[1];
      }
   } else {
      $cmd[] = "--host ".$dbhost;
   }

   $cmd[] = "--user ".$dbuser;

   if (!empty($dbpassword)) {
      $cmd[] = "-p'".urldecode($dbpassword)."'";
   }

   return implode(' ', $cmd);

}

function drop_database($dbuser='', $dbhost='', $dbdefault='', $dbpassword=''){

   $cmd = construct_mysql_options($dbuser, $dbhost, $dbpassword, 'mysql');

   if (is_array($cmd)) {
      return $cmd;
   }

   $cmd = 'echo "DROP DATABASE IF EXISTS '.$dbdefault .'; CREATE DATABASE '.$dbdefault.'" | ' . $cmd ." 2>&1";


   $returncode = 0;
   $output = array();
   exec(
      $cmd,
      $output,
      $returncode
   );
   array_unshift($output,"Output of '{$cmd}'");
   return array(
      'returncode'=>$returncode,
      'output' => $output
   );

}
