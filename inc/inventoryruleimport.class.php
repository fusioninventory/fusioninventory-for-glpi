<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
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

/// FusionInventory Rules class
class PluginFusioninventoryInventoryRuleImport extends Rule {

   const PATTERN_IS_EMPTY              = 30;
   const PATTERN_ENTITY_RESTRICT       = 202;
   const RULE_ACTION_LINK              = 1;
   const RULE_ACTION_DENIED            = 2;

   const LINK_RESULT_DENIED            = 0;
   const LINK_RESULT_CREATE            = 1;
   const LINK_RESULT_LINK              = 2;

   var $restrict_matching = Rule::AND_MATCHING;


   // From Rule
   static public $right = 'rule_import';
   public $can_sort = TRUE;



   function getTitle() {

      return __('Rules for import and link computers');

   }


   function maxActionsCount() {
      // Unlimited
      return 1;
   }


   function getCriterias() {

      $criterias = array ();
      $criterias['entities_id']['table']     = 'glpi_entities';
      $criterias['entities_id']['field']     = 'entities_id';
      $criterias['entities_id']['name']      = __('Assets to import', 'fusioninventory').' : '.
                           __('Destination of equipment entity', 'fusioninventory');

      $criterias['entities_id']['linkfield'] = 'entities_id';
      $criterias['entities_id']['type']      = 'dropdown';
      $criterias['entities_id']['allow_condition'] = array(Rule::PATTERN_IS,
                                                           Rule::PATTERN_IS_NOT,
                                                           Rule::PATTERN_CONTAIN,
                                                           Rule::PATTERN_NOT_CONTAIN,
                                                           Rule::PATTERN_BEGIN,
                                                           Rule::PATTERN_END,
                                                           Rule::REGEX_MATCH,
                                                           Rule::REGEX_NOT_MATCH);

      $criterias['states_id']['table']           = 'glpi_states';
      $criterias['states_id']['field']           = 'name';
      $criterias['states_id']['name']            =
                     __('Search GLPI equipment with the status', 'fusioninventory');

      $criterias['states_id']['linkfield']       = 'state';
      $criterias['states_id']['type']            = 'dropdown';
      //Means that this criterion can only be used in a global search query
      $criterias['states_id']['is_global']       = TRUE;
      $criterias['states_id']['allow_condition'] = array(Rule::PATTERN_IS, Rule::PATTERN_IS_NOT);

      $criterias['model']['name']  = __('Assets to import', 'fusioninventory').' : '.__('Model');


      $criterias['mac']['name']    = __('Assets to import', 'fusioninventory').' : '.__('MAC');


      $criterias['ip']['name']     = __('Assets to import', 'fusioninventory').' : '.__('IP');


      $criterias['serial']['name'] = __('Assets to import', 'fusioninventory').' : '.
                                       __('Serial Number');


//      $criterias['hdserial']['name']        = __('Assets to import', 'fusioninventory').' : '.
//                   __('Hard disk serial number');

//
//      $criterias['partitionserial']['name'] = __('Assets to import', 'fusioninventory').' : '.
//       __('Partition serial number');


      $criterias['uuid']['name']  = __('Assets to import', 'fusioninventory').' : '.__('UUID');


      $criterias['mskey']['name'] = __('Assets to import', 'fusioninventory').' : '.
                                       __('Serial of the operating system');


      $criterias['name']['name']  = __('Assets to import', 'fusioninventory').' : '.__('Name');


      $criterias['tag']['name']   = __('Assets to import', 'fusioninventory').' : '.
                                       __('FusionInventory tag', 'fusioninventory');


      $criterias['osname']['name'] = __('Assets to import', 'fusioninventory').' : '.
                                       __('Operating system');


      $criterias['itemtype']['name'] = __('Assets to import', 'fusioninventory').' : '.
                                          __('Item type');

      $criterias['itemtype']['type']        = 'dropdown_itemtype';
      $criterias['itemtype']['is_global']       = FALSE;
      $criterias['itemtype']['allow_condition'] = array(Rule::PATTERN_IS, Rule::PATTERN_IS_NOT);

      $criterias['entityrestrict']['name']      = __('Restrict search in defined entity', 'fusioninventory');
      $criterias['entityrestrict']['allow_condition'] = array(PluginFusioninventoryInventoryRuleImport::PATTERN_ENTITY_RESTRICT);


      return $criterias;
   }



