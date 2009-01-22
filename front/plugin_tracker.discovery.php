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

$NEEDED_ITEMS=array("printer","computer","networking","peripheral","tracker","search");
include (GLPI_ROOT."/inc/includes.php");

commonHeader($LANG['plugin_tracker']["title"][0],$_SERVER["PHP_SELF"],"plugins","tracker");

if(plugin_tracker_HaveRight("snmp_discovery","r"))

if((isset($_POST['update'])) AND (!empty($_POST['update'])))
{
	// Update DB
	plugin_tracker_discovery_update_devices($_POST, $_SERVER["PHP_SELF"]);
}
if((isset($_POST['discover'])) AND (!empty($_POST['discover'])))
{
	plugin_tracker_discovery_update_conf($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
}
if((isset($_POST['import'])) AND (!empty($_POST['import'])))
{
	plugin_tracker_discovery_import($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
}

plugin_tracker_discovery_startmenu($_SERVER["PHP_SELF"]);

plugin_tracker_discovery_display_array($_SERVER["PHP_SELF"]);

commonFooter();

?>