<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

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

// ----------------------------------------------------------------------
// Original Author of file: Walid Nouh
// Purpose of file:
// ----------------------------------------------------------------------

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

   static function getActions($commands_id, $response = array()) {
      $commands = getAllDatasFromTable('glpi_plugin_fusinvdeploy_actions_commands',
                                       "`id`='$commands_id'");
      foreach ($commands as $command) {
         $tmp    = array('exec' => $command['exec']);
         $linked = array('PluginFusinvdeployAction_Commandstatus'      => 'retChecks',
                         'PluginFusinvdeployAction_Commandenvvariable' => 'envs');
         foreach ($linked as $class => $value) {
            $result = call_user_func(array($class,'getForCommand'),$commands_id);
            if (!empty($result)) {
               $tmp[$value] = $result;
            }
         }
         $response['cmd'][] = $tmp;
      }
      return $response;
   }
}
?>
