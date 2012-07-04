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
   @co-author Alexandre Delaunay
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */
//include_once ("includes.php");

define ("PLUGIN_FUSINVDEPLOY_VERSION","0.80+1.5");

// Init the hooks of fusinvdeploy
function plugin_init_fusinvdeploy() {
   global $PLUGIN_HOOKS,$LANG;

   // ##### 1. Stop if fusioninventory not activated #####

   $plugin = new Plugin;
   if (!$plugin->isActivated("fusioninventory")) {
//      if (isset($_GET['id'])
//         && isset($_GET['action'])
//            && strstr($_SERVER['HTTP_REFERER'], "front/plugin.php")) {
//         switch ($_GET['action']) {
//            case 'activate':
//               addMessageAfterRedirect($LANG['plugin_fusinvdeploy']["setup"][17]);
//               break;
//            case 'uninstall':
//               addMessageAfterRedirect($LANG['plugin_fusinvdeploy']["setup"][18]);
//               glpi_header($CFG_GLPI["root_doc"]."/front/plugin.php");
//               break;
//         }
//      }
      return false;
   }
   if (!$plugin->isActivated("fusinvinventory")) {
//      if (isset($_GET['id'])
//         && isset($_GET['action'])
//            && strstr($_SERVER['HTTP_REFERER'], "front/plugin.php")) {
//         switch ($_GET['action']) {
//            case 'activate':
//               addMessageAfterRedirect($LANG['plugin_fusinvdeploy']["setup"][21]);
//               break;
//         }
//      }
      return false;
   }
   if (!$plugin->isInstalled("webservices")) {
//      if (isset($_GET['id'])
//         && isset($_GET['action'])
//            && strstr($_SERVER['HTTP_REFERER'], "front/plugin.php")) {
//         switch ($_GET['action']) {
//            case 'activate':
//               addMessageAfterRedirect($LANG['plugin_fusinvdeploy']["setup"][19]);
//               break;
//            case 'uninstall':
//               addMessageAfterRedirect($LANG['plugin_fusinvdeploy']["setup"][20]);
//               glpi_header($CFG_GLPI["root_doc"]."/front/plugin.php");
//               break;
//         }
//      }
      return false;
   }

   // ##### 2. register classes #####

/*
   Plugin::registerClass('PluginFusinvDeployConfig');
   Plugin::registerClass('PluginFusinvdeployJob');
   Plugin::registerClass('PluginFusinvdeployPackage');
   Plugin::registerClass('PluginFusinvdeployPackage');
   Plugin::registerClass('PluginFusinvdeployInstall');
   Plugin::registerClass('PluginFusinvdeployUninstall');
*/
   // ##### 3. get informations of the plugin #####

   $a_plugin = plugin_version_fusinvdeploy();
   if (!class_exists('PluginFusioninventoryModule')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/module.class.php");
   }
   $moduleId = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);

   // ##### 4. Set in session module_id #####

   $_SESSION["plugin_".$a_plugin['shortname']."_moduleid"] = $moduleId;


   if (!isset($_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms']['fusinvdeploy'][$LANG['plugin_fusinvdeploy']["title"][0]])) {
      $_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms']['fusinvdeploy'][$LANG['plugin_fusinvdeploy']["title"][0]] =
                                             array('class'        => 'PluginFusinvdeployConfig',
                                                   'submitbutton' => 'plugin_fusinvdeploy_config_set',
                                                   'submitmethod' => 'putForm');
   }

   if (isset($_SESSION["glpiID"])) {

      if (haveRight("configuration", "r") || haveRight("profile", "w")) {// Config page
         if (!class_exists('PluginFusioninventoryConfiguration')) { // if plugin is unactive
            include(GLPI_ROOT . "/plugins/fusioninventory/inc/configuration.class.php");
         }
         $PluginFusioninventoryConfiguration = new PluginFusioninventoryConfiguration();
         $a_tabs = $PluginFusioninventoryConfiguration->defineTabs();
         $PLUGIN_HOOKS['config_page']['fusinvdeploy'] = '../fusioninventory/front/configuration.form.php?glpi_tab='.array_search($a_plugin['name'], $a_tabs);
      }
	}

   if (!class_exists('PluginFusioninventoryProfile')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusioninventory/inc/profile.class.php");
   }
   $PLUGIN_HOOKS['change_profile']['fusinvdeploy'] =
      PluginFusioninventoryProfile::changeprofile($moduleId,$a_plugin['shortname']);

   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['packages'] =
      '../fusinvdeploy/front/package.form.php?add=1';
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['packages'] =
      '../fusinvdeploy/front/package.php';
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['mirror'] =
      '../fusinvdeploy/front/mirror.form.php?add=1';
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['mirror'] =
      '../fusinvdeploy/front/mirror.php';
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['task'] =
      '../fusinvdeploy/front/task.form.php?add=1';
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['task'] =
      '../fusinvdeploy/front/task.php';
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['group'] =
      '../fusinvdeploy/front/group.form.php?add=1';
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['group'] =
      '../fusinvdeploy/front/group.php';

	// Breadcrumbs
	$PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['packages']['title'] = 
		$LANG['plugin_fusinvdeploy']['menu'][1];
	$PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['packages']['page'] = 
		'/plugins/fusinvdeploy/front/package.php';

	$PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['mirror']['title'] = 
		$LANG['plugin_fusinvdeploy']['menu'][2];
	$PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['mirror']['page'] = 
		'/plugins/fusinvdeploy/front/mirror.php';

	$PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['task']['title'] = 
		$LANG['plugin_fusinvdeploy']['menu'][3];
	$PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['task']['page'] = 
		'/plugins/fusinvdeploy/front/task.php';

	$PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['group']['title'] = 
		$LANG['plugin_fusinvdeploy']['menu'][4];
	$PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['group']['page'] = 
		'/plugins/fusinvdeploy/front/group.php';

	$PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['deploy']['title'] = 
		$LANG['plugin_fusinvdeploy']['menu'][5];
	$PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['deploy']['page'] = 
		'/plugins/fusinvdeploy/front/deploystate.php';

   $PLUGIN_HOOKS['add_css']['fusinvdeploy'] = "css/style.css";

   // Massive Action definition (for duplicate packages)
   $PLUGIN_HOOKS['use_massive_action']['fusinvdeploy'] = 1;
}

