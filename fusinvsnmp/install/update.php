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

function pluginFusinvsnmpGetCurrentVersion($version) {
   global $DB;

   if ((!TableExists("glpi_plugin_tracker_config")) &&
      (!TableExists("glpi_plugin_fusioninventory_config")) &&
      (!TableExists("glpi_plugin_fusinvsnmp_agentconfigs")) &&
      (!TableExists("glpi_plugin_fusinvsnmp_tmp_configs")) &&
      (!TableExists("glpi_plugin_fusinvsnmp_networkports"))) {
      return '0';
   } else if ((TableExists("glpi_plugin_tracker_config")) ||
         (TableExists("glpi_plugin_fusioninventory_config"))) {

      if ((!TableExists("glpi_plugin_tracker_agents")) &&
         (!TableExists("glpi_plugin_fusioninventory_agents"))) {
         return "1.1.0";
      }
      if ((!TableExists("glpi_plugin_tracker_config_discovery")) &&
         (!TableExists("glpi_plugin_fusioninventory_config"))) {
         return "2.0.0";
      }
      if (((TableExists("glpi_plugin_tracker_agents")) &&
           (!FieldExists("glpi_plugin_tracker_config", "version"))) &&
         (!TableExists("glpi_plugin_fusioninventory_config"))) {
         return "2.0.2";
      }
      if (((TableExists("glpi_plugin_tracker_agents")) &&
           (FieldExists("glpi_plugin_tracker_config", "version"))) ||
         (TableExists("glpi_plugin_fusioninventory_config")) ||
         (FieldExists("glpi_plugin_tracker_config", "version"))) {

         $query = "";
         if (TableExists("glpi_plugin_tracker_agents")) {
            $query = "SELECT version FROM glpi_plugin_tracker_config LIMIT 1";
         } if (TableExists("glpi_plugin_tracker_config")) {
            $query = "SELECT version FROM glpi_plugin_tracker_config LIMIT 1";            
         } else if (TableExists("glpi_plugin_fusioninventory_config")) {
            $query = "SELECT version FROM glpi_plugin_fusioninventory_config LIMIT 1";
         }
         $data = array();
         if ($query != "") {
            if ($result=$DB->query($query)) {
               if ($DB->numrows($result) == "1") {
                  $data = $DB->fetch_assoc($result);
               }
            }
         }
         if (!isset($data['version'])) {
            return "2.0.2";
         } else if ($data['version'] == "0") {
            return "2.0.2";
         } else {
            return $data['version'];
         }
      }
   } else if (FieldExists("glpi_plugin_fusinvsnmp_networkports", "FK_networking_ports")) {
      return "2.2.1";
   } else {
      if (!class_exists('PluginFusioninventoryConfig')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/config.class.php");
      }
      if (!class_exists('PluginFusioninventoryAgentmodule')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/agentmodule.class.php");
      }
      if (!class_exists('PluginFusioninventoryModule')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/module.class.php");
      }
      
      $pfConfig = new PluginFusioninventoryConfig();
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
      $versionconfig = $pfConfig->getValue($plugins_id, "version");
      if ((isset($versionconfig)) AND (!empty($versionconfig))) {
         if ($versionconfig == '2.2.1'
                 AND TableExists("glpi_plugin_fusinvsnmp_configlogfields")) {
            return "2.3.0-1";
         }
      }
      if ($versionconfig == '') {
         $pFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
         $a_findmodule = current($pFusioninventoryAgentmodule->find("`modulename`='NETDISCOVERY'", "", 1));
         if (isset($a_findmodule['plugins_id'])) {
            $versionconfig = $pfConfig->getValue($a_findmodule['plugins_id'], "version");
            if ((isset($versionconfig)) AND (!empty($versionconfig))) {
               if ($versionconfig == '2.2.1'
                       AND TableExists("glpi_plugin_fusinvsnmp_configlogfields")) {
                  return "2.3.0-1";
               }
            }
            if ($plugins_id != $a_findmodule['plugins_id']) {
               $query = "UPDATE `glpi_plugin_fusioninventory_configs`
                  SET `plugins_id`='".$plugins_id."' 
                  WHERE `plugins_id`='".$a_findmodule['plugins_id']."'";
               $DB->query($query);
               $query = "UPDATE `glpi_plugin_fusioninventory_agentmodules`
                  SET `plugins_id`='".$plugins_id."' 
                  WHERE `plugins_id`='".$a_findmodule['plugins_id']."'";
               $DB->query($query);
               $query = "UPDATE `glpi_plugin_fusioninventory_taskjobs`
                  SET `plugins_id`='".$plugins_id."' 
                  WHERE `plugins_id`='".$a_findmodule['plugins_id']."'";
               $DB->query($query);
               $query = "UPDATE `glpi_plugin_fusioninventory_profiles`
                  SET `plugins_id`='".$plugins_id."' 
                  WHERE `plugins_id`='".$a_findmodule['plugins_id']."'";
               $DB->query($query);
            }
         }
      }
      return $versionconfig;
   }
}



