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
if (haveRight("config","w") && haveRight("profile","w")) {

	if(TableExists("glpi_plugin_fusioninventory_config")) {
		cleanCache("GLPI_HEADER_".$_SESSION["glpiID"]);
		plugin_fusioninventory_uninstall();
		unset($_SESSION["glpi_plugin_fusioninventory_installed"]);
	}
	glpi_header($_SERVER['HTTP_REFERER']);

} else {
	commonHeader($LANG["login"][5],$_SERVER['PHP_SELF'],"plugins","fusioninventory");
	echo "<div align='center'><br><br><img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt=\"warning\"><br><br>";
	echo "<b>".$LANG["login"][5]."</b></div>";
	commonFooter();
}

?>