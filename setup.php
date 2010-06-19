<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2010 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
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

   Plugin::registerClass('PluginFusinvsnmpConstructDevice');
   Plugin::registerClass('PluginFusinvsnmpSNMPModel');
   Plugin::registerClass('PluginFusinvsnmpNetworkEquipment');
   Plugin::registerClass('PluginFusinvsnmpPrinter');
   Plugin::registerClass('PluginFusinvsnmpIPRange');
   Plugin::registerClass('PluginFusinvsnmpConfigSNMPSecurity');
   Plugin::registerClass('PluginFusinvsnmpNetworkPortLog');
   Plugin::registerClass('PluginFusinvsnmpUnknownDevice');
   Plugin::registerClass('PluginFusinvsnmpNetworkport2',
                         array('classname'=>'glpi_networkports'));

	//array_push($CFG_GLPI["specif_entities_tables"],"glpi_plugin_fusinvsnmp_errors");

	$PLUGIN_HOOKS['init_session']['fusinvsnmp'] = 'plugin_fusinvsnmp_initSession';
	$PLUGIN_HOOKS['change_profile']['fusinvsnmp'] = 'plugin_fusinvsnmp_changeprofile';

	$PLUGIN_HOOKS['cron']['fusinvsnmp'] = 20*MINUTE_TIMESTAMP; // All 20 minutes

   $PLUGIN_HOOKS['add_javascript']['fusinvsnmp']="script.js";

	if (isset($_SESSION["glpiID"])) {

		if (haveRight("configuration", "r") || haveRight("profile", "w")) {// Config page
			$PLUGIN_HOOKS['config_page']['fusinvsnmp'] = 'front/functionalities.form.php';
      }

		// Define SQL table restriction of entity
		$CFG_GLPI["specif_entities_tables"][] = 'glpi_plugin_fusinvsnmp_discovery';
		$CFG_GLPI["specif_entities_tables"][] = 'glpi_plugin_fusinvsnmp_ipranges';
      $CFG_GLPI["specif_entities_tables"][] = 'glpi_plugin_fusinvsnmp_unknowndevices';

		if(isset($_SESSION["glpi_plugin_fusinvsnmp_installed"]) && $_SESSION["glpi_plugin_fusinvsnmp_installed"]==1) {

			$PLUGIN_HOOKS['use_massive_action']['fusinvsnmp']=1;
         $PLUGIN_HOOKS['pre_item_delete']['fusinvsnmp'] = 'plugin_pre_item_delete_fusinvsnmp';
			$PLUGIN_HOOKS['pre_item_purge']['fusinvsnmp'] = 'plugin_pre_item_purge_fusinvsnmp';
			$PLUGIN_HOOKS['item_update']['fusinvsnmp'] = 'plugin_item_update_fusinvsnmp';
         $PLUGIN_HOOKS['item_add']['fusinvsnmp'] = 'plugin_item_add_fusinvsnmp';

			$report_list = array();
         $report_list["report/switch_ports.history.php"] = "Historique des ports de switchs";
         $report_list["report/ports_date_connections.php"] = "Ports de switchs non connectés depuis xx mois";
			$PLUGIN_HOOKS['reports']['fusinvsnmp'] = $report_list;

			if (haveRight("snmp_models", "r") || haveRight("snmp_authentication", "r")) {
				$PLUGIN_HOOKS['menu_entry']['fusinvsnmp'] = true;
         }

         // Tabs for each type
         $PLUGIN_HOOKS['headings']['fusinvsnmp'] = 'plugin_get_headings_fusinvsnmp';
         $PLUGIN_HOOKS['headings_action']['fusinvsnmp'] = 'plugin_headings_actions_fusinvsnmp';

         if (PluginFusinvsnmpAuth::haveRight("snmp_models","r")
            OR PluginFusinvsnmpAuth::haveRight("snmp_authentication","r")
            OR PluginFusinvsnmpAuth::haveRight("iprange","r")
            OR PluginFusinvsnmpAuth::haveRight("agents","r")
            OR PluginFusinvsnmpAuth::haveRight("agentsprocesses","r")
            OR PluginFusinvsnmpAuth::haveRight("unknowndevices","r")
            OR PluginFusinvsnmpAuth::haveRight("reports","r")
            ) {

            $PLUGIN_HOOKS['menu_entry']['fusinvsnmp'] = true;
            if (PluginFusinvsnmpAuth::haveRight("snmp_models","w")) {
               $PLUGIN_HOOKS['submenu_entry']['fusinvsnmp']['add']['models'] = 'front/snmpmodel.form.php?add=1';
               $PLUGIN_HOOKS['submenu_entry']['fusinvsnmp']['search']['models'] = 'front/snmpmodel.php';
            }
            if (PluginFusinvsnmpAuth::haveRight("snmp_authentication","w")) {
               $PLUGIN_HOOKS['submenu_entry']['fusinvsnmp']['add']['snmp_auth'] = 'front/configsnmpsecurity.form.php?add=1';
               $PLUGIN_HOOKS['submenu_entry']['fusinvsnmp']['search']['snmp_auth'] = 'front/configsnmpsecurity.php';
            }
            if (PluginFusinvsnmpAuth::haveRight("agents","w")) {
               $PLUGIN_HOOKS['submenu_entry']['fusinvsnmp']['add']['agents'] = 'front/agent.form.php?add=1';
               $PLUGIN_HOOKS['submenu_entry']['fusinvsnmp']['search']['agents'] = 'front/agent.php';
            }

            if (PluginFusinvsnmpAuth::haveRight("iprange","w")) {
               $PLUGIN_HOOKS['submenu_entry']['fusinvsnmp']['add']['iprange'] = 'front/iprange.form.php?add=1';
               $PLUGIN_HOOKS['submenu_entry']['fusinvsnmp']['search']['iprange'] = 'front/iprange.php';
            }

            $PLUGIN_HOOKS['submenu_entry']['fusinvsnmp']['add']['constructdevice'] = 'front/construct_device.form.php?add=1';
            $PLUGIN_HOOKS['submenu_entry']['fusinvsnmp']['search']['constructdevice'] = 'front/construct_device.php';

            if (PluginFusinvsnmpAuth::haveRight("configuration","r")) {
               $PLUGIN_HOOKS['submenu_entry']['fusinvsnmp']['config'] = 'front/functionalities.form.php';
            }
			}
         $PLUGIN_HOOKS['submenu_entry']['fusinvsnmp']["<img  src='".GLPI_ROOT."/plugins/fusinvsnmp/pics/books.png' title='".$LANG['plugin_fusinvsnmp']["setup"][16]."' alt='".$LANG['plugin_fusinvsnmp']["setup"][16]."'>"] = 'front/documentation.php';
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
		return true;
   } else {
		echo $LANG['plugin_fusinvsnmp']["errors"][50];
   }
}



function plugin_fusinvsnmp_check_config() {
	return true;
}



function plugin_fusinvsnmp_haveTypeRight($type,$right) {
	switch ($type) {
		case 'PluginFusinvsnmpConfigSNMPSecurity' :
			return PluginFusinvsnmpAuth::haveRight("snmp_authentication",$right);
			break;
	}
	return true;
}

?>