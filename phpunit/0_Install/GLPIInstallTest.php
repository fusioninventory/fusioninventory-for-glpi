<?php
require_once('commonfunction.php');
include_once (GLPI_ROOT . "/config/based_config.php");
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

      $result = load_mysql_file(
         $DBvars['dbuser'],
         $DBvars['dbhost'],
         $DBvars['dbdefault'],
         $DBvars['dbpassword'],
         GLPI_ROOT ."/install/mysql/glpi-0.91-empty.sql"
      );

      $output = array();
      $returncode = 0;
      exec(
         "php -f ".GLPI_ROOT. "/tools/cliupdate.php -- --upgrade",
         $output, $returncode
      );
      $this->assertEquals(0,$returncode,
         "Error when update GLPI in CLI mode\n".
         implode("\n",$output)
      );

      $this->assertEquals( 0, $result['returncode'],
         "Failed to install GLPI database:\n".
         implode("\n", $result['output'])
      );

   }
}


