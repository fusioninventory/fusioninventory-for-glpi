<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$DB_file = 'locales/en_GB.php';

$sql_query = file_get_contents($DB_file);
foreach (explode(";\n", "$sql_query") as $line) {
   $split = explode("=", $line, 2);
   $string = $split[1];
   $string = str_replace('"', '', $string);
   echo $split[0]." => ".$string."\n";
   foreach (["./",
                 "./inc/",
                 "./ajax/",
                 "./b/deploy/",
                 "./install/",
                 "./js/",
                 "./scripts/",
                 "./test/"] as $dir) {
      foreach (glob($dir.'*.php') as $file) {
         $php_line_content = file_get_contents($file);
         $php_line_content = str_replace($split[0], "__('".$string."', 'fusioninventory')",
                                        $php_line_content);
         file_put_contents($file, $php_line_content);
      }
   }
}


