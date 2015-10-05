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
         GLPI_ROOT ."/install/mysql/glpi-0.90-empty.sql"
      );

      $this->assertEquals( 0, $result['returncode'],
         "Failed to install GLPI database:\n".
         implode("\n", $result['output'])
      );

   }
}


