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

class NetworkEquipmentUpdateDiscovery extends PHPUnit_Framework_TestCase {
   
   public $items_id = 0;
   public $datelatupdate = '';

   
   public function testAddNetworkEquipment() {
      global $DB;

      $DB->connect();
      
      $Install = new Install();
      $Install->testInstall(0);

      $pfCND = new PluginFusioninventoryCommunicationNetworkDiscovery();
      $networkEquipment = new NetworkEquipment();
      $GLPIlog          = new GLPIlogs();
      
      $input = array(
          'name'        => 'switch H3C',
          'entities_id' => '0'
      );
      $id = $networkEquipment->add($input);
      $networkEquipment->getFromDB($id);

      $_SESSION['SOURCE_XMLDEVICE'] = array(
          'SNMPHOSTNAME' => 'switch H3C',
          'DESCRIPTION' => 'H3C Comware Platform Software, Software Version 5.20 Release 2208',
          'AUTHSNMP' => '1',
          'IP' => '99.99.10.10',
          'MAC' => '38:22:d6:3c:da:e7',
          'MANUFACTURER' => 'H3C'
      );
      
      $pfCND->importDevice($networkEquipment);
   
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      $a_networkports = getAllDatasFromTable('glpi_networkports');
      
      $a_referenceNetworkports = array(
          1 => array(
              'id'                  => '1',
              'items_id'            => '1',
              'itemtype'            => 'NetworkEquipment',
              'entities_id'         => '0',
              'is_recursive'        => '0',
              'logical_number'      => '0',
              'name'                => 'management',
              'instantiation_type'  => 'NetworkPortAggregate',
              'mac'                 => '38:22:d6:3c:da:e7',
              'comment'             => null,
              'is_deleted'          => '0',
              'is_dynamic'          => '0'
          )
      );
      
      $this->assertEquals($a_referenceNetworkports, 
                          $a_networkports, 
                          "List of network ports on first update");

      $a_ipaddresses = getAllDatasFromTable('glpi_ipaddresses');
      
      $a_referenceips = array(
          1 => array(
              'id'            => '1',
              'entities_id'   => '0',
              'items_id'      => '1',
              'itemtype'      => 'NetworkName',
              'version'       => '4',
              'name'          => '99.99.10.10',
              'binary_0'      => '0',
              'binary_1'      => '0',
              'binary_2'      => '65535',
              'binary_3'      => '1667435018',
              'is_deleted'    => '0',
              'is_dynamic'    => '0',
          )
      );
      
      $this->assertEquals($a_referenceips, 
                          $a_ipaddresses, 
                          "List of IPs on first update");

      
      // Update 2nd time
      $networkEquipment->getFromDB($id);
      $pfCND->importDevice($networkEquipment);
   
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();

      $a_networkports = getAllDatasFromTable('glpi_networkports');
      
      $this->assertEquals($a_referenceNetworkports, 
                          $a_networkports, 
                          "List of network ports on second update");

      $a_ipaddresses = getAllDatasFromTable('glpi_ipaddresses');
      
      $this->assertEquals($a_referenceips, 
                          $a_ipaddresses, 
                          "List of IPs on second update");
      
   }

}



class NetworkEquipmentUpdateDiscovery_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('NetworkEquipmentUpdateDiscovery');
      return $suite;
   }
}

?>