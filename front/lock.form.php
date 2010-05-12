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

// Original Author of file: MAZZONI Vincent
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

//todo
//PluginFusioninventoryAuth::checkRight("snmp_networking","r");

if(isset($_POST["unlock_field_fusioninventory"])){
	if (isset($_POST["lockfield_fusioninventory"])&&count($_POST["lockfield_fusioninventory"])){
      $tab=PluginFusioninventoryLock::exportChecksToArray($_POST["lockfield_fusioninventory"]);
         PluginFusioninventoryLock::setLockArray($_POST['type'], $_POST["id"], $tab);
	} else {
      PluginFusioninventoryLock::setLockArray($_POST['type'], $_POST["id"], array());
   }
	glpi_header($_SERVER['HTTP_REFERER']);
}

$locks = new PluginFusioninventoryLock;
$locks->showForm($_SERVER["PHP_SELF"], $_POST['type'], $id);

commonFooter();

?>