function pluginFusinvsnmpUpdate($current_version, $migrationname='Migration') {
   global $DB;

   ini_set("max_execution_time", "0");
   
   if (!class_exists('PluginFusioninventoryMapping')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/mapping.class.php");
   }

   $migration = new $migrationname($current_version);
   
   $migration->displayMessage("Update of plugin FusinvSNMP");
   
   $config = new PluginFusioninventoryConfig();
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
   
   if (!class_exists('PluginFusinvSNMPConfig')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusinvsnmp/inc/config.class.php");
   }
   $configSNMP = new PluginFusinvSNMPConfig();
   $configSNMP->initConfigModule();
   
   
   // Create folder in GLPI_PLUGIN_DOC_DIR
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusinvsnmp')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusinvsnmp');
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusinvsnmp/tmp')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusinvsnmp/tmp');
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusinvsnmp/walks')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusinvsnmp/walks');
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusinvsnmp/tmpmodels')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusinvsnmp/tmpmodels');
   }
   
   
   /*
    * Add SNMPQUERY module if not present 
    */
   $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_agentmodules` WHERE `modulename`='SNMPQUERY'";
   $result = $DB->query($query);
   if (!$DB->numrows($result)) {
      if (!class_exists('PluginFusioninventoryAgentmodule')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/agentmodule.class.php");
      }
      $agentmodule = new PluginFusioninventoryAgentmodule;
      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "SNMPQUERY";
      $input['is_active']  = 0;
      $input['exceptions'] = exportArrayToDB(array());
      $agentmodule->add($input);
   }
   
   /*
    * Add NETDISCOVERY module if not present 
    */
   $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_agentmodules` WHERE `modulename`='NETDISCOVERY'";
   $result = $DB->query($query);
   if (!$DB->numrows($result)) {
      if (!class_exists('PluginFusioninventoryAgentmodule')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/agentmodule.class.php");
      }
      $agentmodule = new PluginFusioninventoryAgentmodule;
      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "NETDISCOVERY";
      $input['is_active']  = 0;
      $input['exceptions'] = exportArrayToDB(array());
      $agentmodule->add($input);
   }
   
   /*
    * Udpate mapping
    */
   $pFusioninventoryMapping = new PluginFusioninventoryMapping();
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'location';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'locations_id';
   $a_input['locale']      = 1;
   $pFusioninventoryMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'firmware';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'networkequipmentfirmwares_id';
   $a_input['locale']      = 2;
   $pFusioninventoryMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'firmware1';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 2;
   $pFusioninventoryMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'firmware2';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 2;
   $pFusioninventoryMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'contact';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'contact';
   $a_input['locale']      = 403;
   $pFusioninventoryMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'comments';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'comment';
   $a_input['locale']      = 404;
   $pFusioninventoryMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'uptime';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_networkequipments';
   $a_input['tablefield']  = 'uptime';
   $a_input['locale']      = 3;
   $pFusioninventoryMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cpu';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_networkequipments';
   $a_input['tablefield']  = 'cpu';
   $a_input['locale']      = 12;
   $pFusioninventoryMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cpuuser';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_networkequipments';
   $a_input['tablefield']  = 'cpu';
   $a_input['locale']      = 401;
   $pFusioninventoryMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cpusystem';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_networkequipments';
   $a_input['tablefield']  = 'cpu';
   $a_input['locale']      = 402;
   $pFusioninventoryMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'serial';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'serial';
   $a_input['locale']      = 13;
   $pFusioninventoryMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'otherserial';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'otherserial';
   $a_input['locale']      = 419;
   $pFusioninventoryMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'name';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'name';
   $a_input['locale']      = 20;
   $pFusioninventoryMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ram';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'ram';
   $a_input['locale']      = 21;
   $pFusioninventoryMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'memory';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_networkequipments';
   $a_input['tablefield']  = 'memory';
   $a_input['locale']      = 22;
   $pFusioninventoryMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'vtpVlanName';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 19;
   $pFusioninventoryMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'vmvlan';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 430;
   $pFusioninventoryMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'entPhysicalModelName';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'networkequipmentmodels_id';
   $a_input['locale']      = 17;
   $pFusioninventoryMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'macaddr';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'ip';
   $a_input['locale']      = 417;
   $pFusioninventoryMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cdpCacheAddress';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 409;
   $pFusioninventoryMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cdpCacheDevicePort';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 410;
   $pFusioninventoryMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cdpCacheVersion';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 435;
   $pFusioninventoryMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cdpCacheDeviceId';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 436;
   $pFusioninventoryMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cdpCachePlatform';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 437;
   $pFusioninventoryMapping->set($a_input);
   
   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpRemChassisId';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 431;
   $pFusioninventoryMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpRemPortId';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 432;
   $pFusioninventoryMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpLocChassisId';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 432;
   $pFusioninventoryMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpRemSysDesc';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 438;
   $pFusioninventoryMapping->set($a_input);

   $a_input = array();
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpRemSysName';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 439;
   $pFusioninventoryMapping->set($a_input);
   $a_input = array();

   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpRemPortDesc';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 440;
   $pFusioninventoryMapping->set($a_input);

   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'vlanTrunkPortDynamicStatus';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 411;
   $pFusioninventoryMapping->set($a_input);

   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'dot1dTpFdbAddress';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 412;
   $pFusioninventoryMapping->set($a_input);

   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ipNetToMediaPhysAddress';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 413;
   $pFusioninventoryMapping->set($a_input);

   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'dot1dTpFdbPort';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 414;
   $pFusioninventoryMapping->set($a_input);

   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'dot1dBasePortIfIndex';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 415;
   $pFusioninventoryMapping->set($a_input);

   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ipAdEntAddr';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 421;
   $pFusioninventoryMapping->set($a_input);

   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'PortVlanIndex';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 422;
   $pFusioninventoryMapping->set($a_input);

   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifIndex';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 408;
   $pFusioninventoryMapping->set($a_input);

   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifmtu';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_networkports';
   $a_input['tablefield']  = 'ifmtu';
   $a_input['locale']      = 4;
   $pFusioninventoryMapping->set($a_input);

   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifspeed';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_networkports';
   $a_input['tablefield']  = 'ifspeed';
   $a_input['locale']      = 5;
   $pFusioninventoryMapping->set($a_input);

   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifinternalstatus';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_networkports';
   $a_input['tablefield']  = 'ifinternalstatus';
   $a_input['locale']      = 6;
   $pFusioninventoryMapping->set($a_input);

   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'iflastchange';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_networkports';
   $a_input['tablefield']  = 'iflastchange';
   $a_input['locale']      = 7;
   
   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifinoctets';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_networkports';
   $a_input['tablefield']  = 'ifinoctets';
   $a_input['locale']      = 8;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifoutoctets';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_networkports';
   $a_input['tablefield']  = 'ifoutoctets';
   $a_input['locale']      = 9;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifinerrors';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_networkports';
   $a_input['tablefield']  = 'ifinerrors';
   $a_input['locale']      = 10;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifouterrors';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_networkports';
   $a_input['tablefield']  = 'ifouterrors';
   $a_input['locale']      = 11;
   $pFusioninventoryMapping->set($a_input);
   
   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifstatus';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_networkports';
   $a_input['tablefield']  = 'ifstatus';
   $a_input['locale']      = 14;
   $pFusioninventoryMapping->set($a_input);
   
   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifPhysAddress';
   $a_input['table']       = 'glpi_networkports';
   $a_input['tablefield']  = 'mac';
   $a_input['locale']      = 15;
   $pFusioninventoryMapping->set($a_input);
   
   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifName';
   $a_input['table']       = 'glpi_networkports';
   $a_input['tablefield']  = 'name';
   $a_input['locale']      = 16;
   $pFusioninventoryMapping->set($a_input);
   
   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifType';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 18;
   $pFusioninventoryMapping->set($a_input);
   
   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifdescr';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_networkports';
   $a_input['tablefield']  = 'ifdescr';
   $a_input['locale']      = 23;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'portDuplex';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_networkports';
   $a_input['tablefield']  = 'portduplex';
   $a_input['locale']      = 33;
   $pFusioninventoryMapping->set($a_input);

   // Printers
   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'model';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'printermodels_id';
   $a_input['locale']      = 25;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'enterprise';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'manufacturers_id';
   $a_input['locale']      = 420;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'serial';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'serial';
   $a_input['locale']      = 27;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'contact';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'contact';
   $a_input['locale']      = 405;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'comments';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'comment';
   $a_input['locale']      = 406;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'name';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'comment';
   $a_input['locale']      = 24;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'otherserial';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'otherserial';
   $a_input['locale']      = 418;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'memory';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'memory_size';
   $a_input['locale']      = 26;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'location';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'locations_id';
   $a_input['locale']      = 56;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'informations';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 165;
   $a_input['shortlocale'] = 165;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblack';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 157;
   $a_input['shortlocale'] = 157;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblackmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 166;
   $a_input['shortlocale'] = 166;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblackused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 167;
   $a_input['shortlocale'] = 167;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblackremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 168;
   $a_input['shortlocale'] = 168;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblack2';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 157;
   $a_input['shortlocale'] = 157;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblack2max';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 166;
   $a_input['shortlocale'] = 166;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblack2used';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 167;
   $a_input['shortlocale'] = 167;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblack2remaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 168;
   $a_input['shortlocale'] = 168;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonercyan';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 158;
   $a_input['shortlocale'] = 158;
   $pFusioninventoryMapping->set($a_input);
   
   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonercyanmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 169;
   $a_input['shortlocale'] = 169;
   $pFusioninventoryMapping->set($a_input);
      
   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonercyanused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 170;
   $a_input['shortlocale'] = 170;
   $pFusioninventoryMapping->set($a_input);
   
   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonercyanremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 171;
   $a_input['shortlocale'] = 171;
   $pFusioninventoryMapping->set($a_input);
   
   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonermagenta';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 159;
   $a_input['shortlocale'] = 159;
   $pFusioninventoryMapping->set($a_input);
   
   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonermagentamax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 172;
   $a_input['shortlocale'] = 172;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonermagentaused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 173;
   $a_input['shortlocale'] = 173;
   $pFusioninventoryMapping->set($a_input);
   
   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonermagentaremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 174;
   $a_input['shortlocale'] = 174;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'toneryellow';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 160;
   $a_input['shortlocale'] = 160;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'toneryellowmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 175;
   $a_input['shortlocale'] = 175;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'toneryellowused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 176;
   $a_input['shortlocale'] = 176;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'toneryellowused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 177;
   $a_input['shortlocale'] = 177;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'wastetoner';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 151;
   $a_input['shortlocale'] = 151;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'wastetonermax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 190;
   $a_input['shortlocale'] = 190;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'wastetonerused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 191;
   $a_input['shortlocale'] = 191;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'wastetonerremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 192;
   $a_input['shortlocale'] = 192;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgeblack';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 134;
   $a_input['shortlocale'] = 134;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgeblackphoto';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 135;
   $a_input['shortlocale'] = 135;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgecyan';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 136;
   $a_input['shortlocale'] = 136;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgecyanlight';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 139;
   $a_input['shortlocale'] = 139;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgemagenta';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 138;
   $a_input['shortlocale'] = 138;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgemagentalight';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 140;
   $a_input['shortlocale'] = 140;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgeyellow';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 137;
   $a_input['shortlocale'] = 137;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgegrey';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 196;
   $a_input['shortlocale'] = 196;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'maintenancekit';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 156;
   $a_input['shortlocale'] = 156;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'maintenancekitmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 193;
   $a_input['shortlocale'] = 193;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'maintenancekitused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 194;
   $a_input['shortlocale'] = 194;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'maintenancekitremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 195;
   $a_input['shortlocale'] = 195;
   $pFusioninventoryMapping->set($a_input);
   
   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumblack';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 161;
   $a_input['shortlocale'] = 161;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumblackmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 178;
   $a_input['shortlocale'] = 178;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumblackused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 179;
   $a_input['shortlocale'] = 179;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumblackremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 180;
   $a_input['shortlocale'] = 180;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumcyan';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 162;
   $a_input['shortlocale'] = 162;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumcyanmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 181;
   $a_input['shortlocale'] = 181;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumcyanused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 182;
   $a_input['shortlocale'] = 182;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumcyanremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 183;
   $a_input['shortlocale'] = 183;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drummagenta';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 163;
   $a_input['shortlocale'] = 163;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drummagentamax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 184;
   $a_input['shortlocale'] = 184;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drummagentaused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 185;
   $a_input['shortlocale'] = 185;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drummagentaremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 186;
   $a_input['shortlocale'] = 186;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumyellow';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 164;
   $a_input['shortlocale'] = 164;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumyellowmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 187;
   $a_input['shortlocale'] = 187;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumyellowused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 188;
   $a_input['shortlocale'] = 188;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumyellowremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 189;
   $a_input['shortlocale'] = 189;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountertotalpages';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_printerlogs';
   $a_input['tablefield']  = 'pages_total';
   $a_input['locale']      = 28;
   $a_input['shortlocale'] = 128;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterblackpages';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_printerlogs';
   $a_input['tablefield']  = 'pages_n_b';
   $a_input['locale']      = 29;
   $a_input['shortlocale'] = 129;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountercolorpages';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_printerlogs';
   $a_input['tablefield']  = 'pages_color';
   $a_input['locale']      = 30;
   $a_input['shortlocale'] = 130;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterrectoversopages';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_printerlogs';
   $a_input['tablefield']  = 'pages_recto_verso';
   $a_input['locale']      = 54;
   $a_input['shortlocale'] = 154;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterscannedpages';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_printerlogs';
   $a_input['tablefield']  = 'scanned';
   $a_input['locale']      = 55;
   $a_input['shortlocale'] = 155;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountertotalpages_print';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_printerlogs';
   $a_input['tablefield']  = 'pages_total_print';
   $a_input['locale']      = 423;
   $a_input['shortlocale'] = 1423;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterblackpages_print';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_printerlogs';
   $a_input['tablefield']  = 'pages_n_b_print';
   $a_input['locale']      = 424;
   $a_input['shortlocale'] = 1424;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountercolorpages_print';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_printerlogs';
   $a_input['tablefield']  = 'pages_color_print';
   $a_input['locale']      = 425;
   $a_input['shortlocale'] = 1425;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountertotalpages_copy';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_printerlogs';
   $a_input['tablefield']  = 'pages_total_copy';
   $a_input['locale']      = 426;
   $a_input['shortlocale'] = 1426;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterblackpages_copy';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_printerlogs';
   $a_input['tablefield']  = 'pages_n_b_copy';
   $a_input['locale']      = 427;
   $a_input['shortlocale'] = 1427;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountercolorpages_copy';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_printerlogs';
   $a_input['tablefield']  = 'pages_color_copy';
   $a_input['locale']      = 428;
   $a_input['shortlocale'] = 1428;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountertotalpages_fax';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_printerlogs';
   $a_input['tablefield']  = 'pages_total_fax';
   $a_input['locale']      = 429;
   $a_input['shortlocale'] = 1429;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterlargepages';
   $a_input['table']       = 'glpi_plugin_fusinvsnmp_printerlogs';
   $a_input['tablefield']  = 'pages_total_large';
   $a_input['locale']      = 434;
   $a_input['shortlocale'] = 1434;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'ifPhysAddress';
   $a_input['table']       = 'glpi_networkports';
   $a_input['tablefield']  = 'mac';
   $a_input['locale']      = 48;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'ifName';
   $a_input['table']       = 'glpi_networkports';
   $a_input['tablefield']  = 'name';
   $a_input['locale']      = 57;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'ifaddr';
   $a_input['table']       = 'glpi_networkports';
   $a_input['tablefield']  = 'ip';
   $a_input['locale']      = 407;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'ifType';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 97;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'ifIndex';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 416;
   $pFusioninventoryMapping->set($a_input);

   
   // ** Computer
   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Computer';
   $a_input['name']        = 'serial';
   $a_input['table']       = '';
   $a_input['tablefield']  = 'serial';
   $a_input['locale']      = 13;
   $pFusioninventoryMapping->set($a_input);

   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Computer';
   $a_input['name']        = 'ifPhysAddress';
   $a_input['table']       = '';
   $a_input['tablefield']  = 'mac';
   $a_input['locale']      = 15;
   $pFusioninventoryMapping->set($a_input);
   
   $pFusioninventoryMapping->set($a_input);
   $a_input['itemtype']    = 'Computer';
   $a_input['name']        = 'ifaddr';
   $a_input['table']       = '';
   $a_input['tablefield']  = 'ip';
   $a_input['locale']      = 407;
   $pFusioninventoryMapping->set($a_input);
   
   
   
   /*
    * glpi_plugin_fusinvsnmp_miblabels
    */
      $newTable = "glpi_plugin_fusinvsnmp_miblabels";
      $migration->renameTable("glpi_dropdown_plugin_tracker_mib_label", 
                              $newTable);
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }   
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "comment",
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");      
      $migration->migrationOneTable($newTable);            
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "comments",
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");         
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
 
      
   /*
    *  glpi_plugin_fusinvsnmp_mibobjects
    */
      $newTable = "glpi_plugin_fusinvsnmp_mibobjects";
      $migration->renameTable("glpi_dropdown_plugin_tracker_mib_object", 
                              $newTable);
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }      
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "comment",
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");      
      $migration->migrationOneTable($newTable);            
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "comments",
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");         
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         
      
   /*
    *  glpi_plugin_fusinvsnmp_miboids
    */
      $newTable = "glpi_plugin_fusinvsnmp_miboids";
      $migration->renameTable("glpi_dropdown_plugin_tracker_mib_oid", 
                              $newTable);
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }      
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "comment",
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");      
      $migration->migrationOneTable($newTable);            
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "comments",
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->dropField($newTable,
                               "plugin_fusinvsnmp_constructdevices_id");
         $migration->dropField($newTable,
                               "oid_port_counter");
         $migration->dropField($newTable,
                               "oid_port_dyn");
         $migration->dropField($newTable,
                               "itemtype");
         $migration->dropField($newTable,
                               "vlan");         
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "name",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "comment",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         
      
   /*
    * glpi_plugin_fusinvsnmp_configlogfields
    */
      $newTable = "glpi_plugin_fusinvsnmp_configlogfields";
      $migration->renameTable("glpi_plugin_fusioninventory_config_snmp_history", 
                              $newTable);
      if (TableExists($newTable)) {
         if (FieldExists($newTable, "field")) {
            $query = "SELECT * FROM `".$newTable."`";
            $result=$DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $pFusioninventoryMapping = new PluginFusioninventoryMapping();
               $mapping = 0;
               if ($mapping = $pFusioninventoryMapping->get("NetworkEquipment", $data['field'])) {
                  $queryu = "UPDATE `".$newTable."`
                     SET `field`='".$mapping['id']."'
                     WHERE `field`='".$data['field']."'";
                  $DB->query($queryu);
               }
            }
         }
      }
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(8) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(8) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "days",
                                 "days",
                                 "int(255) NOT NULL DEFAULT '-1'");         
      $migration->migrationOneTable($newTable);            
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(8) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "field",
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");      
      $migration->migrationOneTable($newTable);             
         $migration->addField($newTable,
                                 "id",
                                 "int(8) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "days",
                                 "int(255) NOT NULL DEFAULT '-1'");
         $migration->addKey($newTable,
                            "plugin_fusioninventory_mappings_id");
      $migration->migrationOneTable($newTable);
         if (!class_exists('PluginFusinvsnmpConfigLogField')) { // if plugin is unactive
            include(GLPI_ROOT . "/plugins/fusinvsnmp/inc/configlogfield.class.php");
         }
         $configLogField = new PluginFusinvsnmpConfigLogField();
         $configLogField->initConfig();
      
      
      
      
   /*
    * glpi_plugin_fusinvsnmp_constructdevices
    */
      $newTable = "glpi_plugin_fusinvsnmp_constructdevices";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable,
                                 "have_someinformations",
                                 "have_someinformations",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "have_importantinformations",
                                 "have_importantinformations",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "have_ports",
                                 "have_ports",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "have_portsconnections",
                                 "have_portsconnections",
                                 "tinyint(1) NOT NULL DEFAULT '0'"); 
         $migration->changeField($newTable,
                                 "have_vlan",
                                 "have_vlan",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "have_trunk",
                                 "have_trunk",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "released",
                                 "released",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "snmpmodel_id",
                                 "plugin_fusinvsnmp_models_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_models_id",
                                 "plugin_fusinvsnmp_models_id",
                                 "int(11) NOT NULL DEFAULT '0'"); 
         $migration->changeField($newTable,
                                 "FK_glpi_enterprise",
                                 "manufacturers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 "type",
                                 "itemtype", 
                                 "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->dropField($newTable, "device");
         $migration->dropField($newTable, "firmware");
   $migration->migrationOneTable($newTable);      
      $migration->addField($newTable, 
                           "manufacturers_id", 
                           "int(11) NOT NULL DEFAULT '0'");   
      $migration->addField($newTable, 
                           "sysdescr", 
                           "text DEFAULT NULL");
      $migration->addField($newTable, 
                           "itemtype", 
                           "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->addField($newTable, 
                           "plugin_fusinvsnmp_models_id", 
                           "int(11) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "networkmodel_id", 
                           "int(11) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "printermodel_id", 
                           "int(11) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "have_someinformations", 
                           "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "have_importantinformations", 
                           "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "have_ports", 
                           "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "have_portsconnections", 
                           "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "have_vlan", 
                           "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "have_trunk", 
                           "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "released", 
                           "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "releasedsnmpmodel_id", 
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->migrationOneTable($newTable);
   
   
   /*
    * Table glpi_plugin_fusinvsnmp_constructdevicewalks
    */
      $newTable = "glpi_plugin_fusinvsnmp_constructdevicewalks";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "construct_device_id",
                                 "plugin_fusinvsnmp_constructdevices_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_constructdevices_id",
                                 "plugin_fusinvsnmp_constructdevices_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "log",
                                 "log",
                                 "text DEFAULT NULL");          
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                              "plugin_fusinvsnmp_constructdevices_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "log",
                              "text DEFAULT NULL");
      $migration->migrationOneTable($newTable);

      
      
   /*
    * Table glpi_plugin_fusinvsnmp_constructdevices_users
    */
      $newTable = "glpi_plugin_fusinvsnmp_constructdevices_users";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "users_id",
                                 "users_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "login",
                                 "login",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");         
         $migration->changeField($newTable,
                                 "password",
                                 "password",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");         
         $migration->changeField($newTable,
                                 "key",
                                 "key",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");         
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                              "users_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "login",
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");         
         $migration->addField($newTable,
                              "password",
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");         
         $migration->addField($newTable,
                              "key",
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");         
         $migration->addKey($newTable,
                            "users_id");
      $migration->migrationOneTable($newTable);
      

      
   /*
    * Table glpi_plugin_fusioninventory_construct_walks
    */
      $newTable = "glpi_plugin_fusioninventory_construct_walks";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "construct_device_id",
                                 "construct_device_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "log",
                                 "log",
                                 "varchar(255) DEFAULT NULL");          
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                              "construct_device_id",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "log",
                              "varchar(255) DEFAULT NULL");          
      $migration->migrationOneTable($newTable);
      
      
      
   /*
    * Table glpi_plugin_fusinvsnmp_constructdevice_miboids
    */
      $newTable = "glpi_plugin_fusinvsnmp_constructdevice_miboids";

      // Update with mapping
      if (TableExists($newTable)) {
         if (FieldExists($newTable, "mapping_name")
                 AND FieldExists($newTable, "itemtype")) {
            $query = "SELECT * FROM `".$newTable."`
               GROUP BY `itemtype`, `mapping_type`";
            $result=$DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               if (!is_numeric($data['mapping_name'])) {
                  $pFusioninventoryMapping = new PluginFusioninventoryMapping();
                  $mapping = 0;
                  $mapping_type = '';
                  if ($data['itemtype'] == 'glpi_networkequipments') {
                     $mapping_type = 'NetworkEquipment';
                  } else if ($data['itemtype'] == 'glpi_printers') {
                     $mapping_type = 'Printer';
                  }
                  if ($mapping = $pFusioninventoryMapping->get($mapping_type, $data['mapping_name'])) {
                     $data['mapping_name'] = $mapping['id'];
                     $queryu = "UPDATE `".$newTable."`
                        SET `mapping_name`='".$mapping['id']."',
                           `mapping_type`='".$mapping_type."'
                        WHERE `itemtype`='".$data['itemtype']."'
                           AND `mapping_name`='".$data['mapping_name']."'";
                     $DB->query($queryu);
                  }
               }
            }
         }
         $migration->changeField($newTable,
                                 "mapping_name",
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");
      }   
   $migration->migrationOneTable($newTable);
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "mib_oid_id",
                                 "plugin_fusinvsnmp_miboids_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_miboids_id",
                                 "plugin_fusinvsnmp_miboids_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "construct_device_id",
                                 "plugin_fusinvsnmp_constructdevices_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_constructdevices_id",
                                 "plugin_fusinvsnmp_constructdevices_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");         
         $migration->changeField($newTable,
                                 "oid_port_counter",
                                 "oid_port_counter",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "oid_port_dyn",
                                 "oid_port_dyn",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "itemtype",
                                 "itemtype",
                                 "varchar(100) COLLATE utf8_unicode_ci NOT NULL");
         $migration->changeField($newTable,
                                 "vlan",
                                 "vlan",
                                 "tinyint(1) NOT NULL DEFAULT '0'"); 
      $migration->migrationOneTable($newTable);
         $migration->dropField($newTable, "mapping_type");
      $migration->migrationOneTable($newTable);      
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "plugin_fusinvsnmp_miboids_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusinvsnmp_constructdevices_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");         
         $migration->addField($newTable,
                                 "oid_port_counter",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "oid_port_dyn",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "itemtype",
                                 "varchar(100) COLLATE utf8_unicode_ci NOT NULL");
         $migration->addField($newTable,
                                 "vlan",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            array("plugin_fusinvsnmp_miboids_id", "plugin_fusinvsnmp_constructdevices_id", "plugin_fusioninventory_mappings_id"),
                            "unicity",
                            "UNIQUE");
      $migration->migrationOneTable($newTable);
         
   
   /*
    * Table glpi_plugin_fusinvsnmp_networkportconnectionlogs
    */
      $newTable = "glpi_plugin_fusinvsnmp_networkportconnectionlogs";
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable,
                              "id",
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                              "date",
                              "date_mod",
                              "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
         $migration->changeField($newTable,
                              "date_mod",
                              "date_mod",
                              "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
         $migration->changeField($newTable,
                              "creation",
                              "creation",
                              "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                              "FK_port_source",
                              "networkports_id_source",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                              "networkports_id_source",
                              "networkports_id_source",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                              "FK_port_destination",
                              "networkports_id_destination",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                              "networkports_id_destination",
                              "networkports_id_destination",
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                              "plugin_fusioninventory_agentprocesses_id",
                              "plugin_fusioninventory_agentprocesses_id",
                              "int(11) NOT NULL DEFAULT '0'");      
         $migration->dropField($newTable, "process_number");
      $migration->migrationOneTable($newTable);      
         $migration->addField($newTable, 
                              "id", 
                              "int(11) NOT NULL AUTO_INCREMENT");      
         $migration->addField($newTable, 
                              "date_mod", 
                              "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");      
         $migration->addField($newTable, 
                              "creation", 
                              "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              "networkports_id_source", 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              "networkports_id_destination", 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              "plugin_fusioninventory_agentprocesses_id", 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            array("networkports_id_source", "networkports_id_destination", "plugin_fusioninventory_agentprocesses_id"),
                            "networkports_id_source");        
         $migration->addKey($newTable,
                            "date_mod");  
      $migration->migrationOneTable($newTable);
      
      
      
   /*
    * Table glpi_plugin_fusinvsnmp_modelmibs
    */
      $newTable = "glpi_plugin_fusinvsnmp_modelmibs";
      $migration->renameTable("glpi_plugin_tracker_mib_networking", 
                              $newTable);
      if (FieldExists($newTable, "FK_mib_label")) {
         $query = "UPDATE `".$newTable."`
            SET `FK_mib_label`='0' 
            WHERE `FK_mib_label` IS NULL";
         $DB->query($query);
      }
      if (FieldExists($newTable, "plugin_fusinvsnmp_miblabels_id")) {
         $query = "UPDATE `".$newTable."`
            SET `plugin_fusinvsnmp_miblabels_id`='0' 
            WHERE `plugin_fusinvsnmp_miblabels_id` IS NULL";
         $DB->query($query);
      }
      if (FieldExists($newTable, "plugin_fusinvsnmp_mibobjects_id")) {
         $query = "UPDATE `".$newTable."`
            SET `plugin_fusinvsnmp_mibobjects_id`='0' 
            WHERE `plugin_fusinvsnmp_mibobjects_id` IS NULL";
         $DB->query($query);
      }
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_models_id",
                                 "plugin_fusinvsnmp_models_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_miblabels_id",
                                 "plugin_fusinvsnmp_miblabels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_miboids_id",
                                 "plugin_fusinvsnmp_miboids_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_mibobjects_id",
                                 "plugin_fusinvsnmp_mibobjects_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "oid_port_counter",
                                 "oid_port_counter",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "oid_port_dyn",
                                 "oid_port_dyn",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "is_active",
                                 "is_active",
                                 "tinyint(1) NOT NULL DEFAULT '1'");      
      $migration->migrationOneTable($newTable);      
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "FK_model_infos",
                                 "plugin_fusinvsnmp_models_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "FK_mib_label",
                                 "plugin_fusinvsnmp_miblabels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "FK_mib_oid",
                                 "plugin_fusinvsnmp_miboids_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "FK_mib_object",
                                 "plugin_fusinvsnmp_mibobjects_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "oid_port_counter",
                                 "oid_port_counter",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "oid_port_dyn",
                                 "oid_port_dyn",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              "plugin_fusioninventory_mappings_id", 
                              "int(11) NOT NULL DEFAULT '0'");         
      $migration->migrationOneTable($newTable);
      
         // Update with mapping
         if (FieldExists($newTable, "mapping_type")) {
            $pFusinvsnmpModelMib = new PluginFusinvsnmpModelMib();
            $query = "SELECT * FROM `".$newTable."`
               GROUP BY `mapping_type`, `mapping_name`";
            $result=$DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $pFusioninventoryMapping = new PluginFusioninventoryMapping();
               $mapping = 0;
               $mapping_type = '';
               if ($data['mapping_type'] == '2') {
                  $mapping_type == 'NetworkEquipment';
               } else if ($data['mapping_type'] == '3') {
                  $mapping_type == 'Printer';
               }
               if ($mapping = $pFusioninventoryMapping->get($mapping_type, $data['mapping_name'])) {
                  $data['mapping_name'] = $mapping['id'];
                  $queryu = "UPDATE `".$newTable."`
                     SET `plugin_fusioninventory_mappings_id`='".$mapping['id']."',
                        `mapping_type`='".$mapping_type."'
                     WHERE `mapping_type`='".$data['mapping_type']."'
                        AND `mapping_name`='".$data['mapping_name']."'";
                  $DB->query($queryu);
               }
            }
         }
         $migration->dropField($newTable,
                               "mapping_type");
         $migration->dropField($newTable,
                               "mapping_name");
         $migration->dropField($newTable,
                               "name");
         $migration->dropField($newTable,
                               "itemtype");
         $migration->dropField($newTable,
                               "discovery_key");
         $migration->dropField($newTable,
                               "comment");
         $migration->changeField($newTable,
                                 "activation",
                                 "is_active",
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 "vlan",
                                 "vlan",
                                 "tinyint(1) NOT NULL DEFAULT '0'");

         $migration->dropKey($newTable, 
                             "FK_model_infos");
         $migration->dropKey($newTable, 
                             "FK_model_infos_2");
         $migration->dropKey($newTable, 
                             "FK_model_infos_3");
         $migration->dropKey($newTable, 
                             "FK_model_infos_4");
         $migration->dropKey($newTable, 
                             "oid_port_dyn");
         $migration->dropKey($newTable, 
                             "activation");      
      $migration->migrationOneTable($newTable);      
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "plugin_fusinvsnmp_models_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusinvsnmp_miblabels_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusinvsnmp_miboids_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusinvsnmp_mibobjects_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "oid_port_counter",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "oid_port_dyn",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "is_active",
                                 "tinyint(1) NOT NULL DEFAULT '1'");
         $migration->addField($newTable,
                                 "vlan",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            "plugin_fusinvsnmp_models_id");      
         $migration->addKey($newTable,
                            array("plugin_fusinvsnmp_models_id", "oid_port_dyn"),
                            "plugin_fusinvsnmp_models_id_2");      
         $migration->addKey($newTable,
                            array("plugin_fusinvsnmp_models_id", "oid_port_counter", "plugin_fusioninventory_mappings_id"),
                            "plugin_fusinvsnmp_models_id_3");
         $migration->addKey($newTable,
                            array("plugin_fusinvsnmp_models_id", "plugin_fusioninventory_mappings_id"),
                            "plugin_fusinvsnmp_models_id_4");
         $migration->addKey($newTable,
                            "oid_port_dyn");
         $migration->addKey($newTable,
                            "is_active"); 
         $migration->addKey($newTable,
                            "plugin_fusioninventory_mappings_id");
      $migration->migrationOneTable($newTable);


   /*
    * glpi_plugin_fusinvsnmp_models
    */
      $newTable = "glpi_plugin_fusinvsnmp_models";
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->changeField($newTable, 
                                 "id", 
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 "name", 
                                 "name", 
                                 "varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->changeField($newTable, 
                                 "device_type", 
                                 "itemtype", 
                                 "varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->changeField($newTable, 
                                 "itemtype", 
                                 "itemtype", 
                                 "varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->changeField($newTable, 
                                 "discovery_key", 
                                 "discovery_key", 
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable, 
                                 "comments", 
                                 "comment", 
                                 "text COLLATE utf8_unicode_ci"); 
         $migration->changeField($newTable, 
                                 "comment", 
                                 "comment", 
                                 "text COLLATE utf8_unicode_ci"); 
      $migration->migrationOneTable($newTable);
         $migration->dropField($newTable, "deleted");
         $migration->dropField($newTable, "FK_entities");
         $migration->dropField($newTable, "activation");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              "id", 
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable, 
                              "name", 
                              "varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->addField($newTable, 
                              "itemtype", 
                              "varchar(100) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''");
         $migration->addField($newTable, 
                              "discovery_key", 
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable, 
                              "comment", 
                              "text COLLATE utf8_unicode_ci");
         $migration->addKey($newTable,
                            "name");      
         $migration->addKey($newTable,
                            "itemtype");   
      $migration->migrationOneTable($newTable);

      
      
   /*
    * glpi_plugin_fusinvsnmp_modeldevices
    */
      $newTable = "glpi_plugin_fusinvsnmp_modeldevices";
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable, 
                                 "id", 
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable, 
                                 "plugin_fusinvsnmp_models_id", 
                                 "plugin_fusinvsnmp_models_id", 
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable, 
                                 "sysdescr", 
                                 "sysdescr", 
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable, 
                              "id", 
                              "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable, 
                              "plugin_fusinvsnmp_models_id", 
                              "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              "sysdescr", 
                              "text COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addKey($newTable,
                            "plugin_fusinvsnmp_models_id");      
      $migration->migrationOneTable($newTable);

      
      
   /*
    * glpi_plugin_fusinvsnmp_networkequipments
    */
      $newTable = "glpi_plugin_fusinvsnmp_networkequipments";
      $migration->renameTable("glpi_plugin_tracker_networking", 
                              $newTable);
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "networkequipments_id",
                                 "networkequipments_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "sysdescr",
                                 "sysdescr",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_models_id",
                                 "plugin_fusinvsnmp_models_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_configsecurities_id",
                                 "plugin_fusinvsnmp_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");      
         $migration->changeField($newTable,
                                 "uptime",
                                 "uptime",
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "cpu",
                                 "cpu",
                                 "int(3) NOT NULL DEFAULT '0' COMMENT '%'");
         $migration->changeField($newTable,
                                 "memory",
                                 "memory",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "last_fusioninventory_update",
                                 "last_fusioninventory_update",
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable,
                                 "last_PID_update",
                                 "last_PID_update",
                                 "int(11) NOT NULL DEFAULT '0'");      
      $migration->migrationOneTable($newTable);            
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "FK_networking",
                                 "networkequipments_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "FK_model_infos",
                                 "plugin_fusinvsnmp_models_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "FK_snmp_connection",
                                 "plugin_fusinvsnmp_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");      
         $migration->changeField($newTable,
                                 "last_tracker_update",
                                 "last_fusioninventory_update",
                                 "datetime DEFAULT NULL");
         $migration->dropKey($newTable, 
                             "FK_networking");
         $migration->dropKey($newTable, 
                             "FK_model_infos");         
      $migration->migrationOneTable($newTable);      
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "networkequipments_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "sysdescr",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "plugin_fusinvsnmp_models_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusinvsnmp_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");      
         $migration->addField($newTable,
                                 "uptime",
                                 "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "cpu",
                                 "int(3) NOT NULL DEFAULT '0' COMMENT '%'");
         $migration->addField($newTable,
                                 "memory",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "last_fusioninventory_update",
                                 "datetime DEFAULT NULL");
         $migration->addField($newTable,
                                 "last_PID_update",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            "networkequipments_id");
         $migration->addKey($newTable,
                            array("plugin_fusinvsnmp_models_id", "plugin_fusinvsnmp_configsecurities_id"),
                            "plugin_fusinvsnmp_models_id");
      $migration->migrationOneTable($newTable);
      
      
   /*
    * glpi_plugin_fusinvsnmp_networkequipmentips
    */
      $newTable = "glpi_plugin_fusinvsnmp_networkequipmentips";
      $migration->renameTable("glpi_plugin_tracker_networking_ifaddr", 
                              $newTable);
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "networkequipments_id",
                                 "networkequipments_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ip",
                                 "ip",
                                 "varchar(255) DEFAULT NULL");      
      $migration->migrationOneTable($newTable);      
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "FK_networking",
                                 "networkequipments_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ifaddr",
                                 "ip",
                                 "varchar(255) DEFAULT NULL");
         $migration->dropKey($newTable, 
                             "ifaddr");         
      $migration->migrationOneTable($newTable);         
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "networkequipments_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "ip",
                                 "varchar(255) DEFAULT NULL");
         $migration->addKey($newTable,
                            "ip");
         $migration->addKey($newTable,
                            "networkequipments_id");
      $migration->migrationOneTable($newTable);
  
      
   /*
    * glpi_plugin_fusinvsnmp_networkports
    */
      $newTable = "glpi_plugin_fusinvsnmp_networkports";
      $migration->renameTable("glpi_plugin_tracker_networking_ports", 
                              $newTable);
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "networkports_id",
                                 "networkports_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ifmtu",
                                 "ifmtu",
                                 "int(8) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ifspeed",
                                 "ifspeed",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ifinternalstatus",
                                 "ifinternalstatus",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "ifconnectionstatus",
                                 "ifconnectionstatus",
                                 "int(8) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "iflastchange",
                                 "iflastchange",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "ifinoctets",
                                 "ifinoctets",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ifinerrors",
                                 "ifinerrors",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ifoutoctets",
                                 "ifoutoctets",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ifouterrors",
                                 "ifouterrors",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "ifstatus",
                                 "ifstatus",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "mac",
                                 "mac",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "ifdescr",
                                 "ifdescr",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "portduplex",
                                 "portduplex",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "trunk",
                                 "trunk",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "lastup",
                                 "lastup",
                                 "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");      
      $migration->migrationOneTable($newTable);      
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "FK_networking_ports",
                                 "networkports_id",
                                 "int(11) NOT NULL DEFAULT '0'");      
         $migration->changeField($newTable,
                                 "ifmac",
                                 "mac",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->dropKey($newTable, 
                             "FK_networking_ports");         
      $migration->migrationOneTable($newTable);         
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "networkports_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "ifmtu",
                                 "int(8) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "ifspeed",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "ifinternalstatus",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "ifconnectionstatus",
                                 "int(8) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "iflastchange",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "ifinoctets",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "ifinerrors",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "ifoutoctets",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "ifouterrors",
                                 "bigint(50) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "ifstatus",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "mac",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "ifdescr",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "portduplex",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "trunk",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "lastup",
                                 "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");         
         $migration->addKey($newTable,
                            "networkports_id");
      $migration->migrationOneTable($newTable);
      
      
   /*
    * Table glpi_plugin_fusinvsnmp_printerlogs
    */
      $newTable = "glpi_plugin_fusinvsnmp_printerlogs";
      $migration->renameTable("glpi_plugin_tracker_printers_history", 
                              $newTable);
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "printers_id",
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "date",
                                 "date",
                                 "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
         $migration->changeField($newTable,
                                 "pages_total",
                                 "pages_total",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_n_b",
                                 "pages_n_b",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_color",
                                 "pages_color",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_recto_verso",
                                 "pages_recto_verso",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "scanned",
                                 "scanned",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_total_print",
                                 "pages_total_print",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_n_b_print",
                                 "pages_n_b_print",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_color_print",
                                 "pages_color_print",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_total_copy",
                                 "pages_total_copy",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_n_b_copy",
                                 "pages_n_b_copy",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_color_copy",
                                 "pages_color_copy",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "pages_total_fax",
                                 "pages_total_fax",
                                 "int(11) NOT NULL DEFAULT '0'");      
      $migration->migrationOneTable($newTable);             
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "FK_printers",
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");         
      $migration->migrationOneTable($newTable);       
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "date",
                                 "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
         $migration->addField($newTable,
                                 "pages_total",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_n_b",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_color",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_recto_verso",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "scanned",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_total_print",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_n_b_print",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_color_print",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_total_copy",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_n_b_copy",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_color_copy",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "pages_total_fax",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            array("printers_id", "date"),
                            "printers_id");
      $migration->migrationOneTable($newTable);
      
      
   /*
    * Table glpi_plugin_fusinvsnmp_printers
    */
      $newTable = "glpi_plugin_fusinvsnmp_printers";
      $migration->renameTable("glpi_plugin_tracker_printers", 
                              $newTable);
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");      
         $migration->changeField($newTable,
                                 "printers_id",
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "sysdescr",
                                 "sysdescr",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_models_id",
                                 "plugin_fusinvsnmp_models_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_configsecurities_id",
                                 "plugin_fusinvsnmp_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "frequence_days",
                                 "frequence_days",
                                 "int(5) NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 "last_fusioninventory_update",
                                 "last_fusioninventory_update",
                                 "datetime DEFAULT NULL");      
      $migration->migrationOneTable($newTable);            
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "FK_printers",
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "FK_model_infos",
                                 "plugin_fusinvsnmp_models_id",
                                 "int(11) NOT NULL DEFAULT '0'"); 
         $migration->changeField($newTable,
                                 "FK_snmp_connection",
                                 "plugin_fusinvsnmp_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "last_tracker_update",
                                 "last_fusioninventory_update",
                                 "datetime DEFAULT NULL");
         $migration->dropKey($newTable, 
                             "FK_printers");
         $migration->dropKey($newTable, 
                             "FK_snmp_connection");         
      $migration->migrationOneTable($newTable);      
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");      
         $migration->addField($newTable,
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "sysdescr",
                                 "text COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "plugin_fusinvsnmp_models_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusinvsnmp_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "frequence_days",
                                 "int(5) NOT NULL DEFAULT '1'");
         $migration->addField($newTable,
                                 "last_fusioninventory_update",
                                 "datetime DEFAULT NULL");
         $migration->addKey($newTable,
                            "plugin_fusinvsnmp_configsecurities_id");
         $migration->addKey($newTable,
                            "printers_id");
         $migration->addKey($newTable,
                            "plugin_fusinvsnmp_models_id");
      $migration->migrationOneTable($newTable);
   
      
   /*
    *  glpi_plugin_fusinvsnmp_printercartridges
    */
      $newTable = "glpi_plugin_fusinvsnmp_printercartridges";
      $migration->renameTable("glpi_plugin_tracker_printers_cartridges", 
                              $newTable);
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` bigint(100) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "bigint(100) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "printers_id",
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "cartridges_id",
                                 "cartridges_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "state",
                                 "state",
                                 "int(3) NOT NULL DEFAULT '100'");
      $migration->migrationOneTable($newTable);      
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "bigint(100) NOT NULL AUTO_INCREMENT");      
         $migration->changeField($newTable,
                                 "FK_printers",
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");      
         $migration->changeField($newTable,
                                 "FK_cartridges",
                                 "cartridges_id",
                                 "int(11) NOT NULL DEFAULT '0'");      
         $migration->addField($newTable, 
                              "plugin_fusioninventory_mappings_id", 
                              "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
      
         // Update with mapping
         if (FieldExists($newTable, "object_name")) {
            $pcartridge = new PluginFusinvsnmpCommonDBTM($newTable);
            $query = "SELECT * FROM `".$newTable."`
               GROUP BY `object_name`";
            $result=$DB->query($query);
            while ($data=$DB->fetch_array($result)) {
               $pFusioninventoryMapping = new PluginFusioninventoryMapping();
               $mapping = 0;
               if ($mapping = $pFusioninventoryMapping->get("Printer", $data['object_name'])) {
                  $DB->query("UPDATE `".$newTable."`
                     SET `plugin_fusioninventory_mappings_id`='".$mapping['id']."'
                        WHERE `object_name`='".$data['object_name']."'");
               }
            }
         }
         $migration->dropField($newTable,
                               "object_name");      
      $migration->migrationOneTable($newTable);      
         $migration->addField($newTable,
                                 "id",
                                 "bigint(100) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "printers_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "cartridges_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "state",
                                 "int(3) NOT NULL DEFAULT '100'");
         $migration->addKey($newTable,
                            "printers_id");
         $migration->addKey($newTable,
                            "plugin_fusioninventory_mappings_id");
         $migration->addKey($newTable,
                            "cartridges_id");
      $migration->migrationOneTable($newTable);
      
      
   /*
    * Table glpi_plugin_fusinvsnmp_configsecurities
    */
      // TODO get info to create SNMP authentification with old values of Tracker plugin
      $newTable = "glpi_plugin_fusinvsnmp_configsecurities";
      $migration->renameTable("glpi_plugin_tracker_snmp_connection", 
                              $newTable);
      if (!TableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
          $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL"); 
         $migration->changeField($newTable,
                                 "snmpversion",
                                 "snmpversion",
                                 "varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 "community",
                                 "community",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "username",
                                 "username",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "authentication",
                                 "authentication",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "auth_passphrase",
                                 "auth_passphrase",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "encryption",
                                 "encryption",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "priv_passphrase",
                                 "priv_passphrase",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->changeField($newTable,
                                 "is_deleted",
                                 "is_deleted",
                                 "tinyint(1) NOT NULL DEFAULT '0'");      
      $migration->migrationOneTable($newTable);      
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "FK_snmp_version",
                                 "snmpversion",
                                 "varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1'");
         $migration->changeField($newTable,
                                 "sec_name",
                                 "username",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->dropField($newTable,
                               "sec_level");
         $migration->dropField($newTable,
                               "auth_protocol");
         $migration->dropField($newTable,
                               "priv_protocol");
         $migration->dropField($newTable,
                               "deleted");         
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT"); 
         $migration->addField($newTable,
                                 "name",
                                 "varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL"); 
         $migration->addField($newTable,
                                 "snmpversion",
                                 "varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1'");
         $migration->addField($newTable,
                                 "community",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "username",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "authentication",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "auth_passphrase",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "encryption",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "priv_passphrase",
                                 "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
         $migration->addField($newTable,
                                 "is_deleted",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            "snmpversion");
         $migration->addKey($newTable,
                            "is_deleted");
      $migration->migrationOneTable($newTable);
         
         
      
      /*
       * Drop Tables not used
       */
      
      if (TableExists("glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol")) {
         $DB->query("DROP TABLE glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol");
      }
      
      if (TableExists("glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol")) {
         $DB->query("DROP TABLE glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol");
      }

      if (TableExists("glpi_dropdown_plugin_tracker_snmp_auth_sec_level")) {
         $DB->query("DROP TABLE glpi_dropdown_plugin_tracker_snmp_auth_sec_level");
      }

      if (TableExists("glpi_dropdown_plugin_tracker_snmp_version")) {
         $DB->query("DROP TABLE glpi_dropdown_plugin_tracker_snmp_version");
      }
      if (TableExists("glpi_plugin_fusioninventory_config_snmp_networking")) {
         $DB->query("DROP TABLE glpi_plugin_fusioninventory_config_snmp_networking");
      }
      if (TableExists("glpi_plugin_fusioninventory_config_snmp_history")) {
         $DB->query("DROP TABLE glpi_plugin_fusioninventory_config_snmp_history");
      }
      
       
   /*
    * Table glpi_plugin_fusinvsnmp_networkportlogs
    */
      $newTable = "glpi_plugin_fusinvsnmp_networkportlogs";
         if (TableExists("glpi_plugin_tracker_snmp_history")) {
            // **** Update history
            update213to220_ConvertField($migration);
            
            // **** Migration network history connections
            $query = "SELECT count(ID) FROM `glpi_plugin_tracker_snmp_history`
                              WHERE `Field`='0'";
            $result = $DB->query($query);
            $datas = $DB->fetch_assoc($result);
            $nb = $datas['count(ID)'];

            echo "Move Connections history to another table...";
            
            for ($i=0; $i < $nb; $i = $i + 500) {
               $migration->displayMessage("$i / $nb");
               $sql_connection = "SELECT * FROM `glpi_plugin_tracker_snmp_history`
                                 WHERE `Field`='0'
                                 ORDER BY `FK_process` DESC, `date_mod` DESC
                                 LIMIT 500";
               $result_connection = $DB->query($sql_connection);
               while ($thread_connection = $DB->fetch_array($result_connection)) {
                  $input = array();
                  $input['process_number'] = $thread_connection['FK_process'];
                  $input['date'] = $thread_connection['date_mod'];
                  if (($thread_connection["old_device_ID"] != "0")
                          OR ($thread_connection["new_device_ID"] != "0")) {

                     if ($thread_connection["old_device_ID"] != "0") {
                        // disconnection
                        $input['creation'] = '0';
                     } else if ($thread_connection["new_device_ID"] != "0") {
                        // connection
                        $input['creation'] = '1';
                     }
                     $input['FK_port_source'] = $thread_connection["FK_ports"];
                     $dataPort = array();
                     if ($thread_connection["old_device_ID"] != "0") {
                        $queryPort = "SELECT *
                                      FROM `glpi_networkports`
                                      WHERE `mac`='".$thread_connection['old_value']."'
                                      LIMIT 1";
                        $resultPort = $DB->query($queryPort);
                        $dataPort = $DB->fetch_assoc($resultPort);
                     } else if ($thread_connection["new_device_ID"] != "0") {
                        $queryPort = "SELECT *
                                      FROM `glpi_networkports`
                                      WHERE `mac`='".$thread_connection['new_value']."'
                                      LIMIT 1";
                        $resultPort = $DB->query($queryPort);
                        $dataPort = $DB->fetch_assoc($resultPort);
                     }
                     if (isset($dataPort['id'])) {
                        $input['FK_port_destination'] = $dataPort['id'];
                     } else {
                        $input['FK_port_destination'] = 0;
                     }

                     $query_ins = "INSERT INTO `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
                        (`date_mod`, `creation`, `networkports_id_source`, `networkports_id_destination`)
                        VALUES ('".$input['date']."',
                                '".$input['creation']."',
                                '".$input['FK_port_source']."',
                                '".$input['FK_port_destination']."')";
                     $DB->query($query_ins);
                  }
               }
            }
            $query_del = "DELETE FROM `glpi_plugin_tracker_snmp_history`
               WHERE `Field`='0'
               AND (`old_device_ID`!='0' OR `new_device_ID`!='0')";
            $DB->query($query_del);
            $migration->displayMessage("$nb / $nb");
         }
      
      $migration->renameTable("glpi_plugin_tracker_snmp_history", 
                              $newTable);
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "networkports_id",
                                 "networkports_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "date_mod",
                                 "date_mod",
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable,
                                 "value_old",
                                 "value_old",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "value_new",
                                 "value_new",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_agentprocesses_id",
                                 "plugin_fusioninventory_agentprocesses_id",
                                 "int(11) NOT NULL DEFAULT '0'");      
      $migration->migrationOneTable($newTable);
         $migration->changeField($newTable,
                                 "ID",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");      
         $migration->changeField($newTable,
                                 "FK_ports",
                                 "networkports_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable, 
                              "plugin_fusioninventory_mappings_id",
                              "int(11) NOT NULL DEFAULT '0'");         
      $migration->migrationOneTable($newTable);
      
         // Update with mapping
         if (FieldExists($newTable, "Field")) {
            $pFusinvsnmpNetworkPortLog = new PluginFusinvsnmpNetworkPortLog();
            $pFusioninventoryMapping = new PluginFusioninventoryMapping();
            $query = "SELECT * FROM `".$newTable."`
               GROUP BY `Field`";
            $result=$DB->query($query);
            while ($data=$DB->fetch_array($result)) {               
               $mapping = 0;
               if ($mapping = $pFusioninventoryMapping->get("NetworkEquipment", $data['Field'])) {
                  $DB->query("UPDATE `".$newTable."` 
                     SET `plugin_fusioninventory_mappings_id`='".$mapping['id']."'
                     WHERE `Field`='".$data['Field']."'
                        AND `plugin_fusioninventory_mappings_id`!='".$mapping['id']."'");
               }
            }
         }
         $migration->dropField($newTable,
                            "Field");
         $migration->changeField($newTable,
                                 "old_value",
                                 "value_old",
                                 "varchar(255) DEFAULT NULL");
         $migration->dropField($newTable,
                               "old_device_type");
         $migration->dropField($newTable,
                               "old_device_ID");
         $migration->changeField($newTable,
                                 "new_value",
                                 "value_new",
                                 "varchar(255) DEFAULT NULL");
         $migration->dropField($newTable,
                               "new_device_type");
         $migration->dropField($newTable,
                               "new_device_ID");
         $migration->dropField($newTable, "FK_process");
         $migration->dropKey($newTable, "FK_process");
         $migration->dropKey($newTable, 
                             "FK_ports");         
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "networkports_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_mappings_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "date_mod",
                                 "datetime DEFAULT NULL");
         $migration->addField($newTable,
                                 "value_old",
                                 "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                                 "value_new",
                                 "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_agentprocesses_id",
                                 "int(11) NOT NULL DEFAULT '0'");         
         $migration->addKey($newTable,
                            array("networkports_id", "date_mod"),
                            "networkports_id");
         $migration->addKey($newTable,
                            "plugin_fusioninventory_mappings_id");
         $migration->addKey($newTable,
                            "plugin_fusioninventory_agentprocesses_id");
         $migration->addKey($newTable,
                            "date_mod");
      $migration->migrationOneTable($newTable);

      
      
   /*
    * Table glpi_plugin_fusinvsnmp_networkporttypes
    */
      $newTable = "glpi_plugin_fusinvsnmp_networkporttypes";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }      
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "name",
                                 "name",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "number",
                                 "number",
                                 "int(4) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "othername",
                                 "othername",
                                 "varchar(255) DEFAULT NULL");
         $migration->changeField($newTable,
                                 "import",
                                 "import",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                              "name",
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                              "number",
                              "int(4) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                              "othername",
                              "varchar(255) DEFAULT NULL");
         $migration->addField($newTable,
                              "import",
                              "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
      
      
      
   /*
    * Table glpi_plugin_fusinvsnmp_unknowndevices
    */
      $newTable = "glpi_plugin_fusinvsnmp_unknowndevices";
      if (!TableExists($newTable)) {
         $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
         $DB->query($query);
      }      
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_unknowndevices_id",
                                 "plugin_fusioninventory_unknowndevices_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "sysdescr",
                                 "sysdescr",
                                 "text DEFAULT NULL");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_models_id",
                                 "plugin_fusinvsnmp_models_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusinvsnmp_configsecurities_id",
                                 "plugin_fusinvsnmp_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");  
      $migration->migrationOneTable($newTable);
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_unknowndevices_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "sysdescr",
                                 "text DEFAULT NULL");
         $migration->addField($newTable,
                                 "plugin_fusinvsnmp_models_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusinvsnmp_configsecurities_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable,
                            "plugin_fusioninventory_unknowndevices_id");
      $migration->migrationOneTable($newTable);
         
      
      
   /*
    * Table glpi_plugin_fusinvsnmp_agentconfigs
    */
      $newTable = "glpi_plugin_fusinvsnmp_agentconfigs";
      if (!TableExists($newTable)) {
         $DB->query("CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1");
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_agents_id",
                                 "plugin_fusioninventory_agents_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "version_netdiscovery",
                                 "version_netdiscovery",
                                 "text DEFAULT NULL COMMENT 'array(xmltag=>value)'");
         $migration->changeField($newTable,
                                 "version_snmpquery",
                                 "version_snmpquery",
                                 "text DEFAULT NULL COMMENT 'array(xmltag=>value)'");
         $migration->changeField($newTable,
                                 "threads_netdiscovery",
                                 "threads_netdiscovery",
                                 "int(4) NOT NULL DEFAULT '1' COMMENT 'array(xmltag=>value)'");
         $migration->changeField($newTable,
                                 "threads_snmpquery",
                                 "threads_snmpquery",
                                 "int(4) NOT NULL DEFAULT '1' COMMENT 'array(xmltag=>value)'");
         $migration->changeField($newTable,
                                 "senddico",
                                 "senddico",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);   
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_agents_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "version_netdiscovery",
                                 "text DEFAULT NULL COMMENT 'array(xmltag=>value)'");
         $migration->addField($newTable,
                                 "version_snmpquery",
                                 "text DEFAULT NULL COMMENT 'array(xmltag=>value)'");
         $migration->addField($newTable,
                                 "threads_netdiscovery",
                                 "int(4) NOT NULL DEFAULT '1' COMMENT 'array(xmltag=>value)'");
         $migration->addField($newTable,
                                 "threads_snmpquery",
                                 "int(4) NOT NULL DEFAULT '1' COMMENT 'array(xmltag=>value)'");
         $migration->addField($newTable,
                                 "senddico",
                                 "tinyint(1) NOT NULL DEFAULT '0'");
         $migration->addKey($newTable, 
                            "plugin_fusioninventory_agents_id");
      $migration->migrationOneTable($newTable);
   
      
   /*
    *  glpi_plugin_fusinvsnmp_statediscoveries
    */
      $newTable = "glpi_plugin_fusinvsnmp_statediscoveries";
      if (!TableExists($newTable)) {
         $DB->query("CREATE TABLE `".$newTable."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1");
      }
         $migration->changeField($newTable,
                                 "id",
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_taskjob_id",
                                 "plugin_fusioninventory_taskjob_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "plugin_fusioninventory_agents_id",
                                 "plugin_fusioninventory_agents_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "start_time",
                                 "start_time",
                                 "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
         $migration->changeField($newTable,
                                 "end_time",
                                 "end_time",
                                 "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
         $migration->changeField($newTable,
                                 "date_mod",
                                 "date_mod",
                                 "datetime DEFAULT NULL");
         $migration->changeField($newTable,
                                 "threads",
                                 "threads",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "nb_ip",
                                 "nb_ip",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "nb_found",
                                 "nb_found",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "nb_error",
                                 "nb_error",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "nb_exists",
                                 "nb_exists",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->changeField($newTable,
                                 "nb_import",
                                 "nb_import",
                                 "int(11) NOT NULL DEFAULT '0'");         
      $migration->migrationOneTable($newTable); 
         $migration->addField($newTable,
                                 "id",
                                 "int(11) NOT NULL AUTO_INCREMENT");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_taskjob_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "plugin_fusioninventory_agents_id",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "start_time",
                                 "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
         $migration->addField($newTable,
                                 "end_time",
                                 "datetime NOT NULL DEFAULT '0000-00-00 00:00:00'");
         $migration->addField($newTable,
                                 "date_mod",
                                 "datetime DEFAULT NULL");
         $migration->addField($newTable,
                                 "threads",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "nb_ip",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "nb_found",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "nb_error",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "nb_exists",
                                 "int(11) NOT NULL DEFAULT '0'");
         $migration->addField($newTable,
                                 "nb_import",
                                 "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
      
      
   /*
    * Table glpi_plugin_tracker_tmp_connections
    */
      $newTable = "glpi_plugin_tracker_tmp_connections";
      if (TableExists($newTable)) {
         if (countElementsInTable($newTable) == '0') {
            $DB->query("DROP TABLE `".$newTable."`");
         }
      }
      
   /*
    * Table glpi_plugin_tracker_tmp_netports
    */
      $newTable = "glpi_plugin_tracker_tmp_netports";
      if (TableExists($newTable)) {
         if (countElementsInTable($newTable) == '0') {
            $DB->query("DROP TABLE `".$newTable."`");
         }
      }
      
   /*
    * Table glpi_plugin_tracker_walks
    */
      $newTable = "glpi_plugin_tracker_walks";
      if (TableExists($newTable)) {
         if (countElementsInTable($newTable) == '0') {
            $DB->query("DROP TABLE `".$newTable."`");
         }
      }
      
      
   $migration->executeMigration();

   /*
    * Drop table not used
    */
   if (TableExists("glpi_plugin_tracker_computers")) {
      $DB->query("DROP TABLE glpi_plugin_tracker_computers");
   }  
   if (TableExists("glpi_plugin_tracker_config")) {
      $DB->query("DROP TABLE glpi_plugin_tracker_config");
   }
   if (TableExists("glpi_plugin_tracker_config_discovery")) {
      $DB->query("DROP TABLE glpi_plugin_tracker_config_discovery");
   }
   if (TableExists("glpi_dropdown_plugin_fusioninventory_mib_label")) {
      $DB->query("DROP TABLE glpi_dropdown_plugin_fusioninventory_mib_label");
   }
   if (TableExists("glpi_dropdown_plugin_fusioninventory_mib_object")) {
      $DB->query("DROP TABLE glpi_dropdown_plugin_fusioninventory_mib_object");
   }
   if (TableExists("glpi_dropdown_plugin_fusioninventory_mib_oid")) {
      $DB->query("DROP TABLE glpi_dropdown_plugin_fusioninventory_mib_oid");
   }
   if (TableExists("glpi_dropdown_plugin_fusioninventory_snmp_auth_auth_protocol")) {
      $DB->query("DROP TABLE glpi_dropdown_plugin_fusioninventory_snmp_auth_auth_protocol");
   }
   if (TableExists("glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol")) {
      $DB->query("DROP TABLE glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol");
   }
   if (TableExists("glpi_dropdown_plugin_fusioninventory_snmp_version")) {
      $DB->query("DROP TABLE glpi_dropdown_plugin_fusioninventory_snmp_version");
   }
   if (TableExists("glpi_plugin_fusinvsnmp_temp_profiles")) {
      $DB->query("DROP TABLE glpi_plugin_fusinvsnmp_temp_profiles");
   }
   if (TableExists("glpi_plugin_fusinvsnmp_tmp_agents")) {
      $DB->query("DROP TABLE glpi_plugin_fusinvsnmp_tmp_agents");
   }
   if (TableExists("glpi_plugin_fusinvsnmp_tmp_configs")) {
      $DB->query("DROP TABLE glpi_plugin_fusinvsnmp_tmp_configs");
   }
   if (TableExists("glpi_plugin_fusinvsnmp_tmp_tasks")) {
      $DB->query("DROP TABLE glpi_plugin_fusinvsnmp_tmp_tasks");
   }
   
   
   /*
    *  Clean old ports deleted but have some informations in SNMP tables
    */
   echo "Clean ports purged\n";
   $query_select = "SELECT `glpi_plugin_fusinvsnmp_networkports`.`id`
                    FROM `glpi_plugin_fusinvsnmp_networkports`
                          LEFT JOIN `glpi_networkports`
                                    ON `glpi_networkports`.`id` = `networkports_id`
                          LEFT JOIN `glpi_networkequipments` ON `glpi_networkequipments`.`id` = `glpi_networkports`.`items_id`
                    WHERE `glpi_networkequipments`.`id` IS NULL";
   $result=$DB->query($query_select);
   while ($data=$DB->fetch_array($result)) {
      $query_del = "DELETE FROM `glpi_plugin_fusinvsnmp_networkports`
         WHERE `id`='".$data["id"]."'";
      $DB->query($query_del);
   }
   
   /*
    *  Clean for multiple IP of a switch when this switch is purged but not these IPs
    */
   echo "Clean for multiple IP of a switch when this switch is purged but not these IPs\n";
   $query_select = "SELECT `glpi_plugin_fusinvsnmp_networkequipmentips`.`id`
                    FROM `glpi_plugin_fusinvsnmp_networkequipmentips`
                          LEFT JOIN `glpi_networkequipments` ON `glpi_networkequipments`.`id` = `networkequipments_id`
                    WHERE `glpi_networkequipments`.`id` IS NULL";
   $result=$DB->query($query_select);
   while ($data=$DB->fetch_array($result)) {
      $query_del = "DELETE FROM `glpi_plugin_fusinvsnmp_networkequipmentips`
         WHERE `id`='".$data["id"]."'";
      $DB->query($query_del);
   }

   
   /*
    * Clean for switch more informations again in DB when switch is purged
    */
   echo "Clean for switch more informations again in DB when switch is purged\n";
   $query_select = "SELECT `glpi_plugin_fusinvsnmp_networkequipments`.`id`
                    FROM `glpi_plugin_fusinvsnmp_networkequipments`
                          LEFT JOIN `glpi_networkequipments` ON `glpi_networkequipments`.`id` = `networkequipments_id`
                    WHERE `glpi_networkequipments`.`id` IS NULL";
   $result=$DB->query($query_select);
   while ($data=$DB->fetch_array($result)) {
       $query_del = "DELETE FROM `glpi_plugin_fusinvsnmp_networkequipments`
         WHERE `id`='".$data["id"]."'";
      $DB->query($query_del);
   }
   
   
   /*
    * Clean for printer more informations again in DB when printer is purged
    */
   "Clean for printer more informations again in DB when printer is purged\n";
   $query_select = "SELECT `glpi_plugin_fusinvsnmp_printers`.`id`
                    FROM `glpi_plugin_fusinvsnmp_printers`
                          LEFT JOIN `glpi_printers` ON `glpi_printers`.`id` = `printers_id`
                    WHERE `glpi_printers`.`id` IS NULL";
   $result=$DB->query($query_select);
   while ($data=$DB->fetch_array($result)) {
      $query_del = "DELETE FROM `glpi_plugin_fusinvsnmp_printers`
         WHERE `id`='".$data["id"]."'";
      $DB->query($query_del);
   }
   
   
   /*
    *  Clean printer cartridge not deleted with the printer associated
    */
   echo "Clean printer cartridge not deleted with the printer associated\n";
   $query_select = "SELECT `glpi_plugin_fusinvsnmp_printercartridges`.`id`
                    FROM `glpi_plugin_fusinvsnmp_printercartridges`
                          LEFT JOIN `glpi_printers` ON `glpi_printers`.`id` = `printers_id`
                    WHERE `glpi_printers`.`id` IS NULL";
   $result=$DB->query($query_select);
   while ($data=$DB->fetch_array($result)) {
      $query_del = "DELETE FROM `glpi_plugin_fusinvsnmp_printercartridges`
         WHERE `id`='".$data["id"]."'";
      $DB->query($query_del);
   }
   

   /*
    *  Clean printer history not deleted with printer associated
    */
   echo "Clean printer history not deleted with printer associated\n";
   $query_select = "SELECT `glpi_plugin_fusinvsnmp_printerlogs`.`id`
                    FROM `glpi_plugin_fusinvsnmp_printerlogs`
                          LEFT JOIN `glpi_printers` ON `glpi_printers`.`id` = `printers_id`
                    WHERE `glpi_printers`.`id` IS NULL";
   $result=$DB->query($query_select);
   while ($data=$DB->fetch_array($result)) {
      $query_del = "DELETE FROM `glpi_plugin_fusinvsnmp_printerlogs`
         WHERE `id`='".$data["id"]."'";
      $DB->query($query_del);
   }
   
   
   /*
    * Add IP of switch in table glpi_plugin_fusioninventory_networking_ifaddr if not present
    */
   echo "Add IP of switch in table glpi_plugin_fusinvsnmp_networkequipmentips if not present\n";
   $query = "SELECT * FROM glpi_networkequipments";
   if ($result=$DB->query($query)) {
      while ($data=$DB->fetch_array($result)) {
         $query_ifaddr = "SELECT * FROM `glpi_plugin_fusinvsnmp_networkequipmentips`
            WHERE `ip`='".$data['ip']."'
               AND `networkequipments_id`='".$data['ip']."'";
         $result_ifaddr = $DB->query($query_ifaddr);
         if ($DB->numrows($result_ifaddr) == "0") {
            $query_add = "INSERT INTO `glpi_plugin_fusinvsnmp_networkequipmentips`
               (`networkequipments_id`, `ip`) VALUES ('".$data['id']."', '".$data['ip']."')";
            $DB->query($query_add);
         }
      }
   }
   
   
   /*
    *  Convert displaypreferences
    */
   changeDisplayPreference("5151", "PluginFusinvsnmpModel");
   changeDisplayPreference("5152", "PluginFusinvsnmpConfigSecurity");
   changeDisplayPreference("5156", "PluginFusinvsnmpPrinterCartridge");
   changeDisplayPreference("5157", "PluginFusinvsnmpNetworkEquipment");
   changeDisplayPreference("5159", "PluginFusinvsnmpIPRange");
   changeDisplayPreference("5162", "PluginFusinvsnmpNetworkPortLog");
   changeDisplayPreference("5167", "PluginFusinvsnmpConstructDevice");
   changeDisplayPreference("5168", "PluginFusinvsnmpPrinterLog");
   
   /*
    * Modify displaypreference for PluginFusinvsnmpPrinterLogReport
    */
      if (!class_exists('PluginFusinvsnmpPrinterLogReport')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusinvsnmp/inc/printerlogreport.class.php");
      }
      $pfPrinterLogReport = new PluginFusinvsnmpPrinterLogReport();
      $a_searchoptions = $pfPrinterLogReport->getSearchOptions();
      $query = "SELECT * FROM `glpi_displaypreferences` 
      WHERE `itemtype` = 'PluginFusinvsnmpPrinterLogReport'
         AND `users_id`='0'";
      $result=$DB->query($query);
      if ($DB->numrows($result) == '0') {
         $query = "INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) 
                     VALUES (NULL,'PluginFusinvsnmpPrinterLogReport', '2', '1', '0'),
             (NULL,'PluginFusinvsnmpPrinterLogReport', '18', '2', '0'),
             (NULL,'PluginFusinvsnmpPrinterLogReport', '20', '3', '0'),
             (NULL,'PluginFusinvsnmpPrinterLogReport', '5', '4', '0'),
             (NULL,'PluginFusinvsnmpPrinterLogReport', '6', '5', '0')";
         $DB->query($query);
      } else {   
         while ($data=$DB->fetch_array($result)) {
            if (!isset($a_searchoptions[$data['num']])) {
               $queryd = "DELETE FROM `glpi_displaypreferences`
                  WHERE `id`='".$data['id']."'";
               $DB->query($queryd);
            }
         }
      }
   
   
   /*
    * Modify displaypreference for PluginFusinvsnmpNetworkEquipment
    */
      $a_check = array();
      $a_check["2"] = 1;
      $a_check["3"] = 2;
      $a_check["4"] = 3;
      $a_check["5"] = 4;
      $a_check["6"] = 5;
      $a_check["7"] = 6;
      $a_check["8"] = 7;
      $a_check["9"] = 8;
      $a_check["10"] = 9;
      $a_check["11"] = 10;
      $a_check["14"] = 11;
      $a_check["12"] = 12;
      $a_check["13"] = 13;
      
      foreach ($a_check as $num=>$rank) {
         $query = "SELECT * FROM `glpi_displaypreferences` 
         WHERE `itemtype` = 'PluginFusinvsnmpNetworkEquipment'
         AND `num`='".$num."'
            AND `users_id`='0'";
         $result=$DB->query($query);
         if ($DB->numrows($result) == '0') {
            $query = "INSERT INTO `glpi_displaypreferences` (`id`, `itemtype`, `num`, `rank`, `users_id`) 
                        VALUES (NULL,'PluginFusinvsnmpNetworkEquipment', '".$num."', '".$rank."', '0')";
            $DB->query($query);
         }
      }
      $query = "SELECT * FROM `glpi_displaypreferences` 
      WHERE `itemtype` = 'PluginFusinvsnmpNetworkEquipment'
         AND `users_id`='0'";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if (!isset($a_check[$data['num']])) {
            $queryd = "DELETE FROM `glpi_displaypreferences`
               WHERE `id`='".$data['id']."'";
            $DB->query($queryd);
         }
      }

      


   $config->updateConfigType($plugins_id, 'version', PLUGIN_FUSINVSNMP_VERSION);
   
   // Update profiles
   if (TableExists("glpi_plugin_tracker_profiles")) {
      $profile = new Profile();
      $pFusioninventoryProfile = new PluginFusioninventoryProfile();
      $query = "SELECT * FROM `glpi_plugin_tracker_profiles`";
      $result=$DB->query($query_select);
      while ($data=$DB->fetch_array($result)) {
         $profiledata = current($profile->find("`name`='".$data['name']."'", "", 1));
         if (!empty($profiledata)) {
            $newprofile = array();
            $newprofile['snmp_networking'] = "networkequipment";
            $newprofile['snmp_printers'] = "printer";
            $newprofile['snmp_models'] = "model";
            $newprofile['snmp_authentification'] = "configsecurity";
            $newprofile['general_config'] = "configuration";
            $newprofile['snmp_report'] = "reportprinter";
            
            foreach ($newprofile as $old=>$new) {
               if (isset($profiledata[$old])) {         
                  $pFusioninventoryProfile->addProfile($plugins_id, 
                                                       $new, 
                                                       $profiledata[$old], 
                                                       $profiledata['id']);
               }
            }
            if (isset($profiledata["snmp_report"])) {
               $pFusioninventoryProfile->addProfile($plugins_id, 
                                                    "reportnetworkequipment", 
                                                    $profiledata["snmp_report"], 
                                                    $profiledata['id']);
            }
         }         
      }
      $DB->query("DROP TABLE `glpi_plugin_tracker_profiles`");
   }
   
   /*
    * Fix problem with mapping with many entries with same mapping
    */
   $a_mapping = array();
   $a_mappingdouble = array();
   $query = "SELECT * FROM `glpi_plugin_fusioninventory_mappings`
      ORDER BY `id`";
   $result=$DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      if (!isset($a_mapping[$data['itemtype'].".".$data['name']])) {
         $a_mapping[$data['itemtype'].".".$data['name']] = $data['id'];
      } else {
         $a_mappingdouble[$data['id']] = $data['itemtype'].".".$data['name'];
      }
   }   
   foreach($a_mappingdouble as $mapping_id=>$mappingkey) {
      $query = "UPDATE `glpi_plugin_fusinvsnmp_modelmibs`
         SET plugin_fusioninventory_mappings_id='".$a_mapping[$mappingkey]."'
         WHERE plugin_fusioninventory_mappings_id='".$mapping_id."'";
      $DB->query($query);
      $query = "UPDATE `glpi_plugin_fusinvsnmp_printercartridges`
         SET plugin_fusioninventory_mappings_id='".$a_mapping[$mappingkey]."'
         WHERE plugin_fusioninventory_mappings_id='".$mapping_id."'";
      $DB->query($query);
      $query = "UPDATE `glpi_plugin_fusinvsnmp_networkportlogs`
         SET plugin_fusioninventory_mappings_id='".$a_mapping[$mappingkey]."'
         WHERE plugin_fusioninventory_mappings_id='".$mapping_id."'";
      $DB->query($query);
      $query = "UPDATE `glpi_plugin_fusinvsnmp_configlogfields`
         SET plugin_fusioninventory_mappings_id='".$a_mapping[$mappingkey]."'
         WHERE plugin_fusioninventory_mappings_id='".$mapping_id."'";
      $DB->query($query);
      $query = "DELETE FROM `glpi_plugin_fusioninventory_mappings`
         WHERE `id` = '".$mapping_id."'";
      $DB->query($query);
   }
   
   /*
    * Import / update SNMP models
    */
   if (!class_exists('PluginFusinvsnmpModel')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusinvsnmp/inc/model.class.php");
   }
   if (!class_exists('PluginFusinvsnmpImportExport')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusinvsnmp/inc/importexport.class.php");
   }
   if (!class_exists('PluginFusinvsnmpCommonDBTM')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusinvsnmp/inc/commondbtm.class.php");
   }
   PluginFusinvsnmpModel::importAllModels();
   
   
   // Update networkports types
   if (!class_exists('PluginFusinvsnmpNetworkporttype')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusinvsnmp/inc/networkporttype.class.php");
   }
   $pfNetworkporttype = new PluginFusinvsnmpNetworkporttype();
   $pfNetworkporttype->init();
   
   /*
    * Add Crontask if not exist
    */
   $crontask = new CronTask();
   if (!$crontask->getFromDBbyName('PluginFusinvsnmpNetworkPortLog', 'cleannetworkportlogs')) {
      Crontask::Register('PluginFusinvsnmpNetworkPortLog', 'cleannetworkportlogs', (3600 * 24), 
                         array('mode'=>2, 'allowmode'=>3, 'logs_lifetime'=>30));

   }
}



