<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org//
   ----------------------------------------------------------------------

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
   ------------------------------------------------------------------------
 */

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployStaticmisc {

   const DEPLOYMETHOD_INSTALL   = 'deployinstall';
   const DEPLOYMETHOD_UNINSTALL = 'deployuninstall';

   static function task_methods() {
      global $LANG;

      return array(array('module'         => 'fusinvdeploy',
                         'method'         => self::DEPLOYMETHOD_INSTALL,
                         'name'           => $LANG['plugin_fusinvdeploy']['package'][16],
                         'task'           => "DEPLOY",
                         'use_rest'       => true),
                   array('module'         => 'fusinvdeploy',
                         'method'         => self::DEPLOYMETHOD_UNINSTALL,
                         'name'           => $LANG['plugin_fusinvdeploy']['package'][17],
                         'task'           => "DEPLOY",
                         'use_rest'       => true));
   }

   static function getItemtypeActions() {
      return array('PluginFusinvdeployPackage');
   }
   /*
   # Actions with itemtype autorized
   static function task_action_deploy_install() {
      return self::getItemtypeActions();
   }

   # Actions with itemtype autorized
   static function task_action_deploy_uninstall() {
      return self::getItemtypeActions();
   }*/

   static function getDefinitionType() {
      global $LANG;
      return array(0 => DROPDOWN_EMPTY_VALUE,
                   'PluginFusinvdeployPackage' => $LANG['plugin_fusinvdeploy']['package'][7]);
   }

   static function task_definitiontype_deployinstall($a_itemtype) {
      return self::getDefinitionType();
   }

   static function task_definitiontype_deployuninstall($a_itemtype) {
      return self::getDefinitionType();
   }

   static function getDeploySelections() {
      global $LANG;

      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'definitionselectiontoadd';
      return Dropdown::show("PluginFusinvdeployPackage", $options);
   }

   static function getDeployActions() {
      global $LANG;

      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = haveAccessToEntity($_SESSION['glpiactive_entity'],1);
      $options['name']        = 'actionselectiontoadd';
      return Dropdown::show("Computer", $options);

   }

   static function task_definitionselection_PluginFusinvdeployPackage_deployinstall() {
      return self::getDeploySelections();
   }

   static function task_definitionselection_PluginFusinvdeployPackage_deployuninstall() {
      return self::getDeploySelections();
   }

   static function task_actionselection_PluginFusioninventoryAgent_deployinstall() {
      return self::getDeployActions();
   }

   static function task_actionselection_PluginFusioninventoryAgent_deployuninstall() {
      return self::getDeployActions();
   }

   static function displayMenu() {
      global $LANG;

      $a_menu = array();
      if (PluginFusioninventoryProfile::haveRight("fusinvdeploy", "packages", "r")) {
         $a_menu[0]['name'] = $LANG['plugin_fusinvdeploy']["package"][6];
         $a_menu[0]['pic']  = GLPI_ROOT."/plugins/fusinvdeploy/pics/menu_package.png";
         $a_menu[0]['link'] = GLPI_ROOT."/plugins/fusinvdeploy/front/package.php";
      }


      $a_menu[1]['name'] = $LANG['plugin_fusinvdeploy']['form']['mirror'][1];
      $a_menu[1]['pic']  = GLPI_ROOT."/plugins/fusinvdeploy/pics/menu_files.png";
      $a_menu[1]['link'] = GLPI_ROOT."/plugins/fusinvdeploy/front/mirror.php";

      if (PluginFusioninventoryProfile::haveRight("fusinvdeploy", "status", "r")) {
         $a_menu[2]['name'] = $LANG['plugin_fusinvdeploy']["deploystatus"][0];
         $a_menu[2]['pic']  = GLPI_ROOT."/plugins/fusinvdeploy/pics/menu_deploy_status.png";
         $a_menu[2]['link'] = GLPI_ROOT."/plugins/fusinvdeploy/front/deploystate.php";
      }

      $a_menu[3]['name'] = $LANG['plugin_fusinvdeploy']['task'][0];
      $a_menu[3]['pic']  = GLPI_ROOT."/plugins/fusinvdeploy/pics/menu_task.png";
      $a_menu[3]['link'] = GLPI_ROOT."/plugins/fusinvdeploy/front/task.php";

      return $a_menu;
   }


   static function profiles() {
      global $LANG;

      return array(array('profil'  => 'packages',
                         'name'    => $LANG['plugin_fusinvdeploy']['profile'][2]),
                   array('profil'  => 'status',
                         'name'    => $LANG['plugin_fusinvdeploy']['profile'][3]));
   }

   static function task_deploy_getParameters() {
      global $CFG_GLPI;

      return array ('periodicity' => 3600, 'delayStartup' => 3600, 'task' => 'deploy',
                    'remote' => PluginFusioninventoryAgentmodule::getUrlForModule('deploy'));
   }
}

?>
