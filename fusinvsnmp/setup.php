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

define ("PLUGIN_FUSINVSNMP_VERSION","0.83+2.0");

include_once ("includes.php");

// Init the hooks of fusinvsnmp
function plugin_init_fusinvsnmp() {
	global $PLUGIN_HOOKS,$CFG_GLPI,$LANG;

   // ##### 1. Stop if fusioninventory not activated #####
   
   $PLUGIN_HOOKS['csrf_compliant']['fusinvsnmp'] = true;

   $plugin = new Plugin();
   if (!$plugin->isActivated("fusioninventory")) {
      $plugin->getFromDBbyDir("fusinvsnmp");
      // Check for uninstall
      if (isset($_GET['id']) 
            AND isset($_GET['action'])
            AND $_GET['id'] == $plugin->fields['id']
            AND strstr($_SERVER['HTTP_REFERER'], "front/plugin.php")) {
         switch ($_GET['action']) {
            case 'activate':
                Session::addMessageAfterRedirect($LANG['plugin_fusinvsnmp']['setup'][17]);
               break;
            case 'uninstall':
                Session::addMessageAfterRedirect($LANG['plugin_fusinvsnmp']['setup'][18]);
               Html::redirect($CFG_GLPI["root_doc"]."/front/plugin.php");
               break;
         }
      }
      return false;
   }

   // ##### 2. register class #####

   Plugin::registerClass('PluginFusinvsnmpConstructDevice');
   Plugin::registerClass('PluginFusinvsnmpModel');
   Plugin::registerClass('PluginFusinvsnmpNetworkEquipment');
   Plugin::registerClass('PluginFusinvsnmpPrinter');
   Plugin::registerClass('PluginFusinvsnmpPrinterCartridge');
   Plugin::registerClass('PluginFusinvsnmpConfigSecurity');
   Plugin::registerClass('PluginFusinvsnmpNetworkPortLog');
   Plugin::registerClass('PluginFusinvsnmpAgentconfig');
   Plugin::registerClass('PluginFusinvsnmpNetworkport',
                         array('classname'=>'glpi_networkports'));
   Plugin::registerClass('PluginFusinvsnmpStateDiscovery');
   Plugin::registerClass('PluginFusinvsnmpPrinterLogReport');
   Plugin::registerClass('PluginFusinvsnmpConstructdevice_User',
              array('addtabon' => array('User')));
      
   $CFG_GLPI['glpitablesitemtype']["PluginFusinvsnmpPrinterLogReport"] = "glpi_plugin_fusinvsnmp_printers";

   // ##### 3. get informations of the plugin #####

   $a_plugin = plugin_version_fusinvsnmp();
   $moduleId = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);

   // ##### 4. Set in session module_id #####

   $_SESSION["plugin_".$a_plugin['shortname']."_moduleid"] = $moduleId;

   // ##### 5. Set in session XMLtags of methods #####

   $_SESSION['glpi_plugin_fusioninventory']['xmltags']['SNMPQUERY'] = 'PluginFusinvsnmpCommunicationSNMPQuery';
   $_SESSION['glpi_plugin_fusioninventory']['xmltags']['NETDISCOVERY'] = 'PluginFusinvsnmpCommunicationNetDiscovery';


   
   


   if (!isset($_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms']['fusinvsnmp'][$LANG['plugin_fusinvsnmp']['title'][0]])) {
      $_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms']['fusinvsnmp'][$LANG['plugin_fusinvsnmp']['title'][0]] = array('class'=>'PluginFusinvSNMPConfig',
                                                                'submitbutton'=>'plugin_fusinvsnmp_config_set',
                                                                'submitmethod'=>'putForm');
   }

   $PLUGIN_HOOKS['change_profile']['fusinvsnmp'] = PluginFusioninventoryProfile::changeprofile($moduleId,$a_plugin['shortname']);

	$PLUGIN_HOOKS['cron']['fusinvsnmp'] = 20*MINUTE_TIMESTAMP; // All 20 minutes

   $PLUGIN_HOOKS['add_javascript']['fusinvsnmp']="script.js";
   
	if (isset($_SESSION["glpiID"])) {

		if (Session::haveRight("configuration", "r") || Session::haveRight("profile", "w")) {// Config page
         $pfConfiguration = new PluginFusioninventoryConfig();
         $a_tabs = $pfConfiguration->defineTabs();
         $PLUGIN_HOOKS['config_page']['fusinvsnmp'] = '../fusioninventory/front/config.form.php?itemtype=pluginfusioninventoryconfig&glpi_tab='.array_search($a_plugin['name'], $a_tabs);
      }

		// Define SQL table restriction of entity
		$CFG_GLPI["specif_entities_tables"][] = 'glpi_plugin_fusinvsnmp_discovery';
		//$CFG_GLPI["specif_entities_tables"][] = 'glpi_plugin_fusinvsnmp_ipranges';
      $CFG_GLPI["specif_entities_tables"][] = 'glpi_plugin_fusinvsnmp_unknowndevices';

//		if(isset($_SESSION["glpi_plugin_fusinvsnmp_installed"]) && $_SESSION["glpi_plugin_fusinvsnmp_installed"]==1) {
      $plugin = new Plugin();
		if($plugin->isInstalled('fusinvsnmp')) {

			$PLUGIN_HOOKS['use_massive_action']['fusinvsnmp']=1;

         $PLUGIN_HOOKS['item_add']['fusinvsnmp'] = array('NetworkPort_NetworkPort'=>'plugin_item_add_fusinvsnmp');
         $PLUGIN_HOOKS['pre_item_purge']['fusinvsnmp'] = array('NetworkPort_NetworkPort'=>'plugin_pre_item_purge_fusinvsnmp');

         $PLUGIN_HOOKS['item_purge']['fusinvsnmp'] = array('NetworkEquipment' =>'plugin_item_purge_fusinvsnmp',
                                                           'Printer' =>'plugin_item_purge_fusinvsnmp',
                                                           'PluginFusioninventoryUnknownDevice' =>'plugin_item_purge_fusinvsnmp');

			$report_list = array();
         if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "reportprinter","r")) {
            $report_list["front/printerlogreport.php"] = $LANG['plugin_fusinvsnmp']["report"][1];
         }
         if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "reportnetworkequipment","r")) {
            $report_list["report/switch_ports.history.php"] = $LANG['plugin_fusinvsnmp']['menu'][5];
            $report_list["report/ports_date_connections.php"] = $LANG['plugin_fusinvsnmp']['menu'][6];
            $report_list["report/not_queried_recently.php"] = $LANG['plugin_fusinvsnmp']["report"][0];
         }
         $PLUGIN_HOOKS['reports']['fusinvsnmp'] = $report_list;


         // Tabs for each type
         $PLUGIN_HOOKS['headings']['fusinvsnmp'] = 'plugin_get_headings_fusinvsnmp';
         $PLUGIN_HOOKS['headings_action']['fusinvsnmp'] = 'plugin_headings_actions_fusinvsnmp';

         if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","r")
            OR PluginFusioninventoryProfile::haveRight("fusinvsnmp", "configsecurity","r")
            //OR PluginFusioninventoryProfile::haveRight("fusinvsnmp", "iprange","r")
            OR PluginFusioninventoryProfile::haveRight("fusinvsnmp", "agents","r")
            OR PluginFusioninventoryProfile::haveRight("fusinvsnmp", "agentsprocesses","r")
            OR PluginFusioninventoryProfile::haveRight("fusinvsnmp", "unknowndevices","r")
            OR PluginFusioninventoryProfile::haveRight("fusinvsnmp", "reports","r")
            ) {

//            $PLUGIN_HOOKS['menu_entry']['fusinvsnmp'] = true;
//            if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","w")) {
               $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['models'] = '../fusinvsnmp/front/model.form.php?add=1';
               $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['models'] = '../fusinvsnmp/front/model.php';
//            }
            if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "configsecurity","w")) {
               $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['configsecurity'] = '../fusinvsnmp/front/configsecurity.form.php?add=1';
               $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['configsecurity'] = '../fusinvsnmp/front/configsecurity.php';
            }

            $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['rulenetdiscovery']
               = '../fusinvsnmp/front/rulenetdiscovery.form.php';
            $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['rulenetdiscovery']
               = '../fusinvsnmp/front/rulenetdiscovery.php';

            $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['rulesnmpinventory']
               = '../fusinvsnmp/front/ruleinventory.form.php';
            $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['rulesnmpinventory']
               = '../fusinvsnmp/front/ruleinventory.php';

			}

         // Fil ariane
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['models']['title'] = $LANG['plugin_fusinvsnmp']['model_info'][4];
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['models']['page']  = '/plugins/fusinvsnmp/front/model.php';

         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['configsecurity']['title'] = $LANG['plugin_fusinvsnmp']['model_info'][3];
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['configsecurity']['page']  = '/plugins/fusinvsnmp/front/configsecurity.php';

         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['statediscovery']['title'] = $LANG['plugin_fusinvsnmp']['menu'][9];
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['statediscovery']['page']  = '/plugins/fusinvsnmp/front/statediscovery.php';

		}

	}
}



