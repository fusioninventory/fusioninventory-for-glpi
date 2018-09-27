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

require_once("0_Install/FusinvDB.php");

class FusinvInstallTest extends Common_TestCase {


   public function testInstall() {

      global $DB;
      $DB->connect();
      $this->assertTrue($DB->connected, "Problem connecting to the Database");

      // Delete if Table of FusionInventory or Tracker yet in DB
      $query = "SHOW FULL TABLES WHERE TABLE_TYPE LIKE 'VIEW'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if (strstr($data[0], "fusi")) {
            $DB->query("DROP VIEW ".$data[0]);
         }
      }

      $query = "SHOW TABLES";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if (strstr($data[0], "tracker")
            OR strstr($data[0], "fusi")) {
               $DB->query("DROP TABLE ".$data[0]);
         }
      }
      $this->install();
   }

   public function testForceInstall() {
      global $DB;
      $DB->connect();
      $this->assertTrue($DB->connected, "Problem connecting to the Database");

      $pfComputerComputer = new PluginFusioninventoryInventoryComputerComputer();

      //Add a data in the FI database
      $pfComputerComputer->add(['id' =>1, 'computers_id' => 1]);
      //Check that the data is available
      $this->assertEquals(1, count($pfComputerComputer->find()));

      //Launch the script using the default behavior: data is always accessible
      $this->install(false);
      $this->assertEquals(1, count($pfComputerComputer->find()));

      //Reinstall using --force-install option : data is not present anymore
      $this->install(true);
      $this->assertEquals(0, count($pfComputerComputer->find()));
   }

   function install($force = false) {
      $output     = [];
      $returncode = 0;
      $command = "php -f ".FUSINV_ROOT. "/scripts/cli_install.php -- --as-user 'glpi'";
      if ($force) {
         $command.= " --force-install";
      }
      exec($command, $output, $returncode);
      $this->assertEquals(0, $returncode,
         "Error when installing plugin in CLI mode\n".
         implode("\n", $output)
      );

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      $FusinvDBTest = new FusinvDB();
      $FusinvDBTest->checkInstall("fusioninventory", "install new version");

      PluginFusioninventoryConfig::loadCache();
   }


}



