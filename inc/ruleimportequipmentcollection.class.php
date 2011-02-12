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

include_once(GLPI_ROOT."/plugins/fusioninventory/inc/rulecollection.class.php");

class PluginFusioninventoryRuleImportEquipmentCollection extends PluginFusioninventoryRuleCollection {

   // From RuleCollection
   public $stop_on_first_match = true;
   public $right               = 'rule_ocs';
   public $menu_option         = 'linkcomputer';


   function getTitle() {
      global $LANG;

      return $LANG['plugin_fusioninventory']['rules'][2];
   }


   function prepareInputDataForProcess($input,$params) {
      return array_merge($input,$params);
   }

}

?>