<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

require_once("0_Install/FusinvDBTest.php");

class FusinvInstallTest extends BaseTestCase {


   public function should_restore_install() {
      return FALSE;
   }

   /**
    * @depends GLPIInstallTest::installDatabase
    */
   public function testInstall() {


      global $DB;
      $DB->connect();
      $this->assertTrue($DB->connected, "Problem connecting to the Database");

//      if (file_exists("save.sql") AND $verify == '0') {
//
//         $query = "SHOW FULL TABLES WHERE TABLE_TYPE LIKE 'VIEW'";
//         $result = $DB->query($query);
//         while ($data=$DB->fetch_array($result)) {
//            $DB->query("DROP VIEW ".$data[0]);
//         }
//
//         $query = "SHOW TABLES";
//         $result = $DB->query($query);
//         while ($data=$DB->fetch_array($result)) {
//            $DB->query("DROP TABLE ".$data[0]);
//         }
//
//         $res = $DB->runFile("save.sql");
//         $this->assertTrue($res, "Fail: SQL Error during import saved GLPI DB");
//
//         echo "\n======= Import save.sql file =======\n";
//
//         $FusinvInstall = new FusinvInstall();
//         $FusinvInstall->testDB("fusioninventory", "install new version");
//      } else {

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

         $output = shell_exec("/usr/bin/php -f ../scripts/cli_install.php 4 2>&1 1>/dev/null");
         $this->assertNotNull($output);
         $this->assertGreaterThan(0, strlen($output));

//         Session::loadLanguage("en_GB");
//         $FusinvInstall = new FusinvInstall();
//         $FusinvInstall->testDB("fusioninventory", "install new version");
         $FusinvDBTest = new FusinvDBTest();
         $FusinvDBTest->testDB("fusioninventory", "upgrade from ".$version);


         $this->assertFileExists("../../../files/_plugins/fusioninventory/discovery.xml",
                 'Discovery file (SNMP MODELS) not created');
         $file = file_get_contents("../../../files/_plugins/fusioninventory/discovery.xml");
         $a_lines = explode("\n", $file);

         // TODO: Use assertTag or assertEqualXMLStructure in order to test the discovery.xml file
         $this->assertGreaterThan(20, count($a_lines), 'Discovery.xml file not right generated (nb lines)');

      }

      PluginFusioninventoryConfig::loadCache();
   }
}



?>