// Name and Version of the plugin
function plugin_version_fusinvsnmp() {
	return array('name'           => 'FusionInventory SNMP',
                'shortname'      => 'fusinvsnmp',
                'version'        => PLUGIN_FUSINVSNMP_VERSION,
                'license'        => 'AGPLv3+',
                'author'         =>'<a href="mailto:d.durieux@siprossii.com">David DURIEUX</a>
                                    & FusionInventory team',
                'homepage'       =>'http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/',
                'minGlpiVersion' => '0.83.3'// For compatibility / no install in version < 0.78
   );
}



// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_fusinvsnmp_check_prerequisites() {
   global $LANG;
   
   if (version_compare(GLPI_VERSION,'0.83.3','lt') || version_compare(GLPI_VERSION,'0.84','ge')) {
      echo $LANG['plugin_fusioninventory']['errors'][50];
      return false;
   }
   $plugin = new Plugin();
   if (!$plugin->isActivated("fusioninventory")) {
      return false;
   }
   return true;
}



function plugin_fusinvsnmp_check_config() {
	return true;
}



function plugin_fusinvsnmp_haveTypeRight($type,$right) {
	switch ($type) {
		case 'PluginFusinvsnmpConfigSecurity' :
//			return PluginFusinvsnmpAuth::haveRight("configsecurity",$right);
			return PluginFusioninventoryProfile::haveRight("fusinvsnmp", "configsecurity",$right);
			break;
	}
	return true;
}

?>