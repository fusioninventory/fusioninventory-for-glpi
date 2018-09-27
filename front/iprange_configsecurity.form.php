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
 * Manage the IP range config security (SNMP authentication) form.
 */
include ("../../../inc/includes.php");

$pfIPRange_ConfigSecurity = new PluginFusioninventoryIPRange_ConfigSecurity();

if (isset ($_POST["add"])) {

   $a_data = current(getAllDatasFromTable('glpi_plugin_fusioninventory_ipranges_configsecurities',
                                 ['plugin_fusioninventory_ipranges_id' => $_POST['plugin_fusioninventory_ipranges_id']],
                                 false,
                                 '`rank` DESC'));
   $_POST['rank'] = 1;
   if (isset($a_data['rank'])) {
      $_POST['rank'] = $a_data['rank'] + 1;
   }
   $pfIPRange_ConfigSecurity->add($_POST);
   Html::back();
}

