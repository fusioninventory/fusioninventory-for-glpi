<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","r");

$plugin_fusioninventory_model_infos = new PluginFusinvsnmpModel;
$plugin_fusioninventory_mib = new PluginFusinvsnmpModelMib;

$importexport = new PluginFusinvsnmpImportExport;

commonHeader($LANG['plugin_fusioninventory']['title'][0], $_SERVER["PHP_SELF"], "plugins", "fusioninventory","models");

PluginFusioninventoryMenu::displayMenu("mini");

//if (isset ($_POST["add"]) && isset($_POST["id"])) {
if (isset ($_POST["add"])) {
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
	$plugin_fusioninventory_model_infos->add($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["update"])) {
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
	$plugin_fusioninventory_model_infos->update($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["delete"])) {
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
	$plugin_fusioninventory_model_infos->delete($_POST);
	glpi_header(GLPI_ROOT."/plugins/fusinvsnmp/front/model.php");
} else if (isset ($_FILES['importfile']['tmp_name']) && $_FILES['importfile']['tmp_name']!='') {
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
	$importexport->import($_FILES['importfile']['tmp_name']);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_GET["is_active"])) {
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
	$plugin_fusioninventory_mib->activation($_GET["is_active"]);
	glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST['massimport'])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $importexport->importMass();
	glpi_header($_SERVER['HTTP_REFERER']);
}
if (isset ($_POST["add_oid"])) {
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
	$plugin_fusioninventory_mib->add($_POST);
	glpi_header($_SERVER['HTTP_REFERER']);
}
if(!empty($_POST["item_coche"])) {
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
	$plugin_fusioninventory_mib->deleteMib($_POST["item_coche"]);
	glpi_header($_SERVER['HTTP_REFERER']);
}

if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","r")) {
   $importexport->showForm($_SERVER["PHP_SELF"]);
   $importexport->showFormMassImport($_SERVER["PHP_SELF"]);
}

if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","r")) {
   $id = "";
   if (isset($_GET["id"])) {
      $id = $_GET["id"];
   }
   $plugin_fusioninventory_model_infos->showForm($id);
   $plugin_fusioninventory_mib->showFormList($id, array('create'=>true));

}
commonFooter();

?>