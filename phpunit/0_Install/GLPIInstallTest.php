<?php
require_once('commonfunction.php');
class GLPIInstallTest extends PHPUnit_Framework_TestCase {

   public function should_restore_install() {
      return FALSE;
   }

   /**
    * @test
    */
   public function installDatabase() {

      if (!defined('GLPI_ROOT')) {
         define('GLPI_ROOT', realpath('../../..'));
      }

      include_once (GLPI_ROOT . "/config/based_config.php");
      include_once (GLPI_ROOT . "/inc/dbmysql.class.php");
      include_once (GLPI_CONFIG_DIR . "/config_db.php");
      $DB = new DB();

      drop_database(
         $DB->dbuser,
         $DB->dbhost,
         $DB->dbdefault,
         $DB->dbpassword
      );

//      $DB->query("DROP DATABASE IF EXISTS ".$DB->dbdefault);
//
//      $DB->query("CREATE DATABASE ".$DB->dbdefault);

      $result = load_mysql_file(
         $DB->dbuser,
         $DB->dbhost,
         $DB->dbdefault,
         $DB->dbpassword,
         GLPI_ROOT ."/install/mysql/glpi-0.85-empty.sql"
      );

      $this->assertEquals( 0, $result['returncode'],
         "Failed to install GLPI database:\n".
         implode("\n", $result['output'])
      );

   }
}


