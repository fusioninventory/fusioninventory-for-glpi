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
 * This file is used to manage import rules for inventory (local, network
 * discovery, network inventory).
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
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
 * Manage import rules for inventory (local, network discovery, network
 * inventory).
 */
class PluginFusioninventoryInventoryRuleImport extends Rule {

   const PATTERN_IS_EMPTY              = 30;
   const PATTERN_ENTITY_RESTRICT       = 202;
   const PATTERN_NETWORK_PORT_RESTRICT = 203;
   const PATTERN_ONLY_CRITERIA_RULE    = 204;
   const RULE_ACTION_LINK              = 1;
   const RULE_ACTION_DENIED            = 2;

   const LINK_RESULT_DENIED            = 0;
   const LINK_RESULT_CREATE            = 1;
   const LINK_RESULT_LINK              = 2;

   /**
    * Define the matching only available
    *
    * @var type
    */
   var $restrict_matching = Rule::AND_MATCHING;

   /**
    * Define the right name
    *
    * @var type
    */
   static $rightname = 'rule_import';

   /**
    * Set these rules can be sorted
    *
    * @var type
    */
   public $can_sort = true;


   /**
    * Get name of this type by language of the user connected
    *
    * @return string name of this type
    */
   function getTitle() {
      return __('Rules for import and link computers');
   }


   /**
    * Define maximum number of actions possible in a rule
    *
    * @return integer
    */
   function maxActionsCount() {
      return 1;
   }


   /**
    * Get the criteria available for the rule
    *
    * @return array
    */
   function getCriterias() {

      $criterias = [
         'entities_id' => [
            'table'           => 'glpi_entities',
            'field'           => 'entities_id',
            'name'            => __('Asset', 'fusioninventory').' > '.
                                 __('Destination of equipment entity', 'fusioninventory'),
            'linkfield'       => 'entities_id',
            'type'            => 'dropdown',
            'is_global'       => false,
            'allow_condition' => [
                                    Rule::PATTERN_IS,
                                    Rule::PATTERN_IS_NOT,
                                    Rule::PATTERN_CONTAIN,
                                    Rule::PATTERN_NOT_CONTAIN,
                                    Rule::PATTERN_BEGIN,
                                    Rule::PATTERN_END,
                                    Rule::REGEX_MATCH,
                                    Rule::REGEX_NOT_MATCH
                                 ],
         ],
         'states_id' => [
            'table'           => 'glpi_states',
            'field'           => 'name',
            'name'            => __('General', 'fusioninventory').' > '.
                                 __('Search GLPI equipment with the status', 'fusioninventory'),
            'linkfield'       => 'state',
            'type'            => 'dropdown',
            'is_global'       => true,
            'allow_condition' => [Rule::PATTERN_IS, Rule::PATTERN_IS_NOT],
         ],
         'model' => [
            'name'            => __('Asset', 'fusioninventory').' > '.__('Model'),
         ],
         'mac' => [
            'name'            => __('Asset', 'fusioninventory').' > '.
                                 _n('Network port', 'Network ports', 1).' > '.__('MAC'),
         ],
         'ip' => [
            'name'            => __('Asset', 'fusioninventory').' > '.
                                 _n('Network port', 'Network ports', 1).' > '.__('IP'),
         ],
         'ifdescr' => [
            'name'            => __('Asset', 'fusioninventory').' > '.
                                 _n('Network port', 'Network ports', 1).' > '.
                                 __('Network port description', 'fusioninventory'),
         ],
         'ifnumber' => [
            'name'            => __('Asset', 'fusioninventory').' > '.
                                 _n('Network port', 'Network ports', 1).' > '.
                                 __('Port number'),
         ],
         'serial' => [
            'name'            => __('Asset', 'fusioninventory').' > '.
                                 __('Serial number'),
         ],
         'uuid' => [
            'name'            => __('Asset', 'fusioninventory').' > '.__('UUID'),
         ],
         'device_id' => [
            'name'            => __('Agent', 'fusioninventory').' > '.
                                 __('Device_id', 'fusioninventory'),
         ],
         'mskey' => [
            'name'            => __('Asset', 'fusioninventory').' > '.
                                 __('Serial of the operating system'),
         ],
         'name' => [
            'name'            => __('Asset', 'fusioninventory').' > '.__('Name'),
         ],
         'tag' => [
            'name'            => __('Agent', 'fusioninventory').' > '.
                                 __('FusionInventory tag', 'fusioninventory'),
         ],
         'osname' => [
            'name'            => __('Asset', 'fusioninventory').' > '.
                                 OperatingSystem::getTypeName(1),
         ],
         'itemtype' => [
            'name'            => __('Asset', 'fusioninventory').' > '.
                                 __('Item type'),
            'type'            => 'dropdown_itemtype',
            'is_global'       => false,
            'allow_condition' => [
                                    Rule::PATTERN_IS,
                                    Rule::PATTERN_IS_NOT,
                                    Rule::PATTERN_EXISTS,
                                    Rule::PATTERN_DOES_NOT_EXISTS,
                                 ],
         ],
         'domains_id' => [
            'table'           => 'glpi_domains',
            'field'           => 'name',
            'name'            => __('Asset', 'fusioninventory').' > '.__('Domain'),
            'linkfield'       => 'domain',
            'type'            => 'dropdown',
            'is_global'       => false,
         ],
         'entityrestrict' => [
            'name'            => __('General', 'fusioninventory').' > '.
                                 __('Restrict search in defined entity', 'fusioninventory'),
            'allow_condition' => [PluginFusioninventoryInventoryRuleImport::PATTERN_ENTITY_RESTRICT],
         ],
         'oscomment' => [
            'name'            => __('Asset', 'fusioninventory').' > '.
                                 OperatingSystem::getTypeName(1).'/'.__('Comments'),
            'allow_condition' => [
                                    Rule::PATTERN_IS,
                                    Rule::PATTERN_IS_NOT,
                                    Rule::PATTERN_CONTAIN,
                                    Rule::PATTERN_NOT_CONTAIN,
                                    Rule::PATTERN_BEGIN,
                                    Rule::PATTERN_END,
                                    Rule::REGEX_MATCH,
                                    Rule::REGEX_NOT_MATCH
                                 ],
         ],
         'link_criteria_port' => [
            'name'            => __('General', 'fusioninventory').' > '.
                                 __('Restrict criteria to same network port', 'fusioninventory'),
            'allow_condition' => [PluginFusioninventoryInventoryRuleImport::PATTERN_NETWORK_PORT_RESTRICT],
            'is_global'       => true
         ],
         'only_these_criteria' => [
            'name'            => __('General', 'fusioninventory').' > '.
                                 __('Only criteria of this rule in data', 'fusioninventory'),
            'allow_condition' => [PluginFusioninventoryInventoryRuleImport::PATTERN_ONLY_CRITERIA_RULE],
            'is_global'       => true
         ],

         /*
         'hdserial' => [
            'name'            => __('Assets to import', 'fusioninventory').' > '.
                                 __('Hard disk serial number'),
         ],
         'partitionserial' => [
            'name'            => __('Assets to import', 'fusioninventory').' > '.
                                 __('Partition serial number'),
         ],
          */
      ];
      return $criterias;
   }


