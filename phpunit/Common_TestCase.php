<?php
/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

abstract class Common_TestCase extends TestCase {

   public function mark_incomplete($description = null) {
      $this->markTestIncomplete(
         is_null($description) ? 'This test is not implemented yet' : $description
      );
   }


   public static function restore_database() {

      self::drop_database();
      self::load_mysql_file('./save.sql');
   }


   public static function load_mysql_file($filename) {

      self::assertFileExists($filename, 'File '.$filename.' does not exist!');

      $DBvars = get_class_vars('DB');

      $result = load_mysql_file(
         $DBvars['dbuser'],
         $DBvars['dbhost'],
         $DBvars['dbdefault'],
         $DBvars['dbpassword'],
         $filename
      );

      self::assertEquals( 0, $result['returncode'],
         "Failed to restore database:\n".
         implode("\n", $result['output'])
      );
   }


   public static function drop_database() {

      $DBvars = get_class_vars('DB');

      $result = drop_database(
         $DBvars['dbuser'],
         $DBvars['dbhost'],
         $DBvars['dbdefault'],
         $DBvars['dbpassword']
      );

      self::assertEquals( 0, $result['returncode'],
         "Failed to drop GLPI database:\n".
         implode("\n", $result['output'])
      );
   }


   protected function setUp() {
      global $CFG_GLPI, $DB;

      // Force profile in session to SuperAdmin
      $_SESSION['glpiprofiles'] = ['4' => ['entities' => 0]];
      $_SESSION['glpi_plugin_fusioninventory_profile']['unmanaged'] = ALLSTANDARDRIGHT;
      $_SESSION['glpiactiveentities'] = [0, 1];
      $_SESSION['glpi_use_mode'] = Session::DEBUG_MODE;
      $_SESSION['glpilanguage'] = 'en_GB';

      include (GLPI_ROOT . "/inc/based_config.php");
      include_once (GLPI_ROOT . "/inc/config.php");
      include_once(GLPI_CONFIG_DIR . "/config_db.php");
      include_once (GLPI_ROOT . "/inc/define.php");

      $DB = new DB();
      $DB->connect();

      $plugin = new Plugin();
      $DB->connect();
      require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/module.class.php");
      $plugin->getFromDBbyDir("fusioninventory");
      $plugin->activate($plugin->fields['id']);

      file_put_contents(GLPI_ROOT."/files/_log/sql-errors.log", '');
      file_put_contents(GLPI_ROOT."/files/_log/php-errors.log", '');

      $dir = GLPI_ROOT."/files/_files/_plugins/fusioninventory";
      if (file_exists($dir)) {
         $objects = scandir($dir);

         foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
               if (filetype($dir."/".$object) != "dir") {
                  unlink($dir."/".$object);
               }
            }
         }
      }
      include_once (GLPI_ROOT . "/inc/timer.class.php");
      // Security of PHP_SELF
      $_SERVER['PHP_SELF']=Html::cleanParametersURL($_SERVER['PHP_SELF']);

      ini_set("memory_limit", "-1");
      ini_set("max_execution_time", "0");
   }


   protected function tearDown() {
      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }


}
