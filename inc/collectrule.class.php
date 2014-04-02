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
   @since     2013

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryCollectRule extends Rule {

   // From Rule
   static $rightname = "plugin_fusioninventory_rulecollect";
   
   public $can_sort=TRUE;
   public $specific_parameters = FALSE;


   function getTitle() {
      return __('Additional computer information rules', 'fusioninventory');
   }



   function preProcessPreviewResults($output) {
      return $output;
   }



   function maxActionsCount() {
      // Unlimited
      return 8;
   }


//
//   function executeActions($output, $params) {
//
//      PluginFusioninventoryToolbox::logIfExtradebug(
//         "pluginFusioninventory-collectrules",
//         "execute action\n"
//      );
//
//      if (count($this->actions)) {
//         foreach ($this->actions as $action) {
//            switch ($action->fields["action_type"]) {
//               case "assign" :
//                  PluginFusioninventoryToolbox::logIfExtradebug(
//                     "pluginFusioninventory-collectrules",
//                     "value ".$action->fields["value"]."\n"
//                  );
//                  $output[$action->fields["field"]] = $action->fields["value"];
//                  break;
//
//               case "regex_result" :
//                  //Assign entity using the regex's result
//                  if ($action->fields["field"] == "_affect_entity_by_tag") {
//                     PluginFusioninventoryToolbox::logIfExtradebug(
//                        "pluginFusioninventory-collectrules",
//                        "value ".$action->fields["value"]."\n"
//                     );
//                     //Get the TAG from the regex's results
//                     $res = RuleAction::getRegexResultById($action->fields["value"],
//                                                           $this->regex_results[0]);
//                     if (!is_null($res)) {
//                        //Get the entity associated with the TAG
//                        $target_entity = Entity::getEntityIDByTag($res);
//                        if ($target_entity != '') {
//                           $output["entities_id"]=$target_entity;
//                        }
//                     }
//                  }
//                  break;
//            }
//         }
//      }
//      return $output;
//   }



   function getCriterias() {

      $criterias = array ();

      $criterias['regkey']['field']       = 'name';
      $criterias['regkey']['name']        = __('Registry key', 'fusioninventory');
      $criterias['regkey']['table']       = 'glpi_plugin_fusioninventory_collects_registries';

      $criterias['regvalue']['field']     = 'name';
      $criterias['regvalue']['name']      = __('Registry value', 'fusioninventory');

      $criterias['wmiproperty']['field']  = 'name';
      $criterias['wmiproperty']['name']   = __('WMI property', 'fusioninventory');
      $criterias['wmiproperty']['table']  = 'glpi_plugin_fusioninventory_collects_wmis';

      $criterias['wmivalue']['field']     = 'name';
      $criterias['wmivalue']['name']      = __('WMI value', 'fusioninventory');

      $criterias['filename']['field']     = 'name';
      $criterias['filename']['name']      = __('File name', 'fusioninventory');

      $criterias['filepath']['field']     = 'name';
      $criterias['filepath']['name']      = __('File path', 'fusioninventory');

      $criterias['filesize']['field']     = 'name';
      $criterias['filesize']['name']      = __('File size', 'fusioninventory');

      return $criterias;
   }



   function getActions() {

      $actions = array();

      $actions['computertypes_id']['name']  = __('Type');
      $actions['computertypes_id']['type']  = 'dropdown';
      $actions['computertypes_id']['table'] = 'glpi_computertypes';

      $actions['computermodels_id']['name']  = __('Model');
      $actions['computermodels_id']['type']  = 'dropdown';
      $actions['computermodels_id']['table'] = 'glpi_computermodels';

      $actions['operatingsystems_id']['name']  = __('Operating system');
      $actions['operatingsystems_id']['type']  = 'dropdown';
      $actions['operatingsystems_id']['table'] = 'glpi_operatingsystems';

      $actions['operatingsystemversions_id']['name']  = _n('Version of the operating system', 'Versions of the operating system', 1);
      $actions['operatingsystemversions_id']['type']  = 'dropdown';
      $actions['operatingsystemversions_id']['table'] = 'glpi_operatingsystemversions';

      $actions['user']['name']  = __('User');
      $actions['user']['force_actions'] = array('assign', 'regex_result');

      $actions['locations_id']['name']  = __('Location');
      $actions['locations_id']['type']  = 'dropdown';
      $actions['locations_id']['table'] = 'glpi_locations';

      $actions['states_id']['name']  = __('Status');
      $actions['states_id']['type']  = 'dropdown';
      $actions['states_id']['table'] = 'glpi_states';

      $actions['software']['name']  = __('Software');

      $actions['softwareversion']['name']  = __('Software version');
      $actions['softwareversion']['force_actions'] = array('assign', 'regex_result');

      $actions['otherserial']['name']  = __('Inventory number');
      $actions['otherserial']['force_actions'] = array('assign', 'regex_result');

      return $actions;
   }
}

?>