   /**
    * Get the actions available for the rule
    *
    * @return array
    */
   function getActions() {

      $actions = [];
      $actions['_fusion']['name']        = __('FusionInventory link', 'fusioninventory');
      $actions['_fusion']['type']        = 'fusion_type';

      $actions['_ignore_import']['name'] = __('To be unaware of import (with log)', 'fusioninventory');
      $actions['_ignore_import']['type'] = 'yesonly';

      return $actions;
   }


   /**
    * Get action values
    *
    * @return array
    */
   static function getRuleActionValues() {

      return [self::RULE_ACTION_LINK   => __('Link', 'fusioninventory'),
                   self::RULE_ACTION_DENIED => __('Import denied (no log)', 'fusioninventory')];
   }


   /**
    * Add more action values specific to this type of rule
    *
    * @param string$value the value for this action
    * @return string the label's value or ''
    */
   function displayAdditionRuleActionValue($value) {

      $values = self::getRuleActionValues();
      if (isset($values[$value])) {
         return $values[$value];
      }
      return '';
   }


   /**
    * Manage the specific criteria values
    *
    * @param array $criteria
    * @param string $name
    * @param string $value
    * @return boolean
    */
   function manageSpecificCriteriaValues($criteria, $name, $value) {
      if ($criteria['type'] == 'state') {
         $link_array = ["0" => __('No'),
                             "1" => __('Yes')." : ".__('equal', 'fusioninventory'),
                             "2" => __('Yes')." : ".__('empty', 'fusioninventory')];
         Dropdown::showFromArray($name, $link_array, ['value' => $value]);
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
      if ($criterion == "entityrestrict") {
         return [self::PATTERN_ENTITY_RESTRICT => __('Yes')];
      } else if ($criterion == "link_criteria_port") {
         return [self::PATTERN_NETWORK_PORT_RESTRICT => __('Yes')];
      } else if ($criterion == "only_these_criteria") {
         return [self::PATTERN_ONLY_CRITERIA_RULE => __('Yes')];
      }
      return [
         Rule::PATTERN_FIND     => __('is already present in GLPI'),
         self::PATTERN_IS_EMPTY => __('is empty in GLPI')
            ];

   }


   /**
    * Get additional criteria pattern
    *
    * @param integer $ID
    * @param integer $condition
    * @param string $pattern
    * @return string|false
    */
   function getAdditionalCriteriaDisplayPattern($ID, $condition, $pattern) {

      if ($condition == self::PATTERN_IS_EMPTY
            || $condition == self::PATTERN_ENTITY_RESTRICT
            || $condition == self::PATTERN_NETWORK_PORT_RESTRICT
            || $condition == self::PATTERN_ONLY_CRITERIA_RULE) {
          return __('Yes');
      }
      if ($condition==self::PATTERN_IS || $condition==self::PATTERN_IS_NOT) {
         $crit = $this->getCriteria($ID);
         if (isset($crit['type'])
                 && $crit['type'] == 'dropdown_itemtype') {
            $array = $this->getItemTypesForRules();
            return $array[$pattern];
         }
      }
      return false;
   }


   /**
    * Display more conditions
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

         case self::PATTERN_ENTITY_RESTRICT:
         case self::PATTERN_NETWORK_PORT_RESTRICT:
            return true;

         case Rule::PATTERN_EXISTS:
         case Rule::PATTERN_DOES_NOT_EXISTS:
         case Rule::PATTERN_FIND:
         case PluginFusioninventoryInventoryRuleImport::PATTERN_IS_EMPTY:
            Dropdown::showYesNo($name, 1, 0);
            return true;

      }
      return false;
   }


   /**
    * Display more actions
    *
    * @param array $action
    * @param string $value
    * @return boolean
    */
   function displayAdditionalRuleAction(array $action, $value = '') {
      if ($action['type'] == 'fusion_type') {
         Dropdown::showFromArray('value', self::getRuleActionValues());
      }
      return true;
   }


   /**
    * Get criteria by criteria name
    *
    * @param string $critname
    * @return array
    */
   function getCriteriaByID($critname) {
      $criteria = [];
      foreach ($this->criterias as $criterion) {
         if ($critname == $criterion->fields['criteria']) {
            $criteria[] = $criterion;
         }
      }
      return $criteria;
   }


   /**
    * Find a device in GLPI
    *
    * @global object $DB
    * @global array $CFG_GLPI
    * @param array $input
    * @return boolean
    */
   function findWithGlobalCriteria($input) {
      global $DB, $CFG_GLPI;

      $pfConfig = new PluginFusioninventoryConfig();

      PluginFusioninventoryToolbox::logIfExtradebug(
         "pluginFusioninventory-rules",
         $input
      );

      $complex_criterias = [];
      $sql_where_computer= '';
      $sql_where_domain  = '';
      $sql_where_model   = '';
      $sql_from_computer = '';
      $sql_from_domain   = '';
      $sql_from_model    = '';
      $continue          = true;
      $entityRestrict    = false;
      $linkCriteriaPort  = false;
      $only_these_criteria_in_data = false;
      $nb_crit_find      = 0;
      $global_criteria   = ['model', 'mac', 'ip', 'serial', 'otherserial', 'hdserial',
                            'partitionserial', 'uuid', 'device_id',
                            'mskey', 'name', 'itemtype', 'domains_id',
                            'entityrestrict', 'oscomment', 'ifnumber',
                            'ifdescr', 'link_criteria_port',
                            'only_these_criteria'
                           ];
      $is_networkports = ['mac', 'ip', 'ifdescr', 'ifnumber'];
      $is_networkports_fusion = ['ifdescr'];
      $select_networkport = false;
      foreach ($global_criteria as $criterion) {
         $criteria = $this->getCriteriaByID($criterion);
         if (!empty($criteria)) {
            foreach ($criteria as $crit) {
               if (!isset($input[$criterion]) || $input[$criterion] == '') {
                  $definition_criteria = $this->getCriteria($crit->fields['criteria']);
                  if ($crit->fields["criteria"] == 'link_criteria_port') {
                     $linkCriteriaPort = true;
                  } else if ($crit->fields["criteria"] == 'only_these_criteria') {
                     $only_these_criteria_in_data = true;
                  } else if (isset($definition_criteria['is_global'])
                          && $definition_criteria['is_global']) {
                     $continue = false;
                  }
               } else if ($crit->fields["condition"] == Rule::PATTERN_FIND) {
                  $complex_criterias[] = $crit;
                  $nb_crit_find++;
               } else if ($crit->fields["condition"] == Rule::PATTERN_EXISTS) {
                  if (!isset($input[$crit->fields['criteria']])
                          OR empty($input[$crit->fields['criteria']])) {
                     return false;
                  }
               } else if ($crit->fields["criteria"] == 'itemtype') {
                  $complex_criterias[] = $crit;
               } else if ($crit->fields["criteria"] == 'entityrestrict') {
                  $entityRestrict = true;
               }
            }
         }
      }

      foreach ($this->getCriteriaByID('states_id') as $crit) {
         $complex_criterias[] = $crit;
      }

      // If a value is missing, then there's a problem !
      if (!$continue) {
         return false;
      }

      // check only_these_criteria
      if ($only_these_criteria_in_data) {
         $complex_criterias_strings = [];
         foreach ($global_criteria as $criterion) {
            $criteria = $this->getCriteriaByID($criterion);
            foreach ($criteria as $crit) {
               $complex_criterias_strings[] = $crit->fields["criteria"];
            }
         }
         foreach ($input as $key=>$crit) {
            if (!in_array($key, $complex_criterias_strings)
                  && $key != "class"
                  && !is_object($crit)) {
               return false;
            }
         }
      }

      // No complex criteria
      if ((empty($complex_criterias)) OR ($nb_crit_find == 0)) {
         return true;
      }

      // Build the request to check if the machine exists in GLPI
      $where_entity = "";//FIXME: not used
      if (isset($input['entities_id'])) {
         if (is_array($input['entities_id'])) {
            $where_entity .= implode(',', $input['entities_id']);
         } else {
            $where_entity .= $input['entities_id'];
         }
      }

      // Get all equipment type
      $itemtype_global = 0;
      foreach ($complex_criterias as $criteria) {
         if ($criteria->fields['criteria'] == "itemtype") {
            $itemtype_global++;
         }
      }

      $itemtypeselected = [];
      if (isset($input['itemtype'])
              AND (is_array($input['itemtype']))
              AND ($itemtype_global > 0)) {

         $itemtypeselected = $input['itemtype'];
      } else if (isset($input['itemtype'])
              AND (!empty($input['itemtype']))
              AND ($itemtype_global > 0)) {

         $itemtypeselected[] = $input['itemtype'];
      } else {
         foreach ($CFG_GLPI["state_types"] as $itemtype) {
            if (class_exists($itemtype)
               && ($itemtype != 'SoftwareLicense'
               && $itemtype != 'Certificate'
               && $itemtype != 'Appliance'
               && $itemtype != 'Line'
               && $itemtype != 'Cluster'
               && $itemtype != 'Contract'
               && $itemtype != 'SoftwareVersion'
               )) {
               $itemtypeselected[] = $itemtype;
            }
         }
         $itemtypeselected[] = "PluginFusioninventoryUnmanaged";
      }
      $sql_where = " `[typetable]`.`is_template` = '0' ";
      $sql_from  = "`[typetable]`";
      if ($linkCriteriaPort) {
         $is_ip          = false;
         $is_networkport = false;
         $is_networkport_fusion = false;
         foreach ($complex_criterias as $criteria) {
            if ($criteria->fields['criteria'] == 'ip') {
               $is_ip = true;
            } else if (in_array($criteria->fields['criteria'], $is_networkports)) {
               $is_networkport = true;
            }
            if (in_array($criteria->fields['criteria'], $is_networkports_fusion)) {
               $is_networkport_fusion = true;
            }
         }
         if ($is_ip) {
            $sql_from .= " LEFT JOIN `glpi_networkports`
                              ON (`[typetable]`.`id` = `glpi_networkports`.`items_id`
                                  AND `glpi_networkports`.`itemtype` = '[typename]')
                           LEFT JOIN `glpi_networknames`
                                ON `glpi_networknames`.`items_id`=`glpi_networkports`.`id`
                                   AND `glpi_networknames`.`itemtype`='NetworkPort'
                           LEFT JOIN `glpi_ipaddresses`
                                ON `glpi_ipaddresses`.`items_id`=`glpi_networknames`.`id`
                                   AND `glpi_ipaddresses`.`itemtype`='NetworkName'";
         } else if ($is_networkport) {
            $sql_from .= " LEFT JOIN `glpi_networkports`
                              ON (`[typetable]`.`id` = `glpi_networkports`.`items_id`
                                  AND `glpi_networkports`.`itemtype` = '[typename]')";
         }
         if ($is_networkport_fusion) {
            $sql_from .= " LEFT JOIN `glpi_plugin_fusioninventory_networkports`
                              ON (`glpi_plugin_fusioninventory_networkports`.`networkports_id` = `glpi_networkports`.`id`)";
         }
      } else {
         // 1 join per criterion
         foreach ($complex_criterias as $criteria) {
            if ($criteria->fields['criteria'] == 'ip') {
               $sql_from .= " LEFT JOIN `glpi_networkports` as networkports_".$criteria->fields['criteria']."
                                 ON (`[typetable]`.`id` = networkports_".$criteria->fields['criteria'].".`items_id`
                                     AND networkports_".$criteria->fields['criteria'].".`itemtype` = '[typename]')
                              LEFT JOIN `glpi_networknames`
                                   ON `glpi_networknames`.`items_id`=networkports_".$criteria->fields['criteria'].".`id`
                                      AND `glpi_networknames`.`itemtype`='NetworkPort'
                              LEFT JOIN `glpi_ipaddresses`
                                   ON `glpi_ipaddresses`.`items_id`=`glpi_networknames`.`id`
                                      AND `glpi_ipaddresses`.`itemtype`='NetworkName'";
            } else if (in_array($criteria->fields['criteria'], $is_networkports)) {
               $sql_from .= " LEFT JOIN `glpi_networkports` as networkports_".$criteria->fields['criteria']."
                                 ON (`[typetable]`.`id` = networkports_".$criteria->fields['criteria'].".`items_id`
                                     AND networkports_".$criteria->fields['criteria'].".`itemtype` = '[typename]')";
            }
            if (in_array($criteria->fields['criteria'], $is_networkports_fusion)) {
               $sql_from .= " LEFT JOIN `glpi_plugin_fusioninventory_networkports`
                                 ON (`glpi_plugin_fusioninventory_networkports`.`networkports_id` = networkports_".$criteria->fields['criteria'].".`id`)";
            }
         }
      }

      $selectSQLPort = [];
      foreach ($complex_criterias as $criteria) {
         switch ($criteria->fields['criteria']) {

            case 'model':
               $sql_from_model = " LEFT JOIN `glpi_[typenamefortable]models`
                                 ON (`glpi_[typenamefortable]models`.`id` = ".
                                     "`[typetable]`.`[typenamefortable]models_id`) ";
               $sql_where_model = " AND `glpi_[typenamefortable]models`.`name` = '".
                                    $input["model"]."'";
               break;

            case 'mac':
               $ntable = "`glpi_networkports`";
               if (!$linkCriteriaPort) {
                  $ntable = "networkports_".$criteria->fields['criteria'];
                  $selectSQLPort[] = $ntable.".`id` as portid_".$criteria->fields['criteria'];
               }
               $sql_where_temp = " AND ".$ntable.".`mac` IN ('";
               if (is_array($input['mac'])) {
                  $sql_where_temp .= implode("', '", $input['mac']);
               } else {
                  $sql_where_temp .= $input['mac'];
               }
               $sql_where_temp .= "')";

               $sql_where .= $sql_where_temp;
               $select_networkport = true;
               break;

            case 'ip':
               $sql_where .= " AND `glpi_ipaddresses`.`name` IN ('";
               if (is_array($input['ip'])) {
                  $sql_where .= implode("', '", $input['ip']);
               } else {
                  $sql_where .= $input['ip'];
               }
               $sql_where .= "')";
               $select_networkport = true;
               $selectSQLPort[] = "networkports_".$criteria->fields['criteria'].".`id` as portid_".$criteria->fields['criteria'];
               break;

            case 'ifdescr':
               $sql_where .= " AND `glpi_plugin_fusioninventory_networkports`.`ifdescr`='".$input['ifdescr']."'";
               $selectSQLPort[] = "networkports_".$criteria->fields['criteria'].".`id` as portid_".$criteria->fields['criteria'];
               $select_networkport = true;
               break;

            case 'ifnumber':
               $ntable = "`glpi_networkports`";
               if (!$linkCriteriaPort) {
                  $ntable = "networkports_".$criteria->fields['criteria'];
                  $selectSQLPort[] = $ntable.".`id` as portid_".$criteria->fields['criteria'];
               }
               $sql_where .= " AND ".$ntable.".`logical_number`='".$input['ifnumber']."'";
               $select_networkport = true;
               break;

            case 'serial':
               if (isset($input['itemtype'])
                       AND $input['itemtype'] == 'Computer'
                       AND isset($_SESSION["plugin_fusioninventory_manufacturerHP"])
                       AND preg_match("/^[sS]/", $input['serial'])) {

                  $serial2 = preg_replace("/^[sS]/", "", $input['serial']);
                  $sql_where_temp = " AND (`[typetable]`.`serial`='".$input["serial"]."'
                     OR `[typetable]`.`serial`='".$serial2."')";
                  $_SESSION["plugin_fusioninventory_serialHP"] = $serial2;

               } else if (isset($input['itemtype'])
                       AND $input['itemtype'] == 'Monitor'
                       AND $pfConfig->getValue('import_monitor_on_partial_sn') == 1
                       AND strlen($input["serial"]) >= 4) {
                  // Support partial match for monitor serial
                  $sql_where_temp = " AND `[typetable]`.`serial` LIKE '%".$input["serial"]."%'";
               } else {
                  $sql_where_temp = " AND `[typetable]`.`serial`='".$input["serial"]."'";
               }
               $sql_where .= $sql_where_temp;
               break;

            case 'otherserial':
               if ($criteria->fields['condition'] == self::PATTERN_IS_EMPTY) {
                  $sql_where_temp = " AND (`[typetable]`.`otherserial`=''
                                       OR `[typetable]`.`otherserial` IS NULL) ";
               } else {
                  $sql_where_temp = " AND (`[typetable]`.`otherserial`='".$input['otherserial']."') ";
               }
               $sql_where .= $sql_where_temp;
               break;

            case 'name':
               if ($criteria->fields['condition'] == self::PATTERN_IS_EMPTY) {
                  $sql_where_temp = " AND (`[typetable]`.`name`=''
                                       OR `[typetable]`.`name` IS NULL) ";
               } else {
                  $sql_where_temp = " AND (`[typetable]`.`name`='".$input['name']."') ";
               }
               $sql_where .= $sql_where_temp;
               break;

            case 'hdserial':

               break;

            case 'partitionserial':

               break;

            case 'mskey':
               $sql_where_computer  .= " AND `os_license_number`='".$input['mskey']."'";
               break;

            case 'states_id':
               $condition = "";
               if ($criteria->fields['condition'] == Rule::PATTERN_IS) {
                  $condition = " IN ";
               } else {
                  $condition = " NOT IN ";
               }
               $sql_where .= " AND `[typetable]`.`states_id`
                                 $condition ('".$criteria->fields['pattern']."')";
               break;

            case 'uuid':
               $sql_where_computer .= ' AND `uuid`="'.$input['uuid'].'"';
               break;

            case 'device_id':
               $sql_from_temp = " LEFT JOIN `glpi_plugin_fusioninventory_agents`
                                 ON `glpi_plugin_fusioninventory_agents`.`computers_id` = ".
                                     "`[typetable]`.`id` ";
               $sql_where_temp = " AND `glpi_plugin_fusioninventory_agents`.`device_id` = '".
                                    $input["device_id"]."'";

               $sql_from .= $sql_from_temp;
               $sql_where  .= $sql_where_temp;
               break;

            case 'domains_id':
               $sql_from_domain .= " LEFT JOIN `glpi_domains_items`
                                 ON `glpi_domains_items`.`items_id` = `[typetable]`.`id` AND `glpi_domains_items`.`itemtype` = '{$input['itemtype']}'";
               $sql_from_domain .= " LEFT JOIN `glpi_domains`
                                 ON `glpi_domains`.`id` = ".
                                     "`glpi_domains_items`.`domains_id` ";
               $sql_where_domain .= " AND `glpi_domains`.`name` = '".
                                    $input["domains_id"]."'";
               break;
         }
      }

