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

class ComputerDynamic extends PHPUnit_Framework_TestCase {
   
   
   public function testUpdateComputerManuallyAdded() {
      global $DB;

      $DB->connect();
    
      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusinvinventory_entity"] = 0;
      
      $pfiComputerLib  = new PluginFusioninventoryInventoryComputerLib();
      $computer = new Computer();
      $computerDisk = new ComputerDisk();
      
      $a_computerinventory = array(
          "computer" => array(
              "name"   => "pc002",
              "serial" => "ggheb7ne7"
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
          'memory'         => Array(),
          'monitor'        => Array(),
          'printer'        => Array(),
          'peripheral'     => Array(),
          'networkport'    => Array(),
          'software'       => Array(),
          'harddrive'      => Array(),
          'virtualmachine' => Array(),
          'antivirus'      => Array(),
          'storage'        => Array(),
          'itemtype'       => 'Computer'
      );
      
      $a_computer = $a_computerinventory['computer'];
      $a_computer["entities_id"] = 0;
      
      $computers_id = $computer->add($a_computer);
      $a_cdisk = array(
          "computers_id" => $computers_id,
          "name"         => "D:",
          "mountpoint"   => "D:",
          "entities_id"  => 0
      );
      $computerdisks_id = $computerDisk->add($a_cdisk);
      
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
   
   
   
   public function testUpdateComputerFusioninventoryAdded() {
      global $DB;

      $DB->connect();
      
      // Add manually a computerdisk
      
      $_SESSION['glpiactive_entity'] = 0;
      $_SESSION["plugin_fusinvinventory_entity"] = 0;
      
      $pfiComputerLib  = new PluginFusioninventoryInventoryComputerLib();
      $computer = new Computer();
      $computerDisk = new ComputerDisk();
      
      $a_computerinventory = array(
          "computer" => array(
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
          'memory'         => Array(),
          'monitor'        => Array(),
          'printer'        => Array(),
          'peripheral'     => Array(),
          'networkport'    => Array(),
          'software'       => Array(),
          'harddrive'      => Array(),
          'virtualmachine' => Array(),
          'antivirus'      => Array(),
          'storage'        => Array(),
          'itemtype'       => 'Computer'
      );

      $a_computer = $a_computerinventory['computer'];
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
      $computerdisks_id = $computerDisk->add($a_cdisk);

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
 }



class ComputerDynamic_AllTests  {

   public static function suite() {
    
      $suite = new PHPUnit_Framework_TestSuite('ComputerDynamic');
      return $suite;
   }
}

?>