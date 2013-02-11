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
   
   public $items_id = 0;
   public $datelatupdate = '';

   
   public function testAddPrinter() {
      global $DB;

      $DB->connect();
      
      $this->datelatupdate = date('Y-m-d H:i:s');
      
      $a_inventory = array(
          'PluginFusioninventoryPrinter' => Array(
                  'sysdescr'                    => 'HP ETHERNET MULTI-ENVIRONMENT',
                  'last_fusioninventory_update' => $this->datelatupdate
                ),
          'networkport' => array(),
          'cartridge'   => array(
              '63' => 60, // toner black
              '71' => 40, // toner cyan
              '79' => 80, //toner yelllow
              '75' => 100 // toner magenta
             ),
          'itemtype'    => 'Printer'
          );
      $a_inventory['Printer'] = array(
               'name'               => 'ARC12-B09-N',
               'id'                 => 54,
               'serial'             => 'VRG5XUT5',
               'otherserial'        => 'chr(hex(fd))chr(hex(e8))',
               'manufacturers_id'   => 10,
               'locations_id'       => 102,
               'printermodels_id'   => 15,
               'memory_size'        => 64,
               'is_dynamic'         => 1,
               'have_ethernet'      => 1
      );
      $a_inventory['pagecounters'] = array(
               'pages_total'        => 15134,
               'pages_n_b'          => 10007,
               'pages_color'        => 5127,
               'pages_recto_verso'  => 0,
               'pages_total_copy'   => 0,
               'scanned'            => 0,
               'pages_total_print'  => 0,
               'pages_n_b_print'    => 0,
               'pages_color_print'  => 0,
               'pages_n_b_copy'     => 0,
               'pages_color_copy'   => 0,
               'pages_total_fax'    => 0

          );

      $pfiPrinterLib = new PluginFusioninventoryInventoryPrinterLib();
      $printer = new Printer();
      
      $this->items_id = $printer->add(array('serial'      => 'VRG5XUT5',
                                            'entities_id' => 0));

      $this->assertGreaterThan(0, $this->items_id);
      
      $pfiPrinterLib->updatePrinter($a_inventory, $this->items_id);

      // To be sure not have 2 sme informations
      $pfiPrinterLib->updatePrinter($a_inventory, $this->items_id);
   
      $GLPIlog = new GLPIlogs();
      $GLPIlog->testSQLlogs();
      $GLPIlog->testPHPlogs();
   }
   
   
   public function testPrinterGeneral() {
      global $DB;

      $DB->connect();
      
      $printer = new Printer();
      
      $printer->getFromDB(1);
      unset($printer->fields['date_mod']);
      $a_reference = array(
          'name'                 => 'ARC12-B09-N',
          'serial'               => 'VRG5XUT5',
          'otherserial'          => 'chr(hex(fd))chr(hex(e8))',
          'id'                   => '1',
          'manufacturers_id'     => '10',
          'locations_id'         => '102',
          'printermodels_id'     => '15',
          'memory_size'          => '64',
          'entities_id'          => '0',
          'is_recursive'         => '0',
          'contact'              => NULL,
          'contact_num'          => NULL,
          'users_id_tech'        => '0',
          'groups_id_tech'       => '0',
          'have_serial'          => '0',
          'have_parallel'        => '0',
          'have_usb'             => '0',
          'have_wifi'            => '0',
          'have_ethernet'        => '1',
          'comment'              => NULL,
          'domains_id'           => '0',
          'networks_id'          => '0',
          'printertypes_id'      => '0',
          'is_global'            => '0',
          'is_deleted'           => '0',
          'is_template'          => '0',
          'template_name'        => NULL,
          'init_pages_counter'   => '0',
          'last_pages_counter'   => '0',
          'notepad'              => NULL,
          'users_id'             => '0',
          'groups_id'            => '0',
          'states_id'            => '0',
          'ticket_tco'           => '0.0000',
          'is_dynamic'           => '1'
      );
      
      $this->assertEquals($a_reference, $printer->fields);      
   }   
   

   
   public function testPrinterSNMPExtension() {
      global $DB;

      $DB->connect();
      
      $pfPrinter = new PluginFusioninventoryPrinter();
      $a_printer = current($pfPrinter->find("`printers_id`='1'", "", 1));
      unset($a_printer['last_fusioninventory_update']);
      $a_reference = array(
          'id'                                           => '1',
          'printers_id'                                  => '1',
          'sysdescr'                                     => 'HP ETHERNET MULTI-ENVIRONMENT',
          'plugin_fusioninventory_snmpmodels_id'         => '0',
          'plugin_fusioninventory_configsecurities_id'   => '0',
          'frequence_days'                               => '1'
      );
      
      $this->assertEquals($a_reference, $a_printer);      
      
   }

   
   
   public function testPrinterPagecounter() {
      global $DB;

      $DB->connect();

      $pfPrinterLog = new PluginFusioninventoryPrinterLog();
      
      $a_pages = $pfPrinterLog->find("`printers_id`='1'");

      $this->assertEquals(1, count($a_pages));      

   }
   
   
   
   public function testPrinterCartridgeBlack() {
      global $DB;

      $DB->connect();

      $pfPrinterCartridge = new PluginFusioninventoryPrinterCartridge();
      
      $a_cartridge = $pfPrinterCartridge->find("`printers_id`='1' 
                        AND `plugin_fusioninventory_mappings_id`='63'
                        AND `state`='60'");

      $this->assertEquals(1, count($a_cartridge));      
   }

   
   
   public function testPrinterCartridgeCyan() {
      global $DB;

      $DB->connect();

      $pfPrinterCartridge = new PluginFusioninventoryPrinterCartridge();
      
      $a_cartridge = $pfPrinterCartridge->find("`printers_id`='1' 
                        AND `plugin_fusioninventory_mappings_id`='71'
                        AND `state`='40'");

      $this->assertEquals(1, count($a_cartridge));      
   }
   
   
   
   public function testPrinterCartridgeYellow() {
      global $DB;

      $DB->connect();

      $pfPrinterCartridge = new PluginFusioninventoryPrinterCartridge();
      
      $a_cartridge = $pfPrinterCartridge->find("`printers_id`='1' 
                        AND `plugin_fusioninventory_mappings_id`='79'
                        AND `state`='80'");

      $this->assertEquals(1, count($a_cartridge));        
   }

   
   
   public function testPrinterCartridgeMagenta() {
      global $DB;

      $DB->connect();

      $pfPrinterCartridge = new PluginFusioninventoryPrinterCartridge();
      
      $a_cartridge = $pfPrinterCartridge->find("`printers_id`='1' 
                        AND `plugin_fusioninventory_mappings_id`='75'
                        AND `state`='100'");

      $this->assertEquals(1, count($a_cartridge));    
   }
   
   
   
   public function testPrinterAllCartridges() {
      global $DB;

      $DB->connect();

      $pfPrinterCartridge = new PluginFusioninventoryPrinterCartridge();
      
      $a_cartridge = $pfPrinterCartridge->find("");

      $this->assertEquals(4, count($a_cartridge));    
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