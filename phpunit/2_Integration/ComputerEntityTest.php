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

class ComputerEntity extends RestoreDatabase_TestCase {


   /**
    * Add computer in entity `ent1` (with rules)
    *
    * @test
    */
   public function AddComputer() {
      global $DB;

      $DB->connect();

      plugin_init_fusioninventory();

      $DB->query("INSERT INTO `glpi_entities`
         (`id`, `name`, `entities_id`, `completename`, `level`)
         VALUES (1, 'ent1', 0, 'Entité racine > ent1', 2)");
      $DB->query("INSERT INTO `glpi_entities`
         (`id`, `name`, `entities_id`, `completename`, `level`)
         VALUES (2, 'ent2', 0, 'Entité racine > ent2', 2)");

      $_SESSION['glpiactive_entity'] = 0;
      $pfiComputerInv  = new PluginFusioninventoryInventoryComputerInventory();
      $computer = new Computer();
      $pfEntity = new PluginFusioninventoryEntity();

      $pfEntity->update(array(
          'id'                => 1,
          'entities_id'       => 0,
          'transfers_id_auto' => 1
      ));


      $a_inventory = array();
      $a_inventory['CONTENT']['HARDWARE'] = array(
          'NAME' => 'pc1'
      );
      $a_inventory['CONTENT']['BIOS'] = array(
          'SSN' => 'xxyyzz'
      );

      // * Add rule ignore
         $rule = new Rule();
         $ruleCriteria = new RuleCriteria();
         $ruleAction = new RuleAction();

         $input = array();
         $input['sub_type']   = 'PluginFusioninventoryInventoryRuleEntity';
         $input['name']       = 'pc1';
         $input['match']      = 'AND';
         $input['is_active']  = 1;
         $rules_id = $rule->add($input);

         $input = array();
         $input['rules_id']   = $rules_id;
         $input['criteria']   = 'name';
         $input['condition']  = 0;
         $input['pattern']    = 'pc1';
         $ruleCriteria->add($input);

         $input = array();
         $input['rules_id']      = $rules_id;
         $input['action_type']   = 'assign';
         $input['field']         = 'entities_id';
         $input['value']         = 1;
         $ruleAction->add($input);

      // ** Add agent
      $pfAgent = new PluginFusioninventoryAgent();
      $a_agents_id = $pfAgent->add(array('name'      => 'pc-2013-02-13',
                                         'device_id' => 'pc-2013-02-13'));
      $_SESSION['plugin_fusioninventory_agents_id'] = $a_agents_id;

      // ** Add
         $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // creation

         $computer->getFromDB(1);
         $this->assertEquals(1, $computer->fields['entities_id'], 'Add computer');

         $this->AgentEntity(1, 1, 'Add computer on entity 1');

      // ** Update
         $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // update

         $nbComputers = countElementsInTable("glpi_computers");
         $this->assertEquals(1, $nbComputers, 'Nb computer for update computer');

         $computer->getFromDB(1);
         $this->assertEquals(1, $computer->fields['entities_id'], 'Update computer');

         $this->AgentEntity(1, 1, 'Update computer on entity 1 (not changed)');
   }



   /**
    * Update computer to change entity (transfer allowed)
    *
    * @test
    */
   public function updateComputerTranfer() {
      global $DB;

      $DB->connect();

      $transfer       = new Transfer();
      $computer       = new Computer();
      $pfiComputerInv = new PluginFusioninventoryInventoryComputerInventory();

      // Manual transfer computer to entity 2

      $transfer->getFromDB(1);
      $item_to_transfer = array("Computer" => array(1=>1));
      $transfer->moveItems($item_to_transfer, 2, $transfer->fields);

      $computer->getFromDB(1);
      $this->assertEquals(2, $computer->fields['entities_id'], 'Transfer move computer');

      $this->AgentEntity(1, 2, 'Transfer computer on entity 2');

      // Update computer and computer may be transfered to entity 1 automatically

      $a_inventory = array();
      $a_inventory['CONTENT']['HARDWARE'] = array(
          'NAME' => 'pc1'
      );
      $a_inventory['CONTENT']['BIOS'] = array(
          'SSN' => 'xxyyzz'
      );


      $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // Update

      $computer->getFromDB(1);
      $this->assertEquals(1, $computer->fields['entities_id'], 'Automatic transfer computer');

      $this->AgentEntity(1, 1, 'Automatic transfer computer on entity 1');

   }



   /**
    * Update computer to not change entity (transfer not allowed)
    *
    * @test
    */
   public function updateComputerNoTranfer() {
      global $DB;

      $DB->connect();

      $transfer       = new Transfer();
      $computer       = new Computer();
      $pfiComputerInv = new PluginFusioninventoryInventoryComputerInventory();
      $pfEntity       = new PluginFusioninventoryEntity();

      // Manual transfer computer to entity 2

      $transfer->getFromDB(1);
      $item_to_transfer = array("Computer" => array(1=>1));
      $transfer->moveItems($item_to_transfer, 2, $transfer->fields);

      $computer->getFromDB(1);
      $this->assertEquals(2, $computer->fields['entities_id'], 'Transfer move computer');

      $this->AgentEntity(1, 2, 'Transfer computer on entity 2');

      // Define entity 2 not allowed to transfer
      $ents_id = $pfEntity->add(array(
          'entities_id'       => 2,
          'transfers_id_auto' => 0
      ));
      $this->assertEquals(2, $ents_id, 'Entity 2 defined with no transfer');

      // Update computer and computer must not be transfered (keep in entoty 2)

      $a_inventory = array();
      $a_inventory['CONTENT']['HARDWARE'] = array(
          'NAME' => 'pc1'
      );
      $a_inventory['CONTENT']['BIOS'] = array(
          'SSN' => 'xxyyzz'
      );


      $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // Update

      $this->assertEquals(1, countElementsInTable('glpi_computers'), 'Must have only 1 computer');

      $computer->getFromDB(1);
      $this->assertEquals(2, $computer->fields['entities_id'], 'Computer must not be transfered');

      $this->AgentEntity(1, 2, 'Agent must stay with entity 2');

   }



   /**
    * Update computer with restrict entity (in this case computer added)
    *
    * @test
    */
   public function updateaddComputerRestrictEntity() {
      global $DB;

      $DB->connect();

      $computer = new Computer();
      $pfiComputerInv = new PluginFusioninventoryInventoryComputerInventory();

      // Disable all rules
      $DB->query("UPDATE `glpi_rules`
         SET `is_active`='0'
         WHERE `sub_type`='PluginFusioninventoryInventoryRuleImport'");

      // Add rule name + restrict entity search
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='Computer name + restrict';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
      $input['ranking'] = 1;
      $rule_id = $rulecollection->add($input);

         // Add criteria
         $rule = $rulecollection->getRuleClass();
         $rulecriteria = new RuleCriteria(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= 1;
         $input['condition']=10;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "name";
         $input['pattern']= 1;
         $input['condition']=8;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "entityrestrict";
         $input['pattern']= '';
         $input['condition']=202;
         $rulecriteria->add($input);

         $input = array();
         $input['rules_id'] = $rule_id;
         $input['criteria'] = "itemtype";
         $input['pattern']= 'Computer';
         $input['condition']=0;
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction(get_class($rule));
         $input = array();
         $input['rules_id'] = $rule_id;
         $input['action_type'] = 'assign';
         $input['field'] = '_fusion';
         $input['value'] = '1';
         $ruleaction->add($input);


      $a_inventory = array();
      $a_inventory['CONTENT']['HARDWARE'] = array(
          'NAME' => 'pc1'
      );
      $a_inventory['CONTENT']['BIOS'] = array(
          'SSN' => 'xxyyzz'
      );

      $pfiComputerInv->import("pc-2013-02-13", "", $a_inventory); // Update

      $this->assertEquals(2, countElementsInTable('glpi_computers'), 'Must have only 2 computer');

      $computer->getFromDB(2);
      $this->assertEquals(1, $computer->fields['entities_id'], 'Second computer added');

   }



   public function AgentEntity($computers_id=0, $entities_id=0, $text='') {
      global $DB;

      $DB->connect();

      if ($computers_id == 0) {
         return;
      }

      $pfAgent = new PluginFusioninventoryAgent();
      $a_agents_id = $pfAgent->getAgentWithComputerid($computers_id);
      $pfAgent->getFromDB($a_agents_id);

      $this->assertEquals($entities_id, $pfAgent->fields['entities_id'], $text);
   }

}
?>
