<?php

function backupMySQL() {
   global $DB;


   // List all tables
   $result=$DB->list_tables("");


   $fileHandle = fopen('backup/backup.sql', "w");

   while ($line = $DB->fetch_array($result)) {
      $table = $line[0];
      $todump = "\n".get_def($DB,$table);
      fwrite ($fileHandle,$todump);
      $offsetrow++;
      $cpt++;

      $todump = get_content($DB,$table,0,100000);
      $rowtodump = substr_count($todump, "INSERT INTO");
      fwrite ($fileHandle,$todump);
      $DB->query("DROP TABLE `".$table."`");
   }
   fclose($fileHandle);
}


function restoreMySQL() {
   global $DB,$TPSCOUR,$offset,$cpt,$LANG;

   $dumpFile = 'backup/backup.sql';


   if (!file_exists($dumpFile)) {
      echo $LANG['document'][38]."&nbsp;: $dumpFile<br>";
      return false;
   }
   $fileHandle = fopen($dumpFile, "rb");

   if (!$fileHandle) {
      echo $LANG['document'][45]."&nbsp;: $dumpFile<br>";
      return false;
   }

   if ($offset != 0) {
      if (fseek($fileHandle,$offset,SEEK_SET) != 0) { //erreur
         echo $LANG['backup'][22]." ".formatNumber($offset,false,0)."<br>";
         return false;
      }
      glpi_flush();
   }

   $formattedQuery = "";

   while (!feof($fileHandle)) {

      // specify read length to be able to read long lines
      $buffer = fgets($fileHandle,102400);

      // do not strip comments due to problems when # in begin of a data line
      $formattedQuery .= $buffer;
      if (get_magic_quotes_runtime()) {
         $formattedQuery = stripslashes($formattedQuery);
      }
      if (substr(rtrim($formattedQuery),-1) == ";") {
         // Do not use the $DB->query
         if ($DB->query($formattedQuery)) { //if no success continue to concatenate
            $offset = ftell($fileHandle);
            $formattedQuery = "";
            $cpt++;
         }
      }
   }

   if ($DB->error) {
      echo "error";
   }

   fclose($fileHandle);
   return true;
   
}



function get_def($DB, $table) {

   $def = "### Dump table $table\n\n";
   $def .= "DROP TABLE IF EXISTS `$table`;\n";
   $query = "SHOW CREATE TABLE `$table`";
   $result = $DB->query($query);
   $DB->query("SET SESSION sql_quote_show_create = 1");
   $row = $DB->fetch_array($result);

   $def .= preg_replace("/AUTO_INCREMENT=\w+/i","",$row[1]);
   $def .= ";";
   return $def."\n\n";
}


function get_content($DB, $table,$from,$limit) {

   $content = "";
   $gmqr = "";
   $result = $DB->query("SELECT *
                         FROM `$table`
                         LIMIT ".intval($from).",".intval($limit));
   if ($result) {
      $num_fields = $DB->num_fields($result);
      if (get_magic_quotes_runtime()) {
         $gmqr = true;
      }
      while ($row = $DB->fetch_row($result)) {
         if ($gmqr) {
            $row = addslashes_deep($row);
         }
         $insert = "INSERT INTO `$table` VALUES (";

         for( $j=0 ; $j<$num_fields ; $j++) {
            if (is_null($row[$j])) {
               $insert .= "NULL,";
            } else if ($row[$j] != "") {
               $insert .= "'".addslashes($row[$j])."',";
            } else {
               $insert .= "'',";
            }
         }
         $insert = preg_replace("/,$/","",$insert);
         $insert .= ");\n";
         $content .= $insert;
      }
   }
   return $content;
}

?>