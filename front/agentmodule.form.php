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

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

commonHeader($LANG['plugin_fusioninventory']["title"][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","agentmodules");

$PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;

if (isset($_POST["agent_add"])) {
   $PluginFusioninventoryAgentmodule->getFromDB($_POST['id']);
   $a_agentList = importArrayFromDB($PluginFusioninventoryAgentmodule->fields['exceptions']);
   $a_agentList[] = $_POST['agent_to_add'][0];
   $input = array();
   $input['exceptions'] = exportArrayToDB($a_agentList);
   $input['id'] = $_POST['id'];
   $PluginFusioninventoryAgentmodule->update($input);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST["agent_delete"])) {
   $PluginFusioninventoryAgentmodule->getFromDB($_POST['id']);
   $a_agentList = importArrayFromDB($PluginFusioninventoryAgentmodule->fields['exceptions']);
   foreach ($a_agentList as $key=>$value) {
      if ($value == $_POST['agent_to_delete'][0]) {
         unset($a_agentList[$key]);
      }
   }
   $input = array();
   $input['exceptions'] = exportArrayToDB($a_agentList);
   $input['id'] = $_POST['id'];
   $PluginFusioninventoryAgentmodule->update($input);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["updateexceptions"])) {
   $a_modules = $PluginFusioninventoryAgentmodule->find();
   foreach ($a_modules as $module_id=>$data) {
      $a_agentList = importArrayFromDB($data['exceptions']);
      $agentModule = 0;
      if (isset($_POST['activation-'.$data['modulename']])) {
         $agentModule = 1;
      }
      $agentModuleBase = 0;
      if (in_array($_POST['id'], $a_agentList)) {
         $agentModuleBase = 1;
      }
      if ($data['is_active'] == 0) {
         if (($agentModule == 1) AND ($agentModuleBase == 1)) {
            // OK
         } else if (($agentModule == 1) AND ($agentModuleBase == 0)) {
            $a_agentList[] = $_POST['id'];
         } else if (($agentModule == 0) AND ($agentModuleBase == 1)) {
            foreach ($a_agentList as $key=>$value) {
               if ($value == $_POST['id']) {
                  unset($a_agentList[$key]);
               }
            }
         } else if (($agentModule == 0) AND ($agentModuleBase == 0)) {
            // OK
         }
      } else if ($data['is_active'] == 1) {
         if (($agentModule == 1) AND ($agentModuleBase == 1)) {
            foreach ($a_agentList as $key=>$value) {
               if ($value == $_POST['id']) {
                  unset($a_agentList[$key]);
               }
            } 
         } else if (($agentModule == 1) AND ($agentModuleBase == 0)) {
            // OK
         } else if (($agentModule == 0) AND ($agentModuleBase == 1)) {
            //OK
         } else if (($agentModule == 0) AND ($agentModuleBase == 0)) {
            $a_agentList[]  = $_POST['id'];
         }
      }
      $data['exceptions'] = exportArrayToDB($a_agentList);
      $PluginFusioninventoryAgentmodule->update($data);
   }

   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["update"])) {
   $PluginFusioninventoryAgentmodule->getFromDB($_POST['id']);
   $input = array();
   if (isset($_POST['activation'])) {
      $input['is_active'] = 1;
   } else {
      $input['is_active'] = 0;
   }
   if ($PluginFusioninventoryAgentmodule->fields['is_active'] != $input['is_active']) {
      $a_agentList = array();
      $input['exceptions'] = exportArrayToDB($a_agentList);
   }
   $input['id'] = $_POST['id'];
   $PluginFusioninventoryAgentmodule->update($input);
   glpi_header($_SERVER['HTTP_REFERER']);
}

commonFooter();

?>