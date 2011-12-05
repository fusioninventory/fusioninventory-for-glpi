<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2011 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

define ("PLUGIN_FUSIONINVENTORY_VERSION","0.84+1.0");

include_once(GLPI_ROOT."/inc/includes.php");

// Init the hooks of fusioninventory
function plugin_init_fusioninventory() {
   global $PLUGIN_HOOKS,$CFG_GLPI,$LANG;

   $moduleId = 0;
   if (class_exists('PluginFusioninventoryModule')) { // check if plugin is active
      // ##### 1. (Not required here) #####

      // ##### 2. register class #####

      Plugin::registerClass('PluginFusioninventoryAgent');
      Plugin::registerClass('PluginFusioninventoryConfig');
      Plugin::registerClass('PluginFusioninventoryTask',
              array('addtabon' => array('Computer','Printer','NetworkEquipment','PluginFusioninventoryCredentialIp')));
      Plugin::registerClass('PluginFusioninventoryTaskjob');
      Plugin::registerClass('PluginFusioninventoryUnknownDevice');
      Plugin::registerClass('PluginFusioninventoryModule');
      Plugin::registerClass('PluginFusioninventoryProfile');
      Plugin::registerClass('PluginFusioninventorySetup');
      Plugin::registerClass('PluginFusioninventoryAgentmodule');
      Plugin::registerClass('PluginFusioninventoryIPRange');
      Plugin::registerClass('PluginFusioninventoryCredential');
      Plugin::registerClass('PluginFusioninventoryLock',
              array('addtabon' => array('Computer','Monitor','Printer','NetworkEquipment')));
              
      //Classes for rulesengine
      Plugin::registerClass('PluginFusioninventoryRuleImportEquipment');
      Plugin::registerClass('PluginFusioninventoryRuleImportEquipmentCollection',
                            array('rulecollections_types'=>true));
   
      // ##### 3. get informations of the plugin #####

      $a_plugin = plugin_version_fusioninventory();
      $moduleId = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);
      
      // ##### 4. Set in session module_id #####

      $_SESSION["plugin_".$a_plugin['shortname']."_moduleid"] = $moduleId;

      // ##### 5. Set in session XMLtags of methods #####

      $_SESSION['glpi_plugin_fusioninventory']['xmltags']['WAKEONLAN'] = '';

      //$PLUGIN_HOOKS['init_session']['fusioninventory'] = array('Profile', 'initSession');
      $PLUGIN_HOOKS['change_profile']['fusioninventory'] =
         PluginFusioninventoryProfile::changeprofile($moduleId);

//      $PLUGIN_HOOKS['cron']['fusioninventory'] = 20*MINUTE_TIMESTAMP; // All 20 minutes

      $PLUGIN_HOOKS['add_javascript']['fusioninventory']="script.js";


      if (isset($_SESSION["glpiID"])) {

         $CFG_GLPI["specif_entities_tables"][] = 'glpi_plugin_fusioninventory_ipranges';

         if (Session::haveRight("configuration", "r") || Session::haveRight("profile", "w")) {// Config page
            $PLUGIN_HOOKS['config_page']['fusioninventory'] = 'front/config.form.php?glpi_tab=1';
         }

         $PLUGIN_HOOKS['use_massive_action']['fusioninventory']=1;
         $PLUGIN_HOOKS['pre_item_update']['fusioninventory'] = array('Plugin' => 'plugin_pre_item_update_fusioninventory');
         
         $p = array('NetworkPort_NetworkPort'            =>'plugin_item_purge_fusioninventory',
                    'PluginFusioninventoryTask'          => array('PluginFusioninventoryTask',
                                                                  'purgeTask'),
                    'PluginFusioninventoryTaskjob'       => array('PluginFusioninventoryTaskjob',
                                                                  'purgeTaskjob'),
                    'PluginFusioninventoryUnknownDevice' => array('PluginFusioninventoryUnknownDevice',
                                                                  'purgeUnknownDevice'));
         $PLUGIN_HOOKS['item_purge']['fusioninventory'] = $p;

         
         $PLUGIN_HOOKS['item_update']['fusioninventory'] = 
                                 array('Computer'         => 'plugin_item_update_fusioninventory',
                                       'NetworkEquipment' => 'plugin_item_update_fusioninventory',
                                       'Printer'          => 'plugin_item_update_fusioninventory',
                                       'Monitor'          => 'plugin_item_update_fusioninventory',
                                       'Peripheral'       => 'plugin_item_update_fusioninventory',
                                       'Phone'            => 'plugin_item_update_fusioninventory',
                                       'NetworkPort'      => 'plugin_item_update_fusioninventory');


         $PLUGIN_HOOKS['item_transfer']['fusioninventory'] = 'plugin_item_transfer_fusioninventory';
   //      $PLUGIN_HOOKS['item_add']['fusioninventory'] = 'plugin_item_add_fusioninventory';
         $Plugin = new Plugin();
         if ($Plugin->isActivated('fusioninventory')) {
            if (PluginFusioninventoryProfile::haveRight("fusioninventory", "agents", "r")
               OR PluginFusioninventoryProfile::haveRight("fusioninventory", "remotecontrol","r")
               OR PluginFusioninventoryProfile::haveRight("fusioninventory", "configuration","r")
               OR PluginFusioninventoryProfile::haveRight("fusioninventory", "wol","r")
               OR PluginFusioninventoryProfile::haveRight("fusioninventory", "unknowndevice","r")
               OR PluginFusioninventoryProfile::haveRight("fusioninventory", "task","r")
               ) {
            
               $PLUGIN_HOOKS['menu_entry']['fusioninventory'] = true;
            }
         }

         // Tabs for each type
         $PLUGIN_HOOKS['headings']['fusioninventory'] = 'plugin_get_headings_fusioninventory';
         $PLUGIN_HOOKS['headings_action']['fusioninventory'] = 'plugin_headings_actions_fusioninventory';

         // Icons add, search...
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['tasks'] = 'front/task.form.php?add=1';
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['tasks'] = 'front/task.php';

         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['unknown'] = 'front/unknowndevice.form.php?add=1';
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['unknown'] = 'front/unknowndevice.php';

         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['ruleimportequipment']
            = 'front/ruleimportequipment.form.php';
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['ruleimportequipment']
            = 'front/ruleimportequipment.php';
         
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['agents'] = 'front/agent.php';


         if (PluginFusioninventoryProfile::haveRight("fusioninventory", "agent","r")) {

            if (PluginFusioninventoryProfile::haveRight("fusioninventory", "agents","w")) {
   //               $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['agents'] = 'front/agent.form.php?add=1';
               $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['agents'] = 'front/agent.php';
            }

   //         if (PluginFusioninventoryProfile::haveRight($_SESSION["plugin_".$a_plugin['shortname']."_moduleid"], "configuration","r")) {
            if (PluginFusioninventoryProfile::haveRight("fusioninventory", "configuration", "r")) {// Config page
               $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['config'] = 'front/config.form.php';
            }
   //         }
         }
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']
            ["<img  src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/books.png'
               title='".$LANG['plugin_fusioninventory']['setup'][16]."'
               alt='".$LANG['plugin_fusioninventory']['setup'][16]."'>"] =
            'front/documentation.php';

         // Fil ariane
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['menu']['title'] = $LANG['plugin_fusioninventory']['menu'][3];
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['menu']['page']  = '/plugins/fusioninventory/front/wizard.php';

         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['tasks']['title'] = $LANG['plugin_fusioninventory']['task'][1];
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['tasks']['page']  = '/plugins/fusioninventory/front/task.php';

         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['taskjob']['title'] = $LANG['plugin_fusioninventory']['menu'][7];
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['taskjob']['page']  = '/plugins/fusioninventory/front/taskjob.php';

         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['agents']['title'] = $LANG['plugin_fusioninventory']['menu'][1];
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['agents']['page']  = '/plugins/fusioninventory/front/agent.php';

         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['configuration']['title'] = $LANG['plugin_fusioninventory']['functionalities'][2];
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['configuration']['page']  = '/plugins/fusioninventory/front/config.form.php';

         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['unknown']['title'] = $LANG['plugin_fusioninventory']['menu'][4];
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['unknown']['page']  = '/plugins/fusioninventory/front/unknowndevice.php';

         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['ruleimportequipment']['title'] = $LANG['plugin_fusioninventory']['rules'][2];
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['ruleimportequipment']['page']  = '/plugins/fusioninventory/front/ruleimportequipment.php';

         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['iprange']['title'] = 
            $LANG['plugin_fusioninventory']['menu'][2];
         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options']['iprange']['page']  = 
            '/plugins/fusioninventory/front/iprange.php';

         if (PluginFusioninventoryProfile::haveRight("fusioninventory", "iprange","w")) {
            $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['iprange'] = 
               '../fusioninventory/front/iprange.form.php?add=1';
            $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['iprange'] = 
               '../fusioninventory/front/iprange.php';
         }

         if (!class_exists('PluginFusioninventoryCredential')) { // if plugin is unactive
            include(GLPI_ROOT . "/plugins/fusioninventory/inc/credential.class.php");
         }
         if (!class_exists('PluginFusioninventoryStaticmisc')) { // if plugin is unactive
            include(GLPI_ROOT . "/plugins/fusioninventory/inc/staticmisc.class.php");
         }
         
         if (PluginFusioninventoryCredential::hasAlLeastOneType()) {
            if (PluginFusioninventoryProfile::haveRight("fusioninventory", "credential","w")) {
               $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['PluginFusioninventoryCredential'] = 
                  '../fusioninventory/front/credential.form.php?add=1';
               $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['PluginFusioninventoryCredential'] = 
                  '../fusioninventory/front/credential.php';

            }

            if (PluginFusioninventoryProfile::haveRight("fusioninventory", "credential","w")) {
               $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['add']['PluginFusioninventoryCredentialIp'] = 
                  '../fusioninventory/front/credentialip.form.php?add=1';
               $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['search']['PluginFusioninventoryCredentialIp'] = 
                  '../fusioninventory/front/credentialip.php';
   
            }

         }

      }
   } else { // plugin not active, need $moduleId for uninstall check
      include_once(GLPI_ROOT.'/plugins/fusioninventory/inc/module.class.php');
      $moduleId = PluginFusioninventoryModule::getModuleId('fusioninventory');
   }

   // Check for uninstall
   if (isset($_GET['id']) 
      && ($_GET['id'] == $moduleId)
         && (isset($_GET['action']) 
            && $_GET['action'] == 'uninstall')
               && (strstr($_SERVER['HTTP_REFERER'], "front/plugin.php"))) {

      if (PluginFusioninventoryModule::getAll(true)) {
          Session::addMessageAfterRedirect($LANG['plugin_fusioninventory']['setup'][17]);
         Html::redirect($CFG_GLPI["root_doc"]."/front/plugin.php");
         exit;
      }
   }


   // Add unknown devices in list of devices with networport
   $CFG_GLPI["netport_types"][] = "PluginFusioninventoryUnknownDevice";

}



