<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage entity rules for computer.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Walid Nouh
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage entity rules for computer.
 */
class PluginFusioninventoryInventoryRuleEntity extends Rule {

   /**
    * Set these rules can be sorted
    *
    * @var boolean
    */
   public $can_sort=true;

   /**
    * Set these rules don't have specific parameters
    *
    * @var boolean
    */
   public $specific_parameters = false;

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_ruleentity';

   const PATTERN_CIDR     = 333;
   const PATTERN_NOT_CIDR = 334;


   /**
    * Get name of this type by language of the user connected
    *
    * @return string name of this type
    */
   function getTitle() {
      return __('Entity rules', 'fusioninventory');
   }


   /**
    * Make some changes before process review result
    *
    * @param array $output
    * @return array
    */
   function preProcessPreviewResults($output) {
      return $output;
   }


   /**
    * Define maximum number of actions possible in a rule
    *
    * @return integer
    */
   function maxActionsCount() {
      return 2;
   }


   /**
    * Code execution of actions of the rule
    *
    * @param array $output
    * @param array $params
    * @return array
    */
   function executeActions($output, $params, array $input = []) {

      PluginFusioninventoryToolbox::logIfExtradebug(
         "pluginFusioninventory-rules-entity",
         "execute actions, data:\n". print_r($output, true). "\n" . print_r($params, true)
      );

      PluginFusioninventoryToolbox::logIfExtradebug(
         "pluginFusioninventory-rules-entity",
         "execute actions: ". count($this->actions) ."\n"
      );

      if (count($this->actions)) {
         foreach ($this->actions as $action) {
            PluginFusioninventoryToolbox::logIfExtradebug(
               "pluginFusioninventory-rules-entity",
               "- action: ". $action->fields["action_type"] ." for: ". $action->fields["field"] ."\n"
            );

            switch ($action->fields["action_type"]) {
               case "assign" :
                  PluginFusioninventoryToolbox::logIfExtradebug(
                     "pluginFusioninventory-rules-entity",
                     "- value ".$action->fields["value"]."\n"
                  );
                  // todo: If always for an entity, use entities_id, no?
                  $output[$action->fields["field"]] = $action->fields["value"];
                  break;

               case "regex_result" :
                  //Assign entity using the regex's result
                  if ($action->fields["field"] == "_affect_entity_by_tag") {
                     PluginFusioninventoryToolbox::logIfExtradebug(
                        "pluginFusioninventory-rules-entity",
                        "- value ".$action->fields["value"]."\n"
                     );
                     //Get the TAG from the regex's results
                     $res = RuleAction::getRegexResultById($action->fields["value"],
                                                           $this->regex_results[0]);
                     if (!is_null($res)) {
                        //Get the entity associated with the TAG
                        $target_entity = Entity::getEntityIDByTag($res);
                        if ($target_entity != '') {
                           $output["entities_id"]=$target_entity;
                           PluginFusioninventoryToolbox::logIfExtradebug(
                              "pluginFusioninventory-rules-entity",
                              "- set entity: ".$target_entity."\n"
                           );
                        } else {
                           $output['pass_rule'] = true;
                        }
                     }
                  }
                  break;
            }
         }
      }
      return $output;
   }


   /**
    * Get the criteria available for the rule
    *
    * @return array
    */
   function getCriterias() {

      $criterias = [];

      $criterias['tag']['field']     = 'name';
      $criterias['tag']['name']      = __('FusionInventory tag', 'fusioninventory');

      $criterias['domain']['field']     = 'name';
      $criterias['domain']['name']      = __('Domain');

      $criterias['subnet']['field']     = 'name';
      $criterias['subnet']['name']      = __('Subnet');

      $criterias['ip']['field']     = 'name';
      $criterias['ip']['name']      = __('IP Address', 'fusioninventory');

      $criterias['name']['field']     = 'name';
      $criterias['name']['name']      = __("Computer's name", 'fusioninventory');

      $criterias['serial']['field']     = 'name';
      $criterias['serial']['name']      = __('Serial number');

      $criterias['oscomment']['field']     = 'name';
      $criterias['oscomment']['name']      = OperatingSystem::getTypeName(1).'/'.__('Comments');

      return $criterias;
   }


   /**
    * Get the actions available for the rule
    *
    * @return array
    */
   function getActions() {

      $actions = [];
      $actions['entities_id']['name']  = Entity::getTypeName(1);

      $actions['entities_id']['type']  = 'dropdown';
      $actions['entities_id']['table'] = 'glpi_entities';

      $actions['locations_id']['name']  = __('Location');

      $actions['locations_id']['type']  = 'dropdown';
      $actions['locations_id']['table'] = 'glpi_locations';

      $actions['_affect_entity_by_tag']['name'] = __('Entity from TAG');

      $actions['_affect_entity_by_tag']['type'] = 'text';
      $actions['_affect_entity_by_tag']['force_actions'] = ['regex_result'];

      $actions['_ignore_import']['name'] =
                     __('Ignore in FusionInventory import', 'fusioninventory');

      $actions['_ignore_import']['type'] = 'yesonly';

      return $actions;
   }


