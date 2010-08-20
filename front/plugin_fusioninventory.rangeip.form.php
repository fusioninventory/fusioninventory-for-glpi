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

$NEEDED_ITEMS = array (
	"setup",
	"rulesengine",
	"fusioninventory",
	"search"
);

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

$rangeip = new PluginFusionInventoryRangeIP;

commonHeader($LANG['plugin_fusioninventory']["title"][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","rangeip");

plugin_fusioninventory_checkRight("rangeip","r");

plugin_fusioninventory_mini_menu();

if (isset ($_POST["add"])) {
   if ($rangeip->checkip($_POST)) {
      plugin_fusioninventory_checkRight("rangeip","w");
      $_POST['ifaddr_start'] = $_POST['ifaddr_start0'].".".$_POST['ifaddr_start1'].".".$_POST['ifaddr_start2'].".".$_POST['ifaddr_start3'];
      $_POST['ifaddr_end'] = $_POST['ifaddr_end0'].".".$_POST['ifaddr_end1'].".".$_POST['ifaddr_end2'].".".$_POST['ifaddr_end3'];
      $rangeip->add($_POST);
   } else {
      $_POST['ifaddr_start'] = $_POST['ifaddr_start0'].".".$_POST['ifaddr_start1'].".".$_POST['ifaddr_start2'].".".$_POST['ifaddr_start3'];
      $_POST['ifaddr_end'] = $_POST['ifaddr_end0'].".".$_POST['ifaddr_end1'].".".$_POST['ifaddr_end2'].".".$_POST['ifaddr_end3'];
      $_SESSION['glpi_plugin_fusioninventory_addrangeip'] = $_POST;
   }
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["update"])) {
   if ($rangeip->checkip($_POST)) {
      plugin_fusioninventory_checkRight("rangeip","w");
      $_POST['ifaddr_start'] = $_POST['ifaddr_start0'].".".$_POST['ifaddr_start1'].".".$_POST['ifaddr_start2'].".".$_POST['ifaddr_start3'];
      $_POST['ifaddr_end'] = $_POST['ifaddr_end0'].".".$_POST['ifaddr_end1'].".".$_POST['ifaddr_end2'].".".$_POST['ifaddr_end3'];
      $rangeip->update($_POST);
   }
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["delete"])) {
	plugin_fusioninventory_checkRight("rangeip","w");
	$agents->rangeip($_POST);
	glpi_header("plugin_fusioninventory.rangeip.php");
}


$ID = "";
if (isset($_GET["ID"])) {
	$ID = $_GET["ID"];
}

$rangeip->showForm($_SERVER["PHP_SELF"], $ID);

commonFooter();

?>