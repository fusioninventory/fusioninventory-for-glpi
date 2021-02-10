<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2021 by the FusionInventory Development Team.

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
   @copyright Copyright (C) 2010-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class PluginFusioninventoryCommunicationNetworkInventoryTest extends TestCase {

   public $items_id = 0;
   public $datelatupdate = '';

   public $stack1 = [
      'components' => [
         1 => [
            'comment'          => 'Catalyst 37xx Switch Stack',
            'fru'              => 2,
            'index'            => 1,
            'name'             => 'Cat37xx Stacking',
            'type'             => 'stack',
            'serial'           => '',
            'model'            => '',
            'manufacturers_id' => '',
            'firmware'         => '',
            'revision'         => '',
            'version'          => '',
            'parent_index'     => '',
            'mac'              => '',
            'ip'               => ''
         ],
         1001 => [
            'parent_index'     => 1,
            'comment'          => 'WS-C3750E-48TD',
            'firmware'         => '12.2(58)SE2',
            'fru'              => 1,
            'index'            => 1001,
            'model'            => 'WS-C3750E-48TD-S',
            'name'             => 1,
            'revision'         => 'V01',
            'serial'           => 'CAT11145RH75G8',
            'type'             => 'chassis',
            'version'          => '12.2(58)SE2',
            'manufacturers_id' => '',
            'mac'              => '',
            'ip'               => ''
         ],
         2001 => [
            'parent_index'     => 1,
            'comment'          => 'WS-C3750E-48TD',
            'fru'              => 1,
            'index'            => 2001,
            'name'             => '2',
            'type'             => 'chassis',
            'serial'           => 'CAT4897H573R',
            'model'            => 'WS-C3750E-48TD-S',
            'manufacturers_id' => '',
            'firmware'         => '12.2(58)SE2',
            'revision'         => 'V01',
            'version'          => '12.2(58)SE2',
            'mac'              =>  '',
            'ip'               => ''
         ]
      ]
   ];
   public $stack2 = [
      'components' => [
         569 => [
            'comment'          => '24 G SFP 2 10G',
            'fru'              => 1,
            'index'            => 569,
            'name'             => 'Virtual Chassis',
            'type'             => 'chassis',
            'serial'           => 'N05754783',
            'model'            => 'OS6850E-U24X',
            'manufacturers_id' => 'ALCATEL',
            'firmware'         => '',
            'revision'         => '10',
            'version'          => '6.4.4.585.R01',
            'parent_index'     => '',
            'mac'              => '',
            'ip'               => ''
         ],
         570 => [
            'comment'          => '24 G 2 10G',
            'fru'              => 1,
            'index'            => 570,
            'name'             => 'Virtual Chassis',
            'type'             => 'chassis',
            'serial'           => 'M8476365',
            'model'            => 'OS6850E-24X',
            'manufacturers_id' => 'ALCATEL',
            'firmware'         => '',
            'revision'         => '07',
            'version'          => '6.4.4.585.R01',
            'parent_index'     => '',
            'mac'              => '',
            'ip'               => ''
         ],
      ]
   ];



   /**
    * @test
    */
   public function get_stacked_switches_information_teststacktype1() {
      $inventory = $this->stack1;
      $pfcni = new PluginFusioninventoryCommunicationNetworkInventory();
      $stack_detection = $pfcni->get_stacked_switches_information($inventory);
      $this->assertCount(2, $stack_detection);
   }

   /**
    * @test
    */
   public function get_stacked_switches_information_testnostacktype1() {
      $inventory = $this->stack1;
      unset($inventory['components'][2001]);
      $pfcni = new PluginFusioninventoryCommunicationNetworkInventory();
      $stack_detection = $pfcni->get_stacked_switches_information($inventory);
      $this->assertCount(1, $stack_detection);
   }

   /**
    * @test
    */
   public function get_stacked_switches_information_teststacktype2() {
      $inventory = $this->stack2;
      $pfcni = new PluginFusioninventoryCommunicationNetworkInventory();
      $stack_detection = $pfcni->get_stacked_switches_information($inventory);
      $this->assertCount(2, $stack_detection);
   }

   /**
    * @test
    */
   public function get_stacked_switches_information_testnostacktype2() {
      $inventory = $this->stack2;
      unset($inventory['components'][570]);
      $pfcni = new PluginFusioninventoryCommunicationNetworkInventory();
      $stack_detection = $pfcni->get_stacked_switches_information($inventory);
      $this->assertCount(1, $stack_detection);
   }

   /**
    * @test
    */
   public function is_stacked_switch_teststacktype1() {
      $inventory = $this->stack1;
      $pfcni = new PluginFusioninventoryCommunicationNetworkInventory();
      $stack_detection = $pfcni->is_stacked_switch($inventory);
      $this->assertTrue($stack_detection);
   }

   /**
    * @test
    */
   public function is_stacked_switch_testnostacktype1() {
      $inventory = $this->stack1;
      unset($inventory['components'][2001]);
      $pfcni = new PluginFusioninventoryCommunicationNetworkInventory();
      $stack_detection = $pfcni->is_stacked_switch($inventory);
      $this->assertFalse($stack_detection);
   }

   /**
    * @test
    */
   public function is_stacked_switch_teststacktype2() {
      $inventory = $this->stack2;
      $pfcni = new PluginFusioninventoryCommunicationNetworkInventory();
      $stack_detection = $pfcni->is_stacked_switch($inventory);
      $this->assertTrue($stack_detection);
   }

   /**
    * @test
    */
   public function is_stacked_switch_testnostacktype2() {
      $inventory = $this->stack2;
      unset($inventory['components'][570]);
      $pfcni = new PluginFusioninventoryCommunicationNetworkInventory();
      $stack_detection = $pfcni->is_stacked_switch($inventory);
      $this->assertFalse($stack_detection);
   }
}
