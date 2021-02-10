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

class RuleIgnoredImportTest extends TestCase {


   public static function setUpBeforeClass(): void {

      // Delete all collectrules
      $rule = new Rule();
      $items = $rule->find(['sub_type' => "PluginFusioninventoryInventoryRuleImport"]);
      foreach ($items as $item) {
         $rule->delete(['id' => $item['id']], true);
      }

      // Delete all computers
      $computer = new Computer();
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }

      // Delete all ignoreddevice import
      $pfIgnoredimportdevice = new PluginFusioninventoryIgnoredimportdevice();
      $items = $pfIgnoredimportdevice->find();
      foreach ($items as $item) {
         $pfIgnoredimportdevice->delete(['id' => $item['id']], true);
      }

      // Add a rule to ignore import
      // Create rule for import into unknown devices
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = [
         'is_active' => 1,
         'name'      => 'Ignore import',
         'match'     => 'AND',
         'sub_type'  => 'PluginFusioninventoryInventoryRuleImport',
         'ranking'   => 200,
      ];
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => 'name',
         'pattern'   => '*',
         'condition' => 0
      ];
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [
         'rules_id'    => $rule_id,
         'action_type' => 'assign',
         'field'       => '_ignore_import',
         'value'       => '1'
      ];
      $ruleaction->add($input);
   }


   public static function tearDownAfterClass(): void {
      // Reinit rules
      $setup = new PluginFusioninventorySetup();
      $setup->initRules(true, true);
   }


   /**
    * @test
    * computer inventory
    */
   public function IgnoreComputerImport() {

      $_SESSION['glpishowallentities']       = 1;
      $_SESSION['glpiname']                  = 'glpi';

      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();
      $pfIgnoredimportdevice = new PluginFusioninventoryIgnoredimportdevice();
      $rule            = new Rule();

      $a_inventory = [];
      $a_inventory['CONTENT']['HARDWARE'] = [
          'NAME' => 'pc1'
      ];
      $a_inventory['CONTENT']['SOFTWARES'][] = [];

      // ** Add agent
      $pfAgent = new PluginFusioninventoryAgent();
      $a_agents_id = $pfAgent->add([
         'name'      => 'pc-2013-02-13',
         'device_id' => 'pc-2013-02-13'
      ]);
      $_SESSION['plugin_fusioninventory_agents_id'] = $a_agents_id;

      $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // creation

      $this->assertEquals(0, countElementsInTable('glpi_computers'), 'Computer may not be added');

      $this->assertEquals(0, countElementsInTable('glpi_plugin_fusioninventory_unmanageds'), 'Unmanaged may not be added');

      $a_ignored = $pfIgnoredimportdevice->find();
      $this->assertEquals(1, count($a_ignored), 'May have only one ignored device import');

      $rule_values = $rule->find(['name' => 'Ignore import', 'sub_type' => 'PluginFusioninventoryInventoryRuleImport']);
      $this->assertEquals(1, count($rule_values), 'Ignore import rule not found');
      $rule_ignore = array_pop($rule_values);

      $a_ignore = current($a_ignored);
      $deviceId = $a_ignore['id'];
      unset($a_ignore['id']);
      $a_reference = [
          'name'                             => 'pc1',
          'itemtype'                         => 'Computer',
          'entities_id'                      => 0,
          'ip'                               => null,
          'mac'                              => null,
          'rules_id'                         => $rule_ignore['id'],
          'method'                           => 'inventory',
          'serial'                           => '',
          'uuid'                             => '',
          'plugin_fusioninventory_agents_id' => $a_agents_id
      ];
      unset($a_ignore['date']);
      $this->assertEquals($a_reference, $a_ignore, 'Ignored import computer');
      $pfIgnoredimportdevice->delete(['id' => $deviceId]);
   }


   /**
    * @test
    * network discovery
    */
   public function IgnoreNetworkDiscoveryImport() {

      $a_inventory = [
          'DNSHOSTNAME' => 'pctest',
          'ENTITY'      => 0,
          'IP'          => '192.168.20.3'
      ];

      $pfCommunicationNetworkDiscovery = new PluginFusioninventoryCommunicationNetworkDiscovery();
      $computer = new Computer();
      $pfUnmanaged = new PluginFusioninventoryUnmanaged();
      $pfIgnoredimportdevice = new PluginFusioninventoryIgnoredimportdevice();

      $_SESSION['plugin_fusinvsnmp_taskjoblog']['taskjobs_id'] = 1;
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['items_id']    = '1';
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['itemtype']    = 'Computer';
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['state']       = 0;
      $_SESSION['plugin_fusinvsnmp_taskjoblog']['comment']     = '';

      $pfCommunicationNetworkDiscovery->sendCriteria($a_inventory);

      $a_computers = $computer->find();
      $this->assertEquals(0, count($a_computers), 'Computer may not be added');

      $a_unknown = $pfUnmanaged->find();
      $this->assertEquals(0, count($a_unknown), 'Unmanaged may not be added');

      $a_ignored = $pfIgnoredimportdevice->find();
      $this->assertEquals(1, count($a_ignored), 'May have only one ignored device import');
   }
}