function update213to220_ConvertField($migration) {
   global $LANG,$FUSIONINVENTORY_MAPPING,$FUSIONINVENTORY_MAPPING_DISCOVERY,$DB;

   // ----------------------------------------------------------------------
   //NETWORK MAPPING MAPPING
   // ----------------------------------------------------------------------
   $constantsfield = array();

   $constantsfield['reseaux > lieu'] = 'location';
   $constantsfield['networking > location'] = 'location';
   $constantsfield['Netzwerk > Standort'] = 'location';

   $constantsfield['réseaux > firmware'] = 'firmware';
   $constantsfield['networking > firmware'] = 'firmware';
   $constantsfield['Netzwerk > Firmware'] = 'firmware';
   
   $constantsfield['réseaux > firmware'] = 'firmware1';
   $constantsfield['networking > firmware'] = 'firmware1';
   $constantsfield['Netzwerk > Firmware'] = 'firmware1';

   $constantsfield['réseaux > firmware'] = 'firmware2';
   $constantsfield['networking > firmware'] = 'firmware2';
   $constantsfield['Netzwerk > Firmware'] = 'firmware2';

   $constantsfield['réseaux > contact'] = 'contact';
   $constantsfield['networking > contact'] = 'contact';
   $constantsfield['Netzwerk > Kontakt'] = 'contact';
   
   $constantsfield['réseaux > description'] = 'comments';
   $constantsfield['networking > comments'] = 'comments';
   $constantsfield['Netzwerk > Kommentar'] = 'comments';

   $constantsfield['réseaux > uptime'] = 'uptime';
   $constantsfield['networking > uptime'] = 'uptime';
   $constantsfield['Netzwerk > Uptime'] = 'uptime';

   $constantsfield['réseaux > utilisation du CPU'] = 'cpu';
   $constantsfield['networking > CPU usage'] = 'cpu';
   $constantsfield['Netzwerk > CPU Auslastung'] = 'cpu';

   $constantsfield['réseaux > CPU user'] = 'cpuuser';
   $constantsfield['networking > CPU usage (user)'] = 'cpuuser';
   $constantsfield['Netzwerk > CPU Benutzer'] = 'cpuuser';

   $constantsfield['réseaux > CPU système'] = 'cpusystem';
   $constantsfield['networking > CPU usage (system)'] = 'cpusystem';
   $constantsfield['Netzwerk > CPU System'] = 'cpusystem';

   $constantsfield['réseaux > numéro de série'] = 'serial';
   $constantsfield['networking > serial number'] = 'serial';
   $constantsfield['Netzwerk > Seriennummer'] = 'serial';

   $constantsfield['réseaux > numéro d\'inventaire'] = 'otherserial';
   $constantsfield['networking > Inventory number'] = 'otherserial';
   $constantsfield['Netzwerk > Inventarnummer'] = 'otherserial';

   $constantsfield['réseaux > nom'] = 'name';
   $constantsfield['networking > name'] = 'name';
   $constantsfield['Netzwerk > Name'] = 'name';

   $constantsfield['réseaux > mémoire totale'] = 'ram';
   $constantsfield['networking > total memory'] = 'ram';
   $constantsfield['Netzwerk > Gesamter Speicher'] = 'ram';

   $constantsfield['réseaux > mémoire libre'] = 'memory';
   $constantsfield['networking > free memory'] = 'memory';
   $constantsfield['Netzwerk > Freier Speicher'] = 'memory';

   $constantsfield['réseaux > VLAN'] = 'vtpVlanName';
   $constantsfield['networking > VLAN'] = 'vtpVlanName';
   $constantsfield['Netzwerk > VLAN'] = 'vtpVlanName';

   $constantsfield['réseaux > port > vlan'] = 'vmvlan';
   $constantsfield['networking > port > vlan'] = 'vmvlan';

   $constantsfield['réseaux > modèle'] = 'entPhysicalModelName';
   $constantsfield['networking > model'] = 'entPhysicalModelName';
   $constantsfield['Netzwerk > Modell'] = 'entPhysicalModelName';

   $constantsfield['réseaux > adresse MAC'] = 'macaddr';
   $constantsfield['networking > MAC address'] = 'macaddr';
   $constantsfield['Netzwerk > MAC Adresse'] = 'macaddr';

   $constantsfield['réseaux > Adresse CDP'] = 'cdpCacheAddress';
   $constantsfield['networking > CDP address'] = 'cdpCacheAddress';
   $constantsfield['Netzwerk > Adresse CDP'] = 'cdpCacheAddress';

   $constantsfield['réseaux > port CDP'] = 'cdpCacheDevicePort';
   $constantsfield['networking > CDP port'] = 'cdpCacheDevicePort';
   $constantsfield['Netzwerk > Port CDP'] = 'cdpCacheDevicePort';

   $constantsfield['réseaux > chassis id distant LLDP'] = 'lldpRemChassisId';
   $constantsfield['networking > remote chassis id LLDP'] = 'lldpRemChassisId';

   $constantsfield['réseaux > port distant LLDP'] = 'lldpRemPortId';
   $constantsfield['networking > remote port LLDP'] = 'lldpRemPortId';

   $constantsfield['réseaux > chassis id local LLDP'] = 'lldpLocChassisId';
   $constantsfield['networking > localchassis id LLDP'] = 'lldpLocChassisId';

   $constantsfield['réseaux > port > trunk/tagged'] = 'vlanTrunkPortDynamicStatus';
   $constantsfield['networking > port > trunk/tagged'] = 'vlanTrunkPortDynamicStatus';
   $constantsfield['Netzwerk > Port > trunk/tagged'] = 'vlanTrunkPortDynamicStatus';

   $constantsfield['trunk'] = 'vlanTrunkPortDynamicStatus';

   $constantsfield['réseaux > Adresses mac filtrées (dot1dTpFdbAddress)'] = 'dot1dTpFdbAddress';
   $constantsfield['networking > MAC address filters (dot1dTpFdbAddress)'] = 'dot1dTpFdbAddress';
   $constantsfield['Netzwerk > MAC Adressen Filter (dot1dTpFdbAddress)'] = 'dot1dTpFdbAddress';

   $constantsfield['réseaux > adresses physiques mémorisées (ipNetToMediaPhysAddress)'] = 'ipNetToMediaPhysAddress';
   $constantsfield['networking > Physical addresses in memory (ipNetToMediaPhysAddress)'] = 'ipNetToMediaPhysAddress';
   $constantsfield['Netzwerk > Physikalische Adressen im Speicher (ipNetToMediaPhysAddress)'] = 'ipNetToMediaPhysAddress';

   $constantsfield['réseaux > instances de ports (dot1dTpFdbPort)'] = 'dot1dTpFdbPort';
   $constantsfield['networking > Port instances (dot1dTpFdbPort)'] = 'dot1dTpFdbPort';
   $constantsfield['Netzwerk > Instanzen des Ports (dot1dTpFdbPort)'] = 'dot1dTpFdbPort';

   $constantsfield['réseaux > numéro de ports associé ID du port (dot1dBasePortIfIndex)'] = 'dot1dBasePortIfIndex';
   $constantsfield['networking > Port number associated with port ID (dot1dBasePortIfIndex)'] = 'dot1dBasePortIfIndex';
   $constantsfield['Netzwerk > Verkn&uuml;pfung der Portnummerierung mit der ID des Ports (dot1dBasePortIfIndex)'] = 'dot1dBasePortIfIndex';

   $constantsfield['réseaux > addresses IP'] = 'ipAdEntAddr';
   $constantsfield['networking > IP addresses'] = 'ipAdEntAddr';
   $constantsfield['Netzwerk > IP Adressen'] = 'ipAdEntAddr';

   $constantsfield['réseaux > portVlanIndex'] = 'PortVlanIndex';
   $constantsfield['networking > portVlanIndex'] = 'PortVlanIndex';
   $constantsfield['Netzwerk > portVlanIndex'] = 'PortVlanIndex';

   $constantsfield['réseaux > port > numéro index'] = 'ifIndex';
   $constantsfield['networking > port > index number'] = 'ifIndex';
   $constantsfield['Netzwerk > Port > Nummerischer Index'] = 'ifIndex';

   $constantsfield['réseaux > port > mtu'] = 'ifmtu';
   $constantsfield['networking > port > mtu'] = 'ifmtu';
   $constantsfield['Netzwerk > Port > MTU'] = 'ifmtu';

   $constantsfield['réseaux > port > vitesse'] = 'ifspeed';
   $constantsfield['networking > port > speed'] = 'ifspeed';
   $constantsfield['Netzwerk > Port > Geschwindigkeit'] = 'ifspeed';

   $constantsfield['réseaux > port > statut interne'] = 'ifinternalstatus';
   $constantsfield['networking > port > internal status'] = 'ifinternalstatus';
   $constantsfield['Netzwerk > Port > Interner Zustand'] = 'ifinternalstatus';

   $constantsfield['réseaux > port > Dernier changement'] = 'iflastchange';
   $constantsfield['networking > ports > Last change'] = 'iflastchange';
   $constantsfield['Netzwerk > Ports > Letzte &Auml;nderung'] = 'iflastchange';

   $constantsfield['réseaux > port > nombre d\'octets entrés'] = 'ifinoctets';
   $constantsfield['networking > port > number of bytes in'] = 'ifinoctets';
   $constantsfield['Netzwerk > Port > Anzahl eingegangene Bytes'] = 'ifinoctets';

   $constantsfield['réseaux > port > nombre d\'octets sortis'] = 'ifoutoctets';
   $constantsfield['networking > port > number of bytes out'] = 'ifoutoctets';
   $constantsfield['Netzwerk > Port > Anzahl ausgehende Bytes'] = 'ifoutoctets';

   $constantsfield['réseaux > port > nombre d\'erreurs entrées'] = 'ifinerrors';
   $constantsfield['networking > port > number of input errors'] = 'ifinerrors';
   $constantsfield['Netzwerk > Port > Anzahl Input Fehler'] = 'ifinerrors';

   $constantsfield['réseaux > port > nombre d\'erreurs sorties'] = 'ifouterrors';
   $constantsfield['networking > port > number of output errors'] = 'ifouterrors';
   $constantsfield['Netzwerk > Port > Anzahl Fehler Ausgehend'] = 'ifouterrors';

   $constantsfield['réseaux > port > statut de la connexion'] = 'ifstatus';
   $constantsfield['networking > port > connection status'] = 'ifstatus';
   $constantsfield['Netzwerk > Port > Verbingungszustand'] = 'ifstatus';

   $constantsfield['réseaux > port > adresse MAC'] = 'ifPhysAddress';
   $constantsfield['networking > port > MAC address'] = 'ifPhysAddress';
   $constantsfield['Netzwerk > Port > MAC Adresse'] = 'ifPhysAddress';

   $constantsfield['réseaux > port > nom'] = 'ifName';
   $constantsfield['networking > port > name'] = 'ifName';
   $constantsfield['Netzwerk > Port > Name'] = 'ifName';

   $constantsfield['réseaux > port > type'] = 'ifType';
   $constantsfield['networking > ports > type'] = 'ifType';
   $constantsfield['Netzwerk > Ports > Typ'] = 'ifType';

   $constantsfield['réseaux > port > description du port'] = 'ifdescr';
   $constantsfield['networking > port > port description'] = 'ifdescr';
   $constantsfield['Netzwerk > Port > Port Bezeichnung'] = 'ifdescr';

   $constantsfield['réseaux > port > type de duplex'] = 'portDuplex';
   $constantsfield['networking > port > duplex type'] = 'portDuplex';
   $constantsfield['Netzwerk > Port > Duplex Typ'] = 'portDuplex';

   $constantsfield['imprimante > modèle'] = 'model';
   $constantsfield['printer > model'] = 'model';
   $constantsfield['Drucker > Modell'] = 'model';

   $constantsfield['imprimante > fabricant'] = 'enterprise';
   $constantsfield['printer > manufacturer'] = 'enterprise';
   $constantsfield['Drucker > Hersteller'] = 'enterprise';

   $constantsfield['imprimante > numéro de série'] = 'serial';
   $constantsfield['printer > serial number'] = 'serial';
   $constantsfield['Drucker > Seriennummer'] = 'serial';

   $constantsfield['imprimante > contact'] = 'contact';
   $constantsfield['printer > contact'] = 'contact';
   $constantsfield['Drucker > Kontakt'] = 'contact';

   $constantsfield['imprimante > description'] = 'comments';
   $constantsfield['printer > comments'] = 'comments';
   $constantsfield['Drucker > Kommentar'] = 'comments';

   $constantsfield['imprimante > nom'] = 'name';
   $constantsfield['printer > name'] = 'name';
   $constantsfield['Drucker > Name'] = 'name';

   $constantsfield['imprimante > numéro d\'inventaire'] = 'otherserial';
   $constantsfield['printer > Inventory number'] = 'otherserial';
   $constantsfield['Drucker > Inventarnummer'] = 'otherserial';

   $constantsfield['imprimante > mémoire totale'] = 'memory';
   $constantsfield['printer > total memory'] = 'memory';
   $constantsfield['Drucker > Gesamter Speicher'] = 'memory';

   $constantsfield['imprimante > lieu'] = 'location';
   $constantsfield['printer > location'] = 'location';
   $constantsfield['Drucker > Standort'] = 'location';

   $constantsfield['Informations diverses regroupées'] = 'informations';
   $constantsfield['Many informations grouped'] = 'informations';
   $constantsfield['Many informations grouped'] = 'informations';

   $constantsfield['Toner Noir'] = 'tonerblack';
   $constantsfield['Black toner'] = 'tonerblack';

   $constantsfield['Toner Noir Max'] = 'tonerblackmax';
   $constantsfield['Black toner Max'] = 'tonerblackmax';

   $constantsfield['Toner Noir Utilisé'] = 'tonerblackused';

   $constantsfield['Toner Noir Restant'] = 'tonerblackremaining';

   $constantsfield['Toner Noir'] = 'tonerblack2';
   $constantsfield['Black toner'] = 'tonerblack2';

   $constantsfield['Toner Noir Max'] = 'tonerblack2max';
   $constantsfield['Black toner Max'] = 'tonerblack2max';

   $constantsfield['Toner Noir Utilisé'] = 'tonerblack2used';

   $constantsfield['Toner Noir Restant'] = 'tonerblack2remaining';

   $constantsfield['Toner Cyan'] = 'tonercyan';
   $constantsfield['Cyan toner'] = 'tonercyan';

   $constantsfield['Toner Cyan Max'] = 'tonercyanmax';
   $constantsfield['Cyan toner Max'] = 'tonercyanmax';

   $constantsfield['Toner Cyan Utilisé'] = 'tonercyanused';

   $constantsfield['Toner Cyan Restant'] = 'tonercyanremaining';

   $constantsfield['Toner Magenta'] = 'tonermagenta';
   $constantsfield['Magenta toner'] = 'tonermagenta';

   $constantsfield['Toner Magenta Max'] = 'tonermagentamax';
   $constantsfield['Magenta toner Max'] = 'tonermagentamax';

   $constantsfield['Toner Magenta Utilisé'] = 'tonermagentaused';
   $constantsfield['Magenta toner Utilisé'] = 'tonermagentaused';

   $constantsfield['Toner Magenta Restant'] = 'tonermagentaremaining';
   $constantsfield['Magenta toner Restant'] = 'tonermagentaremaining';

   $constantsfield['Toner Jaune'] = 'toneryellow';
   $constantsfield['Yellow toner'] = 'toneryellow';

   $constantsfield['Toner Jaune Max'] = 'toneryellowmax';
   $constantsfield['Yellow toner Max'] = 'toneryellowmax';

   $constantsfield['Toner Jaune Utilisé'] = 'toneryellowused';
   $constantsfield['Yellow toner Utilisé'] = 'toneryellowused';

   $constantsfield['Toner Jaune Restant'] = 'toneryellowremaining';
   $constantsfield['Yellow toner Restant'] = 'toneryellowremaining';

   $constantsfield['Bac récupérateur de déchet'] = 'wastetoner';
   $constantsfield['Waste bin'] = 'wastetoner';
   $constantsfield['Abfalleimer'] = 'wastetoner';

   $constantsfield['Bac récupérateur de déchet Max'] = 'wastetonermax';
   $constantsfield['Waste bin Max'] = 'wastetonermax';

   $constantsfield['Bac récupérateur de déchet Utilisé'] = 'wastetonerused';
   $constantsfield['Waste bin Utilisé'] = 'wastetonerused';

   $constantsfield['Bac récupérateur de déchet Restant'] = 'wastetonerremaining';
   $constantsfield['Waste bin Restant'] = 'wastetonerremaining';

   $constantsfield['Cartouche noir'] = 'cartridgeblack';
   $constantsfield['Black ink cartridge'] = 'cartridgeblack';
   $constantsfield['Schwarze Kartusche'] = 'cartridgeblack';

   $constantsfield['Cartouche noir photo'] = 'cartridgeblackphoto';
   $constantsfield['Photo black ink cartridge'] = 'cartridgeblackphoto';
   $constantsfield['Photoschwarz Kartusche'] = 'cartridgeblackphoto';

   $constantsfield['Cartouche cyan'] = 'cartridgecyan';
   $constantsfield['Cyan ink cartridge'] = 'cartridgecyan';
   $constantsfield['Cyan Kartusche'] = 'cartridgecyan';

   $constantsfield['Cartouche cyan clair'] = 'cartridgecyanlight';
   $constantsfield['Light cyan ink cartridge'] = 'cartridgecyanlight';
   $constantsfield['Leichtes Cyan Kartusche'] = 'cartridgecyanlight';

   $constantsfield['Cartouche magenta'] = 'cartridgemagenta';
   $constantsfield['Magenta ink cartridge'] = 'cartridgemagenta';
   $constantsfield['Magenta Kartusche'] = 'cartridgemagenta';

   $constantsfield['Cartouche magenta clair'] = 'cartridgemagentalight';
   $constantsfield['Light ink magenta cartridge'] = 'cartridgemagentalight';
   $constantsfield['Leichtes Magenta Kartusche'] = 'cartridgemagentalight';

   $constantsfield['Cartouche jaune'] = 'cartridgeyellow';
   $constantsfield['Yellow ink cartridge'] = 'cartridgeyellow';
   $constantsfield['Gelbe Kartusche'] = 'cartridgeyellow';

   $constantsfield['Cartouche grise'] = 'cartridgegrey';
   $constantsfield['Grey ink cartridge'] = 'cartridgegrey';
   $constantsfield['Grey ink cartridge'] = 'cartridgegrey';

   $constantsfield['Kit de maintenance'] = 'maintenancekit';
   $constantsfield['Maintenance kit'] = 'maintenancekit';
   $constantsfield['Wartungsmodul'] = 'maintenancekit';

   $constantsfield['Kit de maintenance Max'] = 'maintenancekitmax';
   $constantsfield['Maintenance kit Max'] = 'maintenancekitmax';

   $constantsfield['Kit de maintenance Utilisé'] = 'maintenancekitused';
   $constantsfield['Maintenance kit Utilisé'] = 'maintenancekitused';

   $constantsfield['Kit de maintenance Restant'] = 'maintenancekitremaining';
   $constantsfield['Maintenance kit Restant'] = 'maintenancekitremaining';

   $constantsfield['Tambour Noir'] = 'drumblack';
   $constantsfield['Black drum'] = 'drumblack';

   $constantsfield['Tambour Noir Max'] = 'drumblackmax';
   $constantsfield['Black drum Max'] = 'drumblackmax';

   $constantsfield['Tambour Noir Utilisé'] = 'drumblackused';
   $constantsfield['Black drum Utilisé'] = 'drumblackused';

   $constantsfield['Tambour Noir Restant'] = 'drumblackremaining';
   $constantsfield['Black drum Restant'] = 'drumblackremaining';

   $constantsfield['Tambour Cyan'] = 'drumcyan';
   $constantsfield['Cyan drum'] = 'drumcyan';

   $constantsfield['Tambour Cyan Max'] = 'drumcyanmax';
   $constantsfield['Cyan drum Max'] = 'drumcyanmax';

   $constantsfield['Tambour Cyan Utilisé'] = 'drumcyanused';
   $constantsfield['Cyan drum Utilisé'] = 'drumcyanused';

   $constantsfield['Tambour Cyan Restant'] = 'drumcyanremaining';
   $constantsfield['Cyan drumRestant'] = 'drumcyanremaining';

   $constantsfield['Tambour Magenta'] = 'drummagenta';
   $constantsfield['Magenta drum'] = 'drummagenta';

   $constantsfield['Tambour Magenta Max'] = 'drummagentamax';
   $constantsfield['Magenta drum Max'] = 'drummagentamax';

   $constantsfield['Tambour Magenta Utilisé'] = 'drummagentaused';
   $constantsfield['Magenta drum Utilisé'] = 'drummagentaused';

   $constantsfield['Tambour Magenta Restant'] = 'drummagentaremaining';
   $constantsfield['Magenta drum Restant'] = 'drummagentaremaining';

   $constantsfield['Tambour Jaune'] = 'drumyellow';
   $constantsfield['Yellow drum'] = 'drumyellow';

   $constantsfield['Tambour Jaune Max'] = 'drumyellowmax';
   $constantsfield['Yellow drum Max'] = 'drumyellowmax';

   $constantsfield['Tambour Jaune Utilisé'] = 'drumyellowused';
   $constantsfield['Yellow drum Utilisé'] = 'drumyellowused';

   $constantsfield['Tambour Jaune Restant'] = 'drumyellowremaining';
   $constantsfield['Yellow drum Restant'] = 'drumyellowremaining';

   $constantsfield['imprimante > compteur > nombre total de pages imprimées'] = 'pagecountertotalpages';
   $constantsfield['printer > meter > total number of printed pages'] = 'pagecountertotalpages';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Seiten'] = 'pagecountertotalpages';

   $constantsfield['imprimante > compteur > nombre de pages noir et blanc imprimées'] = 'pagecounterblackpages';
   $constantsfield['printer > meter > number of printed black and white pages'] = 'pagecounterblackpages';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedrucker Schwarz/Wei&szlig; Seiten'] = 'pagecounterblackpages';

   $constantsfield['imprimante > compteur > nombre de pages couleur imprimées'] = 'pagecountercolorpages';
   $constantsfield['printer > meter > number of printed color pages'] = 'pagecountercolorpages';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Farbseiten'] = 'pagecountercolorpages';

   $constantsfield['imprimante > compteur > nombre de pages recto/verso imprimées'] = 'pagecounterrectoversopages';
   $constantsfield['printer > meter > number of printed duplex pages'] = 'pagecounterrectoversopages';
   $constantsfield['Drucker > Messung > Anzahl der gedruckten Duplex Seiten'] = 'pagecounterrectoversopages';

   $constantsfield['imprimante > compteur > nombre de pages scannées'] = 'pagecounterscannedpages';
   $constantsfield['printer > meter > nomber of scanned pages'] = 'pagecounterscannedpages';
   $constantsfield['Drucker > Messung > Anzahl der gescannten Seiten'] = 'pagecounterscannedpages';

   $constantsfield['imprimante > compteur > nombre total de pages imprimées (impression)'] = 'pagecountertotalpages_print';
   $constantsfield['printer > meter > total number of printed pages (print mode)'] = 'pagecountertotalpages_print';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Seiten (Druck)'] = 'pagecountertotalpages_print';
   
   $constantsfield['imprimante > compteur > nombre de pages noir et blanc imprimées (impression)'] = 'pagecounterblackpages_print';
   $constantsfield['printer > meter > number of printed black and white pages (print mode)'] = 'pagecounterblackpages_print';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Schwarz/Wei&szlig; Seiten (Druck)'] = 'pagecounterblackpages_print';

   $constantsfield['imprimante > compteur > nombre de pages couleur imprimées (impression)'] = 'pagecountercolorpages_print';
   $constantsfield['printer > meter > number of printed color pages (print mode)'] = 'pagecountercolorpages_print';
   $constantsfield['Drucker > Messung > Gesamtanzahl farbig gedruckter Seiten (Druck)'] = 'pagecountercolorpages_print';

   $constantsfield['imprimante > compteur > nombre total de pages imprimées (copie)'] = 'pagecountertotalpages_copy';
   $constantsfield['printer > meter > total number of printed pages (copy mode)'] = 'pagecountertotalpages_copy';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Seiten (Kopie)'] = 'pagecountertotalpages_copy';

   $constantsfield['imprimante > compteur > nombre de pages noir et blanc imprimées (copie)'] = 'pagecounterblackpages_copy';
   $constantsfield['printer > meter > number of printed black and white pages (copy mode)'] = 'pagecounterblackpages_copy';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Schwarz/Wei&szlig; Seite (Kopie)'] = 'pagecounterblackpages_copy';

   $constantsfield['imprimante > compteur > nombre de pages couleur imprimées (copie)'] = 'pagecountercolorpages_copy';
   $constantsfield['printer > meter > number of printed color pages (copy mode)'] = 'pagecountercolorpages_copy';
   $constantsfield['Drucker > Messung > Gesamtanzahl farbig gedruckter Seiten (Kopie)'] = 'pagecountercolorpages_copy';

   $constantsfield['imprimante > compteur > nombre total de pages imprimées (fax)'] = 'pagecountertotalpages_fax';
   $constantsfield['printer > meter > total number of printed pages (fax mode)'] = 'pagecountertotalpages_fax';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Seiten (Fax)'] = 'pagecountertotalpages_fax';

   $constantsfield['imprimante > compteur > nombre total de pages larges imprimées'] = 'pagecounterlargepages';
   $constantsfield['printer > meter > total number of large printed pages'] = 'pagecounterlargepages';

   $constantsfield['imprimante > port > adresse MAC'] = 'ifPhysAddress';
   $constantsfield['printer > port > MAC address'] = 'ifPhysAddress';
   $constantsfield['Drucker > Port > MAC Adresse'] = 'ifPhysAddress';

   $constantsfield['imprimante > port > nom'] = 'ifName';
   $constantsfield['printer > port > name'] = 'ifName';
   $constantsfield['Drucker > Port > Name'] = 'ifName';

   $constantsfield['imprimante > port > adresse IP'] = 'ifaddr';
   $constantsfield['printer > port > IP address'] = 'ifaddr';
   $constantsfield['Drucker > Port > IP Adresse'] = 'ifaddr';

   $constantsfield['imprimante > port > type'] = 'ifType';
   $constantsfield['printer > port > type'] = 'ifType';
   $constantsfield['Drucker > port > Typ'] = 'ifType';

   $constantsfield['imprimante > port > numéro index'] = 'ifIndex';
   $constantsfield['printer > port > index number'] = 'ifIndex';
   $constantsfield['Drucker > Port > Indexnummer'] = 'ifIndex';

   echo "Converting history port ...\n";
   $i = 0;
   $nb = count($constantsfield);
   $migration->addKey("glpi_plugin_tracker_snmp_history", 
                      "Field");
   $migration->addKey("glpi_plugin_tracker_snmp_history", 
                      array("Field", "old_value"),
                      "Field_2");
   $migration->addKey("glpi_plugin_tracker_snmp_history", 
                      array("Field", "new_value"),
                      "Field_3");
   $migration->migrationOneTable("glpi_plugin_tracker_snmp_history");

   foreach($constantsfield as $langvalue=>$mappingvalue) {
      $i++;
      $query_update = "UPDATE `glpi_plugin_tracker_snmp_history`
         SET `Field`='".$mappingvalue."'
         WHERE `Field`=\"".$langvalue."\" ";
      $DB->query($query_update);
      $migration->displayMessage("$i / $nb");
   }
   $migration->displayMessage("$i / $nb");

   // Move connections from glpi_plugin_fusioninventory_snmp_history to glpi_plugin_fusioninventory_snmp_history_connections
   echo "Moving creation connections history\n";
   $query = "SELECT *
             FROM `glpi_plugin_tracker_snmp_history`
             WHERE `Field` = '0'
               AND ((`old_value` NOT LIKE '%:%')
                     OR (`old_value` IS NULL))";
   if ($result=$DB->query($query)) {
      $nb = $DB->numrows($result);
      $i = 0;
      $migration->displayMessage("$i / $nb");
      while ($data=$DB->fetch_array($result)) {
         $i++;

         // Search port from mac address
         $query_port = "SELECT * FROM `glpi_networkports`
            WHERE `mac`='".$data['new_value']."' ";
         if ($result_port=$DB->query($query_port)) {
            if ($DB->numrows($result_port) == '1') {
               $input = array();
               $data_port = $DB->fetch_assoc($result_port);
               $input['FK_port_source'] = $data_port['id'];

               $query_port2 = "SELECT * FROM `glpi_networkports`
                  WHERE `items_id` = '".$data['new_device_ID']."'
                     AND `itemtype` = '".$data['new_device_type']."' ";
               if ($result_port2=$DB->query($query_port2)) {
                  if ($DB->numrows($result_port2) == '1') {
                     $data_port2 = $DB->fetch_assoc($result_port2);
                     $input['FK_port_destination'] = $data_port2['id'];

                     $input['date'] = $data['date_mod'];
                     $input['creation'] = 1;
                     $input['process_number'] = $data['FK_process'];
                     $query_ins = "INSERT INTO `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
                        (`date_mod`, `creation`, `networkports_id_source`, `networkports_id_destination`)
                        VALUES ('".$input['date']."',
                                '".$input['creation']."',
                                '".$input['FK_port_source']."',
                                '".$input['FK_port_destination']."')";
                     $DB->query($query_ins);
                  }
               }
            }
         }

         $query_delete = "DELETE FROM `glpi_plugin_tracker_snmp_history`
               WHERE `ID`='".$data['ID']."' ";
         $DB->query($query_delete);
         if (preg_match("/000$/", $i)) {
            $migration->displayMessage("$i / $nb");
         }
      }
      $migration->displayMessage("$i / $nb");
   }

   echo "Moving deleted connections history\n";
   $query = "SELECT *
             FROM `glpi_plugin_tracker_snmp_history`
             WHERE `Field` = '0'
               AND ((`new_value` NOT LIKE '%:%')
                     OR (`new_value` IS NULL))";
   if ($result=$DB->query($query)) {
      $nb = $DB->numrows($result);
      $i = 0;
      $migration->displayMessage("$i / $nb");
      while ($data=$DB->fetch_array($result)) {
         $i++;

         // Search port from mac address
         $query_port = "SELECT * FROM `glpi_networkports`
            WHERE `mac`='".$data['old_value']."' ";
         if ($result_port=$DB->query($query_port)) {
            if ($DB->numrows($result_port) == '1') {
               $input = array();
               $data_port = $DB->fetch_assoc($result_port);
               $input['FK_port_source'] = $data_port['id'];

               $query_port2 = "SELECT * FROM `glpi_networkports`
                  WHERE `items_id` = '".$data['old_device_ID']."'
                     AND `itemtype` = '".$data['old_device_type']."' ";
               if ($result_port2=$DB->query($query_port2)) {
                  if ($DB->numrows($result_port2) == '1') {
                     $data_port2 = $DB->fetch_assoc($result_port2);
                     $input['FK_port_destination'] = $data_port2['id'];

                     $input['date'] = $data['date_mod'];
                     $input['creation'] = 1;
                     $input['process_number'] = $data['FK_process'];
                     if ($input['FK_port_source'] != $input['FK_port_destination']) {
                        $query_ins = "INSERT INTO `glpi_plugin_fusinvsnmp_networkportconnectionlogs`
                           (`date_mod`, `creation`, `networkports_id_source`, `networkports_id_destination`)
                           VALUES ('".$input['date']."',
                                   '".$input['creation']."',
                                   '".$input['FK_port_source']."',
                                   '".$input['FK_port_destination']."')";
                        $DB->query($query_ins);
                     }
                  }
               }
            }
         }

         $query_delete = "DELETE FROM `glpi_plugin_tracker_snmp_history`
               WHERE `ID`='".$data['ID']."' ";
         $DB->query($query_delete);
         if (preg_match("/000$/", $i)) {
            $migration->displayMessage("$i / $nb");
         }
      }
      $migration->displayMessage("$i / $nb");
   }
}



function changeDisplayPreference($olditemtype, $newitemtype) {
   global $DB;
   
   $query = "SELECT *,count(`id`) as `cnt` FROM `glpi_displaypreferences` 
   WHERE (`itemtype` = '".$newitemtype."'
   OR `itemtype` = '".$olditemtype."')
   group by `users_id`, `num`";
   $result=$DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      if ($data['cnt'] > 1) {
         $queryd = "DELETE FROM `glpi_displaypreferences`
            WHERE `id`='".$data['id']."'";
         $DB->query($queryd);
      }
   }
   
   $sql = "UPDATE `glpi_displaypreferences`
      SET `itemtype`='".$newitemtype."'
      WHERE `itemtype`='".$olditemtype."' ";
   $DB->query($sql);   
}

?>