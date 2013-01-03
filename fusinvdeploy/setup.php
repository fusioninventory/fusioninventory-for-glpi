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

include_once(GLPI_ROOT."/inc/includes.php");

define ("PLUGIN_FUSINVDEPLOY_VERSION","0.84+1.0");

// Init the hooks of fusinvdeploy
function plugin_init_fusinvdeploy() {
   global $PLUGIN_HOOKS,$CFG_GLPI;

   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/config.class.php");
   require_once(GLPI_ROOT . "/plugins/fusioninventory/inc/profile.class.php");
   // ##### 1. Stop if fusioninventory not activated #####
   $PLUGIN_HOOKS['csrf_compliant']['fusinvdeploy'] = true;

   $plugin = new Plugin();
   if (!$plugin->isActivated("fusioninventory")) {
      if (isset($_GET['id']) AND isset($_GET['action'])
            AND strstr($_SERVER['HTTP_REFERER'], "front/plugin.php")) {
         switch ($_GET['action']) {
            case 'activate':
                Session::addMessageAfterRedirect(__('Plugin FusionInventory DEPLOY needs FusionInventory plugin activated before activation.'));

               break;
            case 'uninstall':
                Session::addMessageAfterRedirect(__('Plugin FusionInventory DEPLOY needs FusionInventory plugin activated before uninstall.'));

               Html::redirect($CFG_GLPI["root_doc"]."/front/plugin.php");
               break;
         }
      }
      return false;
   }

   if (!$plugin->isActivated("fusinvinventory")) {
      if (isset($_GET['id']) AND isset($_GET['action'])
            AND strstr($_SERVER['HTTP_REFERER'], "front/plugin.php")) {
         switch ($_GET['action']) {
            case 'activate':
                Session::addMessageAfterRedirect(__('Plugin FusionInventory DEPLOY needs FusionInventory INVENTORY plugin installed before activation.'));

               break;
            case 'uninstall':
                Session::addMessageAfterRedirect(__('Plugin FusionInventory DEPLOY needs FusionInventory INVENTORY plugin installed before uninstall.'));

               Html::redirect($CFG_GLPI["root_doc"]."/front/plugin.php");
               break;
         }
      }
      return false;
   }

   // ##### 2. register classes #####

   Plugin::registerClass('PluginFusinvdeployReport',
              array('addtabon' => array('PluginFusioninventoryTask')));


   // ##### 3. get informations of the plugin #####

   $a_plugin = plugin_version_fusinvdeploy();
   $moduleId = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);

   // ##### 4. Set in session module_id #####

   $_SESSION["plugin_".$a_plugin['shortname']."_moduleid"] = $moduleId;

   if (!isset($_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms']
                           ['fusinvdeploy'][__('1')])) {

      $_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms']
                           ['fusinvdeploy'][__('1')] =

                              array('class'        => 'PluginFusinvdeployConfig',
                                    'submitbutton' => 'plugin_fusinvdeploy_config_set',
                                    'submitmethod' => 'putForm');
   }

   if (isset($_SESSION["glpiID"])) {

      if (Session::haveRight("configuration", "r") || Session::haveRight("profile", "w")) {// Config page
         $PluginFusioninventoryConfiguration = new PluginFusioninventoryConfig();
         $a_tabs = $PluginFusioninventoryConfiguration->defineTabs();
         $PLUGIN_HOOKS['config_page']['fusinvdeploy'] = "../fusioninventory/front/config.form.php"
            ."?itemtype=pluginfusioninventoryconfig"
            ."&glpi_tab=".array_search($a_plugin['name'], $a_tabs);
      }
   }


   # These will be removed in 0.84
   if (isset($_GET['id']) AND isset($_GET['action']) && $_GET['action'] == 'activate') {
      if (!$DB->query("DROP VIEW IF EXISTS `glpi_plugin_fusinvdeploy_tasks`;")
            ||
            !$DB->query("CREATE VIEW `glpi_plugin_fusinvdeploy_tasks` ".
            "AS SELECT * FROM `glpi_plugin_fusioninventory_tasks`;")
            ||
            !$DB->query("DROP VIEW IF EXISTS `glpi_plugin_fusinvdeploy_taskjobs`;")
            ||
            !$DB->query("CREATE VIEW `glpi_plugin_fusinvdeploy_taskjobs` ".
               "AS SELECT `id`, ".
               "`plugin_fusioninventory_tasks_id` AS `plugin_fusinvdeploy_tasks_id`, ".
               "`entities_id`, `name`, `date_creation`, `retry_nb`, ".
               "`retry_time`, `plugins_id`, `method`, `definition`, ".
               "`action`, `comment`, `users_id`, `status`, ".
               "`rescheduled_taskjob_id`, `statuscomments`, ".
               "`periodicity_count`, `periodicity_type`, `execution_id` ".
               "FROM `glpi_plugin_fusioninventory_taskjobs`;")) {
            Session::addMessageAfterRedirect("SQL VIEW creation failure!");
            Html::redirect($CFG_GLPI["root_doc"]."/front/plugin.php");
            return false;
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
      __('Package management');

   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['packages']['page'] =
      '/plugins/fusinvdeploy/front/package.php';

   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['mirror']['title'] =
      __('Mirror servers');

   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['mirror']['page'] =
      '/plugins/fusinvdeploy/front/mirror.php';

   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['task']['title'] =
      __('Deployment tasks');

   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['task']['page'] =
      '/plugins/fusinvdeploy/front/task.php';

   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['group']['title'] =
      __('Groups of computers');

   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['group']['page'] =
      '/plugins/fusinvdeploy/front/group.php';

   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['deploy']['title'] =
      __('Deployment status');

   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['deploy']['page'] =
      '/plugins/fusinvdeploy/front/deploystate.php';

   $PLUGIN_HOOKS['add_css']['fusinvdeploy'] = "css/style.css";

   // Massive Action definition (for duplicate packages)
   $PLUGIN_HOOKS['use_massive_action']['fusinvdeploy'] = 1;
}

// Name and Version of the plugin
function plugin_version_fusinvdeploy() {

   return array(
      'name'           => __('FusionInventory DEPLOY'),

      'shortname'      => 'fusinvdeploy',
      'version'        => PLUGIN_FUSINVDEPLOY_VERSION,
      'license'        => 'AGPLv3+',
      'author'         => "<a href='http://www.teclib.com'>TECLIB'</a> and the FusionInventory team",
      'homepage'       => 'http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/',
      'minGlpiVersion' => '0.84'
   );
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_fusinvdeploy_check_prerequisites() {

   if (version_compare(GLPI_VERSION,'0.84','lt') || version_compare(GLPI_VERSION,'0.85','ge')) {
      echo __('Your GLPI version not compatible, require 0.83.3');

   } else {
      $plugin = new Plugin;
      if (!$plugin->isInstalled("fusioninventory")) {
        return false;
      }
      if (!$plugin->isActivated("fusioninventory")) {
         print __('Plugin FusionInventory DEPLOY needs FusionInventory plugin activated before activation.')."<br />\n";
         return false;
      }
      if (!$plugin->isActivated("fusinvinventory")) {
         print __('Plugin FusionInventory DEPLOY needs FusionInventory INVENTORY plugin installed before activation.')."<br />\n";
         return false;
      }
      return true;
   }
}

function plugin_fusinvdeploy_check_config() {
   return true;
}

?>
