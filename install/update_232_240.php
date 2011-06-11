<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------


// Update from 2.3.0 to 2.4.0
function update232to240() {
   global $DB;

   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');
   $config = new PluginFusioninventoryConfig();
   if (!PluginFusioninventoryConfig::getValue($plugins_id, "import_vm")) {
       $config->initConfig($plugins_id, array("import_vm" => "1"));
   }
   if (!PluginFusioninventoryConfig::getValue($plugins_id, "component_networkdrive")) {
       $config->initConfig($plugins_id, array("component_networkdrive" => "1"));
   }

   if (TableExists("glpi_plugin_fusinvinventory_computers")) {
      $Computer = new Computer();
      $sql = "SELECT * FROM `glpi_plugin_fusinvinventory_computers`";
      $result=$DB->query($sql);
      while ($data = $DB->fetch_array($result)) {
         if ($Computer->getFromDB($data['items_id'])) {
            $Computer->fields['uuid'] = $data['uuid'];
            $Computer->update($Computer->fields);

         }
      }
      $sql = "DROP TABLE `glpi_plugin_fusinvinventory_computers`";
      $DB->query($sql);
   	
   }

   $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_agentmodules` WHERE `modulename`='ESX'";
   $result = $DB->query($query);
   if (!$DB->numrows($result)) {
      $agentmodule = new PluginFusioninventoryAgentmodule;
      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "ESX";
      $input['is_active']  = 1;
      $input['exceptions'] = exportArrayToDB(array());
      $input['url'] = PluginFusioninventoryRestCommunication:: getDefaultRestURL($_SERVER['HTTP_REFERER'], 
                                                                                 'fusinvinventory', 
                                                                                 'esx');
      $agentmodule->add($input);
   }
}
?>