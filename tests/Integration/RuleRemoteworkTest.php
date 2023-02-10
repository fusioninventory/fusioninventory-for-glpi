<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2023 by the FusionInventory Development Team.

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
   @copyright Copyright (C) 2010-2023 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2013

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class RuleRemoteworkTest extends TestCase {


   public static function setUpBeforeClass(): void {

      // Delete all remoteworkrules
      $rule = new Rule();
      $items = $rule->find(['sub_type' => "PluginFusioninventoryInventoryRemoteworkImport"]);
      foreach ($items as $item) {
         $rule->delete(['id' => $item['id']], true);
      }
   }

   public static function tearDownAfterClass(): void {
      // Delete all remoteworkrules
      $rule = new Rule();
      $items = $rule->find(['sub_type' => "PluginFusioninventoryInventoryRemoteworkImport"]);
      foreach ($items as $item) {
         $rule->delete(['id' => $item['id']], true);
      }
   }

   function setUp(): void {

   }


   /**
    * @test
    */
   function addRemoteworkRule() {

      $rule = new Rule();
      // Add a rule test check model
      $input = [
         'is_active' => 1,
         'name'      => 'remote work rangeIP',
         'match'     => 'AND',
         'sub_type'  => 'PluginFusioninventoryInventoryRuleRemotework',
         'ranking'   => 198,
      ];
      $rule_id = $rule->add($input);
      $this->assertNotFalse($rule_id);

      // Add criteria
      $rulecriteria = new RuleCriteria();
      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => 'ip',
         'pattern'   => '192.168.0.1/24',
         'condition' => 333
      ];
      $ret = $rulecriteria->add($input);
      $this->assertNotFalse($ret);

      // Add action
      $ruleaction = new RuleAction();
      $input = [
         'rules_id'    => $rule_id,
         'action_type' => 'assign',
         'field'       => '_ignore_external_devices',
         'value'       => '1'
      ];
      $ret = $ruleaction->add($input);
      $this->assertNotFalse($ret);
   }


   /**
    * @test
    */
   public function computerImport() {
      $computerData = [
         'Computer' => [
             'name'   => 'pc010',
             'serial' => 'ggheb7hy6'
         ],
         'fusioninventorycomputer' => [
             'last_fusioninventory_update' => date('Y-m-d H:i:s'),
             'serialized_inventory'        => 'something'
         ],
         'soundcard'      => [],
         'graphiccard'    => [],
         'controller'     => [],
         'processor'      => [],
         'computerdisk'   => [],
         'memory'         => [],
         'monitor'        => [
            [
               'name'             => 'DELL E1911',
               'manufacturers_id' => 2,
               'serial'           => 'W6VPJ18475H',
               'is_dynamic'       => 1
            ]
         ],
         'printer'        => [],
         'peripheral'     => [],
         'networkport'    => [
            'em0-cc:f9:54:a1:03:45' => [
               'name'                 => 'em0',
               'netmask'              => '255.255.255.0',
               'subnet'               => '192.168.0.0',
               'mac'                  => 'cc:f9:54:a1:03:45',
               'instantiation_type'   => 'NetworkPortEthernet',
               'virtualdev'           => 0,
               'ssid'                 => '',
               'gateway'              => '',
               'dhcpserver'           => '',
               'logical_number'       => 1,
               'ipaddress'            => ['192.168.0.198']
            ]
         ],
         'software'       => [],
         'harddrive'      => [],
         'virtualmachine' => [],
         'antivirus'      => [],
         'storage'        => [],
         'licenseinfo'    => [],
         'networkcard'    => [],
         'drive'          => [],
         'batteries'      => [],
         'remote_mgmt'    => [],
         'bios'           => [],
         'crontasks'      => [],
         'itemtype'       => 'Computer'
      ];

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $_SESSION["glpiname"] = 'Plugin_FusionInventory';

      $pfiComputerLib   = new PluginFusioninventoryInventoryComputerLib();
      $computer         = new Computer();
      $monitor          = new Monitor();

      $a_computerinventory = $computerData;
      $a_computer = $a_computerinventory['Computer'];
      $a_computer["entities_id"] = 0;
      $computers_id = $computer->add($a_computer);

      $pfiComputerLib->updateComputer(
         $a_computerinventory,
         $computers_id,
         false,
         1
      );

      // Check if no monitor added
      $this->assertEquals(0, countElementsInTable('glpi_monitors', ['serial' => 'W6VPJ18475H']), 'Monitor must not be added');

      $computerData['networkport']['em0-cc:f9:54:a1:03:45']['ipaddress'] = ['10.0.43.55'];
      $a_computerinventory = $computerData;
      $pfiComputerLib->updateComputer(
         $a_computerinventory,
         $computers_id,
         false,
         1
      );
      // Check if the monitor is right added
      $this->assertEquals(1, countElementsInTable('glpi_monitors', ['serial' => 'W6VPJ18475H']), 'Monitor must be present');

   }
}
