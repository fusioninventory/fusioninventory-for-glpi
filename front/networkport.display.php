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
 * Display the network port page.
 */
if (!defined('GLPI_ROOT')) {
   include ("../../../inc/includes.php");
}

// Manage for networkport display in networkequipment (glpi or fusion view
if (isset($_POST['selectview'])) {
   $_SESSION['plugin_fusioninventory_networkportview'] = $_POST['selectview'];
   Html::back();
}

if (isset($_POST["itemtype"])) {
   $itemtype = $_POST["itemtype"];
} else if (isset($_GET["itemtype"])) {
   $itemtype = $_GET["itemtype"];
} else {
   $itemtype = 0;
}

Session::checkRight('networking', READ);
Session::checkRight('internet', READ);
PluginFusioninventoryNetworkPort::showDislayOptions($itemtype);
Html::ajaxFooter();
