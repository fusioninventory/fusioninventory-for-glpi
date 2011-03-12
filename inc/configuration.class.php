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
   Original Author of file: Vincent MAZZONI
   Co-authors of file: 
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryConfiguration extends CommonDBTM {

   
   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $tabs = array();
      $moduleTabs = array();
      $tabs[1]=$LANG['plugin_fusioninventory']['functionalities'][2];
      $tabs[2]=$LANG['plugin_fusioninventory']['agents'][27];
      if (isset($_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms'])) {
         $fusionTabs = $tabs;
         $moduleTabForms = $_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms'];
         if (count($moduleTabForms)) {
            foreach ($moduleTabForms as $module=>$form) {
               $plugin = new Plugin;
               if ($plugin->isActivated($module)) {
                  $tabs[] = key($form);
               }
            }
            $moduleTabs = array_diff($tabs, $fusionTabs);
         }
         $_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabs'] = $moduleTabs;
      }
      return $tabs;
   }



   function showForm($options=array()) {
      global $DB,$CFG_GLPI,$LANG;

      $this->showTabs($options);
      $this->addDivForTabs();

      return true;
   }


}

?>