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
   
   $pFusioninventoryMapping->set($p_itemtype, 'PortVlanIndex','','',422,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifIndex','','',408,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifmtu','glpi_plugin_fusinvsnmp_networkports',
                                 'ifmtu',4,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifspeed','glpi_plugin_fusinvsnmp_networkports',
                                 'ifspeed',5,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifinternalstatus','glpi_plugin_fusinvsnmp_networkports',
                                 'ifinternalstatus',6,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'iflastchange','glpi_plugin_fusinvsnmp_networkports',
                                 'iflastchange',7,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifinoctets','glpi_plugin_fusinvsnmp_networkports',
                                 'ifinoctets',8,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifoutoctets','glpi_plugin_fusinvsnmp_networkports',
                                 'ifoutoctets',9,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifinerrors','glpi_plugin_fusinvsnmp_networkports',
                                 'ifinerrors',10,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifouterrors','glpi_plugin_fusinvsnmp_networkports',
                                 'ifouterrors',11,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifstatus','glpi_plugin_fusinvsnmp_networkports',
                                 'ifstatus',14,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifPhysAddress','glpi_networkports','mac',15,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifName','glpi_networkports','name',16,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifType','','',18,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifdescr','glpi_plugin_fusinvsnmp_networkports',
                                 'ifdescr',23,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'portDuplex','glpi_plugin_fusinvsnmp_networkports',
                                 'portduplex',33,NULL);
   
   $p_itemtype = 'Printer';
   $pFusioninventoryMapping->set($p_itemtype, 'model','glpi_printers','printermodels_id',25,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'enterprise','glpi_printers','manufacturers_id',420,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'serial','glpi_printers','serial',27,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'contact','glpi_printers','contact',405,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'comments','glpi_printers','comment',406,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'name','glpi_printers','comment',24,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'otherserial','glpi_printers','otherserial',418,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'memory','glpi_printers','memory_size',26,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'location','glpi_printers','locations_id',56,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'informations','','',165,165);
   $pFusioninventoryMapping->set($p_itemtype, 'tonerblack','','',157,157);
   $pFusioninventoryMapping->set($p_itemtype, 'tonerblackmax','','',166,166);
   $pFusioninventoryMapping->set($p_itemtype, 'tonerblackused','','',167,167);
   $pFusioninventoryMapping->set($p_itemtype, 'tonerblackremaining','','',168,168);
   $pFusioninventoryMapping->set($p_itemtype, 'tonerblack2','','',157,157);
   $pFusioninventoryMapping->set($p_itemtype, 'tonerblack2max','','',166,166);
   
   $pFusioninventoryMapping->set($p_itemtype, 'tonerblack2used','','',167,167);
   $pFusioninventoryMapping->set($p_itemtype, 'tonerblack2remaining','','',168,168);
   $pFusioninventoryMapping->set($p_itemtype, 'tonercyan','','',158,158);
   $pFusioninventoryMapping->set($p_itemtype, 'tonercyanmax','','',169,169);
   $pFusioninventoryMapping->set($p_itemtype, 'tonercyanused','','',170,170);
   $pFusioninventoryMapping->set($p_itemtype, 'tonercyanremaining','','',171,171);
   $pFusioninventoryMapping->set($p_itemtype, 'tonermagenta','','',159,159);
   $pFusioninventoryMapping->set($p_itemtype, 'tonermagentamax','','',172,172);
   $pFusioninventoryMapping->set($p_itemtype, 'tonermagentaused','','',173,173);
   $pFusioninventoryMapping->set($p_itemtype, 'tonermagentaremaining','','',174,174);
   $pFusioninventoryMapping->set($p_itemtype, 'toneryellow','','',160,160);
   $pFusioninventoryMapping->set($p_itemtype, 'toneryellowmax','','',175,175);
   $pFusioninventoryMapping->set($p_itemtype, 'toneryellowused','','',176,176);
   $pFusioninventoryMapping->set($p_itemtype, 'toneryellowremaining','','',177,177);
   $pFusioninventoryMapping->set($p_itemtype, 'wastetoner','','',151,151);
   $pFusioninventoryMapping->set($p_itemtype, 'wastetonermax','','',190,190);
   $pFusioninventoryMapping->set($p_itemtype, 'wastetonerused','','',191,191);
   $pFusioninventoryMapping->set($p_itemtype, 'wastetonerremaining','','',192,192);
   $pFusioninventoryMapping->set($p_itemtype, 'cartridgeblack','','',134,134);
   $pFusioninventoryMapping->set($p_itemtype, 'cartridgeblackphoto','','',135,135);
   $pFusioninventoryMapping->set($p_itemtype, 'cartridgecyan','','',136,136);
   $pFusioninventoryMapping->set($p_itemtype, 'cartridgecyanlight','','',139,139);
   $pFusioninventoryMapping->set($p_itemtype, 'cartridgemagenta','','',138,138);
   $pFusioninventoryMapping->set($p_itemtype, 'cartridgemagentalight','','',140,140);
   $pFusioninventoryMapping->set($p_itemtype, 'cartridgeyellow','','',137,137);
   $pFusioninventoryMapping->set($p_itemtype, 'cartridgegrey','','',196,196);
   $pFusioninventoryMapping->set($p_itemtype, 'maintenancekit','','',156,156);
   $pFusioninventoryMapping->set($p_itemtype, 'maintenancekitmax','','',193,193);
   $pFusioninventoryMapping->set($p_itemtype, 'maintenancekitused','','',194,194);
   $pFusioninventoryMapping->set($p_itemtype, 'maintenancekitremaining','','',195,195);
   $pFusioninventoryMapping->set($p_itemtype, 'drumblack','','',161,161);
   $pFusioninventoryMapping->set($p_itemtype, 'drumblackmax','','',178,178);
   $pFusioninventoryMapping->set($p_itemtype, 'drumblackused','','',179,179);
   $pFusioninventoryMapping->set($p_itemtype, 'drumblackremaining','','',180,180);
   $pFusioninventoryMapping->set($p_itemtype, 'drumcyan','','',162,162);
   $pFusioninventoryMapping->set($p_itemtype, 'drumcyanmax','','',181,181);
   $pFusioninventoryMapping->set($p_itemtype, 'drumcyanused','','',182,182);
   $pFusioninventoryMapping->set($p_itemtype, 'drumcyanremaining','','',183,183);   
   $pFusioninventoryMapping->set($p_itemtype, 'drummagenta','','',163,163);
   $pFusioninventoryMapping->set($p_itemtype, 'drummagentamax','','',184,184);
   $pFusioninventoryMapping->set($p_itemtype, 'drummagentaused','','',185,185);
   $pFusioninventoryMapping->set($p_itemtype, 'drummagentaremaining','','',186,186);
   $pFusioninventoryMapping->set($p_itemtype, 'drumyellow','','',164,164);
   $pFusioninventoryMapping->set($p_itemtype, 'drumyellowmax','','',187,187);
   $pFusioninventoryMapping->set($p_itemtype, 'drumyellowused','','',188,188);
   $pFusioninventoryMapping->set($p_itemtype, 'drumyellowremaining','','',189,189);
   
   $pFusioninventoryMapping->set($p_itemtype, 'pagecountertotalpages','glpi_plugin_fusinvsnmp_printerlogs',
                                 'pages_total',28,128);
   $pFusioninventoryMapping->set($p_itemtype,'pagecounterblackpages','glpi_plugin_fusinvsnmp_printerlogs',
                                 'pages_n_b',29,129);
   $pFusioninventoryMapping->set($p_itemtype, 'pagecountercolorpages','glpi_plugin_fusinvsnmp_printerlogs',
                                 'pages_color',30,130);
   $pFusioninventoryMapping->set($p_itemtype, 'pagecounterrectoversopages','glpi_plugin_fusinvsnmp_printerlogs',
                                 'pages_recto_verso',54,154);
   $pFusioninventoryMapping->set($p_itemtype, 'pagecounterscannedpages','glpi_plugin_fusinvsnmp_printerlogs',
                                 'scanned',55,155);
   $pFusioninventoryMapping->set($p_itemtype, 'pagecountertotalpages_print','glpi_plugin_fusinvsnmp_printerlogs',
                                 'pages_total_print',423,1423);
   $pFusioninventoryMapping->set($p_itemtype, 'pagecounterblackpages_print','glpi_plugin_fusinvsnmp_printerlogs',
                                 'pages_n_b_print',424,1424);
   $pFusioninventoryMapping->set($p_itemtype, 'pagecountercolorpages_print','glpi_plugin_fusinvsnmp_printerlogs',
                                 'pages_color_print',425,1425);
   $pFusioninventoryMapping->set($p_itemtype, 'pagecountertotalpages_copy','glpi_plugin_fusinvsnmp_printerlogs',
                                 'pages_total_copy',426,1426);
   $pFusioninventoryMapping->set($p_itemtype, 'pagecounterblackpages_copy','glpi_plugin_fusinvsnmp_printerlogs',
                                 'pages_n_b_copy',427,1427);
   $pFusioninventoryMapping->set($p_itemtype, 'pagecountercolorpages_copy','glpi_plugin_fusinvsnmp_printerlogs',
                                 'pages_color_copy',428,1428);
   $pFusioninventoryMapping->set($p_itemtype, 'pagecountertotalpages_fax','glpi_plugin_fusinvsnmp_printerlogs',
                                 'pages_total_fax',429,1429);
   $pFusioninventoryMapping->set($p_itemtype, 'pagecounterlargepages','glpi_plugin_fusinvsnmp_printerlogs',
                                 'pages_total_large',434,1434);
   $pFusioninventoryMapping->set($p_itemtype, 'ifPhysAddress','glpi_networkports','mac',58,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifName','glpi_networkports','name',57,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifaddr','glpi_networkports','ip',407,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifType','','',97,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifIndex','','',416,NULL);
           
   $p_itemtype = 'Computer';
   $pFusioninventoryMapping->set($p_itemtype, 'serial','','serial',13,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifPhysAddress','','mac',15,NULL);
   $pFusioninventoryMapping->set($p_itemtype, 'ifaddr','','ip',407,NULL);
   
   
   
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
                              "bigint(100) NOT NULL AUTO_INCREMENT");
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
      
/*
CREATE TABLE `glpi_plugin_tracker_printers_cartridges` (
  `object_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 * 
 * 
 * 
 * 
 * 
CREATE TABLE `glpi_plugin_fusinvsnmp_printercartridges` (
   `plugin_fusioninventory_mappings_id` int(11) NOT NULL DEFAULT '0',
   KEY `printers_id` (`printers_id`),
   KEY `plugin_fusioninventory_mappings_id` (`plugin_fusioninventory_mappings_id`),
   KEY `cartridges_id` (`cartridges_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;    
      
      
*/
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
      
      
   // ** glpi_plugin_fusinvsnmp_unknowndevices
      
      
   // ** glpi_plugin_fusinvsnmp_agentconfigs
      
      
   // ** glpi_plugin_fusinvsnmp_statediscoveries
      
      
      
      
      
      
   if (TableExists("glpi_plugin_tracker_computers")) {
      $DB->query("DROP TABLE glpi_plugin_tracker_computers");
   }  
      

   $config = new PluginFusioninventoryConfig();
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
   $config->updateConfigType($plugins_id, 'version', PLUGIN_FUSINVSNMP_VERSION);
}
?>