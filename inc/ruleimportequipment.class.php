<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David Durieux
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

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

      $criterias['osname']['name']          = $LANG['plugin_fusioninventory']['rulesengine'][152].' : '.$LANG['computers'][9];

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


   function displayAdditionalRuleCondition($condition, $criteria, $name, $value, $test=false) {
      if ($test) {
         return false;
      }

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
      $criteria = array();
      foreach ($this->criterias as $criterion) {
         if ($ID == $criterion->fields['criteria']) {
            $criteria[] = $criterion;
         }
      }
      return $criteria;
   }


   function findWithGlobalCriteria($input) {
      global $DB, $CFG_GLPI;
      if (PluginFusioninventoryConfig::getValue($_SESSION["plugin_fusioninventory_moduleid"], 'extradebug')) {
         logInFile("xxx", print_r($input, true));
      }
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
                                 'name',
                                 'itemtype');
      $nb_crit_find = 0;
      foreach ($global_criteria as $criterion) {
         $criteria = $this->getCriteriaByID($criterion);
         if (!empty($criteria)) {
            foreach ($criteria as $crit) {
               if (!isset($input[$criterion]) || $input[$criterion] == '') {
                  $continue = false;
               } else if ($crit->fields["condition"] == PluginFusioninventoryRule::PATTERN_FIND) {
                  $complex_criterias[] = $crit;
                  $nb_crit_find++;
               } else if($crit->fields["criteria"] == 'itemtype') {
                  $complex_criterias[] = $crit;
               }
            }
         }
      }

      foreach ($this->getCriteriaByID('states_id') as $crit) {
         $complex_criterias[] = $crit;
      }

      //If a value is missing, then there's a problem !
      if (!$continue) {
         return false;
      }
      
      //No complex criteria
      if ((empty($complex_criterias)) OR ($nb_crit_find == 0)) {
         return true;
      }

      //Build the request to check if the machine exists in GLPI
      $where_entity = "";
      if (isset($input['entities_id'])) {
         if (is_array($input['entities_id'])) {
            $where_entity .= implode($input['entities_id'],',');
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
              AND ($itemtype_global != "0")) {

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
      $sql_from_networkequipment = $sql_from;
      $sql_from .= " LEFT JOIN `glpi_networkports`
                  ON (`[typetable]`.`id` = `glpi_networkports`.`items_id`
                      AND `glpi_networkports`.`itemtype` = '[typename]') ";

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
               if (is_array($input['mac'])) {
                  $sql_where_temp .= implode("', '",$input['mac']);
                  $sql_where_networkequipment_temp .= implode("', '",$input['mac']);
               } else {
                  $sql_where_temp .= $input['mac'];
                  $sql_where_networkequipment_temp .= $input['mac'];
               }
               $sql_where_temp .= "')";
               $sql_where_networkequipment_temp .= "')";

               $sql_where  .= $sql_where_temp;
               $sql_where_networkequipment .= $sql_where_networkequipment_temp;
               break;
            
            case 'ip' :
               $sql_where .= " AND `glpi_networkports`.`ip` IN ";
               $sql_where_networkequipment .= " AND `[typetable]`.`ip` IN ('";
               if (is_array($input['ip'])) {
                  $sql_where .= implode("', '",$input['ip']);
                  $sql_where_networkequipment .= implode("', '",$input['ip']);
               } else {
                  $sql_where .= $input['ip'];
                  $sql_where_networkequipment .= $input['ip'];
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
               $condition = "";
               if ($criteria->fields['condition'] == PluginFusioninventoryRule::PATTERN_IS) {
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
      if (PluginFusioninventoryConfig::getValue($_SESSION["plugin_fusioninventory_moduleid"], 'extradebug')) {
         logInFile("xxx", "===============\n");
      }
      foreach ($itemtypeselected as $itemtype) {
         $sql_from_temp = "";
         $sql_where_temp = "";
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
                      GROUP BY `[typetable]`.`id`
                      ORDER BY `[typetable]`.`is_deleted` ASC
                      ";
         if (strstr($sql_glpi, "`[typetable]`.`is_template` = '0'  AND")) {

            if ($itemtype == "PluginFusioninventoryUnknownDevice") {
               $sql_glpi = str_replace("`[typetable]`.`is_template` = '0'  AND", "", $sql_glpi);
            }
            $sql_glpi = str_replace("[typetable]", $item->getTable(), $sql_glpi);
            $sql_glpi = str_replace("[typename]", $itemtype, $sql_glpi);
            if (PluginFusioninventoryConfig::getValue($_SESSION["plugin_fusioninventory_moduleid"], 'extradebug')) {
               logInFile("xxx", $sql_glpi."\n");
            }
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
      if (count($this->actions)) {
         foreach ($this->actions as $action) {
            if ($action->fields['field'] == '_fusion') {
               if ($action->fields["value"] == self::RULE_ACTION_LINK_OR_NO_IMPORT) {
                  if (PluginFusioninventoryConfig::getValue($_SESSION["plugin_fusioninventory_moduleid"], 'extradebug')) {
                     logInFile("xxx", "Return true because link or Import\n");
                  }
                  return true;
               }
            }
         }
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
      if (isset($_SESSION['plugin_fusioninventory_classrulepassed'])) {
         $classname = $_SESSION['plugin_fusioninventory_classrulepassed'];
         $class = new $classname();
      }
      if (PluginFusioninventoryConfig::getValue($_SESSION["plugin_fusioninventory_moduleid"], 'extradebug')) {
         logInFile("xxx", "execute action\n");
      }
      if (count($this->actions)) {
         foreach ($this->actions as $action) {
            if ($action->fields['field'] == '_fusion') {
               if (PluginFusioninventoryConfig::getValue($_SESSION["plugin_fusioninventory_moduleid"], 'extradebug')) {
                  logInFile("xxx", "value".$action->fields["value"]."\n");
               }
               if ($action->fields["value"] == self::RULE_ACTION_LINK_OR_IMPORT) {
                  if (isset($this->criterias_results['found_equipment'])) {
                     foreach ($this->criterias_results['found_equipment'] as $itemtype=>$datas) {
                        $items_id = current($datas);
                        if (isset($_SESSION['plugin_fusioninventory_classrulepassed'])) {
                           $class->rulepassed($items_id, $itemtype);
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
                                 $class->rulepassed("0", $itemtype);
                              }
                              $itemtype_found = 1;
                           }
                        }
                     }
                     if ($itemtype_found == "0") {
                        if (isset($_SESSION['plugin_fusioninventory_classrulepassed'])) {
                           $class->rulepassed("0", "PluginFusioninventoryUnknownDevice");
                        }
                     }
                     $output['action'] = 0;
                  }

               } else if ($action->fields["value"] == self::RULE_ACTION_LINK_OR_NO_IMPORT) {
                  if (isset($this->criterias_results['found_equipment'])) {
                     foreach ($this->criterias_results['found_equipment'] as $itemtype=>$datas) {
                        $items_id = current($datas);
                        if (isset($_SESSION['plugin_fusioninventory_classrulepassed'])) {
                           $class->rulepassed($items_id, $itemtype);
                        }
                     }
                  } else {
                     // no import
                     $output['action'] = 2;
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


   /**
   * Function used to display type specific criterias during rule's preview
   *
   * @param $fields fields values
   **/
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

//   function preProcessPreviewResults($output) {
//      global $LANG;
//
//      if (isset($output["action"])) {
//
//
//      }
//      return $output;
//   }

}

?>