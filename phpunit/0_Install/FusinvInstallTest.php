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
      $command = "php ".FUSINV_ROOT. "/scripts/cli_install.php --as-user 'glpi'";
      if ($force) {
         $command.= " --force-install";
      }
      exec($command, $output, $returncode);

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



