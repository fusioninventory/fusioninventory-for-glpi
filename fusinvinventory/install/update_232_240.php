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
   if (!PluginFusioninventoryConfig::getValue($plugins_id, "group")) {
       $config->initConfig($plugins_id, array("group" => "0"));
   }
   if (!PluginFusioninventoryConfig::getValue($plugins_id, "component_networkcardvirtual")) {
       $config->initConfig($plugins_id, array("component_networkcardvirtual" => "1"));
   }
   
   if (TableExists("glpi_plugin_fusinvinventory_computers")) {
      $Computer = new Computer();
      $sql = "SELECT * FROM `glpi_plugin_fusinvinventory_computers`";
      $result=$DB->query($sql);
      while ($data = $DB->fetch_array($result)) {
         if ($Computer->getFromDB($data['items_id'])) {
            $input = array();
            $input['id'] = $data['items_id'];
            $input['uuid'] = $data['uuid'];
            $Computer->update($input);

         }
      }
      $sql = "DROP TABLE `glpi_plugin_fusinvinventory_computers`";
      $DB->query($sql);
      
   }

   if (TableExists("glpi_plugin_fusinvinventory_tmp_agents")) {
      $sql = "DROP TABLE `glpi_plugin_fusinvinventory_tmp_agents`";
      $DB->query($sql);
   }
   
   
   $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_agentmodules` WHERE `modulename`='ESX'";
   $result = $DB->query($query);
   if (!$DB->numrows($result)) {
      $agentmodule = new PluginFusioninventoryAgentmodule;
      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "ESX";
      $input['is_active']  = 0;
      $input['exceptions'] = exportArrayToDB(array());
      $input['url'] = PluginFusioninventoryRestCommunication:: getDefaultRestURL($_SERVER['HTTP_REFERER'], 
                                                                                 'fusinvinventory', 
                                                                                 'esx');
      $agentmodule->add($input);
   }
   
   // Update pci and usb ids
   foreach (array('usbid.sql', 'pciid.sql') as $sql) {
      $DB_file = GLPI_ROOT ."/plugins/fusinvinventory/install/mysql/$sql";
      $DBf_handle = fopen($DB_file, "rt");
      $sql_query = fread($DBf_handle, filesize($DB_file));
      fclose($DBf_handle);
      foreach ( explode(";\n", "$sql_query") as $sql_line) {
         if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
         if (!empty($sql_line)) {
            $DB->query($sql_line)/* or die($DB->error())*/;
         }
      }
   }
   
   // Udpate criteria for blacklist
   $sql = "SELECT * FROM `glpi_plugin_fusinvinventory_criterias`
      WHERE `name`='Manufacturer'";
   $result = $DB->query($query);
   if ($DB->numrows($result) == '0') {
      $query_ins = "INSERT INTO `glpi_plugin_fusinvinventory_criterias` (`name`, `comment`) VALUES
         ('Manufacturer', 'manufacturer')";
      $id = $DB->query($query_ins);
      $query_ins = "INSERT INTO `glpi_plugin_fusinvinventory_blacklists` (`plugin_fusioninventory_criterium_id`, `value`) VALUES
         ('".$id."', 'System manufacturer')";
   }
   
   
}
?>