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
   @author    David Durieux
   @co-author Alexandre Delaunay
   @copyright Copyright (c) 2010-2012 FusionInventory team
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
                         'use_rest'       => true)
                         );
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
      return array(0 => Dropdown::EMPTY_VALUE,
                   'PluginFusinvdeployPackage' => $LANG['plugin_fusinvdeploy']['package'][7]);
   }

   static function getActionType() {
      global $LANG;
      return array(0 => Dropdown::EMPTY_VALUE,
                   'PluginFusinvdeployGroup' => $LANG['plugin_fusinvdeploy']['group'][3],
                   'Computer' => $LANG['Menu'][0],
                   'Group' => $LANG['common'][35]
                  );
   }

   static function task_definitiontype_deployinstall($a_itemtype) {
      return self::getDefinitionType();
   }

   static function task_definitiontype_deployuninstall($a_itemtype) {
      return self::getDefinitionType();
   }

   static function task_actiontype_deployinstall($a_itemtype) {
      return self::getActionType();
   }

   static function task_actiontype_deployuninstall($a_itemtype) {
      return self::getActionType();
   }

   static function getDeploySelections() {
      global $LANG;

      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'definitionselectiontoadd';
      return Dropdown::show("PluginFusinvdeployPackage", $options);
   }

  /* static function getDeployActions() {
      global $LANG;

      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = Session::haveAccessToEntity($_SESSION['glpiactive_entity'],1);
      $options['name']        = 'actionselectiontoadd';
      return Dropdown::show("Computer", $options);
   }*/

   static function getDeployActions() {
      global $LANG;

      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      return Dropdown::show("PluginFusinvdeployGroup", $options);

   }

   static function task_definitionselection_PluginFusinvdeployPackage_deployinstall() {
      return self::getDeploySelections();
   }

   static function task_definitionselection_PluginFusinvdeployPackage_deployuninstall() {
      return self::getDeploySelections();
   }

   static function task_definitionselection_PluginFusinvdeployGroup_deployinstall() {
      return self::getDeployActions();
   }

   static function task_definitionselection_PluginFusinvdeployGroup_deployuninstall() {
      return self::getDeployActions();
   }

   static function task_actionselection_Computer_deployinstall() {
      $options = array();
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      $options['condition']   = '`id` IN (SELECT `items_id` FROM `glpi_plugin_fusioninventory_agents`)';
      return Dropdown::show("Computer", $options);
   }
   static function task_actionselection_Computer_deployuninstall() {
      $options = array();
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      $options['condition']   = '`id` IN (SELECT `items_id` FROM `glpi_plugin_fusioninventory_agents`)';
      return Dropdown::show("Computer", $options);
   }

   static function task_actionselection_Group_deployinstall() {
      $options = array();
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      return Dropdown::show("Group", $options);
   }

   static function task_actionselection_PluginFusinvdeployGroup_deployinstall() {
      return self::getDeployActions();
   }

   static function task_actionselection_PluginFusinvdeployGroup_deployuninstall() {
      return self::getDeployActions();
   }

   static function displayMenu() {
      global $LANG;

      $a_menu = array();
      if (PluginFusioninventoryProfile::haveRight("fusinvdeploy", "packages", "r")) {
         $a_menu[0]['name'] = $LANG['plugin_fusinvdeploy']['package'][6];
         $a_menu[0]['pic']  = GLPI_ROOT."/plugins/fusinvdeploy/pics/menu_package.png";
         $a_menu[0]['link'] = GLPI_ROOT."/plugins/fusinvdeploy/front/package.php";
      }

      $a_menu[1]['name'] = $LANG['plugin_fusinvdeploy']['mirror'][1];
      $a_menu[1]['pic']  = GLPI_ROOT."/plugins/fusinvdeploy/pics/menu_files.png";
      $a_menu[1]['link'] = GLPI_ROOT."/plugins/fusinvdeploy/front/mirror.php";

      $a_menu[2]['name'] = $LANG['plugin_fusinvdeploy']['group'][0];
      $a_menu[2]['pic']  = GLPI_ROOT."/plugins/fusinvdeploy/pics/menu_group.png";
      $a_menu[2]['link'] = GLPI_ROOT."/plugins/fusinvdeploy/front/group.php";

      return $a_menu;
   }


   static function profiles() {
      global $LANG;

      return array(array('profil'  => 'packages',
                         'name'    => $LANG['plugin_fusinvdeploy']['profile'][2]),
                   array('profil'  => 'status',
                         'name'    => $LANG['plugin_fusinvdeploy']['deploystatus'][0]));
   }

   static function task_deploy_getParameters() {
      global $CFG_GLPI;

      return array ('periodicity' => 3600, 'delayStartup' => 3600, 'task' => 'Deploy',
                    'remote' => PluginFusioninventoryAgentmodule::getUrlForModule('Deploy'));
   }

   static function json_indent($json) {
      $result      = '';
      $pos         = 0;
      $strLen      = strlen($json);
      $indentStr   = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
      $newLine     = "<br />";
      $prevChar    = '';
      $outOfQuotes = true;

      for ($i=0; $i<=$strLen; $i++) {

         // Grab the next character in the string.
         $char = substr($json, $i, 1);

         // Are we inside a quoted string?
         if ($char == '"' && $prevChar != '\\') {
            $outOfQuotes = !$outOfQuotes;

           // If this character is the end of an element,
           // output a new line and indent the next line.
         } else if(($char == '}' || $char == ']') && $outOfQuotes) {
            $result .= $newLine;
            $pos --;
            for ($j=0; $j<$pos; $j++) {
               $result .= $indentStr;
            }
         }

         // Add the character to the result string.
         $result .= $char;

         // If the last character was the beginning of an element,
         // output a new line and indent the next line.
         if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
               $pos ++;
            }

            for ($j = 0; $j < $pos; $j++) {
               $result .= $indentStr;
            }
         }

         $prevChar = $char;
      }

      return $result;
   }
}

?>