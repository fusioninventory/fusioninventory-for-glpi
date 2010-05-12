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

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

$agents = new PluginFusioninventoryAgents;

commonHeader($LANG['plugin_fusioninventory']["title"][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","agents");

PluginFusioninventoryAuth::checkRight("agents","r");

PluginFusioninventoryDisplay::mini_menu();

if (isset ($_POST["add"])) {
	PluginFusioninventoryAuth::checkRight("agents","w");
   if (($_POST['items_id'] != "0") AND ($_POST['items_id'] != "")) {
      $_POST['itemtype'] = '1';
   }
	$agents->add($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["update"])) {
	PluginFusioninventoryAuth::checkRight("agents","w");
   if (isset($_POST['items_id'])) {
      if (($_POST['items_id'] != "0") AND ($_POST['items_id'] != "")) {
         $_POST['itemtype'] = '1';
      }
   }
	$agents->update($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["delete"])) {
	PluginFusioninventoryAuth::checkRight("agents","w");
	$agents->delete($_POST);
	glpi_header("agents.php");
} else if (isset ($_POST["startagent"])) {
   $pta = new PluginFusioninventoryAgents;
   $pta->RemoteStartAgent($_POST['agentID'], $_POST['ip']);
	glpi_header($_SERVER['HTTP_REFERER']);
}


$id = "";
if (isset($_GET["id"])) {
	$id = $_GET["id"];
} else {
   $agents->showForm($id);
}

$agents->showTabs($id, '',$_SESSION['glpi_tab']);
echo "<div id='tabcontent'></div>";
echo "<script type='text/javascript'>loadDefaultTab();</script>";

commonFooter();

?>