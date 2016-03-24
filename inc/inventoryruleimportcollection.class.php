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
   @since     2010

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryInventoryRuleImportCollection extends RuleCollection {

   // From RuleCollection
   public $stop_on_first_match = TRUE;
   //static public $right        = 'rule_import';
   public $menu_option         = 'fusionlinkcomputer';
   static $rightname           = "plugin_fusioninventory_ruleimport";

   function getTitle() {

      return __('Equipment import and link rules', 'fusioninventory');

   }



   function prepareInputDataForProcess($input, $params) {
      return array_merge($input, $params);
   }



   function getRuleClassName() {
      $rule_class = array();
      if (preg_match('/(.*)Collection/', get_class($this), $rule_class)) {
         return $rule_class[1];
      }
      return "";
   }



   /**
    * Get a instance of the class to manipulate rule of this collection
    *
   **/
   function getRuleClass() {
      $name = $this->getRuleClassName();
      if ($name !=  '') {
         return new $name ();
      }
      else {
         return NULL;
      }
   }



   function preProcessPreviewResults($output) {

      //If ticket is assign to an object, display this information first
      if (isset($output["action"])) {
         echo "<tr class='tab_bg_2'>";
         echo "<td>".__('Action type')."</td>";
         echo "<td>";

         switch ($output["action"]) {
            case PluginFusioninventoryInventoryRuleImport::LINK_RESULT_LINK :
               echo __('Link');

               break;

            case PluginFusioninventoryInventoryRuleImport::LINK_RESULT_CREATE:
               echo __('Device created', 'fusioninventory');

               break;

            case PluginFusioninventoryInventoryRuleImport::LINK_RESULT_DENIED:
               echo __('Import denied', 'fusioninventory');

               break;

         }

         echo "</td>";
         echo "</tr>";
         if ($output["action"] != PluginFusioninventoryInventoryRuleImport::LINK_RESULT_DENIED
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


   /**
    * Get Collection Datas : retrieve descriptions and rules
    *
    * @param $retrieve_criteria  Retrieve the criterias of the rules ? (default 0)
    * @param $retrieve_action    Retrieve the action of the rules ? (default 0)
   **/
   function getCollectionDatas($retrieve_criteria=0, $retrieve_action=0, $condition = 0) {
      global $DB;

      if ($this->RuleList === NULL) {
         $this->RuleList = SingletonRuleList::getInstance($this->getRuleClassName(),
                                                          $this->entity);
      }
      $need = 1+($retrieve_criteria?2:0)+($retrieve_action?4:0);

      // check if load required
//      if (($need & $this->RuleList->load) != $need) {
         //Select all the rules of a different type
         $sql = $this->getRuleListQuery();

         $result = $DB->query($sql);
         if ($result) {
            $this->RuleList->list = array();

            while ($rule = $DB->fetch_assoc($result)) {
               //For each rule, get a Rule object with all the criterias and actions
               $tempRule = $this->getRuleClass();

               if ($tempRule->getRuleWithCriteriasAndActions($rule["id"], $retrieve_criteria,
                                                             $retrieve_action)) {
                  //Add the object to the list of rules
                  $this->RuleList->list[] = $tempRule;
               }
            }

            $this->RuleList->load = $need;
         }
//      }
   }


}

?>
