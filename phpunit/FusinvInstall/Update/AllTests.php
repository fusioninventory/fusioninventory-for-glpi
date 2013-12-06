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

class Update extends PHPUnit_Framework_TestCase {

   public function testUpdate2_3_3() {
      global $PF_CONFIG;
      
      $PF_CONFIG = array();
      
      $Update = new Update();
      $Update->update("2.3.3");
   }
   
   
   public function testUpdate2_1_3() {
      global $PF_CONFIG;
      
      $PF_CONFIG = array();

      $Update = new Update();
      $Update->update("2.1.3");
   }
   
   
   public function testUpdate083_21() {
      global $PF_CONFIG;
      
      $PF_CONFIG = array();

      $Update = new Update();
      $Update->update("0.83+2.1");
      $Update->testEntityRule(1);
      $Update->verifyConfig();
   }
   


   function update($version = '') {
      global $DB;
      $DB->connect();
      
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

      passthru("cd ../scripts/ && php -f cli_install.php");

      $FusinvInstall = new FusinvInstall();
      $FusinvInstall->testDB("fusioninventory", "upgrade from ".$version);

      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

   }
   
   
   
   public function testEntityRule($nbrules=0) {
      global $DB;
      
      $DB->connect();
      
      if ($nbrules == 0) {
         return;
      }
      
      $cnt_old = countElementsInTable("glpi_rules", "`sub_type`='PluginFusinvinventoryRuleEntity'");
      
      $this->assertEquals(0, $cnt_old, "May not have entity rules with old itemtype name");
      
      $cnt_new = countElementsInTable("glpi_rules", "`sub_type`='PluginFusioninventoryInventoryRuleEntity'");
      
      $this->assertEquals($nbrules, $cnt_new, "May have ".$nbrules." entity rules");

   }
   

   
   public function testInstallCleanVersion() {
      global $DB;
      
      $DB->connect();
      
      $Install = new Install();
      $Install->testInstall(0);
      
   }
   
   
   
   function verifyConfig() {
      global $DB;
      
      $DB->connect();

      $a_configs = getAllDatasFromTable('glpi_plugin_fusioninventory_configs', 
                                        "`type`='states_id_default'");
      
      $this->assertEquals(1, count($a_configs), "May have conf states_id_default");
      
      $a_config = current($a_configs);
      $this->assertEquals(1, $a_config['value'], "May keep states_id_default to 1");
   }
}



class Update_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('Update');
      return $suite;

   }
}

?>