   function getActions() {

      $actions = array();
      $actions['_fusion']['name']        = __('FusionInventory link', 'fusioninventory');

      $actions['_fusion']['type']        = 'fusion_type';

      $actions['_ignore_import']['name'] = __('To be unaware of import');

      $actions['_ignore_import']['type'] = 'yesonly';

      return $actions;
   }



   static function getRuleActionValues() {
      return array(self::RULE_ACTION_LINK =>
                           __('Link', 'fusioninventory'),

                   self::RULE_ACTION_DENIED            => __('Import denied', 'fusioninventory'));

   }



   /**
    * Add more action values specific to this type of rule
    *
    * @param value the value for this action
    *
    * @return the label's value or ''
   **/
   function displayAdditionRuleActionValue($value) {

      $values = self::getRuleActionValues();
      if (isset($values[$value])) {
         return $values[$value];
      }
      return '';
   }



   function manageSpecificCriteriaValues($criteria, $name, $value) {

      switch ($criteria['type']) {
         case "state" :
            $link_array = array("0" => __('No'),

                                "1" => __('Yes')." : ".__('equal', 'fusioninventory'),

                                "2" => __('Yes')." : ".__('empty', 'fusioninventory'));


            Dropdown::showFromArray($name, $link_array, array('value' => $value));
      }
      return FALSE;
   }



   /**
    * Add more criteria specific to this type of rule
   **/
   static function addMoreCriteria($criterion='') {

      return array(Rule::PATTERN_FIND     => __('is already present in GLPI'),
                   self::PATTERN_IS_EMPTY => __('is empty in GLPI'),
                   self::PATTERN_ENTITY_RESTRICT => __('Yes'));

   }



   function getAdditionalCriteriaDisplayPattern($ID, $condition, $pattern) {

      if ($condition == self::PATTERN_IS_EMPTY) {
          return __('Yes');
      }
      if ($condition == self::PATTERN_ENTITY_RESTRICT) {
          return __('Yes');
      }
      if ($condition==self::PATTERN_IS || $condition==self::PATTERN_IS_NOT) {
         $crit = $this->getCriteria($ID);

         if (isset($crit['type'])) {
            switch ($crit['type']) {

               case "dropdown_itemtype":
                  $array = $this->getTypes();
                  return $array[$pattern];
                  break;

            }
         }
      }
      return FALSE;
   }



   function displayAdditionalRuleCondition($condition, $criteria, $name, $value, $test=FALSE) {

      if ($test) {
         return FALSE;
      }

      switch ($condition) {

         case self::PATTERN_ENTITY_RESTRICT:
            return TRUE;
            break;

         case Rule::PATTERN_EXISTS:
         case Rule::PATTERN_DOES_NOT_EXISTS:
         case Rule::PATTERN_FIND:
         case PluginFusioninventoryInventoryRuleImport::PATTERN_IS_EMPTY:
            Dropdown::showYesNo($name, 1, 0);
            return TRUE;

      }
      return FALSE;
   }



   function displayAdditionalRuleAction(array $action) {

      switch ($action['type']) {

         case 'fusion_type':
            Dropdown::showFromArray('value', self::getRuleActionValues());
            break;

         default:
            break;

      }
      return TRUE;
   }



   function getCriteriaByID($critname) {
      $criteria = array();
      foreach ($this->criterias as $criterion) {
         if ($critname == $criterion->fields['criteria']) {
            $criteria[] = $criterion;
         }
      }
      return $criteria;
   }



