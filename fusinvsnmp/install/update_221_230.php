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

// Update from 2.2.1 to 2.3.0
function update221to230() {
   global $DB;

   // Get informations of plugin
   $a_plugin = plugin_version_fusinvsnmp();
   $plugin = new Plugin();
   $data = $plugin->find("`directory` = 'fusinvsnmp'");
   $fields = current($data);
   $plugins_id = $fields['id'];

   $DB_file = GLPI_ROOT ."/plugins/fusinvsnmp/install/mysql/plugin_fusinvsnmp-2.3.0-1-update.sql";
   $DBf_handle = fopen($DB_file, "rt");
   $sql_query = fread($DBf_handle, filesize($DB_file));
   fclose($DBf_handle);
   foreach ( explode(";\n", "$sql_query") as $sql_line) {
      if (get_magic_quotes_runtime()) $sql_line=stripslashes_deep($sql_line);
      if (!empty($sql_line)) {
         $DB->query($sql_line);
      }
   }

   // Create folder in GLPI_PLUGIN_DOC_DIR
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'])) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname']);
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'].'/tmp')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'].'/tmp');
   }

   // ***** Todo : get from update
   $configLogField = new PluginFusinvsnmpConfigLogField();
   $configLogField->initConfig();

   /*
    * Manage profiles
    */
   // Convert datas
   if (is_callable(array("PluginFusinvsnmpStaticmisc", "profiles"))) {
      $a_profile = call_user_func(array("PluginFusinvsnmpStaticmisc", "profiles"));
      foreach ($a_profile as $data) {
         $sql_ins = "INSERT INTO glpi_plugin_fusioninventory_profiles
            (`type`, `right`, `plugins_id`, `profiles_id`)
            VALUES('".$data['profil']."', 'w', '".$plugins_id."', '".$_SESSION['glpiactiveprofile']['id']."')";
         $DB->query($sql_ins);
      }
   }
   $sql = "SELECT * FROM `glpi_plugin_fusinvsnmp_temp_profiles`";
   $result=$DB->query($sql);
   $Profile = new Profile();
   while ($data=$DB->fetch_array($result)) {
      if ($data['name'] != '') {
         $a_profiles = $Profile->find("`name`='".$data['name']."'");
         $a_profile = current($a_profiles);
         $profile_id = $a_profile['id'];
         if ($profile_id != $_SESSION['glpiactiveprofile']['id']) {
            if (!is_null($data['configuration'])) {
               $sql_ins = "INSERT INTO glpi_plugin_fusioninventory_profiles
                  (`type`, `right`, `plugins_id`, `profiles_id`)
                  VALUES('configuration', '".$data['configuration']."', '".$plugins_id."', '".$profile_id."')";
               $DB->query($sql_ins);
            }
            if (!is_null($data['rangeip'])) {
               $sql_ins = "INSERT INTO glpi_plugin_fusioninventory_profiles
                  (`type`, `right`, `plugins_id`, `profiles_id`)
                  VALUES('iprange', '".$data['rangeip']."', '".$plugins_id."', '".$profile_id."')";
               $DB->query($sql_ins);
            }
            if (!is_null($data['snmp_authentification'])) {
               $sql_ins = "INSERT INTO glpi_plugin_fusioninventory_profiles
                  (`type`, `right`, `plugins_id`, `profiles_id`)
                  VALUES('configsecurity', '".$data['snmp_authentification']."', '".$plugins_id."', '".$profile_id."')";
               $DB->query($sql_ins);
            }
            if (!is_null($data['snmp_models'])) {
               $sql_ins = "INSERT INTO glpi_plugin_fusioninventory_profiles
                  (`type`, `right`, `plugins_id`, `profiles_id`)
                  VALUES('model', '".$data['snmp_models']."', '".$plugins_id."', '".$profile_id."')";
               $DB->query($sql_ins);
            }
            if (!is_null($data['snmp_printers'])) {
               $sql_ins = "INSERT INTO glpi_plugin_fusioninventory_profiles
                  (`type`, `right`, `plugins_id`, `profiles_id`)
                  VALUES('printer', '".$data['snmp_printers']."', '".$plugins_id."', '".$profile_id."')";
               $DB->query($sql_ins);
            }
            if (!is_null($data['snmp_networking'])) {
               $sql_ins = "INSERT INTO glpi_plugin_fusioninventory_profiles
                  (`type`, `right`, `plugins_id`, `profiles_id`)
                  VALUES('networkequipment', '".$data['snmp_networking']."', '".$plugins_id."', '".$profile_id."')";
               $DB->query($sql_ins);
            }
         }
      }
   }
   $sql = "DROP TABLE `glpi_plugin_fusinvsnmp_temp_profiles`";
   $DB->query($sql);
   PluginFusioninventoryProfile::changeProfile($plugins_id);

   /*
    * Manage agents
    */
   $a_exceptions_query = array();
   $a_exceptions_discovery = array();
   $sql = "SELECT * FROM `glpi_plugin_fusinvsnmp_tmp_agents`";
   $result=$DB->query($sql);
   while ($data=$DB->fetch_array($result)) {
      $sql_ins = "INSERT INTO `glpi_plugin_fusinvsnmp_agentconfigs`
         (`plugin_fusioninventory_agents_id`, `threads_netdiscovery`,
         `threads_snmpquery`)
         VALUES('".$data['id']."',
                '".$data['threads_discovery']."',
                '".$data['threads_query']."')";
      $DB->query($sql_ins);
      if ($data['snmpquery'] == '1') {
         $a_exceptions_query[] = $data['id'];
      }
      if ($data['netdiscovery'] == '1') {
         $a_exceptions_discovery = $data['id'];
      }
   }
   $sql = "DROP TABLE `glpi_plugin_fusinvsnmp_tmp_agents`";
   $DB->query($sql);
   
   /*
    * Manage configs
    */
   $sql = "SELECT * FROM `glpi_plugin_fusinvsnmp_tmp_configs`";
   $result=$DB->query($sql);
   $auth = 'DB';
   while ($data=$DB->fetch_array($result)) {
      $auth = $data['authsnmp'];
   }
   $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
   $insert = array('storagesnmpauth'=>$auth);
   $PluginFusioninventoryConfig->initConfig($plugins_id, $insert);

   $sql = "DROP TABLE `glpi_plugin_fusinvsnmp_tmp_configs`";
   $DB->query($sql);




   $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
   $input = array();
   $input['plugins_id'] = $plugins_id;
   $input['modulename'] = "SNMPQUERY";
   $input['is_active']  = 0;
   $input['exceptions'] = exportArrayToDB($a_exceptions_query);
   $PluginFusioninventoryAgentmodule->add($input);

   $input = array();
   $input['plugins_id'] = $plugins_id;
   $input['modulename'] = "NETDISCOVERY";
   $input['is_active']  = 0;
   $input['exceptions'] = exportArrayToDB($a_exceptions_discovery);
   $PluginFusioninventoryAgentmodule->add($input);

   /*
    * Clean for port orphelin
    */
   //networkports with item_type = 0
   $NetworkPort = new NetworkPort();
   $NetworkPort_Vlan = new NetworkPort_Vlan();
   $NetworkPort_NetworkPort = new NetworkPort_NetworkPort();
   $a_networkports = $NetworkPort->find("`itemtype`=''");
   foreach ($a_networkports as $data) {
      if ($NetworkPort_NetworkPort->getFromDBForNetworkPort($data['id'])) {
         $NetworkPort_NetworkPort->delete($NetworkPort_NetworkPort->fields);
      }
      $a_vlans = $NetworkPort_Vlan->find("`networkports_id`='".$data['id']."'");
      foreach ($a_vlans as $a_vlan) {
         $NetworkPort_Vlan->delete($a_vlan);
      }
      $NetworkPort->delete($data, 1);
   }


   /*
    *  Convert displaypreferences
    */
   $sql = "UPDATE `glpi_displaypreferences`
      SET `itemtype`='PluginFusinvsnmpModel'
      WHERE `itemtype`='5151' ";
   $DB->query($sql);
   $sql = "UPDATE `glpi_displaypreferences`
      SET `itemtype`='PluginFusinvsnmpConfigSecurity'
      WHERE `itemtype`='5152' ";
   $DB->query($sql);
   $sql = "UPDATE `glpi_displaypreferences`
      SET `itemtype`='PluginFusinvsnmpPrinterCartridge'
      WHERE `itemtype`='5156' ";
   $DB->query($sql);
   $sql = "UPDATE `glpi_displaypreferences`
      SET `itemtype`='PluginFusinvsnmpNetworkPort'
      WHERE `itemtype`='5157' ";
   $DB->query($sql);
   $sql = "UPDATE `glpi_displaypreferences`
      SET `itemtype`='PluginFusinvsnmpIPRange'
      WHERE `itemtype`='5159' ";
   $DB->query($sql);
   $sql = "UPDATE `glpi_displaypreferences`
      SET `itemtype`='PluginFusinvsnmpConstructDevice'
      WHERE `itemtype`='5167' ";
   $DB->query($sql);
   $sql = "UPDATE `glpi_displaypreferences`
      SET `itemtype`='PluginFusinvsnmpPrinterLog'
      WHERE `itemtype`='5168' ";
   $DB->query($sql);


   /*
    * Update networports to convert itemtype 5153 to PluginFusioninventoryUnknownDevice
    */
   $sql = "UPDATE `glpi_networkports`
      SET `itemtype`='PluginFusioninventoryUnknownDevice'
      WHERE `itemtype`='5153'";
   $DB->query($sql);

   /*
    * Create tasks
    */
   $PluginFusioninventoryTask = new PluginFusioninventoryTask();
   $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();

   $sql = "SELECT * FROM `glpi_plugin_fusinvsnmp_tmp_tasks`";
   $result=$DB->query($sql);
   while ($data=$DB->fetch_array($result)) {
         $sql_select = "SELECT * FROM `glpi_plugin_fusioninventory_ipranges`
            WHERE `id`='".$data['rangeip_id']."'";
         $result_select = $DB->query($sql_select);
         $data_iprange = $DB->fetch_assoc($result_select);

         // Create task NETDISCOVERY
         $input = array();
         $permanent = exportArrayToDB(array('PluginFusioninventoryIPRange'=>$data['rangeip_id'], 'module'=>'NETDISCOVERY'));
         $input['name'] = "NETDISCOVERY of IP Range (permanent) : ".$data_iprange['name'];
         $input['date_creation'] = date("Y-m-d H:i:s");
         $input['is_active'] = $data['discoveractive'];
         $input['permanent'] = $permanent;
         $input["entities_id"]  = $data['entities_id'];
         $input['date_scheduled'] = date("Y-m-d H:i:s");
         $input['periodicity_count'] = "1";
         $input['periodicity_type'] = "hours";

         $task_id = $PluginFusioninventoryTask->add($input);

         $input = array();
         $input['plugin_fusioninventory_tasks_id'] = $task_id;
         $input['plugins_id'] = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
         $input['method'] = 'netdiscovery';
         $input['action'] = '[{"PluginFusioninventoryAgent":"'.$data['discoveragent_id'].'"}]';
         $input['definition'] = '[{"PluginFusioninventoryIPRange":"'.$data['rangeip_id'].'"}]';
         $input["entities_id"]  = $data['entities_id'];

         $PluginFusioninventoryTaskjob->add($input);

         // Create task SNMPINVENTORY
         $input = array();
         $permanent = exportArrayToDB(array('PluginFusioninventoryIPRange'=>$data['rangeip_id'], 'module'=>'SNMPQUERY'));
         $input['name'] = "SNMPQUERY of IP Range (permanent) : ".$data_iprange['name'];
         $input['date_creation'] = date("Y-m-d H:i:s");
         $input['is_active'] = $data['queryactive'];
         $input['permanent'] = $permanent;
         $input["entities_id"]  = $data['entities_id'];
         $input['date_scheduled'] = date("Y-m-d H:i:s");
         $input['periodicity_count'] = "1";
         $input['periodicity_type'] = "hours";

         $task_id = $PluginFusioninventoryTask->add($input);

         $input = array();
         $input['plugin_fusioninventory_tasks_id'] = $task_id;
         $input['plugins_id'] = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
         $input['method'] = 'snmpinventory';
         $input['action'] = '[{"PluginFusioninventoryAgent":"'.$data['queryagent_id'].'"}]';
         $input['definition'] = '[{"PluginFusioninventoryIPRange":"'.$data['rangeip_id'].'"}]';
         $input["entities_id"]  = $data['entities_id'];

         $PluginFusioninventoryTaskjob->add($input);
   }
   $sql = "DROP TABLE `glpi_plugin_fusinvsnmp_tmp_tasks`";
   $DB->query($sql);

}
?>