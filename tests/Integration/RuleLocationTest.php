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

class RuleLocationTest extends TestCase {

   public static function setUpBeforeClass(): void {

      // Delete all locationrules
      $rule = new PluginFusioninventoryInventoryRuleLocation();
      $items = $rule->find(['sub_type' => "PluginFusioninventoryInventoryRuleLocation"]);
      foreach ($items as $item) {
         $rule->delete(['id' => $item['id']], true);
      }
   }


   /**
    * @test
    */
   public function RegexpRuleResultAssignTest() {

      $rule     = new Rule();
      $location = new Location();

      $location->deleteByCriteria(['name' => 'Monsols04'], true);
      $rule->deleteByCriteria(['sub_type' => 'PluginFusioninventoryInventoryRuleLocation'], true);
      $input = [
         'name'        => 'Monsols04',
         'entities_id' => 0
      ];
      $locations_id = $location->add($input);

      $input = [
         'is_active' => 1,
         'name'      => 'Location regexp',
         'match'     => 'AND',
         'sub_type'  => 'PluginFusioninventoryInventoryRuleLocation',
         'ranking'   => 1
      ];
      $rules_id = $rule->add($input);
      $this->assertNotFalse($rules_id);

      // Add criteria
      $rulecriteria = new RuleCriteria();
      $input = [
         'rules_id'  => $rules_id,
         'criteria'  => "name",
         'pattern'   => "/Item (.*)/",
         'condition' => PluginFusioninventoryInventoryRuleLocation::REGEX_MATCH
      ];
      $ret = $rulecriteria->add($input);
      $this->assertNotFalse($ret);

      // Add criteria
      $rulecriteria = new RuleCriteria();
      $input = [
         'rules_id'  => $rules_id,
         'criteria'  => "itemtype",
         'pattern'   => "/Computer|NetworkEquipment/",
         'condition' => Rule::REGEX_MATCH
      ];
      $ret = $rulecriteria->add($input);
      $this->assertNotFalse($ret);

      // Add action
      $ruleaction = new RuleAction();
      $input = [
         'rules_id'    => $rules_id,
         'action_type' => 'assign',
         'field'       => 'locations_id',
         'value'       => $locations_id
      ];
      $ret = $ruleaction->add($input);
      $this->assertNotFalse($ret);

      $input = [
         'name'     => 'Item Monsols04',
         'itemtype' => 'Computer'
      ];

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $ruleLocation = new PluginFusioninventoryInventoryRuleLocationCollection();
      $ruleLocation->getCollectionPart();
      $loc = $ruleLocation->processAllRules($input);

      $a_references = [
         'locations_id' => $locations_id,
         '_ruleid'      => $rules_id
      ];

      $this->assertEquals($a_references, $loc, 'Location result assign_result');

      //This time it should not match because
      $input = [
         'name'     => 'Printer01',
         'itemtype' => 'Printer'
      ];

      $a_references = [
         '_no_rule_matches' => 1,
         '_rule_process'    => ''
      ];

      $ruleLocation->getCollectionPart();
      $loc = $ruleLocation->processAllRules($input);

      $this->assertEquals($a_references, $loc);
      $rule->delete(['id' => $rules_id], true);
   }


   /**
    * @test
    */
   public function RegexpRuleResultRegexpTest() {

      $rule = new Rule();
      $rule->deleteByCriteria(['sub_type' => 'PluginFusioninventoryInventoryRuleLocation'], true);
      $location = new Location;
      $location->getFromDBByCrit(['completename' => 'Monsols04']);;

      $input = [
         'is_active' => 1,
         'name'      => 'Location regexp pc',
         'match'     => 'AND',
         'sub_type'  => 'PluginFusioninventoryInventoryRuleLocation',
         'ranking'   => 1
      ];
      $rules_id = $rule->add($input);
      $this->assertNotFalse($rules_id);

      // Add criteria
      $rulecriteria = new RuleCriteria();
      $input = [
         'rules_id'  => $rules_id,
         'criteria'  => "name",
         'pattern'   => "/pc (.*)/",
         'condition' => PluginFusioninventoryInventoryRuleLocation::REGEX_MATCH
      ];
      $ret = $rulecriteria->add($input);
      $this->assertNotFalse($ret);

      // Add criteria
      $rulecriteria = new RuleCriteria();
      $input = [
         'rules_id'  => $rules_id,
         'criteria'  => "itemtype",
         'pattern'   => "Computer",
         'condition' => Rule::PATTERN_IS
      ];
      $ret = $rulecriteria->add($input);
      $this->assertNotFalse($ret);

      // Add action
      $ruleaction = new RuleAction();
      $input = [
         'rules_id'    => $rules_id,
         'action_type' => 'regex_result',
         'field'       => 'locations_id',
         'value'       => '#0'
      ];
      $ret = $ruleaction->add($input);
      $this->assertNotFalse($ret);

      $ruleLocation = new PluginFusioninventoryInventoryRuleLocationCollection();

      $input = [
         'name'     => 'pc Monsols04',
         'itemtype' => 'Computer'
      ];

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $ruleLocation->getCollectionPart();
      $loc = $ruleLocation->processAllRules($input);

      $a_references = [
         'locations_id' => $location->getID(),
         '_ruleid'      => $rules_id
         ];

      $this->assertEquals($a_references, $loc, 'Location result regexp_result');

      $input = [
         'name'     => 'Monitor Monsols04',
         'itemtype' => 'Monitor'
      ];

      $_SESSION["plugin_fusioninventory_entity"] = 0;

      $a_references = [
         '_no_rule_matches' => 1,
         '_rule_process'    => ''
      ];

      $ruleLocation->getCollectionPart();
      $loc = $ruleLocation->processAllRules($input);

      $this->assertEquals($a_references, $loc);
   }

