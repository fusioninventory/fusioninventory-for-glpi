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

class PrinterUpdate extends PHPUnit_Framework_TestCase {
   
   
   public function testPrinterGeneral() {
      global $DB;

      $DB->connect();
      
      $_SESSION["plugin_fusinvinventory_entity"] = 0;

      $a_inventory = array(
          'PluginFusioninventoryPrinter' => Array(
                  'sysdescr'                    => 'HP ETHERNET MULTI-ENVIRONMENT',
                  'last_fusioninventory_update' => date('Y-m-d H:i:s')
                ),
          'networkport'    => array(),
          'cartridge'      => array(),
          'pagecounters'   => array(),
          'itemtype'       => 'Printer'
          );
      $a_inventory['Printer'] = array(
               'name'               => 'ARC12-B09-N',
               'serial'             => 'VRG5XUT4',
               'otherserial'        => 'chr(hex(fd))chr(hex(e8))',
               'id'                 => 54,
               'manufacturers_id'   => '10',
               'locations_id'       => '102',
               'printermodels_id'   => '15',
               'memory_size'        => 64,
               'is_dynamic'         => 1,
               'have_ethernet'      => 1
      );

      $pfiPrinterLib = new PluginFusioninventoryInventoryPrinterLib();
      $printer = new Printer();
      
      $items_id = $printer->add(array('serial'      => 'VRG5XUT4',
                                      'entities_id' => 0));
      
      $pfiPrinterLib->updatePrinter($a_inventory, $items_id);
  
      $printer->getFromDB($items_id);
      unset($printer->fields['date_mod']);
      $a_reference = array(
          'name'                 => 'ARC12-B09-N',
          'serial'               => 'VRG5XUT4',
          'otherserial'          => 'chr(hex(fd))chr(hex(e8))',
          'id'                   => (string)$items_id,
          'manufacturers_id'     => '10',
          'locations_id'         => '102',
          'printermodels_id'     => '15',
          'memory_size'          => '64',
          'entities_id'          => '0',
          'is_recursive'         => '0',
          'contact'              => null,
          'contact_num'          => null,
          'users_id_tech'        => '0',
          'groups_id_tech'       => '0',
          'have_serial'          => '0',
          'have_parallel'        => '0',
          'have_usb'             => '0',
          'have_wifi'            => '0',
          'have_ethernet'        => '1',
          'comment'              => null,
          'domains_id'           => '0',
          'networks_id'          => '0',
          'printertypes_id'      => '0',
          'is_global'            => '0',
          'is_deleted'           => '0',
          'is_template'          => '0',
          'template_name'        => null,
          'init_pages_counter'   => '0',
          'last_pages_counter'   => '0',
          'notepad'              => null,
          'users_id'             => '0',
          'groups_id'            => '0',
          'states_id'            => '0',
          'ticket_tco'           => '0.0000',
          'is_dynamic'           => '1'
      );
      
      $this->assertEquals($a_reference, $printer->fields);      
   }   
 }



class PrinterUpdate_AllTests  {

   public static function suite() {

//      $Install = new Install();
//      $Install->testInstall(0);
      
      $suite = new PHPUnit_Framework_TestSuite('PrinterUpdate');
      return $suite;
   }
}

?>