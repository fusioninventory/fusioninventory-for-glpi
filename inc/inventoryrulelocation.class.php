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
   @author    Walid Nouh
   @co-author David Durieux
   @copyright Copyright (c) 2010-2016 FusionInventory team
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

class PluginFusioninventoryInventoryRuleLocation extends Rule {

   static $rightname = "plugin_fusioninventory_rulelocation";

   // From Rule
   //public $right='rule_import';
   public $can_sort=TRUE;
   public $specific_parameters = FALSE;

   const PATTERN_CIDR     = 333;
   const PATTERN_NOT_CIDR = 334;


   function getTitle() {
      return __('Location rules', 'fusioninventory');

   }



   function preProcessPreviewResults($output) {
      return $output;
   }



   function maxActionsCount() {
      // Unlimited
      return 2;
   }



   function executeActions($output, $params) {

      PluginFusioninventoryToolbox::logIfExtradebug(
         "pluginFusioninventory-locationrules",
         "execute action\n"
      );

      if (count($this->actions)) {
         foreach ($this->actions as $action) {
            switch ($action->fields["action_type"]) {
               case "assign" :
                  PluginFusioninventoryToolbox::logIfExtradebug(
                     "pluginFusioninventory-locationrules",
                     "value ".$action->fields["value"]."\n"
                  );
                  $output[$action->fields["field"]] = $action->fields["value"];
                  break;

               case "regex_result" :
                  $res = '';
                  if (isset($this->regex_results[0])) {
                     $res .= RuleAction::getRegexResultById($action->fields["value"],
                                                            $this->regex_results[0]);
                  } else {
                     $res .= $action->fields["value"];
                  }
                  if ($res != '') {
                     $entities_id = 0;
                     if (isset($_SESSION["plugin_fusioninventory_entity"])
                             && $_SESSION["plugin_fusioninventory_entity"] > 0) {
                        $entities_id = $_SESSION["plugin_fusioninventory_entity"];
                     }
                     $res = Dropdown::importExternal(
                             getItemTypeForTable(
                                     getTableNameForForeignKeyField(
                                             $action->fields['field'])),
                             $res,
                             $entities_id);
                  }
                  $output[$action->fields["field"]] = $res;
                  break;
            }
         }
      }
      return $output;
   }



   function getCriterias() {

      $criterias = array ();

      $criterias['tag']['field']     = 'name';
      $criterias['tag']['name']      = __('FusionInventory tag', 'fusioninventory');


      $criterias['domain']['field']     = 'name';
      $criterias['domain']['name']      = __('Domain');


      $criterias['subnet']['field']     = 'name';
      $criterias['subnet']['name']      = __('Subnet');

      $criterias['ip']['field']     = 'name';
      $criterias['ip']['name']      = __('Address')." ".__('IP');


      $criterias['name']['field']     = 'name';
      $criterias['name']['name']      = __('Computer\'s name');


      $criterias['serial']['field']     = 'name';
      $criterias['serial']['name']      = __('Serial Number');

      $criterias['oscomment']['field']     = 'name';
      $criterias['oscomment']['name']      = __('Operating system').'/'.__('Comment');

      return $criterias;
   }



   function getActions() {

      $actions = array();

      $actions['locations_id']['name']  = __('Location');

      $actions['locations_id']['type']  = 'dropdown';
      $actions['locations_id']['table'] = 'glpi_locations';
      $actions['locations_id']['force_actions'] = array('assign', 'regex_result');

/*
      $actions['_affect_entity_by_tag']['name'] = __('Entity from TAG');

      $actions['_affect_entity_by_tag']['type'] = 'text';
      $actions['_affect_entity_by_tag']['force_actions'] = array('regex_result');
*/
      $actions['_ignore_import']['name'] =
                     __('Ignore in FusionInventory import', 'fusioninventory');

      $actions['_ignore_import']['type'] = 'yesonly';

      return $actions;
   }



   function displayAdditionalRuleCondition($condition, $criteria, $name, $value, $test=FALSE) {
      if ($test) {
         return FALSE;
      }

      switch ($condition) {
         case Rule::PATTERN_FIND:
            return FALSE;
            break;

         case PluginFusioninventoryInventoryRuleImport::PATTERN_IS_EMPTY :
            Dropdown::showYesNo($name, 0, 0);
            return TRUE;

         case Rule::PATTERN_EXISTS:
            echo Dropdown::showYesNo($name, 1, 0);
            return TRUE;

         case Rule::PATTERN_DOES_NOT_EXISTS:
            echo Dropdown::showYesNo($name, 1, 0);
            return TRUE;

      }

      return FALSE;
   }


   /**
    * Add more criteria specific to this type of rule
   **/
   static function addMoreCriteria($criterion='') {
      if ($criterion == 'ip'
              || $criterion == 'subnet') {
         return array(self::PATTERN_CIDR => __('is CIDR', 'fusioninventory'),
                      self::PATTERN_NOT_CIDR => __('is not CIDR', 'fusioninventory'));
      }
      return array();
   }



   function checkCriteria(&$criteria, &$input) {

      $res = parent::checkCriteria($criteria, $input);

      if (in_array($criteria->fields["condition"], array(self::PATTERN_CIDR))) {
         $condition = $criteria->fields['condition'];
         $pattern   = $criteria->fields['pattern'];
         $value = $this->getCriteriaValue($criteria->fields["criteria"],
                                          $criteria->fields["condition"],
                                          $input[$criteria->fields["criteria"]]);

         list ($subnet, $bits) = explode('/', $pattern);
         $subnet = ip2long($subnet);
         $mask = -1 << (32 - $bits);
         $subnet &= $mask; # nb: in case the supplied subnet wasn't correctly aligned

         if (is_array($value)) {
            foreach ($value as $ip) {
               if (isset($ip) && $ip != '') {
                  $ip = ip2long($ip);
                  if (($ip & $mask) == $subnet) {
                     $res = TRUE;
                     break 1;
                  }
               }
            }
         } else {
            if (isset($value) && $value != '') {
               $ip = ip2long($value);
               if (($ip & $mask) == $subnet) {
                  $res = TRUE;
               }
            }
         }
      } else if (in_array($criteria->fields["condition"], array(self::PATTERN_NOT_CIDR))) {
         $condition = $criteria->fields['condition'];
         $pattern   = $criteria->fields['pattern'];
         $value = $this->getCriteriaValue($criteria->fields["criteria"],
                                          $criteria->fields["condition"],
                                          $input[$criteria->fields["criteria"]]);

         list ($subnet, $bits) = explode('/', $pattern);
         $subnet = ip2long($subnet);
         $mask = -1 << (32 - $bits);
         $subnet &= $mask; # nb: in case the supplied subnet wasn't correctly aligned

         if (is_array($value)) {
            $resarray = TRUE;
            foreach ($value as $ip) {
               if (isset($ip) && $ip != '') {
                  $ip = ip2long($ip);
                  if (($ip & $mask) == $subnet) {
                     $resarray = FALSE;
                  }
               }
            }
            $res = $resarray;
         } else {
            if (isset($value) && $value != '') {
               $ip = ip2long($value);
               if (($ip & $mask) != $subnet) {
                  $res = TRUE;
               }
            }
         }
      }

      return $res;
   }

}

?>
