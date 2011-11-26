<?php

/*
 * @version $Id: action_command.statusclass.php 134 2011-03-16 19:02:08Z wnouh $
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
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvdeployAction_Commandstatus extends CommonDBTM {
   
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusinvdeploy']['package'][1];
   }

   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   static function getForCommand($commands_id) {
      $response = array();
      $commands = getAllDatasFromTable('glpi_plugin_fusinvdeploy_actions_commandstatus',
                                       "`plugin_fusinvdeploy_commands_id`='$commands_id'");
      foreach ($commands as $command) {
         $response[] = array( 'type' => $command['type'], 
                              'value' => $command['value']);
      }
      return $response;
   }
}
?>