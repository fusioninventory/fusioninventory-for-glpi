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

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

$iprange = new PluginFusioninventoryIPRange;

commonHeader($LANG['plugin_fusioninventory']["title"][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","iprange");

PluginFusioninventoryAuth::checkRight("iprange","r");

PluginFusioninventoryDisplay::mini_menu();

if (isset ($_POST["add"])) {
   if ($iprange->checkip($_POST)) {
      PluginFusioninventoryAuth::checkRight("iprange","w");
      $_POST['ifaddr_start'] = $_POST['ifaddr_start0'].".".$_POST['ifaddr_start1'].".".$_POST['ifaddr_start2'].".".$_POST['ifaddr_start3'];
      $_POST['ifaddr_end'] = $_POST['ifaddr_end0'].".".$_POST['ifaddr_end1'].".".$_POST['ifaddr_end2'].".".$_POST['ifaddr_end3'];
      $iprange->add($_POST);
   }
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["update"])) {
   if ($iprange->checkip($_POST)) {
      PluginFusioninventoryAuth::checkRight("iprange","w");
      $_POST['ifaddr_start'] = $_POST['ifaddr_start0'].".".$_POST['ifaddr_start1'].".".$_POST['ifaddr_start2'].".".$_POST['ifaddr_start3'];
      $_POST['ifaddr_end'] = $_POST['ifaddr_end0'].".".$_POST['ifaddr_end1'].".".$_POST['ifaddr_end2'].".".$_POST['ifaddr_end3'];
      $iprange->update($_POST);
   }
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["delete"])) {
	PluginFusioninventoryAuth::checkRight("iprange","w");
	$agents->iprange($_POST);
	glpi_header("iprange.php");
}


$id = "";
if (isset($_GET["id"])) {
	$id = $_GET["id"];
}

$iprange->showForm($id);

commonFooter();

?>