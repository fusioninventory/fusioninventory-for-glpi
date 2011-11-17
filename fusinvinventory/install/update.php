<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

function pluginFusinvinventoryGetCurrentVersion($version) {
   global $DB;

   $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
   $version_installed = $PluginFusioninventoryConfig->getValue(PluginFusioninventoryModule::getModuleId("fusinvinventory"),
                                             "version");
   $versionconfig = '';

   if ($version_installed) {
      return $version_installed;
   } else {
      $pFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
      $a_findmodule = current($pFusioninventoryAgentmodule->find("`modulename`='INVENTORY'", "", 1));
      if (isset($a_findmodule['plugins_id'])) {
         $versionconfig = $PluginFusioninventoryConfig->getValue($a_findmodule['plugins_id'], "version");
         if (PluginFusioninventoryModule::getModuleId("fusinvinventory") != $a_findmodule['plugins_id']) {
            $query = "UPDATE `glpi_plugin_fusioninventory_configs`
               SET `plugins_id`='".PluginFusioninventoryModule::getModuleId("fusinvinventory")."' 
               WHERE `plugins_id`='".$a_findmodule['plugins_id']."'";
            $DB->query($query);
            $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules`
               SET `plugins_id`='".PluginFusioninventoryModule::getModuleId("fusinvinventory")."' 
               WHERE `plugins_id`='".$a_findmodule['plugins_id']."'";
            $DB->query($query);
            $query = "UPDATE `glpi_plugin_fusioninventory_taskjobs`
               SET `plugins_id`='".PluginFusioninventoryModule::getModuleId("fusinvinventory")."' 
               WHERE `plugins_id`='".$a_findmodule['plugins_id']."'";
            $DB->query($query);
            $query = "UPDATE `glpi_plugin_fusioninventory_profiles`
               SET `plugins_id`='".PluginFusioninventoryModule::getModuleId("fusinvinventory")."' 
               WHERE `plugins_id`='".$a_findmodule['plugins_id']."'";
            $DB->query($query);
         }
      }
      if ($versionconfig) {
         return $versionconfig;
      }
      return '0';
   }
}


function pluginFusinvinventoryUpdate($current_version, $migrationname='Migration') {
   global $DB;

   $config = new PluginFusioninventoryConfig();
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');   
   
   if (!PluginFusioninventoryConfig::getValue($plugins_id, 'states_id_default')) {
      $config->initConfig($plugins_id, array('states_id_default' => 0));
   }
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
   
   if (TableExists("glpi_plugin_fusinvinventory_computers")
           AND FieldExists("glpi_plugin_fusinvinventory_computers", "uuid")) {
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
   
   /*
    * Add ESX module appear in version 2.4.0(0.80+1.0)
    */
   $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_agentmodules` WHERE `modulename`='ESX'";
   $result = $DB->query($query);
   if (!$DB->numrows($result)) {
      $agentmodule = new PluginFusioninventoryAgentmodule;
      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "ESX";
      $input['is_active']  = 0;
      $input['exceptions'] = exportArrayToDB(array());
      $input['url'] = PluginFusioninventoryRestCommunication::getDefaultRestURL($_SERVER['HTTP_REFERER'], 
                                                                                 'fusinvinventory', 
                                                                                 'esx');
      $agentmodule->add($input);
   }

   
   /*
    *  Udpate criteria for blacklist
    */
   $query = "SELECT * FROM `glpi_plugin_fusinvinventory_criterias`
      WHERE `name`='Manufacturer'";
   $result = $DB->query($query);
   if ($DB->numrows($result) == '0') {
      $query_ins = "INSERT INTO `glpi_plugin_fusinvinventory_criterias` (`name`, `comment`) VALUES
         ('Manufacturer', 'manufacturer')";
      $id = $DB->query($query_ins);
      $query_ins = "INSERT INTO `glpi_plugin_fusinvinventory_blacklists` (`plugin_fusioninventory_criterium_id`, `value`) VALUES
         ('".$id."', 'System manufacturer')";
   }
   
   
   /*
    * Update pci and usb ids
    */
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
   
   
   /*
    * Update serialized sections to mysql_real_escape_string(htmlspecialchars_decode("data"))
    */
   if (!strstr($current_version, "+")) {// All version before 0.80+1.1 (new versioning)
      if (!class_exists('PluginFusinvinventoryLib')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusinvinventory/inc/lib.class.php");
      }
      $pfLib = new PluginFusinvinventoryLib();
      $query = "SELECT * FROM `glpi_plugin_fusinvinventory_libserialization`";
      if ($result=$DB->query($query)) {
         while ($data=$DB->fetch_array($result)) {
            $infoSections = array();
            $infoSections["externalId"] = '';
            $infoSections["sections"] = array();
            $infoSections["sectionsToModify"] = array();

            /* Variables for the recovery and changes in the serialized sections */
            $serializedSections = "";
            $arraySerializedSections = array();
            $arraySerializedSectionsTemp = array();

            $infoSections["externalId"] = $data['internal_id'];
            $serializedSections = htmlspecialchars_decode($data['serialized_sections1'].$data['serialized_sections2'].$data['serialized_sections3'], ENT_QUOTES); // Recover double quotes
            $arraySerializedSections = explode("\n", $serializedSections); // Recovering a table with one line per entry
            foreach ($arraySerializedSections as $valeur) {
               $arraySerializedSectionsTemp = explode("<<=>>", $valeur); // For each line, we create a table with data separated
               if (isset($arraySerializedSectionsTemp[0]) AND isset($arraySerializedSectionsTemp[1])) {
                  if ($arraySerializedSectionsTemp[0] != "" && $arraySerializedSectionsTemp[1] != "") { // that is added to infosections
                     $infoSections["sections"][$arraySerializedSectionsTemp[0]] = $arraySerializedSectionsTemp[1];
                  }
               }
            }
            $infoSections['sections'] = $pfLib->convertData($infoSections['sections']);

            $serializedSections = "";
            foreach($infoSections["sections"] as $key => $serializedSection) {
               if (!strstr($key, "ENVS/")
                     AND !strstr($key, "PROCESSES/")) {

                  $serializedSections .= $key."<<=>>".$serializedSection."
";
               }
            }
            $pfLib->_serializeIntoDB($data['internal_id'], $serializedSections);
         }
      }
   }
   


   /*
    * Create table `glpi_plugin_fusinvinventory_computer` appear in 0.80+1.1
    */
   $migration = new Migration("0.80+1.1");
   $newTable = "glpi_plugin_fusinvinventory_computers";
   if (!TableExists($newTable)) {
      $DB->query("CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
   }   
   $migration->addField($newTable, 
                        "id", 
                        "int(11) NOT NULL AUTO_INCREMENT");
   $migration->addField($newTable, 
                        "computers_id", 
                        "int(11) NOT NULL default '0'");   
   $migration->addField($newTable, 
                        "bios_date", 
                        "datetime DEFAULT NULL");
   $migration->addField($newTable, 
                        "bios_version", 
                        "varchar(255) DEFAULT NULL");
   $migration->addField($newTable, 
                        "bios_manufacturers_id", 
                        "int(11) NOT NULL default '0'");
   $migration->addField($newTable, 
                        "operatingsystem_installationdate", 
                        "datetime DEFAULT NULL");
   $migration->addField($newTable, 
                        "winowner", 
                        "varchar(255) DEFAULT NULL");
   $migration->addField($newTable, 
                        "wincompany", 
                        "varchar(255) DEFAULT NULL");
   $migration->addKey($newTable, 
                       "computers_id");
    
   /* 
    * TODO : parse all libserialization to update the fields of the previous table not yet in DB
    */

    
    
   $config->updateConfigType($plugins_id, 'version', PLUGIN_FUSINVINVENTORY_VERSION);
}

?>