      // Suivant le / les types, on cherche dans un ou plusieurs / tous les types
      $found = 0;
      PluginFusioninventoryToolbox::logIfExtradebug(
         "pluginFusioninventory-rules",
         "===============\n"
      );

      foreach ($itemtypeselected as $itemtype) {
         $sql_from_temp = "";
         $sql_where_temp = "";
         $sql_from_temp = $sql_from;
         $sql_where_temp = $sql_where;
         if ($itemtype == "Computer") {
            $sql_from_temp .= $sql_from_computer;
            $sql_where_temp .= $sql_where_computer;
            $sql_from_temp .= $sql_from_domain;
            $sql_where_temp .= $sql_where_domain;
         } else if ($itemtype == 'NetworkEquipment') {
            $sql_from_temp .= $sql_from_domain;
            $sql_where_temp .= $sql_where_domain;
         } else if ($itemtype == 'Printer') {
            $sql_from_temp .= $sql_from_domain;
            $sql_where_temp .= $sql_where_domain;
         } else if ($itemtype == 'PluginFusioninventoryUnmanaged') {
            $itemtype = 'PluginFusioninventoryUnmanaged';
            $sql_from_temp .= $sql_from_domain;
            $sql_where_temp .= $sql_where_domain;
         }

         if ($itemtype != 'PluginFusioninventoryUnmanaged') {
            $sql_from_temp .= $sql_from_model;
            $sql_where_temp .= $sql_where_model;
         }

         if ($entityRestrict) {
            if (isset($_SESSION['plugin_fusioninventory_entityrestrict'])) {
               $sql_where_temp .= " AND `[typetable]`.`entities_id`='".
                                       $_SESSION['plugin_fusioninventory_entityrestrict']."'";
            } else {
               $sql_where_temp .= " AND `[typetable]`.`entities_id`='0'";
            }
         }

         $item = new $itemtype;
         $sql_glpi = "SELECT `[typetable]`.`id`";
         if ($select_networkport) {
            if ($linkCriteriaPort) {
               $sql_glpi .= ", `glpi_networkports`.`id` as portid";
            } else if (count($selectSQLPort)) {
               $sql_glpi .= ", ".implode(", ", $selectSQLPort);
            }
         }
         $sql_glpi .= " FROM $sql_from_temp
                      WHERE $sql_where_temp
                      GROUP BY `[typetable]`.`id`
                      ORDER BY `[typetable]`.`is_deleted` ASC
                      LIMIT 1";
         if (strstr($sql_glpi, "`[typetable]`.`is_template` = '0'  AND")) {
            if (!$DB->fieldExists(getTableForItemType($itemtype), 'is_template')) {
               $sql_glpi = str_replace("`[typetable]`.`is_template` = '0'  AND", "", $sql_glpi);
            }
            if (!$DB->fieldExists(getTableForItemType($itemtype), 'is_deleted')) {
               $sql_glpi = str_replace("ORDER BY `[typetable]`.`is_deleted` ASC", "", $sql_glpi);
            }
            $sql_glpi = str_replace("[typetable]", $item->getTable(), $sql_glpi);
            $sql_glpi = str_replace("[typename]", $itemtype, $sql_glpi);
            $sql_glpi = str_replace("[typenamefortable]", strtolower($itemtype), $sql_glpi);

            PluginFusioninventoryToolbox::logIfExtradebug(
               "pluginFusioninventory-rules",
               $sql_glpi."\n"
            );
            $result_glpi = $DB->query($sql_glpi);

            if ($result_glpi) {
               if ($DB->numrows($result_glpi) > 0) {
                  while ($data=$DB->fetchArray($result_glpi)) {
                     $found = 1;
                     $this->criterias_results['found_equipment'][$itemtype][] = $data['id'];
                     $this->criterias_results['found_port'] = 0;
                     foreach ($data as $alias=>$value) {
                        if (strstr($alias, "portid")
                              && !is_null($value)
                              && is_numeric($value)
                              && $value > 0) {
                           $this->criterias_results['found_port'] = $value;
                        }
                     }
                  }
               }
            }
         }
      }
      if ($found == "1") {
         return true;
      }
      return false;
   }


   /**
    * Code execution of actions of the rule
    *
    * @param array $output
    * @param array $params
    * @return array
    */
   function executeActions($output, $params, array $input = []) {
      if (isset($params['class'])) {
         $class = $params['class'];
      } else if (isset($_SESSION['plugin_fusioninventory_classrulepassed'])
            && !empty($_SESSION['plugin_fusioninventory_classrulepassed'])) {
         $classname = $_SESSION['plugin_fusioninventory_classrulepassed'];
         if ($classname == "PluginFusioninventoryCommunicationNetworkInventory") {
            $class = new PluginFusioninventoryCommunicationNetworkInventory();
         } else {
            $class = new $classname();
         }
      }

      $pfRulematchedlog = new PluginFusioninventoryRulematchedlog();
      $inputrulelog = [];
      $inputrulelog['date'] = date('Y-m-d H:i:s');
      $inputrulelog['rules_id'] = $this->fields['id'];
      if (!isset($params['return'])) {
         if (isset($class)
               && method_exists($class, 'rulepassed')) {
            $inputrulelog['method'] = $class->getMethod();
         }
         if (isset($_SESSION['plugin_fusioninventory_agents_id'])) {
            $inputrulelog['plugin_fusioninventory_agents_id'] =
                           $_SESSION['plugin_fusioninventory_agents_id'];
         }
      }
      PluginFusioninventoryToolbox::logIfExtradebug(
         "pluginFusioninventory-rules",
         "execute actions, data:\n". print_r($output, true). "\n" . print_r($params, true)
      );

      PluginFusioninventoryToolbox::logIfExtradebug(
         "pluginFusioninventory-rules",
         "execute actions: ". count($this->actions) ."\n"
      );
      $_SESSION['plugin_fusioninventory_rules_id'] = $this->fields['id'];

      if (count($this->actions)) {
         foreach ($this->actions as $action) {
            if ($action->fields['field'] == '_fusion') {
               PluginFusioninventoryToolbox::logIfExtradebug(
                  "pluginFusioninventory-rules",
                  "- value".$action->fields["value"]."\n"
               );

               if ($action->fields["value"] == self::RULE_ACTION_LINK) {
                  if (isset($this->criterias_results['found_equipment'])) {
                     foreach ($this->criterias_results['found_equipment'] as $itemtype=>$datas) {
                        $items_id = current($datas);
                        $output['found_equipment'] = [$items_id, $itemtype];
                        if (isset($class)
                              && method_exists($class, 'rulepassed')) {
                           if (!isset($params['return'])) {
                              $inputrulelog['items_id'] = $items_id;
                              $inputrulelog['itemtype'] = $itemtype;
                              $critinput = $this->criteriaInput;
                              if (isset($critinput['class'])) {
                                 unset($critinput['class']);
                              }
                              $inputrulelog['criteria'] = exportArrayToDB(\Toolbox::addslashes_deep($critinput));
                              $pfRulematchedlog->add($inputrulelog);
                              $pfRulematchedlog->cleanOlddata($items_id, $itemtype);
                              $class->rulepassed($items_id, $itemtype, $this->criterias_results['found_port']);
                           }
                           return $output;
                        } else {
                           $output['action'] = self::LINK_RESULT_LINK;
                           return $output;
                        }
                     }
                  } else {
                     // Import into new equipment
                     $itemtype_found = 0;
                     if (count($this->criterias)) {
                        foreach ($this->criterias as $criteria) {
                           if ($criteria->fields['criteria'] == 'itemtype'
                                 AND !is_numeric($criteria->fields['pattern'])) {

                              $itemtype = $criteria->fields['pattern'];
                              if (isset($class)
                                    && method_exists($class, 'rulepassed')) {
                                 if (!isset($params['return'])) {
                                    $class->rulepassed("0", $itemtype);
                                 }
                                 $output['found_equipment'] = [0, $itemtype];
                                 return $output;
                              } else {
                                 $output['action'] = self::LINK_RESULT_CREATE;
                                 return $output;
                              }
                              $itemtype_found = 1;
                           }
                        }
                     }
                     if ($itemtype_found == "0") {
                        if (isset($class)
                              && method_exists($class, 'rulepassed')) {
                           if (!isset($params['return'])) {
                              $class->rulepassed("0", "PluginFusioninventoryUnmanaged");
                           }
                           $output['found_equipment'] = [0, "PluginFusioninventoryUnmanaged"];
                           return $output;
                        } else {
                           $output['action'] = self::LINK_RESULT_CREATE;
                           return $output;
                        }
                     }
                  }
               } else if ($action->fields["value"] == self::RULE_ACTION_DENIED) {
                  PluginFusioninventoryToolbox::logIfExtradebug(
                     "pluginFusioninventory-rules",
                     "- action denied\n"
                  );
                  $output['action'] = self::LINK_RESULT_DENIED;
                  return $output;
               }
            } else if ($action->fields['field'] == '_ignore_import') {
               PluginFusioninventoryToolbox::logIfExtradebug(
                  "pluginFusioninventory-rules",
                  "- ignore import\n"
               );
               $output['action'] = self::LINK_RESULT_DENIED;
               return $output;
            } else {
               PluginFusioninventoryToolbox::logIfExtradebug(
                  "pluginFusioninventory-rules",
                  "- no import\n"
               );
               // no import
               $itemtype_found = 0;
               if (count($this->criterias)) {
                  foreach ($this->criterias as $criteria) {
                     if ($criteria->fields['criteria'] == 'itemtype'
                           AND !is_numeric($criteria->fields['pattern'])) {
                        $itemtype = $criteria->fields['pattern'];
                        if (isset($class)
                              && method_exists($class, 'rulepassed')) {
                           if (!isset($params['return'])) {
                              $class->rulepassed("0", $itemtype);
                           }
                           $output['found_equipment'] = [0, $itemtype];
                           return $output;
                        } else {
                           $output['action'] = self::LINK_RESULT_CREATE;
                           return $output;
                        }
                        $itemtype_found = 1;
                     }
                  }
               }
               if ($itemtype_found == "0") {
                  if (isset($class)
                        && method_exists($class, 'rulepassed')) {
                     if (!isset($params['return'])) {
                        $class->rulepassed("0", "PluginFusioninventoryUnmanaged");
                     }
                     $output['found_equipment'] = [0, 'PluginFusioninventoryUnmanaged'];
                     return $output;
                  } else {
                     $output['action'] = self::LINK_RESULT_CREATE;
                     return $output;
                  }
               }
            }
         }
      }
      return $output;
   }


   /**
    * Display the pattern form selection
    *
    * @param string $name
    * @param integer $ID
    * @param integer $condition
    * @param string $value
    * @param boolean $test
    * @return type
    */
   function displayCriteriaSelectPattern($name, $ID, $condition, $value = "", $test = false) {

      $crit    = $this->getCriteria($ID);
      $display = false;
      $tested  = false;

      foreach ($this->criterias as $criteria) {
         if ($criteria->fields['criteria'] == $name) {

            if ($criteria->fields['condition'] == Rule::PATTERN_CONTAIN
             || $criteria->fields['condition'] == Rule::PATTERN_NOT_CONTAIN
             || $criteria->fields['condition'] == Rule::PATTERN_BEGIN
             || $criteria->fields['condition'] == Rule::PATTERN_END
             || $criteria->fields['condition'] == Rule::REGEX_MATCH
             || $criteria->fields['condition'] == Rule::REGEX_NOT_MATCH) {

               $rc = new $this->rulecriteriaclass();
               Html::autocompletionTextField($rc, "pattern", ['name'  => $name,
                                                       'value' => $value,
                                                       'size'  => 70]);
               return;
            }

            if (($criteria->fields['condition'] == Rule::PATTERN_IS
                        || $criteria->fields['condition'] == Rule::PATTERN_IS_NOT)
                    AND ($name != "itemtype" AND $name != 'states_id')) {

               $rc = new $this->rulecriteriaclass();
               Html::autocompletionTextField($rc, "pattern", ['name'  => $name,
                                                       'value' => $value,
                                                       'size'  => 70]);
               return;
            }
         }
      }

      if (isset($crit['type'])
                 && ($test
                     || $condition == Rule::PATTERN_IS
                     || $condition == Rule::PATTERN_IS_NOT)) {

         switch ($crit['type']) {

            case "yesonly":
               Dropdown::showYesNo($name, $value, 0);
               $display = true;
               break;

            case "yesno":
               Dropdown::showYesNo($name, $value);
               $display = true;
               break;

            case "dropdown":
               Dropdown::show(getItemTypeForTable($crit['table']), ['name'  => $name,
                                                                         'value' => $value]);
               $display = true;
               break;

            case "dropdown_users":
               User::dropdown(['value'  => $value,
                                    'name'   => $name,
                                    'right'  => 'all']);
               $display = true;
               break;

            case "dropdown_itemtype":
               $types = $this->getItemTypesForRules();
               Dropdown::showItemTypes($name, array_keys($types),
                                          ['value' => $value]);
               $display = true;
               break;

         }
      }
      //Not a standard condition
      if (!$tested) {
         $display = $this->displayAdditionalRuleCondition($condition, $crit, $name, $value);
      }

      if ($condition == PluginFusioninventoryInventoryRuleImport::PATTERN_ENTITY_RESTRICT
            || $condition == PluginFusioninventoryInventoryRuleImport::PATTERN_NETWORK_PORT_RESTRICT
            || $condition == PluginFusioninventoryInventoryRuleImport::PATTERN_ONLY_CRITERIA_RULE) {
         echo Html::hidden($name, ['value' => 1]);
         return;
      }

      if (!$display) {
         $rc = new $this->rulecriteriaclass();
         Html::autocompletionTextField($rc, "pattern", ['name'  => $name,
                                                       'value' => $value,
                                                       'size'  => 70]);
      }
   }


   /**
    * Get itemtypes have state_type and unmanaged devices
    *
    * @global array $CFG_GLPI
    * @return array
    */
   function getTypes() {
      global $CFG_GLPI;

      $types = [];
      foreach ($CFG_GLPI["state_types"] as $itemtype) {
         if (class_exists($itemtype)) {
            $item = new $itemtype();
            $types[$itemtype] = $item->getTypeName();
         }
      }
      $types["PluginFusioninventoryUnmanaged"] =
                     PluginFusioninventoryUnmanaged::getTypeName();
      $types[""] = __('No itemtype defined', 'fusioninventory');
      return $types;
   }


   /**
    * Display type specific criterias during rule's preview
    *
    * @param array $fields
    */
   function showSpecificCriteriasForPreview($fields) {

      $entity_as_criteria = false;
      foreach ($this->criterias as $criteria) {
         if ($criteria->fields['criteria'] == 'entities_id') {
            $entity_as_criteria = true;
            break;
         }
      }
      if (!$entity_as_criteria) {
         echo "<input type='hidden' name='entities_id' value='".$_SESSION["glpiactive_entity"]."'>";
      }
   }


   /**
    * Make some changes before process review result
    *
    * @param array $output
    * @return array
    */
   function preProcessPreviewResults($output) {

      //If ticket is assign to an object, display this information first
      if (isset($output["action"])) {
         echo "<tr class='tab_bg_2'>";
         echo "<td>".__('Action type')."</td>";
         echo "<td>";

         switch ($output["action"]) {

            case self::LINK_RESULT_LINK:
               echo __('Link');

               break;

            case self::LINK_RESULT_CREATE:
               echo __('Device created', 'fusioninventory');

               break;

            case self::LINK_RESULT_DENIED:
               echo __('Import denied', 'fusioninventory');

               break;

         }

         echo "</td>";
         echo "</tr>";
         if ($output["action"] != self::LINK_RESULT_DENIED
             && isset($output["found_equipment"])) {
            echo "<tr class='tab_bg_2'>";
            $className = $output["found_equipment"][1];
            $class = new $className;
            if ($class->getFromDB($output["found_equipment"][0])) {
               echo "<td>".__('Link')."</td>";
               echo "<td>".$class->getLink(true)."</td>";
            }
            echo "</tr>";
         }
      }
      return $output;
   }

   /**
    * Get itemtypes have state_type and unmanaged devices
    *
    * @global array $CFG_GLPI
    * @return array
    */
   static function getItemTypesForRules() {
      global $CFG_GLPI;

      $types = [];
      foreach ($CFG_GLPI["state_types"] as $itemtype) {
         if (class_exists($itemtype)) {
            $item = new $itemtype();
            $types[$itemtype] = $item->getTypeName();
         }
      }
      $types[""] = __('No itemtype defined', 'fusioninventory');
      ksort($types);
      return $types;
   }

   function prepareInputDataForProcess($input, $params) {
      $this->criteriaInput = $input;
      return $input;
   }
}
