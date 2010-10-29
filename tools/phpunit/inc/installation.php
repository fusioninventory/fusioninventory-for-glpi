<?php


function installGLPI() {
   global $LANG, $CFG_GLPI;

   include_once (GLPI_ROOT . "/inc/dbmysql.class.php");
   include_once (GLPI_ROOT . "/config/config_db.php");

   $DB = new DB;
   if (!$DB->runFile(GLPI_ROOT ."/install/mysql/glpi-0.78-empty.sql")) {
      echo "Errors occurred inserting default database\n";
   }

   // update default language
   $query = "UPDATE `glpi_configs` SET language='en_GB' ;";
   $DB->query($query) or die("4203 error");
   $query = "UPDATE `glpi_users` SET language=NULL ;";
   $DB->query($query) or die("4203 error");

}


function installFusionPlugins() {
   global $DB;
   
   $Plugin = new Plugin();

   $Plugin->init();

   $query = "SELECT * FROM glpi_plugins
      WHERE directory = 'fusioninventory' ";
   $result = $DB->query($query);
   while ($fields = $DB->fetch_array($result)) {
      $Plugin->install($fields['id']);
      $Plugin->activate($fields['id']);
   }
   
   $query = "SELECT * FROM glpi_plugins
      WHERE directory LIKE 'fusinv%' ";
   $result = $DB->query($query);
   while ($fields = $DB->fetch_array($result)) {
      $Plugin->install($fields['id']);
      $Plugin->activate($fields['id']);
   }
   
}


?>