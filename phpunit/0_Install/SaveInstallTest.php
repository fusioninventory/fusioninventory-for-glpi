<?php

use PHPUnit\Framework\TestCase;

class SaveInstallTest extends TestCase {


   public function should_restore_install() {
      return false;
   }


   public function testSaveInstallation() {
      if (!defined('GLPI_ROOT')) {
         define('GLPI_ROOT', realpath('../../..'));
      }

      include_once (GLPI_ROOT . "/inc/based_config.php");
      include_once (GLPI_ROOT . "/inc/define.php");
      include_once (GLPI_ROOT . "/inc/dbmysql.class.php");
      include_once (GLPI_CONFIG_DIR . "/config_db.php");
      $DB = new DB();

      mysql_dump($DB->dbuser, $DB->dbhost, $DB->dbpassword, $DB->dbdefault, './save.sql');

      $this->assertFileExists("./save.sql");
      $filestats = stat("./save.sql");
      $length = $filestats[7];
      $this->assertGreaterThan(0, $length);
   }


}
