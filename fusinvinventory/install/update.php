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

function pluginFusinvinventoryGetCurrentVersion($version) {
   global $DB;

   $pfConfig = new PluginFusioninventoryConfig();
   $version_installed = $pfConfig->getValue(PluginFusioninventoryModule::getModuleId("fusinvinventory"),
                                             "version");
   
   $versionconfig = '';
   if ($version_installed) {
      return $version_installed;
   } else {
      $pFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
      $a_findmodule = current($pFusioninventoryAgentmodule->find("`modulename`='INVENTORY'", "", 1));
      if (isset($a_findmodule['plugins_id'])) {
         $versionconfig = $pfConfig->getValue($a_findmodule['plugins_id'], "version");
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
   
   ini_set("max_execution_time", "0");
   ini_set("memory_limit", "-1");

   $migration = new $migrationname($current_version);
   $pfConfig = new PluginFusioninventoryConfig();
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');   
   
   if (!$pfConfig->getValue($plugins_id, 'states_id_default')) {
      $pfConfig->addValues($plugins_id, array('states_id_default' => 0));
   }
   if (!$pfConfig->getValue($plugins_id, "import_vm")) {
       $pfConfig->addValues($plugins_id, array("import_vm" => "1"));
   }
   if (!$pfConfig->getValue($plugins_id, "component_networkdrive")) {
       $pfConfig->addValues($plugins_id, array("component_networkdrive" => "1"));
   }
   if (!$pfConfig->getValue($plugins_id, "group")) {
       $pfConfig->addValues($plugins_id, array("group" => "0"));
   }
   if (!$pfConfig->getValue($plugins_id, "component_networkcardvirtual")) {
       $pfConfig->addValues($plugins_id, array("component_networkcardvirtual" => "1"));
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
   if ($DB->numrows($result) == '0') {
      if (!class_exists('PluginFusioninventoryAgentmodule')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/agentmodule.class.php");
      }
      $agentmodule = new PluginFusioninventoryAgentmodule();
      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "ESX";
      $input['is_active']  = 0;
      $input['exceptions'] = exportArrayToDB(array());
      $url= '';
      if (isset($_SERVER['HTTP_REFERER'])) {
         $url = $_SERVER['HTTP_REFERER'];
      }
      $input['url'] = '';
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
    * Table glpi_plugin_fusinvinventory_antivirus
    */
   $newTable = "glpi_plugin_fusinvinventory_antivirus";
   if (!TableExists($newTable)) {
      $DB->query("CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1");
   }   
   $migration->addField($newTable, 
                        "id", 
                        "int(11) NOT NULL AUTO_INCREMENT");
   $migration->addField($newTable, 
                        "computers_id", 
                        "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable, 
                        "name", 
                        "varchar(255) DEFAULT NULL");
   $migration->addField($newTable, 
                        "manufacturers_id", 
                        "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable, 
                        "version", 
                        "varchar(255) DEFAULT NULL");  
   $migration->addField($newTable, 
                        "is_active", 
                        "tinyint(1) NOT NULL DEFAULT '0'");
   $migration->addField($newTable, 
                        "uptodate", 
                        "tinyint(1) NOT NULL DEFAULT '0'");
   $migration->addKey($newTable, 
                       "name");
   $migration->addKey($newTable, 
                       "version");
   $migration->addKey($newTable, 
                       "is_active");
   $migration->addKey($newTable, 
                       "uptodate");

   
    /*
    * Table glpi_plugin_fusinvinventory_blacklists
    */
      $newTable = "glpi_plugin_fusinvinventory_blacklists";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable, 
                                 'id', 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 'plugin_fusioninventory_criterium_id', 
                                 'plugin_fusioninventory_criterium_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 'value', 
                                 'value', 
                                 "varchar(255) DEFAULT NULL");
      
      $migration->migrationOneTable($newTable);
      
         $migration->addField($newTable, 
                                 'id', 
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable, 
                                 'plugin_fusioninventory_criterium_id', 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                                 'value', 
                                 "varchar(255) DEFAULT NULL");
         $migration->addKey($newTable, 
                            "plugin_fusioninventory_criterium_id");
         
      
      
    /*
    * Table glpi_plugin_fusinvinventory_libserialization
    */
   $newTable = "glpi_plugin_fusinvinventory_libserialization";
   if (!TableExists($newTable)) {
      $DB->query("CREATE TABLE `".$newTable."` (
                     `internal_id` varchar(255) NOT NULL DEFAULT '',
                     PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci");
   }   
      $migration->changeField($newTable,
                              "internal_id",
                              "internal_id",
                              "varchar(255) NOT NULL DEFAULT ''");
      $migration->changeField($newTable,
                              "computers_id",
                              "computers_id",
                              "int(11) DEFAULT NULL");
      $migration->changeField($newTable,
                              "serialized_sections1",
                              "serialized_sections1",
                              "longtext DEFAULT NULL");
      $migration->changeField($newTable,
                              "serialized_sections2",
                              "serialized_sections2",
                              "longtext DEFAULT NULL");
      $migration->changeField($newTable,
                              "serialized_sections3",
                              "serialized_sections3",
                              "longtext DEFAULT NULL");
      $migration->changeField($newTable,
                              "hash",
                              "hash",
                              "varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->changeField($newTable,
                              "last_fusioninventory_update",
                              "last_fusioninventory_update",
                              "datetime DEFAULT NULL");
   
   $migration->migrationOneTable($newTable);
   
      $migration->addField($newTable,
                              "internal_id",
                              "varchar(255) NOT NULL DEFAULT ''");
      $migration->addField($newTable,
                              "computers_id",
                              "int(11) DEFAULT NULL");
      $migration->addField($newTable,
                              "serialized_sections1",
                              "longtext DEFAULT NULL");
      $migration->addField($newTable,
                              "serialized_sections2",
                              "longtext DEFAULT NULL");
      $migration->addField($newTable,
                              "serialized_sections3",
                              "longtext DEFAULT NULL");
      $migration->addField($newTable,
                              "hash",
                              "varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->addField($newTable,
                              "last_fusioninventory_update",
                              "datetime DEFAULT NULL");
   $migration->migrationOneTable($newTable);
   
   
   
   /*
    * Update pci and usb ids
    */
   foreach (array('usbid.sql', 'pciid.sql') as $sql) {
      $DB_file = GLPI_ROOT ."/plugins/fusinvinventory/install/mysql/$sql";
      $DBf_handle = fopen($DB_file, "rt");
      $sql_query = fread($DBf_handle, filesize($DB_file));
      fclose($DBf_handle);
      foreach ( explode(";\n", "$sql_query") as $sql_line) {
         if (Toolbox::get_magic_quotes_runtime()) $sql_line=Toolbox::stripslashes_deep($sql_line);
         if (!empty($sql_line)) {
            $DB->query($sql_line)/* or die($DB->error())*/;
         }
      }
   }
   
   


   /*
    * Create table `glpi_plugin_fusinvinventory_computer` appear in 0.80+1.1
    */
   $newTable = "glpi_plugin_fusinvinventory_computers";
   if (!TableExists($newTable)) {
      $DB->query("CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1");
   }   
      $migration->addField($newTable, 
                           "id", 
                           "int(11) NOT NULL AUTO_INCREMENT");
      $migration->addField($newTable, 
                           "computers_id", 
                           "int(11) NOT NULL DEFAULT '0'");   
      $migration->addField($newTable, 
                           "bios_date", 
                           "datetime DEFAULT NULL");
      $migration->addField($newTable, 
                           "bios_version", 
                           "varchar(255) DEFAULT NULL");
      $migration->addField($newTable, 
                           "bios_assettag",
                           "varchar(255) DEFAULT NULL");
      $migration->addField($newTable, 
                           "bios_manufacturers_id", 
                           "int(11) NOT NULL DEFAULT '0'");
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
   
   $migration->migrationOneTable($newTable);
   
   
   /*
    * Update serialized sections to mysql_real_escape_string(htmlspecialchars_decode("data"))
    */
   if (!strstr($current_version, "+")) {// All version before 0.80+1.1 (new versioning)
      $computer = new Computer();
      if (!class_exists('PluginFusinvinventoryComputer')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusinvinventory/inc/computer.class.php");
      }      
      $pfComputer = new PluginFusinvinventoryComputer();
      $migration->displayMessage("Convert computer inventory, may require some minutes");
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
            $previous_infosection = array();
            foreach ($arraySerializedSections as $valeur) {
               $arraySerializedSectionsTemp = explode("<<=>>", $valeur); // For each line, we create a table with data separated
               if (isset($arraySerializedSectionsTemp[0]) AND isset($arraySerializedSectionsTemp[1])) {
                  if ($arraySerializedSectionsTemp[0] != "" && $arraySerializedSectionsTemp[1] != "") { // that is added to infosections
                     $infoSections["sections"][$arraySerializedSectionsTemp[0]] = $arraySerializedSectionsTemp[1];
                  }
                  $previous_infosection = $arraySerializedSectionsTemp[0];
               } else if ($valeur != '') {
                  $infoSections["sections"][$previous_infosection] .= "\n".$valeur;
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
            if ($computer->getFromDB($data['computers_id'])) {
               $pfLib->_serializeIntoDB($data['internal_id'], $serializedSections);

               // * Add informations of BIOS (table glpi_plugin_fusinvinventory_computers)
               $input = array();
               $input['computers_id'] = $data['computers_id'];
               foreach($infoSections['sections'] as $name=>$section) {
                  $split = explode("/", $name);
                  if (($split[1] > 0) OR (strstr($split[1], 'd'))) {
                     $dataSection = unserialize($section);

                     if ($split[0] == 'BIOS') {
                        if (isset($dataSection['BDATE'])) {
                           $a_split = explode("/", $dataSection['BDATE']);
                           if (isset($a_split[1]) AND isset($a_split[2])) {
                              $input['bios_date'] = $a_split[2]."-".$a_split[0]."-".$a_split[1];
                           }
                        }
                        if (isset($dataSection['BVERSION'])) {
                           $input['bios_version'] = $dataSection['BVERSION'];
                        }
                        if (isset($dataSection['BMANUFACTURER'])) {
                           $input['bios_manufacturers_id'] = Dropdown::importExternal('Manufacturer',
                                                                                       $dataSection['BMANUFACTURER'],
                                                                                       $computer->fields['entities_id']);
                        }
                     }
                     if ($split[0] == 'HARDWARE') {
                        if (isset($dataSection['OSINSTALLDATE'])) {
                           $input['operatingsystem_installationdate'] = date("Y-m-d", $dataSection['OSINSTALLDATE']);
                        }
                        if (isset($dataSection['WINOWNER'])) {
                           $input['winowner'] = $dataSection['WINOWNER'];
                        }
                        if (isset($dataSection['WINCOMPANY'])) {
                           $input['wincompany'] = $dataSection['WINCOMPANY'];
                        }
                     }
                  }
               }
               $pfComputer->add($input);
            } else {
               $DB->query("DELETE FROM `glpi_plugin_fusinvinventory_libserialization`
                  WHERE `internal_id`='".$data['internal_id']."'");
            }
         }
      }
   }
   
   
   $migration->executeMigration();
   
   /*
    * Remove / at the end of printers (bugs in older versions of agents.
    */
   $printer = new Printer();
   $query = "SELECT * FROM `glpi_printers`
      WHERE `serial` LIKE '%/' ";
   $result=$DB->query($query);
   while ($data = $DB->fetch_array($result)) {
      $cleanSerial = preg_replace('/\/$/', '', $data['serial']);
      $querynb = "SELECT * FROM `glpi_printers`
         WHERE `serial`='".$cleanSerial."'
         LIMIT 1";
      $resultnb=$DB->query($querynb);
      if ($DB->numrows($resultnb) == '0') {
         $input = array();
         $input['id'] = $data['id'];
         $input["serial"] = $cleanSerial;
         $printer->update($input);
      }
   }
   
   
   
    
   /*
    * Update blacklist
    */
   $input = array();
   $input['03000200-0400-0500-0006-000700080009'] = '2';
   $input['6AB5B300-538D-1014-9FB5-B0684D007B53'] = '2';
   $input['01010101-0101-0101-0101-010101010101'] = '2';
   $input['20:41:53:59:4e:ff'] = '3';
   $input['02:00:4e:43:50:49'] = '3';
   $input['e2:e6:16:20:0a:35'] = '3';
   $input['d2:0a:2d:a0:04:be'] = '3';
   $input['00:a0:c6:00:00:00'] = '3';
   $input['d2:6b:25:2f:2c:e7'] = '3';
   $input['33:50:6f:45:30:30'] = '3';
   $input['0a:00:27:00:00:00'] = '3';
   $input['00:50:56:C0:00:01'] = '3';
   $input['00:50:56:C0:00:02'] = '3';
   $input['00:50:56:C0:00:03'] = '3';
   $input['00:50:56:C0:00:04'] = '3';
   $input['00:50:56:C0:00:08'] = '3';
   $input['FE:FF:FF:FF:FF:FF'] = '3';
   $input['00:00:00:00:00:00'] = '3';
   $input['00:0b:ca:fe:00:00'] = '3';
   $input['02:80:37:EC:02:00'] = '3';
   $input['MB-1234567890'] = '1';
   $input['Not Specified'] = '1';
   $input['OEM_Serial'] = '1';
   $input['SystemSerialNumb'] = '1';
   $input['Not'] = '2';
   foreach ($input as $value=>$type) {
      $query = "SELECT * FROM `glpi_plugin_fusinvinventory_blacklists`
         WHERE `plugin_fusioninventory_criterium_id`='".$type."'
          AND `value`='".$value."'";
      $result=$DB->query($query);
      if ($DB->numrows($result) == '0') {
         $query = "INSERT INTO `glpi_plugin_fusinvinventory_blacklists` (`plugin_fusioninventory_criterium_id`, `value`) VALUES
            ( '".$type."', '".$value."')";
         $DB->query($query);         
      }
   }
   
   $pfConfig->updateConfigType($plugins_id, 'version', PLUGIN_FUSINVINVENTORY_VERSION);
}

?>