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

checkRight("printer","r");
PluginFusioninventoryProfile::checkRight("fusinvsnmp", "printer","r");

$PluginFusinvsnmpPrinterLog = new PluginFusinvsnmpPrinterLog();
print_r($_POST);
exit;
if ((isset($_POST['delete']))) {
	
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "printer","w");
	
	if (isset($_POST['limit'])) {
		for ($i=0 ; $i<$_POST['limit'] ; $i++) {
			if ((isset($_POST["checked_$i"])) && ($_POST["checked_$i"] == 1)) {
            $input = array();
   			if (isset($_POST["ID_$i"])) {
               $input['id'] = $_POST["ID_$i"];
            }
				$PluginFusinvsnmpPrinterLog->delete($input);
			}
		}
	}
}

glpi_header($_SERVER['HTTP_REFERER']);

?>