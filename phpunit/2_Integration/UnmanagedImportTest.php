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
   @since     2013

   ------------------------------------------------------------------------
 */

class UnmanagedImportTest extends Common_TestCase {



   /**
    * @test
    */
   public function ImportNetworkEquipment() {
      global $DB;

      self::restore_database();

      // Load session rights
      $_SESSION['glpidefault_entity'] = 0;
      Session::initEntityProfiles(2);
      Session::changeProfile(4);

      $pfUnmanaged      = new PluginFusioninventoryUnmanaged();
      $networkEquipment = new NetworkEquipment();
      $networkPort      = new NetworkPort();
      $networkName      = new NetworkName();
      $iPAddress        = new IPAddress();

      $input= array(
          'name'        => 'switch',
          'entities_id' => 0,
          'item_type'   => 'NetworkEquipment',
          'sysdescr'    => 'Cisco machin chose',
          'locations_id'=> 1,
          'is_dynamic'  => 1,
          'serial'      => 'XXS6BEF3',
          'comment'     => 'this is a comment',
          'plugin_fusioninventory_configsecurities_id' => 1
      );
      $unmanageds_id = $pfUnmanaged->add($input);

      // * Add networkport
      $input = array();
      $input['itemtype']            = 'PluginFusioninventoryUnmanaged';
      $input['items_id']            = $unmanageds_id;
      $input['instantiation_type']  = 'NetworkPortEthernet';
      $input['name']                = 'general';
      $input['mac']                 = '00:00:00:43:ae:0f';
      $input['is_dynamic']          = 1;
      $networkports_id = $networkPort->add($input);

      $input = array();
      $input['items_id']   = $networkports_id;
      $input['itemtype']   = 'NetworkPort';
      $input['name']       = '';
      $input['is_dynamic'] = 1;
      $networknames_id     = $networkName->add($input);

      $input = array();
      $input['entities_id']   = 0;
      $input['itemtype']      = 'NetworkName';
      $input['items_id']      = $networknames_id;
      $input['name']          = '192.168.20.1';
      $input['is_dynamic']    = 1;
      $iPAddress->add($input);


      $pfUnmanaged->import($unmanageds_id);

      $cnt = countElementsInTable("glpi_networkequipments");

      $this->assertEquals(1, $cnt, "May have network equipment added");

      $cnt = countElementsInTable("glpi_plugin_fusioninventory_unmanageds");

      $this->assertEquals(0, $cnt, "Unknown device may be deleted");

      $networkEquipment->getFromDB(1);

      $this->assertEquals('XXS6BEF3', $networkEquipment->fields['serial'], "Serial");
      $this->assertEquals('switch', $networkEquipment->fields['name'], "Name");
      $this->assertEquals(1, $networkEquipment->fields['is_dynamic'], "is_dynamic");
      $this->assertEquals(1, $networkEquipment->fields['locations_id'], "locations_id");
      $this->assertEquals('this is a comment', $networkEquipment->fields['comment'], "comment");

      $networkPort->getFromDB(1);
      $a_reference = array(
          'name'                 => 'general',
          'id'                   => '1',
          'items_id'             => '1',
          'itemtype'             => 'NetworkEquipment',
          'entities_id'          => '0',
          'is_recursive'         => '0',
          'logical_number'       => '0',
          'instantiation_type'   => 'NetworkPortEthernet',
          'mac'                  => '00:00:00:43:ae:0f',
          'comment'              => '',
          'is_deleted'           => '0',
          'is_dynamic'           => '1'
      );
      $this->assertEquals($a_reference, $networkPort->fields, "Networkport");
      $networkName->getFromDB(1);
      $a_reference = array(
          'id'          => '1',
          'entities_id' => '0',
          'items_id'    => '1',
          'itemtype'    => 'NetworkPort',
          'comment'     => NULL,
          'fqdns_id'    => '0',
          'is_deleted'  => '0',
          'is_dynamic'  => '1',
          'name'        => ''
      );
      $this->assertEquals($a_reference, $networkName->fields, "Networkname");
      $iPAddress->getFromDB(1);
      $a_reference = array(
          'name'        => '192.168.20.1',
          'id'          => '1',
          'entities_id' => '0',
          'items_id'    => '1',
          'itemtype'    => 'NetworkName',
          'version'     => '4',
          'binary_0'    => '0',
          'binary_1'    => '0',
          'binary_2'    => '65535',
          'binary_3'    => '3232240641',
          'is_deleted'  => '0',
          'is_dynamic'  => '1',
          'mainitems_id'  => '1',
          'mainitemtype'  => 'NetworkEquipment'
      );
      $this->assertEquals($a_reference, $iPAddress->fields, "IPAddress");

   }



   /**
    * @test
    */
   public function ImportComputer() {
      $this->mark_incomplete();
      global $DB;

      $DB->connect();


   }



   /**
    * @test
    */
   public function ImportPrinter() {
      $this->mark_incomplete();
      global $DB;

      $DB->connect();


   }

}
?>
