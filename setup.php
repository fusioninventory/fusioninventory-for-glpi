<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Copynetwork (C) 2003-2010 by the INDEPNET Development Team.

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
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

include_once ("includes.php");


// Init the hooks of fusinvsnmp
function plugin_init_fusinvsnmp() {
	global $PLUGIN_HOOKS,$CFG_GLPI,$LANG;

   // ##### 1. Stop if fusioninventory not activated #####

   $plugin = new Plugin;
   if (!$plugin->isActivated("fusioninventory")) {
      if (isset($_GET['id']) AND isset($_GET['action'])
            AND strstr($_SERVER['HTTP_REFERER'], "front/plugin.php")) {
         switch ($_GET['action']) {
            case 'activate':
               addMessageAfterRedirect($LANG['plugin_fusinvsnmp']['setup'][17]);
               break;
            case 'uninstall':
               addMessageAfterRedirect($LANG['plugin_fusinvsnmp']['setup'][18]);
               glpi_header($CFG_GLPI["root_doc"]."/front/plugin.php");
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
   Plugin::registerClass('PluginFusinvsnmpIPRange');
   Plugin::registerClass('PluginFusinvsnmpConfigSecurity');
   Plugin::registerClass('PluginFusinvsnmpNetworkPortLog');
   Plugin::registerClass('PluginFusinvsnmpAgentconfig');
   Plugin::registerClass('PluginFusinvsnmpNetworkport',
                         array('classname'=>'glpi_networkports'));
   Plugin::registerClass('PluginFusinvsnmpStateDiscovery');

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
//   if (!isset($_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms']['fusinvsnmp']
//                       [$LANG['plugin_fusinvsnmp']['title'][5]])) {
//      $_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms']['fusinvsnmp']
//               [$LANG['plugin_fusinvsnmp']['title'][5]] = array('class'=>'PluginFusinvsnmpConfigLogField',
//                                                                'submitbutton'=>'plugin_fusinvsnmp_configlogfield_set',
//                                                                'submitmethod'=>'putForm');
//   }

	//$PLUGIN_HOOKS['init_session']['fusioninventory'] = array('Profile', 'initSession');
   $PLUGIN_HOOKS['change_profile']['fusinvsnmp'] = PluginFusioninventoryProfile::changeprofile($moduleId,$a_plugin['shortname']);


	$PLUGIN_HOOKS['cron']['fusinvsnmp'] = 20*MINUTE_TIMESTAMP; // All 20 minutes

   $PLUGIN_HOOKS['add_javascript']['fusinvsnmp']="script.js";

	if (isset($_SESSION["glpiID"])) {

		if (haveRight("configuration", "r") || haveRight("profile", "w")) {// Config page
         $PluginFusioninventoryConfiguration = new PluginFusioninventoryConfiguration();
         $a_tabs = $PluginFusioninventoryConfiguration->defineTabs();
         $PLUGIN_HOOKS['config_page']['fusinvsnmp'] = '../fusioninventory/front/configuration.form.php?glpi_tab='.array_search($a_plugin['name'], $a_tabs);
      }

		// Define SQL table restriction of entity
		$CFG_GLPI["specif_entities_tables"][] = 'glpi_plugin_fusinvsnmp_discovery';
		$CFG_GLPI["specif_entities_tables"][] = 'glpi_plugin_fusinvsnmp_ipranges';
      $CFG_GLPI["specif_entities_tables"][] = 'glpi_plugin_fusinvsnmp_unknowndevices';

//		if(isset($_SESSION["glpi_plugin_fusinvsnmp_installed"]) && $_SESSION["glpi_plugin_fusinvsnmp_installed"]==1) {
      $plugin = new Plugin();
		if($plugin->isInstalled('fusinvsnmp')) {

			$PLUGIN_HOOKS['use_massive_action']['fusinvsnmp']=1;
//         $PLUGIN_HOOKS['pre_item_delete']['fusinvsnmp'] = 'plugin_pre_item_delete_fusinvsnmp';
//			$PLUGIN_HOOKS['pre_item_purge']['fusinvsnmp'] = '';
//			$PLUGIN_HOOKS['item_update']['fusinvsnmp'] = 'plugin_item_update_fusinvsnmp';
//         $PLUGIN_HOOKS['item_add']['fusinvsnmp'] = 'plugin_item_add_fusinvsnmp';

			$report_list = array();
         if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "reportprinter","r")) {
            $report_list["front/printerlog.php"] = $LANG['plugin_fusinvsnmp']["report"][1];
         }
         if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "reportnetworkequipment","r")) {
            $report_list["report/switch_ports.history.php"] = $LANG['plugin_fusinvsnmp']['menu'][5];
            $report_list["report/ports_date_connections.php"] = $LANG['plugin_fusinvsnmp']['menu'][6];
            $report_list["report/not_queried_recently.php"] = $LANG['plugin_fusinvsnmp']["report"][0];
         }
         $PLUGIN_HOOKS['reports']['fusinvsnmp'] = $report_list;

//			if (haveRight("models", "r") || haveRight("configsecurity", "r")) {
//			if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model", "r")
//             || PluginFusioninventoryProfile::haveRight("fusinvsnmp", "configsecurity", "r")) {
////				$PLUGIN_HOOKS['menu_entry']['fusinvsnmp'] = true;
//         }

         // Tabs for each type
         $PLUGIN_HOOKS['headings']['fusinvsnmp'] = 'plugin_get_headings_fusinvsnmp';
         $PLUGIN_HOOKS['headings_action']['fusinvsnmp'] = 'plugin_headings_actions_fusinvsnmp';

//         if (PluginFusinvsnmpAuth::haveRight("models","r")
         if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "model","r")
            OR PluginFusioninventoryProfile::haveRight("fusinvsnmp", "configsecurity","r")
            OR PluginFusioninventoryProfile::haveRight("fusinvsnmp", "iprange","r")
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


            if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "iprange","w")) {
//               $PLUGIN_HOOKS['submenu_entry']['fusinvsnmp']['add']['iprange'] = 'front/iprange.form.php?add=1';
               $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['iprange'] = '../fusinvsnmp/front/iprange.form.php?add=1';
               $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['iprange'] = '../fusinvsnmp/front/iprange.php';
            }
