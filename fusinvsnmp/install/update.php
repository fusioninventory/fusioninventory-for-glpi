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

function pluginFusinvsnmpGetCurrentVersion($version) {
   global $DB;

   if ((!TableExists("glpi_plugin_tracker_config")) &&
      (!TableExists("glpi_plugin_fusioninventory_config")) &&
      (!TableExists("glpi_plugin_fusinvsnmp_agentconfigs")) &&
      (!TableExists("glpi_plugin_fusinvsnmp_tmp_configs"))) {
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
      
      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
      $versionconfig = $PluginFusioninventoryConfig->getValue($plugins_id, "version");
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
            $versionconfig = $PluginFusioninventoryConfig->getValue($a_findmodule['plugins_id'], "version");
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

   if (!class_exists('PluginFusioninventoryMapping')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/mapping.class.php");
   }
//   
//   switch ($current_version){
//      case "2.2.1":
//         include("update_221_230.php");
//         update221to230();
//      case "2.3.0-1":
//      case "2.3.1-1":
//         include("update_231_232.php");
//         update231to232();
//      case "2.3.2-1":
//      case "2.3.3-1":
//      case "2.3.4-1":
//      case "2.3.5-1":
//      case "2.3.6-1":
//      case "2.3.7-1":
//      case "2.3.8-1":
//      case "2.3.9-1":
//         include("update_232_240.php");
//         update232to240();
//         PluginFusinvsnmpModel::importAllModels();
//   }
   
   $configSNMP = new PluginFusinvSNMPConfig;
   $configSNMP->initConfigModule();
   
   
   $migration = new $migrationname($current_version);
   $prepare_task = array();
   $prepare_rangeip = array();
   
   // ** Udpate mapping
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
   
   
   
   // ** glpi_plugin_fusinvsnmp_miblabels
      $newTable = "glpi_plugin_fusinvsnmp_miblabels";
      $migration->renameTable("glpi_dropdown_plugin_tracker_mib_label", 
                              $newTable);
      $migration->changeField($newTable,
                              "ID",
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
      $migration->changeField($newTable,
                              "name",
                              "name",
                              "varchar(255) DEFAULT NULL");
      $migration->changeField($newTable,
                              "comments",
                              "comment",
                              "text");
 
      
   // ** glpi_plugin_fusinvsnmp_mibobjects
      $newTable = "glpi_plugin_fusinvsnmp_mibobjects";
      $migration->renameTable("glpi_dropdown_plugin_tracker_mib_object", 
                              $newTable);
      $migration->changeField($newTable,
                              "ID",
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
      $migration->changeField($newTable,
                              "name",
                              "name",
                              "varchar(255) DEFAULT NULL");
      $migration->changeField($newTable,
                              "comments",
                              "comment",
                              "text");
      
      
   // ** glpi_plugin_fusinvsnmp_miboids
      $newTable = "glpi_plugin_fusinvsnmp_miboids";
      $migration->renameTable("glpi_dropdown_plugin_tracker_mib_oid", 
                              $newTable);
      $migration->changeField($newTable,
                              "ID",
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
      $migration->changeField($newTable,
                              "name",
                              "name",
                              "varchar(255) DEFAULT NULL");
      $migration->changeField($newTable,
                              "comments",
                              "comment",
                              "text");
      
   // ** glpi_plugin_fusinvsnmp_configlogfields
      
      
   // ** glpi_plugin_fusinvsnmp_constructdevices
      
   
   // ** glpi_plugin_fusinvsnmp_constructdevicewalks
      
      
   // ** glpi_plugin_fusinvsnmp_constructdevice_miboids
      
      
   // ** glpi_plugin_fusinvsnmp_networkportconnectionlogs
      
      
   // ** glpi_plugin_fusinvsnmp_modelmibs
      $newTable = "glpi_plugin_fusinvsnmp_modelmibs";
      $migration->renameTable("glpi_plugin_tracker_mib_networking", 
                              $newTable);
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
      $migration->changeField($newTable,
                              "oid_port_dyn",
                              "oid_port_dyn",
                              "tinyint(1) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "plugin_fusioninventory_mappings_id", 
                           "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
      // Update with mapping
      $pFusinvsnmpModelMib = new PluginFusinvsnmpModelMib();
      $query = "SELECT * FROM `".$newTable."`";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $pFusioninventoryMapping = new PluginFusioninventoryMapping();
         $mapping = 0;
         if ($data['mapping_type'] == '2') {
            $data['mapping_type'] == 'NetworkEquipment';
         } else if ($data['mapping_type'] == '3') {
            $data['mapping_type'] == 'Printer';
         } else {
            $data['mapping_type'] = '';
         }
         if ($mapping_id = $pFusioninventoryMapping->get($data['mapping_type'], $data['mapping_name'])) {
            $data['plugin_fusioninventory_mappings_id'] = $mapping_id;
            $pFusinvsnmpModelMib->update($data);
         }
      }
      $migration->dropField($newTable,
                            "mapping_type");
      $migration->dropField($newTable,
                            "mapping_name");
      
      $migration->changeField($newTable,
                              "activation",
                              "is_active",
                              "tinyint(1) NOT NULL DEFAULT '1'");
      $migration->changeField($newTable,
                              "vlan",
                              "vlan",
                              "tinyint(1) NOT NULL DEFAULT '0'");
      
/*
CREATE TABLE `glpi_plugin_tracker_mib_networking` (
  `mapping_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `mapping_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `activation` int(1) NOT NULL DEFAULT '1',
  `vlan` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `FK_model_infos` (`FK_model_infos`),
  KEY `FK_model_infos_2` (`FK_model_infos`,`oid_port_dyn`),
  KEY `FK_model_infos_3` (`FK_model_infos`,`oid_port_counter`,`mapping_name`),
  KEY `FK_model_infos_4` (`FK_model_infos`,`mapping_name`),
  KEY `oid_port_dyn` (`oid_port_dyn`),
  KEY `activation` (`activation`)
) ENGINE=MyISAM AUTO_INCREMENT=542 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

 * 
 * 
CREATE TABLE `glpi_plugin_fusinvsnmp_modelmibs` (

   `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   `is_active` tinyint(1) NOT NULL DEFAULT '1',
   `vlan` tinyint(1) NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`),
   KEY `plugin_fusinvsnmp_models_id` (`plugin_fusinvsnmp_models_id`),
   KEY `plugin_fusinvsnmp_models_id_2` (`plugin_fusinvsnmp_models_id`,`oid_port_dyn`),
   KEY `plugin_fusinvsnmp_models_id_3` (`plugin_fusinvsnmp_models_id`,`oid_port_counter`,`plugin_fusioninventory_mappings_id`),
   KEY `plugin_fusinvsnmp_models_id_4` (`plugin_fusinvsnmp_models_id`,`plugin_fusioninventory_mappings_id`),
   KEY `oid_port_dyn` (`oid_port_dyn`),
   KEY `is_active` (`is_active`),
   KEY `plugin_fusioninventory_mappings_id` (`plugin_fusioninventory_mappings_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

 */
      
      
      
      
   // ** glpi_plugin_fusinvsnmp_models
      
      
   // ** glpi_plugin_fusinvsnmp_networkequipments
      
      
   // ** glpi_plugin_fusinvsnmp_networkequipmentips
      $newTable = "glpi_plugin_fusinvsnmp_networkequipmentips";
      $migration->renameTable("glpi_plugin_tracker_networking_ifaddr", 
                              $newTable);
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
      $migration->changeField($newTable,
                              "ip",
                              "ip",
                              "varchar(255) DEFAULT NULL");
      $migration->dropKey($newTable, 
                          "ifaddr");
      $migration->addKey($newTable,
                         "ip");
      $migration->addKey($newTable,
                         "networkequipments_id");
  
      
   // ** glpi_plugin_fusinvsnmp_networkports
      $newTable = "glpi_plugin_fusinvsnmp_networkports";
      $migration->renameTable("glpi_plugin_tracker_networking", 
                              $newTable);
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
                              "last_tracker_update",
                              "last_fusioninventory_update",
                              "datetime DEFAULT NULL");
      $migration->changeField($newTable,
                              "last_PID_update",
                              "last_PID_update",
                              "int(11) NOT NULL DEFAULT '0'");
      $migration->addField($newTable, 
                           "sysdescr", 
                           "text COLLATE utf8_unicode_ci");
      $migration->dropKey($newTable, 
                          "FK_networking");
      $migration->dropKey($newTable, 
                          "FK_model_infos");
      $migration->addKey($newTable,
                         "networkequipments_id");
      $migration->addKey($newTable,
                         array("plugin_fusinvsnmp_models_id", "plugin_fusinvsnmp_configsecurities_id"),
                         "plugin_fusinvsnmp_models_id");

      
   // ** glpi_plugin_fusinvsnmp_printerlogs
      $newTable = "glpi_plugin_fusinvsnmp_printerlogs";
      $migration->renameTable("glpi_plugin_tracker_printers_history", 
                              $newTable);
      $migration->changeField($newTable,
                              "ID",
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
      $migration->changeField($newTable,
                              "FK_printers",
                              "printers_id",
                              "int(11) NOT NULL DEFAULT '0'");
      $migration->changeField($newTable,
                              "date",
                              "date",
                              "datetime DEFAULT '0000-00-00 00:00:00'");
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
      $migration->addKey($newTable,
                         array("printers_id", "date"),
                         "printers_id");
      
      
   // ** glpi_plugin_fusinvsnmp_printers
      $newTable = "glpi_plugin_fusinvsnmp_printers";
      $migration->renameTable("glpi_plugin_tracker_printers", 
                              $newTable);
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
                              "frequence_days",
                              "frequence_days",
                              "int(5) NOT NULL DEFAULT '1'");
      $migration->changeField($newTable,
                              "last_tracker_update",
                              "last_fusioninventory_update",
                              "datetime DEFAULT NULL");
      $migration->addField($newTable, 
                           "sysdescr", 
                           "text COLLATE utf8_unicode_ci");
      $migration->dropKey($newTable, 
                          "FK_printers");
      $migration->dropKey($newTable, 
                          "FK_snmp_connection");
      $migration->addKey($newTable,
                         "plugin_fusinvsnmp_configsecurities_id");
      $migration->addKey($newTable,
                         "printers_id");
      $migration->addKey($newTable,
                         "plugin_fusinvsnmp_models_id");
   
      
   // ** glpi_plugin_fusinvsnmp_printercartridges
      $newTable = "glpi_plugin_fusinvsnmp_printercartridges";
      $migration->renameTable("glpi_plugin_tracker_printers_cartridges", 
                              $newTable);
      $migration->changeField($newTable,
                              "ID",
                              "id",
                              "int(100) NOT NULL AUTO_INCREMENT");
      $migration->changeField($newTable,
                              "id",
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
      $migration->changeField($newTable,
                              "state",
                              "state",
                              "int(3) NOT NULL DEFAULT '100'");
      
      $migration->addField($newTable, 
                           "plugin_fusioninventory_mappings_id", 
                           "int(11) NOT NULL DEFAULT '0'");
      $migration->migrationOneTable($newTable);
      // Update with mapping
      $pcartridge = new PluginFusinvsnmpCommonDBTM($newTable);
      $query = "SELECT * FROM `".$newTable."`";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $pFusioninventoryMapping = new PluginFusioninventoryMapping();
         $mapping = 0;
         if ($mapping_id = $pFusioninventoryMapping->get("Printer", $data['object_name'])) {
            $data['plugin_fusioninventory_mappings_id'] = $mapping_id;
            $pcartridge->update($data);
         }
      }
      $migration->dropField($newTable,
                            "object_name");
      $migration->addKey($newTable,
                         "printers_id");
      $migration->addKey($newTable,
                         "plugin_fusioninventory_mappings_id");
      $migration->addKey($newTable,
                         "cartridges_id");
      
      
   // ** glpi_plugin_fusinvsnmp_configsecurities
      // TODO get info to create SNMP authentification with old values of Tracker plugin
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

      
      
   // ** glpi_plugin_fusinvsnmp_networkportlogs
      $newTable = "glpi_plugin_fusinvsnmp_networkportlogs";
      $migration->renameTable("glpi_plugin_tracker_snmp_history", 
                              $newTable);
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
      $pFusinvsnmpNetworkPortLog = new PluginFusinvsnmpNetworkPortLog();
      $query = "SELECT * FROM `".$newTable."`";
      $result=$DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $pFusioninventoryMapping = new PluginFusioninventoryMapping();
         $mapping = 0;
         if ($mapping_id = $pFusioninventoryMapping->get("NetworkEquipment", $data['Field'])) {
            $data['plugin_fusioninventory_mappings_id'] = $mapping_id;
            $pFusinvsnmpNetworkPortLog->update($data);
         }
      }
      $migration->dropField($newTable,
                            "Field");
      $migration->changeField($newTable,
                              "date_mod",
                              "date_mod",
                              "datetime DEFAULT NULL"); 
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
      $migration->changeField($newTable,
                              "FK_process",
                              "plugin_fusioninventory_agentprocesses_id",
                              "int(11) NOT NULL DEFAULT '0'");
      $migration->dropKey($newTable, 
                          "FK_ports");
      $migration->addKey($newTable,
                         array("networkports_id", "date_mod"),
                         "networkports_id");
      $migration->addKey($newTable,
                         "plugin_fusioninventory_mappings_id");
      $migration->addKey($newTable,
                         "plugin_fusioninventory_agentprocesses_id");
      $migration->addKey($newTable,
                         "date_mod");

      
   // ** glpi_plugin_fusinvsnmp_unknowndevices
      
      
   // ** glpi_plugin_fusinvsnmp_agentconfigs
      
      
   // ** glpi_plugin_fusinvsnmp_statediscoveries
      
      
      
      
      
      
   if (TableExists("glpi_plugin_tracker_computers")) {
      $DB->query("DROP TABLE glpi_plugin_tracker_computers");
   }  
   if (TableExists("glpi_plugin_tracker_config")) {
      $DB->query("DROP TABLE glpi_plugin_tracker_config");
   }
   if (TableExists("glpi_plugin_tracker_config_discovery")) {
      $DB->query("DROP TABLE glpi_plugin_tracker_config_discovery");
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
      SET `itemtype`='PluginFusinvsnmpNetworkPortLog'
      WHERE `itemtype`='5162' ";
   $DB->query($sql);
   $sql = "UPDATE `glpi_displaypreferences`
      SET `itemtype`='PluginFusinvsnmpConstructDevice'
      WHERE `itemtype`='5167' ";
   $DB->query($sql);
   $sql = "UPDATE `glpi_displaypreferences`
      SET `itemtype`='PluginFusinvsnmpPrinterLog'
      WHERE `itemtype`='5168' ";
   $DB->query($sql);

      

   $config = new PluginFusioninventoryConfig();
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
   $config->updateConfigType($plugins_id, 'version', PLUGIN_FUSINVSNMP_VERSION);
}
?>