<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

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

// ----------------------------------------------------------------------
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..'); 

include (GLPI_ROOT."/inc/includes.php");

checkRight("networking","r");
PluginFusioninventoryAuth::checkRight("snmp_networking","r");

$plugin_fusioninventory_snmp = new PluginFusioninventorySNMP;

if ((isset($_POST['update'])) && (isset($_POST['id']))) {
	PluginFusioninventoryAuth::checkRight("snmp_networking","w");
	$plugin_fusioninventory_snmp->update_network_infos($_POST['id'], $_POST['model_infos'], $_POST['plugin_fusioninventory_snmpauths_id']);
} else if ((isset($_POST["GetRightModel"])) && (isset($_POST['id']))) {
   $plugin_fusioninventory_model_infos = new PluginFusioninventoryModelInfos;
   $plugin_fusioninventory_model_infos->getrightmodel($_POST['id'], NETWORKING_TYPE);
}

glpi_header($_SERVER['HTTP_REFERER']);

?>