<?php

function load_mysql_file($dbuser='', $dbhost='', $dbdefault='', $dbpassword='', $file = NULL) {

   if (!file_exists($file)) {
      return array(
         'returncode' => 1,
         'output' => array("ERROR: File '{$file}' does not exist !")
      );
   }

   $result = construct_mysql($dbuser, $dbhost, $dbpassword);

   if (is_array($result)) {
      return $result;
   }

   $cmd = $result . " " . $dbdefault . " < ". $file;


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

function construct_mysql($dbuser='', $dbhost='', $dbpassword='') {
   $cmd = array();

   if ( empty($dbuser) || empty($dbhost)) {
      return array(
         'returncode' => 2,
         'output' => array("ERROR: missing mysql parameters (user='{$dbuser}', host='{$dbhost}')")
      );
   }
   $cmd = array('mysql');

   $cmd[] = "-h ".$dbhost;

   $cmd[] = "-u ".$dbuser;

   if (!empty($dbpassword)) {
      $cmd[] = "-p'".urldecode($dbpassword)."'";
   }

   return implode(' ', $cmd);

}

function drop_database($dbuser='', $dbhost='', $dbdefault='', $dbpassword=''){

   $cmd = construct_mysql($dbuser, $dbhost, $dbpassword);

   if (is_array($cmd)) {
      return $cmd;
   }

   $cmd = 'echo "DROP DATABASE IF EXISTS '.$dbdefault .'; CREATE DATABASE '.$dbdefault.'" | ' . $cmd;


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
