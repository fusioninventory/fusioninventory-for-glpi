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

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");

checkRight("config","w");

if (PluginFusioninventoryProfile::haveRight("fusioninventory", "configuration", "r")) {
   switch($_POST['glpi_tab']) {
      case -1 : // All
         $config = new PluginFusioninventoryConfig;
         $config->showForm(array('target'=>$_POST['target']));

         $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
         $PluginFusioninventoryAgentmodule->showForm();

         if (isset($_SESSION['glpi_plugin_fusioninventory']['configuration'])) {
            $sessionConfig = $_SESSION['glpi_plugin_fusioninventory']['configuration'];
            if (isset($sessionConfig['moduletabs'])) {
               $pluginsTabs = $sessionConfig['moduletabs'];
               $plugin = new Plugin;
               foreach($sessionConfig['moduletabforms'] as $module=>$form) {
                  if ($plugin->isActivated($module)) {
                     foreach($form as $title=>$tab) {
                        $class = $form[$title]['class'];
                        $oTab = new $class;
                        $oTab->showForm(array('target'=>$_POST['target']));
                     }
                  }
               }
            }
         }
         break;

      case 0 :
         $config = new PluginFusioninventoryConfig;
         $config->showForm(array('target'=>$_POST['target']));
         break;

      case 1:
         $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
         $PluginFusioninventoryAgentmodule->showForm();
         break;

      default :
         if (isset($_SESSION['glpi_plugin_fusioninventory']['configuration'])) {
            $sessionConfig = $_SESSION['glpi_plugin_fusioninventory']['configuration'];
            if (isset($sessionConfig['moduletabs'])) {
               $pluginsTabs = $sessionConfig['moduletabs'];
               if (isset($pluginsTabs[$_POST['glpi_tab']])){
                  $title = $pluginsTabs[$_POST['glpi_tab']];
                  $plugin = new Plugin;
                  foreach($sessionConfig['moduletabforms'] as $module=>$form) {
                     if ($plugin->isActivated($module)) {
                        if (isset($form[$title])) {
                           $class = $form[$title]['class'];
                           $oTab = new $class;
                           $oTab->showForm(array('target'=>$_POST['target']));
                           break;
                        }
                     }
                  }
               }
            }
         }

   }
} else {
   echo $LANG['common'][83]."<br/>";
}
ajaxFooter();

?>