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
   @copyright Copyright (c) 2010-2021 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2018

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class ImportRuleTest extends TestCase {

   private $items_id = 0;
   private $itemtype = '';
   private $ports_id = 0;

   public static function setUpBeforeClass(): void {
      // Reinit rules
      $setup = new PluginFusioninventorySetup();
      $setup->initRules(true, true);
   }

   public static function tearDownAfterClass(): void {
      // Reinit rules
      $setup = new PluginFusioninventorySetup();
      $setup->initRules(true, true);
   }

   function setUp(): void {
      $this->items_id = 0;
      $this->itemtype = '';
      $this->ports_id = 0;

      // Delete all computers
      $computer = new Computer();
      $computers = $computer->find();
      foreach ($computers as $item) {
         $computer->delete(['id' => $item['id']], true);
      }

      // Delete all network equipments
      $networkEquipment = new NetworkEquipment();
      $items = $networkEquipment->find();
      foreach ($items as $item) {
         $networkEquipment->delete(['id' => $item['id']], true);
      }

      // Delete all printers
      $printer = new Printer();
      $items = $printer->find();
      foreach ($items as $item) {
         $printer->delete(['id' => $item['id']], true);
      }

      // Delete all unmanaged items
      $pfUnmanaged = new PluginFusioninventoryUnmanaged();
      $items = $pfUnmanaged->find();
      foreach ($items as $item) {
         $pfUnmanaged->delete(['id' => $item['id']], true);
      }
   }

   function rulepassed($items_id, $itemtype, $ports_id = 0) {
      $this->items_id = $items_id;
      $this->itemtype = $itemtype;
      $this->ports_id = $ports_id;
   }

   static function getMethod() {
      return 'testRules';
   }

   function activeRule($name) {
      $pfRule = new PluginFusioninventoryInventoryRuleImportCollection();
      $rules = $pfRule->find([
         'name'      => ['LIKE', '%'.$name.'%'],
         'is_active' => 0
      ]);
      foreach ($rules as $rule) {
         $pfRule->update([
            'id'        => $rule['id'],
            'is_active' => 1,
         ]);
      }
   }

   function activateAllRules() {
      $pfRule = new PluginFusioninventoryInventoryRuleImportCollection();
      $rules = $pfRule->find();
      foreach ($rules as $rule) {
         $pfRule->update([
            'id'        => $rule['id'],
            'is_active' => 1,
         ]);
      }
   }

   function addRule($name, $criteria = [], $action = [], $afterRuleName = '') {
      global $DB;

      $rule = new PluginFusioninventoryInventoryRuleImport();
      $rulecriteria = new RuleCriteria();
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();

      $input = [
         'is_active' => 1,
         'name'      => $name,
         'match'     => 'AND',
         'sub_type'  => 'PluginFusioninventoryInventoryRuleImport'
      ];
      if ($afterRuleName != '') {
         $ruleARN = $rule->find(['name' => $afterRuleName], [], 1);
         if (count($ruleARN) > 0) {
            $r = current($ruleARN);
            $DB->query("UPDATE glpi_rules "
                  . "SET ranking = ranking + 1 "
                  . "WHERE ranking > '".$r['ranking']."' "
                  . "   AND `sub_type`='PluginFusioninventoryInventoryRuleImport'");

            $input['ranking'] = ($r['ranking'] + 1);
         }
      }
      $rules_id = $rulecollection->add($input);
      $DB->query("UPDATE glpi_rules SET `ranking`=1"
            . " WHERE `id`=".$rules_id);

      // Add criteria
      foreach ($criteria as $crit) {
         $input = [
            'rules_id'  => $rules_id,
            'criteria'  => $crit['criteria'],
            'pattern'   => $crit['pattern'],
            'condition' => $crit['condition'],
         ];
         $rulecriteria->add($input);
      }

      // Add action
      $ruleaction = new RuleAction();
      $input = [
         'rules_id'    => $rules_id,
         'action_type' => $action['action_type'],
         'field'       => $action['field'],
         'value'       => $action['value'],
      ];
      $ruleaction->add($input);
   }

   /**
    * @test
    */
   public function createComputerName() {

      $input = [
         'itemtype' => 'Computer',
         'name'     => 'pc-01',
      ];
      $ruleCollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $rule = new PluginFusioninventoryInventoryRuleImport();
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "ImportRuleTest";

      $data = $ruleCollection->processAllRules($input, [], ['class'=>$this]);
      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Computer import (by name)", $rule->fields['name']);
      $this->assertEquals(0, $this->items_id);
      $this->assertEquals('Computer', $this->itemtype);
   }

   /**
    * @test
    */
   public function updateComputerName() {

      $input = [
         'itemtype' => 'Computer',
         'name'     => 'pc-01',
      ];
      $ruleCollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $rule = new PluginFusioninventoryInventoryRuleImport();
      $computer = new Computer();
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "ImportRuleTest";

      $computers_id = $computer->add([
         'entities_id' => 0,
         'name'        => 'pc-01',
      ]);
      $this->assertNotFalse($computers_id);

      $data = $ruleCollection->processAllRules($input, [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Computer update (by name)", $rule->fields['name']);
      $this->assertEquals($computers_id, $this->items_id);
      $this->assertEquals('Computer', $this->itemtype);
   }


   /**
    * @test
    */
   public function updateComputerDoubleName() {

      $input = [
         'itemtype' => 'Computer',
         'name'     => 'pc-01',
      ];
      $ruleCollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $rule = new PluginFusioninventoryInventoryRuleImport();
      $computer = new Computer();
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "ImportRuleTest";

      $computers_id = $computer->add([
         'entities_id' => 0,
         'comment'     => 'first computer',
         'name'        => 'pc-01',
      ]);
      $this->assertNotFalse($computers_id);

      $computers_id2 = $computer->add([
         'entities_id' => 0,
         'comment'     => 'second computer',
         'name'        => 'pc-01',
      ]);
      $this->assertNotFalse($computers_id2);

      $this->assertNotEquals($computers_id, $computers_id2);

      $data = $ruleCollection->processAllRules($input, [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Computer update (by name)", $rule->fields['name']);
      $this->assertEquals($computers_id, $this->items_id);
      $this->assertEquals('Computer', $this->itemtype);
   }

   /**
    * @test
    *
    * case 1 :
    *   no computer in DB
    */
   public function createComputerSerial_UUID_case1() {

      $input = [
         'itemtype' => 'Computer',
         'name'     => 'pc-01',
         'serial'   => '75F4BF',
         'uuid'     => '01391796-50A4-0246-955B-417652A8AF14',
      ];
      $ruleCollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $rule = new PluginFusioninventoryInventoryRuleImport();
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "ImportRuleTest";

      $data = $ruleCollection->processAllRules($input, [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Computer import (by serial + uuid)", $rule->fields['name']);
      $this->assertEquals(0, $this->items_id);
      $this->assertEquals('Computer', $this->itemtype);
   }

   /**
    * @test
    *
    * case 2 :
    *   computer in DB with this UUID and another name
    */
   public function createComputerSerial_UUID_case2() {

      $input = [
         'itemtype' => 'Computer',
         'name'     => 'pc-01',
         'serial'   => '75F4BF',
         'uuid'     => '01391796-50A4-0246-955B-417652A8AF14',
      ];
      $ruleCollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $rule = new PluginFusioninventoryInventoryRuleImport();
      $computer = new Computer();
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "ImportRuleTest";

      $computers_id = $computer->add([
         'entities_id' => 0,
         'name'        => 'pc-02',
         'uuid'     => '01391796-50A4-0246-955B-417652A8AF14',
      ]);
      $this->assertNotFalse($computers_id);

      $data = $ruleCollection->processAllRules($input, [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Computer import (by serial + uuid)", $rule->fields['name']);
      $this->assertEquals(0, $this->items_id);
      $this->assertEquals('Computer', $this->itemtype);
   }

   /**
    * @test
    */
   public function updateComputerSerial_UUID() {

      $input = [
         'itemtype' => 'Computer',
         'name'     => 'pc-01',
         'serial'   => '75F4BF',
         'uuid'     => '01391796-50A4-0246-955B-417652A8AF14',
      ];
      $ruleCollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $rule = new PluginFusioninventoryInventoryRuleImport();
      $computer = new Computer();
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "ImportRuleTest";

      $computers_id = $computer->add([
         'entities_id' => 0,
         'name'        => 'pc-01',
         'serial'      => '75F4BF',
         'uuid'        => '01391796-50A4-0246-955B-417652A8AF14',
      ]);
      $this->assertNotFalse($computers_id);

      $data = $ruleCollection->processAllRules($input, [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Computer update (by serial + uuid)", $rule->fields['name']);
      $this->assertEquals($computers_id, $this->items_id);
      $this->assertEquals('Computer', $this->itemtype);
   }

   /**
    * @test
    */
   public function createComputerMac() {

      // Reinit rules
      $setup = new PluginFusioninventorySetup();
      $setup->initRules(true);
      $this->activateAllRules();

      $input = [
         'itemtype' => 'Computer',
         'name'     => 'pc-01',
         'mac'      => ['d4:81:d7:7b:6c:21'],
      ];
      $ruleCollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $rule = new PluginFusioninventoryInventoryRuleImport();
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "ImportRuleTest";

      $this->activeRule('(by mac)');
      $data = $ruleCollection->processAllRules($input, [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Computer import (by mac)", $rule->fields['name']);
      $this->assertEquals(0, $this->items_id);
      $this->assertEquals('Computer', $this->itemtype);

   }

   /**
    * @test
    */
   public function updateComputerMac() {

      $input = [
         'itemtype' => 'Computer',
         'name'     => 'pc-01',
         'mac'      => ['d4:81:d7:7b:6c:21'],
      ];
      $ruleCollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $rule = new PluginFusioninventoryInventoryRuleImport();
      $computer = new Computer();
      $networkPort = new NetworkPort();
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "ImportRuleTest";

      $computers_id = $computer->add([
         'entities_id' => 0,
         'name'        => 'pc-02', // to be sure the name rule not works before mac rule
      ]);
      $this->assertNotFalse($computers_id);
      $ports_id = $networkPort->add([
         'instantiation_type' => 'NetworkPortEthernet',
         'itemtype'           => 'Computer',
         'items_id'           => $computers_id,
         'mac'                => 'd4:81:d7:7b:6c:21'
      ]);
      $this->assertNotFalse($ports_id);

      $this->activeRule('(by mac)');
      $data = $ruleCollection->processAllRules($input, [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Computer update (by mac)", $rule->fields['name']);
      $this->assertEquals($computers_id, $this->items_id);
      $this->assertEquals('Computer', $this->itemtype);
      $this->assertGreaterThan(0, $this->ports_id);
      $this->assertEquals($ports_id, $this->ports_id);

      // Reinit rules by default
      $setup = new PluginFusioninventorySetup();
      $setup->initRules(true, true);
   }

   /**
    * @test
    */
   public function createComputerIp() {
      $input = [
         'itemtype' => 'Computer',
         'name'     => 'pc-01',
         'ip'       => ['192.168.0.10'],
      ];
      $ruleCollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $rule = new PluginFusioninventoryInventoryRuleImport();
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "ImportRuleTest";

      // Create rules
      $this->addRule(
            "Computer update (by ip)",
            [
               [
                  'condition' => 0,
                  'criteria'  => 'itemtype',
                  'pattern'   => 'Computer',
               ],
               [
                  'condition' => PluginFusioninventoryInventoryRuleImport::PATTERN_FIND,
                  'criteria'  => 'ip',
                  'pattern'   => '1',
               ],
               [
                  'condition' => PluginFusioninventoryInventoryRuleImport::PATTERN_EXISTS,
                  'criteria'  => 'ip',
                  'pattern'   => '1',
               ],
            ],
            [
               'action_type' => 'assign',
               'field'       => '_fusion',
               'value'       => PluginFusioninventoryInventoryRuleImport::RULE_ACTION_LINK,
            ],
            "Computer update (by mac)");

      $this->addRule(
            "Computer import (by ip)",
            [
               [
                  'condition' => 0,
                  'criteria'  => 'itemtype',
                  'pattern'   => 'Computer',
               ],
               [
                  'condition' => PluginFusioninventoryInventoryRuleImport::PATTERN_EXISTS,
                  'criteria'  => 'ip',
                  'pattern'   => '1',
               ],
            ],
            [
               'action_type' => 'assign',
               'field'       => '_fusion',
               'value'       => PluginFusioninventoryInventoryRuleImport::RULE_ACTION_LINK,
            ],
            "Computer import (by mac)");

      $data = $ruleCollection->processAllRules($input, [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Computer import (by ip)", $rule->fields['name']);
      $this->assertEquals(0, $this->items_id);
      $this->assertEquals('Computer', $this->itemtype);

   }

   /**
    * @test
    */
   public function updateComputerIp() {
      $input = [
         'itemtype' => 'Computer',
         'name'     => 'pc-01',
         'ip'       => ['192.168.0.10'],
      ];
      $ruleCollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $rule = new PluginFusioninventoryInventoryRuleImport();
      $computer = new Computer();
      $networkPort = new NetworkPort();
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "ImportRuleTest";

      $computers_id = $computer->add([
         'entities_id' => 0,
         'name'        => 'pc-02', // to be sure the name rule not works before mac rule
      ]);
      $this->assertNotFalse($computers_id);
      $ports_id = $networkPort->add([
         'instantiation_type' => 'NetworkPortEthernet',
         'itemtype'           => 'Computer',
         'items_id'           => $computers_id,
         'ip'                 => '192.168.0.10',
         '_create_children'   => 1,
         'NetworkName_name'   => '',
         'NetworkName_fqdns_id' => 0,
         'NetworkName__ipaddresses' => [
            '-1' => '192.168.0.10'
         ],
      ]);
      $this->assertNotFalse($ports_id);

      // Create rules
      $this->addRule(
            "Computer update (by ip)",
            [
               [
                  'condition' => 0,
                  'criteria'  => 'itemtype',
                  'pattern'   => 'Computer',
               ],
               [
                  'condition' => PluginFusioninventoryInventoryRuleImport::PATTERN_FIND,
                  'criteria'  => 'ip',
                  'pattern'   => '1',
               ],
               [
                  'condition' => PluginFusioninventoryInventoryRuleImport::PATTERN_EXISTS,
                  'criteria'  => 'ip',
                  'pattern'   => '1',
               ],
            ],
            [
               'action_type' => 'assign',
               'field'       => '_fusion',
               'value'       => PluginFusioninventoryInventoryRuleImport::RULE_ACTION_LINK,
            ],
            "Computer update (by mac)");

      $this->addRule(
            "Computer import (by ip)",
            [
               [
                  'condition' => 0,
                  'criteria'  => 'itemtype',
                  'pattern'   => 'Computer',
               ],
               [
                  'condition' => PluginFusioninventoryInventoryRuleImport::PATTERN_EXISTS,
                  'criteria'  => 'ip',
                  'pattern'   => '1',
               ],
            ],
            [
               'action_type' => 'assign',
               'field'       => '_fusion',
               'value'       => PluginFusioninventoryInventoryRuleImport::RULE_ACTION_LINK,
            ],
            "Computer import (by mac)");

      $data = $ruleCollection->processAllRules($input, [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Computer update (by ip)", $rule->fields['name']);
      $this->assertEquals($computers_id, $this->items_id);
      $this->assertEquals('Computer', $this->itemtype);
      $this->assertGreaterThan(0, $this->ports_id);
      $this->assertEquals($ports_id, $this->ports_id);

      // Reinit rules
      $setup = new PluginFusioninventorySetup();
      $setup->initRules(true, true);

   }

   /**
    * @test
    *
    * Case when all rules are disabled
    */
   public function createComputerNoRules() {

      $input = [
         'itemtype' => 'Computer',
         'name'     => 'pc-01',
      ];
      $ruleCollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $rule = new PluginFusioninventoryInventoryRuleImport();
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "ImportRuleTest";

      $rules = $ruleCollection->find(['is_active' => 1]);
      foreach ($rules as $rule) {
         $ruleCollection->update([
            'id'        => $rule['id'],
            'is_active' => 0,
         ]);
      }

      $data = $ruleCollection->processAllRules($input, [], ['class'=>$this]);

      $this->assertArrayHasKey('_no_rule_matches', $data);
      $this->assertEquals(1, $data['_no_rule_matches']);

      $this->activateAllRules();
   }

   /**
    * @test
    *
    * With default rules, refuse import in theses cases
    */
   public function refuseImport() {

      $ruleCollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $rule = new PluginFusioninventoryInventoryRuleImport();
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "ImportRuleTest";

      // only IP
      $data = $ruleCollection->processAllRules(['ip' => '192.168.0.10'], [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Global constraint (name)", $rule->fields['name']);
      $this->assertEquals(0, $this->items_id);

      // only IP+mac
      $data = $ruleCollection->processAllRules(
            ['mac' => 'd4:81:d7:7b:6c:21', 'ip' => '192.168.0.10'], [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Global constraint (name)", $rule->fields['name']);
      $this->assertEquals(0, $this->items_id);

      // only IP+name
      $data = $ruleCollection->processAllRules(
            ['name' => 'pc-01', 'ip' => '192.168.0.10'], [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Global import denied", $rule->fields['name']);
      $this->assertEquals(0, $this->items_id);

      // only IP+mac+name
      $data = $ruleCollection->processAllRules(
            ['name' => 'pc-01', 'mac' => 'd4:81:d7:7b:6c:21', 'ip' => '192.168.0.10'],
            [],
            ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Global import denied", $rule->fields['name']);
      $this->assertEquals(0, $this->items_id);
   }

   /**
    * @test
    *
    * Search device based on MAC + ifnumber (logicial number)
    */
   public function createMacIfnumber() {

      $input = [
         'ifnumber' => '10102',
         'mac'      => '00:1a:6c:9a:fc:99',
         'name'     => 'network-01',
      ];
      $ruleCollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $rule = new PluginFusioninventoryInventoryRuleImport();
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "ImportRuleTest";

      $data = $ruleCollection->processAllRules($input, [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Device import (by mac+ifnumber)", $rule->fields['name']);
      $this->assertEquals(0, $this->items_id);
      $this->assertEquals('PluginFusioninventoryUnmanaged', $this->itemtype);
      $this->assertEquals(0, $this->ports_id);
   }

   /**
    * @test
    *
    * Search device based on MAC + ifnumber (logicial number)
    */
   public function updateMacIfnumber() {

      $input = [
         'ifnumber' => '10102',
         'mac'      => '00:1a:6c:9a:fc:99',
         'name'     => 'network-01',
      ];
      $ruleCollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $rule = new PluginFusioninventoryInventoryRuleImport();
      $networkEquipment = new NetworkEquipment();
      $networkPort = new NetworkPort();
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "ImportRuleTest";

      $networkEquipments_id = $networkEquipment->add([
         'entities_id' => 0,
         'name'        => 'network-02',
      ]);
      $this->assertNotFalse($networkEquipments_id);
      $ports_id = $networkPort->add([
         'mac'                => '00:1a:6c:9a:fc:99',
         'name'               => 'Gi0/1',
         'logical_number'     => '10101',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);
      $ports_id = $networkPort->add([
         'mac'                => '00:1a:6c:9a:fc:99',
         'name'               => 'Gi0/2',
         'logical_number'     => '10102',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
      ]);
      $this->assertNotFalse($ports_id);

      $data = $ruleCollection->processAllRules($input, [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Device update (by mac+ifnumber restricted port)", $rule->fields['name']);
      $this->assertEquals($networkEquipments_id, $this->items_id);
      $this->assertEquals('NetworkEquipment', $this->itemtype);
      $this->assertGreaterThan(0, $this->ports_id);
      $this->assertEquals($ports_id, $this->ports_id);
   }

   /**
    * @test
    *
    * Search device based on IP + ifdescr restricted on same port
    */
   public function updateIpIfdescrRestrictport() {

      $input = [
         'ifdescr' => 'FastEthernet0/1',
         'ip'      => '192.168.0.1',
      ];
      $ruleCollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $rule = new PluginFusioninventoryInventoryRuleImport();
      $networkEquipment = new NetworkEquipment();
      $networkPort = new NetworkPort();
      $pfNetworkPort = new PluginFusioninventoryNetworkPort();
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "ImportRuleTest";

      $networkEquipments_id = $networkEquipment->add([
         'entities_id' => 0,
         'name'        => 'network-02',
      ]);
      $this->assertNotFalse($networkEquipments_id);
      $ports_id_fa01 = $networkPort->add([
         'mac'                => '00:1a:6c:9a:fc:99',
         'name'               => 'Fa0/1',
         'logical_number'     => '10101',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
         'ip'                 => '192.168.0.1',
         '_create_children'   => 1,
         'NetworkName_name'   => '',
         'NetworkName_fqdns_id' => 0,
         'NetworkName__ipaddresses' => [
            '-1' => '192.168.0.1'
         ],
      ]);
      $this->assertNotFalse($ports_id_fa01);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id_fa01,
         'ifdescr'         => 'FastEthernet0/1',
      ]);
      $this->assertNotFalse($id);

      $ports_id = $networkPort->add([
         'mac'                => '00:1a:6c:9a:fc:98',
         'name'               => 'Fa0/2',
         'logical_number'     => '10102',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
         'ip'                 => '192.168.0.2',
         '_create_children'   => 1,
         'NetworkName_name'   => '',
         'NetworkName_fqdns_id' => 0,
         'NetworkName__ipaddresses' => [
            '-1' => '192.168.0.2'
         ],
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => 'FastEthernet0/2',
      ]);
      $this->assertNotFalse($id);

      $data = $ruleCollection->processAllRules($input, [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Device update (by ip+ifdescr restricted port)", $rule->fields['name']);
      $this->assertEquals($networkEquipments_id, $this->items_id);
      $this->assertEquals('NetworkEquipment', $this->itemtype);
      $this->assertEquals($ports_id_fa01, $this->ports_id);

      // But not find IP on different port than ifdescr
      $this->items_id = 0;
      $this->itemtype = "";
      $this->ports_id = 0;
      $input = [
         'ifdescr' => 'FastEthernet0/1',
         'ip'      => '192.168.0.2',
      ];
      $data = $ruleCollection->processAllRules($input, [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertNotEquals("Device update (by ip+ifdescr restricted port)", $rule->fields['name']);
   }

   /**
    * @test
    *
    * Search device based on IP + ifdescr not restricted on same port
    */
   public function updateIpIfdescrNotRestrictport() {

      $input = [
         'ifdescr' => 'FastEthernet0/1',
         'ip'      => '192.168.0.2',
      ];
      $ruleCollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $rule = new PluginFusioninventoryInventoryRuleImport();
      $networkEquipment = new NetworkEquipment();
      $networkPort = new NetworkPort();
      $pfNetworkPort = new PluginFusioninventoryNetworkPort();
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "ImportRuleTest";

      $networkEquipments_id = $networkEquipment->add([
         'entities_id' => 0,
         'name'        => 'network-02',
      ]);
      $this->assertNotFalse($networkEquipments_id);
      $ports_id_fa01 = $networkPort->add([
         'mac'                => '00:1a:6c:9a:fc:99',
         'name'               => 'Fa0/1',
         'logical_number'     => '10101',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
         'ip'                 => '192.168.0.1',
         '_create_children'   => 1,
         'NetworkName_name'   => '',
         'NetworkName_fqdns_id' => 0,
         'NetworkName__ipaddresses' => [
            '-1' => '192.168.0.1'
         ],
      ]);
      $this->assertNotFalse($ports_id_fa01);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id_fa01,
         'ifdescr'         => 'FastEthernet0/1',
      ]);
      $this->assertNotFalse($id);

      $ports_id = $networkPort->add([
         'mac'                => '00:1a:6c:9a:fc:98',
         'name'               => 'Fa0/2',
         'logical_number'     => '10102',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $networkEquipments_id,
         'itemtype'           => 'NetworkEquipment',
         'ip'                 => '192.168.0.2',
         '_create_children'   => 1,
         'NetworkName_name'   => '',
         'NetworkName_fqdns_id' => 0,
         'NetworkName__ipaddresses' => [
            '-1' => '192.168.0.2'
         ],
      ]);
      $this->assertNotFalse($ports_id);
      $id = $pfNetworkPort->add([
         'networkports_id' => $ports_id,
         'ifdescr'         => 'FastEthernet0/2',
      ]);
      $this->assertNotFalse($id);

      $data = $ruleCollection->processAllRules($input, [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Device update (by ip+ifdescr not restricted port)", $rule->fields['name']);
      $this->assertEquals($networkEquipments_id, $this->items_id);
      $this->assertEquals('NetworkEquipment', $this->itemtype);
      $this->assertEquals($ports_id_fa01, $this->ports_id);
   }

   /**
    * @test
    *
    * Case have only the mac address (mac found on switches)
    */
   public function searchMacNoMoreData() {

      $input = [
         'mac' => 'd4:81:b4:5a:a6:19',
      ];
      $ruleCollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $rule = new PluginFusioninventoryInventoryRuleImport();
      $printer = new Printer();
      $networkPort = new NetworkPort();
      $_SESSION['plugin_fusioninventory_classrulepassed'] = "ImportRuleTest";

      $printers_id = $printer->add([
         'entities_id' => 0,
         'name'        => 'network-02',
      ]);
      $this->assertNotFalse($printers_id);
      $ports_id_1 = $networkPort->add([
         'mac'                => 'd4:81:b4:5a:a6:18',
         'name'               => 'Fa0/1',
         'logical_number'     => '10101',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $printers_id,
         'itemtype'           => 'Printer',
      ]);
      $this->assertNotFalse($ports_id_1);
      $ports_id_2 = $networkPort->add([
         'mac'                => 'd4:81:b4:5a:a6:19',
         'name'               => 'Fa0/2',
         'logical_number'     => '10102',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $printers_id,
         'itemtype'           => 'Printer',
      ]);
      $this->assertNotFalse($ports_id_2);
      $ports_id_3 = $networkPort->add([
         'mac'                => 'd4:81:b4:5a:a6:20',
         'name'               => 'Fa0/3',
         'logical_number'     => '10103',
         'instantiation_type' => 'NetworkPortEthernet',
         'items_id'           => $printers_id,
         'itemtype'           => 'Printer',
      ]);
      $this->assertNotFalse($ports_id_3);

      $data = $ruleCollection->processAllRules($input, [], ['class'=>$this]);

      $this->assertArrayHasKey('_ruleid', $data);
      $this->assertGreaterThan(0, $data['_ruleid']);

      $rule->getFromDB($data['_ruleid']);
      $this->assertEquals("Update only mac address (mac on switch port)", $rule->fields['name']);
      $this->assertEquals($printers_id, $this->items_id);
      $this->assertEquals('Printer', $this->itemtype);
      $this->assertEquals($ports_id_2, $this->ports_id);
   }

   /**
    * @test
    */
   public function rulepassedNetworkEquipment_nodevice() {

      $pf = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $pfUnmanaged = new PluginFusioninventoryUnmanaged();
      $dbu = new DbUtils();
      $items = $pfUnmanaged->find();
      foreach ($items as $item) {
         $pfUnmanaged->delete(['id' => $item['id']], true);
      }
      $pf->data_device = [
         "name" => "test03"
      ];
      $_SESSION['plugin_fusinvsnmp_datacriteria'] = serialize(["name" => "test01"]);
      $pf->rulepassed(0, '', 0);
      $this->assertEquals(0, $dbu->countElementsInTable('glpi_networkports'));

      // Check if the device is right an unmanaged device
      $items = $pfUnmanaged->find();
      $this->assertEquals(1, count($items));
      $item = current($items);
      $this->assertEquals("test03", $item['name']);
   }

   /**
    * @test
    */
   public function rulepassedNetworkEquipment_device_noport() {

      $pf = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkequipment = new NetworkEquipment();
      $networkport = new NetworkPort();
      $dbu = new DbUtils();

      $device_id = $networkequipment->add([
         'name'        => 'sw001',
         'entities_id' => 0,
      ]);

      $pf->data_device = [
         "ifdescr" => "Fa0/12",
         "name"    => "sw001",
      ];
      $pf->rulepassed($device_id, "NetworkEquipment", 0);

      $this->assertEquals(1, $dbu->countElementsInTable('glpi_networkports'));
      // Check if the device is right a networkequipment
      $ports = $networkport->find();
      $port = current($ports);
      $this->assertEquals("NetworkEquipment", $port['itemtype']);
      $this->assertEquals("Fa0/12", $port['name']);
   }

   /**
    * @test
    */
   public function rulepassedNetworkEquipment_device_port() {

      $pf = new PluginFusioninventoryInventoryNetworkEquipmentLib();
      $networkequipment = new NetworkEquipment();
      $networkport = new NetworkPort();
      $dbu = new DbUtils();

      $device_id = $networkequipment->add([
         'name'        => 'sw001',
         'entities_id' => 0,
      ]);
      $ports_id = $networkport->add([
         'name'     => 'Fa0/12',
         'items_id' => $device_id,
         'itemtype' => 'NetworkEquipment',
      ]);

      $pf->data_device = [
         "ifdescr" => "Fa0/12",
         "name"    => "sw001",
      ];
      $pf->rulepassed($device_id, "NetworkEquipment", $ports_id);

      $this->assertEquals(1, $dbu->countElementsInTable('glpi_networkports'));
   }
}
