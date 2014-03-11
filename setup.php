<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2013 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

define ("PLUGIN_FUSIONINVENTORY_VERSION", "0.85+1.0");

// Used for use config values in 'cache'
$PF_CONFIG = array();
// used to know if computer inventory is in reallity a ESX task
$PF_ESXINVENTORY = FALSE;

define ("PLUGIN_FUSIONINVENTORY_XML", '');

define ("PLUGIN_FUSIONINVENTORY_OFFICIAL_RELEASE", "0");
define ("PLUGIN_FUSIONINVENTORY_REALVERSION", "0.85+1.0 SNAPSHOT");
include_once(GLPI_ROOT."/inc/includes.php");

include_once( GLPI_ROOT . "/plugins/fusioninventory/lib/autoload.php");

define("PLUGIN_FUSIONINVENTORY_ROOT",
   implode(DIRECTORY_SEPARATOR , array(GLPI_ROOT,'plugins', 'fusioninventory', 'inc'))
);

$options = array(
   PLUGIN_FUSIONINVENTORY_ROOT
);

$fi_loader = new FusioninventoryIncludePathAutoloader($options);
$fi_loader->register();
/*
 * @function script_endswith()
 * @param $scriptname : string representing the script to test
 * test the end of the called scriptname ( this is usefull to load )
 *
 */
function script_endswith($scriptname) {
   return substr($_SERVER['SCRIPT_FILENAME'], -strlen($scriptname))===$scriptname;
}

