<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

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
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Vincent Mazzoni
   @co-author David Durieux
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT . "/inc/includes.php");
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight("config","w");

if (PluginFusioninventoryProfile::haveRight("fusioninventory", "configuration", "r")) {
   switch($_POST['glpi_tab']) {
      
      case -1: // All
         $config = new PluginFusioninventoryConfig;
         $config->showForm(array('target'=>$_POST['target']));

         $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
         $PluginFusioninventoryAgentmodule->showForm();

         if (isset($_SESSION['glpi_plugin_fusioninventory']['configuration'])) {
            $sessionConfig = $_SESSION['glpi_plugin_fusioninventory']['configuration'];
            if (isset($sessionConfig['moduletabs'])) {
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

      case 1:
         $config = new PluginFusioninventoryConfig;
         $config->showForm(array('target'=>$_POST['target']));
         break;

      case 2:
         $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
         $PluginFusioninventoryAgentmodule->showForm();
         break;

      default:
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
Html::ajaxFooter();

?>