   /**
    * Add additional rule conditions for criteria
    *
    * @param integer $condition
    * @param string $criteria
    * @param string $name
    * @param string $value
    * @param boolean $test
    * @return boolean
    */
   function displayAdditionalRuleCondition($condition, $criteria, $name, $value, $test = false) {
      if ($test) {
         return false;
      }

      switch ($condition) {

         case Rule::PATTERN_FIND:
            return false;

         case PluginFusioninventoryInventoryRuleImport::PATTERN_IS_EMPTY :
            Dropdown::showYesNo($name, 0, 0);
            return true;

         case Rule::PATTERN_EXISTS:
            echo Dropdown::showYesNo($name, 1, 0);
            return true;

         case Rule::PATTERN_DOES_NOT_EXISTS:
            echo Dropdown::showYesNo($name, 1, 0);
            return true;

      }
      return false;
   }


   /**
    * Add more criteria
    *
    * @param string $criterion
    * @return array
    */
   static function addMoreCriteria($criterion = '') {
      if ($criterion == 'ip'
              || $criterion == 'subnet') {
         return [self::PATTERN_CIDR => __('is CIDR', 'fusioninventory'),
                      self::PATTERN_NOT_CIDR => __('is not CIDR', 'fusioninventory')];
      }
      return [];
   }


   /**
    * Check the criteria
    *
    * @param object $criteria
    * @param array $input
    * @return boolean
    */
   function checkCriteria(&$criteria, &$input) {

      $res = parent::checkCriteria($criteria, $input);

      if (in_array($criteria->fields["condition"], [self::PATTERN_CIDR])) {
         $pattern   = $criteria->fields['pattern'];
         $value = $this->getCriteriaValue($criteria->fields["criteria"],
                                          $criteria->fields["condition"],
                                          $input[$criteria->fields["criteria"]]);

         list ($subnet, $bits) = explode('/', $pattern);
         $subnet = ip2long($subnet);
         $mask = -1 << (32 - $bits);
         $subnet &= $mask; // nb: in case the supplied subnet wasn't correctly aligned

         if (is_array($value)) {
            foreach ($value as $ip) {
               if (isset($ip) && $ip != '') {
                  $ip = ip2long($ip);
                  if (($ip & $mask) == $subnet) {
                     $res = true;
                     break 1;
                  }
               }
            }
         } else {
            if (isset($value) && $value != '') {
               $ip = ip2long($value);
               if (($ip & $mask) == $subnet) {
                  $res = true;
               }
            }
         }
      } else if (in_array($criteria->fields["condition"], [self::PATTERN_NOT_CIDR])) {
         $pattern   = $criteria->fields['pattern'];
         $value = $this->getCriteriaValue($criteria->fields["criteria"],
                                          $criteria->fields["condition"],
                                          $input[$criteria->fields["criteria"]]);

         list ($subnet, $bits) = explode('/', $pattern);
         $subnet = ip2long($subnet);
         $mask = -1 << (32 - $bits);
         $subnet &= $mask; // nb: in case the supplied subnet wasn't correctly aligned

         if (is_array($value)) {
            $resarray = true;
            foreach ($value as $ip) {
               if (isset($ip) && $ip != '') {
                  $ip = ip2long($ip);
                  if (($ip & $mask) == $subnet) {
                     $resarray = false;
                  }
               }
            }
            $res = $resarray;
         } else {
            if (isset($value) && $value != '') {
               $ip = ip2long($value);
               if (($ip & $mask) != $subnet) {
                  $res = true;
               }
            }
         }
      }

      return $res;
   }


   /**
    * Process the rule
    *
    * @param array &$input the input data used to check criterias
    * @param array &$output the initial ouput array used to be manipulate by actions
    * @param array &$params parameters for all internal functions
    * @param array &options array options:
    *                     - only_criteria : only react on specific criteria
    *
    * @return array the output updated by actions.
    *         If rule matched add field _rule_process to return value
    */
   function process(&$input, &$output, &$params, &$options = []) {

      if ($this->validateCriterias($options)) {
         $this->regex_results     = [];
         $this->criterias_results = [];
         $input = $this->prepareInputDataForProcess($input, $params);

         if ($this->checkCriterias($input)) {
            unset($output["_no_rule_matches"]);
            $refoutput = $output;
            $output = $this->executeActions($output, $params);
            if (!isset($output['pass_rule'])) {
               $this->updateOnlyCriteria($options, $refoutput, $output);
               //Hook
               $hook_params["sub_type"] = $this->getType();
               $hook_params["ruleid"]   = $this->fields["id"];
               $hook_params["input"]    = $input;
               $hook_params["output"]   = $output;
               Plugin::doHook("rule_matched", $hook_params);
               $output["_rule_process"] = true;
            }
         }
      }
   }
}
