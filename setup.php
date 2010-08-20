<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

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

// Init the hooks of fusioninventory
function plugin_init_fusioninventory() {
	global $PLUGIN_HOOKS,$CFG_GLPI,$LANG;

   Plugin::registerClass('PluginFusioninventoryAgent');
   Plugin::registerClass('PluginFusioninventoryConfig');
   Plugin::registerClass('PluginFusioninventoryTask');
   Plugin::registerClass('PluginFusioninventoryTaskjob');

   CronTask::Register('fusioninventory', 'tasks', '300', array('mode'=>1, 'allowmode'=>3, 'logs_lifetime'=>30));

   $a_plugin = plugin_version_fusioninventory();
   $_SESSION["plugin_".$a_plugin['shortname']."_moduleid"] = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);

	//$PLUGIN_HOOKS['init_session']['fusioninventory'] = array('Profile', 'initSession');
   $PLUGIN_HOOKS['change_profile']['fusioninventory'] =
      PluginFusioninventoryProfile::changeprofile(
        PluginFusioninventoryModule::getModuleId($a_plugin['shortname']));

	$PLUGIN_HOOKS['cron']['fusioninventory'] = 20*MINUTE_TIMESTAMP; // All 20 minutes

   $PLUGIN_HOOKS['add_javascript']['fusioninventory']="script.js";

	if (isset($_SESSION["glpiID"])) {

//		if (haveRight("configuration", "r")) {// Config page
//		if (PluginFusioninventoryProfile::haveRight("fusioninventory", "configuration", "r")) {// Config page
//			$PLUGIN_HOOKS['config_page']['fusioninventory'] = 'front/functionalities.form.php';
//      }
      $PLUGIN_HOOKS['use_massive_action']['fusioninventory']=1;
      $PLUGIN_HOOKS['pre_item_delete']['fusioninventory'] = 'plugin_pre_item_delete_fusioninventory';
      $PLUGIN_HOOKS['pre_item_purge']['fusioninventory'] = 'plugin_pre_item_purge_fusioninventory';
      $PLUGIN_HOOKS['item_update']['fusioninventory'] = 'plugin_item_update_fusioninventory';
      $PLUGIN_HOOKS['item_add']['fusioninventory'] = 'plugin_item_add_fusioninventory';

      $PLUGIN_HOOKS['menu_entry']['fusioninventory'] = true;

      // Tabs for each type
      $PLUGIN_HOOKS['headings']['fusioninventory'] = 'plugin_get_headings_fusioninventory';
      $PLUGIN_HOOKS['headings_action']['fusioninventory'] = 'plugin_headings_actions_fusioninventory';


      // Icons add, search...
      $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['tasks'] = 'front/task.form.php?add=1';
      $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['tasks'] = 'front/task.php';


      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "agents","r")
         OR PluginFusioninventoryProfile::haveRight("fusioninventory", "agentsprocesses","r")
         ) {

         if (PluginFusioninventoryProfile::haveRight("fusioninventory", "agents","w")) {
//               $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['agents'] = 'front/agent.form.php?add=1';
            $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['agents'] = 'front/agent.php';
         }

//         if (PluginFusioninventoryProfile::haveRight($_SESSION["plugin_".$a_plugin['shortname']."_moduleid"], "configuration","r")) {
         if (PluginFusioninventoryProfile::haveRight("fusioninventory", "configuration", "r")) {// Config page
            $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['config'] = 'front/configuration.form.php';
         }
//         }
      }
      $PLUGIN_HOOKS['submenu_entry']['fusioninventory']["<img  src='".GLPI_ROOT."/plugins/fusioninventory/pics/books.png' title='".$LANG['plugin_fusioninventory']["setup"][16]."' alt='".$LANG['plugin_fusioninventory']["setup"][16]."'>"] = 'front/documentation.php';

      // Fil ariane
      $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['menu']['title'] = $LANG['plugin_fusioninventory']["menu"][3];
      $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['menu']['page']  = '/plugins/fusioninventory/front/menu.php';

      $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['tasks']['title'] = $LANG['plugin_fusioninventory']["task"][1];
      $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['tasks']['page']  = '/plugins/fusioninventory/front/task.php';

      $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['agents']['title'] = $LANG['plugin_fusioninventory']["menu"][1];
      $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['agents']['page']  = '/plugins/fusioninventory/front/agent.php';
	}
}

// Name and Version of the plugin
function plugin_version_fusioninventory() {
	return array('name'           => 'FusionInventory',
                'shortname'      => 'fusioninventory',
                'version'        => '2.3.0',
                'author'         =>'<a href="mailto:d.durieux@siprossii.com">David DURIEUX</a>
                                    & <a href="mailto:v.mazzoni@siprossii.com">Vincent MAZZONI</a>',
                'homepage'       =>'http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/',
                'minGlpiVersion' => '0.78'// For compatibility / no install in version < 0.78
   );
}


// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_fusioninventory_check_prerequisites() {
   global $LANG;
	if (GLPI_VERSION >= '0.78') {
		return true;
   } else {
		echo $LANG['plugin_fusioninventory']["errors"][50];
   }
}



function plugin_fusioninventory_check_config() {
	return true;
}



function plugin_fusioninventory_haveTypeRight($type,$right) {
//	switch ($type) {
//		case 'PluginFusioninventoryConfigSNMPSecurity' :
//			return PluginFusioninventoryProfile::haveRight("snmp_authentication",$right);
//			break;
//	}
	return true;
}

?>
