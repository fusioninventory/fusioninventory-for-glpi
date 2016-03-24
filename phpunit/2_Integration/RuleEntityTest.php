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
   @since     2015

   ------------------------------------------------------------------------
 */

class RuleEntityTest extends Common_TestCase {


   /**
    * @test
    */
   public function TwoRegexpEntitiesTest() {
      global $DB;

      $DB->connect();

      $DB->query('DELETE FROM glpi_entities where id>0');

      $DB->query("INSERT INTO `glpi_entities`
         (`id`, `name`, `entities_id`, `completename`, `level`, `tag`)
         VALUES (1, 'entity A', 0, 'Entité racine > entity A', 2, 'entA')");

      $DB->query("INSERT INTO `glpi_entities`
         (`id`, `name`, `entities_id`, `completename`, `level`, `tag`)
         VALUES (2, 'entity B', 0, 'Entité racine > entity B', 2, 'entB')");

      $DB->query("INSERT INTO `glpi_entities`
         (`id`, `name`, `entities_id`, `completename`, `level`, `tag`)
         VALUES (3, 'entity C', 0, 'Entité racine > entity C', 2, 'entC')");

      // Add a rule for get entity tag (1)
      $rule = new Rule();
      $input = array(
         'is_active' => 1,
         'name'      => 'entity rule 1',
         'match'     => 'AND',
         'sub_type'  => 'PluginFusioninventoryInventoryRuleEntity',
         'ranking'   => 1
      );
      $rule1_id = $rule->add($input);

         // Add criteria
         $rulecriteria = new RuleCriteria();
         $input = array(
            'rules_id'  => $rule1_id,
            'criteria'  => "name",
            'pattern'   => "/^([A-Za-z0-9]*) - ([A-Za-z0-9]*) - (.*)$/",
            'condition' => PluginFusioninventoryInventoryRuleEntity::REGEX_MATCH
         );
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction();
         $input = array(
            'rules_id'    => $rule1_id,
            'action_type' => 'regex_result',
            'field'       => '_affect_entity_by_tag',
            'value'       => '#2'
         );
         $ruleaction->add($input);

      // Add a rule for get entity tag (2)
      $rule = new Rule();
      $input = array(
         'is_active' => 1,
         'name'      => 'entity rule 2',
         'match'     => 'AND',
         'sub_type'  => 'PluginFusioninventoryInventoryRuleEntity',
         'ranking'   => 2
      );
      $rule2_id = $rule->add($input);

         // Add criteria
         $rulecriteria = new RuleCriteria();
         $input = array(
            'rules_id'  => $rule2_id,
            'criteria'  => "name",
            'pattern'   => "/^([A-Za-z0-9]*) - (.*)$/",
            'condition' => PluginFusioninventoryInventoryRuleEntity::REGEX_MATCH
         );
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction();
         $input = array(
            'rules_id'    => $rule2_id,
            'action_type' => 'regex_result',
            'field'       => '_affect_entity_by_tag',
            'value'       => '#1'
         );
         $ruleaction->add($input);


      $input = array(
         'name' => 'computer01 - entC'
      );

      $ruleEntity = new PluginFusioninventoryInventoryRuleEntityCollection();
      $ruleEntity->getCollectionPart();
      $ent = $ruleEntity->processAllRules($input, array());

      $a_references = array(
         'entities_id' => 3,
         '_ruleid'     => $rule2_id
      );

      $this->assertEquals($a_references, $ent, 'Entity C');



      $input = array(
         'name' => 'computer01 - blabla - entB'
      );

      $ruleEntity = new PluginFusioninventoryInventoryRuleEntityCollection();
      $ruleEntity->getCollectionPart();
      $ent = $ruleEntity->processAllRules($input, array());

      $a_references = array(
         'entities_id' => 2,
         '_ruleid'     => $rule1_id
      );

      $this->assertEquals($a_references, $ent, 'Entity B');

   }

}
?>
