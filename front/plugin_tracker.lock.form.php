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
//plugin_tracker_checkRight("snmp_networking","r");

if(isset($_POST["unlock_field_tracker"])){
	if (isset($_POST["lockfield_tracker"])&&count($_POST["lockfield_tracker"])){
      $tab=plugin_tracker_exportChecksToArray($_POST["lockfield_tracker"]);
         plugin_tracker_lock_setLockArray($_POST['type'], $_POST["ID"], $tab);
	} else {
      plugin_tracker_lock_setLockArray($_POST['type'], $_POST["ID"], array());
   }
	glpi_header($_SERVER['HTTP_REFERER']);
}

$locks = new PluginTrackerLock;
$locks->showForm($_SERVER["PHP_SELF"], $_POST['type'], $ID);

commonFooter();
?>