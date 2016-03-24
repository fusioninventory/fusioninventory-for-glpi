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

class ComputerPeripheral extends RestoreDatabase_TestCase {
   public $a_computer1_XML = array();

   function __construct() {

      $this->a_computer1_XML =
"<REQUEST>
   <CONTENT>
    <HARDWARE>
       <NAME>pc001</NAME>
    </HARDWARE>
    <BIOS>
       <SSN>ggheb7ne7</SSN>
    </BIOS>
    <USBDEVICES>
      <NAME>Intel(R) Centrino(R) Wireless Bluetooth(R) 4.0 + High Speed Adapter</NAME>
      <PRODUCTID>07DA</PRODUCTID>
      <VENDORID>8087</VENDORID>
    </USBDEVICES>
    <USBDEVICES>
      <NAME>Generic USB Hub</NAME>
      <PRODUCTID>0024</PRODUCTID>
      <VENDORID>8087</VENDORID>
    </USBDEVICES>
    <USBDEVICES>
      <NAME>Périphérique USB composite</NAME>
      <PRODUCTID>4302</PRODUCTID>
      <SERIAL>10075973</SERIAL>
      <VENDORID>17E9</VENDORID>
    </USBDEVICES>
    <USBDEVICES>
      <NAME>Concentrateur USB générique</NAME>
      <PRODUCTID>3431</PRODUCTID>
      <VENDORID>2109</VENDORID>
    </USBDEVICES>
    <USBDEVICES>
      <NAME>Périphérique USB composite</NAME>
      <PRODUCTID>B315</PRODUCTID>
      <VENDORID>04F2</VENDORID>
    </USBDEVICES>
    <USBDEVICES>
      <NAME>Périphérique d’entrée USB</NAME>
      <PRODUCTID>3025</PRODUCTID>
      <VENDORID>04B3</VENDORID>
    </USBDEVICES>
    <USBDEVICES>
      <NAME>Concentrateur USB générique</NAME>
      <PRODUCTID>3431</PRODUCTID>
      <VENDORID>2109</VENDORID>
    </USBDEVICES>
    <USBDEVICES>
      <NAME>Périphérique d’entrée USB</NAME>
      <PRODUCTID>6019</PRODUCTID>
      <VENDORID>17EF</VENDORID>
    </USBDEVICES>
    <USBDEVICES>
      <NAME>Périphérique USB composite</NAME>
      <PRODUCTID>8206</PRODUCTID>
      <VENDORID>03EB</VENDORID>
    </USBDEVICES>
    <USBDEVICES>
      <NAME>USB-IF USB 3.0 Hub</NAME>
      <PRODUCTID>1111</PRODUCTID>
      <VENDORID>8086</VENDORID>
    </USBDEVICES>
    <USBDEVICES>
      <NAME>Concentrateur USB SuperSpeed générique</NAME>
      <PRODUCTID>0811</PRODUCTID>
      <VENDORID>2109</VENDORID>
    </USBDEVICES>
    <USBDEVICES>
      <NAME>Generic USB Hub</NAME>
      <PRODUCTID>0024</PRODUCTID>
      <VENDORID>8087</VENDORID>
    </USBDEVICES>
    <USBDEVICES>
      <NAME>H5321 gw Mobile Broadband Device</NAME>
      <PRODUCTID>1926</PRODUCTID>
      <SERIAL>187A047919938CM0</SERIAL>
      <VENDORID>0BDB</VENDORID>
    </USBDEVICES>
    <USBDEVICES>
      <NAME>Périphérique d’entrée USB</NAME>
      <PRODUCTID>91D1</PRODUCTID>
      <SERIAL>STM32_EMOTION2</SERIAL>
      <VENDORID>0483</VENDORID>
    </USBDEVICES>
    <USBDEVICES>
      <NAME>Concentrateur USB SuperSpeed générique</NAME>
      <PRODUCTID>0811</PRODUCTID>
      <VENDORID>2109</VENDORID>
    </USBDEVICES>
</CONTENT>
</REQUEST>";

   }



   /**
    * @test
    */
   public function PeripheralUniqueSerialimport() {
      global $DB;

      $DB->connect();

      self::restore_database();
      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfConfig         = new PluginFusioninventoryConfig();
      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();
      $GLPIlog          = new GLPIlogs();

      $pxml = @simplexml_load_string($this->a_computer1_XML, 'SimpleXMLElement', LIBXML_NOCDATA);

      $arrayinventory = PluginFusioninventoryFormatconvert::XMLtoArray($pxml);

      $agent = new PluginFusioninventoryAgent();
      $agents_id = $agent->importToken($arrayinventory);
      $_SESSION['plugin_fusioninventory_agents_id'] = $agents_id;

      $pfInventoryComputerInventory = new PluginFusioninventoryInventoryComputerInventory();
      $pfInventoryComputerInventory->import('deviceid',
                                            $arrayinventory['CONTENT'],
                                            $arrayinventory);

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly');

      $a_ref = array(
          1 => array(
              'name' => 'Périphérique USB composite',
              'id'                  => '1',
              'serial'              => '10075973',
              'peripheraltypes_id'  => '0',
              'peripheralmodels_id' => '0',
              'manufacturers_id'    => '2',
              'is_global'           => '0',
              'is_deleted'          => '0',
              'is_template'         => '0',
              'is_dynamic'          => '1'
          ),
          2 => array(
              'name' => 'H5321 gw Mobile Broadband Device',
              'id'                  => '2',
              'serial'              => '187A047919938CM0',
              'peripheraltypes_id'  => '0',
              'peripheralmodels_id' => '0',
              'manufacturers_id'    => '8',
              'is_global'           => '0',
              'is_deleted'          => '0',
              'is_template'         => '0',
              'is_dynamic'          => '1'
          ),
          3 => array(
              'name' => 'Périphérique d’entrée USB',
              'id'                  => '3',
              'serial'              => 'STM32_EMOTION2',
              'peripheraltypes_id'  => '0',
              'peripheralmodels_id' => '0',
              'manufacturers_id'    => '9',
              'is_global'           => '0',
              'is_deleted'          => '0',
              'is_template'         => '0',
              'is_dynamic'          => '1'
          )
      );

      $a_db_peripherals = getAllDatasFromTable('glpi_peripherals');
      foreach ($a_db_peripherals as $id=>$data) {
         $data_temp = array(
              'name'                => $data['name'],
              'id'                  => $data['id'],
              'serial'              => $data['serial'],
              'peripheraltypes_id'  => $data['peripheraltypes_id'],
              'peripheralmodels_id' => $data['peripheralmodels_id'],
              'manufacturers_id'    => $data['manufacturers_id'],
              'is_global'           => $data['is_global'],
              'is_deleted'          => $data['is_deleted'],
              'is_template'         => $data['is_template'],
              'is_dynamic'          => $data['is_dynamic']
         );
         $a_db_peripherals[$id] = $data_temp;
      }

      $this->assertEquals($a_ref, $a_db_peripherals, 'List of peripherals');

      // Update computer and may not have new values in glpi_logs
      $query = "SELECT * FROM `glpi_logs`
         ORDER BY `id` DESC LIMIT 1";

      $result = $DB->query($query);
      $data = $DB->fetch_assoc($result);
      $last_id = $data['id'];
      $pfInventoryComputerInventory->import('deviceid',
                                            $arrayinventory['CONTENT'],
                                            $arrayinventory);

      $data = getAllDatasFromTable('glpi_logs', "`id`>'".$last_id."'");
      $this->assertEquals(array(), $data, 'On update peripherals, may not have new lines in glpi_logs');

   }
}
?>
