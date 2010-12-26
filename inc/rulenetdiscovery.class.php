<?php
/*
 * @version $Id: ruleocs.class.php 12616 2010-10-05 13:21:59Z walid $
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

include_once(GLPI_ROOT."/plugins/fusioninventory/inc/rule.class.php");


class PluginFusinvsnmpRuleNetdiscovery extends PluginFusioninventoryRule {

   // From Rule
   public $right='rule_ocs';
   public $can_sort=true;
   public $specific_parameters = true;
   public $restrict_matching = "AND";

   function getTitle() {
      global $LANG;
      return $LANG['plugin_fusinvsnmp']['rule'][0];
   }

   function preProcessPreviewResults($output) {
      return $output;
   }


   function maxActionsCount() {
      return 1;
   }

   function getCriterias() {
      global $LANG;
      
      $criterias = array();
      $criterias['globalcriteria']['field'] = 'name';
      $criterias['globalcriteria']['name']  = "* ".$LANG['plugin_fusinvsnmp']['rule'][1];
      $criterias['globalcriteria']['table'] = 'glpi_plugin_fusinvsnmp_netdiscoverycriteria';
      $criterias['globalcriteria']['linkfield'] = '';
      $criterias['globalcriteria']['type'] = 'dropdown';
      $criterias['globalcriteria']['virtual']   = true;
      $criterias['globalcriteria']['id']        = 'globalcriteria';
      $criterias['globalcriteria']['allow_condition'] = array(Rule::PATTERN_IS, Rule::PATTERN_IS_NOT);

      $criterias['serialnumber']['field'] = 'name';
      $criterias['serialnumber']['name']  = $LANG['plugin_fusinvsnmp']['rule'][2];

      $criterias['mac']['field'] = 'name';
      $criterias['mac']['name']  = $LANG['plugin_fusinvsnmp']['rule'][3];

      $criterias['model']['field'] = 'name';
      $criterias['model']['name']  = $LANG['plugin_fusinvsnmp']['rule'][5];

      $criterias['name']['field'] = 'name';
      $criterias['name']['name']  = $LANG['plugin_fusinvsnmp']['rule'][6];


      return $criterias;
   }

   function getActions() {
      global $LANG;
      $actions = array();
      $actions['_import']['name']  = $LANG['plugin_fusinvsnmp']['rule'][30];
      $actions['_import']['type']  = 'yesno';
      $actions['_import']['table'] = '';

      $actions['_import_unknowndevice']['name']  = $LANG['plugin_fusinvsnmp']['rule'][31];
      $actions['_import_unknowndevice']['type']  = 'yesno';
      $actions['_import_unknowndevice']['table'] = '';

      return $actions;
   }


   function executeActions($output,$params) {

      $import = 0;
      if (count($this->actions)) {
         foreach ($this->actions as $action) {
            switch ($action->fields["action_type"]) {

               case "assign" :

                  switch ($action->fields["field"]) {
                     case "_import" :
                        if ($action->fields["value"] == "1") {
                           $import = 1;
                        }
                        break;

                     case "_import_unknowndevice" :
                        // Import dans le matos inconnu

                        break;
                  }
                  break;
            }
         }
      }
      if ($import == "1") {

         // Get all criteria global
         $globalcriteria = array();
         foreach ($this->criterias as $criteria) {
            if ($criteria->fields['criteria'] == 'globalcriteria') {
               $array = Dropdown::getDropdownName('glpi_plugin_fusinvsnmp_netdiscoverycriteria', $criteria->fields['pattern'], 1);
               $globalcriteria[] = $array['comment'];
            }
         }
         if (count($globalcriteria) > 0) {
            $PluginFusinvsnmpCommunicationNetDiscovery = new PluginFusinvsnmpCommunicationNetDiscovery();
            $PluginFusinvsnmpCommunicationNetDiscovery->checkCriteria($globalcriteria);
         }
      }
      return $output;
   }

}
?>