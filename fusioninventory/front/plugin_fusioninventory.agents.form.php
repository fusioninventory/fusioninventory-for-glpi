<?php

/*
   ----------------------------------------------------------------------
   GLPI - Gestionnaire Libre de Parc Informatique
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

   http://indepnet.net/   http://glpi-project.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of GLPI.

   GLPI is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   GLPI is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with GLPI; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
   ------------------------------------------------------------------------
 */

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

$NEEDED_ITEMS = array (
	"setup",
	"rulesengine",
	"fusioninventory",
	"search",
   "computer",
   "device",
   "printer",
   "networking",
   "peripheral",
   "monitor"
);

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

$agents = new PluginFusionInventoryAgents;

commonHeader($LANG['plugin_fusioninventory']["title"][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","agents");

plugin_fusioninventory_checkRight("agents","r");

plugin_fusioninventory_mini_menu();

if (isset ($_POST["add"])) {
	plugin_fusioninventory_checkRight("agents","w");
   if (($_POST['on_device'] != "0") AND ($_POST['on_device'] != "")) {
      $_POST['device_type'] = '1';
   }
	$agents->add($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["update"])) {
	plugin_fusioninventory_checkRight("agents","w");
   if (isset($_POST['on_device'])) {
      if (($_POST['on_device'] != "0") AND ($_POST['on_device'] != "")) {
         $_POST['device_type'] = '1';
      }
   }
	$agents->update($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["delete"])) {
	plugin_fusioninventory_checkRight("agents","w");
	$agents->delete($_POST);
	glpi_header("plugin_fusioninventory.agents.php");
} else if (isset ($_POST["startagent"])) {
   $pta = new PluginFusionInventoryAgents;
   $pta->RemoteStartAgent($_POST['agentID'], $_POST['ip']);
	glpi_header($_SERVER['HTTP_REFERER']);
}


$ID = "";
if (isset($_GET["ID"])) {
	$ID = $_GET["ID"];
} else {
   $agents->showForm($_SERVER["PHP_SELF"], $ID);
}

$agents->showTabs($ID, '',$_SESSION['glpi_tab']);
echo "<div id='tabcontent'></div>";
echo "<script type='text/javascript'>loadDefaultTab();</script>";

commonFooter();

?>