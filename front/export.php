<?php
/**
 * FusionInventory
 *
 * Copyright (C) 2010-2020 by the FusionInventory Development Team.
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
 * This file is used to manage the redirection to the documentation page.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Stanislas Kita
 * @copyright Copyright (c) 2010-2020 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

include ("../../../inc/includes.php");

Session::checkRight('plugin_fusioninventory_iprange', READ);

$ID = null;
if (isset($_GET['id'])) {
   $ID = $_GET['id'];
}

if (PluginFusioninventoryIPRange::exportAsYaml($ID)) {
   $filename = "fusioninventory_ip_conf.yaml";
   $path = GLPI_TMP_DIR."/fusioninventory_ip_conf.yaml";
   Toolbox::sendFile($path, $filename);
} else {
   Session::addMessageAfterRedirect("No data to export", false, INFO);
   Html::back();
}

