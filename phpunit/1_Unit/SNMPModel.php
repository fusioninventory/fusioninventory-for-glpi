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
   @since     2013

   ------------------------------------------------------------------------
 */

class SNMPModel extends PHPUnit_Framework_TestCase {
   
   public function testDeleteModelDeleteItInNetworkEquipment() {
      global $DB;

      $DB->connect();
      
      $Install = new Install();
      $Install->testInstall(0);
      
      $GLPIlog = new GLPIlogs();
      $pfSnmpmodel = new PluginFusioninventorySnmpmodel();
      
      $DB->query("INSERT INTO `glpi_plugin_fusioninventory_networkequipments`
         (`networkequipments_id`, `plugin_fusioninventory_snmpmodels_id`)
         VALUES ('1', '1')");

      $DB->query("INSERT INTO `glpi_plugin_fusioninventory_networkequipments`
         (`networkequipments_id`, `plugin_fusioninventory_snmpmodels_id`)
         VALUES ('2', '2')");

      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
      
      $pfSnmpmodel->delete(array('id'=>1), 1);

      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
      
      $this->assertEquals(2, countElementsInTable('glpi_plugin_fusioninventory_networkequipments'), 
                          'May have 2 networkequipment line in DB');
      
      $a_pfnes = getAllDatasFromTable('glpi_plugin_fusioninventory_networkequipments');
      
      $this->assertEquals(0, $a_pfnes[1]['plugin_fusioninventory_snmpmodels_id'], 
                          'May have now no snmpmodel');
      
      $this->assertEquals(2, $a_pfnes[2]['plugin_fusioninventory_snmpmodels_id'], 
                          'May have always snmpmodel id=2');
   }
   
   
   
   public function testDeleteModelDeleteItInPrinter() {
      global $DB;

      $DB->connect();
      
      $Install = new Install();
      $Install->testInstall(0);
      
      $GLPIlog = new GLPIlogs();
      $pfSnmpmodel = new PluginFusioninventorySnmpmodel();
      
      $DB->query("INSERT INTO `glpi_plugin_fusioninventory_printers`
         (`printers_id`, `plugin_fusioninventory_snmpmodels_id`)
         VALUES ('1', '1')");
      
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
      
      $pfSnmpmodel->delete(array('id'=>1), 1);

      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
      
      $this->assertEquals(1, countElementsInTable('glpi_plugin_fusioninventory_printers'), 
                          'May have a printer line in DB');
      
      $a_pfnes = getAllDatasFromTable('glpi_plugin_fusioninventory_printers');
      
      $this->assertEquals(0, $a_pfnes[1]['plugin_fusioninventory_snmpmodels_id'], 
                          'May have now no snmpmodel');
      
   }
   
   
   
   public function testLoadRightModel() {
      global $DB;

      $DB->connect();
      
      $networkEquipment = new NetworkEquipment();
      $pfNetworkEquipment = new PluginFusioninventoryNetworkEquipment();
      $pfSnmpmodel = new PluginFusioninventorySnmpmodel();
      $GLPIlog = new GLPIlogs();
      
      $sysdescr = "Cisco IOS Software, C2960 Software (C2960-LANBASEK9-M), Version 12.2(52)SE, RELEASE SOFTWARE (fc3)
Copyright (c) 1986-2009 by Cisco Systems, Inc.
Compiled Fri 25-Sep-09 08:49 by sasyamal";
      
      $networkequipments_id = $networkEquipment->add(array(
          'name'        => 'switch',
          'entities_id' => '0'
      ));
      $pfnetworkequipments_id = $pfNetworkEquipment->add(array(
          'networkequipments_id' => $networkequipments_id,
          'sysdescr' => $sysdescr
      ));
      
      // find if this model is in models list
      $a_models = getAllDatasFromTable(
              "glpi_plugin_fusioninventory_snmpmodeldevices", 
              "`sysdescr`='".str_replace("\n", "", $sysdescr)."'");
      
      $this->assertEquals(1, count($a_models), 
                          'May have our sysdescr in SNMP model list');
      $a_model = current($a_models);
      
      $pfSnmpmodel->getrightmodel($networkequipments_id, 'NetworkEquipment');
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      $pfNetworkEquipment->getFromDB($pfnetworkequipments_id);
      
      $this->assertEquals($a_model['plugin_fusioninventory_snmpmodels_id'], 
                          $pfNetworkEquipment->fields['plugin_fusioninventory_snmpmodels_id'], 
                          'May have the right model assigned to the switch');
      
   }
}



class SNMPModel_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('SNMPModel');
      return $suite;
   }
}

?>