// Name and Version of the plugin
function plugin_version_fusinvdeploy() {
   global $LANG;
   return array('name'           => $LANG['plugin_fusinvdeploy']['title'][0],
                'shortname'      => 'fusinvdeploy',
                'version'        => PLUGIN_FUSINVDEPLOY_VERSION,
                'author'         => "<a href='http://www.teclib.com'>TECLIB'</a> and the FusionInventory team",
                'homepage'       => 'http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/',
                'minGlpiVersion' => '0.78' // For compatibility / no install in version < 0.78
   );
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_fusinvdeploy_check_prerequisites() {
   global $LANG;
   if (version_compare('0.80',GLPI_VERSION) < 0) {
      $plugin = new Plugin;
      if (!$plugin->isInstalled("fusioninventory")) {
        return false;
      }
      if (!$plugin->isActivated("fusioninventory")) {
         print $LANG['plugin_fusinvdeploy']["setup"][17]."<br />\n";
         return false;
      }
      if (!$plugin->isActivated("fusinvinventory")) {
         print $LANG['plugin_fusinvdeploy']["setup"][21]."<br />\n";
         return false;
      }
      if (!$plugin->isInstalled("webservices")) {
         print $LANG['plugin_fusinvdeploy']["setup"][19]."<br />\n";
         return false;
      } else {
         //cheeck version of webservice
         $plugin = new Plugin;
         $tmp = $plugin->find("directory = 'webservices'");
         $webservices_plugin = array_pop($tmp);
         if (version_compare($webservices_plugin['version'], '1.2.0') < 0) {
            print $LANG['plugin_fusinvdeploy']["setup"][19]."<br />\n";
            return false;
         }
      }
      return true;
   } else {
      echo $LANG['plugin_fusinvdeploy']["errors"][50];
   }
}

function plugin_fusinvdeploy_check_config() {
   return true;
}

?>