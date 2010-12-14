<?php
/*
 * @version $Id$
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
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..'); 

include (GLPI_ROOT."/inc/includes.php");

checkRight("printer","r");
PluginFusioninventoryProfile::checkRight("fusinvsnmp", "printer","r");

$printer_history = new PluginFusinvsnmpPrinterLog;

if ((isset($_POST['delete']))) {
	
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "printer","w");
	
	if (isset($_POST['limit'])) {
		for ($i=0 ; $i<$_POST['limit'] ; $i++) {
			if ((isset($_POST["checked_$i"])) && ($_POST["checked_$i"] == 1)) {
				if (isset($_POST["ID_$i"])) {
   				$input['id'] = $_POST["ID_$i"];
            }
				$printer_history->delete($input);
			}
		}
	}
}

glpi_header($_SERVER['HTTP_REFERER']);

?>