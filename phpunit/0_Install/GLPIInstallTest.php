<?php
require_once('commonfunction.php');
include_once (GLPI_ROOT . "/inc/define.php");
include_once (GLPI_ROOT . "/inc/based_config.php");
include_once (GLPI_ROOT . "/inc/dbmysql.class.php");
include_once (GLPI_CONFIG_DIR . "/config_db.php");

class GLPIInstallTest extends PHPUnit_Framework_TestCase {


   /**
    * @test
    */
   public function installDatabase() {

      $DBvars = get_class_vars('DB');

      drop_database(
         $DBvars['dbuser'],
         $DBvars['dbhost'],
         $DBvars['dbdefault'],
         $DBvars['dbpassword']
      );

      $glpi_version = getenv('GLPI');
      $glpisql = '';
      if ($glpi_version == '0.90/bugfixes') {
         $glpisql = '0.90';
      } else if ($glpi_version == '0.85/bugfixes') {
         $glpisql = '0.85.5';
      } else {
         $glpisql = null;
      }

      $sql_file = $glpisql === null ? "glpi-empty.sql" : "glpi-$glpisql-empty.sql";
      $output = [];
      $returncode = 0;
      exec(
         "php ".GLPI_ROOT. "/scripts/cliinstall.php --force --db={$DBvars['dbdefault']} --user={$DBvars['dbuser']} --pass={$DBvars['dbpassword']}",
         $output, $returncode
      );
      $this->assertEquals(0, $returncode,
         "Error when installing GLPI in CLI mode\n".
         implode("\n", $output)
      );
   }
}