//            $PLUGIN_HOOKS['submenu_entry']['fusinvsnmp']['add']['constructdevice'] = 'front/construct_device.form.php?add=1';
//            $PLUGIN_HOOKS['submenu_entry']['fusinvsnmp']['search']['constructdevice'] = 'front/construct_device.php';

//            if (PluginFusioninventoryProfile::haveRight("fusinvsnmp", "configuration","r")) {
//               $PLUGIN_HOOKS['submenu_entry']['fusinvsnmp']['config'] = 'front/functionalities.form.php';
//            }
			}
//         $PLUGIN_HOOKS['submenu_entry']['fusinvsnmp']["<img  src='".GLPI_ROOT."/plugins/fusinvsnmp/pics/books.png' title='".$LANG['plugin_fusinvsnmp']['setup'][16]."' alt='".$LANG['plugin_fusinvsnmp']['setup'][16]."'>"] = 'front/documentation.php';

         // Fil ariane
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['models']['title'] = $LANG['plugin_fusinvsnmp']['model_info'][4];
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['models']['page']  = '/plugins/fusinvsnmp/front/model.php';

         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['configsecurity']['title'] = $LANG['plugin_fusinvsnmp']['model_info'][3];
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['configsecurity']['page']  = '/plugins/fusinvsnmp/front/configsecurity.php';

         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['iprange']['title'] = $LANG['plugin_fusinvsnmp']['menu'][2];
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['iprange']['page']  = '/plugins/fusinvsnmp/front/iprange.php';

         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['statediscovery']['title'] = $LANG['plugin_fusinvsnmp']['menu'][9];
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['statediscovery']['page']  = '/plugins/fusinvsnmp/front/statediscovery.php';

		}

	}
}

// Name and Version of the plugin
function plugin_version_fusinvsnmp() {
	return array('name'           => 'FusionInventory SNMP',
                'shortname'      => 'fusinvsnmp',
                'version'        => '2.3.0-1',
                'author'         =>'<a href="mailto:d.durieux@siprossii.com">David DURIEUX</a>
                                    & <a href="mailto:v.mazzoni@siprossii.com">Vincent MAZZONI</a>',
                'homepage'       =>'http://forge.fusioninventory.org/projects/pluginfusinvsnmp',
                'minGlpiVersion' => '0.78'// For compatibility / no install in version < 0.78
   );
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_fusinvsnmp_check_prerequisites() {
   global $LANG;
	if (GLPI_VERSION >= '0.78') {
      $plugin = new Plugin;
      if (!$plugin->isActivated("fusioninventory")) {
         return false;
      }
		return true;
   } else {
		echo $LANG['plugin_fusinvsnmp']['errors'][50];
   }
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