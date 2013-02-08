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

class PrinterTransformation extends PHPUnit_Framework_TestCase {
   
   
   public function testPrinterGeneral() {
      global $DB;

      $DB->connect();
      
      $_SESSION["plugin_fusinvinventory_entity"] = 0;

      $a_computer = array();
      $a_computer['INFO'] = array(
                'COMMENTS'       => 'HP ETHERNET MULTI-ENVIRONMENT',
                'ID'             => '54',
                'LOCATION'       => 'Room 102',
                'MANUFACTURER'   => 'Hewlett Packard',
                'MODEL'          => 'HP LaserJet P1505n',
                'NAME'           => 'ARC12-B09-N',
                'SERIAL'         => 'VRG5XUT4',
                'OTHERSERIAL'    => 'chr(hex(fd))chr(hex(e8))',
                'TYPE'           => 'PRINTER',
                'MEMORY'         => 64
            );
      
      $pfFormatconvert = new PluginFusioninventoryFormatconvert();
      
      $a_return = $pfFormatconvert->printerInventoryTransformation($a_computer);
      $date = date('Y-m-d H:i:s');
      if (isset($a_return['PluginFusioninventoryPrinter'])
              && isset($a_return['PluginFusioninventoryPrinter']['last_fusioninventory_update'])) {
         $date = $a_return['PluginFusioninventoryPrinter']['last_fusioninventory_update'];
      }
      $a_reference = array(
          'PluginFusioninventoryPrinter' => Array(
                  'sysdescr'                    => 'HP ETHERNET MULTI-ENVIRONMENT',
                  'last_fusioninventory_update' => $date
                ),
          'networkport' => array(),
          'itemtype'    => 'Printer'
          );
      $a_reference['Printer'] = array(
               'name'               => 'ARC12-B09-N',
               'serial'             => 'VRG5XUT4',
               'otherserial'        => 'chr(hex(fd))chr(hex(e8))',
               'id'                 => 54,
               'manufacturers_id'   => 'Hewlett Packard',
               'locations_id'       => 'Room 102',
               'printermodels_id'   => 'HP LaserJet P1505n',
               'memory_size'        => 64
      );
      $this->assertEquals($a_reference, $a_return);      
   }   
   
   
   
   public function testPrinterPageCounter() {

   }  
   
   
   
   public function testPrinterCartridge() {

   }  

}



class PrinterTransformation_AllTests  {

   public static function suite() {

//      $Install = new Install();
//      $Install->testInstall(0);
      
      $suite = new PHPUnit_Framework_TestSuite('PrinterTransformation');
      return $suite;
   }
}

?>