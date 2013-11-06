<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

include ("../../../inc/includes.php");

PluginFusioninventoryProfile::checkRight("model", "r");

$pfModel = new PluginFusioninventorySnmpmodel();
$pfModelMib = new PluginFusioninventorySnmpmodelMib();
$pfImportExport = new PluginFusioninventorySnmpmodelImportExport();

Html::header(__('FusionInventory', 'fusioninventory'), $_SERVER["PHP_SELF"], "plugins", "fusioninventory", "models");

PluginFusioninventoryMenu::displayMenu("mini");

//if (isset ($_POST["add"]) && isset($_POST["id"])) {
if (isset ($_POST["add"])) {
   PluginFusioninventoryProfile::checkRight("model", "w");
   $pfModel->add($_POST);
   Html::back();
} else if (isset ($_POST["update"])) {
   PluginFusioninventoryProfile::checkRight("model", "w");
   $pfModel->update($_POST);
   Html::back();
} else if (isset ($_POST["delete"])) {
   PluginFusioninventoryProfile::checkRight("model", "w");
   $pfModel->delete($_POST);
   PluginFusioninventorySnmpmodelImportExport::exportDictionnaryFile();
   Html::redirect($CFG_GLPI['root_doc']."/plugins/fusioninventory/front/snmpmodel.php");
} else if (isset ($_FILES['importfile']['tmp_name']) && $_FILES['importfile']['tmp_name']!='') {
   PluginFusioninventoryProfile::checkRight("model", "w");
   $pfImportExport->import($_FILES['importfile']['tmp_name']);
   PluginFusioninventorySnmpmodelImportExport::exportDictionnaryFile();
   Html::back();
} else if (isset($_GET["is_active"])) {
   PluginFusioninventoryProfile::checkRight("model", "w");
   $pfModelMib->activation($_GET["is_active"]);
   Html::back();
} else if (isset($_POST['massimport'])) {
   PluginFusioninventoryProfile::checkRight("model", "w");
   $pfImportExport->importMass();
   Html::back();
}
if (isset ($_POST["add_oid"])) {
   PluginFusioninventoryProfile::checkRight("model", "w");
   $pfModelMib->add($_POST);
   Html::back();
}
if(!empty($_POST["item_coche"])) {
   PluginFusioninventoryProfile::checkRight("model", "w");
   $pfModelMib->deleteMib($_POST["item_coche"]);
   Html::back();
}

if(PluginFusioninventoryProfile::haveRight("model", "r")) {
   if (!isset($_GET["id"])) {
      $pfImportExport->showForm($_SERVER["PHP_SELF"]);
      $pfImportExport->showFormMassImport($_SERVER["PHP_SELF"]);
   }
}

if(PluginFusioninventoryProfile::haveRight("model", "r")) {
   $id = "";
   if (isset($_GET["id"])) {
      $id = $_GET["id"];
   }
   $pfModel->showForm($id);
   $pfModelMib->showFormList($id, array('create'=>TRUE));

}
Html::footer();

?>