// Name and Version of the plugin
function plugin_version_fusioninventory() {
   return array('name'           => 'FusionInventory',
                'shortname'      => 'fusioninventory',
                'version'        => PLUGIN_FUSIONINVENTORY_VERSION,
                'license'        => 'AGPLv3+',
                'oldname'        => 'tracker',
                'author'         =>'<a href="mailto:d.durieux@siprossii.com">David DURIEUX</a>
                                    & FusionInventory team',
                'homepage'       =>'http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/',
                'minGlpiVersion' => '0.84'// For compatibility / no install in version < 0.78
   );
}


// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_fusioninventory_check_prerequisites() {
   global $LANG;
   
   if (version_compare(GLPI_VERSION,'0.84','lt') || version_compare(GLPI_VERSION,'0.85','ge')) {
      echo $LANG['plugin_fusioninventory']['errors'][50];
      return false;
   }
   return true;
}

/**
 * Check if HTTP request comes from an inventory agent (Fusion or legacy OCS)
 * @param useragent the user agent coming from $_SERVER
 * 
 * @return bool : true if request comes from an agent, false otherwise
 */
function isFusioninventoryUserAgent($useragent = '') {
   return (preg_match("/(fusioninventory|ocsinventory|ocs-ng)/i",$useragent));
}

function plugin_fusioninventory_check_config() {
   return true;
}

function plugin_fusioninventory_haveTypeRight($type,$right) {
   return true;
}

?>