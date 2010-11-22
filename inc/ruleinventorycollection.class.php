<?php
/*
 * @version $Id: ruleocscollection.class.php 11162 2010-03-30 17:11:13Z walid $
 -------------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org/
 -------------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------
if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

include_once(GLPI_ROOT."/plugins/fusioninventory/inc/rulecollection.class.php");


class PluginFusinvinventoryRuleInventoryCollection extends PluginFusioninventoryRuleCollection {

   // From RuleCollection
   public $stop_on_first_match=true;
   public $right='rule_ocs';
   public $menu_option='test';

   function getTitle() {
      global $LANG;

      return $LANG['plugin_fusinvinventory']["rule"][0];
   }


   function initRules() {
      // *** Create first rule
      $rulecollection = new PluginFusinvinventoryRuleInventoryCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='serial number + uuid';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusinvinventoryRuleInventory';
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = array();
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "globalcriteria";
      $input['pattern']= 1;
      $input['condition']=0;
      $rulecriteria->add($input);
      $input = array();
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "globalcriteria";
      $input['pattern']= 2;
      $input['condition']=0;
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = array();
      $input['rules_id'] = $rule_id;
      $input['action_type'] = 'assign';
      $input['field'] = '_import';
      $input['value'] = '1';
      $ruleaction->add($input);

      // *** Create second rule
      $rulecollection = new PluginFusinvinventoryRuleInventoryCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='mac address';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusinvinventoryRuleInventory';
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = array();
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "globalcriteria";
      $input['pattern']= 3;
      $input['condition']=0;
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = array();
      $input['rules_id'] = $rule_id;
      $input['action_type'] = 'assign';
      $input['field'] = '_import';
      $input['value'] = '1';
      $ruleaction->add($input);

      // *** Create third rule
      $rulecollection = new PluginFusinvinventoryRuleInventoryCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='serial number';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusinvinventoryRuleInventory';
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = array();
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "globalcriteria";
      $input['pattern']= 1;
      $input['condition']=0;
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = array();
      $input['rules_id'] = $rule_id;
      $input['action_type'] = 'assign';
      $input['field'] = '_import';
      $input['value'] = '1';
      $ruleaction->add($input);

      // *** Add rule for import in unknown devices
      $rulecollection = new PluginFusinvinventoryRuleInventoryCollection();
      $input = array();
      $input['is_active']=1;
      $input['name']='unknown device';
      $input['match']='AND';
      $input['sub_type'] = 'PluginFusinvinventoryRuleInventory';
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = array();
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "mac";
      $input['pattern']= "*";
      $input['condition']=0;
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = array();
      $input['rules_id'] = $rule_id;
      $input['action_type'] = 'assign';
      $input['field'] = '_import_unknowndevice';
      $input['value'] = '1';
      $ruleaction->add($input);
   }


   function prepareInputDataForProcess($input,$params) {

      return $input;
   }


  

}


?>
