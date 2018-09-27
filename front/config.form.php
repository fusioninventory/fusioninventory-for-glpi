<?php
/**
 * ---------------------------------------------------------------------
 * FusionInventory plugin for GLPI
 * Copyright (C) 2010-2018 FusionInventory Development Team and contributors.
 *
 * http://fusioninventory.org/
 * ---------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory plugin for GLPI.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 * ---------------------------------------------------------------------
 */

/*
 * Manage the general configuration form.
 */
include ("../../../inc/includes.php");

Session::checkRight('plugin_fusioninventory_configuration', READ);

Html::header(__('Features', 'fusioninventory'), $_SERVER["PHP_SELF"],
             "admin", "pluginfusioninventorymenu", "config");


PluginFusioninventoryMenu::displayMenu("mini");

$pfConfig = new PluginFusioninventoryConfig();

if (isset($_POST['update'])) {
   $data = $_POST;
   unset($data['update']);
   unset($data['id']);
   unset($data['_glpi_csrf_token']);
   foreach ($data as $key=>$value) {
      $pfConfig->updateValue($key, $value);
   }
   Html::back();
}

$a_config = current($pfConfig->find("", "", 1));
$pfConfig->getFromDB($a_config['id']);
if (isset($_GET['glpi_tab'])) {
   $_SESSION['glpi_tabs']['pluginfusioninventoryconfiguration'] = $_GET['glpi_tab'];
   Html::redirect(Toolbox::getItemTypeFormURL($pfConfig->getType()));
}
$pfConfig->display();

Html::footer();

