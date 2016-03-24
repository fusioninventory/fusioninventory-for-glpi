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

class RuleLocationTest extends Common_TestCase {


   /**
    * @test
    */
   public function RegexpRuleResultAssignTest() {
      global $DB, $PF_CONFIG;

      $DB->connect();

      $rule = new Rule();
      $location = new Location();

      $input = array(
         'name'        => 'Monsols04',
         'entities_id' => 0
      );
      $location->add($input);

      $input = array(
         'is_active' => 1,
         'name'      => 'Location regexp',
         'match'     => 'AND',
         'sub_type'  => 'PluginFusioninventoryInventoryRuleLocation',
         'ranking'   => 1
      );
      $rules_id = $rule->add($input);

         // Add criteria
         $rulecriteria = new RuleCriteria();
         $input = array(
            'rules_id'  => $rules_id,
            'criteria'  => "name",
            'pattern'   => "/computer (.*)/",
            'condition' => PluginFusioninventoryInventoryRuleLocation::REGEX_MATCH
         );
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction();
         $input = array(
            'rules_id'    => $rules_id,
            'action_type' => 'assign',
            'field'       => 'locations_id',
            'value'       => 1
         );
         $ruleaction->add($input);

      $input = array(
         'name' => 'computer Monsols04'
      );

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $ruleLocation = new PluginFusioninventoryInventoryRuleLocationCollection();
      $ruleLocation->getCollectionPart();
      $loc = $ruleLocation->processAllRules($input, array());

      $a_references = array(
         'locations_id' => 1,
         '_ruleid'      => 50
      );

      $this->assertEquals($a_references, $loc, 'Location result assign_result');
      $rule->delete(array('id' => $rules_id), True);
   }



   /**
    * @test
    */
   public function RegexpRuleResultRegexpTest() {
      global $DB, $PF_CONFIG;

      $DB->connect();

      $rule = new Rule();

      $input = array(
         'is_active' => 1,
         'name'      => 'Location regexp pc',
         'match'     => 'AND',
         'sub_type'  => 'PluginFusioninventoryInventoryRuleLocation',
         'ranking'   => 1
      );
      $rules_id = $rule->add($input);

         // Add criteria
         $rulecriteria = new RuleCriteria();
         $input = array(
            'rules_id'  => $rules_id,
            'criteria'  => "name",
            'pattern'   => "/pc (.*)/",
            'condition' => PluginFusioninventoryInventoryRuleLocation::REGEX_MATCH
         );
         $rulecriteria->add($input);

         // Add action
         $ruleaction = new RuleAction();
         $input = array(
            'rules_id'    => $rules_id,
            'action_type' => 'regex_result',
            'field'       => 'locations_id',
            'value'       => '#0'
         );
         $ruleaction->add($input);

      $input = array(
         'name' => 'pc Monsols04'
      );

      $_SESSION["plugin_fusioninventory_entity"] = 0;
      $ruleLocation = new PluginFusioninventoryInventoryRuleLocationCollection();
      $ruleLocation->getCollectionPart();
      $loc = $ruleLocation->processAllRules($input, array());

      $a_references = array(
         'locations_id' => 1,
         '_ruleid'      => 51
      );

      $this->assertEquals($a_references, $loc, 'Location result regexp_result');
   }

}
?>