// Init the hooks of fusioninventory
function plugin_init_fusioninventory() {
   global $PLUGIN_HOOKS, $CFG_GLPI;

   $PLUGIN_HOOKS['csrf_compliant']['fusioninventory'] = TRUE;

   $Plugin = new Plugin();
   $moduleId = 0;
   if ($Plugin->isActivated('fusioninventory')) { // check if plugin is active

      // Register classes into GLPI plugin factory

      Plugin::registerClass('PluginFusioninventoryAgent');
      Plugin::registerClass('PluginFusioninventoryAgentmodule');
      Plugin::registerClass('PluginFusioninventoryConfig');
      Plugin::registerClass('PluginFusioninventoryTask',
         array(
            'addtabon' => array(
               'Computer',
               'Printer',
               'NetworkEquipment',
               'PluginFusioninventoryCredentialIp'
            )
         )
      );
      Plugin::registerClass('PluginFusioninventoryTaskjob',
         array(
            'addtabon' => array(
               'Computer',
               'Printer',
               'NetworkEquipment',
               'PluginFusioninventoryTask',
               'PluginFusioninventoryUnknowndevice'
            )
         )
      );
      Plugin::registerClass('PluginFusioninventoryTaskjob');
      Plugin::registerClass('PluginFusioninventoryTaskjobstate',
         array(
            'addtabon' => array(
               'PluginFusioninventoryTask'
            )
         )
      );
      Plugin::registerClass('PluginFusioninventoryUnknownDevice');
      Plugin::registerClass('PluginFusioninventoryModule');
      Plugin::registerClass('PluginFusioninventoryProfile',
              array('addtabon' => array('Profile')));
      Plugin::registerClass('PluginFusioninventorySetup');
      Plugin::registerClass('PluginFusioninventoryIPRange');
      Plugin::registerClass('PluginFusioninventoryCredential');
      Plugin::registerClass('PluginFusioninventoryLock',
              array('addtabon' => array('Computer', 'Printer', 'NetworkEquipment')));

      Plugin::registerClass('PluginFusioninventoryInventoryComputerAntivirus',
              array('addtabon' => array('Computer')));
      Plugin::registerClass('PluginFusioninventoryInventoryComputerComputer',
              array('addtabon' => array('Computer')));
      Plugin::registerClass('PluginFusioninventoryInventoryComputerInventory');
      Plugin::registerClass('PluginFusioninventoryInventoryComputerStorage',
              array('addtabon' => array('Computer')));
      Plugin::registerClass('PluginFusioninventoryConfigurationManagement',
              array('addtabon' => array('Computer')));
      Plugin::registerClass('PluginFusioninventoryCollect');
      Plugin::registerClass('PluginFusioninventoryCollect_Registry',
              array('addtabon' => array('PluginFusioninventoryCollect')));
      Plugin::registerClass('PluginFusioninventoryCollect_Registry_Content',
              array('addtabon' => array('PluginFusioninventoryCollect',
                                        'Computer')));
      Plugin::registerClass('PluginFusioninventoryCollect_Wmi',
              array('addtabon' => array('PluginFusioninventoryCollect')));
      Plugin::registerClass('PluginFusioninventoryCollect_Wmi_Content',
              array('addtabon' => array('PluginFusioninventoryCollect',
                                        'Computer')));
      Plugin::registerClass('PluginFusioninventoryCollect_File',
              array('addtabon' => array('PluginFusioninventoryCollect')));
      Plugin::registerClass('PluginFusioninventoryCollect_File_Content',
              array('addtabon' => array('PluginFusioninventoryCollect',
                                        'Computer')));
      Plugin::registerClass('PluginFusioninventoryComputerLicenseInfo',
              array('addtabon' => array('Computer')));

         //Classes for rulesengine
      Plugin::registerClass('PluginFusioninventoryInventoryRuleLocation');
      Plugin::registerClass('PluginFusioninventoryInventoryRuleLocationCollection',
              array('rulecollections_types'=>TRUE));
      Plugin::registerClass('PluginFusioninventoryInventoryRuleEntity');
      Plugin::registerClass('PluginFusioninventoryInventoryRuleEntityCollection',
              array('rulecollections_types'=>TRUE));
      Plugin::registerClass('PluginFusioninventoryRulematchedlog',
              array('addtabon' => array('Computer',
                                        'PluginFusioninventoryAgent',
                                        'PluginFusioninventoryUnknownDevice',
                                        'Printer',
                                        'NetworkEquipment')));

      //Classes for rulesengine
      Plugin::registerClass('PluginFusioninventoryInventoryRuleImport');
      Plugin::registerClass('PluginFusioninventoryInventoryRuleImportCollection',
              array('rulecollections_types'=>TRUE));
      Plugin::registerClass('PluginFusioninventoryConstructDevice');

      // Networkinventory and networkdiscovery
      Plugin::registerClass('PluginFusioninventorySnmpmodel');
      Plugin::registerClass('PluginFusioninventoryNetworkEquipment',
              array('addtabon' => array('NetworkEquipment')));
      Plugin::registerClass('PluginFusioninventoryPrinter',
              array('addtabon' => array('Printer')));
      Plugin::registerClass('PluginFusioninventoryPrinterCartridge');
      Plugin::registerClass('PluginFusioninventoryConfigSecurity');
      Plugin::registerClass('PluginFusioninventoryNetworkPortLog',
              array('addtabon' => array('NetworkPort')));
      Plugin::registerClass('PluginFusinvsnmpAgentconfig');
      Plugin::registerClass('PluginFusioninventoryNetworkPort',
              array('classname'=>'glpi_networkports'));
      Plugin::registerClass('PluginFusioninventoryStateDiscovery');
      Plugin::registerClass('PluginFusioninventoryPrinterLogReport');
      Plugin::registerClass('PluginFusioninventorySnmpmodelConstructdevice_User',
              array('addtabon' => array('User')));

      $CFG_GLPI['glpitablesitemtype']["PluginFusioninventoryPrinterLogReport"] =
                                                      "glpi_plugin_fusioninventory_printers";

      // ##### 3. get informations of the plugin #####

      $Plugin->getFromDBbyDir('fusioninventory');
      $moduleId = $Plugin->fields['id'];

      // Load config
      PluginFusioninventoryConfig::loadCache();

      // ##### 4. Set in session module_id #####

      $_SESSION["plugin_fusioninventory_moduleid"] = $moduleId;

      // ##### 5. Set in session XMLtags of methods #####

      $_SESSION['glpi_plugin_fusioninventory']['xmltags']['WAKEONLAN'] = '';
      $_SESSION['glpi_plugin_fusioninventory']['xmltags']['INVENTORY']
                                             = 'PluginFusioninventoryInventoryComputerInventory';
      $_SESSION['glpi_plugin_fusioninventory']['xmltags']['NETWORKDISCOVERY']
                                             = 'PluginFusioninventoryCommunicationNetworkDiscovery';
      $_SESSION['glpi_plugin_fusioninventory']['xmltags']['NETWORKINVENTORY']
                                             = 'PluginFusioninventoryCommunicationNetworkInventory';

      $PLUGIN_HOOKS['import_item']['fusioninventory'] = array(
          'Computer' => array('Plugin'));

      $CFG_GLPI["specif_entities_tables"][] = 'glpi_plugin_fusioninventory_ipranges';

      $CFG_GLPI["networkport_types"][] = 'PluginFusioninventoryUnknownDevice';

      $PLUGIN_HOOKS['add_css']['fusioninventory'][]="css/views.css";
      $PLUGIN_HOOKS['add_css']['fusioninventory'][]="css/deploy.css";

      /**
       * Load the relevant javascript files only on pages that need them.
       */
      if (  script_endswith("deploypackage.form.php") ) {

         $PLUGIN_HOOKS['add_javascript']['fusioninventory'] = array(
             "lib/REDIPS_drag/redips-drag-source.js",
             "lib/REDIPS_drag/drag_table_rows.js",
             "lib/plusbutton.js",
             "lib/deploy_editsubtype.js",
         );
      }

      if (  script_endswith("task.form.php") )
      {
         $PLUGIN_HOOKS['add_javascript']['fusioninventory'] = array(
             "lib/REDIPS_drag/redips-drag-source.js",
             "lib/REDIPS_drag/drag_table_rows.js",
             "lib/plusbutton.js",
         );
      }


      if (Session::haveRight('plugin_fusioninventory_configuration', READ)
              || Session::haveRight('profile', UPDATE)) {// Config page
         $PLUGIN_HOOKS['config_page']['fusioninventory'] = 'front/config.form.php'.
                 '?itemtype=pluginfusioninventoryconfig&glpi_tab=1';
      }

      $PLUGIN_HOOKS['autoinventory_information']['fusioninventory'] = array(
            'Computer' =>  array('PluginFusioninventoryInventoryComputerComputer',
                                 'showInfo'),
            'NetworkEquipment' => array('PluginFusioninventoryNetworkEquipment',
                                        'showInfo'));

      $PLUGIN_HOOKS['use_massive_action']['fusioninventory'] = 1;

      $PLUGIN_HOOKS['item_add']['fusioninventory'] = array(
            'NetworkPort_NetworkPort' => 'plugin_item_add_fusioninventory',
            'NetworkPort'             => 'plugin_item_add_fusioninventory'
          );


      $PLUGIN_HOOKS['pre_item_update']['fusioninventory'] = array(
            'Plugin' => 'plugin_pre_item_update_fusioninventory'
          );
      $PLUGIN_HOOKS['item_update']['fusioninventory'] =
                              array('Computer'         => 'plugin_item_update_fusioninventory',
                                    'NetworkEquipment' => 'plugin_item_update_fusioninventory',
                                    'Printer'          => 'plugin_item_update_fusioninventory',
                                    'Monitor'          => 'plugin_item_update_fusioninventory',
                                    'Peripheral'       => 'plugin_item_update_fusioninventory',
                                    'Phone'            => 'plugin_item_update_fusioninventory',
                                    'NetworkPort'      => 'plugin_item_update_fusioninventory',
                                    'PluginFusioninventoryInventoryComputerAntivirus' => array(
                                          'PluginFusioninventoryInventoryComputerAntivirus',
                                          'addhistory'),
                                    'PluginFusioninventoryLock' => array('PluginFusioninventoryLock', 'deleteLock'));


      $PLUGIN_HOOKS['pre_item_purge']['fusioninventory'] = array(
            'Computer'                 =>'plugin_pre_item_purge_fusioninventory',
            'NetworkPort_NetworkPort'  =>'plugin_pre_item_purge_fusioninventory',
            'PluginFusioninventoryLock'=> array('PluginFusioninventoryLock', 'deleteLock')
          );
      $p = array('NetworkPort_NetworkPort'            => 'plugin_item_purge_fusioninventory',
                 'PluginFusioninventoryTask'          => array('PluginFusioninventoryTask',
                                                               'purgeTask'),
                 'PluginFusioninventoryTaskjob'       => array('PluginFusioninventoryTaskjob',
                                                               'purgeTaskjob'),
                 'PluginFusioninventoryUnknownDevice' => array('PluginFusioninventoryUnknownDevice',
                                                               'purgeUnknownDevice'),
                 'NetworkEquipment'                   => 'plugin_item_purge_fusinvsnmp',
                 'Printer'                            => 'plugin_item_purge_fusinvsnmp',
                 'PluginFusioninventoryUnknownDevice' => 'plugin_item_purge_fusinvsnmp');
      $PLUGIN_HOOKS['item_purge']['fusioninventory'] = $p;


      $PLUGIN_HOOKS['item_transfer']['fusioninventory'] = 'plugin_item_transfer_fusioninventory';

         $PLUGIN_HOOKS["menu_toadd"]['fusioninventory'] = array(
             'plugins' => 'PluginFusioninventoryMenu',
             'assets'  => 'PluginFusioninventoryUnknowndevice'
         );


      // * Tabs for each type
      $PLUGIN_HOOKS['headings']['fusioninventory'] = 'plugin_get_headings_fusioninventory';
      $PLUGIN_HOOKS['headings_action']['fusioninventory'] = 'plugin_headings_actions_fusioninventory';

      if (isset($_SESSION["glpiname"])) {
         $report_list = array();
         if (Session::haveRight('plugin_fusioninventory_reportprinter', READ)) {
            $report_list["front/printerlogreport.php"] = __('Printed page counter', 'fusioninventory');

         }
         if (Session::haveRight('plugin_fusioninventory_reportnetworkequipment', READ)) {
            $report_list["report/switch_ports.history.php"] = __('Switchs ports history', 'fusioninventory');

            $report_list["report/ports_date_connections.php"] = __('Unused switchs ports', 'fusioninventory');

            $report_list["report/not_queried_recently.php"] = __('Number of days since last inventory', 'fusioninventory');

         }
         if (Session::haveRight('computer', READ)) {
            $report_list["report/computer_last_inventory.php"] = __('Computers not inventoried since xx days', 'fusioninventory');
         }
         $PLUGIN_HOOKS['reports']['fusioninventory'] = $report_list;

         /*
          * Deploy submenu entries
          */

         if (Session::haveRight('plugin_fusioninventory_configuration', READ)) {// Config page
            $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['config'] = 'front/config.form.php';
         }

         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']
            ["<img  src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/books.png'
               title='".__('Documentation', 'fusioninventory')."'
               alt='".__('Documentation', 'fusioninventory')."'>"] =
            'front/documentation.php';

         $PLUGIN_HOOKS['webservices']['fusioninventory'] = 'plugin_fusioninventory_registerMethods';

         // * Fil ariane
         $filariane= array();
         $filariane['menu']['title'] = __('Menu', 'fusioninventory');
         $filariane['menu']['page']  = '/plugins/fusioninventory/front/wizard.php';

         $filariane['tasks']['title'] = __('Task management', 'fusioninventory');
         $filariane['tasks']['page']  = '/plugins/fusioninventory/front/task.php';

         $filariane['taskjob']['title'] = __('Running jobs', 'fusioninventory');
         $filariane['taskjob']['page']  = '/plugins/fusioninventory/front/taskjob.php';

         $filariane['agents']['title'] = __('Agents management', 'fusioninventory');
         $filariane['agents']['page']  = '/plugins/fusioninventory/front/agent.php';

         $filariane['configuration']['title'] = __('General setup');
         $filariane['configuration']['page']  = '/plugins/fusioninventory/front/config.form.php';

         $filariane['unknown']['title'] = __('Unknown device', 'fusioninventory');
         $filariane['unknown']['page']  = '/plugins/fusioninventory/front/unknowndevice.php';

         $filariane['inventoryruleimport']['title'] = __('Equipment import and link rules', 'fusioninventory');
         $filariane['inventoryruleimport']['page']  = '/plugins/fusioninventory/front/inventoryruleimport.php';

         $filariane['wizard-start']['title'] = __('Wizard', 'fusioninventory');
         $filariane['wizard-start']['page']  = '/plugins/fusioninventory/front/wizard.php';

         $filariane['iprange']['title'] = __('IP range configuration', 'fusioninventory');
         $filariane['iprange']['page']  = '/plugins/fusioninventory/front/iprange.php';

         $filariane['packages']['title'] = __('Packages', 'fusioninventory');
         $filariane['packages']['page']  = '/plugins/fusioninventory/front/deploypackage.php';

         $filariane['group']['title'] = __('Groups of computers', 'fusioninventory');
         $filariane['group']['page']  = '/plugins/fusioninventory/front/deploygroup.php';

         $filariane['ignoredimportrules']['title'] = __('Equipment ignored on import', 'fusioninventory');
         $filariane['ignoredimportrules']['page']  = '/plugins/fusioninventory/front/ignoredimportdevice.php';

         $filariane['blacklist']['title'] = __('BlackList');
         $filariane['blacklist']['page']  = '/plugins/fusioninventory/front/inventorycomputerblacklist.php';

         $filariane['ruleentity']['title'] = __('Entity rules', 'fusioninventory');
         $filariane['ruleentity']['page']  = '/plugins/fusioninventory/front/inventoryruleentity.php';

         $filariane['rulelocation']['title'] = __('Location rules', 'fusioninventory');
         $filariane['rulelocation']['page']  = '/plugins/fusioninventory/front/inventoryrulelocation.php';

         $filariane['importxmlfile']['title'] = __('Import agent XML file', 'fusioninventory');
         $filariane['importxmlfile']['page']  = '/plugins/fusioninventory/front/inventorycomputerimportxml.php';

         $filariane['models']['title'] = __('SNMP models');
         $filariane['models']['page']  = '/plugins/fusioninventory/front/snmpmodel.php';

         $filariane['configsecurity']['title'] = __('SNMP authentication');
         $filariane['configsecurity']['page']  = '/plugins/fusioninventory/front/configsecurity.php';

         $filariane['statediscovery']['title'] = __('Discovery status', 'fusioninventory');
         $filariane['statediscovery']['page']  = '/plugins/fusioninventory/front/statediscovery.php';

         $filariane['stateinventory']['title'] = __('Inventory status', 'fusioninventory');
         $filariane['stateinventory']['page']  = '/plugins/fusioninventory/front/stateinventory.php';

         $filariane['mirror']['title'] = __('Mirror servers', 'fusioninventory');
         $filariane['mirror']['page']  = '/plugins/fusioninventory/front/deploymirror.php';

         $PLUGIN_HOOKS['submenu_entry']['fusioninventory']['options'] = $filariane;

         // Hack for NetworkEquipment display ports
         if (strstr($_SERVER['PHP_SELF'], '/ajax/common.tabs.php')) {
            if (isset($_POST['target'])
                    && strstr($_POST['target'], '/front/networkequipment.form.php')
                    && $_POST['itemtype'] == 'NetworkEquipment') {

               if ($_POST['glpi_tab'] == 'NetworkPort$1') {
                  $_POST['glpi_tab'] = 'PluginFusioninventoryNetworkEquipment$1';
               } else if ($_POST['glpi_tab'] == 'PluginFusioninventoryNetworkEquipment$1') {
                  $_POST['displaysnmpinfo'] = 1;
               }
            }
         }
         // Load nvd3 for printerpage counter graph
         if (strstr($_SERVER['PHP_SELF'], '/front/printer.form.php')) {
            echo '<link href="'.$CFG_GLPI['root_doc'].'/plugins/fusioninventory/lib/nvd3'.
                    '/src/nv.d3.css" rel="stylesheet" type="text/css" />
               <script src="'.$CFG_GLPI['root_doc'].'/plugins/fusioninventory/lib/nvd3'.
                    '/lib/d3.v2.min.js"></script>
               <script src="'.$CFG_GLPI['root_doc'].'/plugins/fusioninventory/lib/nvd3'.
                    '/nv.d3.min.js"></script>
               <script src="'.$CFG_GLPI['root_doc'].'/plugins/fusioninventory/lib/nvd3'.
                    '/src/tooltip.js"></script>
               <script src="'.$CFG_GLPI['root_doc'].'/plugins/fusioninventory/lib/nvd3'.
                    '/src/utils.js"></script>
               <script src="'.$CFG_GLPI['root_doc'].'/plugins/fusioninventory/lib/nvd3'.
                    '/src/models/legend.js"></script>
               <script src="'.$CFG_GLPI['root_doc'].'/plugins/fusioninventory/lib/nvd3'.
                    '/src/models/axis.js"></script>
               <script src="'.$CFG_GLPI['root_doc'].'/plugins/fusioninventory/lib/nvd3'.
                    '/src/models/scatter.js"></script>
               <script src="'.$CFG_GLPI['root_doc'].'/plugins/fusioninventory/lib/nvd3'.
                    '/src/models/line.js"></script>
               <script src="'.$CFG_GLPI['root_doc'].'/plugins/fusioninventory/lib/nvd3'.
                    '/src/models/multiBar.js"></script>
               <script src="'.$CFG_GLPI['root_doc'].'/plugins/fusioninventory/lib/nvd3'.
                    '/src/models/multiBarChart.js"></script>
               <script src="'.$CFG_GLPI['root_doc'].'/plugins/fusioninventory/lib/nvd3'.
                    '/src/models/lineChart.js"></script>';
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

      if (PluginFusioninventoryModule::getAll(TRUE)) {
          Session::addMessageAfterRedirect(__('Other FusionInventory plugins (fusinv...) must be uninstalled before removing the FusionInventory plugin'));

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
                'minGlpiVersion' => '0.85'
   );
}



// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_fusioninventory_check_prerequisites() {
   global $DB;

   if (!isset($_SESSION['glpi_plugins'])) {
      $_SESSION['glpi_plugins'] = array();
   }

   if (version_compare(GLPI_VERSION, '0.85', 'lt') || version_compare(GLPI_VERSION, '0.86', 'ge')) {
      echo __('Your GLPI version not compatible, require 0.85', 'fusioninventory');
      return FALSE;
   }

   if (!function_exists('finfo_open')) {
      echo __('fileinfo extension (PHP) is required...', 'fusioninventory');
      return FALSE;
   }

   $plugin = new Plugin();
   if ($plugin->isActivated("fusioninventory")
           && !TableExists("glpi_plugin_fusioninventory_configs")) {
      return FALSE;
   }

   $a_plugins = array('fusinvinventory', 'fusinvsnmp', 'fusinvdeploy');
   foreach ($a_plugins as $pluginname) {
      if (file_exists(GLPI_ROOT.'/plugins/'.$pluginname)) {
         printf(__('Please remove folder %s in glpi/plugins/', 'fusioninventory'), $pluginname);
         return FALSE;
      }
   }

   $crontask = new CronTask();
   if ($plugin->isActivated("fusioninventory")) {
      if ((TableExists("glpi_plugin_fusioninventory_agents")
              AND !FieldExists("glpi_plugin_fusioninventory_agents", "tag"))
           OR ($crontask->getFromDBbyName('PluginFusioninventoryTaskjobstatus', 'cleantaskjob'))
           OR (TableExists("glpi_plugin_fusioninventory_agentmodules")
              AND FieldExists("glpi_plugin_fusioninventory_agentmodules", "url"))) {
         $DB->query("UPDATE `glpi_plugin_fusioninventory_configs` SET `value`='0.80+1.4'
                        WHERE `type`='version'");
         $DB->query("UPDATE `glpi_plugins` SET `version`='0.80+1.4'
                        WHERE `directory` LIKE 'fusi%'");
      }
   }
   return TRUE;
}



function plugin_fusioninventory_check_config() {
   return TRUE;
}



function plugin_fusioninventory_haveTypeRight($type,$right) {
   return TRUE;
}

?>
