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

if (!defined('GLPI_ROOT')) {
	define('GLPI_ROOT', '../../..');
}

include (GLPI_ROOT."/inc/includes.php");

commonHeader($LANG['plugin_fusioninventory']["title"][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory", "agentsprocesses");

PluginFusioninventoryProfile::checkRight("fusioninventory", "agentsprocesses","r");

$modif = 0;
if (empty($_GET)) {
	$modif = 1;
}

PluginFusioninventoryMenu::displayMenu("mini");

$ptap = new PluginFusioninventoryAgentProcess;
$pfiae  =  new PluginFusioninventoryAgentProcessError;
$ptap->CleanProcesses();
$pfiae->CleanErrors();


$a_tab = array();
if (isset($_GET['process_number'])) {
   $_SESSION['glpi_tabs'] = 3;
   $a_tab['process_number'] = $_GET['process_number'];
}
if (isset($_GET['h_process_number'])) {
   $_SESSION['glpi_tabs'] = 2;
   $a_tab['process_number'] = $_GET['h_process_number'];
}
if (isset($_GET['agent_type'])) {
   $_SESSION['glpi_tabs'] = 3;
   $a_tab['agent_type'] = $_GET['agent_type'];
}
if (isset($_GET['created'])) {
   $_SESSION['glpi_tabs'] = 2;
   $a_tab['created'] = $_GET['created'];
}

$ptap->showTabs('1', '',$_SESSION['glpi_tabs'],$a_tab);
echo "<div id='tabcontent'></div>";
echo "<script type='text/javascript'>loadDefaultTab();</script>";

commonFooter();

?>