   /**
    * @test
    */
   public function RegexpRuleByIPTest() {
      $rule = new Rule();
      $rule->deleteByCriteria(['sub_type' => 'PluginFusioninventoryInventoryRuleLocation'], true);
      $location = new Location;
      $location->getFromDBByCrit(['completename' => 'Monsols04']);;

      $input = [
         'is_active' => 1,
         'name'      => 'Location by IP',
         'match'     => 'AND',
         'sub_type'  => 'PluginFusioninventoryInventoryRuleLocation',
         'ranking'   => 1
      ];
      $rules_id = $rule->add($input);
      $this->assertNotFalse($rules_id);

      // Add criteria
      $rulecriteria = new RuleCriteria();
      $input = [
         'rules_id'  => $rules_id,
         'criteria'  => "ip",
         'pattern'   => "192.168.0",
         'condition' => Rule::PATTERN_CONTAIN
      ];
      $ret = $rulecriteria->add($input);
      $this->assertNotFalse($ret);

      // Add criteria
      $rulecriteria = new RuleCriteria();
      $input = [
         'rules_id'  => $rules_id,
         'criteria'  => "itemtype",
         'pattern'   => "/Computer|NetworkEquipment|Printer/",
         'condition' => Rule::REGEX_MATCH
      ];
      $ret = $rulecriteria->add($input);
      $this->assertNotFalse($ret);

      // Add action
      $ruleaction = new RuleAction();
      $input = [
         'rules_id'    => $rules_id,
         'action_type' => 'assign',
         'field'       => 'locations_id',
         'value'       => $location->getID()
      ];
      $ret = $ruleaction->add($input);
      $this->assertNotFalse($ret);

      $ruleLocation = new PluginFusioninventoryInventoryRuleLocationCollection();

      $input = [
         'name'     => 'pc Monsols04',
         'ip'       => '192.168.0.10',
         'itemtype' => 'Computer'
      ];

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $ruleLocation->getCollectionPart();
      $loc = $ruleLocation->processAllRules($input);

      $a_references = [
         'locations_id' => $location->getID(),
         '_ruleid'      => $rules_id
      ];

      $this->assertEquals($a_references, $loc, 'Location by IP');

      $input = [
         'name'     => 'pc Monsols04',
         'ip'       => '172.168.0.10',
         'itemtype' => 'Computer'
      ];

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $ruleLocation->getCollectionPart();
      $loc = $ruleLocation->processAllRules($input);

      $a_references = [
         '_no_rule_matches' => 1,
         '_rule_process'    => ''
      ];

      $this->assertEquals($a_references, $loc, 'Location by IP');

      $input = [
         'name'     => 'Monitor Monsols04',
         'ip'       => '192.168.0.10',
         'itemtype' => 'Monitor'
      ];

      $_SESSION["plugin_fusioninventory_entity"] = 0;

      $a_references = [
         '_no_rule_matches' => 1,
         '_rule_process'    => ''
      ];

      $ruleLocation->getCollectionPart();
      $loc = $ruleLocation->processAllRules($input);

      $this->assertEquals($a_references, $loc);

      //Peripheral is an itemtype that can be targeted by the rules engine
      $input = [
         'name'     => 'Peripheral Monsols04',
         'ip'       => '192.168.0.10',
         'itemtype' => 'Peripheral'
      ];

      $_SESSION["plugin_fusioninventory_entity"] = 0;

      $a_references = [
         '_no_rule_matches' => 1,
         '_rule_process'    => ''
      ];

      $ruleLocation->getCollectionPart();
      $loc = $ruleLocation->processAllRules($input);

      $this->assertEquals($a_references, $loc);
   }
}
