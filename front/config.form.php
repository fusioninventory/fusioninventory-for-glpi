<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the general configuration form.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Vincent Mazzoni
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
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

$a_config = current($pfConfig->find([], [], 1));
$pfConfig->getFromDB($a_config['id']);
if (isset($_GET['glpi_tab'])) {
   $_SESSION['glpi_tabs']['pluginfusioninventoryconfiguration'] = $_GET['glpi_tab'];
   Html::redirect(Toolbox::getItemTypeFormURL($pfConfig->getType()));
}
$pfConfig->display();

Html::footer();

