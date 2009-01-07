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

checkRight("config","w");

commonHeader($LANGTRACKER["functionalities"][0],$_SERVER["PHP_SELF"],"plugins","tracker","summary");

$config = new plugin_tracker_config();
$config_snmp_printer = new plugin_tracker_config_snmp_printer();
$print_config = new glpi_plugin_tracker_printers_history_config();

if (isset($_POST['update'])) {

	if (empty($_POST['cleaning_days']))
		$_POST['cleaning_days'] = 0;
		
	$_POST['ID']=1;
	
	
	$config_snmp_printer->update($_POST);
}

$config->showTabs("smp-peripheral");
//$config_snmp_printer->showForm($_SERVER['PHP_SELF'],'1');
commonFooter();

?>