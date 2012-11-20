<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

class Update extends PHPUnit_Framework_TestCase {

   public function testUpdate() {
      
      $Update = new Update();
      $Update->Update("2.3.3");
      $Update->Update("2.1.3");
   }
   
   
   function Update($version = '') {
      global $DB;

      if ($version == '') {
         return;
      }
      echo "#####################################################\n
            ######### Update from version ".$version."###############\n
            #####################################################\n";
      $GLPIInstall = new GLPIInstall();
      $GLPIInstall->testInstall();
      
      $query = "SHOW TABLES";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if (strstr($data[0], "tracker")
                 OR strstr($data[0], "fusi")) {
            $DB->query("DROP TABLE ".$data[0]);
         }
      }
      $query = "DELETE FROM `glpi_displaypreferences` 
         WHERE `itemtype` LIKE 'PluginFus%'";
      $DB->query($query);
      
      // ** Insert in DB
      $res = $DB->runFile(GLPI_ROOT ."/plugins/fusioninventory/phpunit/FusinvInstall/Update/mysql/i-".$version.".sql");
      $this->assertTrue($res, "Fail: SQL Error during insert version ".$version);
      
      passthru("cd ../tools/ && /usr/local/bin/php -f cli_install.php");
      
      $FusinvInstall = new FusinvInstall();
      $FusinvInstall->testDB("fusioninventory");
      
      $FusinvInstall->testDB("fusinvinventory");
      
      $FusinvInstall->testDB("fusinvsnmp");
      
//      $FusinvInstall->testDB("fusinvdeploy");
      
      $this->assertFileExists("../../../files/_plugins/fusinvsnmp/discovery.xml", 
                 'Discovery file (SNMP MODELS) not created');
      $file = file_get_contents("../../../files/_plugins/fusinvsnmp/discovery.xml");
      $a_lines = explode("\n", $file);
      $this->assertGreaterThan(20, count($a_lines), 'Discovery.xml file not right generated (nb lines)');

      
      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
      
   }
}



class Update_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('Update');
      return $suite;
      
   }
}

?>