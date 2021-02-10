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
   @since     2015

   ------------------------------------------------------------------------
 */

use PHPUnit\Framework\TestCase;

class RuleEntityTest extends TestCase {


   public static function setUpBeforeClass(): void {

      // Delete all entities exept root entity
      $entity = new Entity();
      $items = $entity->find();
      foreach ($items as $item) {
         if ($item['id'] > 0) {
            $entity->delete(['id' => $item['id']], true);
         }
      }

      // Delete all entityrules
      $rule = new Rule();
      $items = $rule->find(['sub_type' => 'PluginFusioninventoryInventoryRuleEntity']);
      foreach ($items as $item) {
         $rule->delete(['id' => $item['id']], true);
      }

      // Delete all computers
      $computer = new Computer();
      $items = $computer->find();
      foreach ($items as $item) {
         $computer->delete(['id' => $item['id']], true);
      }
   }

   public static function tearDownAfterClass(): void {
      // Delete all entity rules
      $rule = new Rule();
      $items = $rule->find(['sub_type' => "PluginFusioninventoryInventoryRuleEntity"]);
      foreach ($items as $item) {
         $rule->delete(['id' => $item['id']], true);
      }

   }

   /**
    * @test
    */
   public function TwoRegexpEntitiesTest() {
      $entity = new Entity();

      $entityAId = $entity->add([
         'name'        => 'entity A',
         'entities_id' => 0,
         'comment'     => '',
         'tag'         => 'entA'
      ]);
      $this->assertNotFalse($entityAId);

      $entityBId = $entity->add([
         'name'        => 'entity B',
         'entities_id' => 0,
         'comment'     => '',
         'tag'         => 'entB'
      ]);
      $this->assertNotFalse($entityBId);

      $entityCId = $entity->add([
         'name'        => 'entity C',
         'entities_id' => 0,
         'comment'     => '',
         'tag'         => 'entC'
      ]);
      $this->assertNotFalse($entityBId);

      // Add a rule for get entity tag (1)
      $rule = new Rule();
      $input = [
         'is_active' => 1,
         'name'      => 'entity rule 1',
         'match'     => 'AND',
         'sub_type'  => 'PluginFusioninventoryInventoryRuleEntity',
         'ranking'   => 1
      ];
      $rule1_id = $rule->add($input);

      // Add criteria
      $rulecriteria = new RuleCriteria();
      $input = [
         'rules_id'  => $rule1_id,
         'criteria'  => "name",
         'pattern'   => "/^([A-Za-z0-9]*) - ([A-Za-z0-9]*) - (.*)$/",
         'condition' => PluginFusioninventoryInventoryRuleEntity::REGEX_MATCH
      ];
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction();
      $input = [
         'rules_id'    => $rule1_id,
         'action_type' => 'regex_result',
         'field'       => '_affect_entity_by_tag',
         'value'       => '#2'
      ];
      $ruleaction->add($input);

      // Add a rule for get entity tag (2)
      $rule = new Rule();
      $input = [
      'is_active' => 1,
      'name'      => 'entity rule 2',
      'match'     => 'AND',
      'sub_type'  => 'PluginFusioninventoryInventoryRuleEntity',
      'ranking'   => 2
      ];
      $rule2_id = $rule->add($input);

      // Add criteria
      $rulecriteria = new RuleCriteria();
      $input = [
         'rules_id'  => $rule2_id,
         'criteria'  => "name",
         'pattern'   => "/^([A-Za-z0-9]*) - (.*)$/",
         'condition' => PluginFusioninventoryInventoryRuleEntity::REGEX_MATCH
      ];
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction();
      $input = [
         'rules_id'    => $rule2_id,
         'action_type' => 'regex_result',
         'field'       => '_affect_entity_by_tag',
         'value'       => '#1'
      ];
      $ruleaction->add($input);

      $input = [
      'name' => 'computer01 - entC'
      ];

      $ruleEntity = new PluginFusioninventoryInventoryRuleEntityCollection();
      $ruleEntity->getCollectionPart();
      $ent = $ruleEntity->processAllRules($input, []);

      $a_references = [
      'entities_id' => $entityCId,
      '_ruleid'     => $rule2_id
      ];

      $this->assertEquals($a_references, $ent, 'Entity C');

      $input = [
      'name' => 'computer01 - blabla - entB'
      ];

      $ruleEntity = new PluginFusioninventoryInventoryRuleEntityCollection();
      $ruleEntity->getCollectionPart();
      $ent = $ruleEntity->processAllRules($input, []);

      $a_references = [
      'entities_id' => $entityBId,
      '_ruleid'     => $rule1_id
      ];

      $this->assertEquals($a_references, $ent, 'Entity B');
   }
}
