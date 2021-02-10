<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

require_once("FusinvDB.php");

use PHPUnit\Framework\TestCase;

class InstallationTest extends TestCase {

   public static function setUpBeforeClass(): void {
      // clean log files
      file_put_contents("../../files/_log/php-errors.log", '');
      file_put_contents("../../files/_log/sql-errors.log", '');
   }

   /**
    * @test
    */
   public function testInstall() {
      global $DB;

      // Delete if Table of FusionInventory or Tracker yet in DB
      $query = "SHOW FULL TABLES WHERE TABLE_TYPE LIKE 'VIEW'";
      $result = $DB->query($query);
      while ($data=$DB->fetchArray($result)) {
         if (strstr($data[0], "fusi")) {
            $DB->query("DROP VIEW ".$data[0]);
         }
      }

      $query = "SHOW TABLES";
      $result = $DB->query($query);
      while ($data=$DB->fetchArray($result)) {
         if (strstr($data[0], "tracker")
            OR strstr($data[0], "fusi")) {
               $DB->query("DROP TABLE ".$data[0]);
         }
      }
      $DB->query('TRUNCATE TABLE glpi_plugins');
      $this->_install();
   }


   function _install() {
      $output     = [];
      $returncode = 0;
      $outputActivate     = [];
      $returncodeActivate = 0;
      $command = "cd ../../ && php bin/console glpi:plugin:install -vvv -n --config-dir=tests --username=glpi fusioninventory";
      exec($command, $output, $returncode);

      $commandActivate = "cd ../../ && php bin/console glpi:plugin:activate -n --config-dir=tests fusioninventory";
      exec($commandActivate, $outputActivate, $returncodeActivate);

      // Check if errors in logs
      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      $this->assertEquals(0, $returncode,
         "Error when installing plugin in CLI mode\n".
         implode("\n", $output)."\n".$command."\n"
      );

      $FusinvDBTest = new FusinvDB();
      $FusinvDBTest->checkInstall("fusioninventory", "install new version");

      PluginFusioninventoryConfig::loadCache();

   }
}
