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

class ComputerDynamic extends RestoreDatabase_TestCase {


   /**
    * @test
    */
   public function UpdateComputerManuallyAdded() {
      global $DB;

      $DB->connect();

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfiComputerLib  = new PluginFusioninventoryInventoryComputerLib();
      $computer = new Computer();
      $computerDisk = new ComputerDisk();

      $a_computerinventory = array(
          "Computer" => array(
              "name"   => "pc002",
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
          "computerdisk"   => array(
              array(
                 "freesize"   => 259327,
                 "totalsize"  => 290143,
                 "device"     => '',
                 "name"       => "C:",
                 "mountpoint" => "C:"
              )
          ),
          'memory'         => array(),
          'monitor'        => array(),
          'printer'        => array(),
          'peripheral'     => array(),
          'networkport'    => array(),
          'software'       => array(),
          'harddrive'      => array(),
          'virtualmachine' => array(),
          'antivirus'      => array(),
          'storage'        => array(),
          'licenseinfo'    => array(),
          'networkcard'    => array(),
          'drive'          => Array(),
          'batteries'      => Array(),
          'itemtype'       => 'Computer'
      );

      $a_computer = $a_computerinventory['Computer'];
      $a_computer["entities_id"] = 0;

      $computers_id = $computer->add($a_computer);
      $a_cdisk = array(
          "computers_id" => $computers_id,
          "name"         => "D:",
          "mountpoint"   => "D:",
          "entities_id"  => 0
      );
      $computerDisk->add($a_cdisk);

      $a_computerdisk = $computerDisk->find("`computers_id`='".$computers_id."'");
      $this->assertEquals(1, count($a_computerdisk), 'Right no dynamic added');

      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computers_id,
                                      FALSE,
                                      1);

      $a_computerdisk = $computerDisk->find("`computers_id`='".$computers_id."'");
      $this->assertEquals(1, count($a_computerdisk), 'May have only 1 computerdisk');

      $a_computerdisk = $computerDisk->find("`computers_id`='".$computers_id."'
         AND `is_dynamic`='1'");
      $this->assertEquals(1, count($a_computerdisk), 'May have only 1 computerdisk and is dynamic');
   }


   /**
    * @test
    */
   public function UpdateComputerFusioninventoryAdded() {
      global $DB;

      $DB->connect();

      // Add manually a computerdisk

      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfiComputerLib  = new PluginFusioninventoryInventoryComputerLib();
      $computer = new Computer();
      $computerDisk = new ComputerDisk();

      $a_computerinventory = array(
          "Computer" => array(
              "name"   => "pc002",
              "serial" => "ggheb7ne72"
          ),
          "fusioninventorycomputer" => Array(
              'last_fusioninventory_update' => date('Y-m-d H:i:s'),
              'serialized_inventory'        => 'something'
          ),
          'soundcard'      => Array(),
          'graphiccard'    => Array(),
          'controller'     => Array(),
          'processor'      => Array(),
          "computerdisk" => array(
              array(
                 "freesize"   => 259327,
                 "totalsize"  => 290143,
                 "device"     => '',
                 "name"       => "C:",
                 "mountpoint" => "C:"
              )
          ),
          'memory'         => array(),
          'monitor'        => array(),
          'printer'        => array(),
          'peripheral'     => array(),
          'networkport'    => array(),
          'software'       => array(),
          'harddrive'      => array(),
          'virtualmachine' => array(),
          'antivirus'      => array(),
          'storage'        => array(),
          'licenseinfo'    => array(),
          'networkcard'    => array(),
          'drive'          => Array(),
          'batteries'      => Array(),
          'itemtype'       => 'Computer'
      );

      $a_computer = $a_computerinventory['Computer'];
      $a_computer["entities_id"] = 0;

      $computers_id = $computer->add($a_computer);

      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computers_id,
                                      FALSE,
                                      0);

      $a_cdisk = array(
          "computers_id" => $computers_id,
          "name"         => "D:",
          "mountpoint"   => "D:",
          "entities_id"  => 0
      );
      $computerDisk->add($a_cdisk);

      $a_computerdisk = $computerDisk->find("`computers_id`='".$computers_id."'");
      $this->assertEquals(2, count($a_computerdisk), 'May have dynamic + no dynamic computerdisk');

      $a_computerdisk = $computerDisk->find("`computers_id`='".$computers_id."'
         AND `is_dynamic`='0'");
      $this->assertEquals(1, count($a_computerdisk), '(1)Not dynamic');

      $a_computerdisk = $computerDisk->find("`computers_id`='".$computers_id."'
         AND `is_dynamic`='1'");
      $this->assertEquals(1, count($a_computerdisk), '(2)Dynamic');

      $pfiComputerLib->updateComputer($a_computerinventory,
                                      $computers_id,
                                      FALSE,
                                      1);

      $a_computerdisk = $computerDisk->find("`computers_id`='".$computers_id."'");
      $this->assertEquals(2, count($a_computerdisk), 'May ALWAYS have dynamic '.
                                                     '+ no dynamic computerdisk');

      $a_computerdisk = $computerDisk->find("`computers_id`='".$computers_id."'
         AND `is_dynamic`='0'");
      $this->assertEquals(1, count($a_computerdisk), '(3)Not dynamic');

      $a_computerdisk = $computerDisk->find("`computers_id`='".$computers_id."'
         AND `is_dynamic`='1'");
      $this->assertEquals(1, count($a_computerdisk), '(4)Dynamic');
   }


   /**
    * @test
    */
   public function UpdateComputerRemoveProcessor() {
      global $DB;

      $DB->connect();

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $a_inventory = array(
          'Computer' => array(
             'name'                             => 'pcxxx1',
             'users_id'                         => 0,
             'operatingsystems_id'              => 0,
             'operatingsystemversions_id'       => 0,
             'uuid'                             => '68405E00-E5BE-11DF-801C-B05981261220',
             'domains_id'                       => 0,
             'manufacturers_id'                 => 0,
             'computermodels_id'                => 0,
             'serial'                           => 'XB63J7DH',
             'computertypes_id'                 => 0,
             'is_dynamic'                       => 1,
             'contact'                          => 'ddurieux'
          ),
          'fusioninventorycomputer' => Array(
              'winowner'                        => 'test',
              'wincompany'                      => 'siprossii',
              'operatingsystem_installationdate'=> '2012-10-16 08:12:56',
              'last_fusioninventory_update'     => date('Y-m-d H:i:s')
          ),
          'soundcard'      => Array(),
          'graphiccard'    => Array(),
          'controller'     => Array(),
          'processor'      => Array(
            Array(
                    'manufacturers_id'  => 0,
                    'designation'       => 'Core i3',
                    'frequence'         => 2400,
                    'serial'            => '',
                    'frequency'         => 2400,
                    'frequence'         => 2400,
                    'frequency_default' => 2400
                ),
            Array(
                    'manufacturers_id'  => 0,
                    'designation'       => 'Core i3',
                    'frequence'         => 2400,
                    'serial'            => '',
                    'frequency'         => 2400,
                    'frequence'         => 2400,
                    'frequency_default' => 2400
                )
            ),
          'computerdisk'   => array(),
          'memory'         => array(),
          'monitor'        => array(),
          'printer'        => array(),
          'peripheral'     => array(),
          'networkport'    => array(),
          'software'       => array(),
          'harddrive'      => array(),
          'virtualmachine' => array(),
          'antivirus'      => array(),
          'storage'        => array(),
          'licenseinfo'    => array(),
          'networkcard'    => array(),
          'drive'          => Array(),
          'batteries'      => Array(),
          'itemtype'       => 'Computer'
          );

      $computer         = new Computer();
      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $item_DeviceProcessor = new Item_DeviceProcessor();

      $computers_id = $computer->add(array('serial'      => 'XB63J7DH',
                                           'entities_id' => 0));

      $_SESSION['glpiactive_entity'] = 0;
      $pfiComputerLib->updateComputer($a_inventory, $computers_id, FALSE);

      $a_processors = $item_DeviceProcessor->find("`items_id`='".$computers_id."'
         AND `itemtype`='Computer'");
      $this->assertEquals(2, count($a_processors), 'May have the 2 Processors');

      // Remove one processor from inventory
      unset($a_inventory['processor'][1]);
      $pfiComputerLib->updateComputer($a_inventory, $computers_id, FALSE);

      $a_processors = $item_DeviceProcessor->find("`items_id`='".$computers_id."'
         AND `itemtype`='Computer'");
      $this->assertEquals(1, count($a_processors), 'May have the only 1 processor after
                           deleted a processor from inventory');
   }
}
?>
