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
 * Manage the switch (network equipment) extended information form.
 */
include ("../../../inc/includes.php");

Session::checkRight('networking', READ);
Session::checkRight('plugin_fusioninventory_networkequipment', UPDATE);

if ((isset($_POST['update'])) && (isset($_POST['id']))) {
   $pfNetworkEquipment = new PluginFusioninventoryNetworkEquipment();
   $pfNetworkEquipment->updateNetworkInfo($_POST['id'], $_POST['plugin_fusioninventory_configsecurities_id'], $_POST['sysdescr']);
}

Html::back();

