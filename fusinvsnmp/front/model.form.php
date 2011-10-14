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

include (GLPI_ROOT . "/inc/includes.php");

PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","r");

$PluginFusinvsnmpModel = new PluginFusinvsnmpModel();
$PluginFusinvsnmpModelMib = new PluginFusinvsnmpModelMib();
$PluginFusinvsnmpImportExport = new PluginFusinvsnmpImportExport();

Html::header($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","models");

PluginFusioninventoryMenu::displayMenu("mini");

//if (isset ($_POST["add"]) && isset($_POST["id"])) {
if (isset ($_POST["add"])) {
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
	$PluginFusinvsnmpModel->add($_POST);
	Html::back();
} else if (isset ($_POST["update"])) {
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
	$PluginFusinvsnmpModel->update($_POST);
	Html::back();
} else if (isset ($_POST["delete"])) {
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
	$PluginFusinvsnmpModel->delete($_POST);
	Html::redirect($CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/model.php");
} else if (isset ($_FILES['importfile']['tmp_name']) && $_FILES['importfile']['tmp_name']!='') {
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
	$PluginFusinvsnmpImportExport->import($_FILES['importfile']['tmp_name']);
	Html::back();
} else if (isset($_GET["is_active"])) {
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
	$PluginFusinvsnmpModelMib->activation($_GET["is_active"]);
	Html::back();
} else if (isset($_POST['massimport'])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $PluginFusinvsnmpImportExport->importMass();
	Html::back();
}
if (isset ($_POST["add_oid"])) {
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
	$PluginFusinvsnmpModelMib->add($_POST);
	Html::back();
}
if(!empty($_POST["item_coche"])) {
	PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
	$PluginFusinvsnmpModelMib->deleteMib($_POST["item_coche"]);
	Html::back();
}

if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","r")) {
   $PluginFusinvsnmpImportExport->showForm($_SERVER["PHP_SELF"]);
   $PluginFusinvsnmpImportExport->showFormMassImport($_SERVER["PHP_SELF"]);
}

if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","r")) {
   $id = "";
   if (isset($_GET["id"])) {
      $id = $_GET["id"];
   }
   $PluginFusinvsnmpModel->showForm($id);
   $PluginFusinvsnmpModelMib->showFormList($id, array('create'=>true));

}
Html::footer();

?>