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
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT."/inc/includes.php");

checkRight("config","w");

commonHeader($LANG['plugin_fusioninventory']['functionalities'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","summary");

if (isset($_POST['update'])) {

	if (empty($_POST['cleaning_days'])) {
		$_POST['cleaning_days'] = 0;
   }

   $_POST['id']=1;
	switch ($_POST['tabs']) {
      
		case 'config' :
			$PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
			break;

      case 'history' :
         $PluginFusinvsnmpConfigLogField = new PluginFusinvsnmpConfigLogField();
         foreach ($_POST as $key=>$val) {
            $split = explode("-", $key);
            if (isset($split[1]) AND is_numeric($split[1])) {
               $PluginFusinvsnmpConfigLogField->getFromDB($split[1]);
               $PluginFusinvsnmpConfigLogField->fields['days'] = $val;
               $PluginFusinvsnmpConfigLogField->update($PluginFusinvsnmpConfigLogField->fields);
            }
         }
         break;

	}
	if (isset($PluginFusioninventoryConfig)) {
		$PluginFusioninventoryConfig->update($_POST);
   }
   glpi_header($_SERVER['HTTP_REFERER']);
} else if ((isset($_POST['Clean_history']))) {
   $PluginFusinvsnmpNetworkPortLog = new PluginFusinvsnmpNetworkPortLog();
   $PluginFusinvsnmpNetworkPortLog->cronCleannetworkportlogs();
   glpi_header($_SERVER['HTTP_REFERER']);
}

commonFooter();

?>