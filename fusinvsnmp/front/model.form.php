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

$pfModel = new PluginFusinvsnmpModel();
$pfModelMib = new PluginFusinvsnmpModelMib();
$pfImportExport = new PluginFusinvsnmpImportExport();

Html::header($LANG['plugin_fusioninventory']['title'][0],$_SERVER["PHP_SELF"],"plugins","fusioninventory","models");

PluginFusioninventoryMenu::displayMenu("mini");

//if (isset ($_POST["add"]) && isset($_POST["id"])) {
if (isset ($_POST["add"])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $pfModel->add($_POST);
   Html::back();
} else if (isset ($_POST["update"])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $pfModel->update($_POST);
   Html::back();
} else if (isset ($_POST["delete"])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $pfModel->delete($_POST);
   PluginFusinvsnmpImportExport::exportDictionnaryFile();
   Html::redirect($CFG_GLPI['root_doc']."/plugins/fusinvsnmp/front/model.php");
} else if (isset ($_FILES['importfile']['tmp_name']) && $_FILES['importfile']['tmp_name']!='') {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $pfImportExport->import($_FILES['importfile']['tmp_name']);
   PluginFusinvsnmpImportExport::exportDictionnaryFile();
   Html::back();
} else if (isset($_GET["is_active"])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $pfModelMib->activation($_GET["is_active"]);
   Html::back();
} else if (isset($_POST['massimport'])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $pfImportExport->importMass();
   Html::back();
}
if (isset ($_POST["add_oid"])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $pfModelMib->add($_POST);
   Html::back();
}
if(!empty($_POST["item_coche"])) {
   PluginFusioninventoryProfile::checkRight("fusinvsnmp", "model","w");
   $pfModelMib->deleteMib($_POST["item_coche"]);
   Html::back();
}

if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","r")) {
   if (!isset($_GET["id"])) {
      $pfImportExport->showForm($_SERVER["PHP_SELF"]);
      $pfImportExport->showFormMassImport($_SERVER["PHP_SELF"]);
   }
}

if(PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","r")) {
   $id = "";
   if (isset($_GET["id"])) {
      $id = $_GET["id"];
   }
   $pfModel->showForm($id);
   $pfModelMib->showFormList($id, array('create'=>true));

}
Html::footer();

?>