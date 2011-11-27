<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Walid Nouh
   @co-author David Durieux
   @copyright Copyright (c) 2010-2011 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvinventoryRuleEntityCollection extends RuleCollection {

   // From RuleCollection
   public $stop_on_first_match=true;
   public $right='rule_ocs';
   public $menu_option='test';

   function getTitle() {
      global $LANG;

      return $LANG['plugin_fusinvinventory']['rule'][100];
   }


//   function initRules() {
//      // *** Create first rule
//      $rulecollection = new PluginFusinvinventoryRuleInventoryCollection();
//      $input = array();
//      $input['is_active']=1;
//      $input['name']='serial number + uuid';
//      $input['match']='AND';
//      $input['sub_type'] = 'PluginFusinvinventoryRuleInventory';
//      $rule_id = $rulecollection->add($input);
//
//      // Add criteria
//      $rule = $rulecollection->getRuleClass();
//      $rulecriteria = new RuleCriteria(get_class($rule));
//      $input = array();
//      $input['rules_id'] = $rule_id;
//      $input['criteria'] = "globalcriteria";
//      $input['pattern']= 1;
//      $input['condition']=0;
//      $rulecriteria->add($input);
//      $input = array();
//      $input['rules_id'] = $rule_id;
//      $input['criteria'] = "globalcriteria";
//      $input['pattern']= 2;
//      $input['condition']=0;
//      $rulecriteria->add($input);
//
//      // Add action
//      $ruleaction = new RuleAction(get_class($rule));
//      $input = array();
//      $input['rules_id'] = $rule_id;
//      $input['action_type'] = 'assign';
//      $input['field'] = '_import';
//      $input['value'] = '1';
//      $ruleaction->add($input);
//
//      // *** Create second rule
//      $rulecollection = new PluginFusinvinventoryRuleInventoryCollection();
//      $input = array();
//      $input['is_active']=1;
//      $input['name']='mac address';
//      $input['match']='AND';
//      $input['sub_type'] = 'PluginFusinvinventoryRuleInventory';
//      $rule_id = $rulecollection->add($input);
//
//      // Add criteria
//      $rule = $rulecollection->getRuleClass();
//      $rulecriteria = new RuleCriteria(get_class($rule));
//      $input = array();
//      $input['rules_id'] = $rule_id;
//      $input['criteria'] = "globalcriteria";
//      $input['pattern']= 3;
//      $input['condition']=0;
//      $rulecriteria->add($input);
//
//      // Add action
//      $ruleaction = new RuleAction(get_class($rule));
//      $input = array();
//      $input['rules_id'] = $rule_id;
//      $input['action_type'] = 'assign';
//      $input['field'] = '_import';
//      $input['value'] = '1';
//      $ruleaction->add($input);
//
//      // *** Create third rule
//      $rulecollection = new PluginFusinvinventoryRuleInventoryCollection();
//      $input = array();
//      $input['is_active']=1;
//      $input['name']='serial number';
//      $input['match']='AND';
//      $input['sub_type'] = 'PluginFusinvinventoryRuleInventory';
//      $rule_id = $rulecollection->add($input);
//
//      // Add criteria
//      $rule = $rulecollection->getRuleClass();
//      $rulecriteria = new RuleCriteria(get_class($rule));
//      $input = array();
//      $input['rules_id'] = $rule_id;
//      $input['criteria'] = "globalcriteria";
//      $input['pattern']= 1;
//      $input['condition']=0;
//      $rulecriteria->add($input);
//
//      // Add action
//      $ruleaction = new RuleAction(get_class($rule));
//      $input = array();
//      $input['rules_id'] = $rule_id;
//      $input['action_type'] = 'assign';
//      $input['field'] = '_import';
//      $input['value'] = '1';
//      $ruleaction->add($input);
//
//      // *** Add rule for import in unknown devices
//      $rulecollection = new PluginFusinvinventoryRuleInventoryCollection();
//      $input = array();
//      $input['is_active']=1;
//      $input['name']='unknown device';
//      $input['match']='AND';
//      $input['sub_type'] = 'PluginFusinvinventoryRuleInventory';
//      $rule_id = $rulecollection->add($input);
//
//      // Add criteria
//      $rule = $rulecollection->getRuleClass();
//      $rulecriteria = new RuleCriteria(get_class($rule));
//      $input = array();
//      $input['rules_id'] = $rule_id;
//      $input['criteria'] = "mac";
//      $input['pattern']= "*";
//      $input['condition']=0;
//      $rulecriteria->add($input);
//
//      // Add action
//      $ruleaction = new RuleAction(get_class($rule));
//      $input = array();
//      $input['rules_id'] = $rule_id;
//      $input['action_type'] = 'assign';
//      $input['field'] = '_import_unknowndevice';
//      $input['value'] = '1';
//      $ruleaction->add($input);
//   }


   function prepareInputDataForProcess($input,$params) {

      return $input;
   }

}

?>