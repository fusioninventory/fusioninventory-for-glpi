<?php
/*
 * @version $Id: ruleimportcomputer.class.php 13602 2011-01-12 08:20:12Z walid $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
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

include_once(GLPI_ROOT."/plugins/fusioninventory/inc/rule.class.php");

/// FusionInventory Rules class
class PluginFusioninventoryRuleImportEquipment extends PluginFusioninventoryRule {

   const PATTERN_IS_EMPTY              = 30;
   const RULE_ACTION_LINK_OR_IMPORT    = 0;
   const RULE_ACTION_LINK_OR_NO_IMPORT = 1;

   var $restrict_matching = PluginFusioninventoryRule::AND_MATCHING;


   // From Rule
   public $right    = 'rule_ocs';
   public $can_sort = true;


   function canCreate() {
      return haveRight('rule_ocs', 'w');
   }


   function canView() {
      return haveRight('rule_ocs', 'r');
   }


   function getTitle() {
      global $LANG;

      return $LANG['rulesengine'][57];
   }


   function maxActionsCount() {
      // Unlimited
      return 1;
   }


   function preProcessPreviewResults($output) {
      return $output;
   }


   function getCriterias() {
      global $LANG;

      $criterias = array ();
      $criterias['entities_id']['table']     = 'glpi_entities';
      $criterias['entities_id']['field']     = 'entities_id';
      $criterias['entities_id']['name']      = $LANG['plugin_fusioninventory']['rulesengine'][152].' : '.$LANG['plugin_fusioninventory']['rules'][4];
      $criterias['entities_id']['linkfield'] = 'entities_id';
      $criterias['entities_id']['type']      = 'dropdown';

      $criterias['states_id']['table']           = 'glpi_states';
      $criterias['states_id']['field']           = 'name';
      $criterias['states_id']['name']            = $LANG['plugin_fusioninventory']['rules'][3];
      $criterias['states_id']['linkfield']       = 'state';
      $criterias['states_id']['type']            = 'dropdown';
      //Means that this criterion can only be used in a global search query
      $criterias['states_id']['is_global']       = true;
      $criterias['states_id']['allow_condition'] = array(PluginFusioninventoryRule::PATTERN_IS, PluginFusioninventoryRule::PATTERN_IS_NOT);

      $criterias['model']['name']           = $LANG['plugin_fusioninventory']['rulesengine'][152].' : '.$LANG['common'][22];

      $criterias['mac']['name']             = $LANG['plugin_fusioninventory']['rulesengine'][152].' : '.$LANG['networking'][15];

      $criterias['ip']['name']              = $LANG['plugin_fusioninventory']['rulesengine'][152].' : '.$LANG['networking'][14];

      $criterias['serial']['name']          = $LANG['plugin_fusioninventory']['rulesengine'][152].' : '.$LANG['common'][19];

//      $criterias['hdserial']['name']        = $LANG['plugin_fusioninventory']['rulesengine'][152].' : '.$LANG['plugin_fusioninventory']['rules'][13];
//
//      $criterias['partitionserial']['name'] = $LANG['plugin_fusioninventory']['rulesengine'][152].' : '.$LANG['plugin_fusioninventory']['rules'][14];

      $criterias['uuid']['name']            = $LANG['plugin_fusioninventory']['rulesengine'][152].' : '.$LANG['plugin_fusioninventory']['rules'][15];

      $criterias['mskey']['name']           = $LANG['plugin_fusioninventory']['rulesengine'][152].' : '.$LANG['computers'][10];

      $criterias['name']['name']            = $LANG['plugin_fusioninventory']['rulesengine'][152].' : '.$LANG['common'][16];

      $criterias['tag']['name']             = $LANG['plugin_fusioninventory']['rulesengine'][152].' : '.$LANG['plugin_fusioninventory']['rules'][16];

      $criterias['itemtype']['name']        = $LANG['plugin_fusioninventory']['rulesengine'][152].' : '.$LANG['state'][6];
      $criterias['itemtype']['type']        = 'dropdown_itemtype';
      $criterias['itemtype']['is_global']       = false;
      $criterias['itemtype']['allow_condition'] = array(PluginFusioninventoryRule::PATTERN_IS, PluginFusioninventoryRule::PATTERN_IS_NOT);

      return $criterias;
   }


   function getActions() {
      global $LANG;

      $actions = array();
      $actions['_fusion']['name']        = $LANG['plugin_fusioninventory']['rules'][5];
      $actions['_fusion']['type']        = 'fusion_type';

      $actions['_ignore_import']['name'] = $LANG['rulesengine'][132];
      $actions['_ignore_import']['type'] = 'yesonly';

      return $actions;
   }


   static function getRuleActionValues() {
      global $LANG;

      return array(self::RULE_ACTION_LINK_OR_IMPORT    => $LANG['plugin_fusioninventory']['rules'][7],
                   self::RULE_ACTION_LINK_OR_NO_IMPORT => $LANG['plugin_fusioninventory']['rules'][6]);
   }


   /**
    * Add more action values specific to this type of rule
    *
    * @param value the value for this action
    *
    * @return the label's value or ''
   **/
   function displayAdditionRuleActionValue($value) {
      global $LANG;

      $values = self::getRuleActionValues();
      if (isset($values[$value])) {
         return $values[$value];
      }
      return '';
   }


   function manageSpecificCriteriaValues($criteria, $name, $value) {
      global $LANG;

      switch ($criteria['type']) {
         case "state" :
            $link_array = array("0" => $LANG['choice'][0],
                                "1" => $LANG['choice'][1]." : ".$LANG['ocsconfig'][57],
                                "2" => $LANG['choice'][1]." : ".$LANG['ocsconfig'][56]);

            Dropdown::showFromArray($name, $link_array, array('value' => $value));
      }
      return false;
   }


   /**
    * Add more criteria specific to this type of rule
   **/
   static function addMoreCriteria($criterion='') {
      global $LANG;

      return array(PluginFusioninventoryRule::PATTERN_FIND                   => $LANG['plugin_fusioninventory']['rules'][11],
                   PluginFusioninventoryRuleImportEquipment::PATTERN_IS_EMPTY => $LANG['plugin_fusioninventory']['rules'][12]);
   }


   function getAdditionalCriteriaDisplayPattern($ID, $condition, $pattern) {
      global $LANG;

      if ($condition == PluginFusioninventoryRuleImportEquipment::PATTERN_IS_EMPTY) {
          return $LANG['choice'][1];
      }
      return false;
   }


   function displayAdditionalRuleCondition($condition, $criteria, $name, $value) {

      switch ($condition) {
         case PluginFusioninventoryRule::PATTERN_FIND :
         case PluginFusioninventoryRuleImportEquipment::PATTERN_IS_EMPTY :
            Dropdown::showYesNo($name, 0, 0);
            return true;
      }

      return false;
   }


   function displayAdditionalRuleAction($action, $params=array()) {
      global $LANG;

      switch ($action['type']) {
         case 'fusion_type' :
            Dropdown::showFromArray('value', self::getRuleActionValues());
            break;

         default :
            break;
      }
      return true;
   }


   function getCriteriaByID($ID) {

      foreach ($this->criterias as $criterion) {
         if ($ID == $criterion->fields['criteria']) {
            return $criterion;
         }
      }
      return array();
   }


   function findWithGlobalCriteria($input) {
      global $DB, $CFG_GLPI;

      $complex_criterias = array();
      $sql_where         = '';
      $sql_from          = '';
      $sql_where_networkequipment  = '';
      $sql_from_networkequipment   = '';
      $sql_where_computer  = '';
      $sql_from_computer   = '';
      $continue          = true;
      $global_criteria   = array('model',
                                 'mac',
                                 'ip',
                                 'serial',
                                 'hdserial',
                                 'partitionserial',
                                 'uuid',
                                 'mskey',
                                 'name');

      foreach ($global_criteria as $criterion) {
         $crit = $this->getCriteriaByID($criterion);
         if (!empty($crit)) {
            if (!isset($input[$criterion]) || $input[$criterion] == '') {
               $continue = false;
            } else if ($crit->fields["condition"] == PluginFusioninventoryRule::PATTERN_FIND) {
               $complex_criterias[] = $crit;
            }
         }
      }

      if (isset($this->criterias['states_id'])) {
         $complex_criterias[] = $this->getCriteriaByID('states_id');
      }

      //If no complex criteria or a value is missing, then there's a problem !
      if (!$continue) {
         return false;
      }

      //No complex criteria
      if (empty($complex_criterias)) {
         return true;
      }

      //Build the request to check if the machine exists in GLPI
      if (isset($input['entities_id'])) {
         if (is_array($input['entities_id'])) {
            $where_entity = implode($input['entities_id'],',');
         } else {
            $where_entity = $input['entities_id'];
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
      if (isset($input['itemtype']) AND (is_array($input['itemtype'])) AND ($itemtype_global != "0")) {
         $itemtypeselected = $input['itemtype'];      
      } else if (isset($input['itemtype']) AND (!empty($input['itemtype'])) AND ($itemtype_global != "0")) {
         $itemtypeselected[] = $input['itemtype'];
      } else {
         foreach($CFG_GLPI["state_types"] as $itemtype) {
            if (class_exists($itemtype)) {
               $itemtypeselected[] = $itemtype;
            }
         }
      }


//      $sql_where = " `[typetable]`.`entities_id` IN ($where_entity)
//                    AND `[typetable]`.`is_template` = '0' ";
      $sql_where = " `[typetable]`.`is_template` = '0' ";
      $sql_where_networkequipment = $sql_where;
      $sql_from = "`[typetable]`";
      $sql_from .= " LEFT JOIN `glpi_networkports`
                  ON (`[typetable]`.`id` = `glpi_networkports`.`items_id`
                      AND `glpi_networkports`.`itemtype` = '[typename]') ";
      $sql_from_networkequipment = $sql_from;

      foreach ($complex_criterias as $criteria) {
         switch ($criteria->fields['criteria']) {

            case 'model' :
               $sql_from_temp = " LEFT JOIN `glpi_".strtolower("[typename]")."models`
                                 ON (`glpi_".strtolower("[typename]")."models`.`id` = `[typetable]`.`".strtolower("[typename]models_id")."`
                                     AND `glpi_networkports`.`itemtype` = '[typename]') ";
               $sql_where_temp = " AND `[typetable]`.`".strtolower("[typename]")."models_id` = '".$input["serial"]."'";

               $sql_from .= $sql_from_temp;
               $sql_where  .= $sql_where_temp;
               $sql_from_networkequipment .= $sql_from_temp;
               $sql_where_networkequipment .= $sql_where_temp;
               break;

            case 'mac' :
               $sql_where_temp = " AND `glpi_networkports`.`mac` IN ('";
               $sql_where_networkequipment_temp = " AND `[typetable]`.`mac` IN ('";
               $sql_where_temp .= implode("', '",$input['mac']);
               $sql_where_networkequipment_temp .= implode("', '",$input['mac']);
               $sql_where_temp .= "')";
               $sql_where_networkequipment_temp .= "')";

               $sql_where  .= $sql_where_temp;
               $sql_where_networkequipment .= $sql_where_networkequipment_temp;
               break;
            
            case 'ip' :
               $sql_where .= " AND `glpi_networkports`.`ip` IN ";
               $sql_where_networkequipment .= " AND `[typetable]`.`ip` IN ";
               for ($i=0 ; $i<count($input["ip"]) ; $i++) {
                  $sql_where .= ($i>0 ? ',"' : '("').$input["ip"][$i].'"';
                  $sql_where_networkequipment .= ($i>0 ? ',"' : '("').$input["ip"][$i].'"';
               }
               $sql_where .= ")";
               $sql_where_networkequipment .= ")";
               break;

            case 'serial' :
               $sql_where_temp = " AND `[typetable]`.`serial`='".$input["serial"]."'";

               $sql_where .= $sql_where_temp;
               $sql_where_networkequipment .= $sql_where_temp;
               break;

            case 'name' :
               if ($criteria->fields['condition'] == self::PATTERN_IS_EMPTY) {
                  $sql_where .= " AND (`[typetable]`.`name`=''
                                       OR `[typetable]`.`name` IS NULL) ";
               } else {
                  $sql_where .= " AND (`[typetable]`.`name`='".$input['name']."') ";
               }
               break;

            case 'hdserial':

               break;

            case 'partitionserial':

               break;

            case 'mskey':
               $sql_where_computer  .= " AND `os_license_number`='".$input['mskey']."'";
               break;

            case 'states_id':
               if ($criteria->fields['condition'] == PluginFusioninventoryRule::PATTERN_IS) {
                  $condition = " IN ";
               } else {
                  $conditin = " NOT IN ";
               }
               $sql_where .= " AND `[typetable]`.`states_id`
                                 $condition ('".$criteria->fields['pattern']."')";
               break;

            case 'uuid':
               $sql_from_computer .= ' LEFT JOIN `glpi_plugin_fusinvinventory_computers`
                                 ON `glpi_plugin_fusinvinventory_computers`.`items_id` = `[typetable]`.`id`';
               $sql_where_computer .= ' AND `uuid`="'.$input['uuid'].'"';
               break;

         }
      }

      // Suivant le / les types, on cherche dans un ou plusieurs / tous les types
      $found = 0;
      foreach ($itemtypeselected as $itemtype) {
         if ($itemtype == "NetworkEquipment") {
            $sql_from_temp = $sql_from_networkequipment;
            $sql_where_temp = $sql_where_networkequipment;
         } else {
            $sql_from_temp = $sql_from;
            $sql_where_temp = $sql_where;
            if ($itemtype == "Computer") {
               $sql_from_temp .= $sql_from_computer;
               $sql_where_temp .= $sql_where_computer;
            }
         }

         $item = new $itemtype();
         $sql_glpi = "SELECT `[typetable]`.`id`
                      FROM $sql_from_temp
                      WHERE $sql_where_temp
                      ORDER BY `[typetable]`.`is_deleted` ASC";
         if ($itemtype == "PluginFusioninventoryUnknownDevice") {
            $sql_glpi = str_replace("`[typetable]`.`is_template` = '0'  AND", "", $sql_glpi);
         }

         if (strstr($sql_glpi, "`[typetable]`.`is_template` = '0'  AND")) {
            $sql_glpi = str_replace("[typetable]", $item->getTable(), $sql_glpi);
            $sql_glpi = str_replace("[typename]", $itemtype, $sql_glpi);
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
         return true;
      }
      return false;
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
      $classname = $_SESSION['plugin_fusioninventory_classrulepassed'];
      $class = new $classname();
      
      if (count($this->actions)) {
         foreach ($this->actions as $action) {
            if ($action->fields['field'] == '_fusion') {
               if ($action->fields["value"] == self::RULE_ACTION_LINK_OR_IMPORT) {
                  if (isset($this->criterias_results['found_equipment'])) {
                     foreach ($this->criterias_results['found_equipment'] as $itemtype=>$datas) {
                        $items_id = current($datas);
                        $class->rulepassed($items_id, $itemtype);
                     }
                  } else {
                     // Import into new equipment
                     $itemtype_found = 0;
                     if (count($this->criterias)) {
                        foreach ($this->criterias as $criteria){
                           if ($criteria->fields['criteria'] == 'itemtype') {
                              $itemtype = $criteria->fields['pattern'];
                              $class->rulepassed("0", $itemtype);
                              $itemtype_found = 1;
                           }
                        }
                     }                     
                     if ($itemtype_found == "0") {
                        $class->rulepassed("0", "PluginFusioninventoryUnknownDevice");
                     }
                     $output['action'] = 0;
                  }

               } else if ($action->fields["value"] == self::RULE_ACTION_LINK_OR_NO_IMPORT) {
                  if (isset($this->criterias_results['found_equipment'])) {
                     foreach ($this->criterias_results['found_equipment'] as $itemtype=>$datas) {
                        $items_id = current($datas);
                        $class->rulepassed($items_id, $itemtype);
                     }
                  } else {
                     // no import
                     $output['action'] = 1;
                  }
               }

            } else {
               // no import
               $output['action'] = 1;
            }
         }
      }
      return $output;
   }


   function displayCriteriaSelectPattern($name, $ID, $condition, $value="", $test=false) {
      global $CFG_GLPI;

      $crit    = $this->getCriteria($ID);
      $display = false;
      $tested  = false;

      if ($test
          || $condition == PluginFusioninventoryRule::PATTERN_EXISTS
          || $condition == PluginFusioninventoryRule::PATTERN_DOES_NOT_EXISTS) {
         Dropdown::showYesNo($name, 0, 0);
         $display = true;
         $tested  = true;

      } else if (isset($crit['type'])
                 && ($test
                     ||$condition == PluginFusioninventoryRule::PATTERN_IS
                     || $condition == PluginFusioninventoryRule::PATTERN_IS_NOT)) {

         switch ($crit['type']) {
            case "yesonly" :
               Dropdown::showYesNo($name, $crit['table'], 0);
               $display = true;
               break;

            case "yesno" :
               Dropdown::showYesNo($name, $crit['table']);
               $display = true;
               break;

            case "dropdown" :
               Dropdown::show(getItemTypeForTable($crit['table']), array('name'  => $name,
                                                                         'value' => $value));
               $display = true;
               break;

            case "dropdown_users" :
               User::dropdown(array('value'  => $value,
                                    'name'   => $name,
                                    'right'  => 'all'));
               $display = true;
               break;

            case "dropdown_itemtype" :
               $types = $this->getTypes();
               ksort($types);

               Dropdown::dropdownTypes($name, 0 ,array_keys($types));
               $display = true;
               break;

         }
         $tested = true;
      }
      //Not a standard condition
      if (!$tested) {
        $display = $this->displayAdditionalRuleCondition($condition, $crit, $name, $value);
      }

      if (!$display) {
         $rc = new $this->rulecriteriaclass();
         autocompletionTextField($rc, "pattern", array('name'  => $name,
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
      $types["PluginFusioninventoryUnknownDevice"] = PluginFusioninventoryUnknownDevice::getTypeName();
      return $types;
   }


}

?>