   function findWithGlobalCriteria($input) {
      global $DB, $CFG_GLPI;

      PluginFusioninventoryToolbox::logIfExtradebug(
         "pluginFusioninventory-rules",
         $input
      );

      $complex_criterias = array();
      $sql_where         = '';
      $sql_from          = '';
      $sql_where_computer  = '';
      $sql_from_computer   = '';
      $continue          = TRUE;
      $entityRestrict    = FALSE;
      $global_criteria   = array('model',
                                 'mac',
                                 'ip',
                                 'serial',
                                 'hdserial',
                                 'partitionserial',
                                 'uuid',
                                 'mskey',
                                 'name',
                                 'itemtype',
                                 'entityrestrict');
      $nb_crit_find = 0;
      foreach ($global_criteria as $criterion) {
         $criteria = $this->getCriteriaByID($criterion);
         if (!empty($criteria)) {
            foreach ($criteria as $crit) {
               if (!isset($input[$criterion]) || $input[$criterion] == '') {
                  $definition_criteria = $this->getCriteria($crit->fields['criteria']);
                  if (isset($definition_criteria['is_global'])
                          && $definition_criteria['is_global']) {
                     $continue = FALSE;
                  }
               } else if ($crit->fields["condition"] == Rule::PATTERN_FIND) {
                  $complex_criterias[] = $crit;
                  $nb_crit_find++;
               } else if ($crit->fields["condition"] == Rule::PATTERN_EXISTS) {
                  if (!isset($input[$crit->fields['criteria']])
                          OR empty($input[$crit->fields['criteria']])) {
                     return FALSE;
                  }
               } else if($crit->fields["criteria"] == 'itemtype') {
                  $complex_criterias[] = $crit;
               } else if ($crit->fields["criteria"] == 'entityrestrict') {
                  $entityRestrict = TRUE;
               }
            }
         }
      }

      foreach ($this->getCriteriaByID('states_id') as $crit) {
         $complex_criterias[] = $crit;
      }

      //If a value is missing, then there's a problem !
      if (!$continue) {
         return FALSE;
      }

      //No complex criteria
      if ((empty($complex_criterias)) OR ($nb_crit_find == 0)) {
         return TRUE;
      }

      //Build the request to check if the machine exists in GLPI
      $where_entity = "";
      if (isset($input['entities_id'])) {
         if (is_array($input['entities_id'])) {
            $where_entity .= implode($input['entities_id'], ', ');
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

      $itemtypeselected = array();
      if (isset($input['itemtype'])
              AND (is_array($input['itemtype']))
              AND ($itemtype_global != "0")) {

         $itemtypeselected = $input['itemtype'];
      } else if (isset($input['itemtype'])
              AND (!empty($input['itemtype']))
              AND ($itemtype_global > 0)) {

         $itemtypeselected[] = $input['itemtype'];
      } else {
         foreach($CFG_GLPI["state_types"] as $itemtype) {
            if (class_exists($itemtype)) {
               $itemtypeselected[] = $itemtype;
            }
         }
         $itemtypeselected[] = "PluginFusioninventoryUnknownDevice";
      }

      $sql_where = " `[typetable]`.`is_template` = '0' ";
      $sql_from = "`[typetable]`";
      $is_ip = FALSE;
      $is_mac = FALSE;
      foreach ($complex_criterias as $criteria) {
         if ($criteria->fields['criteria'] == 'ip') {
            $is_ip = TRUE;
         } else if ($criteria->fields['criteria'] == 'mac') {
            $is_mac = TRUE;
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
      } else if ($is_mac) {
         $sql_from .= " LEFT JOIN `glpi_networkports`
                           ON (`[typetable]`.`id` = `glpi_networkports`.`items_id`
                               AND `glpi_networkports`.`itemtype` = '[typename]')";
      }

      foreach ($complex_criterias as $criteria) {
         switch ($criteria->fields['criteria']) {

            case 'model':
               $sql_from_temp = " LEFT JOIN `glpi_".strtolower("[typename]")."models`
                                 ON (`glpi_".strtolower("[typename]")."models`.`id` = ".
                                     "`[typetable]`.`".strtolower("[typename]models_id")."`
                                     AND `glpi_networkports`.`itemtype` = '[typename]') ";
               $sql_where_temp = " AND `[typetable]`.`".strtolower("[typename]")."models_id` = '".
                                    $input["serial"]."'";

               $sql_from .= $sql_from_temp;
               $sql_where  .= $sql_where_temp;
               break;

            case 'mac':
               $sql_where_temp = " AND `glpi_networkports`.`mac` IN ('";
               if (is_array($input['mac'])) {
                  $sql_where_temp .= implode("', '", $input['mac']);
               } else {
                  $sql_where_temp .= $input['mac'];
               }
               $sql_where_temp .= "')";

               $sql_where .= $sql_where_temp;
               break;

            case 'ip':
               $sql_where .= " AND `glpi_ipaddresses`.`name` IN ('";
               if (is_array($input['ip'])) {
                  $sql_where .= implode("', '", $input['ip']);
               } else {
                  $sql_where .= $input['ip'];
               }
               $sql_where .= "')";
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

               } else {
                  $sql_where_temp = " AND `[typetable]`.`serial`='".$input["serial"]."'";
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
         }

         if ($entityRestrict) {
            if (isset($_SESSION['plugin_fusioninventory_entityrestrict'])) {
               $sql_where_temp .= " AND `[typetable]`.`entities_id`='".
                                       $_SESSION['plugin_fusioninventory_entityrestrict']."'";
            } else {
               $sql_where_temp .= " AND `[typetable]`.`entities_id`='0'";
            }
         }

         $item = new $itemtype();
         $sql_glpi = "SELECT `[typetable]`.`id`
                      FROM $sql_from_temp
                      WHERE $sql_where_temp
                      GROUP BY `[typetable]`.`id`
                      ORDER BY `[typetable]`.`is_deleted` ASC
                      LIMIT 1";
         if (strstr($sql_glpi, "`[typetable]`.`is_template` = '0'  AND")) {

            if ($itemtype == "PluginFusioninventoryUnknownDevice") {
               $sql_glpi = str_replace("`[typetable]`.`is_template` = '0'  AND", "", $sql_glpi);
            }
            $sql_glpi = str_replace("[typetable]", $item->getTable(), $sql_glpi);
            $sql_glpi = str_replace("[typename]", $itemtype, $sql_glpi);

            PluginFusioninventoryToolbox::logIfExtradebug(
               "pluginFusioninventory-rules",
               $sql_glpi."\n"
            );
            $result_glpi = $DB->query($sql_glpi);

            if ($DB->numrows($result_glpi) > 0) {
               while ($data=$DB->fetch_array($result_glpi)) {
                  $found = 1;
                  $this->criterias_results['found_equipment'][$itemtype][] = $data['id'];
               }
            }
         }
      }
      if ($found == "1") {
         return TRUE;
      }
      return FALSE;
   }



   /**
    * Execute the actions as defined in the rule
    *
    * @param $output the fields to manipulate
    * @param $params parameters
    *
    * @return the $output array modified
   **/
   function executeActions($output, $params) {
      if (isset($params['class'])) {
         $class = $params['class'];
      } else if (isset($_SESSION['plugin_fusioninventory_classrulepassed'])) {
         $classname = $_SESSION['plugin_fusioninventory_classrulepassed'];
         $class = new $classname();
      }

      $pfRulematchedlog = new PluginFusioninventoryRulematchedlog();
      $inputrulelog = array();
      $inputrulelog['date'] = date('Y-m-d H:i:s');
      $inputrulelog['rules_id'] = $this->fields['id'];
      if (isset($_SESSION['plugin_fusioninventory_classrulepassed'])) {
         $inputrulelog['method'] = $class->getMethod();
      }
      if (isset($_SESSION['plugin_fusioninventory_agents_id'])) {
         $inputrulelog['plugin_fusioninventory_agents_id'] =
                        $_SESSION['plugin_fusioninventory_agents_id'];
      }

      PluginFusioninventoryToolbox::logIfExtradebug(
         "pluginFusioninventory-rules",
         "execute action\n"
      );

      if (count($this->actions)) {
         foreach ($this->actions as $action) {
            if ($action->fields['field'] == '_fusion') {
               PluginFusioninventoryToolbox::logIfExtradebug(
                  "pluginFusioninventory-rules",
                  "value".$action->fields["value"]."\n"
               );

               if ($action->fields["value"] == self::RULE_ACTION_LINK) {
                  if (isset($this->criterias_results['found_equipment'])) {
                     foreach ($this->criterias_results['found_equipment'] as $itemtype=>$datas) {
                        $items_id = current($datas);
                        $output['found_equipment'] = array($items_id, $itemtype);
                        if (isset($_SESSION['plugin_fusioninventory_classrulepassed'])) {
                           $inputrulelog['items_id'] = $items_id;
                           $inputrulelog['itemtype'] = $itemtype;
                           $pfRulematchedlog->add($inputrulelog);
                           $pfRulematchedlog->cleanOlddata($items_id, $itemtype);
                           $class->rulepassed($items_id, $itemtype);
                           return $output;
                        } else {
                           $_SESSION['plugin_fusioninventory_rules_id'] = $this->fields['id'];
                           $output['action'] = self::LINK_RESULT_LINK;
                           return $output;
                        }
                     }
                  } else {
                     // Import into new equipment
                     $itemtype_found = 0;
                     if (count($this->criterias)) {
                        foreach ($this->criterias as $criteria){
                           if ($criteria->fields['criteria'] == 'itemtype') {
                              $itemtype = $criteria->fields['pattern'];
                              if (isset($_SESSION['plugin_fusioninventory_classrulepassed'])) {
                                 $_SESSION['plugin_fusioninventory_rules_id'] =
                                                $this->fields['id'];
                                 $class->rulepassed("0", $itemtype);
                                 $output['found_equipment'] = array(0, $itemtype);
                                 return $output;
                              } else {
                                 $_SESSION['plugin_fusioninventory_rules_id'] =
                                         $this->fields['id'];
                                 $output['action'] = self::LINK_RESULT_CREATE;
                                 return $output;
                              }
                              $itemtype_found = 1;
                           }
                        }
                     }
                     if ($itemtype_found == "0") {
                        if (isset($_SESSION['plugin_fusioninventory_classrulepassed'])) {
                           $_SESSION['plugin_fusioninventory_rules_id'] = $this->fields['id'];
                           $class->rulepassed("0", "PluginFusioninventoryUnknownDevice");
                           $output['found_equipment'] = array(0, "PluginFusioninventoryUnknownDevice");
                           return $output;
                        } else {
                           $_SESSION['plugin_fusioninventory_rules_id'] = $this->fields['id'];
                           $output['action'] = self::LINK_RESULT_CREATE;
                           return $output;
                        }
                     }
                  }
               } else if ($action->fields["value"] == self::RULE_ACTION_DENIED) {
                  $_SESSION['plugin_fusioninventory_rules_id'] = $this->fields['id'];
                  $output['action'] = self::LINK_RESULT_DENIED;
                  return $output;
               }
            } else if ($action->fields['field'] == '_ignore_import') {
               PluginFusioninventoryToolbox::logIfExtradebug(
                  "pluginFusioninventory-rules",
                  "value".$action->fields["value"]."\n"
               );
               $_SESSION['plugin_fusioninventory_rules_id'] = $this->fields['id'];
               $output['action'] = self::LINK_RESULT_DENIED;
               return $output;
            } else {
               // no import
               $itemtype_found = 0;
               if (count($this->criterias)) {
                  foreach ($this->criterias as $criteria){
                     if ($criteria->fields['criteria'] == 'itemtype') {
                        $itemtype = $criteria->fields['pattern'];
                        if (isset($_SESSION['plugin_fusioninventory_classrulepassed'])) {
                           $_SESSION['plugin_fusioninventory_rules_id'] = $this->fields['id'];
                           $class->rulepassed("0", $itemtype);
                           $output['found_equipment'] = array(0, $itemtype);
                           return $output;
                        } else {
                           $_SESSION['plugin_fusioninventory_rules_id'] = $this->fields['id'];
                           $output['action'] = self::LINK_RESULT_CREATE;
                           return $output;
                        }
                        $itemtype_found = 1;
                     }
                  }
               }
               if ($itemtype_found == "0") {
                  if (isset($_SESSION['plugin_fusioninventory_classrulepassed'])) {
                     $_SESSION['plugin_fusioninventory_rules_id'] = $this->fields['id'];
                     $class->rulepassed("0", "PluginFusioninventoryUnknownDevice");
                     $output['found_equipment'] = array(0, 'PluginFusioninventoryUnknownDevice');
                     return $output;
                  } else {
                     $_SESSION['plugin_fusioninventory_rules_id'] = $this->fields['id'];
                     $output['action'] = self::LINK_RESULT_CREATE;
                     return $output;
                  }
               }
            }
         }
      }
      return $output;
   }



   function displayCriteriaSelectPattern($name, $ID, $condition, $value="", $test=FALSE) {

      $crit    = $this->getCriteria($ID);
      $display = FALSE;
      $tested  = FALSE;

      foreach ($this->criterias as $criteria) {
         if ($criteria->fields['criteria'] == $name) {

            if ($criteria->fields['condition'] == Rule::PATTERN_CONTAIN
             || $criteria->fields['condition'] == Rule::PATTERN_NOT_CONTAIN
             || $criteria->fields['condition'] == Rule::PATTERN_BEGIN
             || $criteria->fields['condition'] == Rule::PATTERN_END
             || $criteria->fields['condition'] == Rule::REGEX_MATCH
             || $criteria->fields['condition'] == Rule::REGEX_NOT_MATCH) {

               $rc = new $this->rulecriteriaclass();
               Html::autocompletionTextField($rc, "pattern", array('name'  => $name,
                                                       'value' => $value,
                                                       'size'  => 70));
               return;
            }

            if (($criteria->fields['condition'] == Rule::PATTERN_IS
             || $criteria->fields['condition'] == Rule::PATTERN_IS_NOT)
                    AND ($name != "itemtype" AND $name != 'states_id')) {

               $rc = new $this->rulecriteriaclass();
               Html::autocompletionTextField($rc, "pattern", array('name'  => $name,
                                                       'value' => $value,
                                                       'size'  => 70));
               return;

            }
         }
      }

      if (isset($crit['type'])
                 && ($test
                     ||$condition == Rule::PATTERN_IS
                     || $condition == Rule::PATTERN_IS_NOT)) {

         switch ($crit['type']) {

            case "yesonly":
               Dropdown::showYesNo($name, $value, 0);
               $display = TRUE;
               break;

            case "yesno":
               Dropdown::showYesNo($name, $value);
               $display = TRUE;
               break;

            case "dropdown":
               Dropdown::show(getItemTypeForTable($crit['table']), array('name'  => $name,
                                                                         'value' => $value));
               $display = TRUE;
               break;

            case "dropdown_users":
               User::dropdown(array('value'  => $value,
                                    'name'   => $name,
                                    'right'  => 'all'));
               $display = TRUE;
               break;

            case "dropdown_itemtype":
               $types = $this->getTypes();
               ksort($types);
               Dropdown::showItemTypes($name, array_keys($types),
                                          array('value' => $value));
               $display = TRUE;
               break;

         }
         $tested = TRUE;
      }
      //Not a standard condition
      if (!$tested) {
        $display = $this->displayAdditionalRuleCondition($condition, $crit, $name, $value);
      }

      if (!$display) {
         $rc = new $this->rulecriteriaclass();
         Html::autocompletionTextField($rc, "pattern", array('name'  => $name,
                                                       'value' => $value,
                                                       'size'  => 70));
      }
   }



   function getTypes() {
      global $CFG_GLPI;

      $types = array();
      foreach($CFG_GLPI["state_types"] as $itemtype) {
         if (class_exists($itemtype)) {
            $item = new $itemtype();
            $types[$itemtype] = $item->getTypeName();
         }
      }
      $types["PluginFusioninventoryUnknownDevice"] =
                     PluginFusioninventoryUnknownDevice::getTypeName();
      return $types;
   }



   /**
   * Function used to display type specific criterias during rule's preview
   *
   * @param $fields fields values
   **/
   function showSpecificCriteriasForPreview($fields) {

      $entity_as_criteria = FALSE;
      foreach ($this->criterias as $criteria) {
         if ($criteria->fields['criteria'] == 'entities_id') {
            $entity_as_criteria = TRUE;
            break;
         }
      }
      if (!$entity_as_criteria) {
         echo "<input type='hidden' name='entities_id' value='".$_SESSION["glpiactive_entity"]."'>";
      }
   }



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
               echo "<td>".$class->getLink(TRUE)."</td>";
            }
            echo "</tr>";
         }
      }
      return $output;
   }
}

?>
