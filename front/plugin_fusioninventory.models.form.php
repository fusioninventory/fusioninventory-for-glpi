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

PluginFusioninventoryAuth::checkRight("snmp_models","r");

$plugin_fusioninventory_model_infos = new PluginFusioninventoryModelInfos;
$plugin_fusioninventory_mib_networking = new PluginFusioninventoryMib;

$importexport = new PluginFusioninventoryImportExport;

commonHeader($LANG['plugin_fusioninventory']["title"][0], $_SERVER["PHP_SELF"], "plugins", "fusioninventory","models");

PluginFusioninventoryDisplay::mini_menu();

//if (isset ($_POST["add"]) && isset($_POST["ID"])) {
if (isset ($_POST["add"])) {
	PluginFusioninventoryAuth::checkRight("snmp_models","w");
	$plugin_fusioninventory_model_infos->add($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["update"])) {
	PluginFusioninventoryAuth::checkRight("snmp_models","w");
	$plugin_fusioninventory_model_infos->update($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["delete"])) {
	PluginFusioninventoryAuth::checkRight("snmp_models","w");
	$plugin_fusioninventory_model_infos->delete($_POST);
	glpi_header("plugin_fusioninventory.models.php");
} else if (isset ($_FILES['importfile']['tmp_name']) && $_FILES['importfile']['tmp_name']!='') {
	PluginFusioninventoryAuth::checkRight("snmp_models","w");
	$importexport->import($_FILES['importfile']['tmp_name']);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_GET["activation"])) {
	PluginFusioninventoryAuth::checkRight("snmp_models","w");
	$plugin_fusioninventory_mib_networking->activation($_GET["activation"]);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST['massimport'])) {
   PluginFusioninventoryAuth::checkRight("snmp_models","w");
   $importexport->importMass();
	glpi_header($_SERVER['HTTP_REFERER']);
}
if (isset ($_POST["add_oid"])) {
	PluginFusioninventoryAuth::checkRight("snmp_models","w");
	$plugin_fusioninventory_mib_networking->add($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
}

if(PluginFusioninventory::HaveRight("snmp_models","r")) {
   $importexport->showForm($_SERVER["PHP_SELF"]);
   $importexport->showFormMassImport($_SERVER["PHP_SELF"]);
}
$ID = "";
if (isset($_GET["ID"])) {
	$ID = $_GET["ID"];
}

if(!empty($_POST["item_coche"])) {
	PluginFusioninventoryAuth::checkRight("snmp_models","w");
	$plugin_fusioninventory_mib_networking->deleteMib($_POST["item_coche"]);
	glpi_header($_SERVER['HTTP_REFERER']);
}

if(PluginFusioninventory::HaveRight("snmp_models","r")) {
   $plugin_fusioninventory_model_infos->showForm($_SERVER["PHP_SELF"], $ID);
   $plugin_fusioninventory_mib_networking->showForm($_SERVER["PHP_SELF"], $ID);
}
commonFooter();

?>