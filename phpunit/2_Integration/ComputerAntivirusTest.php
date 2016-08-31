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
   @author    Johan Cwiklinski
   @co-author
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2016

   ------------------------------------------------------------------------
 */

class ComputerAntivirusTest extends RestoreDatabase_TestCase {
   public $a_computer1 = array();
   public $a_computer1_beforeformat = array();

   function __construct() {
      $this->a_computer1 = array(
          "Computer" => array(
              "name"   => "pc001",
              "serial" => "ggheb7ne7"
          ),
          "fusioninventorycomputer" => Array(
              'last_fusioninventory_update' => date('Y-m-d H:i:s'),
              'serialized_inventory'        => 'something'
          ),
          'soundcard'      => array(),
          'graphiccard'    => array(),
          'controller'     => array(),
          'processor'      => array(),
          "computerdisk"   => array(),
          'memory'         => array(),
          'monitor'        => array(),
          'printer'        => array(),
          'peripheral'     => array(),
          'networkport'    => array(),
          'software'       => array(),
          'harddrive'      => array(),
          'virtualmachine' => array(),
          'antivirus'      => array(
             array(
                'name'              => 'Trend Micro Security Agent',
                'manufacturers_id'  => '',
                'antivirus_version' => '',
                'is_active'         => '1',
                'is_uptodate'       => '1'
             )
          ),
          'storage'        => array(),
          'licenseinfo'    => array(),
          'networkcard'    => array(),
          'drive'          => array(),
          'batteries'      => array(),
          'remote_mgmt'    => array(),
          'itemtype'       => 'Computer'
      );

      $this->a_computer1_beforeformat = array(
          "CONTENT" => array(
              "HARDWARE" => array(
                  "NAME"   => "pc001"
              ),
              "BIOS" => array(
                  "SSN" => "ggheb7ne7"
              ),
              'ANTIVIRUS' => array(
                 'ENABLED'  => 1,
                 'GUID'     => '{8242D66F-41BD-4049-C2E6-E578E73B62A0}',
                 'NAME'     => 'Trend Micro Security Agent',
                 'UPTODATE' => 1
              )
          )
      );
   }



   /**
    * @test
    */
   public function Antiviruses() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();

      $a_computerinventory = $this->a_computer1;
      $a_computer = $a_computerinventory['Computer'];
      $a_computer["entities_id"] = 0;
      $computers_id = $computer->add($a_computer);

      $pfiComputerLib->updateComputer(
         $a_computerinventory,
         $computers_id,
         FALSE,
         1
      );

      $computer->getFromDB(1);
      $this->assertEquals('ggheb7ne7', $computer->fields['serial'], 'Computer not updated correctly');

      $this->assertEquals(
         1,
         countElementsInTable('glpi_computerantiviruses'),
         'Antivirus may be added in core table'
      );

      $pfComputerAntivirus = new ComputerAntivirus();
      $pfComputerAntivirus->getFromDB(1);
      $date = $pfComputerAntivirus->fields['date_creation'];
      $a_ref = array(
          'id'                  => '1',
          'computers_id'        => '1',
          'name'                => 'Trend Micro Security Agent',
          'manufacturers_id'    => '0',
          'antivirus_version'   => '',
          'signature_version'   => null,
          'is_active'           => '1',
          'is_deleted'          => '0',
          'is_uptodate'         => '1',
          'is_dynamic'          => '1',
          'date_mod'            => $date,
          'date_creation'       => $date,
          'date_expiration'     => null
      );

      $this->assertEquals(
         $a_ref,
         $pfComputerAntivirus->fields,
         'Antivirus data'
      );

      //update antivirus
      $a_computerinventory['antivirus'][0]['is_active'] = '0';
      $a_computerinventory['antivirus'][0]['is_uptodate'] = '0';

      $pfiComputerLib->updateComputer(
         $a_computerinventory,
         $computers_id,
         FALSE,
         1
      );

      $pfComputerAntivirus->getFromDB(1);
      $a_ref = array(
          'id'                  => '1',
          'computers_id'        => '1',
          'name'                => 'Trend Micro Security Agent',
          'manufacturers_id'    => '0',
          'antivirus_version'   => '',
          'signature_version'   => null,
          'is_active'           => '0',
          'is_deleted'          => '0',
          'is_uptodate'         => '0',
          'is_dynamic'          => '1',
          'date_mod'            => $date,
          'date_creation'       => $date,
          'date_expiration'     => null
      );

      $this->assertEquals(
         $a_ref,
         $pfComputerAntivirus->fields,
         'Antivirus updated data'
      );
   }
}
