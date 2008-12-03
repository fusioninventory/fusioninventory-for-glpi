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
// Original Author of file: Nicolas SMOLYNIEC
// Purpose of file:
// ----------------------------------------------------------------------

$NEEDED_ITEMS = array("printer");

define('GLPI_ROOT', '../../..'); 

include (GLPI_ROOT."/inc/includes.php");

checkRight("printer","r");
plugin_tracker_checkRight("printers_info","r");


if ( (isset($_POST['update'])) && (isset($_POST['ID'])) ) {
	
	plugin_tracker_checkRight("printers_info","w");
	
	$plugin_tracker_printers = new plugin_tracker_printers();
	
	$_POST['FK_printers'] = $_POST['ID'];
	unset($_POST['ID']);
	
	$query = "SELECT * FROM glpi_plugin_tracker_printers
	WHERE FK_printers='".$_POST['FK_printers']."' ";
	$result = $DB->query($query);		
	$data = $DB->fetch_assoc($result);	
	$_POST['ID'] = $data['ID'];
	$plugin_tracker_printers->update($_POST);
	
}

if ( (isset($_POST['add'])) && (isset($_POST['ID'])) ) {
	plugin_tracker_checkRight("printers_info","w");

	$plugin_tracker_printers_cartridges = new plugin_tracker_printers_cartridges;

	$_POST['FK_printers'] = $_POST['ID'];
	unset($_POST['ID']);
	$_POST['FK_cartridges'] = $_POST['tID'];
	$plugin_tracker_printers_cartridges->add($_POST);
}

glpi_header($_SERVER['HTTP_REFERER']);

?>