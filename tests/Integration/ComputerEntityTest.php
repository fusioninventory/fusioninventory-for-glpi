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

class ComputerEntityTest extends TestCase {


   public static function setUpBeforeClass(): void {

      // Delete all entities exept root entity
      $entity = new Entity();
      $items = $entity->find();
      foreach ($items as $item) {
         if ($item['id'] > 0) {
            $entity->delete(['id' => $item['id']], true);
         }
      }

      // Delete all computers
      $computer = new Computer();
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }

      // Delete all entity rules
      $rule = new Rule();
      $items = $rule->find(['sub_type' => "PluginFusioninventoryInventoryRuleEntity"]);
      foreach ($items as $item) {
         $rule->delete(['id' => $item['id']], true);
      }

   }

   public static function tearDownAfterClass(): void {
      // Reinit rules
      $setup = new PluginFusioninventorySetup();
      $setup->initRules(true, true);
   }


   /**
    * @test
    */
   public function testFusionEntityEmpty() {
      $pfEntity = new PluginFusioninventoryEntity();
      $items = $pfEntity->find(['entities_id' => ['>', 0]]);
      $this->assertEquals(0, count($items));
   }


   /**
    * Add computer in entity `ent1` (with rules)
    *
    * @test
    */
   public function AddComputer() {
      plugin_init_fusioninventory();

      $entity = new Entity();

      $entity1Id = $entity->add([
         'name'        => 'ent1',
         'entities_id' => 0,
         'comment'     => ''
      ]);
      $this->assertNotFalse($entity1Id);

      $entity2Id = $entity->add([
         'name'        => 'ent2',
         'entities_id' => 0,
         'comment'     => ''
      ]);
      $this->assertNotFalse($entity2Id);

      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();
      $computer = new Computer();
      $pfEntity = new PluginFusioninventoryEntity();

      $pfEntity->getFromDBByCrit(['entities_id' => 0]);
      if (isset($pfEntity->fields['id'])) {
         $pfEntity->update([
            'id'                => $pfEntity->fields['id'],
            'entities_id'       => 0,
            'transfers_id_auto' => 1
         ]);
      } else {
         $pfEntity->add([
            'entities_id'       => 0,
            'transfers_id_auto' => 1
         ]);
      }

      $a_inventory = [];
      $a_inventory['CONTENT']['HARDWARE'] = [
          'NAME' => 'pc1'
      ];
      $a_inventory['CONTENT']['BIOS'] = [
          'SSN' => 'xxyyzz'
      ];

      // * Add rule ignore
         $rule = new Rule();
         $ruleCriteria = new RuleCriteria();
         $ruleAction = new RuleAction();

         $input = [];
         $input['sub_type']   = 'PluginFusioninventoryInventoryRuleEntity';
         $input['name']       = 'pc1';
         $input['match']      = 'AND';
         $input['is_active']  = 1;
         $rules_id = $rule->add($input);

         $input = [];
         $input['rules_id']   = $rules_id;
         $input['criteria']   = 'name';
         $input['condition']  = 0;
         $input['pattern']    = 'pc1';
         $ruleCriteria->add($input);

         $input = [];
         $input['rules_id']      = $rules_id;
         $input['action_type']   = 'assign';
         $input['field']         = 'entities_id';
         $input['value']         = $entity1Id;
         $ruleAction->add($input);

      // ** Add agent
      $pfAgent = new PluginFusioninventoryAgent();
      $a_agents_id = $pfAgent->add(['name'      => 'pc-2013-02-13',
                                    'device_id' => 'pc-2013-02-13']);
      $_SESSION['plugin_fusioninventory_agents_id'] = $a_agents_id;

      // ** Add
         $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // creation

         $nbComputers = countElementsInTable("glpi_computers");
         $this->assertEquals(1, $nbComputers, 'Nb computer for update computer');

         $computer->getFromDBByCrit(['name' => 'pc1']);
         $this->assertEquals($entity1Id, $computer->fields['entities_id'], 'Add computer');

         $this->_agentEntity($computer->fields['id'], $entity1Id, 'Add computer on entity 1');

      // ** Update
         $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // update

         $computers = getAllDataFromTable("glpi_computers");
         $this->assertEquals(1, count($computers), 'Nb computer for update computer '.print_r($computers, true));

         $computer->getFromDBByCrit(['name' => 'pc1']);
         $this->assertEquals($entity1Id, $computer->fields['entities_id'], 'Update computer');

         $this->_agentEntity($computer->fields['id'], $entity1Id, 'Update computer on entity 1 (not changed)');
   }


   /**
    * Update computer to change entity (transfer allowed)
    *
    * @test
    */
   public function updateComputerTranfer() {

      $transfer       = new Transfer();
      $computer       = new Computer();
      $pfiComputerInv = new PluginFusioninventoryInventoryComputerInventory();

      // Manual transfer computer to entity 2

      $transfer->getFromDB(1);
      $computer->getFromDBByCrit(['serial' => 'xxyyzz']);
      $item_to_transfer = ["Computer" => [1 => $computer->fields['id']]];
      $transfer->moveItems($item_to_transfer, 2, $transfer->fields);

      $computer->getFromDBByCrit(['serial' => 'xxyyzz']);
      $this->assertEquals(2, $computer->fields['entities_id'], 'Transfer move computer');

      $this->_agentEntity($computer->fields['id'], 2, 'Transfer computer on entity 2');

      // Update computer and computer may be transfered to entity 1 automatically

      $a_inventory = [];
      $a_inventory['CONTENT']['HARDWARE'] = [
          'NAME' => 'pc1'
      ];
      $a_inventory['CONTENT']['BIOS'] = [
          'SSN' => 'xxyyzz'
      ];

      $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // Update

      $nbComputers = countElementsInTable("glpi_computers");
      $this->assertEquals(1, $nbComputers, 'Nb computer for update computer');

      $computer->getFromDBByCrit(['serial' => 'xxyyzz']);
      $this->assertEquals(1, $computer->fields['entities_id'], 'Automatic transfer computer');

      $this->_agentEntity($computer->fields['id'], 1, 'Automatic transfer computer on entity 1');

   }


   /**
    * Update computer to not change entity (transfer not allowed)
    *
    * @test
    */
   public function updateComputerNoTranfer() {

      $transfer       = new Transfer();
      $computer       = new Computer();
      $pfiComputerInv = new PluginFusioninventoryInventoryComputerInventory();
      $pfEntity       = new PluginFusioninventoryEntity();

      // Manual transfer computer to entity 2

      $transfer->getFromDB(1);
      $computer->getFromDBByCrit(['serial' => 'xxyyzz']);
      $item_to_transfer = ["Computer" => [1 => $computer->fields['id']]];
      $transfer->moveItems($item_to_transfer, 2, $transfer->fields);

      $computer->getFromDBByCrit(['serial' => 'xxyyzz']);
      $this->assertEquals(2, $computer->fields['entities_id'], 'Transfer move computer');

      $this->_agentEntity($computer->fields['id'], 2, 'Transfer computer on entity 2');

      // Define entity 2 not allowed to transfer
      $ents_id = $pfEntity->add([
          'entities_id'       => 2,
          'transfers_id_auto' => 0
      ]);
      $this->assertNotFalse($ents_id, 'Entity 2 defined with no transfer');

      // Update computer and computer must not be transfered (keep in entity 2)

      $a_inventory = [];
      $a_inventory['CONTENT']['HARDWARE'] = [
          'NAME' => 'pc1'
      ];
      $a_inventory['CONTENT']['BIOS'] = [
          'SSN' => 'xxyyzz'
      ];

      $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // Update

      $this->assertEquals(1, countElementsInTable('glpi_computers'), 'Must have only 1 computer');

      $computer->getFromDBByCrit(['serial' => 'xxyyzz']);
      $this->assertEquals(2, $computer->fields['entities_id'], 'Computer must not be transfered');

      $this->_agentEntity($computer->fields['id'], 2, 'Agent must stay with entity 2');

   }


   /**
    * Update computer with restrict entity (in this case computer added)
    *
    * @test
    */
   public function updateaddComputerRestrictEntity() {
      global $DB;

      $computer = new Computer();
      $pfiComputerInv = new PluginFusioninventoryInventoryComputerInventory();

      // Disable all rules
      $DB->query("UPDATE `glpi_rules`
         SET `is_active`='0'
         WHERE `sub_type`='PluginFusioninventoryInventoryRuleImport'");

      // Add rule name + restrict entity search
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = [
         'is_active' => 1,
         'name'      => 'Computer name + restrict',
         'match'     => 'AND',
         'sub_type'  => 'PluginFusioninventoryInventoryRuleImport',
         'ranking'   => 1
      ];
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => 'name',
         'pattern'   => 1,
         'condition' => 10,
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => 'name',
         'pattern'   => 1,
         'condition' => 8,
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => 'entityrestrict',
         'pattern'   => '',
         'condition' => 202,
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => 'itemtype',
         'pattern'   => 'Computer',
         'condition' => 0,
      ];
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [
         'rules_id'    => $rule_id,
         'action_type' => 'assign',
         'field'       => '_fusion',
         'value'       => '1',
      ];
      $ruleaction->add($input);

      $a_inventory = [];
      $a_inventory['CONTENT']['HARDWARE'] = [
          'NAME' => 'pc1'
      ];
      $a_inventory['CONTENT']['BIOS'] = [
          'SSN' => 'xxyyzz'
      ];

      $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // Update

      $this->assertEquals(2, countElementsInTable('glpi_computers'), 'Must have only 2 computer');

      $item = current($computer->find(['serial' => 'xxyyzz'], ['id DESC'], 1));
      $this->assertEquals(1, $item['entities_id'], 'Second computer added');

   }


   function _agentEntity($computers_id = 0, $entities_id = 0, $text = '') {

      if ($computers_id == 0) {
         return;
      }

      $pfAgent = new PluginFusioninventoryAgent();
      $a_agents_id = $pfAgent->getAgentWithComputerid($computers_id);
      $pfAgent->getFromDB($a_agents_id);

      $this->assertEquals($entities_id, $pfAgent->fields['entities_id'], $text);
   }
}
