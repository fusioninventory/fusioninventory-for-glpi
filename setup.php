<?php

/*
 * @version $Id$
 -------------------------------------------------------------------------
 FusionInventory
 Copyright (C) 2003-2010 by the INDEPNET Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org/
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

include_once ("includes.php");

// Init the hooks of fusinvdeploy
function plugin_init_fusinvinventory() {
   global $PLUGIN_HOOKS,$CFG_GLPI,$LANG;

   // ##### 1. Stop if fusioninventory not activated #####

   $plugin = new Plugin;
   if (!$plugin->isActivated("fusioninventory")) {
      if (isset($_GET['id']) AND isset($_GET['action'])
            AND strstr($_SERVER['HTTP_REFERER'], "front/plugin.php")) {
         switch ($_GET['action']) {
            case 'activate':
               addMessageAfterRedirect($LANG['plugin_fusinvinventory']["setup"][17]);
               break;
            case 'uninstall':
               addMessageAfterRedirect($LANG['plugin_fusinvinventory']["setup"][18]);
               glpi_header($CFG_GLPI["root_doc"]."/front/plugin.php");
               break;
         }
      }
      return false;
   }

   // ##### 2. register class #####
   Plugin::registerClass('PluginFusinvinventoryInventory');
      //Classes for rulesengine
   Plugin::registerClass('PluginFusinvinventoryRuleInventory');
   Plugin::registerClass('PluginFusinvinventoryRuleInventoryCollection',
                         array('rulecollections_types'=>true));
   Plugin::registerClass('PluginFusinvinventoryRuleEntity');
   Plugin::registerClass('PluginFusinvinventoryRuleEntityCollection',
                         array('rulecollections_types'=>true));


   // ##### 3. get informations of the plugin #####

   $a_plugin = plugin_version_fusinvinventory();
   $moduleId = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);

   // ##### 4. Set in session module_id #####

   $_SESSION["plugin_".$a_plugin['shortname']."_moduleid"] = $moduleId;

   // ##### 5. Set in session XMLtags of methods #####

   $_SESSION['glpi_plugin_fusioninventory']['xmltags']['INVENTORY']
      = 'PluginFusinvinventoryInventory';


   if (!isset($_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms']['fusinvinventory'][$LANG['plugin_fusinvinventory']["title"][0]])) {
      $_SESSION['glpi_plugin_fusioninventory']['configuration']['moduletabforms']['fusinvinventory'][$LANG['plugin_fusinvinventory']["title"][0]] = array('class'=>'PluginFusinvinventoryConfig',
                                                                'submitbutton'=>'plugin_fusinvinventory_config_set',
                                                                'submitmethod'=>'putForm');
   }


   $PLUGIN_HOOKS['change_profile']['fusinvinventory']
      = PluginFusioninventoryProfile::changeprofile($moduleId,$a_plugin['shortname']);

   if (isset($_SESSION["glpiID"])) {

      //$PLUGIN_HOOKS['init_session']['fusioninventory'] = array('Profile', 'initSession');

      $PLUGIN_HOOKS['use_massive_action']['fusinvinventory']=1;
      $PLUGIN_HOOKS['pre_item_purge']['fusinvinventory']
         = array('Computer' =>'plugin_pre_item_purge_fusinvinventory');

   }

   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['ruleinventory']
      = '../fusinvinventory/front/ruleinventory.form.php';
   $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['ruleentity']
      = '../fusinvinventory/front/ruleentity.form.php';

   // Tabs for each type
   $PLUGIN_HOOKS['headings']['fusinvinventory'] = 'plugin_get_headings_fusinvinventory';
   $PLUGIN_HOOKS['headings_action']['fusinvinventory'] = 'plugin_headings_actions_fusinvinventory';


   $PLUGIN_HOOKS['webservices']['fusinvinventory'] = 'plugin_fusinvinventory_registerMethods';


}



// Name and Version of the plugin
function plugin_version_fusinvinventory() {
   return array('name'           => 'FusionInventory INVENTORY',
                'shortname'      => 'fusinvinventory',
                'version'        => '2.3.0-1',
                'author'         =>'<a href="mailto:d.durieux@siprossii.com">David DURIEUX</a>
                                    & <a href="mailto:v.mazzoni@siprossii.com">Vincent MAZZONI</a>',
                'homepage'       =>'http://forge.fusioninventory.org/projects/pluginfusinvinventory',
                'minGlpiVersion' => '0.78'// For compatibility / no install in version < 0.78
   );
}



// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_fusinvinventory_check_prerequisites() {
   global $LANG;
   if (GLPI_VERSION >= '0.78') {
      $plugin = new Plugin;
      if (!$plugin->isActivated("fusioninventory")) {
         return false;
      }
      return true;
   } else {
      echo $LANG['plugin_fusinvinventory']["errors"][50];
   }
}



function plugin_fusinvinventory_check_config() {
   return true;
}
?>