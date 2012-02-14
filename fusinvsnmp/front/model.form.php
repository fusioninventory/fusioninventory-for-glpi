<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

define('GLPI_ROOT', '../../..');

include (GLPI_ROOT . "/inc/includes.php");

PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","r");

$PluginFusinvsnmpModel = new PluginFusinvsnmpModel();
$PluginFusinvsnmpModelMib = new PluginFusinvsnmpModelMib();
$PluginFusinvsnmpImportExport = new PluginFusinvsnmpImportExport();

commonHeader($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","models");

PluginFusioninventoryMenu::displayMenu("mini");

//if (isset ($_POST["add"]) && isset($_POST["id"])) {
if (isset ($_POST["add"])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $PluginFusinvsnmpModel->add($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["update"])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $PluginFusinvsnmpModel->update($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset ($_POST["delete"])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $PluginFusinvsnmpModel->delete($_POST);
   glpi_header($CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/model.php");
} else if (isset ($_FILES['importfile']['tmp_name']) && $_FILES['importfile']['tmp_name']!='') {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $PluginFusinvsnmpImportExport->import($_FILES['importfile']['tmp_name']);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_GET["is_active"])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $PluginFusinvsnmpModelMib->activation($_GET["is_active"]);
   glpi_header($_SERVER['HTTP_REFERER']);
} else if (isset($_POST['massimport'])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $PluginFusinvsnmpImportExport->importMass();
   glpi_header($_SERVER['HTTP_REFERER']);
}
if (isset ($_POST["add_oid"])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $PluginFusinvsnmpModelMib->add($_POST);
   glpi_header($_SERVER['HTTP_REFERER']);
}
if(!empty($_POST["item_coche"])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $PluginFusinvsnmpModelMib->deleteMib($_POST["item_coche"]);
   glpi_header($_SERVER['HTTP_REFERER']);
}

if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","r")) {
   if (!isset($_GET["id"])) {
      $PluginFusinvsnmpImportExport->showForm($_SERVER["PHP_SELF"]);
      $PluginFusinvsnmpImportExport->showFormMassImport($_SERVER["PHP_SELF"]);
   }
}

if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","r")) {
   $id = "";
   if (isset($_GET["id"])) {
      $id = $_GET["id"];
   }
   $PluginFusinvsnmpModel->showForm($id);
   $PluginFusinvsnmpModelMib->showFormList($id, array('create'=>true));

}
commonFooter();

?>