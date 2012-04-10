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
   @author    Walid Nouh
   @co-author 
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

class PluginFusinvdeployAction_Command extends CommonDBTM {

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvdeploy']['package'][1];
   }

   function cleanDBonPurge() {
      $temp = new PluginFusinvdeployAction_Commandstatus();
      $temp->deleteByCriteria(array('plugin_fusinvdeploy_commands_id' => $this->fields['id']));
   }

   static function getActions($commands_id, $action_id) {
      $response = array();

      $commands = getAllDatasFromTable('glpi_plugin_fusinvdeploy_actions_commands',
                                       "`id`='$commands_id'");

      foreach ($commands as $command) {
         if (!empty($command['exec']))
            $tmp    = array('exec' => $command['exec']);
         else continue;
         $linked = array('PluginFusinvdeployAction_Commandstatus'      => 'retChecks',
                         'PluginFusinvdeployAction_Commandenvvariable' => 'envs');
         foreach ($linked as $class => $value) {
            $result = call_user_func(array($class,'getForCommand'), $action_id);
            if (!empty($result)) {
               $tmp[$value] = $result;
            }
         }
         $response['cmd'] = $tmp;
      }
      return $response;
   }
}

?>