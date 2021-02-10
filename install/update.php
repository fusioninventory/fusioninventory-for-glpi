<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the agents
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

include_once( PLUGIN_FUSIONINVENTORY_DIR . "/install/update.tasks.php" );

/**
 * Get the current version of the plugin
 *
 * @global object $DB
 * @return string
 */
function pluginFusioninventoryGetCurrentVersion() {
   global $DB;

   require_once(PLUGIN_FUSIONINVENTORY_DIR . "/inc/module.class.php");

   if ((!$DB->tableExists("glpi_plugin_tracker_config")) &&
      (!$DB->tableExists("glpi_plugin_fusioninventory_config")) &&
      (!$DB->tableExists("glpi_plugin_fusioninventory_configs"))) {
      return '0';
   } else if (($DB->tableExists("glpi_plugin_tracker_config")) ||
         ($DB->tableExists("glpi_plugin_fusioninventory_config"))) {

      if ($DB->tableExists("glpi_plugin_fusioninventory_configs")) {
         $iterator = $DB->request([
            'FROM'   => 'glpi_plugin_fusioninventory_configs',
            'WHERE'  => ['type' => 'version'],
            'LIMIT'  => 1
         ]);

         $data = [];
         if (count($iterator)) {
            $data = $iterator->next();
            return $data['value'];
         }
      }

      if ((!$DB->tableExists("glpi_plugin_tracker_agents")) &&
         (!$DB->tableExists("glpi_plugin_fusioninventory_agents"))) {
         return "1.1.0";
      }
      if ((!$DB->tableExists("glpi_plugin_tracker_config_discovery")) &&
         (!$DB->tableExists("glpi_plugin_fusioninventory_config"))) {
         return "2.0.0";
      }
      if ((($DB->tableExists("glpi_plugin_tracker_agents")) &&
           (!$DB->fieldExists("glpi_plugin_tracker_config", "version"))) &&
         (!$DB->tableExists("glpi_plugin_fusioninventory_config"))) {
         return "2.0.1";
      }
      if ((($DB->tableExists("glpi_plugin_tracker_agents")) &&
           ($DB->fieldExists("glpi_plugin_tracker_config", "version"))) ||
         ($DB->tableExists("glpi_plugin_fusioninventory_config"))) {

         $querytable = 'glpi_plugin_fusioninventory_config';
         if ($DB->tableExists("glpi_plugin_tracker_agents")) {
            $querytable = 'glpi_plugin_tracker_config';
         }

         $iterator = $DB->request([
            'SELECT' => ['version'],
            'FROM'   => $querytable,
            'LIMIT'  => 1
         ]);

         $data = [];
         if (count($iterator)) {
            $data = $iterator->next();
         }

         if ($data['version'] == "0") {
            return "2.0.2";
         } else {
            return $data['version'];
         }
      }
   } else if ($DB->tableExists("glpi_plugin_fusioninventory_configs")) {
      $iterator = $DB->request([
         'SELECT' => ['value'],
         'FROM'   => 'glpi_plugin_fusioninventory_configs',
         'WHERE'  => ['type' => 'version'],
         'LIMIT'  => 1
      ]);

      $data = [];
      if (count($iterator)) {
         $data = $iterator->next();
         return $data['value'];
      }
      if ($DB->fieldExists('glpi_plugin_fusioninventory_agentmodules', 'plugins_id')) {
         $iterator = $DB->request([
            'SELECT' => ['plugins_id'],
            'FROM'   => 'glpi_plugin_fusioninventory_agentmodules',
            'WHERE'  => ['modulename' => 'WAKEONLAN'],
            'LIMIT'  => 1
         ]);
         if (count($iterator)) {
            $ex_pluginid = $iterator->next();

            $DB->update(
               'glpi_plugin_fusioninventory_taskjobs', [
                  'plugins_id'   => PluginFusioninventoryModule::getModuleId('fusioninventory')
               ], [
                  'plugins_id'   => $ex_pluginid['plugins_id']
               ]
            );

            $DB->update(
               'glpi_plugin_fusioninventory_profiles', [
                  'plugins_id'   => PluginFusioninventoryModule::getModuleId('fusioninventory')
               ], [
                  'plugins_id'   => $ex_pluginid['plugins_id']
               ]
            );

            $DB->update(
               'glpi_plugin_fusioninventory_agentmodules', [
                  'plugins_id'   => PluginFusioninventoryModule::getModuleId('fusioninventory')
               ], [
                  'plugins_id'   => $ex_pluginid['plugins_id']
               ]
            );

            $iterator = $DB->request([
               'SELECT' => ['value'],
               'FROM'   => 'glpi_plugin_fusioninventory_configs',
               'WHERE'  => ['type' => 'version'],
               'LIMIT'  => 1
            ]);

            $data = [];
            if (count($iterator)) {
               $data = $iterator->next();
               return $data['value'];
            }
         }
      }
   }
   return "1.1.0";
}


/**
 * Find files recursively filtered with pattern
 * (grabbed from http://rosettacode.org/wiki/Walk_a_directory/Recursively#PHP)
 *
 * @param string $dir
 * @param string $pattern
 * @return array
 */
function pluginFusioninventoryFindFiles($dir = '.', $pattern = '/./') {
   $files = [];
   $prefix = $dir . '/';
   $dir = dir($dir);
   while (false !== ($file = $dir->read())) {
      if ($file === '.' || $file === '..') {
         continue;
      }
      $file = $prefix . $file;
      if (is_dir($file)) {
         $files[] = pluginFusioninventoryFindFiles($file, $pattern);
         continue;
      }
      if (preg_match($pattern, $file)) {
          $files[] = $file;
      }
   }
   return pluginFusioninventoryFlatArray($files);
}


/**
 * Flat a multi-dimensional array
 *
 * @param array $array
 * @return array
 */
function pluginFusioninventoryFlatArray($array) {
   $tmp = [];
   foreach ($array as $a) {
      if (is_array($a)) {
         $tmp = array_merge($tmp, pluginFusioninventoryFlatArray($a));
      } else {
         $tmp[] = $a;
      }
   }
   return $tmp;
}


/**
 * The main function to update the plugin FusionInventory
 *
 * @global object $DB
 * @param string $current_version
 * @param string $migrationname
 */
function pluginFusioninventoryUpdate($current_version, $migrationname = 'Migration') {
   global $DB;

   ini_set("max_execution_time", "0");
   ini_set("memory_limit", "-1");

   // load abstract classes before
   require_once PLUGIN_FUSIONINVENTORY_DIR.'/inc/deploypackageitem.class.php';
   require_once PLUGIN_FUSIONINVENTORY_DIR.'/inc/commonview.class.php';
   require_once PLUGIN_FUSIONINVENTORY_DIR.'/inc/taskview.class.php';
   require_once PLUGIN_FUSIONINVENTORY_DIR.'/inc/taskjobview.class.php';
   require_once PLUGIN_FUSIONINVENTORY_DIR.'/inc/item.class.php';
   require_once PLUGIN_FUSIONINVENTORY_DIR.'/inc/collectcommon.class.php';
   require_once PLUGIN_FUSIONINVENTORY_DIR.'/inc/collectcontentcommon.class.php';

   foreach (glob(PLUGIN_FUSIONINVENTORY_DIR.'/inc/*.php') as $file) {
      require_once($file);
   }

   $migration = new $migrationname($current_version);
   $prepare_task = [];
   $prepare_rangeip = [];
   $prepare_Config = [];

   $a_plugin = plugin_version_fusioninventory();
   $plugins_id = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);

   $migration->displayMessage("Migration Classname : " . $migrationname);
   $migration->displayMessage("Update of plugin FusionInventory");

   // ********* Check if folders are correctly created ********************** //

   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml');
   }
   if (!is_dir(PLUGIN_FUSIONINVENTORY_XML_DIR.'computer')) {
      mkdir(PLUGIN_FUSIONINVENTORY_XML_DIR.'computer');
   }
   if (!is_dir(PLUGIN_FUSIONINVENTORY_XML_DIR.'printer')) {
      mkdir(PLUGIN_FUSIONINVENTORY_XML_DIR.'printer');
   }
   if (!is_dir(PLUGIN_FUSIONINVENTORY_XML_DIR.'networkequipment')) {
      mkdir(PLUGIN_FUSIONINVENTORY_XML_DIR.'networkequipment');
   }

   // ********* Deploy folders ********************************************** //

   if (is_dir(GLPI_PLUGIN_DOC_DIR.'/fusinvdeploy/files')) {
      rename(
         GLPI_PLUGIN_DOC_DIR.'/fusinvdeploy/files',
         GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files'
      );
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files');
   }

   if (is_dir(GLPI_PLUGIN_DOC_DIR.'/fusinvdeploy/repository')) {
      rename(
         GLPI_PLUGIN_DOC_DIR.'/fusinvdeploy/repository',
         GLPI_PLUGIN_DOC_DIR.'/fusioninventory/repository'
      );
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files/repository')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files/repository');
   }

   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files/manifests')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files/manifests');
   }

   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files/import')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files/import');
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files/export')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files/export');
   }

   if (is_dir(GLPI_PLUGIN_DOC_DIR.'/fusinvdeploy/upload')) {
      rename(
         GLPI_PLUGIN_DOC_DIR.'/fusinvdeploy/upload',
         GLPI_PLUGIN_DOC_DIR.'/fusioninventory/upload'
      );
   }
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/upload')) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/upload');
   }

   // ********* Rename fileparts without .gz extension (cf #1999) *********** //

   if (is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files')) {
      $gzfiles = pluginFusioninventoryFindFiles(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/files', '/\.gz$/');
      foreach ($gzfiles as $file) {
         $fileWithoutExt =
            pathinfo($file, PATHINFO_DIRNAME) .
            '/' . pathinfo($file, PATHINFO_FILENAME);

         rename($file, $fileWithoutExt);
      }
   }
   unset($gzfiles);

   // conversion in very old version
   update213to220_ConvertField($migration);

   // ********* Migration internal / common ********************************* //

      // ********* Rename tables ******************************************** //
      $migration->renameTable("glpi_plugin_fusioninventory_lock",
                              "glpi_plugin_fusioninventory_locks");
      $migration->renameTable("glpi_plugin_fusioninventory_unknown_device",
                              "glpi_plugin_fusioninventory_unknowndevices");
      $migration->renameTable("glpi_plugin_fusioninventory_config",
                              "glpi_plugin_fusioninventory_configs");

      // ********* Migration ************************************************ //
      $prepare_rangeip = do_agent_migration($migration);
      $prepare_Config  = do_config_migration($migration);
      do_entities_migration($migration);
      do_locks_migration($migration);
      do_profile_migration($migration);
      do_ignoredimport_migration($migration);
      do_rulematchedlog_migration($migration);
      do_unmanaged_migration($migration);

   // ********* Migration Computer inventory ******************************** //

      // ********* Rename tables ******************************************** //

      // ********* Migration ************************************************ //
      do_blacklist_migration($migration);
      do_antivirus_migration($migration);
      do_computercomputer_migration($migration);
      do_computerstat_migration($migration);
      do_computerlicense_migration($migration);
      do_computerremotemgmt_migration($migration);
      do_computerarch_migration($migration);
      do_computeroperatingsystem_migration($migration);
      do_dblocks_migration($migration);
      do_rule_migration($migration);
      do_task_migration($migration);

   // ********* Migration SNMP discovery and inventory ********************** //

      // ********* Rename tables ******************************************** //
      $migration->renameTable("glpi_plugin_fusioninventory_rangeip",
                              "glpi_plugin_fusioninventory_ipranges");
      $migration->renameTable("glpi_plugin_fusioninventory_networking_ports",
                              "glpi_plugin_fusinvsnmp_networkports");
      $migration->renameTable("glpi_plugin_fusioninventory_construct_device",
                              "glpi_plugin_fusinvsnmp_constructdevices");
      $migration->renameTable("glpi_plugin_fusioninventory_construct_mibs",
                              "glpi_plugin_fusioninventory_snmpmodelconstructdevice_miboids");
      $migration->renameTable("glpi_plugin_fusioninventory_networking",
                              "glpi_plugin_fusioninventory_networkequipments");
      $migration->renameTable("glpi_plugin_fusioninventory_networking_ifaddr",
                              "glpi_plugin_fusinvsnmp_networkequipmentips");
      $migration->renameTable("glpi_plugin_fusioninventory_printers",
                              "glpi_plugin_fusinvsnmp_printers");
      $migration->renameTable("glpi_plugin_fusioninventory_printers_cartridges",
                              "glpi_plugin_fusinvsnmp_printercartridges");
      $migration->renameTable("glpi_plugin_fusioninventory_printers_history",
                              "glpi_plugin_fusinvsnmp_printerlogs");
      $migration->renameTable("glpi_plugin_fusioninventory_model_infos",
                              "glpi_plugin_fusioninventory_snmpmodels");
      $migration->renameTable("glpi_plugin_fusioninventory_mib_networking",
                              "glpi_plugin_fusinvsnmp_modelmibs");
      $migration->renameTable("glpi_plugin_fusioninventory_snmp_connection",
                              "glpi_plugin_fusinvsnmp_configsecurities");
      $migration->renameTable("glpi_plugin_fusioninventory_snmp_history",
                              "glpi_plugin_fusinvsnmp_networkportlogs");
      $migration->renameTable("glpi_plugin_fusioninventory_snmp_history_connections",
                              "glpi_plugin_fusinvsnmp_networkportconnectionlogs");

      // ********* Migration ************************************************ //
      $prepare_task = do_iprange_migration($migration);
      do_iprangeconfigsecurity_migration($migration);
      do_configlogfield_migration($migration);
      do_networkport_migration($migration);
      do_printer_migration($migration);
      do_networkequipment_migration($migration);
      do_configsecurity_migration($migration);
      do_statediscovery_migration($migration);
      do_mapping_migration($migration);
      do_snmpmodel_migration($migration);

   // ********* Migration deploy ******************************************** //

      // ********* Rename tables ******************************************** //

      // ********* Migration ************************************************ //
      do_deployfile_migration($migration);
      do_deploypackage_migration($migration);
      do_deploymirror_migration($migration);
      do_deploygroup_migration($migration);
      do_deployuserinteraction_migration($migration);
      migrateTablesFromFusinvDeploy($migration);

   // ********* Migration ESX *********************************************** //

      // ********* Rename tables ******************************************** //

      // ********* Migration ************************************************ //
      do_credentialESX_migration($migration);

   // ********* Migration Collect ******************************************* //

      // ********* Rename tables ******************************************** //

      // ********* Migration ************************************************ //
      do_collect_migration($migration);

   // ********* Migration Tasks ********************************************* //

      // ********* Rename tables ******************************************** //

      // ********* Migration ************************************************ //
      pluginFusioninventoryUpdateTasks($migration, $plugins_id);
      do_timeslot_migration($migration);

   // ********* Drop tables not used **************************************** //

   $a_droptable = ['glpi_plugin_fusioninventory_agents_inventory_state',
                        'glpi_plugin_fusioninventory_config_modules',
                        'glpi_plugin_fusioninventory_connection_stats',
                        'glpi_plugin_fusioninventory_discovery',
                        'glpi_plugin_fusioninventory_errors',
                        'glpi_plugin_fusioninventory_lockable',
                        'glpi_plugin_fusioninventory_connection_history',
                        'glpi_plugin_fusioninventory_walks',
                        'glpi_plugin_fusioninventory_config_snmp_history',
                        'glpi_plugin_fusioninventory_config_snmp_networking',
                        'glpi_plugin_fusioninventory_task',
                        'glpi_plugin_fusinvinventory_pcidevices',
                        'glpi_plugin_fusinvinventory_pcivendors',
                        'glpi_plugin_fusinvinventory_usbdevices',
                        'glpi_plugin_fusinvinventory_usbvendors',
                        'glpi_plugin_fusinvsnmp_constructdevicewalks',
                        'glpi_plugin_fusioninventory_snmpmodelmiblabels',
                        'glpi_plugin_fusioninventory_snmpmodelmibobjects',
                        'glpi_plugin_fusioninventory_snmpmodelmiboids',
                        'glpi_plugin_fusioninventory_snmpmodelconstructdevices',
                        'glpi_plugin_fusioninventory_snmpmodelconstructdevicewalks'.
                        'glpi_plugin_fusioninventory_snmpmodelconstructdevices_users',
                        'glpi_plugin_fusioninventory_snmpmodelconstructdevice_miboids',
                        'glpi_plugin_fusioninventory_snmpmodelmibs',
                        'glpi_plugin_fusioninventory_snmpmodels',
                        'glpi_plugin_fusioninventory_snmpmodeldevices',
                        'glpi_plugin_fusinvsnmp_constructdevice_miboids',
                        'glpi_plugin_fusinvsnmp_constructdevices',
                        'glpi_plugin_fusinvsnmp_constructdevices_users',
                        'glpi_plugin_fusinvsnmp_miblabels',
                        'glpi_plugin_fusinvsnmp_mibobjects',
                        'glpi_plugin_fusinvsnmp_miboids',
                        'glpi_plugin_fusinvsnmp_modeldevices',
                        'glpi_plugin_fusinvsnmp_modelmibs',
                        'glpi_plugin_fusinvsnmp_models',
                        'glpi_plugin_fusioninventory_construct_walks',
                        'glpi_plugin_fusioninventory_deployorders',
                        'glpi_plugin_tracker_computers',
                        'glpi_plugin_tracker_connection_history',
                        'glpi_plugin_tracker_agents_processes',
                        'glpi_plugin_tracker_config_snmp_history',
                        'glpi_plugin_tracker_config_snmp_networking',
                        'glpi_plugin_tracker_config_snmp_printer',
                        'glpi_plugin_tracker_config_snmp_script',
                        'glpi_plugin_tracker_connection_stats',
                        'glpi_plugin_tracker_discovery',
                        'glpi_plugin_tracker_errors',
                        'glpi_plugin_tracker_model_infos',
                        'glpi_plugin_tracker_processes',
                        'glpi_plugin_tracker_processes_values',
                        'glpi_dropdown_plugin_tracker_snmp_auth_auth_protocol',
                        'glpi_dropdown_plugin_tracker_snmp_auth_priv_protocol',
                        'glpi_dropdown_plugin_tracker_snmp_auth_sec_level',
                        'glpi_dropdown_plugin_tracker_snmp_version',
                        'glpi_plugin_tracker_computers',
                        'glpi_plugin_tracker_config',
                        'glpi_plugin_tracker_config_discovery',
                        'glpi_plugin_tracker_tmp_connections',
                        'glpi_plugin_tracker_tmp_netports',
                        'glpi_plugin_tracker_walks',
                        'glpi_plugin_fusioninventory_agents_errors',
                        'glpi_plugin_fusioninventory_agents_processes',
                        'glpi_plugin_fusioninventory_computers',
                        'glpi_plugin_fusioninventory_config_snmp_networking',
                        'glpi_plugin_fusioninventory_config_snmp_history',
                        'glpi_plugin_fusinvsnmp_agentconfigs',
                        'glpi_dropdown_plugin_fusioninventory_mib_label',
                        'glpi_dropdown_plugin_fusioninventory_mib_object',
                        'glpi_dropdown_plugin_fusioninventory_mib_oid',
                        'glpi_dropdown_plugin_fusioninventory_snmp_auth_auth_protocol',
                        'glpi_dropdown_plugin_fusioninventory_snmp_auth_priv_protocol',
                        'glpi_dropdown_plugin_fusioninventory_snmp_version',
                        'glpi_plugin_fusinvsnmp_temp_profiles',
                        'glpi_plugin_fusinvsnmp_tmp_agents',
                        'glpi_plugin_fusinvsnmp_tmp_configs',
                        'glpi_plugin_fusinvsnmp_tmp_tasks',
                        'glpi_plugin_fusioninventory_networkequipmentips',
                        'glpi_plugin_fusioninventory_inventorycomputerbatteries',
                        'glpi_plugin_fusioninventory_inventorycomputerchemistries'
       ];

   foreach ($a_droptable as $newTable) {
      $migration->dropTable($newTable);
   }

    /*
      $a_table = array();

      //table name
      $a_table['name'] = '';
      $a_table['oldname'] = array(
      );

      // fields : fields that are new, have changed type or just stay the same
      //    array(
      //        <fieldname> = array(
      //            'type' => <type>, 'value' => <value>)
      //    );
      $a_table['fields'] = array(

      );

      // oldfields = fields that need to be removed
      //    array( 'field0', 'field1', ...);
      $a_table['oldfields'] = array(
      );

      // renamefields = fields that need to be renamed
      //    array('oldname' = 'newname', ...)
      $a_table['renamefields'] = array(
      );

      // keys : new, changed or not
      //    array( 'field' => <fields>, 'name' => <keyname> , 'type' => <keytype>)
      // <fields> : fieldnames needed by the key
      //            ex : array('field0' , 'field1' ...)
      //            ex : 'fieldname'
      // <keyname> : the name of the key (if blank, the fieldname is used)
      // <type> : the type of key (ex: INDEX, ...)
      $a_table['keys'] = array(
      );

      // oldkeys : keys that need to be removed
      //    array( 'key0', 'key1', ... )
      $a_table['oldkeys'] = array(
      );
   */

   //Push task functionnality
   $migration->addField('glpi_plugin_fusioninventory_tasks', 'last_agent_wakeup', 'datetime');
   $migration->addField('glpi_plugin_fusioninventory_tasks', 'wakeup_agent_counter', "int(11) NOT NULL DEFAULT '0'");
   $migration->addField('glpi_plugin_fusioninventory_tasks', 'wakeup_agent_time', "int(11) NOT NULL DEFAULT '0'");
   $migration->addField('glpi_plugin_fusioninventory_tasks', 'reprepare_if_successful', "tinyint(1) NOT NULL DEFAULT '1'");
   $deploy_on_demand = $migration->addField('glpi_plugin_fusioninventory_tasks', 'is_deploy_on_demand', "tinyint(1) NOT NULL DEFAULT '0'");
   $migration->addKey('glpi_plugin_fusioninventory_tasks', 'wakeup_agent_counter');
   $migration->addKey('glpi_plugin_fusioninventory_tasks', 'reprepare_if_successful');
   $migration->addKey('glpi_plugin_fusioninventory_tasks', 'is_deploy_on_demand');
   $migration->migrationOneTable('glpi_plugin_fusioninventory_tasks');

   //deploy on demand task migration :
   //the way to detect a deploy on demand task was by looking at it's name
   //we've now introduced a boolean to easily check for it
   if ($deploy_on_demand) {
      $task = new PluginFusioninventoryTask();
      foreach (getAllDataFromTable('glpi_plugin_fusioninventory_tasks',
         ['name' => ['LIKE', '%[self-deploy]%']]) as $tsk) {
         $task->update(['id' => $tsk['id'], 'is_deploy_on_demand' => 1]);
      }
   }

      /*
       * Update pci and usb ids and oui
       */
   foreach (['usbid.sql', 'pciid.sql', 'oui.sql'] as $sql) {
      $DB_file = PLUGIN_FUSIONINVENTORY_DIR ."/install/mysql/$sql";
      $DBf_handle = fopen($DB_file, "rt");
      $sql_query = fread($DBf_handle, filesize($DB_file));
      fclose($DBf_handle);
      foreach (explode(";\n", "$sql_query") as $sql_line) {
         if (!empty($sql_line)) {
            $DB->query($sql_line);/* or die($DB->error())*/
         }
      }
   }

   /*
    * Clean display preferences not used
    */
   $DB->delete(
      'glpi_displaypreferences', [
         'itemtype' => [
            '5150',
            '5160',
            '5161',
            '5163',
            '5165',
            '5190'
         ]
      ]
   );

   // If no PluginFusioninventoryTaskjoblog in preferences, add them
   $iterator = $DB->request([
      'FROM'   => 'glpi_displaypreferences',
      'WHERE'  => [
         'itemtype'  => 'PluginFusioninventoryTaskjoblog',
         'users_id'  => 0
      ]
   ]);
   if (!count($iterator)) {
      $insert = $DB->buildInsert(
         'glpi_displaypreferences', [
            'itemtype'  => 'PluginFusioninventoryTaskjoblog',
            'num'       => new \QueryParam(),
            'rank'      => new \QueryParam(),
            'users_id'  => 0
         ]
      );

      $stmt = $DB->prepare($insert);
      $insert_data = [
         [2, 1],
         [3, 2],
         [4, 3],
         [5, 4],
         [6, 5],
         [7, 6],
         [8, 7]
      ];
      foreach ($insert_data as $idata) {
         $stmt->bind_param(
            'ss',
            $idata[0],
            $idata[1]
         );
         $stmt->execute();
      }
      mysqli_stmt_close($stmt);
   }

   /*
    * Convert taskjob definition from PluginFusinvsnmpIPRange to PluginFusioninventoryIPRange
    * onvert taskjob definition from PluginFusinvdeployPackage to PluginFusioninventoryDeployPackage
    */
   $iterator = $DB->request([
      'FROM' => 'glpi_plugin_fusioninventory_taskjobs'
   ]);
   if (count($iterator)) {
      $update = $DB->buildUpdate(
         'glpi_plugin_fusioninventory_taskjobs', [
            'targets'   => new \QueryParam()
         ], [
            'id'        => new \QueryParam()
         ]
      );
      $stmt = $DB->prepare($update);

      while ($data = $iterator->next()) {
         $a_defs = importArrayFromDB($data['targets']);
         foreach ($a_defs as $num=>$a_def) {
            if (key($a_def) == 'PluginFusinvsnmpIPRange') {
               $a_defs[$num] = ['PluginFusioninventoryIPRange'=>current($a_def)];
            } else if (key($a_def) == 'PluginFusinvdeployPackage') {
               $a_defs[$num] = ['PluginFusioninventoryDeployPackage'=>current($a_def)];
            }
         }

         $targets = exportArrayToDB($a_defs);
         $stmt->bind_param(
            'ss',
            $targets,
            $data['id']
         );
         $stmt->execute();
      }

      mysqli_stmt_close($stmt);
   }

   /*
    * Convert taskjoblogs itemtype from PluginFusinvdeployPackage to
    * PluginFusioninventoryDeployPackage
    */
   $DB->update(
      'glpi_plugin_fusioninventory_taskjoblogs', [
         'itemtype' => 'PluginFusioninventoryDeployPackage'
      ], [
         'itemtype' => 'PluginFusinvdeployPackage'
      ]
   );

   /*
    * Convert taskjobstates itemtype from PluginFusinvdeployPackage to
    * PluginFusioninventoryDeployPackage
    */
   $DB->update(
      'glpi_plugin_fusioninventory_taskjobstates', [
         'itemtype' => 'PluginFusioninventoryDeployPackage'
      ], [
         'itemtype' => 'PluginFusinvdeployPackage'
      ]
   );

   /*
    * Convert taskjob action from PluginFusinvdeployGroup to PluginFusioninventoryDeployGroup
    */
   $iterator = $DB->request(['FROM' => 'glpi_plugin_fusioninventory_taskjobs']);
   if (count($iterator)) {
      $update = $DB->buildUpdate(
         'glpi_plugin_fusioninventory_taskjobs', [
            'actors' => new \QueryParam()
         ], [
            'id'     => new \QueryParam()
         ]
      );
      $stmt = $DB->prepare($update);

      while ($data = $iterator->next()) {
         $a_defs = importArrayFromDB($data['actors']);
         foreach ($a_defs as $num=>$a_def) {
            if (key($a_def) == 'PluginFusinvdeployGroup') {
               $a_defs[$num] = ['PluginFusioninventoryDeployGroup'=>current($a_def)];
            }
         }

         $actors = exportArrayToDB($a_defs);
         $stmt->bind_param(
            'ss',
            $actors,
            $data['id']
         );
         $stmt->execute();
      }

      mysqli_stmt_close($stmt);
   }

   /*
    * Convert taskjob method deployuninstall in deployinstall
    */
   $DB->update(
      'glpi_plugin_fusioninventory_taskjobs', [
         'method' => 'deployinstall'
      ], [
         'method' => 'deployuninstall'
      ]
   );

   /*
    *  Manage configuration of plugin
    */
   $config = new PluginFusioninventoryConfig();
   $pfSetup = new PluginFusioninventorySetup();
   $users_id = $pfSetup->createFusionInventoryUser();
   $a_input = [];
   $a_input['ssl_only'] = 0;
   $a_input['delete_task'] = 20;
   $a_input['inventory_frequence'] = 24;
   $a_input['agent_port'] = 62354;
   $a_input['extradebug'] = 0;
   $a_input['users_id'] = $users_id;
   $a_input['agents_old_days'] = 0;
   $a_input['agents_action'] = 0;
   $a_input['agents_status'] = 0;
   $config->addValues($a_input, false);

   $a_input = [];
   $a_input['version'] = PLUGIN_FUSIONINVENTORY_VERSION;
   $config->addValues($a_input, true);
   $a_input = [];
   $a_input['ssl_only'] = 0;
   if (isset($prepare_Config['ssl_only'])) {
      $a_input['ssl_only'] = $prepare_Config['ssl_only'];
   }
   $a_input['delete_task'] = 20;
   $a_input['inventory_frequence'] = 24;
   $a_input['agent_port'] = 62354;
   $a_input['extradebug'] = 0;
   $a_input['users_id'] = 0;

   //Deploy configuration options
   $a_input['server_upload_path'] =
         Toolbox::addslashes_deep(
            implode(
               DIRECTORY_SEPARATOR,
               [
                  GLPI_PLUGIN_DOC_DIR,
                  'fusioninventory',
                  'upload'
               ]
            )
         );
   $a_input['alert_winpath']    = 1;
   $a_input['server_as_mirror'] = 1;
   $a_input['mirror_match']     = 0;
   $config->addValues($a_input, false);

   $pfSetup = new PluginFusioninventorySetup();
   $users_id = $pfSetup->createFusionInventoryUser();

   $DB->update(
      'glpi_plugin_fusioninventory_configs', [
         'value'  => $users_id
      ], [
         'type'   => 'users_id'
      ]
   );

   // Update fusinvinventory _config values to this plugin
   $input = [
      'import_software'                => 1,
      'import_volume'                  => 1,
      'import_antivirus'               => 1,
      'import_registry'                => 1,
      'import_process'                 => 1,
      'import_vm'                      => 1,
      'import_monitor_on_partial_sn'   => 0,
      'component_processor'            => 1,
      'component_memory'               => 1,
      'component_harddrive'            => 1,
      'component_networkcard'          => 1,
      'component_graphiccard'          => 1,
      'component_soundcard'            => 1,
      'component_drive'                => 1,
      'component_networkdrive'         => 1,
      'component_control'              => 1,
      'component_battery'              => 1,
      'states_id_default'              => 0,
      'location'                       => 0,
      'group'                          => 0,
      'manage_osname'                  => 0,
      'component_networkcardvirtual'   => 1,
      'reprepare_job'                  => 0
   ];
   $config->addValues($input, false);

   // Add new config values if not added
   $input = $config->initConfigModule(true);
   foreach ($input as $name=>$value) {
      $a_conf = $config->find(['type' => $name]);
      if (count($a_conf) == 0) {
         $config->add(['type' => $name, 'value' => $value]);
      }
   }

   $migration->displayMessage("Add Crontasks");
   /*
    * Add Crontask if not exist
    */
   $crontask = new CronTask();
   if ($crontask->getFromDBbyName('PluginFusioninventoryTaskjob', 'taskscheduler')) {
      $crontask->fields['itemtype'] = 'PluginFusioninventoryTask';
      $crontask->updateInDB(['itemtype']);
   }
   if (!$crontask->getFromDBbyName('PluginFusioninventoryTask', 'taskscheduler')) {
      CronTask::Register('PluginFusioninventoryTask', 'taskscheduler', '60',
                         ['mode' => 2, 'allowmode' => 3, 'logs_lifetime'=> 30]);
   }
   if ($crontask->getFromDBbyName('PluginFusioninventoryTaskjobstate', 'cleantaskjob')
           AND $crontask->getFromDBbyName('PluginFusioninventoryTaskjobstatus', 'cleantaskjob')) {
      $crontask->getFromDBbyName('PluginFusioninventoryTaskjobstatus', 'cleantaskjob');
      $crontask->delete($crontask->fields);
   }

   if ($crontask->getFromDBbyName('PluginFusioninventoryTaskjobstatus', 'cleantaskjob')) {
      $DB->update(
         'glpi_crontasks', [
            'itemtype'  => 'PluginFusioninventoryTaskjobstate'
         ], [
            'itemtype'  => 'PluginFusioninventoryTaskjobstatus'
         ]
      );
   }
   if (!$crontask->getFromDBbyName('PluginFusioninventoryTaskjobstate', 'cleantaskjob')) {
      CronTask::Register('PluginFusioninventoryTaskjobstate', 'cleantaskjob', (3600 * 24),
                         ['mode' => 2, 'allowmode' => 3, 'logs_lifetime' => 30]);
   }
   if ($crontask->getFromDBbyName('PluginFusinvsnmpNetworkPortLog', 'cleannetworkportlogs')) {
      $crontask->delete($crontask->fields);
   }
   if (!$crontask->getFromDBbyName('PluginFusioninventoryNetworkPortLog', 'cleannetworkportlogs')) {
      CronTask::Register('PluginFusioninventoryNetworkPortLog', 'cleannetworkportlogs', (3600 * 24),
                         ['mode'=>2, 'allowmode'=>3, 'logs_lifetime'=>30]);
   }
   if ($crontask->getFromDBbyName('PluginFusioninventoryConfigurationManagement', 'checkdevices')) {
      $crontask->delete($crontask->fields);
   }
   if ($crontask->getFromDBbyName('PluginFusioninventoryTaskjob', 'updatedynamictasks')) {
      $crontask->delete($crontask->fields);
   }
   if (!$crontask->getFromDBbyName('PluginFusioninventoryAgent', 'cleanoldagents')) {
      CronTask::Register('PluginFusioninventoryAgent', 'cleanoldagents', 86400,
                         ['mode'=>2, 'allowmode'=>3, 'logs_lifetime'=>30,
                               'hourmin' =>22, 'hourmax'=>6,
                               'comment'=>Toolbox::addslashes_deep(__('Delete agents that have not contacted the server since "xxx" days.', 'fusioninventory'))]);
   }
   if (!$crontask->getFromDBbyName('PluginFusioninventoryTask', 'cleanondemand')) {
      CronTask::Register('PluginFusioninventoryTask', 'cleanondemand', 86400,
                         ['mode'=>2, 'allowmode'=>3, 'logs_lifetime'=>30,
                          'comment' => Toolbox::addslashes_deep(__('Clean on demand deployment tasks'))]);
   }

   /*
    * Update task's agents list from dynamic group periodically in order to automatically target new
    * computer.
    */
   if (!$crontask->getFromDBbyName('PluginFusioninventoryAgentWakeup', 'wakeupAgents')) {
      CronTask::Register('PluginFusioninventoryAgentWakeup', 'wakeupAgents', 120,
                         ['mode'=>2, 'allowmode'=>3, 'logs_lifetime'=>30,
                               'comment'=>Toolbox::addslashes_deep(__('Wake agents ups'))]);
   }

   // Fix software version in computers. see https://github.com/fusioninventory/fusioninventory-for-glpi/issues/1810
   $iterator = $DB->request([
      'FROM'   => 'glpi_computers',
      'WHERE'  => ['entities_id' => ['>', 0]]
   ]);
   if (count($iterator)) {
      $update = $DB->buildUpdate(
         'glpi_items_softwareversions', [
            'entities_id'  => new \QueryParam()
         ], [
            'itemtype'     => 'Computer',
            'items_id'     => new \QueryParam(),
            'is_dynamic'   => 1,
            'entities_id'  => 0
         ]
      );
      $stmt = $DB->prepare($update);
      while ($data = $iterator->next()) {
         $stmt->bind_param(
            'ss',
            $data['entities_id'],
            $data['id']
         );
         $stmt->execute();
      }
      mysqli_stmt_close($stmt);
   }

   if ($DB->tableExists('glpi_plugin_fusioninventory_profiles')) {
      //Migrate rights to the new system introduction in GLPI 0.85
      PluginFusioninventoryProfile::migrateProfiles();
      //Drop old table
      $migration->dropTable('glpi_plugin_fusioninventory_profiles');
   }

   //Antivirus stuff has been integrated in GLPI's core
   if ($DB->tableExists('glpi_plugin_fusioninventory_inventorycomputerantiviruses')) {
      //Antivirus migration from FI table to GLPi core table
      $antivirus = new ComputerAntivirus();
      foreach (getAllDataFromTable('glpi_plugin_fusioninventory_inventorycomputerantiviruses') as $ant) {
         unset($ant['id']);
         $ant['is_dynamic'] = 1;
         if (isset($ant['uptodate'])) {
            $ant['is_uptodate'] = $ant['uptodate'];
            unset($ant['uptodate']);
         } else {
            $ant['is_uptodate'] = 0;
         }
         if (isset($ant['version'])) {
            $ant['antivirus_version'] = $ant['version'];
            unset($ant['version']);
         } else {
            $ant['antivirus_version'] = '';
         }
         $antivirus->add($ant, [], false);
      }
      $migration->dropTable('glpi_plugin_fusioninventory_inventorycomputerantiviruses');
   }

   //Create first access to the current profile is needed
   if (isset($_SESSION['glpiactiveprofile'])) {
      PluginFusioninventoryProfile::createFirstAccess($_SESSION['glpiactiveprofile']['id']);
   }

   // Add computer otherserial lock for version before 9.1+1.0 because we put
   // BIOS/assettag as computer.otherserial in 9.1+1.0.
   if ($current_version < 9.1) {
      require_once(PLUGIN_FUSIONINVENTORY_DIR . "/inc/lock.class.php");
      PluginFusioninventoryLock::addLocks('Computer', 0, ['otherserial']);
   }

   // ********* Clean orphan data ********************************************** //

   // Clean timeslotentries
   $query = "SELECT glpi_plugin_fusioninventory_timeslotentries.id
            FROM glpi_plugin_fusioninventory_timeslotentries
            LEFT JOIN glpi_plugin_fusioninventory_timeslots
               ON glpi_plugin_fusioninventory_timeslotentries.plugin_fusioninventory_timeslots_id = glpi_plugin_fusioninventory_timeslots.id
            WHERE glpi_plugin_fusioninventory_timeslots.id IS NULL";
   $result = $DB->query($query);
   while ($data = $DB->fetchArray($result)) {
      $DB->query('DELETE FROM glpi_plugin_fusioninventory_timeslotentries WHERE id='.$data['id']);
   }

   // Clean entities
   $query = "SELECT glpi_plugin_fusioninventory_entities.id
            FROM glpi_plugin_fusioninventory_entities
            LEFT JOIN glpi_entities
               ON glpi_plugin_fusioninventory_entities.entities_id = glpi_entities.id
            WHERE glpi_entities.id IS NULL";
   $result = $DB->query($query);
   while ($data = $DB->fetchArray($result)) {
      $DB->query('DELETE FROM glpi_plugin_fusioninventory_entities WHERE id='.$data['id']);
   }

   // Clean packages
   $tables = [
      'glpi_plugin_fusioninventory_deploypackages_entities',
      'glpi_plugin_fusioninventory_deploypackages_groups',
      'glpi_plugin_fusioninventory_deploypackages_profiles',
      'glpi_plugin_fusioninventory_deploypackages_users'
   ];
   foreach ($tables as $table) {
      $query = "SELECT ".$table.".id
               FROM ".$table."
               LEFT JOIN glpi_plugin_fusioninventory_deploypackages
                  ON ".$table.".plugin_fusioninventory_deploypackages_id = glpi_plugin_fusioninventory_deploypackages.id
               WHERE glpi_plugin_fusioninventory_deploypackages.id IS NULL";
      $result = $DB->query($query);
      while ($data = $DB->fetchArray($result)) {
         $DB->query('DELETE FROM '.$table.' WHERE id='.$data['id']);
      }
   }

   // Migrate search params for dynamic groups
   doDynamicDataSearchParamsMigration();
}


/**
 * Manage the agent part migration
 *
 * @global object $DB
 * @param object $migration
 * @return array
 */
function do_agent_migration($migration) {
   global $DB;

   /*
    *  Table glpi_plugin_fusioninventory_agents
    */
   $newTable = "glpi_plugin_fusioninventory_agents";
   $prepare_rangeip = [];
   $prepare_agentConfig = [];
   if ($DB->tableExists("glpi_plugin_tracker_agents")
              && $DB->fieldExists("glpi_plugin_tracker_agents",
                              "ifaddr_start")) {
      $iterator = $DB->request(['FROM' => 'glpi_plugin_tracker_agents']);
      while ($data = $iterator->next()) {
         $prepare_rangeip[] = [
            "ip_start"=> $data['ifaddr_start'],
            "ip_end"  => $data['ifaddr_end'],
            "name"    => $data['name']
         ];
         $prepare_agentConfig[] = [
            "name" => $data["name"],
            "lock" => $data['lock'],
            "threads_networkinventory" => $data['nb_process_query'],
            "threads_networkdiscovery" => $data['nb_process_discovery']
         ];
      }
   } else if ($DB->tableExists("glpi_plugin_tracker_agents")
                  AND $DB->fieldExists("glpi_plugin_tracker_agents",
                              "core_discovery")) {
      $iterator = $DB->request(['FROM' => 'glpi_plugin_tracker_agents']);
      while ($data = $iterator->next()) {
         $prepare_agentConfig[] = [
            "name" => $data["name"],
            "lock" => $data['lock'],
            "threads_networkinventory" => $data['threads_query'],
            "threads_networkdiscovery" => $data['threads_discovery']
         ];
      }
   } else if ($DB->tableExists("glpi_plugin_fusioninventory_agents")) {
      if ($DB->fieldExists($newTable, "module_snmpquery")) {
         $iterator = $DB->request(['FROM' => 'glpi_plugin_tracker_agents']);
         while ($data = $iterator->next()) {
            $prepare_agentConfig[] = [
               "id" => $data["ID"],
               "threads_networkinventory" => $data['threads_query'],
               "threads_networkdiscovery" => $data['threads_discovery'],
               "NETORKINVENTORY" => $data['module_snmpquery'],
               "NETWORKDISCOVERY" => $data['module_netdiscovery'],
               "INVENTORY" => $data['module_inventory'],
               "WAKEONLAN" => $data['module_wakeonlan']
            ];
         }
      }
   }

   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_agents';
   $a_table['oldname'] = ['glpi_plugin_tracker_agents'];

   $a_table['fields']  = [];
   $a_table['fields']['id']            = ['type'    => 'autoincrement',
                                                'value'   => ''];
   $a_table['fields']['entities_id']   = ['type'    => 'integer',
                                                'value'   => null];
   $a_table['fields']['is_recursive']  = ['type'    => 'bool',
                                                'value'   => '1'];
   $a_table['fields']['name']          = ['type'    => 'string',
                                                'value'   => null];
   $a_table['fields']['last_contact']  = ['type'    => 'datetime',
                                                'value'   => null];
   $a_table['fields']['version']       = ['type'    => 'string',
                                                'value'   => null];
   $a_table['fields']['lock']          = ['type'    => 'bool',
                                                'value'   => null];
   $a_table['fields']['device_id']     = ['type'    => 'string',
                                                'value'   => null];
   $a_table['fields']['computers_id']  = ['type'    => 'integer',
                                                'value'   => null];
   $a_table['fields']['token']         = ['type'    => 'string',
                                                'value'   => null];
   $a_table['fields']['useragent']     = ['type'    => 'string',
                                                'value'   => null];
   $a_table['fields']['tag']           = ['type'    => 'string',
                                                'value'   => null];
   $a_table['fields']['threads_networkdiscovery'] = [
      'type' => "int(4) NOT NULL DEFAULT '1' COMMENT 'array(xmltag=>value)'",
      'value'   => null];

   $a_table['fields']['threads_networkinventory'] = [
      'type' => "int(4) NOT NULL DEFAULT '1' COMMENT 'array(xmltag=>value)'",
      'value'   => null];

   $a_table['fields']['senddico']      = [
      'type'    => 'bool',
      'value'   => null
   ];

   $a_table['fields']['timeout_networkdiscovery'] = [
      'type' => "int(4) NOT NULL DEFAULT '0' COMMENT 'Network Discovery task timeout'",
      'value'   => null
   ];
   $a_table['fields']['timeout_networkinventory'] = [
      'type' => "int(4) NOT NULL DEFAULT '0' COMMENT 'Network Inventory task timeout'",
      'value'   => null
   ];
   $a_table['fields']['agent_port']    = ['type'    => 'varchar(6)',
                                                'value'   => null];

   $a_table['oldfields']  = [
      'module_snmpquery',
      'module_netdiscovery',
      'module_inventory',
      'module_wakeonlan',
      'core_discovery',
      'threads_discovery',
      'core_query',
      'threads_query',
      'tracker_agent_version',
      'logs',
      'fragment',
      'itemtype',
      'device_type'];

   $a_table['renamefields'] = [];
   $a_table['renamefields']['ID'] = 'id';
   $a_table['renamefields']['last_agent_update'] = 'last_contact';
   $a_table['renamefields']['fusioninventory_agent_version'] = 'version';
   $a_table['renamefields']['key'] = 'device_id';
   $a_table['renamefields']['on_device'] = 'computers_id';
   $a_table['renamefields']['items_id'] = 'computers_id';

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => 'name', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'device_id', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'computers_id', 'name' => '', 'type' => 'INDEX'];

   $a_table['oldkeys'] = ['key'];

   migrateTablesFusionInventory($migration, $a_table);

   /*
   * Table glpi_plugin_fusioninventory_agentmodules
   */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_agentmodules';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => 'autoincrement',
                                             'value'   => ''];
   $a_table['fields']['modulename'] = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['is_active']  = ['type'    => 'bool',
                                             'value'   => null];
   $a_table['fields']['exceptions'] = ['type'    => 'text',
                                             'value'   => null];

   $a_table['oldfields']  = [];
   $a_table['oldfields'][] = 'plugins_id';
   $a_table['oldfields'][] = 'entities_id';
   $a_table['oldfields'][] = 'url';

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => 'modulename', 'name' => '', 'type' => 'UNIQUE'];

   $a_table['oldkeys'] = ['unicity', 'entities_id'];

   migrateTablesFusionInventory($migration, $a_table);

   /*
    * Add Deploy module
    */
   $iterator = $DB->request([
      'FROM'   => 'glpi_plugin_fusioninventory_agentmodules',
      'WHERE'  => ['modulename' => 'DEPLOY'],
      'LIMIT'  => 1
   ]);
   if (!count($iterator)) {
      $DB->insert(
         'glpi_plugin_fusioninventory_agentmodules', [
            'modulename'   => 'DEPLOY',
            'is_active'    => 0,
            'exceptions'   => exportArrayToDB([])
         ]
      );
   }

   /*
    * Add WakeOnLan module appear in version 2.3.0
    */
   $iterator = $DB->request([
      'FROM'   => 'glpi_plugin_fusioninventory_agentmodules',
      'WHERE'  => ['modulename' => 'WAKEONLAN'],
      'LIMIT'  => 1
   ]);
   if (!count($iterator)) {
      $agentmodule = new PluginFusioninventoryAgentmodule;
      $input = [
         'modulename'   => "WAKEONLAN",
         'is_active'  => 0,
         'exceptions' => exportArrayToDB([])
      ];
      $agentmodule->add($input);
   }

   /*
    * Add SNMPQUERY module if not present
    */
   $DB->update(
      'glpi_plugin_fusioninventory_agentmodules', [
         'modulename'   => 'NETWORKINVENTORY'
      ], [
         'modulename'   => 'SNMPQUERY'
      ]
   );

   $iterator = $DB->request([
      'FROM'   => 'glpi_plugin_fusioninventory_agentmodules',
      'WHERE'  => ['modulename' => 'NETWORKINVENTORY'],
      'LIMIT'  => 1
   ]);
   if (!count($iterator)) {
      $agentmodule = new PluginFusioninventoryAgentmodule;
      $input = [];
      $input['modulename'] = "NETWORKINVENTORY";
      $input['is_active']  = 0;
      $input['exceptions'] = exportArrayToDB([]);
      $agentmodule->add($input);
   }

   /*
    * Add NETDISCOVERY module if not present
    */
   $DB->update(
      'glpi_plugin_fusioninventory_agentmodules', [
         'modulename'   => 'NETWORKDISCOVERY'
      ], [
         'modulename'   => 'NETDISCOVERY'
      ]
   );

   $iterator = $DB->request([
      'SELECT' => ['id'],
      'FROM'   => 'glpi_plugin_fusioninventory_agentmodules',
      'WHERE'  => ['modulename' => 'NETWORKDISCOVERY'],
      'LIMIT'  => 1
   ]);

   if (!count($iterator)) {
      $agentmodule = new PluginFusioninventoryAgentmodule;
      $input = [
         'modulename'   => "NETWORKDISCOVERY",
         'is_active'    => 0,
         'exceptions'   => exportArrayToDB([])
      ];
      $agentmodule->add($input);
   }

   /*
    * Add INVENTORY module if not present
    */
   $iterator = $DB->request([
      'SELECT' => ['id'],
      'FROM'   => 'glpi_plugin_fusioninventory_agentmodules',
      'WHERE'  => ['modulename' => 'INVENTORY'],
      'LIMIT'  => 1
   ]);
   if (!count($iterator)) {
      $agentmodule = new PluginFusioninventoryAgentmodule;
      $input = [
         'modulename'   => "INVENTORY",
         'is_active'    => 1,
         'exceptions'   => exportArrayToDB([])
      ];
      $agentmodule->add($input);
   }

   /*
    * Add ESX module appear in version 2.4.0(0.80+1.0)
    */
   $DB->update(
      'glpi_plugin_fusioninventory_agentmodules', [
         'modulename'   => 'InventoryComputerESX'
      ], [
         'modulename'   => 'ESX'
      ]
   );

   $agentmodule = new PluginFusioninventoryAgentmodule();
   $iterator = $DB->request([
      'SELECT' => ['id'],
      'FROM'   => 'glpi_plugin_fusioninventory_agentmodules',
      'WHERE'  => ['modulename' => 'InventoryComputerESX'],
      'LIMIT'  => 1
   ]);
   if (!count($iterator)) {
      $input = [
         'modulename'   => "InventoryComputerESX",
         'is_active'    => 0,
         'exceptions'   => exportArrayToDB([])
      ];
      $url= '';
      if (isset($_SERVER['HTTP_REFERER'])) {
         $url = $_SERVER['HTTP_REFERER'];
      }
      $agentmodule->add($input);
   }

   /*
    * Add Collect module appear in version 0.84+2.0
    */
   $agentmodule = new PluginFusioninventoryAgentmodule();
   $iterator = $DB->request([
      'SELECT' => ['id'],
      'FROM'   => 'glpi_plugin_fusioninventory_agentmodules',
      'WHERE'  => ['modulename' => 'Collect'],
      'LIMIT'  => 1
   ]);
   if (!count($iterator)) {
      $input = [
         'modulename'   => "Collect",
         'is_active'    => 1,
         'exceptions'   => exportArrayToDB([])
      ];
      $agentmodule->add($input);
   }

   /*
    * Migrate data of table glpi_plugin_fusinvsnmp_agentconfigs into
    * glpi_plugin_fusioninventory_agents
    */
   if ($DB->tableExists("glpi_plugin_fusinvsnmp_agentconfigs")) {
      $iterator = $DB->request(['FROM' => 'glpi_plugin_fusinvsnmp_agentconfigs']);
      if (count($iterator)) {
         $update = $DB->buildUpdate(
            'glpi_plugin_fusioninventory_agents', [
               'threads_networkdiscovery' => new \QueryParam(),
               'threads_networkinventory' => new \QueryParam(),
               'senddico'                 => new \QueryParam()
            ], [
               'id'                       => new \QueryParam()
            ]
         );
         $stmt = $DB->prepare($update);
         while ($data = $iterator->next()) {
            $stmt->bind_param(
               'ssss',
               $data['threads_netdiscovery'],
               $data['threads_snmpquery'],
               $data['senddico'],
               $data['plugin_fusioninventory_agents_id']
            );
            $stmt->execute();
         }
         mysqli_stmt_close($stmt);
      }
   }

   changeDisplayPreference("5158", "PluginFusioninventoryAgent");

   // Delete data in glpi_logs(agent problem => ticket http://forge.fusioninventory.org/issues/1546)
   // ** Token
   $DB->delete(
      'glpi_logs', [
         'itemtype'           => 'PluginFusioninventoryAgent',
         'id_search_option'   => 9
      ]
   );

   // ** Last contact
   $DB->delete(
      'glpi_logs', [
         'itemtype'           => 'PluginFusioninventoryAgent',
         'id_search_option'   => 4
      ]
   );

   // ** Version
   $DB->delete(
      'glpi_logs', [
         'itemtype'           => 'PluginFusioninventoryAgent',
         'id_search_option'   => 8,
         'old_value'          => new \QueryExpression($DB->quoteName('new_value'))
      ]
   );

   return $prepare_rangeip;
}


/**
 * Manage the configuration part migration
 *
 * @global object $DB
 * @param object $migration
 * @return array
 */
function do_config_migration($migration) {
   global $DB;

   $prepare_Config = [];
   /*
    * Table glpi_plugin_fusioninventory_configs
    */
   if ($DB->tableExists('glpi_plugin_tracker_config')) {
      if ($DB->fieldExists('glpi_plugin_tracker_config', 'ssl_only')) {
         $iterator = $DB->request([
            'FROM'   => 'glpi_plugin_tracker_config',
            'LIMIT'  => 1
         ]);
         if (count($iterator)) {
            $data = $iterator->next();
            $prepare_Config['ssl_only'] = $data['ssl_only'];
         }
      }
   }
   if ($DB->tableExists('glpi_plugin_fusioninventory_configs')) {
      $id = 'id';
      if ($DB->fieldExists('glpi_plugin_fusioninventory_configs', 'ID')) {
         $id = 'ID';
      }

      $iterator = $DB->request([
         'FROM'   => 'glpi_plugin_fusioninventory_configs',
         'WHERE'  => ['type' => 'version'],
         'START'  => 1,
         'LIMIT'  => 10
      ]);
      if (count($iterator)) {
         $delete = $DB->buildDelete(
            'glpi_plugin_fusioninventory_configs', [
               $id => new \QueryParam()
            ]
         );
         $stmt = $DB->prepare($delete);
         while ($data = $iterator->next()) {
            $stmt->bind_param('s', $data['id']);
            $stmt->execute();
         }
         mysqli_stmt_close($stmt);
      }
   }

   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_configs';
   $a_table['oldname'] = ['glpi_plugin_tracker_config'];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => 'autoincrement',
                                            'value'   => ''];
   $a_table['fields']['type']       = ['type'    => 'string',
                                            'value'   => null];
   $a_table['fields']['value']      = ['type'    => 'string',
                                            'value'   => null];

   $a_table['oldfields']  = [];
   $a_table['oldfields'][] = 'version';
   $a_table['oldfields'][] = 'URL_agent_conf';
   $a_table['oldfields'][] = 'ssl_only';
   $a_table['oldfields'][] = 'authsnmp';
   $a_table['oldfields'][] = 'inventory_frequence';
   $a_table['oldfields'][] = 'criteria1_ip';
   $a_table['oldfields'][] = 'criteria1_name';
   $a_table['oldfields'][] = 'criteria1_serial';
   $a_table['oldfields'][] = 'criteria1_macaddr';
   $a_table['oldfields'][] = 'criteria2_ip';
   $a_table['oldfields'][] = 'criteria2_name';
   $a_table['oldfields'][] = 'criteria2_serial';
   $a_table['oldfields'][] = 'criteria2_macaddr';
   $a_table['oldfields'][] = 'delete_agent_process';
   $a_table['oldfields'][] = 'activation_history';
   $a_table['oldfields'][] = 'activation_connection';
   $a_table['oldfields'][] = 'activation_snmp_computer';
   $a_table['oldfields'][] = 'activation_snmp_networking';
   $a_table['oldfields'][] = 'activation_snmp_peripheral';
   $a_table['oldfields'][] = 'activation_snmp_phone';
   $a_table['oldfields'][] = 'activation_snmp_printer';
   $a_table['oldfields'][] = 'plugins_id';
   $a_table['oldfields'][] = 'module';

   $a_table['renamefields'] = [];
   $a_table['renamefields']['ID'] = 'id';

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => ["type"],
                              'name' => 'unicity',
                              'type' => 'UNIQUE'];

   $a_table['oldkeys'] = ['unicity'];

   migrateTablesFusionInventory($migration, $a_table);

   return $prepare_Config;
}


/**
 * Manage the entities part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_entities_migration($migration) {
   global $DB;

   /*
    * Table glpi_plugin_fusioninventory_entities
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_entities';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => 'autoincrement',
                                            'value'   => ''];
   $a_table['fields']['entities_id']= ['type'    => 'integer',
                                            'value'   => null];
   $a_table['fields']['transfers_id_auto']= ['type'    => 'integer',
                                              'value'   => null];
   $a_table['fields']['agent_base_url']= ['type'    => 'string',
                                              'value'   => ''];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => ['entities_id', 'transfers_id_auto'],
                              'name' => 'entities_id',
                              'type' => 'INDEX'];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);
   if (countElementsInTable($a_table['name']) == 0) {
      $a_configs = getAllDataFromTable('glpi_plugin_fusioninventory_configs',
         ['type' => 'transfers_id_auto']);
      $transfers_id_auto = 0;
      if (count($a_configs) > 0) {
         $a_config = current($a_configs);
         $transfers_id_auto = $a_config['value'];
      }

      $a_configs = getAllDataFromTable('glpi_plugin_fusioninventory_configs',
         ['type' => 'agent_base_url']);
      $agent_base_url = '';
      if (count($a_configs) > 0) {
         $a_config = current($a_configs);
         $agent_base_url = $a_config['value'];
      }

      $DB->insert(
         'glpi_plugin_fusioninventory_entities', [
            'entities_id'        => 0,
            'transfers_id_auto'  => $transfers_id_auto,
            'agent_base_url'     => $agent_base_url
         ]
      );
   } else if (countElementsInTable($a_table['name']) > 0) {
      $a_configs = getAllDataFromTable('glpi_plugin_fusioninventory_configs',
         ['type' => 'agent_base_url']);
      $agent_base_url = '';
      if (count($a_configs) > 0) {
         $a_config = current($a_configs);
         $agent_base_url = $a_config['value'];

         $DB->update(
            'glpi_plugin_fusioninventory_entities', [
               'agent_base_url' => $agent_base_url
            ], [true]
         );
      }
   }
}


/**
 * Manage the IP range part migration
 *
 * @global object $DB
 * @param object $migration
 * @return array
 */
function do_iprange_migration($migration) {
   global $DB;

   $prepare_task = [];

   /*
    * Table glpi_plugin_fusioninventory_ipranges
    */
   if ($DB->tableExists("glpi_plugin_tracker_rangeip")) {
      // Get all data to create task
      $iterator = $DB->request(['FROM' => 'glpi_plugin_tracker_rangeip']);
      while ($data = $iterator->next()) {
         if ($data['discover'] == '1') {
            $prepare_task[] = ["agents_id" => $data['FK_tracker_agents'],
                                    "ipranges_id" => $data['ID'],
                                    "netdiscovery" => "1"];
         }
         if ($data['query'] == '1') {
            $prepare_task[] = ["agents_id" => $data['FK_tracker_agents'],
                                    "ipranges_id" => $data['ID'],
                                    "snmpquery" => "1"];
         }
      }
   }
   if ($DB->tableExists("glpi_plugin_fusioninventory_rangeip")
           && $DB->fieldExists("glpi_plugin_fusioninventory_rangeip",
                           "FK_fusioninventory_agents_discover")) {

      // Get all data to create task
      $iterator = $DB->request(['FROM' => 'glpi_plugin_fusioninventory_rangeip']);
      while ($data = $iterator->next()) {
         if ($data['discover'] == '1') {
            $prepare_task[] = ["agents_id" => $data['FK_fusioninventory_agents_discover'],
                                    "ipranges_id" => $data['ID'],
                                    "netdiscovery" => "1"];
         }
         if ($data['query'] == '1') {
            $prepare_task[] = ["agents_id" => $data['FK_fusioninventory_agents_query'],
                                    "ipranges_id" => $data['ID'],
                                    "snmpquery" => "1"];
         }
      }
   }
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_ipranges';
   $a_table['oldname'] = ['glpi_plugin_tracker_rangeip', 'glpi_plugin_fusinvsnmp_ipranges'];

   $a_table['fields']  = [
      'id'         => ['type'    => 'autoincrement',    'value'   => ''],
      'name'       => ['type'    => 'string',           'value'   => null],
      'entities_id'=> ['type'    => 'integer',          'value'   => null],
      'ip_start'   => ['type'    => 'string',           'value'   => null],
      'ip_end'     => ['type'    => 'string',           'value'   => null]
   ];

   $a_table['oldfields']  = [
      'FK_tracker_agents',
      'discover',
      'query',
      'FK_fusioninventory_agents_discover',
      'FK_fusioninventory_agents_query',
      'construct_device_id',
      'log',
      'comment'
   ];

   $a_table['renamefields'] = [
      'ID'           => 'id',
      'ifaddr_start' => 'ip_start',
      'ifaddr_end'   => 'ip_end',
      'FK_entities'  => 'entities_id'
   ];

   $a_table['keys']   = [
      ['field' => 'entities_id', 'name' => '', 'type' => 'INDEX']
   ];

   $a_table['oldkeys'] = [
      'FK_tracker_agents',
      'FK_tracker_agents_2'
   ];

   migrateTablesFusionInventory($migration, $a_table);

   changeDisplayPreference("5159", "PluginFusinvsnmpIPRange");

   return $prepare_task;
}


/**
 * Manage the locks part migration
 *
 * @param object $migration
 */
function do_locks_migration($migration) {
   global $DB;

   /*
    * Table glpi_plugin_fusioninventory_locks
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_locks';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => 'autoincrement',
                                            'value'   => ''];
   $a_table['fields']['tablename']  = [
                     'type'    => "varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''",
                     'value'   => null];
   $a_table['fields']['items_id']   = ['type'    => 'integer',
                                            'value'   => null];
   $a_table['fields']['tablefields']= ['type'    => 'text',
                                            'value'   => null];

   $a_table['oldfields']  = ['itemtype'];

   $a_table['renamefields'] = [];
   $a_table['renamefields']['fields'] = 'tablefields';

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => 'tablename', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'items_id' , 'name' => '', 'type' => 'INDEX'];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);

   // Deduplicate entries

   $iterator = $DB->request([
      'SELECT'  => [
         'tablename',
         'COUNT' => ['tablename as cpt'],
         'items_id'
      ],
      'FROM'    => 'glpi_plugin_fusioninventory_locks',
      'GROUPBY' => [
         'tablename',
         'items_id'
      ],
      'HAVING' => [
         'cpt' => ['>', 1]
      ]
   ]);
   while ($data = $iterator->next()) {
      $DB->query("DELETE FROM glpi_plugin_fusioninventory_locks ".
      "WHERE `tablename`='".$data['tablename']."' AND `items_id`='".$data['items_id']."' ".
      "ORDER BY ID desc LIMIT ".($data['cpt'] - 1));
   }

   // add unique key
   $a_table['keys'][] = ['field' => ["tablename", "items_id"],
                         'name' => 'unicity', 'type' => 'UNIQUE'];
   migrateTablesFusionInventory($migration, $a_table);

}


/**
 * Manage the SNMP communities linked to IP range part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_iprangeconfigsecurity_migration($migration) {
   global $DB;

   /*
    * Table glpi_plugin_fusioninventory_ipranges_configsecurities
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_ipranges_configsecurities';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => 'autoincrement',
                                            'value'   => ''];
   $a_table['fields']['plugin_fusioninventory_ipranges_id']   = ['type'    => 'integer',
                                            'value'   => null];
   $a_table['fields']['plugin_fusioninventory_configsecurities_id']   = ['type'    => 'integer',
                                            'value'   => null];
   $a_table['fields']['rank']       = ['type'    => 'integer',
                                            'value'   => '1'];

   $a_table['oldfields']    = [];

   $a_table['renamefields'] = [];

   $a_table['keys']         = [];

   $a_table['oldkeys']      = [];

   migrateTablesFusionInventory($migration, $a_table);

   /*
    *  Clean SNMP communities orphelin associated to deleted ipranges
    */
   $iterator = $DB->request([
      'SELECT'    => 'glpi_plugin_fusioninventory_ipranges_configsecurities.id',
      'FROM'      => 'glpi_plugin_fusioninventory_ipranges_configsecurities',
      'LEFT JOIN' => [
         'glpi_plugin_fusioninventory_ipranges' => [
            'FKEY'   => [
               'glpi_plugin_fusioninventory_ipranges_configsecurities'  => 'plugin_fusioninventory_ipranges_id',
               'glpi_plugin_fusioninventory_ipranges'                   => 'id'
            ]
         ]
      ],
      'WHERE'     => ['glpi_plugin_fusioninventory_ipranges_configsecurities.id' => null]
   ]);
   if (count($iterator)) {
      $delete = $DB->buildDelete(
         'glpi_plugin_fusioninventory_ipranges_configsecurities', [
            'id' => new \QueryParam()
         ]
      );
      $stmt = $DB->prepare($delete);
      while ($data = $iterator->next()) {
         $stmt->bind_param('s', $data['id']);
         $stmt->execute();
      }
      mysqli_stmt_close($stmt);
   }
}


/**
 * Manage the mapping part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_mapping_migration($migration) {
   global $DB;

   /*
    * Table glpi_plugin_fusioninventory_mappings
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_mappings';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => 'autoincrement',
                                            'value'   => ''];
   $a_table['fields']['itemtype']   = [
                     'type'    => "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL",
                     'value'   => null];
   $a_table['fields']['name']       = ['type'    => 'string',
                                            'value'   => null];
   $a_table['fields']['table']      = ['type'    => 'string',
                                            'value'   => null];
   $a_table['fields']['tablefield'] = ['type'    => 'string',
                                            'value'   => null];
   $a_table['fields']['locale']     = ['type'    => "int(4) NOT NULL DEFAULT '0'",
                                            'value'   => null];
   $a_table['fields']['shortlocale']= ['type'    => 'int(4) DEFAULT NULL',
                                            'value'   => null];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => 'name', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'itemtype' , 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'table', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'tablefield' , 'name' => '', 'type' => 'INDEX'];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);
   pluginFusioninventoryUpdatemapping();

   /*
    * Fix problem with mapping with many entries with same mapping
    */
   $a_mapping = [];
   $a_mappingdouble = [];
   $iterator = $DB->request([
      'FROM'   => 'glpi_plugin_fusioninventory_mappings',
      'ORDER'  => 'id'
   ]);
   while ($data = $iterator->next()) {
      if (!isset($a_mapping[$data['itemtype'].".".$data['name']])) {
         $a_mapping[$data['itemtype'].".".$data['name']] = $data['id'];
      } else {
         $a_mappingdouble[$data['id']] = $data['itemtype'].".".$data['name'];
      }
   }
   foreach ($a_mappingdouble as $mapping_id=>$mappingkey) {
      $DB->update(
         'glpi_plugin_fusioninventory_printercartridges', [
            'plugin_fusioninventory_mappings_id'   => $a_mapping[$mappingkey]
         ], [
            'plugin_fusioninventory_mappings_id'   => $mapping_id
         ]
      );

      $DB->update(
         'glpi_plugin_fusioninventory_networkportlogs', [
            'plugin_fusioninventory_mappings_id'   => $a_mapping[$mappingkey]
         ], [
            'plugin_fusioninventory_mappings_id'   => $mapping_id
         ]
      );

      $DB->update(
         'glpi_plugin_fusioninventory_configlogfields', [
            'plugin_fusioninventory_mappings_id'   => $a_mapping[$mappingkey]
         ], [
            'plugin_fusioninventory_mappings_id'   => $mapping_id
         ]
      );

      $DB->delete(
         'glpi_plugin_fusioninventory_mappings', [
            'id'  => $mapping_id
         ]
      );
   }
}


/**
 * Manage the profile part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_profile_migration($migration) {
   global $DB;

   if ($DB->tableExists('glpi_plugin_fusioninventory_profiles')) {
      /*
       * Table glpi_plugin_fusioninventory_profiles
       */
      $a_table = [];
      $a_table['name'] = 'glpi_plugin_fusioninventory_profiles';
      $a_table['oldname'] = [];

      $a_table['fields']  = [];
      $a_table['fields']['id']         = ['type'    => 'autoincrement',
                                               'value'   => ''];
      $a_table['fields']['type']       = ['type'    => 'string',
                                               'value'   => ''];
      $a_table['fields']['right']      = ['type'    => 'char',
                                               'value'   => null];
      $a_table['fields']['plugins_id'] = ['type'    => 'integer',
                                               'value'   => null];
      $a_table['fields']['profiles_id']= ['type'    => 'integer',
                                               'value'   => null];

      $a_table['oldfields']  = [
          'name',
          'interface',
          'is_default',
          'snmp_networking',
          'snmp_printers',
          'snmp_models',
          'snmp_authentification',
          'rangeip',
          'agents',
          'remotecontrol',
          'agentsprocesses',
          'unknowndevices',
          'reports',
          'deviceinventory',
          'netdiscovery',
          'snmp_query',
          'wol',
          'configuration'];

      $a_table['renamefields'] = [];
      $a_table['renamefields']['ID'] = 'id';

      $a_table['keys']   = [];

      $a_table['oldkeys'] = [];

      migrateTablesFusionInventory($migration, $a_table);

      // Remove multiple lines can have problem with unicity
      $query = "SELECT * , count(`id`) AS cnt
         FROM `glpi_plugin_fusioninventory_profiles`
         GROUP BY `type`,`plugins_id`,`profiles_id`
         HAVING cnt >1
         ORDER BY cnt";
      $result=$DB->query($query);
      while ($data=$DB->fetchArray($result)) {
         //DB::delete() not yet supports limit nor order
         $queryd = "DELETE FROM `glpi_plugin_fusioninventory_profiles`
               WHERE `type`='".$data['type']."'
                  AND `plugins_id`='".$data['plugins_id']."'
                  AND `profiles_id`='".$data['profiles_id']."'
               ORDER BY `id` DESC
               LIMIT ".($data['cnt'] - 1)." ";
         $DB->query($queryd);
      }

      $a_table = [];
      $a_table['name'] = 'glpi_plugin_fusioninventory_profiles';
      $a_table['oldname'] = [];

      $a_table['fields']  = [];

      $a_table['oldfields']  = [];

      $a_table['renamefields'] = [];

      $a_table['keys']   = [];
      $a_table['keys'][] = ['field' => ["type", "plugins_id", "profiles_id"],
                                 'name' => 'unicity', 'type' => 'UNIQUE'];

      $a_table['oldkeys'] = [];

      migrateTablesFusionInventory($migration, $a_table);
   }
}


/**
 * Manage the timeslot (of task) part migration
 *
 * @param object $migration
 */
function do_timeslot_migration($migration) {
   /*
    * Table glpi_plugin_fusioninventory_timeslots
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_timeslots';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']           = ['type'    => 'autoincrement',
                                              'value'   => ''];
   $a_table['fields']['entities_id']  = ['type'    => 'integer',
                                              'value'   => null];
   $a_table['fields']['is_recursive'] = ['type'    => 'bool',
                                              'value'   => '0'];
   $a_table['fields']['name']         = ['type'    => 'string',
                                              'value'   => null];
   $a_table['fields']['comment']      = ['type'    => 'text',
                                              'value'   => null];
   $a_table['fields']['date_mod']     = ['type'    => 'datetime',
                                              'value'   => null];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);

   /*
    * Table glpi_plugin_fusioninventory_timeslotentries
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_timeslotentries';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']           = ['type'    => 'autoincrement',
                                              'value'   => ''];
   $a_table['fields']['plugin_fusioninventory_timeslots_id']  = ['type'    => 'integer',
                                              'value'   => null];
   $a_table['fields']['entities_id']  = ['type'    => 'integer',
                                              'value'   => null];
   $a_table['fields']['is_recursive'] = ['type'    => 'bool',
                                              'value'   => '0'];
   $a_table['fields']['day']          = ['type'    => 'bool',
                                              'value'   => 1];
   $a_table['fields']['begin']        = ['type'    => 'int(11) DEFAULT NULL',
                                              'value'   => null];
   $a_table['fields']['end']          = ['type'    => 'int(11) DEFAULT NULL',
                                              'value'   => null];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);
}


/**
 * Manage the unmanaged devices part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_unmanaged_migration($migration) {
   global $DB;

   /*
    * Table glpi_plugin_fusioninventory_unmanageds
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_unmanageds';
   $a_table['oldname'] = [
       'glpi_plugin_fusioninventory_unknowndevices',
       'glpi_plugin_tracker_unknown_device'];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => 'autoincrement',
                                            'value'   => ''];
   $a_table['fields']['name']       = ['type'    => 'string',
                                            'value'   => null];
   $a_table['fields']['date_mod']   = ['type'    => 'datetime',
                                            'value'   => null];
   $a_table['fields']['entities_id']= ['type'    => 'integer',
                                            'value'   => null];
   $a_table['fields']['locations_id']= ['type'    => 'integer',
                                            'value'   => null];
   $a_table['fields']['is_deleted'] = ['type'    => 'bool',
                                            'value'   => null];
   $a_table['fields']['users_id']   = ['type'    => 'integer',
                                            'value'   => null];
   $a_table['fields']['serial']     = ['type'    => 'string',
                                            'value'   => null];
   $a_table['fields']['otherserial']= ['type'    => 'string',
                                            'value'   => null];
   $a_table['fields']['contact']    = ['type'    => 'string',
                                            'value'   => null];
   $a_table['fields']['domain']     = ['type'    => 'integer',
                                            'value'   => null];
   $a_table['fields']['comment']    = ['type'    => 'text',
                                            'value'   => null];
   $a_table['fields']['item_type']  = ['type'    => 'string',
                                            'value'   => null];
   $a_table['fields']['accepted']   = ['type'    => 'bool',
                                            'value'   => null];
   $a_table['fields']['plugin_fusioninventory_agents_id'] = ['type'    => 'integer',
                                            'value'   => null];
   $a_table['fields']['ip']         = ['type'    => 'string',
                                            'value'   => null];
   $a_table['fields']['hub']        = ['type'    => 'bool',
                                            'value'   => null];
   $a_table['fields']['states_id']  = ['type'    => 'integer',
                                            'value'   => null];
   $a_table['fields']['sysdescr']   = ['type'    => 'text',
                                            'value'   => null];
   $a_table['fields']['plugin_fusioninventory_configsecurities_id'] = ['type'    => 'integer',
                                            'value'   => null];
   $a_table['fields']['is_dynamic'] = ['type'    => 'bool',
                                            'value'   => null];
   $a_table['fields']['serialized_inventory'] = ['type'    => 'longblob',
                                            'value'   => null];

   $a_table['oldfields']  = [
      'dnsname',
      'snmp',
      'FK_model_infos',
      'FK_snmp_connection',
      'FK_agent',
      'mac',
      'ifmac',
      'plugin_fusinvsnmp_models_id',
      'plugin_fusioninventory_snmpmodels_id',
       'is_template'
      ];

   $a_table['renamefields'] = [
      'ID'           => 'id',
      'comments'     => 'comment',
      'type'         => 'item_type',
      'ifaddr'       => 'ip',
      'FK_entities'  => 'entities_id',
      'location'     => 'locations_id',
      'deleted'      => 'is_deleted',
      'plugin_fusinvsnmp_configsecurities_id' => 'plugin_fusioninventory_configsecurities_id'
       ];

   $a_table['keys']   = [
      ['field' => 'entities_id', 'name' => '', 'type' => 'INDEX'],
      ['field' => 'plugin_fusioninventory_agents_id', 'name' => '', 'type' => 'INDEX'],
      ['field' => 'is_deleted', 'name' => '', 'type' => 'INDEX'],
      ['field' => 'date_mod', 'name' => '', 'type' => 'INDEX']
   ];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);

   if ($DB->tableExists('glpi_plugin_fusinvsnmp_unknowndevices')) {
      $iterator = $DB->request(['FROM' => 'glpi_plugin_fusinvsnmp_unknowndevices']);
      if (count($iterator)) {
         $update = $DB->buildUpdate(
            'glpi_plugin_fusioninventory_unmanageds', [
               'sysdescr'                                   => new \QueryParam(),
               'plugin_fusioninventory_configsecurities_id' => new \QueryParam()
            ], [
               'id'                                         => new \QueryParam()
            ]
         );
         $stmt = $DB->prepare($update);
         while ($data = $iterator->next()) {
            $stmt->bind_param(
               'sss',
               $data['sysdescr'],
               $data['plugin_fusinvsnmp_configsecurities_id'],
               $data['plugin_fusioninventory_unknowndevices_id']
            );
         }
         mysqli_stmt_close($stmt);
      }
      $migration->dropTable('glpi_plugin_fusinvsnmp_unknowndevices');
   }

   changeDisplayPreference("5153", "PluginFusioninventoryUnmanaged");
   changeDisplayPreference("PluginFusioninventoryUnknownDevice",
                           "PluginFusioninventoryUnmanaged");

   /*
    * Delete IP and MAC of PluginFusioninventoryUnmanaged in displaypreference
    */
   $DB->delete(
      'glpi_displaypreferences', [
         'itemtype'  => 'PluginFusioninventoryUnmanaged',
         'OR'        => [
            'num' => [11, 12, 16]
         ]
      ]
   );

   /*
    * Convert itemtype from glpi_plugin_fusioninventory_unknowndevices to
    * PluginFusioninventoryUnmanaged
    */
   $tables = ['glpi_networkports', 'glpi_logs',
      'glpi_plugin_fusioninventory_ignoredimportdevices'];
   foreach ($tables as $table) {
      $DB->update(
         $table, [
            'itemtype'  => 'PluginFusioninventoryUnmanaged'
         ], [
            'itemtype'  => 'PluginFusioninventoryUnknowndevice'
         ]
      );
   }

   $DB->update(
      'glpi_ipaddresses', [
         'mainitemtype' => 'PluginFusioninventoryUnmanaged'
      ], [
         'mainitemtype' => 'PluginFusioninventoryUnknowndevice'
      ]
   );
}


/**
 * Manage the ignored import rules part migration
 *
 * @param object $migration
 */
function do_ignoredimport_migration($migration) {
   /*
    * Table glpi_plugin_fusioninventory_ignoredimportdevices
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_ignoredimportdevices';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => 'autoincrement',
                                            'value'   => ''];
   $a_table['fields']['name']       = ['type'    => 'string',
                                            'value'   => null];
   $a_table['fields']['date']       = ['type'    => 'datetime',
                                            'value'   => null];
   $a_table['fields']['itemtype']   = [
                     'type'    => "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL",
                     'value'   => null];
   $a_table['fields']['entities_id']= ['type'    => 'integer',
                                            'value'   => null];
   $a_table['fields']['ip']         = ['type'    => 'string',
                                            'value'   => null];
   $a_table['fields']['mac']        = ['type'    => 'string',
                                            'value'   => null];
   $a_table['fields']['rules_id']   = ['type'    => 'integer',
                                            'value'   => null];
   $a_table['fields']['method']     = ['type'    => 'string',
                                            'value'   => null];
   $a_table['fields']['serial']     = ['type'    => 'string',
                                            'value'   => null];
   $a_table['fields']['uuid']       = ['type'    => 'string',
                                            'value'   => null];
   $a_table['fields']['plugin_fusioninventory_agents_id']
                                    = ['type'    => 'integer',
                                            'value'   => null];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => 'plugin_fusioninventory_agents_id',
                              'name' => '',
                              'type' => 'INDEX'];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);
}


/**
 * Manage the computer blacklist part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_blacklist_migration($migration) {
   global $DB;
   /*
    * Table glpi_plugin_fusioninventory_inventorycomputercriterias
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_inventorycomputercriterias';
   $a_table['oldname'] = ['glpi_plugin_fusinvinventory_criterias'];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => 'autoincrement',
                                             'value'   => ''];
   $a_table['fields']['name']       = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['comment']    = ['type'    => 'text',
                                             'value'   => null];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => 'name', 'name' => '', 'type' => 'INDEX'];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);

   /*
   * Table glpi_plugin_fusioninventory_inventorycomputerblacklists
   */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_inventorycomputerblacklists';
   $a_table['oldname'] = ['glpi_plugin_fusinvinventory_blacklists'];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => 'autoincrement',
                                             'value'   => ''];
   $a_table['fields']['plugin_fusioninventory_criterium_id'] = ['type'    => 'integer',
                                                                     'value'   => null];
   $a_table['fields']['value']  = ['type'    => 'string',
                                          'value'   => null];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => 'plugin_fusioninventory_criterium_id',
                              'name' => '',
                              'type' => 'KEY'];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);
   $DB->listFields($a_table['name'], false);

   /*
   *  Udpate criteria for blacklist
   */
   $a_criteria = [
         'Serial number'       => 'ssn',
         'uuid'                => 'uuid',
         'Mac address'         => 'macAddress',
         'Windows product key' => 'winProdKey',
         'Model'               => 'smodel',
         'storage serial'      => 'storagesSerial',
         'drives serial'       => 'drivesSerial',
         'Asset Tag'           => 'assetTag',
         'Computer name'       => 'name',
         'Manufacturer'        => 'manufacturer'
   ];

   foreach ($a_criteria as $name=>$comment) {
      $iterator = $DB->request([
         'FROM'   => 'glpi_plugin_fusioninventory_inventorycomputercriterias',
         'WHERE'  => ['name' => $name]
      ]);
      if (!count($iterator)) {
         $DB->insert(
            'glpi_plugin_fusioninventory_inventorycomputercriterias', [
               'name'      => $name,
               'comment'   => $comment
            ]
         );
      }
   }
   $a_criteria = [];
   $iterator = $DB->request(['FROM' => 'glpi_plugin_fusioninventory_inventorycomputercriterias']);
   while ($data = $iterator->next()) {
      $a_criteria[$data['comment']] = $data['id'];
   }

   /*
   * Update blacklist
   */
   $newTable = "glpi_plugin_fusioninventory_inventorycomputerblacklists";
   // * ssn
   $a_input = [
      'N/A',
      '(null string)',
      'INVALID',
      'SYS-1234567890',
      'SYS-9876543210',
      'SN-12345',
      'SN-1234567890',
      '1111111111',
      '1111111',
      '1',
      '0123456789',
      '12345',
      '123456',
      '1234567',
      '12345678',
      '123456789',
      '1234567890',
      '123456789000',
      '12345678901234567',
      '0000000000',
      '000000000',
      '00000000',
      '0000000',
      '0000000',
      'NNNNNNN',
      'xxxxxxxxxxx',
      'EVAL',
      'IATPASS',
      'none',
      'To Be Filled By O.E.M.',
      'Tulip Computers',
      'Serial Number xxxxxx',
      'SN-123456fvgv3i0b8o5n6n7k',
      'Unknow',
      'System Serial Number',
      'MB-1234567890',
      '0',
      'empty',
      'Not Specified',
      'OEM_Serial',
      'SystemSerialNumb'];

   foreach ($a_input as $value) {
      $iterator = $DB->request([
         'FROM'   => $newTable,
         'WHERE'  => [
            'plugin_fusioninventory_criterium_id'  => $a_criteria['ssn'],
            'value'                                => $value
         ]
      ]);
      if (!count($iterator)) {
         $DB->insert(
            $newTable, [
               'plugin_fusioninventory_criterium_id'  => $a_criteria['ssn'],
               'value'                                => $value
            ]
         );
      }
   }

   // * uuid
   $a_input = [
      'FFFFFFFF-FFFF-FFFF-FFFF-FFFFFFFFFFFF',
      '03000200-0400-0500-0006-000700080009',
      '6AB5B300-538D-1014-9FB5-B0684D007B53',
      '01010101-0101-0101-0101-010101010101',
      '2'];

   foreach ($a_input as $value) {
      $iterator = $DB->request([
         'FROM'   => $newTable,
         'WHERE'  => [
            'plugin_fusioninventory_criterium_id'  => $a_criteria['uuid'],
            'value'                                => $value
         ]
      ]);
      if (!count($iterator)) {
         $DB->insert(
            $newTable, [
               'plugin_fusioninventory_criterium_id'  => $a_criteria['uuid'],
               'value'                                => $value
            ]
         );
      }
   }

   // * macAddress
   $a_input = [
      '20:41:53:59:4e:ff',
      '02:00:4e:43:50:49',
      'e2:e6:16:20:0a:35',
      'd2:0a:2d:a0:04:be',
      '00:a0:c6:00:00:00',
      'd2:6b:25:2f:2c:e7',
      '33:50:6f:45:30:30',
      '0a:00:27:00:00:00',
      '00:50:56:C0:00:01',
      '00:50:56:C0:00:08',
      '02:80:37:EC:02:00',
      '50:50:54:50:30:30',
      '24:b6:20:52:41:53',
      '00:50:56:C0:00:02',
      '00:50:56:C0:00:03',
      '00:50:56:C0:00:04',
      'FE:FF:FF:FF:FF:FF',
      '00:00:00:00:00:00',
      '00:0b:ca:fe:00:00'];
   foreach ($a_input as $value) {
      $iterator = $DB->request([
         'FROM'   => $newTable,
         'WHERE'  => [
            'plugin_fusioninventory_criterium_id'  => $a_criteria['macAddress'],
            'value'                                => $value
         ]
      ]);
      if (!count($iterator)) {
         $DB->insert(
            $newTable, [
               'plugin_fusioninventory_criterium_id'  => $a_criteria['macAddress'],
               'value'                                => $value
            ]
         );
      }
   }

   // * smodel
   $a_input = [
      'Unknow',
      'To Be Filled By O.E.M.',
      '*',
      'System Product Name',
      'Product Name',
      'System Name',
      'All Series'];
   foreach ($a_input as $value) {
      $iterator = $DB->request([
         'FROM'   => $newTable,
         'WHERE'  => [
            'plugin_fusioninventory_criterium_id'  => $a_criteria['smodel'],
            'value'                                => $value
         ]
      ]);
      if (!count($iterator)) {
         $DB->insert(
            $newTable, [
               'plugin_fusioninventory_criterium_id'  => $a_criteria['smodel'],
               'value'                                => $value
            ]
         );
      }
   }

   // * manufacturer
   $a_input = ['System manufacturer'];
   foreach ($a_input as $value) {
      $iterator = $DB->request([
         'FROM'   => $newTable,
         'WHERE'  => [
            'plugin_fusioninventory_criterium_id'  => $a_criteria['manufacturer'],
            'value'                                => $value
         ]
      ]);
      if (!count($iterator)) {
         $DB->insert(
            $newTable, [
               'plugin_fusioninventory_criterium_id'  => $a_criteria['manufacturer'],
               'value'                                => $value
            ]
         );
      }
   }

   // * ip
   $iterator = $DB->request([
      'FROM'   => 'glpi_plugin_fusioninventory_inventorycomputercriterias',
      'WHERE'  => ['name' => 'IP']
   ]);
   if (!count($iterator)) {
      $DB->insert(
         'glpi_plugin_fusioninventory_inventorycomputercriterias', [
            'id'        => 11,
            'name'      => 'IP',
            'comment'   => 'IP'
         ]
      );
   }

   $a_criteria = [];
   $iterator = $DB->request(['FROM' => 'glpi_plugin_fusioninventory_inventorycomputercriterias']);
   while ($data = $iterator->next()) {
      $a_criteria[$data['comment']] = $data['id'];
   }

   $a_input = ['0.0.0.0'];
   foreach ($a_input as $value) {
      $iterator = $DB->request([
         'FROM'   => $newTable,
         'WHERE'  => [
            'plugin_fusioninventory_criterium_id'  => $a_criteria['IP'],
            'value'                                => $value
         ]
      ]);
      if (!count($iterator)) {
         $DB->insert(
            $newTable, [
               'plugin_fusioninventory_criterium_id'  => $a_criteria['IP'],
               'value'                                => $value
            ]
         );
      }
   }

   changeDisplayPreference("PluginFusinvinventoryBlacklist",
                     "PluginFusioninventoryInventoryComputerBlacklist");

}


/**
 * Manage the rules matched log part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_rulematchedlog_migration($migration) {
   global $DB;

   /*
    * Table glpi_plugin_fusioninventory_rulematchedlogs
    */
   $newTable = "glpi_plugin_fusioninventory_rulematchedlogs";
   if (!$DB->tableExists($newTable)) {
      $query = "CREATE TABLE `".$newTable."` (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                   PRIMARY KEY (`id`)
               ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
      $DB->query($query);
   }
   $migration->changeField($newTable,
                              "id",
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");

   $migration->migrationOneTable($newTable);

   $migration->addField($newTable,
                              "date",
                              "datetime DEFAULT NULL");
   $migration->addField($newTable,
                              "items_id",
                              "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                              "itemtype",
                              "varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                              "rules_id",
                              "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                              "plugin_fusioninventory_agents_id",
                              "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                              "method",
                              "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                           "criteria",
                           'text COLLATE utf8_unicode_ci DEFAULT NULL');
   $migration->migrationOneTable($newTable);
   $DB->listFields($newTable, false);
}


/**
 * Manage the antivirus part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_antivirus_migration($migration) {
   /*
    * Table glpi_plugin_fusioninventory_inventorycomputerantiviruses
    */
   $newTable = "glpi_plugin_fusioninventory_inventorycomputerantiviruses";
   $migration->renameTable("glpi_plugin_fusinvinventory_antivirus", $newTable);
}


/**
 * Manage the computer extended part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_computercomputer_migration($migration) {
   global $DB;

   /*
    * Table glpi_plugin_fusioninventory_inventorycomputercomputers
    */
   if ($DB->tableExists("glpi_plugin_fusinvinventory_computers")
           && $DB->fieldExists("glpi_plugin_fusinvinventory_computers", "uuid")) {
      $Computer = new Computer();
      $iterator = $DB->request(['FROM' => 'glpi_plugin_fusinvinventory_computers']);
      while ($data = $iterator->next()) {
         if ($Computer->getFromDB($data['items_id'])) {
            $input = [
               'id'     => $data['items_id'],
               'uuid'   => $data['uuid']
            ];
            $Computer->update($input);
         }
      }
      $sql = "DROP TABLE `glpi_plugin_fusinvinventory_computers`";
      $DB->query($sql);
   }
   if ($DB->tableExists("glpi_plugin_fusinvinventory_tmp_agents")) {
      $sql = "DROP TABLE `glpi_plugin_fusinvinventory_tmp_agents`";
      $DB->query($sql);
   }
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_inventorycomputercomputers';
   $a_table['oldname'] = ['glpi_plugin_fusinvinventory_computers'];

   $a_table['fields']  = [];
   $a_table['fields']['id']                     = ['type'    => 'autoincrement',
                                                   'value'   => ''];
   $a_table['fields']['computers_id']           = ['type'    => 'integer',
                                                   'value'   => null];
   $a_table['fields']['operatingsystem_installationdate'] = ['type'    => 'datetime',
                                                             'value'   => null];
   $a_table['fields']['winowner']               = ['type'    => 'string',
                                                   'value'   => null];
   $a_table['fields']['wincompany']             = ['type'    => 'string',
                                                   'value'   => null];
   $a_table['fields']['last_fusioninventory_update']     = ['type'    => 'datetime',
                                                            'value'   => null];
   $a_table['fields']['remote_addr']            = ['type'    => 'string',
                                                   'value'   => null];
   $a_table['fields']['serialized_inventory']   = ['type'    => 'longblob',
                                                   'value'   => null];
   $a_table['fields']['is_entitylocked']        = ['type'    => 'bool',
                                                   'value'   => "0"];
   $a_table['fields']['oscomment']              = ['type'    => 'text',
                                                   'value'   => null];
   $a_table['fields']['last_boot']              = ['type'    => 'datetime',
                                                   'value'   => null];

   $a_table['oldfields']  = [
      'plugin_fusioninventory_computerarchs_id',
      'bios_assettag',
      'bios_date',
      'bios_version',
      'bios_manufacturers_id'
   ];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => 'computers_id', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'last_fusioninventory_update', 'name' => '', 'type' => 'INDEX'];

   $a_table['oldkeys'] = [];

   do_biosascomponentmigration();

   migrateTablesFusionInventory($migration, $a_table);

   // Migrate libserialization
   require_once(PLUGIN_FUSIONINVENTORY_DIR . "/inc/inventorycomputercomputer.class.php");
   $pfInventoryComputerComputer = new PluginFusioninventoryInventoryComputerComputer();
   if ($DB->tableExists('glpi_plugin_fusinvinventory_libserialization')) {
      $iterator = $DB->request(['FROM' => 'glpi_plugin_fusinvinventory_libserialization']);
      while ($data = $iterator->next()) {
         $a_pfcomputer = current($pfInventoryComputerComputer->find(
               ['computers_id' => $data['computers_id']],
               [], 1));
         if (empty($a_pfcomputer)) {
            // Add
            if (countElementsInTable("glpi_computers",
                  ['id' => $data['computers_id']]) > 0) {
               $input = [];
               $input['computers_id'] = $data['computers_id'];
               $input['last_fusioninventory_update'] = $data['last_fusioninventory_update'];
               $pfInventoryComputerComputer->add($input);
            }
         } else {
            // Update
            $a_pfcomputer['last_fusioninventory_update'] = $data['last_fusioninventory_update'];
            $pfInventoryComputerComputer->update($a_pfcomputer);
         }
      }
   }
   $migration->dropTable('glpi_plugin_fusinvinventory_libserialization');

   /*
    * Manage devices with is_dynamic
    */
   $iterator = $DB->request(['FROM' => 'glpi_plugin_fusioninventory_inventorycomputercomputers']);
   if (count($iterator)) {
      $update = $DB->buildUpdate(
         'glpi_computers', [
            'is_dynamic'   => 1
         ], [
            'id'           => new \QueryParam()
         ]
      );
      $stmt = $DB->prepare($update);
      while ($data = $iterator->next()) {
         $stmt->bind_param('s', $data['computers_id']);
         $stmt->execute();
      }
      mysqli_stmt_close($stmt);
   }
}


/**
 * A firmware component with a BIOS type has been added in GLPI 9.2
 *
 * @return void
 */
function do_biosascomponentmigration() {
   global $DB;

   //BIOS as a component
   if ($DB->tableExists('glpi_plugin_fusioninventory_inventorycomputercomputers') &&
      ($DB->fieldExists('glpi_plugin_fusioninventory_inventorycomputercomputers', 'bios_date')
      || $DB->fieldExists('glpi_plugin_fusioninventory_inventorycomputercomputers', 'bios_version')
      || $DB->fieldExists('glpi_plugin_fusioninventory_inventorycomputercomputers', 'bios_manufacturers_id'))
   ) {
      $bioses = [];
      //retrieve exiting
      $query = "SELECT computers_id, bios_date, bios_version, bios_manufacturers_id, glpi_manufacturers.name AS mname
                  FROM glpi_plugin_fusioninventory_inventorycomputercomputers
                  LEFT JOIN glpi_manufacturers
                     ON glpi_plugin_fusioninventory_inventorycomputercomputers.bios_manufacturers_id = glpi_manufacturers.id
                     WHERE
                        bios_date IS NOT NULL AND bios_date != ''
                        OR bios_version IS NOT NULL AND bios_version != ''
                        OR bios_manufacturers_id != 0";
      $result = $DB->query($query);

      $deviceBios = new DeviceFirmware();
      $item_DeviceBios  = new Item_DeviceFirmware();
      while ($data = $DB->fetchArray($result)) {
         $key = md5($data['bios_date'] . $data['bios_version']. $data['bios_manufacturers_id']);
         if (!isset($bioses[$key])) {
            //look for an existing BIOS in the database
            $iterator = $DB->request([
               'SELECT' => 'id',
               'FROM'   => 'glpi_devicefirmwares',
               'WHERE'  => [
                  'date'               => $data['bios_date'],
                  'version'            => $data['bios_version'],
                  'manufacturers_id'   => $data['bios_manufacturers_id']
               ],
               'START'  => 0,
               'LIMIT'  => 1
            ]);
            if (count($iterator)) {
               $existing = $iterator->next();
               $bioses[$key] = $existing['id'];
            } else {
               $designation = sprintf(
                  __('%1$s BIOS'),
                  $data['mname']
               );

               //not found in database, create it
               $deviceBios->add(
                  [
                     'designation'        => $designation,
                     'date'               => $data['bios_date'],
                     'version'            => $data['bios_version'],
                     'manufacturers_id'   => $data['bios_manufacturers_id']
                  ]
               );
               $bioses[$key] = $deviceBios->getID();
            }
         }

         //attach found/created component to computer
         $item_DeviceBios->add(
            [
               'items_id'           => $data['computers_id'],
               'itemtype'           => 'Computer',
               'devicefirmwares_id' => $bioses[$key],
               'is_dynamic'         => 1
            ]
         );
      }
   }
}


/**
 * Manage the computer inventory staistics part migration
 *
 * @param object $migration
 */
function do_computerstat_migration($migration) {
   global $DB;

   /*
    * Table glpi_plugin_fusioninventory_inventorycomputerstats
    */

   if (!$DB->tableExists("glpi_plugin_fusioninventory_inventorycomputerstats")) {
      $a_table = [];
      $a_table['name'] = 'glpi_plugin_fusioninventory_inventorycomputerstats';
      $a_table['oldname'] = [];

      $a_table['fields']  = [];
      $a_table['fields']['id']      = ['type'    => "smallint(3) NOT NULL AUTO_INCREMENT",
                                                         'value'   => ''];
      $a_table['fields']['day']     = ['type'    => "smallint(3) NOT NULL DEFAULT '0'",
                                                         'value'   => ''];
      $a_table['fields']['hour']    = ['type'    => "tinyint(2) NOT NULL DEFAULT '0'",
                                                         'value'   => ''];
      $a_table['fields']['counter'] = ['type'    => 'integer',
                                                         'value'   => null];

      $a_table['oldfields']  = [];

      $a_table['renamefields'] = [];

      $a_table['keys']   = [];

      $a_table['oldkeys'] = [];

      migrateTablesFusionInventory($migration, $a_table);

      require_once(PLUGIN_FUSIONINVENTORY_DIR . "/inc/inventorycomputerstat.class.php");
      PluginFusioninventoryInventoryComputerStat::init();
   }
}


/**
 * Manage the configuration log fields (for network equipment and printer)
 * part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_configlogfield_migration($migration) {
   global $DB;

   /*
    * glpi_plugin_fusioninventory_configlogfields
    */
   $newTable = "glpi_plugin_fusioninventory_configlogfields";
   $migration->renameTable("glpi_plugin_fusioninventory_config_snmp_history",
                           $newTable);
   $migration->renameTable("glpi_plugin_fusinvsnmp_configlogfields",
                           $newTable);
   if ($DB->tableExists($newTable)) {
      if ($DB->fieldExists($newTable, "field")) {
         $iterator = $DB->request(['FROM' => $newTable]);
         while ($data = $iterator->next()) {
            $pfMapping = new PluginFusioninventoryMapping();
            $mapping = 0;
            if ($mapping = $pfMapping->get("NetworkEquipment", $data['field'])) {
               $DB->update(
                  $newTable, [
                     'field'  => $mapping['id']
                  ], [
                     'field'  => $data['field']
                  ]
               );
            }
         }
      }
   }
   if (!$DB->tableExists($newTable)) {
      $query = "CREATE TABLE `".$newTable."` (
                  `id` int(8) NOT NULL AUTO_INCREMENT,
                   PRIMARY KEY (`id`)
               ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
      $DB->query($query);
   }
   $migration->changeField($newTable,
                           "ID",
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                           "id",
                           "id",
                           "int(8) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                           "plugin_fusioninventory_mappings_id",
                           "plugin_fusioninventory_mappings_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "days",
                           "days",
                           "int(255) NOT NULL DEFAULT '-1'");
   $migration->migrationOneTable($newTable);
   $migration->changeField($newTable,
                           "ID",
                           "id",
                           "int(8) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                           "field",
                           "plugin_fusioninventory_mappings_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->migrationOneTable($newTable);
   $migration->addField($newTable,
                           "id",
                           "int(8) NOT NULL AUTO_INCREMENT");
   $migration->addField($newTable,
                           "plugin_fusioninventory_mappings_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "days",
                           "int(255) NOT NULL DEFAULT '-1'");
   $migration->addKey($newTable,
                        "plugin_fusioninventory_mappings_id");
   $migration->migrationOneTable($newTable);
   $DB->listFields($newTable, false);

   $configLogField = new PluginFusioninventoryConfigLogField();
   $configLogField->initConfig();
}


/**
 * Manage the network port part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_networkport_migration($migration) {
   global $DB;

   /*
    * Table glpi_plugin_fusioninventory_networkportconnectionlogs
    */
   $newTable = "glpi_plugin_fusioninventory_networkportconnectionlogs";
   $migration->renameTable("glpi_plugin_fusinvsnmp_networkportconnectionlogs",
                           $newTable);

   if (!$DB->tableExists($newTable)) {
      $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
   }
   $migration->changeField($newTable,
                        "ID",
                        "id",
                        "int(11) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                        "id",
                        "id",
                        "int(11) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                        "date",
                        "date_mod",
                        "datetime DEFAULT NULL");
   $migration->changeField($newTable,
                        "date_mod",
                        "date_mod",
                        "datetime DEFAULT NULL");
   $migration->changeField($newTable,
                        "creation",
                        "creation",
                        "tinyint(1) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                        "FK_port_source",
                        "networkports_id_source",
                        "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                        "networkports_id_source",
                        "networkports_id_source",
                        "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                        "FK_port_destination",
                        "networkports_id_destination",
                        "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                        "networkports_id_destination",
                        "networkports_id_destination",
                        "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                        "plugin_fusioninventory_agentprocesses_id",
                        "plugin_fusioninventory_agentprocesses_id",
                        "int(11) NOT NULL DEFAULT '0'");
   $migration->dropField($newTable, "process_number");
   $migration->migrationOneTable($newTable);
   $migration->addField($newTable,
                        "id",
                        "int(11) NOT NULL AUTO_INCREMENT");
   $migration->addField($newTable,
                        "date_mod",
                        "datetime DEFAULT NULL");
   $migration->addField($newTable,
                        "creation",
                        "tinyint(1) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                        "networkports_id_source",
                        "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                        "networkports_id_destination",
                        "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                        "plugin_fusioninventory_agentprocesses_id",
                        "int(11) NOT NULL DEFAULT '0'");
   $migration->addKey($newTable,
                        ["networkports_id_source",
                              "networkports_id_destination",
                              "plugin_fusioninventory_agentprocesses_id"],
                        "networkports_id_source");
   $migration->addKey($newTable,
                        "date_mod");
   $migration->migrationOneTable($newTable);
   $DB->listFields($newTable, false);

   /*
    * Table glpi_plugin_fusioninventory_networkporttypes
    */
   $newTable = "glpi_plugin_fusioninventory_networkporttypes";
   $migration->renameTable("glpi_plugin_fusinvsnmp_networkporttypes",
                              $newTable);
   if (!$DB->tableExists($newTable)) {
      $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
      $DB->query($query);
   }
   $migration->changeField($newTable,
                           "id",
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                           "name",
                           "name",
                           "varchar(255) DEFAULT NULL");
   $migration->changeField($newTable,
                           "number",
                           "number",
                           "int(4) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "othername",
                           "othername",
                           "varchar(255) DEFAULT NULL");
   $migration->changeField($newTable,
                           "import",
                           "import",
                           "tinyint(1) NOT NULL DEFAULT '0'");
   $migration->migrationOneTable($newTable);
   $migration->addField($newTable,
                        "name",
                        "varchar(255) DEFAULT NULL");
   $migration->addField($newTable,
                        "number",
                        "int(4) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                        "othername",
                        "varchar(255) DEFAULT NULL");
   $migration->addField($newTable,
                        "import",
                        "tinyint(1) NOT NULL DEFAULT '0'");
   $migration->migrationOneTable($newTable);
   $DB->listFields($newTable, false);

   /*
    * glpi_plugin_fusioninventory_networkports
    */
   $newTable = "glpi_plugin_fusioninventory_networkports";
   $migration->renameTable("glpi_plugin_fusinvsnmp_networkports",
                           $newTable);
   $migration->renameTable("glpi_plugin_tracker_networking_ports",
                           $newTable);
   if (!$DB->tableExists($newTable)) {
      $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
   }
   $migration->changeField($newTable,
                           "id",
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                           "networkports_id",
                           "networkports_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "ifmtu",
                           "ifmtu",
                           "int(8) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "ifspeed",
                           "ifspeed",
                           "bigint(50) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "ifinternalstatus",
                           "ifinternalstatus",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->changeField($newTable,
                           "ifconnectionstatus",
                           "ifconnectionstatus",
                           "int(8) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "iflastchange",
                           "iflastchange",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->changeField($newTable,
                           "ifinoctets",
                           "ifinoctets",
                           "bigint(50) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "ifinerrors",
                           "ifinerrors",
                           "bigint(50) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "ifoutoctets",
                           "ifoutoctets",
                           "bigint(50) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "ifouterrors",
                           "ifouterrors",
                           "bigint(50) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "ifstatus",
                           "ifstatus",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->changeField($newTable,
                           "mac",
                           "mac",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->changeField($newTable,
                           "ifdescr",
                           "ifdescr",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->changeField($newTable,
                           "portduplex",
                           "portduplex",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->changeField($newTable,
                           "trunk",
                           "trunk",
                           "tinyint(1) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "lastup",
                           "lastup",
                           "datetime DEFAULT NULL");
   $migration->migrationOneTable($newTable);
   $migration->changeField($newTable,
                           "ID",
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                           "FK_networking_ports",
                           "networkports_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "ifmac",
                           "mac",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->dropKey($newTable,
                        "FK_networking_ports");
   $migration->migrationOneTable($newTable);
   $migration->addField($newTable,
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->addField($newTable,
                           "networkports_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "ifmtu",
                           "int(8) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "ifspeed",
                           "bigint(50) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "ifinternalstatus",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                           "ifconnectionstatus",
                           "int(8) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "iflastchange",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                           "ifinoctets",
                           "bigint(50) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "ifinerrors",
                           "bigint(50) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "ifoutoctets",
                           "bigint(50) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "ifouterrors",
                           "bigint(50) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "ifstatus",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                           "mac",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                           "ifdescr",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                           "ifalias",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                           "portduplex",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                           "trunk",
                           "tinyint(1) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "lastup",
                           "datetime DEFAULT NULL");
   $migration->addKey($newTable,
                        "networkports_id");
   $migration->migrationOneTable($newTable);
   $DB->listFields($newTable, false);

   /*
    * Table glpi_plugin_fusioninventory_networkportlogs
    */
   $newTable = "glpi_plugin_fusioninventory_networkportlogs";
   if ($DB->tableExists("glpi_plugin_tracker_snmp_history")) {
      // **** Update history
      update213to220_ConvertField($migration);

      // **** Migration network history connections
      $iterator = $DB->request([
         'FROM'   => 'glpi_plugin_tracker_snmp_history',
         'COUNT'  => 'cpt',
         'WHERE'  => ['Field' => 0]
      ]);
      $datas = $iterator->next();
      $nb = $datas['cpt'];

      //echo "Move Connections history to another table...";

      for ($i=0; $i < $nb; $i = $i + 500) {
         $migration->displayMessage("$i / $nb");
         $iterator = $DB->request([
            'FROM'   => 'glpi_plugin_tracker_snmp_history',
            'WHERE'  => ['Field' => 0],
            'ORDER'  => ['FK_process DESC', 'date_mod DESC'],
            'LIMIT'  => 500
         ]);
         while ($thread_connection = $iterator->next()) {
            $input = [];
            $input['process_number'] = $thread_connection['FK_process'];
            $input['date'] = $thread_connection['date_mod'];
            if (($thread_connection["old_device_ID"] != "0")
                    OR ($thread_connection["new_device_ID"] != "0")) {

               if ($thread_connection["old_device_ID"] != "0") {
                  // disconnection
                  $input['creation'] = '0';
               } else if ($thread_connection["new_device_ID"] != "0") {
                  // connection
                  $input['creation'] = '1';
               }
               $input['FK_port_source'] = $thread_connection["FK_ports"];
               $dataPort = [];
               $portvalue = null;
               if ($thread_connection["old_device_ID"] != "0") {
                  $portvalue = $thread_connection['old_value'];
               } else if ($thread_connection["new_device_ID"] != "0") {
                  $portvalue = $thread_connection['new_value'];
               }
               if ($portvalue != null) {
                  $port_iterator = $DB->request([
                     'FROM'   => 'glpi_networkports',
                     'WHERE'  => ['mac' => $thread_connection['old_value']],
                     'LIMIT'  => 1
                  ]);
                  $dataPort = $port_iterator->next();
               }
               if (isset($dataPort['id'])) {
                  $input['FK_port_destination'] = $dataPort['id'];
               } else {
                  $input['FK_port_destination'] = 0;
               }

               $DB->insert(
                  'glpi_plugin_fusinvsnmp_networkportconnectionlogs', [
                     'date_mod'                    => $input['date'],
                     'creation'                    => $input['creation'],
                     'networkports_id_source'      => $data['FK_port_source'],
                     'networkports_id_destination' => $data['FK_port_destination']
                  ]
               );
            }
         }
      }

      $DB->delete(
         'glpi_plugin_tracker_snmp_history', [
            'Field'  => 0,
            'OR'     => [
               'old_device_ID'   => ['!=', 0],
               'new_device_ID'   => ['!=', 0],
            ]
         ]
      );
      $migration->displayMessage("$nb / $nb");
   }

   $migration->renameTable("glpi_plugin_fusinvsnmp_networkportlogs",
                           $newTable);
   $migration->renameTable("glpi_plugin_tracker_snmp_history",
                           $newTable);
   if (!$DB->tableExists($newTable)) {
      $query = "CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                      PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
      $DB->query($query);
   }
   $migration->changeField($newTable,
                           "id",
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                           "networkports_id",
                           "networkports_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "plugin_fusioninventory_mappings_id",
                           "plugin_fusioninventory_mappings_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "date_mod",
                           "date_mod",
                           "datetime DEFAULT NULL");
   $migration->changeField($newTable,
                           "value_old",
                           "value_old",
                           "varchar(255) DEFAULT NULL");
   $migration->changeField($newTable,
                           "value_new",
                           "value_new",
                           "varchar(255) DEFAULT NULL");
   $migration->changeField($newTable,
                           "plugin_fusioninventory_agentprocesses_id",
                           "plugin_fusioninventory_agentprocesses_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->migrationOneTable($newTable);
   $migration->changeField($newTable,
                           "ID",
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                           "FK_ports",
                           "networkports_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                        "plugin_fusioninventory_mappings_id",
                        "int(11) NOT NULL DEFAULT '0'");
   $migration->migrationOneTable($newTable);

   // Update with mapping
   if ($DB->fieldExists($newTable, "Field")) {
      $pfMapping = new PluginFusioninventoryMapping();
      $iterator = $DB->request([
         'FROM'      => $newTable,
         'GROUPBY'   => 'Field'
      ]);
      while ($data = $iterator->next()) {
         $mapping = 0;
         if ($mapping = $pfMapping->get("NetworkEquipment", $data['Field'])) {
            $DB->update(
               $newTable, [
                  'plugin_fusioninventory_mappings_id'   => $mapping['id']
               ], [
                  'Field'                                => $data['Field'],
                  'plugin_fusioninventory_mappings_id'   => ['!=', $mapping['id']]
               ]
            );
         }
      }
   }
   $migration->dropField($newTable,
                        "Field");
   $migration->changeField($newTable,
                           "old_value",
                           "value_old",
                           "varchar(255) DEFAULT NULL");
   $migration->dropField($newTable,
                           "old_device_type");
   $migration->dropField($newTable,
                           "old_device_ID");
   $migration->changeField($newTable,
                           "new_value",
                           "value_new",
                           "varchar(255) DEFAULT NULL");
   $migration->dropField($newTable,
                           "new_device_type");
   $migration->dropField($newTable,
                           "new_device_ID");
   $migration->dropField($newTable, "FK_process");
   $migration->dropKey($newTable, "FK_process");
   $migration->dropKey($newTable,
                        "FK_ports");
   $migration->migrationOneTable($newTable);
   $migration->addField($newTable,
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->addField($newTable,
                           "networkports_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "plugin_fusioninventory_mappings_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "date_mod",
                           "datetime DEFAULT NULL");
   $migration->addField($newTable,
                           "value_old",
                           "varchar(255) DEFAULT NULL");
   $migration->addField($newTable,
                           "value_new",
                           "varchar(255) DEFAULT NULL");
   $migration->addField($newTable,
                           "plugin_fusioninventory_agentprocesses_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addKey($newTable,
                        ["networkports_id", "date_mod"],
                        "networkports_id");
   $migration->addKey($newTable,
                        "plugin_fusioninventory_mappings_id");
   $migration->addKey($newTable,
                        "plugin_fusioninventory_agentprocesses_id");
   $migration->addKey($newTable,
                        "date_mod");
   $migration->migrationOneTable($newTable);
   $DB->listFields($newTable, false);

   /*
    * Update networports to convert itemtype 5153 to PluginFusioninventoryUnknownDevice
    */
   $DB->update(
      'glpi_networkports', [
         'itemtype'  => 'PluginFusioninventoryUnmanaged'
      ], [
         'itemtype'  => [5153, 'PluginFusioninventoryUnknownDevice']
      ]
   );

   $DB->update(
      'glpi_networkports', [
         'itemtype'  => 'PluginFusioninventoryTask'
      ], [
         'itemtype'  => 5166
      ]
   );

   /*
    * Clean for port orphelin
    */
   //networkports with item_type = 0
   $NetworkPort = new NetworkPort();
   $NetworkPort_Vlan = new NetworkPort_Vlan();
   $NetworkPort_NetworkPort = new NetworkPort_NetworkPort();
   $a_networkports = $NetworkPort->find(['itemtype' => '']);
   foreach ($a_networkports as $data) {
      if ($NetworkPort_NetworkPort->getFromDBForNetworkPort($data['id'])) {
         $NetworkPort_NetworkPort->delete($NetworkPort_NetworkPort->fields);
      }
      $a_vlans = $NetworkPort_Vlan->find(['networkports_id' => $data['id']]);
      foreach ($a_vlans as $a_vlan) {
         $NetworkPort_Vlan->delete($a_vlan);
      }
      $NetworkPort->delete($data, 1);
   }

   /*
    *  Clean old ports deleted but have some informations in SNMP tables
    */
   $query_select = "SELECT `glpi_plugin_fusioninventory_networkports`.`id`
                    FROM `glpi_plugin_fusioninventory_networkports`
                          LEFT JOIN `glpi_networkports`
                                    ON `glpi_networkports`.`id` = `networkports_id`
                          LEFT JOIN `glpi_networkequipments`
                              ON `glpi_networkequipments`.`id` = `glpi_networkports`.`items_id`
                    WHERE `glpi_networkequipments`.`id` IS NULL";
   $result=$DB->query($query_select);
   while ($data=$DB->fetchArray($result)) {
      $DB->delete(
         'glpi_plugin_fusioninventory_networkports', [
            'id'  => $data['id']
         ]
      );
   }

   changeDisplayPreference("5162", "PluginFusinvsnmpNetworkPortLog");

   // If no PluginFusioninventoryNetworkPort in preferences, add them
   $iterator = $DB->request([
      'FROM'   => 'glpi_displaypreferences',
      'WHERE'  => [
         'itemtype'  => 'PluginFusioninventoryNetworkPort',
         'users_id'  => 0
      ]
   ]);
   if (!count($iterator)) {
      $insert = $DB->buildInsert(
         'glpi_displaypreferences', [
            'itemtype'  => 'PluginFusioninventoryNetworkPort',
            'num'       => new \QueryParam(),
            'rank'      => new \QueryParam(),
            'users_id'  => 0
         ]
      );
      $stmt = $DB->prepare($insert);

      $insert_data =  [
         [3, 1],
         [5, 2],
         [6, 3],
         [7, 4],
         [8, 5],
         [9, 6],
         [10, 7],
         [11, 8],
         [12, 9],
         [13, 10],
         [14, 11]
      ];

      foreach ($insert_data as $idata) {
         $stmt->bind_param(
            'ss',
            $idata[0],
            $idata[1]
         );
      }
   }

   // Update networkports types
   $pfNetworkporttype = new PluginFusioninventoryNetworkporttype();
   $pfNetworkporttype->init();

   // Define lastup field of fusion networkports
   $iterator = $DB->request([
      'FROM'   => 'glpi_plugin_fusioninventory_mappings',
      'WHERE'  => ['name' => 'ifstatus'],
      'LIMIT'  => 1
   ]);
   while ($data = $iterator->next()) {
      $iterator_np = $DB->request(['FROM' => 'glpi_plugin_fusioninventory_networkports']);
      while ($data_np = $iterator_np->next()) {
         $iterator_npplog = $DB->request([
            'FROM'   => 'glpi_plugin_fusioninventory_networkportlogs',
            'WHERE'  => [
               'networkports_id'                      => $data_np['networkports_id'],
               'plugin_fusioninventory_mappings_id'   => $data['id']
            ],
            'ORDER'  => 'date_mod DESC',
            'LIMIT'  => 1
         ]);
         while ($data_nplog = $iterator_npplog->next()) {
            $DB->update(
               'glpi_plugin_fusioninventory_networkports', [
                  'lastup' => $data_nplog['date_mod']
               ], [
                  'id'  => $data_np['id']
               ]
            );
         }
      }
   }
}


/**
 * Manage the printer part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_printer_migration($migration) {
   global $DB;

   /*
    * Table glpi_plugin_fusioninventory_printers
    */
   $newTable = "glpi_plugin_fusioninventory_printers";
   $migration->renameTable("glpi_plugin_fusinvsnmp_printers",
                           $newTable);

   $migration->renameTable("glpi_plugin_tracker_printers",
                           $newTable);
   if (!$DB->tableExists($newTable)) {
      $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
   }
   $migration->changeField($newTable,
                           "id",
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                           "printers_id",
                           "printers_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "sysdescr",
                           "sysdescr",
                           "text COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->changeField($newTable,
                           "plugin_fusinvsnmp_configsecurities_id",
                           "plugin_fusioninventory_configsecurities_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "plugin_fusioninventory_configsecurities_id",
                           "plugin_fusioninventory_configsecurities_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "frequence_days",
                           "frequence_days",
                           "int(5) NOT NULL DEFAULT '1'");
   $migration->changeField($newTable,
                           "last_fusioninventory_update",
                           "last_fusioninventory_update",
                           "datetime DEFAULT NULL");
   $migration->migrationOneTable($newTable);
   $migration->changeField($newTable,
                           "ID",
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                           "FK_printers",
                           "printers_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "FK_snmp_connection",
                           "plugin_fusioninventory_configsecurities_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "last_tracker_update",
                           "last_fusioninventory_update",
                           "datetime DEFAULT NULL");
   $migration->dropKey($newTable,
                        "FK_printers");
   $migration->dropKey($newTable,
                        "FK_snmp_connection");
   $migration->dropKey($newTable,
                        "plugin_fusioninventory_snmpmodels_id");
   $migration->migrationOneTable($newTable);
   $migration->dropField($newTable,
                        "plugin_fusinvsnmp_models_id");
   $migration->dropField($newTable,
                        "plugin_fusioninventory_snmpmodels_id");
   $migration->dropField($newTable,
                        "FK_model_infos");
   $migration->addField($newTable,
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->addField($newTable,
                           "printers_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "sysdescr",
                           "text COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                           "plugin_fusioninventory_configsecurities_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "frequence_days",
                           "int(5) NOT NULL DEFAULT '1'");
   $migration->addField($newTable,
                           "last_fusioninventory_update",
                           "datetime DEFAULT NULL");
   $migration->addField($newTable,
                        "serialized_inventory",
                        "longblob");
   $migration->addKey($newTable,
                        "plugin_fusioninventory_configsecurities_id");
   $migration->addKey($newTable,
                        "printers_id");
   $migration->migrationOneTable($newTable);
   $DB->listFields($newTable, false);

   /*
    * Table glpi_plugin_fusioninventory_printerlogs
    */
   $newTable = "glpi_plugin_fusioninventory_printerlogs";
   $migration->renameTable("glpi_plugin_fusinvsnmp_printerlogs",
                           $newTable);
   $migration->renameTable("glpi_plugin_tracker_printers_history",
                           $newTable);
   if (!$DB->tableExists($newTable)) {
      $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
   }
   $migration->changeField($newTable,
                           "id",
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                           "printers_id",
                           "printers_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "date",
                           "date",
                           "datetime DEFAULT NULL");
   $migration->changeField($newTable,
                           "pages_total",
                           "pages_total",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "pages_n_b",
                           "pages_n_b",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "pages_color",
                           "pages_color",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "pages_recto_verso",
                           "pages_recto_verso",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "scanned",
                           "scanned",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "pages_total_print",
                           "pages_total_print",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "pages_n_b_print",
                           "pages_n_b_print",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "pages_color_print",
                           "pages_color_print",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "pages_total_copy",
                           "pages_total_copy",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "pages_n_b_copy",
                           "pages_n_b_copy",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "pages_color_copy",
                           "pages_color_copy",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "pages_total_fax",
                           "pages_total_fax",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->migrationOneTable($newTable);
   $migration->changeField($newTable,
                           "ID",
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                           "FK_printers",
                           "printers_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->migrationOneTable($newTable);
   $migration->addField($newTable,
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->addField($newTable,
                           "printers_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "date",
                           "datetime DEFAULT NULL");
   $migration->addField($newTable,
                           "pages_total",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "pages_n_b",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "pages_color",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "pages_recto_verso",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "scanned",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "pages_total_print",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "pages_n_b_print",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "pages_color_print",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "pages_total_copy",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "pages_n_b_copy",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "pages_color_copy",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "pages_total_fax",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addKey($newTable,
                        ["printers_id", "date"],
                        "printers_id");
   $migration->migrationOneTable($newTable);
   $DB->listFields($newTable, false);

   /*
    *  glpi_plugin_fusioninventory_printercartridges
    */
   $newTable = "glpi_plugin_fusioninventory_printercartridges";
   $migration->renameTable("glpi_plugin_fusinvsnmp_printercartridges",
                           $newTable);
   $migration->renameTable("glpi_plugin_tracker_printers_cartridges",
                           $newTable);
   if (!$DB->tableExists($newTable)) {
      $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` bigint(100) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
   }
   $migration->changeField($newTable,
                           "id",
                           "id",
                           "bigint(100) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                           "printers_id",
                           "printers_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "plugin_fusioninventory_mappings_id",
                           "plugin_fusioninventory_mappings_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "cartridges_id",
                           "cartridges_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "state",
                           "state",
                           "int(3) NOT NULL DEFAULT '100'");
   $migration->migrationOneTable($newTable);
   $migration->changeField($newTable,
                           "ID",
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
   $migration->addField($newTable,
                        "plugin_fusioninventory_mappings_id",
                        "int(11) NOT NULL DEFAULT '0'");
   $migration->migrationOneTable($newTable);

   // Update with mapping
   if ($DB->fieldExists($newTable, "object_name")) {
      $iterator = $DB->request([
         'FROM'   => $newTable,
         'GROUP'  => 'object_name'
      ]);
      while ($data = $iterator->next()) {
         $pfMapping = new PluginFusioninventoryMapping();
         $mapping = 0;
         if (($mapping = $pfMapping->get("Printer", $data['object_name']))) {
            $DB->update(
               $newTable, [
                  'plugin_fusioninventory_mappings_id'   => $mapping['id']
               ], [
                  'object_name'                          => $data['object_name']
               ]
            );
         }
      }
   }
   $migration->dropField($newTable,
                           "object_name");
   $migration->migrationOneTable($newTable);
   $migration->addField($newTable,
                           "id",
                           "bigint(100) NOT NULL AUTO_INCREMENT");
   $migration->addField($newTable,
                           "printers_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "plugin_fusioninventory_mappings_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "cartridges_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "state",
                           "int(3) NOT NULL DEFAULT '100'");
   $migration->addKey($newTable,
                        "printers_id");
   $migration->addKey($newTable,
                        "plugin_fusioninventory_mappings_id");
   $migration->addKey($newTable,
                        "cartridges_id");
   $migration->migrationOneTable($newTable);
   $DB->listFields($newTable, false);

   /*
    * Clean for printer more informations again in DB when printer is purged
    */
   //echo "Clean for printer more informations again in DB when printer is purged\n";
   $iterator = $DB->request([
      'SELECT'    => 'glpi_plugin_fusioninventory_printers.id',
      'FROM'      => 'glpi_plugin_fusioninventory_printers',
      'LEFT JOIN' => [
         'glpi_printers'   => [
            'FKEY'   => [
               'glpi_plugin_fusioninventory_printers' => 'printers_id',
               'glpi_printers'                        => 'id'
            ]
         ]
      ],
      'WHERE'     => ['glpi_printers.id' => null]
   ]);
   if (count($iterator)) {
      $delete = $DB->buildDelete(
         'glpi_plugin_fusioninventory_printers', [
            'id' => new \QueryParam()
         ]
      );
      $stmt = $DB->prepare($delete);
      while ($data = $iterator->next()) {
         $stmt->bind_param('s', $data['id']);
         $stmt->execute();
      }
      mysqli_stmt_close($stmt);
   }

   /*
    *  Clean printer cartridge not deleted with the printer associated
    */
   //echo "Clean printer cartridge not deleted with the printer associated\n";
   $iterator = $DB->request([
      'SELECT'    => 'glpi_plugin_fusioninventory_printercartridges.id',
      'FROM'      => 'glpi_plugin_fusioninventory_printercartridges',
      'LEFT JOIN' => [
         'glpi_printers'   => [
            'FKEY'   => [
               'glpi_plugin_fusioninventory_printercartridges' => 'printers_id',
               'glpi_printers'                                 => 'id'
            ]
         ]
      ],
      'WHERE'     => ['glpi_printers.id' => null]
   ]);

   $stmt = null;
   while ($data = $iterator->next()) {
      $DB->delete(
         'glpi_plugin_fusioninventory_printercartridges', [
            'id' => $data['id']
         ]
      );

      changeDisplayPreference("5168", "PluginFusioninventoryPrinterLogReport");
      changeDisplayPreference("PluginFusinvsnmpPrinterLogReport",
                           "PluginFusioninventoryPrinterLogReport");
      changeDisplayPreference("5156", "PluginFusinvsnmpPrinterCartridge");

      /*
      * Modify displaypreference for PluginFusioninventoryPrinterLog
      */
      $pfPrinterLogReport = new PluginFusioninventoryPrinterLog();
      $a_searchoptions = $pfPrinterLogReport->rawSearchOptions();
      $iterator2 = $DB->request([
         'FROM'   => 'glpi_displaypreferences',
         'WHERE'  => [
            'itemtype'  => 'PluginFusioninventoryPrinterLogReport',
            'users_id'  => 0
         ]
      ]);
      if (!count($iterator2)) {
         if ($stmt === null) {
            $insert = $DB->buildInsert(
               'glpi_displaypreferences', [
                  'itemtype'  => 'PluginFusioninventoryPrinterLogReport',
                  'num'       => new \QueryParam(),
                  'rank'      => new \QueryParam(),
                  'users_id'  => 0
               ]
            );
            $stmt = $DB->prepare($insert);
         }

         $insert_data = [
            [2, 1],
            [18, 2],
            [20, 3],
            [5, 4],
            [6, 5]
         ];

         foreach ($insert_data as $idata) {
            $stmt->bind_param(
               'ss',
               $idata[0],
               $idata[1]
            );
            $stmt->execute();
         }
      } else {
         while ($data = $iterator2->next()) {
            $delete = true;
            foreach ($a_searchoptions as $searchoption) {
               if ($searchoption['id'] == $data['num']) {
                  $delete = false;
                  continue;
               }
            }
            if ($delete) {
               $DB->delete(
                  'glpi_displaypreferences', [
                     'id'  => $data['id']
                  ]
               );
            }
         }
      }

   }
   if ($stmt !== null) {
      mysqli_stmt_close($stmt);
   }

   /*
    *  Clean printer history not deleted with printer associated
    */
   //echo "Clean printer history not deleted with printer associated\n";
   $iterator = $DB->request([
      'SELECT'    => 'glpi_plugin_fusioninventory_printerlogs.id',
      'FROM'      => 'glpi_plugin_fusioninventory_printerlogs',
      'LEFT JOIN' => [
         'glpi_printers'   => [
            'FKEY'   => [
               'glpi_plugin_fusioninventory_printerlogs' => 'printers_id',
               'glpi_printers'                           => 'id'
            ]
         ]
      ],
      'WHERE'     => ['glpi_printers.id' => null]
   ]);
   if (count($iterator)) {
      $delete = $DB->buildDelete(
         'glpi_plugin_fusioninventory_printerlogs', [
            'id'  => new \QueryParam()
         ]
      );
      $stmt = $DB->prepare($delete);
      while ($data = $iterator->next()) {
         $stmt->bind_param('s', $data['id']);
         $stmt->execute();
      }
      mysqli_stmt_close($stmt);
   }

   /*
    * Manage devices with is_dynamic
    */
   $iterator = $DB->request(['FROM' => 'glpi_plugin_fusioninventory_printers']);
   if (count($iterator)) {
      $update = $DB->buildUpdate(
         'glpi_printers', [
            'is_dynamic'   => 1
         ], [
            'id'           => new \QueryParam()
         ]
      );
      $stmt = $DB->prepare($update);
      while ($data = $iterator->next()) {
         $stmt->bind_param('s', $data['printers_id']);
         $stmt->execute();
      }
      mysqli_stmt_close($stmt);
   }

   $migration->displayMessage("Clean printers");
   /*
    * Remove / at the end of printers (bugs in older versions of agents.
    */
   $printer = new Printer();
   $iterator = $DB->request([
      'FROM'   => 'glpi_printers',
      'WHERE'  => ['serial' => ['LIKE', '%/']]
   ]);
   while ($data = $iterator->next()) {
      $cleanSerial = preg_replace('/\/$/', '', $data['serial']);
      $iterator2 = $DB->request([
         'FROM'   => 'glpi_printers',
         'WHERE'  => ['serial' => $cleanSerial],
         'LIMIT'  => 1
      ]);
      if (!count($iterator)) {
         $input = [
            'id'     => $data['id'],
            'serial' => $cleanSerial
         ];
         $printer->update($input);
      }
   }

}


/**
 * Manage the network equipment part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_networkequipment_migration($migration) {
   global $DB;

   /*
    * Table glpi_plugin_fusioninventory_networkequipments
    */
   $newTable = "glpi_plugin_fusioninventory_networkequipments";
   $migration->renameTable("glpi_plugin_fusinvsnmp_networkequipments",
                           $newTable);
   $migration->renameTable("glpi_plugin_tracker_networking",
                           $newTable);
   if (!$DB->tableExists($newTable)) {
      $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
   }
   $migration->changeField($newTable,
                           "id",
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                           "networkequipments_id",
                           "networkequipments_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "sysdescr",
                           "sysdescr",
                           "text COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->changeField($newTable,
                           "plugin_fusioninventory_configsecurities_id",
                           "plugin_fusioninventory_configsecurities_id",
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
                           "last_fusioninventory_update",
                           "last_fusioninventory_update",
                           "datetime DEFAULT NULL");
   $migration->changeField($newTable,
                           "last_PID_update",
                           "last_PID_update",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->migrationOneTable($newTable);
   $migration->changeField($newTable,
                           "ID",
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                           "FK_networking",
                           "networkequipments_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "FK_snmp_connection",
                           "plugin_fusioninventory_configsecurities_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "last_tracker_update",
                           "last_fusioninventory_update",
                           "datetime DEFAULT NULL");
   $migration->changeField($newTable,
                           "plugin_fusinvsnmp_configsecurities_id",
                           "plugin_fusioninventory_configsecurities_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->dropKey($newTable,
                        "FK_networking");
   $migration->dropKey($newTable,
                        "FK_model_infos");
   $migration->dropKey($newTable,
                        "plugin_fusioninventory_snmpmodels_id");
   $migration->migrationOneTable($newTable);
   $migration->dropField($newTable,
                           "plugin_fusioninventory_snmpmodels_id");
   $migration->dropField($newTable,
                           "plugin_fusinvsnmp_models_id");
   $migration->dropField($newTable,
                           "FK_model_infos");
   $migration->addField($newTable,
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->addField($newTable,
                           "networkequipments_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "sysdescr",
                           "text COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                           "plugin_fusioninventory_configsecurities_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "uptime",
                           "varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "cpu",
                           "int(3) NOT NULL DEFAULT '0' COMMENT '%'");
   $migration->addField($newTable,
                           "memory",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "last_fusioninventory_update",
                           "datetime DEFAULT NULL");
   $migration->addField($newTable,
                           "last_PID_update",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                        "serialized_inventory",
                        "longblob");
   $migration->addKey($newTable,
                        "networkequipments_id");
   $migration->migrationOneTable($newTable);
   $DB->listFields($newTable, false);

   /*
    * glpi_plugin_fusioninventory_networkequipmentips
    * Removed in 0.84, but required here for update, we drop in edn of this function
    */
   if ($DB->tableExists("glpi_plugin_fusioninventory_networkequipmentips")
           || $DB->tableExists("glpi_plugin_fusinvsnmp_networkequipmentips")
           || $DB->tableExists("glpi_plugin_tracker_networking_ifaddr")) {
      $newTable = "glpi_plugin_fusioninventory_networkequipmentips";
      $migration->renameTable("glpi_plugin_fusinvsnmp_networkequipmentips",
                              $newTable);
      $migration->renameTable("glpi_plugin_tracker_networking_ifaddr",
                              $newTable);
      if (!$DB->tableExists($newTable)) {
         $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
      }
      $migration->changeField($newTable,
                              "id",
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
      $migration->changeField($newTable,
                              "networkequipments_id",
                              "networkequipments_id",
                              "int(11) NOT NULL DEFAULT '0'");
      $migration->changeField($newTable,
                              "ip",
                              "ip",
                              "varchar(255) DEFAULT NULL");
      $migration->migrationOneTable($newTable);
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
      $migration->dropKey($newTable,
                           "ifaddr");
      $migration->migrationOneTable($newTable);
      $migration->addField($newTable,
                              "id",
                              "int(11) NOT NULL AUTO_INCREMENT");
      $migration->addField($newTable,
                              "networkequipments_id",
                              "int(11) NOT NULL DEFAULT '0'");
      $migration->addField($newTable,
                              "ip",
                              "varchar(255) DEFAULT NULL");
      $migration->addKey($newTable,
                           "ip");
      $migration->addKey($newTable,
                           "networkequipments_id");
      $migration->migrationOneTable($newTable);
      $DB->listFields($newTable, false);
   }

   /*
    * Move networkequipment IPs to net system
    */
   if ($DB->tableExists("glpi_plugin_fusioninventory_networkequipmentips")) {
      $networkPort = new NetworkPort();
      $networkName = new NetworkName();
      $ipAddress = new IPAddress();
      $networkEquipment = new NetworkEquipment();

      $iterator = $DB->request(['FROM' => 'glpi_plugin_fusioninventory_networkequipments']);
      while ($data = $iterator->next()) {
         if ($networkEquipment->getFromDB($data['networkequipments_id'])) {
            $oldtableip = [];
            $iterator2 = $DB->request([
               'FROM'   => 'glpi_plugin_fusioninventory_networkequipmentips',
               'WHERE'  => ['networkequipments_id' => $data['networkequipments_id']]
            ]);
            while ($dataIP = $iterator->next()) {
               $oldtableip[$dataIP['ip']] = $dataIP['ip'];
            }

            // Get actual IP defined
            $networknames_id = 0;
            $a_ports = $networkPort->find(
                  ['itemtype'           => 'NetworkEquipment',
                   'items_id'           => $data['networkequipments_id'],
                   'instantiation_type' => 'NetworkPortAggregate',
                   'name'               => 'management'],
                  [], 1);

            foreach ($a_ports as $a_port) {
               $a_networknames = $networkName->find(
                     ['itemtype' => 'NetworkPort',
                      'items_id' => $a_port['id']]);
               foreach ($a_networknames as $a_networkname) {
                  $networknames_id = $a_networkname['id'];
                  $a_ipaddresses = $ipAddress->find(
                        ['itemtype' => 'NetworkName',
                         'items_id' => $a_networkname['id']]);
                  foreach ($a_ipaddresses as $a_ipaddress) {
                     if (isset($oldtableip[$a_ipaddress['name']])) {
                        unset($oldtableip[$a_ipaddress['name']]);
                     } else {
                        $ipAddress->delete($a_ipaddress, 1);
                     }
                  }
               }
            }

            // Update
            foreach ($oldtableip as $ip) {
               $input = [];
               $input['itemtype']   = "NetworkName";
               $input['items_id']   = $networknames_id;
               $input['name']       = $ip;
               $input['is_dynamic'] = 1;
               $ipAddress->add($input);
            }
         }
      }
   }

   /*
    * Clean for switch more informations again in DB when switch is purged
    */
   //echo "Clean for switch more informations again in DB when switch is purged\n";
   $iterator = $DB->request([
      'SELECT'    => 'glpi_plugin_fusioninventory_networkequipments.id',
      'FROM'      => 'glpi_plugin_fusioninventory_networkequipments',
      'LEFT JOIN' => [
         'glpi_networkequipments'   => [
            'FKEY'   => [
               'glpi_networkequipments'                        => 'id',
               'glpi_plugin_fusioninventory_networkequipments' => 'networkequipments_id'
            ]
         ]
      ],
      'WHERE'     => [
         'glpi_networkequipments.id' => null
      ]
   ]);
   if (count($iterator)) {
      $delete = $DB->buildDelete(
         'glpi_plugin_fusioninventory_networkequipments', [
            'id'  => new \QueryParam()
         ]
      );
      $stmt = $DB->prepare($delete);
      while ($data = $iterator->next()) {
         $stmt->bind_param('s', $data['id']);
         $stmt->execute();
      }
   }

   changeDisplayPreference("5157", "PluginFusioninventoryNetworkEquipment");
   changeDisplayPreference("PluginFusinvsnmpNetworkEquipment",
                           "PluginFusioninventoryNetworkEquipment");

   /*
    * Modify displaypreference for PluginFusinvsnmpNetworkEquipment
    */
   $a_check = [];
   $a_check["2"] = 1;
   $a_check["3"] = 2;
   $a_check["4"] = 3;
   $a_check["5"] = 4;
   $a_check["6"] = 5;
   $a_check["7"] = 6;
   $a_check["8"] = 7;
   $a_check["9"] = 8;
   $a_check["10"] = 9;
   $a_check["11"] = 10;
   $a_check["14"] = 11;
   $a_check["12"] = 12;
   $a_check["13"] = 13;

   foreach ($a_check as $num=>$rank) {
      $iterator = $DB->request([
         'FROM'   => 'glpi_displaypreferences',
         'WHERE'  => [
            'itemtype'  => 'PluginFusioninventoryNetworkEquipment',
            'num'       => $num,
            'users_id'  => 0
         ]
      ]);
      if (!count($iterator)) {
         $DB->insert(
            'glpi_displaypreferences', [
               'itemtype'  => 'PluginFusioninventoryNetworkEquipment',
               'num'       => $num,
               'rank'      => $rank,
               'users_id'  => 0
            ]
         );
      }
   }

   $iterator = $DB->request([
      'FROM'   => 'glpi_displaypreferences',
      'WHERE'  => [
         'itemtype'  => 'PluginFusioninventoryNetworkEquipment',
         'users_id'  => 0
      ]
   ]);
   while ($data = $iterator->next()) {
      if (!isset($a_check[$data['num']])) {
         $DB->delete(
            'glpi_displaypreferences', [
               'id' => $date['id']
            ]
         );
      }
   }

   /*
    * Manage devices with is_dynamic
    */
   $iterator = $DB->request(['FROM' => 'glpi_plugin_fusioninventory_networkequipments']);
   if (count($iterator)) {
      $update = $DB->buildUpdate(
         'glpi_networkequipments', [
            'is_dynamic'   => 1
         ], [
            'id'           => new \QueryParam()
         ]
      );
      $stmt = $DB->prepare($update);
      while ($data = $iterator->next()) {
         $stmt->bind_param('s', $data['networkequipments_id']);
         $stmt->execute();
      }
      mysqli_stmt_close($stmt);
   }
}


/**
 * Manage the Config security (SNMP anthentication) part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_configsecurity_migration($migration) {
   global $DB;

   /*
    * Table glpi_plugin_fusioninventory_configsecurities
    */
   // TODO get info to create SNMP authentification with old values of Tracker plugin
   $newTable = "glpi_plugin_fusioninventory_configsecurities";
   $migration->renameTable("glpi_plugin_fusinvsnmp_configsecurities",
                           $newTable);
   $migration->renameTable("glpi_plugin_tracker_snmp_connection",
                           $newTable);
   if (!$DB->tableExists($newTable)) {
      $DB->query('CREATE TABLE `'.$newTable.'` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                   ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1');
   }
   $migration->changeField($newTable,
                           "id",
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
      $migration->changeField($newTable,
                           "name",
                           "name",
                           "varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->changeField($newTable,
                           "snmpversion",
                           "snmpversion",
                           "varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1'");
   $migration->changeField($newTable,
                           "community",
                           "community",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->changeField($newTable,
                           "username",
                           "username",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->changeField($newTable,
                           "authentication",
                           "authentication",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->changeField($newTable,
                           "auth_passphrase",
                           "auth_passphrase",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->changeField($newTable,
                           "encryption",
                           "encryption",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->changeField($newTable,
                           "priv_passphrase",
                           "priv_passphrase",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->changeField($newTable,
                           "is_deleted",
                           "is_deleted",
                           "tinyint(1) NOT NULL DEFAULT '0'");
   $migration->migrationOneTable($newTable);
   $migration->changeField($newTable,
                           "ID",
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                           "FK_snmp_version",
                           "snmpversion",
                           "varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1'");
   $migration->changeField($newTable,
                           "sec_name",
                           "username",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->dropField($newTable,
                           "sec_level");
   $migration->dropField($newTable,
                           "auth_protocol");
   $migration->dropField($newTable,
                           "priv_protocol");
   $migration->dropField($newTable,
                           "deleted");
   $migration->migrationOneTable($newTable);
   $migration->addField($newTable,
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->addField($newTable,
                           "name",
                           "varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                           "snmpversion",
                           "varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1'");
   $migration->addField($newTable,
                           "community",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                           "username",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                           "authentication",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                           "auth_passphrase",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                           "encryption",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                           "priv_passphrase",
                           "varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL");
   $migration->addField($newTable,
                           "is_deleted",
                           "tinyint(1) NOT NULL DEFAULT '0'");
   $migration->addKey($newTable,
                        "snmpversion");
   $migration->addKey($newTable,
                        "is_deleted");
   $migration->migrationOneTable($newTable);
   $DB->listFields($newTable, false);

   changeDisplayPreference("5152", "PluginFusioninventoryConfigSecurity");

   // Remove the protocols AES192 and AES256 because not managed in the agent
   // with the perl module NET-SNMP
   $DB->update('glpi_plugin_fusioninventory_configsecurities',
               ['encryption' => 'AES128'],
               ['encryption' => ['AES192', 'AES256']]);
}


/**
 * Manage the discovery state part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_statediscovery_migration($migration) {
   global $DB;

   /*
    *  glpi_plugin_fusioninventory_statediscoveries
    */
   $newTable = "glpi_plugin_fusioninventory_statediscoveries";
   $migration->renameTable("glpi_plugin_fusinvsnmp_statediscoveries",
                           $newTable);
   if (!$DB->tableExists($newTable)) {
      $DB->query("CREATE TABLE `".$newTable."` (
                     `id` int(11) NOT NULL AUTO_INCREMENT,
                     PRIMARY KEY (`id`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1");
   }
   $migration->changeField($newTable,
                           "id",
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->changeField($newTable,
                           "plugin_fusioninventory_taskjob_id",
                           "plugin_fusioninventory_taskjob_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "plugin_fusioninventory_agents_id",
                           "plugin_fusioninventory_agents_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "start_time",
                           "start_time",
                           "datetime DEFAULT NULL");
   $migration->changeField($newTable,
                           "end_time",
                           "end_time",
                           "datetime DEFAULT NULL");
   $migration->changeField($newTable,
                           "date_mod",
                           "date_mod",
                           "datetime DEFAULT NULL");
   $migration->changeField($newTable,
                           "threads",
                           "threads",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "nb_ip",
                           "nb_ip",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "nb_found",
                           "nb_found",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "nb_error",
                           "nb_error",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "nb_exists",
                           "nb_exists",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->changeField($newTable,
                           "nb_import",
                           "nb_import",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->migrationOneTable($newTable);
   $migration->addField($newTable,
                           "id",
                           "int(11) NOT NULL AUTO_INCREMENT");
   $migration->addField($newTable,
                           "plugin_fusioninventory_taskjob_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "plugin_fusioninventory_agents_id",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "start_time",
                           "datetime DEFAULT NULL");
   $migration->addField($newTable,
                           "end_time",
                           "datetime DEFAULT NULL");
   $migration->addField($newTable,
                           "date_mod",
                           "datetime DEFAULT NULL");
   $migration->addField($newTable,
                           "threads",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "nb_ip",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "nb_found",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "nb_error",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "nb_exists",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->addField($newTable,
                           "nb_import",
                           "int(11) NOT NULL DEFAULT '0'");
   $migration->migrationOneTable($newTable);
   $DB->listFields($newTable, false);
}


/**
 * Manage the computer license part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_computerlicense_migration($migration) {
   global $DB;

   /*
    * Table glpi_plugin_fusioninventory_computerlicenseinfos
    */
   if ($DB->tableExists("glpi_plugin_fusinvinventory_licenseinfos")) {
      $DB->update(
         'glpi_plugin_fusinvinventory_licenseinfos', [
            'softwarelicenses_id'   => 0
         ], [
            'softwarelicenses_id'   => null
         ]
      );
   }
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_computerlicenseinfos';
   $a_table['oldname'] = ['glpi_plugin_fusinvinventory_licenseinfos'];

   $a_table['fields']  = [];
   $a_table['fields']['id']                  = ['type'    => 'autoincrement',
                                                     'value'   => ''];
   $a_table['fields']['computers_id']        = ['type'    => 'integer',
                                                     'value'   => null];
   $a_table['fields']['softwarelicenses_id'] = ['type'    => 'integer',
                                                     'value'   => null];
   $a_table['fields']['name']                = ['type'    => 'string',
                                                     'value'   => null];
   $a_table['fields']['fullname']            = ['type'    => 'string',
                                                     'value'   => null];
   $a_table['fields']['serial']              = ['type'    => 'string',
                                                     'value'   => null];
   $a_table['fields']['is_trial']            = ['type'    => 'bool',
                                                     'value'   => null];
   $a_table['fields']['is_update']           = ['type'    => 'bool',
                                                     'value'   => null];
   $a_table['fields']['is_oem']              = ['type'    => 'bool',
                                                     'value'   => null];
   $a_table['fields']['activation_date']     = ['type'    => 'datetime',
                                                     'value'   => null];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => 'name', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'fullname', 'name' => '', 'type' => 'INDEX'];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);
}


/**
 * Manage the computer remote management part migration
 *
 * @param object $migration
 */
function do_computerremotemgmt_migration($migration) {

   /*
    * Table PluginFusioninventoryComputerRemoteManagement
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_computerremotemanagements';
   $a_table['oldname'] = ['glpi_plugin_fusioninventory_computerremotemanagements'];

   $a_table['fields']  = [];
   $a_table['fields']['id']                  = ['type'    => 'autoincrement',
                                                     'value'   => ''];
   $a_table['fields']['computers_id']        = ['type'    => 'integer',
                                                     'value'   => null];
   $a_table['fields']['number']              = ['type'    => 'string',
                                                     'value'   => null];
   $a_table['fields']['type']                = ['type'    => 'string',
                                                     'value'   => null];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => 'computers_id', 'name' => '', 'type' => 'INDEX'];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);
}


/**
 * Manage the computer architecture part migration
 *
 * @param object $migration
 */
function do_computerarch_migration($migration) {
    global $DB;

   if ($DB->tableExists('glpi_plugin_fusioninventory_computerarches')) {
      //Rename field in coputeroperatingsystems table
      $a_table = [
         'name'     => 'glpi_plugin_fusioninventory_computeroperatingsystems',
         'renamefields' => [
            'plugin_fusioninventory_computerarches_id' => 'operatingsystemarchitectures_id'
         ]
      ];
      migrateTablesFusionInventory($migration, $a_table);

      //Arches migration from FI table to GLPi core table
      $arches = new OperatingSystemArchitecture();
      foreach (getAllDataFromTable('glpi_plugin_fusioninventory_computerarches') as $arch) {
         //check if arch already exists in core
         if ($arches->getFromDBByCrit(['name' => $DB->escape($arch['name'])])) {
            $new_id = $arches->fields['id'];
         } else {
            unset($arch['id']);
            $new_id = $arches->add($arch, [], false);
         }

         //DB::update() does not handle joins for now
         $sql_u = "UPDATE glpi_plugin_fusioninventory_computeroperatingsystems pf_os SET "
                     . " pf_os.operatingsystemarchitectures_id='" . $new_id . "',"
                     . " JOIN operatingsystemarchitectures os_arch WHERE os_arch.name='" . $DB->escape($arch['name']) . "'";
         $DB->query($sql_u);
      }

      $migration->dropTable('glpi_plugin_fusioninventory_computerarches');

      //Update dictionnary to match the new operating system
      $DB->update(
         'glpi_rules', [
            'sub_type'  => 'RuleDictionnaryOperatingSystemArchitectureCollection'
         ], [
            'sub_type'  => 'PluginFusioninventoryRuleDictionnaryComputerArchCollection'
         ]
      );
   }
}


/**
 * Manage the operating system edition part migration
 *
 * @param object $migration
 */
function do_operatingsystemedition_migration($migration) {
    global $DB;

   if ($DB->tableExists('glpi_plugin_fusioninventory_computeroperatingsystemeditions')) {
      //OS editions migration from FI table to GLPi core table
      $ose = new OperatingSystemEdition();
      foreach (getAllDataFromTable('glpi_plugin_fusioninventory_computeroperatingsystemeditions') as $edition) {
         //check if arch already exists in core
         if ($ose->getFromDBByCrit(['name' => $DB->escape($edition['name'])])) {
            $new_id = $ose->fields['id'];
         } else {
            unset($edition['id']);
            $new_id = $ose->add($edition, [], false);
         }

         //DB::update() does not handle joins for now
         $sql_u = "UPDATE glpi_plugin_fusioninventory_computeroperatingsystems pf_os"
                     . " JOIN glpi_plugin_fusioninventory_computeroperatingsystemeditions os_edition "
                     . " ON pf_os.plugin_fusioninventory_computeroperatingsystemeditions_id = os_edition.id "
                     . " SET pf_os.plugin_fusioninventory_computeroperatingsystemeditions_id='$new_id'"
                     . " WHERE os_edition.name='" . $DB->escape($edition['name']) . "'";
         $DB->query($sql_u);
      }
      $migration->dropTable('glpi_plugin_fusioninventory_computeroperatingsystemeditions');
   }
}


/**
 * Manage the kernel names and kernel versions part migration
 *
 * @param object $migration
 *
 * @return array
 */
function do_operatingsystemkernel_migration($migration) {
   global $DB;

   if ($DB->tableExists('glpi_plugin_fusioninventory_computeroskernelnames')) {
      //Find wich version on which kernel
      $kmapping = []; // [orig_osid|orig_osversionid => newid]
      $mapping  = []; // [orig_computerosid => new_osversionid]

      $kernels = new OperatingSystemKernel();
      $kversions = new OperatingSystemKernelVersion();

      //DB::update() does not handle joins for now
      $query = "SELECT fi_cos.id,
            fi_kname.id AS kid, fi_kname.name AS kname,
            fi_kversion.id AS kvid, fi_kversion.name AS kversion
         FROM glpi_plugin_fusioninventory_computeroperatingsystems AS fi_cos
         INNER JOIN glpi_plugin_fusioninventory_computeroskernelnames AS fi_kname
            ON fi_kname.id = fi_cos.plugin_fusioninventory_computeroskernelnames_id
         INNER JOIN glpi_plugin_fusioninventory_computeroskernelversions AS fi_kversion
            ON fi_kversion.id = fi_cos.plugin_fusioninventory_computeroskernelversions_id
      ";
      $iterator = $DB->request($query);

      while ($row = $iterator->next()) {
         $key = "{$row['kid']}|{$row['kvid']}";
         if (!isset($mapping[$key])) {
            //find in db for an existing kernel name
            if (!$kernels->getFromDBByCrit(['name' => $DB->escape($row['kname'])])) {
               $kernels->add(['name' => $row['kname']]);
            }
            if (!$kversions->getFromDBByCrit(['name' => $DB->escape($row['kversion']), 'operatingsystemkernels_id' => $kernels->getID()])) {
               $kversions->add([
                  'name'                        => $row['kversion'],
                  'operatingsystemkernels_id'  => $kernels->getID()
               ]);
            }
            $kmapping[$key] = $kversions->getID();
         }
         $mapping[$row['id']] = $kmapping[$key];
      }

      $migration->dropTable('glpi_plugin_fusioninventory_computeroskernelnames');
      $migration->dropTable('glpi_plugin_fusioninventory_computeroskernelversions');

      return $mapping;
   }
}


/**
 * Manage the computer operating system part migration
 *
 * @param object $migration
 */
function do_computeroperatingsystem_migration($migration) {
   global $DB;

   do_operatingsystemedition_migration($migration);
   $kversions_mapping = do_operatingsystemkernel_migration($migration);

   if ($DB->tableExists("glpi_plugin_fusioninventory_computeroperatingsystems")) {
      $ios = new Item_OperatingSystem();
      $query = "SELECT DISTINCT(fi_computer.computers_id) AS cid, fi_computer.computers_id, fi_cos.*
         FROM glpi_plugin_fusioninventory_inventorycomputercomputers AS fi_computer
         INNER JOIN glpi_plugin_fusioninventory_computeroperatingsystems AS fi_cos
            ON fi_computer.plugin_fusioninventory_computeroperatingsystems_id = fi_cos.id
         ";
      $iterator = $DB->request($query);

      while ($row = $iterator->next()) {
         $search = [
            'itemtype'                          => 'Computer',
            'items_id'                          => $row['cid'],
            'operatingsystems_id'               => $row['operatingsystems_id'],
            'operatingsystemarchitectures_id'   => $row['operatingsystemarchitectures_id']
         ];

         $computer = new Computer();
         $computer->getFromDB($row['cid']);

         $input = $search + [
            'operatingsystemversions_id'        => $row['operatingsystemversions_id'],
            'operatingsystemservicepacks_id'    => $row['operatingsystemservicepacks_id'],
            'operatingsystemkernelversions_id'  => isset($kversions_mapping[$row['id']])
                                                      ? $kversions_mapping[$row['id']]
                                                      : 0,
            'operatingsystemeditions_id'        => $row['plugin_fusioninventory_computeroperatingsystemeditions_id'],
            'is_dynamic'                        => 1,
            'entities_id'                       => $computer->fields['entities_id']
         ];

         if (!$ios->getFromDBByCrit($search)) {
            $ios->add($input);
         } else {
            $ios->update(
               ['id' => $ios->getID()] + $input
            );
         }
      }

      $migration->dropTable('glpi_plugin_fusioninventory_computeroperatingsystems');
      $migration->dropField(
         'glpi_plugin_fusioninventory_inventorycomputercomputers',
         'plugin_fusioninventory_computeroperatingsystems_id'
      );

      //handle display preferences
      //[oldid => newid]
      $sopts = [
         5172 => 45, //OS name
         5173 => 46, //OS version
         5174 => 64, //Kernel name
         5175 => 48, //Kernel version
         5176 => 41, //Service pack
         5177 => 63  //OS edition
      ];
      foreach ($sopts as $oldid => $newid) {
         $iterator = $DB->request(
            "SELECT * FROM `glpi_displaypreferences`
               WHERE
                  `itemtype`='Computer' AND (
                     `num`='$oldid' OR `num`='$newid'
                  )"
         );
         $users = [];
         while ($row = $iterator->next()) {
            if (!in_array($row['users_id'], $users)) {
               $users[] = $row['users_id'];
               $DB->update(
                  'glpi_displaypreferences', [
                     'num' => $newid
                  ], [
                     'id'  => $row['id']
                  ]
               );
            } else if ($row['num'] == $oldid) {
               $DB->delete(
                  'glpi_displaypreferences', [
                     'id' => $row['id']
                  ]
               );
            }
         }
      }

      //handle bookmarks
      $iterator = $DB->request([
         'FROM'   => 'glpi_savedsearches',
         'WHERE'  => [
            'itemtype' => 'Computer'
         ]
      ]);
      while ($row = $iterator->next()) {
         parse_str($row["query"], $options);
         $changed = false;
         foreach ($options['criteria'] as &$criterion) {
            if (isset($sopts[$criterion['field']])) {
               $criterion['field'] = $sopts[$criterion['field']];
               $changed = true;
            }
         }

         if ($changed === true) {
            $querystr = Toolbox::append_params($options);
            $ssearch = new SavedSearch();
            $ssearch->update([
               'id'     => $row['id'],
               'query'  => $querystr
            ]);
         }
      }

      //handle dynamic groups
      $iterator = $DB->request([
         'FROM'   => 'glpi_plugin_fusioninventory_deploygroups_dynamicdatas'
      ]);
      while ($row = $iterator->next()) {
         $fields = unserialize($row['fields_array']);
         $changed = false;
         foreach ($fields as &$type) {
            foreach ($type as &$criterion) {
               if (isset($sopts[$criterion['field']])) {
                  $criterion['field'] = $sopts[$criterion['field']];
                  $changed = true;
               }
            }
         }

         if ($changed === true) {
            $dyndata = new PluginFusioninventoryDeployGroup_Dynamicdata();
            $dyndata->update([
               'id'  => $row['id'],
               'fields_array' => serialize($fields)
            ]);
         }
      }
   }

   $migration->addField('glpi_plugin_fusioninventory_inventorycomputercomputers',
                        "hostid", "string", ['after' => 'oscomment']);
   $migration->migrationOneTable('glpi_plugin_fusioninventory_inventorycomputercomputers');
}


/**
 * Manage the deploy user interaction migration process
 *
 * @since 9.2
 * @global object $DB
 * @param object $migration
 */
function do_deployuserinteraction_migration($migration) {
   global $DB;

   if (!$DB->tableExists('glpi_plugin_fusioninventory_deployuserinteractions')) {
      $query = "CREATE TABLE IF NOT EXISTS `glpi_plugin_fusioninventory_deployuserinteractiontemplates` (
         `id` int(11) NOT NULL AUTO_INCREMENT,
         `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
         `entities_id` int(11) NOT NULL DEFAULT '0',
         `is_recursive` tinyint(1) NOT NULL DEFAULT '0',
         `date_creation` datetime DEFAULT NULL,
         `date_mod` datetime DEFAULT NULL,
         `json` longtext DEFAULT NULL,
         PRIMARY KEY (`id`),
         KEY `date_mod` (`date_mod`),
         KEY `date_creation` (`date_creation`),
         KEY `entities_id` (`entities_id`),
         KEY `is_recursive` (`is_recursive`)
      ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;";
      $DB->query($query);
   }
}


/**
 * Manage the deploy files part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_deployfile_migration($migration) {
   global $DB;

   /*
    * glpi_plugin_fusioninventory_deployfiles
    */
   $a_table = [];

   $a_table['name'] = 'glpi_plugin_fusioninventory_deployfiles';

   $a_table['oldname'] = [
   ];

   $a_table['fields'] = [
      'id' =>  [
               'type'   => 'autoincrement',
               'value'  => null
      ],
      'name' => [
               'type'   => 'varchar(255) NOT NULL',
               'value'  => null
      ],
      'mimetype' => [
               'type'   => 'varchar(255) NOT NULL',
               'value'  => null
      ],
      'filesize' => [
               'type' => 'bigint(20) NOT NULL',
               'value' => null
      ],
      'comment' => [
               'type'   => 'text DEFAULT NULL',
               'value'  => null
      ],
      'sha512' => [
               'type'   => 'char(128) NOT NULL',
               'value'  => null
      ],
      'shortsha512' => [
               'type'   => 'char(6) NOT NULL',
               'value'  => null
      ],
      'entities_id' => [
               'type'   => 'int(11) NOT NULL',
               'value'  => null
      ],
      'is_recursive' => [
               'type'   => 'tinyint(1) NOT NULL DEFAULT \'0\'',
               'value'  => 0
      ],
      'date_mod' => [
               'type'   => 'datetime DEFAULT NULL',
               'value'  => null
      ],

   ];

   $a_table['oldfields'] = [
   ];

   $a_table['renamefields'] = [
   ];

   $a_table['keys'] = [
      [
         'field' => 'id',
         'name' => '',
         'type' => 'KEY'
      ],
      [
         'field' => 'shortsha512',
         'name' => '',
         'type' => 'KEY'
      ],
      [
         'field' => 'entities_id',
         'name' => '',
         'type' => 'KEY'
      ],
      [
         'field' => 'date_mod',
         'name' => '',
         'type' => 'KEY'
      ],
   ];

   $a_table['oldkeys'] = [
   ];

   migrateTablesFusionInventory($migration, $a_table);

   if ($DB->tableExists("glpi_plugin_fusinvdeploy_files")) {
      if (!$DB->fieldExists("glpi_plugin_fusinvdeploy_files", "entities_id")) {
         $migration->addField('glpi_plugin_fusinvdeploy_files',
                 'entities_id', 'integer', ['value' => 0]);
         $migration->addField('glpi_plugin_fusinvdeploy_files',
                 'is_recursive', 'bool', ['value' => 0]);
         $migration->migrationOneTable('glpi_plugin_fusinvdeploy_files');
         if ($DB->fieldExists("glpi_plugin_fusinvdeploy_files", "filesize")) {
            $sql = "SELECT  files.`id`, files.`name`, files.`filesize`, "
                    . " files.`mimetype`, files.`sha512`, files.`shortsha512`, "
                    . " files.`create_date`, pkgs.`entities_id`, "
                    . " pkgs.`is_recursive` "
                    . " FROM glpi_plugin_fusinvdeploy_files as files "
                    . " LEFT JOIN glpi_plugin_fusinvdeploy_orders as orders"
                    . "    ON orders.`id` = files.`plugin_fusinvdeploy_orders_id` "
                    . " LEFT JOIN glpi_plugin_fusinvdeploy_packages as pkgs "
                    . "    ON orders.`plugin_fusinvdeploy_packages_id` = pkgs.`id`"
                    . " WHERE files.`shortsha512` != ''";
            $result = $DB->query($sql);
            if ($DB->numrows($result) > 0) {
               $update = $DB->buildUpdate(
                  'glpi_plugin_fusinvdeploy_files', [
                     'entities_id'  => new \QueryParam(),
                     'is_recursive' => new \QueryParam(),
                  ], [
                     'id'           => new \QueryParam()
                  ]
               );
               $stmt = $DB->prepare($update);
               while ($data = $DB->fetchArray($result)) {
                  $stmt->bind_param(
                     'sss',
                     $data['entities_id'],
                     $data['is_recursive'],
                     $data['id']
                  );
                  $stmt->execute();
               }
               mysqli_stmt_close($stmt);
            }
         }
      }
   }
}


/**
 * Manage the deploy package part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_deploypackage_migration($migration) {
   global $DB;

   /*
    * glpi_plugin_fusioninventory_deploypackages
    */

   $a_table = [];

   //table name
   $a_table['name'] = 'glpi_plugin_fusioninventory_deploypackages';
   $a_table['oldname'] = [
      'glpi_plugin_fusinvdeploy_packages'
   ];

   $a_table['fields'] = [
      'id' =>  [
               'type' => 'autoincrement',
               'value' => null
      ],
      'name' =>  [
               'type' => 'varchar(255) COLLATE utf8_unicode_ci NOT NULL',
               'value' => null
      ],
      'comment' =>  [
               'type' => "text",
               'value' => null
      ],
      'entities_id' =>  [
               'type' => 'int(11) NOT NULL',
               'value' => null
      ],
      'is_recursive' =>  [
               'type' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
               'value' => null
      ],
      'date_mod' =>  [
               'type' => 'datetime DEFAULT NULL',
               'value' => null
      ],
      'uuid' =>  [
               'type' => 'string',
               'value' => null
      ],
      'json' =>  [
               'type' => 'longtext DEFAULT NULL',
               'value' => null
      ],
      'plugin_fusioninventory_deploygroups_id' => [
               'type'    => 'integer',
               'value'   => null
      ],

   ];

   $a_table['oldfields'] = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];
   $a_table['keys'][] = [
         'field' => 'entities_id',
         'name' => '',
         'type' => 'INDEX'
      ];
   $a_table['keys'][] = [
         'field' => 'date_mod',
         'name' => '',
         'type' => 'INDEX'
      ];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);

   // Before update, manage old Installation and Uninstallation
   // see https://github.com/fusioninventory/fusioninventory-for-glpi/issues/1823
   $order_table = 'glpi_plugin_fusioninventory_deployorders';
   $migration->renameTable('glpi_plugin_fusinvdeploy_orders', $order_table);

   if ($DB->tableExists($order_table)
           and $DB->fieldExists($order_table, 'type', false)) {

      require_once(PLUGIN_FUSIONINVENTORY_DIR . "/inc/deploypackage.class.php");
      $pfDeployPackage = new PluginFusioninventoryDeployPackage();

      $installs = getAllDataFromTable($order_table, ['type' => '0']);
      foreach ($installs as $install) {
         $pfDeployPackage->getFromDB($install['plugin_fusioninventory_deploypackages_id']);
         $input = [
             'id'   => $pfDeployPackage->fields['id'],
             'json' => addslashes($install['json']),
         ];
         $pfDeployPackage->update($input);
      }

      $uninstalls = getAllDataFromTable($order_table, ['type' => '1']);
      foreach ($uninstalls as $uninstall) {
         if (countElementsInTable($order_table, [
               'type'                                     => '0',
               'plugin_fusioninventory_deploypackages_id' => $uninstall['plugin_fusioninventory_deploypackages_id'],
               'json'                                     => ['<>', ''],
            ]) > 0) {
            // have install and uninstall, so duplicate package
            $pfDeployPackage->getFromDB($uninstall['plugin_fusioninventory_deploypackages_id']);
            $input = $pfDeployPackage->fields;
            unset($input['id']);
            $input['json'] = $uninstall['json'];
            $input['name'] .= " (uninstall)";
            $deploypackage_id = $pfDeployPackage->add($input);
            $DB->update(
               $order_table, [
                  'plugin_fusioninventory_deploypackages_id'   => $deploypackage_id
               ], [
                  'id'                                         => $uninstall['id']
               ]
            );
         }
      }

   }
   if ($DB->tableExists($order_table)) {
      $migration->dropTable($order_table);
   }

   /*
    * Table glpi_plugin_fusioninventory_deploypackages_entities
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_deploypackages_entities';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']      = ['type'    => 'autoincrement',
                                         'value'   => ''];
   $a_table['fields']['plugin_fusioninventory_deploypackages_id'] = ['type' => 'integer',
                                                                          'value' => null];
   $a_table['fields']['entities_id']   = ['type'    => 'integer',
                                               'value'   => null];
   $a_table['fields']['is_recursive']  = ['type'    => 'bool',
                                               'value'   => '0'];
   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => 'plugin_fusioninventory_deploypackages_id', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'entities_id', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'is_recursive', 'name' => '', 'type' => 'INDEX'];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);

   /*
    * Table glpi_plugin_fusioninventory_deploypackages_groups
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_deploypackages_groups';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']      = ['type'    => 'autoincrement',
                                         'value'   => ''];
   $a_table['fields']['plugin_fusioninventory_deploypackages_id'] = ['type' => 'integer',
                                                                          'value' => null];
   $a_table['fields']['groups_id']   = ['type'    => 'integer',
                                             'value'   => null];
   $a_table['fields']['entities_id']   = ['type'    => 'integer',
                                               'value'   => null];
   $a_table['fields']['is_recursive']  = ['type'    => 'bool',
                                               'value'   => '0'];
   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => 'plugin_fusioninventory_deploypackages_id', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'groups_id', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'entities_id', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'is_recursive', 'name' => '', 'type' => 'INDEX'];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);

   /*
    * Table glpi_plugin_fusioninventory_deploypackages_profiles
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_deploypackages_profiles';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']      = ['type'    => 'autoincrement',
                                         'value'   => ''];
   $a_table['fields']['plugin_fusioninventory_deploypackages_id'] = ['type' => 'integer',
                                                                          'value' => null];
   $a_table['fields']['profiles_id']   = ['type'    => 'integer',
                                               'value'   => null];
   $a_table['fields']['entities_id']   = ['type'    => 'integer',
                                               'value'   => null];
   $a_table['fields']['is_recursive']  = ['type'    => 'bool',
                                               'value'   => '0'];
   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => 'plugin_fusioninventory_deploypackages_id', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'profiles_id', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'entities_id', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'is_recursive', 'name' => '', 'type' => 'INDEX'];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);

   /*
    * Table glpi_plugin_fusioninventory_deploypackages_users
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_deploypackages_users';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']      = ['type'    => 'autoincrement',
                                         'value'   => ''];
   $a_table['fields']['plugin_fusioninventory_deploypackages_id'] = ['type' => 'integer',
                                                                          'value' => null];
   $a_table['fields']['users_id']   = ['type'    => 'integer',
                                            'value'   => null];
   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => 'plugin_fusioninventory_deploypackages_id', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'users_id', 'name' => '', 'type' => 'INDEX'];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);

}


/**
 * Manage the deploy mirror part migration
 *
 * @param object $migration
 */
function do_deploymirror_migration($migration) {
   global $DB;

   /*
    * glpi_plugin_fusioninventory_deploymirrors
    */

   $a_table = [];

   //If table doesn't exists, then we're sure the is_active field is not present
   if (!$DB->tableExists('glpi_plugin_fusioninventory_deploymirrors')) {
      $is_active_exists = false;
   } else {
      $is_active_exists = ($DB->fieldExists('glpi_plugin_fusioninventory_deploymirrors',
                                       'is_active'));
   }

   //table name
   $a_table['name'] = 'glpi_plugin_fusioninventory_deploymirrors';
   $a_table['oldname'] = [
      'glpi_plugin_fusinvdeploy_mirrors'
   ];

   $a_table['fields'] = [
      'id' =>  [
         'type' => 'autoincrement',
         'value' => null
      ],
      'entities_id' =>  [
         'type' => 'int(11) NOT NULL',
         'value' => null
      ],
      'is_active' =>  [
         'type' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
         'value' => null
      ],
      'is_recursive' =>  [
         'type' => 'tinyint(1) NOT NULL DEFAULT \'0\'',
         'value' => null
      ],
      'name' =>  [
         'type' => 'varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL',
         'value' => null
      ],
      'url' =>  [
         'type' => "varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci".
                   " NOT NULL DEFAULT ''",
         'value' => null
      ],
      'locations_id' => [
         'type' => 'int(11) NOT NULL',
         'value' => 0
      ],
      'comment' =>  [
         'type' => "text",
         'value' => null
      ],
      'date_mod' =>  [
         'type' => 'datetime DEFAULT NULL',
         'value' => null
      ],
   ];

   $a_table['oldfields'] = [
   ];

   $a_table['renamefields'] = [
   ];

   $a_table['keys'] = [
      [
         'field' => 'entities_id',
         'name' => '',
         'type' => 'KEY'
      ],
      [
         'field' => 'is_active',
         'name' => '',
         'type' => 'KEY'
      ],
      [
         'field' => 'is_recursive',
         'name' => '',
         'type' => 'KEY'
      ],
      [
         'field' => 'date_mod',
         'name' => '',
         'type' => 'KEY'
      ],
   ];

   $a_table['oldkeys'] = [
   ];

   migrateTablesFusionInventory($migration, $a_table);

   //During migration, once the is_active field is added,
   //all mirrors must be active to keep compatibility
   if (!$is_active_exists) {
      $DB->update(
         'glpi_plugin_fusioninventory_deploymirrors', [
            'is_active' => 1
         ], [1 => 1]
      );
   }
}


/**
 * Manage the deploy group part migration
 *
 * @param object $migration
 */
function do_deploygroup_migration($migration) {

   /*
    * glpi_plugin_fusioninventory_deploygroups
    */

   $a_table = [];

   //table name
   $a_table['name'] = 'glpi_plugin_fusioninventory_deploygroups';
   $a_table['oldname'] = [
      'glpi_plugin_fusinvdeploy_groups'
   ];

   $a_table['fields'] = [
      'id' =>  [
         'type' => 'autoincrement',
         'value' => null
      ],
      'name' =>  [
         'type' => 'varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL',
         'value' => null
      ],
      'comment' =>  [
         'type' => "text",
         'value' => null
      ],
      'type' =>  [
         'type' => 'varchar(255) COLLATE utf8_unicode_ci NOT NULL',
         'value' => null
      ],
   ];

   $a_table['oldfields'] = [
   ];

   $a_table['renamefields'] = [
   ];

   $a_table['keys'] = [
   ];

   $a_table['oldkeys'] = [
   ];

   migrateTablesFusionInventory($migration, $a_table);

   /*
    * glpi_plugin_fusioninventory_deploygroups_staticdatas
    */

   $a_table = [];

   //table name
   $a_table['name'] = 'glpi_plugin_fusioninventory_deploygroups_staticdatas';
   $a_table['oldname'] = [
      'glpi_plugin_fusinvdeploy_groups_staticdatas'
   ];

   $a_table['fields'] = [
      'id' =>  [
         'type' => 'autoincrement',
         'value' => null
      ],
      'plugin_fusioninventory_deploygroups_id' =>  [
         'type' => 'integer',
         'value' => null
      ],
      'itemtype' =>  [
         'type' => 'varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL',
         'value' => null
      ],
      'items_id' =>  [
         'type' => 'integer',
         'value' => null
      ],
   ];

   $a_table['oldfields'] = [
   ];

   $a_table['renamefields'] = [
      'groups_id' => 'plugin_fusioninventory_deploygroups_id',
   ];

   $a_table['keys'] = [
      [
         'field' => 'plugin_fusioninventory_deploygroups_id',
         'name' => '',
         'type' => 'KEY'
      ],
      [
         'field' => 'items_id',
         'name' => '',
         'type' => 'KEY'
      ],
   ];

   $a_table['oldkeys'] = [
   ];

   migrateTablesFusionInventory($migration, $a_table);

   /*
    * glpi_plugin_fusioninventory_deploygroups_dynamicdatas
    */

   $a_table = [];

   //table name
   $a_table['name'] = 'glpi_plugin_fusioninventory_deploygroups_dynamicdatas';
   $a_table['oldname'] = [
      'glpi_plugin_fusinvdeploy_groups_dynamicdatas'
   ];

   $a_table['fields'] = [
      'id' =>  [
         'type' => 'autoincrement',
         'value' => null
      ],
      'plugin_fusioninventory_deploygroups_id' =>  [
         'type' => 'integer',
         'value' => null
      ],
      'fields_array' =>  [
         'type' => 'text',
         'value' => null
      ],
      'can_update_group' =>  [
         'type' => 'bool',
         'value' => 0
      ],
      'computers_id_cache' =>  [
         'type' => 'longtext',
         'value' => null
      ],
   ];

   $a_table['oldfields'] = [
   ];

   $a_table['renamefields'] = [
      'groups_id' => 'plugin_fusioninventory_deploygroups_id',
   ];

   $a_table['keys'] = [
      [
         'field' => 'plugin_fusioninventory_deploygroups_id',
         'name' => '',
         'type' => 'KEY'
      ],
      [
         'field' => 'can_update_group',
         'name' => '',
         'type' => 'KEY'
      ],
   ];

   $a_table['oldkeys'] = [
   ];

   migrateTablesFusionInventory($migration, $a_table);
}


/**
 * Manage the database locks part migration
 *
 * @param object $migration
 */
function do_dblocks_migration($migration) {

   /*
    * Table glpi_plugin_fusioninventory_dblockinventorynames
    */
      $a_table = [];
      $a_table['name'] = 'glpi_plugin_fusioninventory_dblockinventorynames';
      $a_table['oldname'] = [];

      $a_table['fields']  = [];
      $a_table['fields']['value']      = ['type'    => "varchar(100) NOT NULL DEFAULT ''",
                                               'value'   => null];
      $a_table['fields']['date']       = ['type'    => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP()',
                                               'value'   => null];

      $a_table['oldfields']  = [];

      $a_table['renamefields'] = [];

      $a_table['keys']   = [];
      $a_table['keys'][] = ['field' => 'value', 'name' => '', 'type' => 'UNIQUE'];

      $a_table['oldkeys'] = [];

      migrateTablesFusionInventory($migration, $a_table);

      /*
      * Table glpi_plugin_fusioninventory_dblockinventories
      */
      $a_table = [];
      $a_table['name'] = 'glpi_plugin_fusioninventory_dblockinventories';
      $a_table['oldname'] = [];

      $a_table['fields']  = [];
      $a_table['fields']['value']      = ['type'    => 'integer',
                                               'value'   => null];
      $a_table['fields']['date']       = ['type'    => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP()',
                                               'value'   => null];

      $a_table['oldfields']  = [];

      $a_table['renamefields'] = [];

      $a_table['keys']   = [];
      $a_table['keys'][] = ['field' => 'value', 'name' => '', 'type' => 'UNIQUE'];

      $a_table['oldkeys'] = [];

      migrateTablesFusionInventory($migration, $a_table);

      /*
      * Table glpi_plugin_fusioninventory_dblocksoftwares
      */
      $a_table = [];
      $a_table['name'] = 'glpi_plugin_fusioninventory_dblocksoftwares';
      $a_table['oldname'] = [];

      $a_table['fields']  = [];
      $a_table['fields']['value']      = ['type'    => 'bool',
                                               'value'   => null];
      $a_table['fields']['date']       = ['type'    => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP()',
                                               'value'   => null];

      $a_table['oldfields']  = [];

      $a_table['renamefields'] = [];

      $a_table['keys']   = [];
      $a_table['keys'][] = ['field' => 'value', 'name' => '', 'type' => 'UNIQUE'];

      $a_table['oldkeys'] = [];

      migrateTablesFusionInventory($migration, $a_table);

      /*
      * Table glpi_plugin_fusioninventory_dblocksoftwareversions
      */
      $a_table = [];
      $a_table['name'] = 'glpi_plugin_fusioninventory_dblocksoftwareversions';
      $a_table['oldname'] = [];

      $a_table['fields']  = [];
      $a_table['fields']['value']      = ['type'    => 'bool',
                                               'value'   => null];
      $a_table['fields']['date']       = ['type'    => 'timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP()',
                                               'value'   => null];

      $a_table['oldfields']  = [];

      $a_table['renamefields'] = [];

      $a_table['keys']   = [];
      $a_table['keys'][] = ['field' => 'value', 'name' => '', 'type' => 'UNIQUE'];

      $a_table['oldkeys'] = [];

      migrateTablesFusionInventory($migration, $a_table);
}


/**
 * Manage the ESX credentials part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_credentialESX_migration($migration) {
   global $DB;

   /*
    * Table glpi_plugin_fusioninventory_credentials
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_credentials';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => 'autoincrement',
                                             'value'   => ''];
   $a_table['fields']['entities_id']= ['type'    => 'integer',
                                             'value'   => null];
   $a_table['fields']['is_recursive']= ['type'    => 'bool',
                                             'value'   => null];
   $a_table['fields']['name']       = ['type'    => 'string',
                                             'value'   => ""];
   $a_table['fields']['username']   = ['type'    => 'string',
                                             'value'   => ""];
   $a_table['fields']['password']   = ['type'    => 'string',
                                             'value'   => ""];
   $a_table['fields']['comment']    = ['type'    => 'text',
                                             'value'   => null];
   $a_table['fields']['date_mod']   = ['type'    => 'datetime',
                                             'value'   => null];
   $a_table['fields']['itemtype']   = ['type'    => 'string',
                                             'value'   => ""];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);

   // Fix itemtype changed in 0.84
   $DB->update(
      'glpi_plugin_fusioninventory_credentials', [
         'itemtype'  => 'PluginFusioninventoryInventoryComputerESX'
      ], [
         'itemtype'  => 'PluginFusinvinventoryVmwareESX'
      ]
   );

   /*
    * Table glpi_plugin_fusioninventory_credentialips
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_credentialips';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => 'autoincrement',
                                             'value'   => ''];
   $a_table['fields']['entities_id']= ['type'    => 'integer',
                                             'value'   => null];
   $a_table['fields']['plugin_fusioninventory_credentials_id'] = ['type'    => 'integer',
                                             'value'   => null];
   $a_table['fields']['name']       = ['type'    => 'string',
                                             'value'   => ""];
   $a_table['fields']['comment']    = ['type'    => 'text',
                                             'value'   => null];
   $a_table['fields']['ip']         = ['type'    => 'string',
                                             'value'   => ""];
   $a_table['fields']['date_mod']   = ['type'    => 'datetime',
                                             'value'   => null];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);
}


/**
 * Manage the collect part migration
 *
 * @param object $migration
 */
function do_collect_migration($migration) {

   /*
    * Table glpi_plugin_fusioninventory_collects
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_collects';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => "autoincrement",
                                             'value'   => ''];
   $a_table['fields']['name']       = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['entities_id']   = ['type'    => 'integer',
                                                'value'   => null];
   $a_table['fields']['is_recursive']  = ['type'    => 'bool',
                                                'value'   => null];
   $a_table['fields']['type']       = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['is_active']  = ['type'    => 'bool',
                                             'value'   => null];
   $a_table['fields']['comment']    = ['type'    => 'text',
                                             'value'   => null];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);

   /*
   * Table glpi_plugin_fusioninventory_collects_registries
   */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_collects_registries';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => "autoincrement",
                                             'value'   => ''];
   $a_table['fields']['name']       = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['plugin_fusioninventory_collects_id']   = ['type'    => 'integer',
                                                'value'   => null];
   $a_table['fields']['hive']       = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['path']       = ['type'    => 'text',
                                             'value'   => null];
   $a_table['fields']['key']        = ['type'    => 'string',
                                             'value'   => null];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);

   /*
   * Table glpi_plugin_fusioninventory_collects_registries_contents
   */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_collects_registries_contents';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => "autoincrement",
                                             'value'   => ''];
   $a_table['fields']['computers_id'] = ['type'    => 'integer',
                                             'value'   => null];
   $a_table['fields']['plugin_fusioninventory_collects_registries_id']   = ['type'    => 'integer',
                                                'value'   => null];
   $a_table['fields']['key']       = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['value']     = ['type'    => 'string',
                                             'value'   => null];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => 'computers_id', 'name' => '', 'type' => 'INDEX'];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);

   /*
   * Table glpi_plugin_fusioninventory_collects_wmis
   */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_collects_wmis';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => "autoincrement",
                                             'value'   => ''];
   $a_table['fields']['name']       = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['plugin_fusioninventory_collects_id']   = ['type'    => 'integer',
                                                'value'   => null];
   $a_table['fields']['moniker']    = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['class']      = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['properties'] = ['type'    => 'string',
                                             'value'   => null];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);

   /*
   * Table glpi_plugin_fusioninventory_collects_wmis_contents
   */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_collects_wmis_contents';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => "autoincrement",
                                             'value'   => ''];
   $a_table['fields']['computers_id'] = ['type'    => 'integer',
                                             'value'   => null];
   $a_table['fields']['plugin_fusioninventory_collects_wmis_id']   = ['type'    => 'integer',
                                                'value'   => null];
   $a_table['fields']['property']   = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['value']      = ['type'    => 'string',
                                             'value'   => null];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);

   /*
   * Table glpi_plugin_fusioninventory_collects_files
   */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_collects_files';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => "autoincrement",
                                             'value'   => ''];
   $a_table['fields']['name']       = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['plugin_fusioninventory_collects_id']   = ['type'    => 'integer',
                                                'value'   => null];
   $a_table['fields']['dir']        = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['limit']      = ['type'    => "int(4) NOT NULL DEFAULT '50'",
                                             'value'   => null];
   $a_table['fields']['is_recursive'] = ['type'    => 'bool',
                                             'value'   => null];
   $a_table['fields']['filter_regex'] = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['filter_sizeequals'] = ['type'    => 'integer',
                                             'value'   => null];
   $a_table['fields']['filter_sizegreater'] = ['type'    => 'integer',
                                             'value'   => null];
   $a_table['fields']['filter_sizelower'] = ['type'    => 'integer',
                                             'value'   => null];
   $a_table['fields']['filter_checksumsha512'] = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['filter_checksumsha2'] = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['filter_name'] = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['filter_iname'] = ['type'    => 'string',
                                             'value'   => null];
   $a_table['fields']['filter_is_file'] = ['type'    => 'bool',
                                             'value'   => '1'];
   $a_table['fields']['filter_is_dir'] = ['type'    => 'bool',
                                             'value'   => '0'];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);

   /*
   * Table glpi_plugin_fusioninventory_collects_files_contents
   */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_collects_files_contents';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']         = ['type'    => "autoincrement",
                                             'value'   => ''];
   $a_table['fields']['computers_id'] = ['type'    => 'integer',
                                             'value'   => null];
   $a_table['fields']['plugin_fusioninventory_collects_files_id']   = ['type'    => 'integer',
                                                'value'   => null];
   $a_table['fields']['pathfile']   = ['type'    => 'text',
                                             'value'   => null];
   $a_table['fields']['size']       = ['type'    => 'integer',
                                             'value'   => null];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);
}


/**
 * Manage the SNMP models part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_snmpmodel_migration($migration) {
   global $DB;

   $elements = ['5151', 'PluginFusinvsnmpModel',
       'PluginFusioninventorySnmpmodel', 'PluginFusinvsnmpConstructDevice',
       'PluginFusioninventorySnmpmodelConstructDevice', '5167'];
   foreach ($elements as $element) {
      $DB->delete(
         'glpi_displaypreferences', [
            'itemtype' => $element
         ]
      );
   }
}


/**
 * Manage the rules part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_rule_migration($migration) {
   global $DB;

   /*
    * Update rules
    */
   $DB->update(
      'glpi_rules', [
         'sub_type'  => 'PluginFusioninventoryInventoryRuleImport'
      ], [
         'sub_type'  => 'PluginFusioninventoryRuleImportEquipment'
      ]
   );

   $iterator = $DB->request([
      'FROM'   => 'glpi_rules',
      'WHERE'  => ['sub_type' => 'PluginFusioninventoryInventoryRuleImport']
   ]);
   if (count($iterator)) {
      $update = $DB->buildUpdate(
         'glpi_ruleactions', [
            'value'  => 1
         ], [
            'rules_id'  => new \QueryParam(),
            'value'     => 0,
            'field'     => '_fusion'
         ]
      );
      $stmt = $DB->prepare($update);
      while ($data = $iterator->next()) {
         $stmt->bind_param('s', $data['id']);
         $stmt->execute();
      }
      mysqli_stmt_close($stmt);
   }

   $DB->update(
      'glpi_rules', [
         'sub_type'  => 'PluginFusioninventoryInventoryRuleEntity'
      ], [
         'sub_type'  => 'PluginFusinvinventoryRuleEntity'
      ]
   );

   /*
    *  Add default rules
    */
   if ($DB->tableExists("glpi_plugin_tracker_config_discovery")) {
      $migration->displayMessage("Create rules");
      $pfSetup = new PluginFusioninventorySetup();
      $pfSetup->initRules();
   }
   // If no rules, add them
   if (countElementsInTable('glpi_rules',
         ['sub_type' => 'PluginFusioninventoryInventoryRuleImport']) == 0) {
      $migration->displayMessage("Create rules");
      $pfSetup = new PluginFusioninventorySetup();
      $pfSetup->initRules();
   }
   // Add peripheral rules (in first in rule list) when use it since 0.85
   $DB->delete(
      'glpi_plugin_fusioninventory_configs', [
         'type'   => 'import_peripheral'
      ]
   );

   $DB->update(
      'glpi_rules', [
         'ranking'   => new \QueryExpression($DB->quoteName('ranking') . ' + 3')
      ], [
         'sub_type'  => 'PluginFusioninventoryInventoryRuleImport'
      ]
   );

   $ranking = 0;

   // Create rule for : Peripheral + serial
   $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
   $ruleimport     = new Rule();
   $input = [];
   $input['name']='Peripheral serial';
   $input['match']='AND';
   $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
   if (!$ruleimport->getFromDBByCrit($input)) {
      $input['is_active']=1;
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "serial";
      $input['pattern']= 1;
      $input['condition']=10;
      $rulecriteria->add($input);

      $input = [];
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "serial";
      $input['pattern']= 1;
      $input['condition']=8;
      $rulecriteria->add($input);

      $input = [];
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "itemtype";
      $input['pattern']= 'Peripheral';
      $input['condition']=0;
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['action_type'] = 'assign';
      $input['field'] = '_fusion';
      $input['value'] = '1';
      $ruleaction->add($input);
      $ranking++;
   }

   // Create rule for : Peripheral import
   $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
   $ruleimport     = new Rule();
   $input = [];
   $input['name']='Peripheral import';
   $input['match']='AND';
   $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
   if (!$ruleimport->getFromDBByCrit($input)) {
      $input['is_active']=1;
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "itemtype";
      $input['pattern']= 'Peripheral';
      $input['condition']=0;
      $rulecriteria->add($input);

      $input = [];
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "serial";
      $input['pattern']= 1;
      $input['condition']=8;
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['action_type'] = 'assign';
      $input['field'] = '_fusion';
      $input['value'] = '1';
      $ruleaction->add($input);
      $ranking++;
   }

   // Create rule for : Peripheral ignore import
   $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
   $ruleimport     = new Rule();
   $input = [];
   $input['name']='Peripheral ignore import';
   $input['match']='AND';
   $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
   if (!$ruleimport->getFromDBByCrit($input)) {
      $input['is_active']=1;
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "itemtype";
      $input['pattern']= 'Peripheral';
      $input['condition']=0;
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['action_type'] = 'assign';
      $input['field'] = '_ignore_import';
      $input['value'] = '1';
      $ruleaction->add($input);
   }

   // Add monitor rules (in first in rule list) when use it since 0.85
   $DB->delete(
      'glpi_plugin_fusioninventory_configs', [
         'type'   => 'import_printer'
      ]
   );

   /*
   *  Manage configuration of plugin
   */
   $config = new PluginFusioninventoryConfig();
   $pfSetup = new PluginFusioninventorySetup();
   $users_id = $pfSetup->createFusionInventoryUser();
   $a_input = [];
   $a_input['ssl_only'] = 0;
   $a_input['delete_task'] = 20;
   $a_input['inventory_frequence'] = 24;
   $a_input['agent_port'] = 62354;
   $a_input['extradebug'] = 0;
   $a_input['users_id'] = $users_id;
   $a_input['agents_old_days'] = 0;
   $a_input['agents_action'] = 0;
   $a_input['agents_status'] = 0;
   $config->addValues($a_input, false);

   $a_input = [];
   $a_input['version'] = PLUGIN_FUSIONINVENTORY_VERSION;
   $config->addValues($a_input, true);
   $a_input = [];
   $a_input['ssl_only'] = 0;
   if (isset($prepare_Config['ssl_only'])) {
      $a_input['ssl_only'] = $prepare_Config['ssl_only'];
   }
   $a_input['delete_task'] = 20;
   $a_input['inventory_frequence'] = 24;
   $a_input['agent_port'] = 62354;
   $a_input['extradebug'] = 0;
   $a_input['users_id'] = 0;

   //Deploy configuration options
   $a_input['server_upload_path'] =
         Toolbox::addslashes_deep(
            implode(
               DIRECTORY_SEPARATOR,
               [
                  GLPI_PLUGIN_DOC_DIR,
                  'fusioninventory',
                  'upload'
               ]
            )
         );
   $a_input['alert_winpath']    = 1;
   $a_input['server_as_mirror'] = 1;
   $a_input['mirror_match']     = 0;
   $config->addValues($a_input, false);

   $pfSetup = new PluginFusioninventorySetup();
   $users_id = $pfSetup->createFusionInventoryUser();
   $DB->update(
      'glpi_plugin_fusioninventory_configs', [
         'value'  => $users_id
      ], [
         'type'   => 'users_id'
      ]
   );

   // Update fusinvinventory _config values to this plugin
   $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
   $ruleimport     = new Rule();
   $input = [];
   $input['name']='Monitor serial';
   $input['match']='AND';
   $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
   if (!$ruleimport->getFromDBByCrit($input)) {
      $input['is_active']=1;
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "serial";
      $input['pattern']= 1;
      $input['condition']=10;
      $rulecriteria->add($input);

      $input = [];
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "serial";
      $input['pattern']= 1;
      $input['condition']=8;
      $rulecriteria->add($input);

      $input = [];
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "itemtype";
      $input['pattern']= 'Monitor';
      $input['condition']=0;
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['action_type'] = 'assign';
      $input['field'] = '_fusion';
      $input['value'] = '1';
      $ruleaction->add($input);

      $ranking++;
   }

   // Create rule for : Monitor import
   $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
   $ruleimport     = new Rule();
   $input = [];
   $input['name']='Monitor import';
   $input['match']='AND';
   $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
   if (!$ruleimport->getFromDBByCrit($input)) {
      $input['is_active']=1;
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "itemtype";
      $input['pattern']= 'Monitor';
      $input['condition']=0;
      $rulecriteria->add($input);

      $input = [];
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "serial";
      $input['pattern']= 1;
      $input['condition']=8;
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['action_type'] = 'assign';
      $input['field'] = '_fusion';
      $input['value'] = '1';
      $ruleaction->add($input);

      $ranking++;
   }

   // Create rule for : Monitor ignore import
   $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
   $ruleimport     = new Rule();
   $input = [];
   $input['name']='Monitor ignore import';
   $input['match']='AND';
   $input['sub_type'] = 'PluginFusioninventoryInventoryRuleImport';
   if (!$ruleimport->getFromDBByCrit($input)) {
      $input['is_active']=1;
      $input['ranking'] = $ranking;
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['criteria'] = "itemtype";
      $input['pattern']= 'Monitor';
      $input['condition']=0;
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [];
      $input['rules_id'] = $rule_id;
      $input['action_type'] = 'assign';
      $input['field'] = '_ignore_import';
      $input['value'] = '1';
      $ruleaction->add($input);
   }

   // Add printer rules (in first in rule list) when use it since 0.85
   $DB->delete(
      'glpi_plugin_fusioninventory_configs', [
         'type' => 'import_printer'
      ]
   );

   // Add 8 rules for connexions from networkequipment in top of the list
   $rule = new PluginFusioninventoryInventoryRuleImport();
   if (countElementsInTable("glpi_rules", [
      "name" => "Device update (by mac+ifnumber restricted port)",
      "sub_type" => "PluginFusioninventoryInventoryRuleImport"
      ]) == 0) {

      $DB->query("UPDATE glpi_rules "
            . "SET ranking = ranking + 8 "
            . "WHERE `sub_type`='PluginFusioninventoryInventoryRuleImport'");

      // Create rule for : Device update (by mac+ifnumber restricted port)
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $ruleimport     = new Rule();
      $input = [
         'name'     => 'Device update (by mac+ifnumber restricted port)',
         'match'    => 'AND',
         'sub_type' => 'PluginFusioninventoryInventoryRuleImport',
         'is_active' => 1,
         'ranking'  => 0,
      ];
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "itemtype",
         'pattern'   => 1,
         'condition' => 9
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "mac",
         'pattern'   => 1,
         'condition' => 10
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "mac",
         'pattern'   => 1,
         'condition' => 8
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "ifnumber",
         'pattern'   => 1,
         'condition' => 10
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "ifnumber",
         'pattern'   => 1,
         'condition' => 8
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "link_criteria_port",
         'pattern'   => 1,
         'condition' => 203
      ];
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [
         'rules_id'    => $rule_id,
         'action_type' => 'assign',
         'field'       => '_fusion',
         'value'       => '1'
      ];
      $ruleaction->add($input);

      // Create rule for : Device update (by mac+ifnumber not restricted port)
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $ruleimport     = new Rule();
      $input = [
         'name'     => 'Device update (by mac+ifnumber not restricted port)',
         'match'    => 'AND',
         'sub_type' => 'PluginFusioninventoryInventoryRuleImport',
         'is_active' => 1,
         'ranking'  => 1,
      ];
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "itemtype",
         'pattern'   => 1,
         'condition' => 9
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "mac",
         'pattern'   => 1,
         'condition' => 10
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "mac",
         'pattern'   => 1,
         'condition' => 8
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "ifnumber",
         'pattern'   => 1,
         'condition' => 10
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "ifnumber",
         'pattern'   => 1,
         'condition' => 8
      ];
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [
         'rules_id'    => $rule_id,
         'action_type' => 'assign',
         'field'       => '_fusion',
         'value'       => '1'
      ];
      $ruleaction->add($input);

      // Create rule for : Device update (by mac+ifnumber not restricted port)
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $ruleimport     = new Rule();
      $input = [
         'name'     => 'Device update (by ip+ifdescr restricted port)',
         'match'    => 'AND',
         'sub_type' => 'PluginFusioninventoryInventoryRuleImport',
         'is_active' => 1,
         'ranking'  => 2,
      ];
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "itemtype",
         'pattern'   => 1,
         'condition' => 9
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "ip",
         'pattern'   => 1,
         'condition' => 10
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "ip",
         'pattern'   => 1,
         'condition' => 8
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "ifdescr",
         'pattern'   => 1,
         'condition' => 10
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "ifdescr",
         'pattern'   => 1,
         'condition' => 8
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "link_criteria_port",
         'pattern'   => 1,
         'condition' => 203
      ];
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [
         'rules_id'    => $rule_id,
         'action_type' => 'assign',
         'field'       => '_fusion',
         'value'       => '1'
      ];
      $ruleaction->add($input);

      // Create rule for : Device update (by mac+ifnumber not restricted port)
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $ruleimport     = new Rule();
      $input = [
         'name'     => 'Device update (by ip+ifdescr not restricted port)',
         'match'    => 'AND',
         'sub_type' => 'PluginFusioninventoryInventoryRuleImport',
         'is_active' => 1,
         'ranking'  => 3,
      ];
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "itemtype",
         'pattern'   => 1,
         'condition' => 9
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "ip",
         'pattern'   => 1,
         'condition' => 10
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "ip",
         'pattern'   => 1,
         'condition' => 8
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "ifdescr",
         'pattern'   => 1,
         'condition' => 10
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "ifdescr",
         'pattern'   => 1,
         'condition' => 8
      ];
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [
         'rules_id'    => $rule_id,
         'action_type' => 'assign',
         'field'       => '_fusion',
         'value'       => '1'
      ];
      $ruleaction->add($input);

      // Create rule for : Device import (by mac+ifnumber)
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $ruleimport     = new Rule();
      $input = [
         'name'     => 'Device import (by mac+ifnumber)',
         'match'    => 'AND',
         'sub_type' => 'PluginFusioninventoryInventoryRuleImport',
         'is_active' => 1,
         'ranking'  => 4,
      ];
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "itemtype",
         'pattern'   => 1,
         'condition' => 9
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "mac",
         'pattern'   => 1,
         'condition' => 8
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "ifnumber",
         'pattern'   => 1,
         'condition' => 8
      ];
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [
         'rules_id'    => $rule_id,
         'action_type' => 'assign',
         'field'       => '_fusion',
         'value'       => '1'
      ];
      $ruleaction->add($input);

      // Create rule for : Device import (by ip+ifdescr)
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $ruleimport     = new Rule();
      $input = [
         'name'     => 'Device import (by ip+ifdescr)',
         'match'    => 'AND',
         'sub_type' => 'PluginFusioninventoryInventoryRuleImport',
         'is_active' => 1,
         'ranking'  => 5,
      ];
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "itemtype",
         'pattern'   => 1,
         'condition' => 9
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "ip",
         'pattern'   => 1,
         'condition' => 8
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "ifdescr",
         'pattern'   => 1,
         'condition' => 8
      ];
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [
         'rules_id'    => $rule_id,
         'action_type' => 'assign',
         'field'       => '_fusion',
         'value'       => '1'
      ];
      $ruleaction->add($input);

      // Create rule for : Update only mac address (mac on switch port)
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $ruleimport     = new Rule();
      $input = [
         'name'     => 'Update only mac address (mac on switch port)',
         'match'    => 'AND',
         'sub_type' => 'PluginFusioninventoryInventoryRuleImport',
         'is_active' => 1,
         'ranking'  => 6,
      ];
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "itemtype",
         'pattern'   => 1,
         'condition' => 9
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "mac",
         'pattern'   => 1,
         'condition' => 10
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "mac",
         'pattern'   => 1,
         'condition' => 8
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "only_these_criteria",
         'pattern'   => 1,
         'condition' => 204
      ];
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [
         'rules_id'    => $rule_id,
         'action_type' => 'assign',
         'field'       => '_fusion',
         'value'       => '1'
      ];
      $ruleaction->add($input);

      // Create rule for : Import only mac address (mac on switch port)
      $rulecollection = new PluginFusioninventoryInventoryRuleImportCollection();
      $ruleimport     = new Rule();
      $input = [
         'name'     => 'Import only mac address (mac on switch port)',
         'match'    => 'AND',
         'sub_type' => 'PluginFusioninventoryInventoryRuleImport',
         'is_active' => 1,
         'ranking'  => 7,
      ];
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "itemtype",
         'pattern'   => 1,
         'condition' => 9
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "mac",
         'pattern'   => 1,
         'condition' => 8
      ];
      $rulecriteria->add($input);

      $input = [
         'rules_id'  => $rule_id,
         'criteria'  => "only_these_criteria",
         'pattern'   => 1,
         'condition' => 204
      ];
      $rulecriteria->add($input);

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $input = [
         'rules_id'    => $rule_id,
         'action_type' => 'assign',
         'field'       => '_fusion',
         'value'       => '1'
      ];
      $ruleaction->add($input);
   }
}


/**
 * Manage the task part migration
 *
 * @global object $DB
 * @param object $migration
 */
function do_task_migration($migration) {
   global $DB;

   /*
    * Table glpi_plugin_fusioninventory_taskjobs
    */
   $a_table = [];
   $a_table['name'] = 'glpi_plugin_fusioninventory_taskjobs';
   $a_table['oldname'] = [];

   $a_table['fields']  = [];
   $a_table['fields']['id']                     = ['type'    => 'autoincrement',
                                                        'value'   => ''];
   $a_table['fields']['plugin_fusioninventory_tasks_id'] = ['type'    => 'integer',
                                                                 'value'   => null];
   $a_table['fields']['entities_id']   = ['type'    => 'integer',
                                               'value'   => null];
   $a_table['fields']['name']          = ['type'    => 'string',
                                               'value'   => null];
   $a_table['fields']['date_creation'] = ['type'    => 'datetime',
                                               'value'   => null];
   $a_table['fields']['method']        = ['type'    => 'string',
                                               'value'   => null];
   $a_table['fields']['targets']       = ['type'    => 'text',
                                               'value'   => null];
   $a_table['fields']['actors']        = ['type'    => 'text',
                                               'value'   => null];
   $a_table['fields']['comment']       = ['type'    => 'text',
                                               'value'   => null];
   $a_table['fields']['rescheduled_taskjob_id'] = ['type'    => 'integer',
                                                        'value'   => null];
   $a_table['fields']['statuscomments'] = ['type'    => 'text',
                                                'value'   => null];
   $a_table['fields']['enduser']       = ['type'    => 'text',
                                               'value'   => null];

   $a_table['oldfields']  = [];

   $a_table['renamefields'] = [];

   $a_table['keys']   = [];
   $a_table['keys'][] = ['field' => 'plugin_fusioninventory_tasks_id', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'entities_id', 'name' => '', 'type' => 'INDEX'];
   $a_table['keys'][] = ['field' => 'method', 'name' => '', 'type' => 'INDEX'];

   $a_table['oldkeys'] = [];

   migrateTablesFusionInventory($migration, $a_table);
}


/**
 * Migrate search params from the old system to the new one
 * As search engine integration has been improved with GLPI 0.85
 *
 * @since 0.85+1.0
 *
 * @global object $DB
 */
function doDynamicDataSearchParamsMigration() {
   global $DB;

   $iterator = $DB->request([
      'SELECT' => ['id', 'fields_array'],
      'FROM'   => 'glpi_plugin_fusioninventory_deploygroups_dynamicdatas'
   ]);

   if (count($iterator)) {
      $update = $DB->buildUpdate(
         'glpi_plugin_fusioninventory_deploygroups_dynamicdatas', [
            'fields_array' => new \QueryParam()
         ], [
            'id'           => new \QueryParam()
         ]
      );
      $stmt = $DB->prepare($update);
      while ($dynamic_data = $iterator->next()) {
         $new_values   = migrationDynamicGroupFields($dynamic_data['fields_array']);
         $stmt->bind_param(
            'ss',
            $new_values,
            $dynamic_data['id']
         );
      }
      mysqli_stmt_close($stmt);
   }
}


/**
 * Migration of one dynamic group
 *
 * @since 0.85+1.0
 *
 * @param array $fields search paramas in old format (serialized)
 * @return string search paramas in new format (serialized)
 */
function migrationDynamicGroupFields($fields) {
   $data       = json_decode($fields, true);
   $new_fields = [];
   if (!is_array($data)) {
      $data   = unserialize($fields);
   }

   //We're still in 0.85 or higher,
   //no need for migration !
   if (isset($data['criteria'])) {
      return $fields;
   }

   //Upgrade from 0.84
   if (isset($data['field'])) {
      $count_fields = count ($data['field']);
      for ($i = 0; $i < $count_fields; $i++) {
         $new_value = [];
         $new_value['value']       = $data['contains'][$i];
         $new_value['field']       = $data['field'][$i];
         $new_value['searchtype']  = $data['searchtype'][$i];
         $new_fields['criteria'][] = $new_value;
      }

      if (isset($data['field2'])) {
         $count_fields = count ($data['field2']);
         for ($i = 0; $i < $count_fields; $i++) {
            $new_value = [];
            $new_value['value']           = $data['contains2'][$i];
            $new_value['field']           = $data['field2'][$i];
            $new_value['itemtype']        = $data['itemtype2'][$i];
            $new_value['searchtype']      = $data['searchtype2'][$i];
            $new_fields['metacriteria'][] = $new_value;
         }
      }
   } else if (isset($data['itemtype']) && isset($data['name'])) {
      //Ugrapde from 0.83, where the number of fields to search was fixed
      $oldfields = ['name'                => 2,
                         'serial'              => 5,
                         'otherserial'         => 6,
                         'locations_id'        => 3,
                         'operatingsystems_id' => 45,
                         'room'                => 92,
                         'building'            => 91];
      foreach ($oldfields as $name => $id) {
         $new_value = [];
         if (isset($data[$name]) && $data[$name] != '') {
            $new_value['field']       = $id;
            $new_value['value']       = $data[$name];
            $new_value['searchtype']  = 'equals';
         }
         if (!empty($new_value)) {
            $new_fields['criteria'][] = $new_value;
         }
      }
   }
   return serialize($new_fields);
}


/**
 * Manage the display preference part migration
 *
 * @global object $DB
 * @param string $olditemtype
 * @param string $newitemtype
 */
function changeDisplayPreference($olditemtype, $newitemtype) {
   global $DB;

   $query = "SELECT `users_id`, `num`, count(*) as `cnt`, GROUP_CONCAT( id SEPARATOR ' ') as id
      FROM `glpi_displaypreferences`
      WHERE (`itemtype` = '".$newitemtype."'
      OR `itemtype` = '".$olditemtype."')
      group by `users_id`, `num`";
   $result = $DB->query($query);
   while ($data = $DB->fetchArray($result)) {
      if ($data['cnt'] > 1) {
         $ids = explode(' ', $data['id']);
         array_shift($ids);
         $DB->delete(
            'glpi_displaypreferences', [
               'id' => $ids
            ]
         );
      }
   }

   $DB->update(
      'glpi_displaypreferences', [
         'itemtype'  => $newitemtype
      ], [
         'itemtype'  => $olditemtype
      ]
   );
}


/**
 * Manage the update of mapping part migration
 */
function pluginFusioninventoryUpdatemapping() {

   /*
    * Udpate mapping
    */
   $pfMapping = new PluginFusioninventoryMapping();

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'location';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'locations_id';
   $a_input['locale']      = 1;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'firmware';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'networkequipmentfirmwares_id';
   $a_input['locale']      = 2;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'firmware1';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 2;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'firmware2';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 2;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'contact';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'contact';
   $a_input['locale']      = 403;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'comments';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'comment';
   $a_input['locale']      = 404;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'uptime';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkequipments';
   $a_input['tablefield']  = 'uptime';
   $a_input['locale']      = 3;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cpu';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkequipments';
   $a_input['tablefield']  = 'cpu';
   $a_input['locale']      = 12;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cpuuser';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkequipments';
   $a_input['tablefield']  = 'cpu';
   $a_input['locale']      = 401;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cpusystem';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkequipments';
   $a_input['tablefield']  = 'cpu';
   $a_input['locale']      = 402;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'serial';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'serial';
   $a_input['locale']      = 13;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'otherserial';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'otherserial';
   $a_input['locale']      = 419;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'name';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'name';
   $a_input['locale']      = 20;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ram';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'ram';
   $a_input['locale']      = 21;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'memory';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkequipments';
   $a_input['tablefield']  = 'memory';
   $a_input['locale']      = 22;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'vtpVlanName';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 19;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'vmvlan';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 430;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'entPhysicalModelName';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'networkequipmentmodels_id';
   $a_input['locale']      = 17;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'macaddr';
   $a_input['table']       = 'glpi_networkequipments';
   $a_input['tablefield']  = 'ip';
   $a_input['locale']      = 417;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cdpCacheAddress';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 409;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cdpCacheDevicePort';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 410;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cdpCacheVersion';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 435;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cdpCacheDeviceId';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 436;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'cdpCachePlatform';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 437;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpRemChassisId';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 431;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpRemPortId';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 432;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpLocChassisId';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 432;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpRemSysDesc';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 438;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpRemSysName';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 439;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'lldpRemPortDesc';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 440;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'vlanTrunkPortDynamicStatus';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 411;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'dot1dTpFdbAddress';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 412;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ipNetToMediaPhysAddress';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 413;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'dot1dTpFdbPort';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 414;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'dot1dBasePortIfIndex';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 415;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ipAdEntAddr';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 421;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'PortVlanIndex';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 422;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifIndex';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 408;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifmtu';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifmtu';
   $a_input['locale']      = 4;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifspeed';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifspeed';
   $a_input['locale']      = 5;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifinternalstatus';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifinternalstatus';
   $a_input['locale']      = 6;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'iflastchange';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'iflastchange';
   $a_input['locale']      = 7;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifinoctets';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifinoctets';
   $a_input['locale']      = 8;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifoutoctets';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifoutoctets';
   $a_input['locale']      = 9;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifinerrors';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifinerrors';
   $a_input['locale']      = 10;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifouterrors';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifouterrors';
   $a_input['locale']      = 11;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifstatus';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifstatus';
   $a_input['locale']      = 14;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifPhysAddress';
   $a_input['table']       = 'glpi_networkports';
   $a_input['tablefield']  = 'mac';
   $a_input['locale']      = 15;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifName';
   $a_input['table']       = 'glpi_networkports';
   $a_input['tablefield']  = 'name';
   $a_input['locale']      = 16;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifType';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 18;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifdescr';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifdescr';
   $a_input['locale']      = 23;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'portDuplex';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'portduplex';
   $a_input['locale']      = 33;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'NetworkEquipment';
   $a_input['name']        = 'ifalias';
   $a_input['table']       = 'glpi_plugin_fusioninventory_networkports';
   $a_input['tablefield']  = 'ifalias';
   $a_input['locale']      = 120;
   $pfMapping->set($a_input);

   // Printers
   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'model';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'printermodels_id';
   $a_input['locale']      = 25;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'enterprise';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'manufacturers_id';
   $a_input['locale']      = 420;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'serial';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'serial';
   $a_input['locale']      = 27;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'contact';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'contact';
   $a_input['locale']      = 405;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'comments';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'comment';
   $a_input['locale']      = 406;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'name';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'comment';
   $a_input['locale']      = 24;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'otherserial';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'otherserial';
   $a_input['locale']      = 418;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'memory';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'memory_size';
   $a_input['locale']      = 26;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'location';
   $a_input['table']       = 'glpi_printers';
   $a_input['tablefield']  = 'locations_id';
   $a_input['locale']      = 56;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'informations';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 165;
   $a_input['shortlocale'] = 165;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblack';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 157;
   $a_input['shortlocale'] = 157;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblackmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 166;
   $a_input['shortlocale'] = 166;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblackused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 167;
   $a_input['shortlocale'] = 167;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblackremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 168;
   $a_input['shortlocale'] = 168;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblack2';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 157;
   $a_input['shortlocale'] = 157;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblack2max';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 166;
   $a_input['shortlocale'] = 166;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblack2used';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 167;
   $a_input['shortlocale'] = 167;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonerblack2remaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 168;
   $a_input['shortlocale'] = 168;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonercyan';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 158;
   $a_input['shortlocale'] = 158;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonercyanmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 169;
   $a_input['shortlocale'] = 169;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonercyanused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 170;
   $a_input['shortlocale'] = 170;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonercyanremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 171;
   $a_input['shortlocale'] = 171;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonermagenta';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 159;
   $a_input['shortlocale'] = 159;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonermagentamax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 172;
   $a_input['shortlocale'] = 172;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonermagentaused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 173;
   $a_input['shortlocale'] = 173;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'tonermagentaremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 174;
   $a_input['shortlocale'] = 174;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'toneryellow';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 160;
   $a_input['shortlocale'] = 160;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'toneryellowmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 175;
   $a_input['shortlocale'] = 175;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'toneryellowused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 176;
   $a_input['shortlocale'] = 176;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'toneryellowused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 177;
   $a_input['shortlocale'] = 177;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'wastetoner';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 151;
   $a_input['shortlocale'] = 151;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'wastetonermax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 190;
   $a_input['shortlocale'] = 190;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'wastetonerused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 191;
   $a_input['shortlocale'] = 191;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'wastetonerremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 192;
   $a_input['shortlocale'] = 192;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgeblack';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 134;
   $a_input['shortlocale'] = 134;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgeblackmatte';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 133;
   $a_input['shortlocale'] = 133;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgematteblack';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 133;
   $a_input['shortlocale'] = 133;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgeblackphoto';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 135;
   $a_input['shortlocale'] = 135;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgephotoblack';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 135;
   $a_input['shortlocale'] = 135;

   $pfMapping->set($a_input);
   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgecyan';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 136;
   $a_input['shortlocale'] = 136;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgecyanlight';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 139;
   $a_input['shortlocale'] = 139;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgelightcyan';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 139;
   $a_input['shortlocale'] = 139;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgemagenta';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 138;
   $a_input['shortlocale'] = 138;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgemagentalight';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 140;
   $a_input['shortlocale'] = 140;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgelightmagenta';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 140;
   $a_input['shortlocale'] = 140;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgeyellow';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 137;
   $a_input['shortlocale'] = 137;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgegrey';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 196;
   $a_input['shortlocale'] = 196;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgegray';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 196;
   $a_input['shortlocale'] = 196;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgegreylight';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 211;
   $a_input['shortlocale'] = 211;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgegraylight';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 211;
   $a_input['shortlocale'] = 211;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgelightgrey';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 211;
   $a_input['shortlocale'] = 211;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgelightgray';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 211;
   $a_input['shortlocale'] = 211;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgeglossenhancer';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 206;
   $a_input['shortlocale'] = 206;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgeblue';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 207;
   $a_input['shortlocale'] = 207;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgegreen';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 208;
   $a_input['shortlocale'] = 208;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgered';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 209;
   $a_input['shortlocale'] = 209;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'cartridgechromaticred';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 210;
   $a_input['shortlocale'] = 210;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'maintenancekit';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 156;
   $a_input['shortlocale'] = 156;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'maintenancekitmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 193;
   $a_input['shortlocale'] = 193;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'maintenancekitused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 194;
   $a_input['shortlocale'] = 194;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'maintenancekitremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 195;
   $a_input['shortlocale'] = 195;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'transferkit';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 212;
   $a_input['shortlocale'] = 212;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'transferkitmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 199;
   $a_input['shortlocale'] = 199;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'transferkitused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 200;
   $a_input['shortlocale'] = 200;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'transferkitremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 201;
   $a_input['shortlocale'] = 201;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'fuserkit';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 202;
   $a_input['shortlocale'] = 202;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'fuserkitmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 203;
   $a_input['shortlocale'] = 203;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'fuserkitused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 204;
   $a_input['shortlocale'] = 204;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'fuserkitremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 205;
   $a_input['shortlocale'] = 205;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumblack';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 161;
   $a_input['shortlocale'] = 161;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumblackmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 178;
   $a_input['shortlocale'] = 178;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumblackused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 179;
   $a_input['shortlocale'] = 179;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumblackremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 180;
   $a_input['shortlocale'] = 180;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumcyan';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 162;
   $a_input['shortlocale'] = 162;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumcyanmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 181;
   $a_input['shortlocale'] = 181;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumcyanused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 182;
   $a_input['shortlocale'] = 182;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumcyanremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 183;
   $a_input['shortlocale'] = 183;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drummagenta';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 163;
   $a_input['shortlocale'] = 163;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drummagentamax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 184;
   $a_input['shortlocale'] = 184;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drummagentaused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 185;
   $a_input['shortlocale'] = 185;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drummagentaremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 186;
   $a_input['shortlocale'] = 186;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumyellow';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 164;
   $a_input['shortlocale'] = 164;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumyellowmax';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 187;
   $a_input['shortlocale'] = 187;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumyellowused';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 188;
   $a_input['shortlocale'] = 188;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'drumyellowremaining';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 189;
   $a_input['shortlocale'] = 189;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountertotalpages';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_total';
   $a_input['locale']      = 28;
   $a_input['shortlocale'] = 128;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterblackpages';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_n_b';
   $a_input['locale']      = 29;
   $a_input['shortlocale'] = 129;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountercolorpages';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_color';
   $a_input['locale']      = 30;
   $a_input['shortlocale'] = 130;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterrectoversopages';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_recto_verso';
   $a_input['locale']      = 54;
   $a_input['shortlocale'] = 154;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterscannedpages';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'scanned';
   $a_input['locale']      = 55;
   $a_input['shortlocale'] = 155;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountertotalpages_print';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_total_print';
   $a_input['locale']      = 423;
   $a_input['shortlocale'] = 1423;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterblackpages_print';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_n_b_print';
   $a_input['locale']      = 424;
   $a_input['shortlocale'] = 1424;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountercolorpages_print';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_color_print';
   $a_input['locale']      = 425;
   $a_input['shortlocale'] = 1425;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountertotalpages_copy';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_total_copy';
   $a_input['locale']      = 426;
   $a_input['shortlocale'] = 1426;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterblackpages_copy';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_n_b_copy';
   $a_input['locale']      = 427;
   $a_input['shortlocale'] = 1427;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountercolorpages_copy';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_color_copy';
   $a_input['locale']      = 428;
   $a_input['shortlocale'] = 1428;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecountertotalpages_fax';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_total_fax';
   $a_input['locale']      = 429;
   $a_input['shortlocale'] = 1429;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'pagecounterlargepages';
   $a_input['table']       = 'glpi_plugin_fusioninventory_printerlogs';
   $a_input['tablefield']  = 'pages_total_large';
   $a_input['locale']      = 434;
   $a_input['shortlocale'] = 1434;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'ifPhysAddress';
   $a_input['table']       = 'glpi_networkports';
   $a_input['tablefield']  = 'mac';
   $a_input['locale']      = 48;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'ifName';
   $a_input['table']       = 'glpi_networkports';
   $a_input['tablefield']  = 'name';
   $a_input['locale']      = 57;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'ifaddr';
   $a_input['table']       = 'glpi_networkports';
   $a_input['tablefield']  = 'ip';
   $a_input['locale']      = 407;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'ifType';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 97;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'ifIndex';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 416;
   $pfMapping->set($a_input);

   // ** Computer
   $a_input = [];
   $a_input['itemtype']    = 'Computer';
   $a_input['name']        = 'serial';
   $a_input['table']       = '';
   $a_input['tablefield']  = 'serial';
   $a_input['locale']      = 13;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Computer';
   $a_input['name']        = 'ifPhysAddress';
   $a_input['table']       = '';
   $a_input['tablefield']  = 'mac';
   $a_input['locale']      = 15;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Computer';
   $a_input['name']        = 'ifaddr';
   $a_input['table']       = '';
   $a_input['tablefield']  = 'ip';
   $a_input['locale']      = 407;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'paperrollinches';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 197;
   $a_input['shortlocale'] = 197;
   $pfMapping->set($a_input);

   $a_input = [];
   $a_input['itemtype']    = 'Printer';
   $a_input['name']        = 'paperrollcentimeters';
   $a_input['table']       = '';
   $a_input['tablefield']  = '';
   $a_input['locale']      = 198;
   $a_input['shortlocale'] = 198;
   $pfMapping->set($a_input);
}


/**
 * Manage the update from 2.13 to 2.20 version (very old) part migration
 *
 * @global object $DB
 * @param object $migration
 */
function update213to220_ConvertField($migration) {
   global $DB;

   // ----------------------------------------------------------------------
   //NETWORK MAPPING MAPPING
   // ----------------------------------------------------------------------
   $constantsfield = [];

   $constantsfield['reseaux > lieu'] = 'location';
   $constantsfield['networking > location'] = 'location';
   $constantsfield['Netzwerk > Standort'] = 'location';

   $constantsfield['réseaux > firmware'] = 'firmware';
   $constantsfield['networking > firmware'] = 'firmware';
   $constantsfield['Netzwerk > Firmware'] = 'firmware';

   $constantsfield['réseaux > firmware'] = 'firmware1';
   $constantsfield['networking > firmware'] = 'firmware1';
   $constantsfield['Netzwerk > Firmware'] = 'firmware1';

   $constantsfield['réseaux > firmware'] = 'firmware2';
   $constantsfield['networking > firmware'] = 'firmware2';
   $constantsfield['Netzwerk > Firmware'] = 'firmware2';

   $constantsfield['réseaux > contact'] = 'contact';
   $constantsfield['networking > contact'] = 'contact';
   $constantsfield['Netzwerk > Kontakt'] = 'contact';

   $constantsfield['réseaux > description'] = 'comments';
   $constantsfield['networking > comments'] = 'comments';
   $constantsfield['Netzwerk > Kommentar'] = 'comments';

   $constantsfield['réseaux > uptime'] = 'uptime';
   $constantsfield['networking > uptime'] = 'uptime';
   $constantsfield['Netzwerk > Uptime'] = 'uptime';

   $constantsfield['réseaux > utilisation du CPU'] = 'cpu';
   $constantsfield['networking > CPU usage'] = 'cpu';
   $constantsfield['Netzwerk > CPU Auslastung'] = 'cpu';

   $constantsfield['réseaux > CPU user'] = 'cpuuser';
   $constantsfield['networking > CPU usage (user)'] = 'cpuuser';
   $constantsfield['Netzwerk > CPU Benutzer'] = 'cpuuser';

   $constantsfield['réseaux > CPU système'] = 'cpusystem';
   $constantsfield['networking > CPU usage (system)'] = 'cpusystem';
   $constantsfield['Netzwerk > CPU System'] = 'cpusystem';

   $constantsfield['réseaux > numéro de série'] = 'serial';
   $constantsfield['networking > serial number'] = 'serial';
   $constantsfield['Netzwerk > Seriennummer'] = 'serial';

   $constantsfield['réseaux > numéro d\'inventaire'] = 'otherserial';
   $constantsfield['networking > Inventory number'] = 'otherserial';
   $constantsfield['Netzwerk > Inventarnummer'] = 'otherserial';

   $constantsfield['réseaux > nom'] = 'name';
   $constantsfield['networking > name'] = 'name';
   $constantsfield['Netzwerk > Name'] = 'name';

   $constantsfield['réseaux > mémoire totale'] = 'ram';
   $constantsfield['networking > total memory'] = 'ram';
   $constantsfield['Netzwerk > Gesamter Speicher'] = 'ram';

   $constantsfield['réseaux > mémoire libre'] = 'memory';
   $constantsfield['networking > free memory'] = 'memory';
   $constantsfield['Netzwerk > Freier Speicher'] = 'memory';

   $constantsfield['réseaux > VLAN'] = 'vtpVlanName';
   $constantsfield['networking > VLAN'] = 'vtpVlanName';
   $constantsfield['Netzwerk > VLAN'] = 'vtpVlanName';

   $constantsfield['réseaux > port > vlan'] = 'vmvlan';
   $constantsfield['networking > port > vlan'] = 'vmvlan';

   $constantsfield['réseaux > modèle'] = 'entPhysicalModelName';
   $constantsfield['networking > model'] = 'entPhysicalModelName';
   $constantsfield['Netzwerk > Modell'] = 'entPhysicalModelName';

   $constantsfield['réseaux > adresse MAC'] = 'macaddr';
   $constantsfield['networking > MAC address'] = 'macaddr';
   $constantsfield['Netzwerk > MAC Adresse'] = 'macaddr';

   $constantsfield['réseaux > Adresse CDP'] = 'cdpCacheAddress';
   $constantsfield['networking > CDP address'] = 'cdpCacheAddress';
   $constantsfield['Netzwerk > Adresse CDP'] = 'cdpCacheAddress';

   $constantsfield['réseaux > port CDP'] = 'cdpCacheDevicePort';
   $constantsfield['networking > CDP port'] = 'cdpCacheDevicePort';
   $constantsfield['Netzwerk > Port CDP'] = 'cdpCacheDevicePort';

   $constantsfield['réseaux > chassis id distant LLDP'] = 'lldpRemChassisId';
   $constantsfield['networking > remote chassis id LLDP'] = 'lldpRemChassisId';

   $constantsfield['réseaux > port distant LLDP'] = 'lldpRemPortId';
   $constantsfield['networking > remote port LLDP'] = 'lldpRemPortId';

   $constantsfield['réseaux > chassis id local LLDP'] = 'lldpLocChassisId';
   $constantsfield['networking > localchassis id LLDP'] = 'lldpLocChassisId';

   $constantsfield['réseaux > port > trunk/tagged'] = 'vlanTrunkPortDynamicStatus';
   $constantsfield['networking > port > trunk/tagged'] = 'vlanTrunkPortDynamicStatus';
   $constantsfield['Netzwerk > Port > trunk/tagged'] = 'vlanTrunkPortDynamicStatus';

   $constantsfield['trunk'] = 'vlanTrunkPortDynamicStatus';

   $constantsfield['réseaux > Adresses mac filtrées (dot1dTpFdbAddress)'] = 'dot1dTpFdbAddress';
   $constantsfield['networking > MAC address filters (dot1dTpFdbAddress)'] = 'dot1dTpFdbAddress';
   $constantsfield['Netzwerk > MAC Adressen Filter (dot1dTpFdbAddress)'] = 'dot1dTpFdbAddress';

   $constantsfield['réseaux > adresses physiques mémorisées (ipNetToMediaPhysAddress)'] =
                  'ipNetToMediaPhysAddress';
   $constantsfield['networking > Physical addresses in memory (ipNetToMediaPhysAddress)'] =
                  'ipNetToMediaPhysAddress';
   $constantsfield['Netzwerk > Physikalische Adressen im Speicher (ipNetToMediaPhysAddress)'] =
                  'ipNetToMediaPhysAddress';

   $constantsfield['réseaux > instances de ports (dot1dTpFdbPort)'] = 'dot1dTpFdbPort';
   $constantsfield['networking > Port instances (dot1dTpFdbPort)'] = 'dot1dTpFdbPort';
   $constantsfield['Netzwerk > Instanzen des Ports (dot1dTpFdbPort)'] = 'dot1dTpFdbPort';

   $constantsfield['réseaux > numéro de ports associé ID du port (dot1dBasePortIfIndex)'] =
                  'dot1dBasePortIfIndex';
   $constantsfield['networking > Port number associated with port ID (dot1dBasePortIfIndex)'] =
                  'dot1dBasePortIfIndex';
   $constantsfield['Netzwerk > Verkn&uuml;pfung der Portnummerierung mit der ID des Ports (dot1dBasePortIfIndex)'] = 'dot1dBasePortIfIndex';

   $constantsfield['réseaux > addresses IP'] = 'ipAdEntAddr';
   $constantsfield['networking > IP addresses'] = 'ipAdEntAddr';
   $constantsfield['Netzwerk > IP Adressen'] = 'ipAdEntAddr';

   $constantsfield['réseaux > portVlanIndex'] = 'PortVlanIndex';
   $constantsfield['networking > portVlanIndex'] = 'PortVlanIndex';
   $constantsfield['Netzwerk > portVlanIndex'] = 'PortVlanIndex';

   $constantsfield['réseaux > port > numéro index'] = 'ifIndex';
   $constantsfield['networking > port > index number'] = 'ifIndex';
   $constantsfield['Netzwerk > Port > Nummerischer Index'] = 'ifIndex';

   $constantsfield['réseaux > port > mtu'] = 'ifmtu';
   $constantsfield['networking > port > mtu'] = 'ifmtu';
   $constantsfield['Netzwerk > Port > MTU'] = 'ifmtu';

   $constantsfield['réseaux > port > vitesse'] = 'ifspeed';
   $constantsfield['networking > port > speed'] = 'ifspeed';
   $constantsfield['Netzwerk > Port > Geschwindigkeit'] = 'ifspeed';

   $constantsfield['réseaux > port > statut interne'] = 'ifinternalstatus';
   $constantsfield['networking > port > internal status'] = 'ifinternalstatus';
   $constantsfield['Netzwerk > Port > Interner Zustand'] = 'ifinternalstatus';

   $constantsfield['réseaux > port > Dernier changement'] = 'iflastchange';
   $constantsfield['networking > ports > Last change'] = 'iflastchange';
   $constantsfield['Netzwerk > Ports > Letzte &Auml;nderung'] = 'iflastchange';

   $constantsfield['réseaux > port > nombre d\'octets entrés'] = 'ifinoctets';
   $constantsfield['networking > port > number of bytes in'] = 'ifinoctets';
   $constantsfield['Netzwerk > Port > Anzahl eingegangene Bytes'] = 'ifinoctets';

   $constantsfield['réseaux > port > nombre d\'octets sortis'] = 'ifoutoctets';
   $constantsfield['networking > port > number of bytes out'] = 'ifoutoctets';
   $constantsfield['Netzwerk > Port > Anzahl ausgehende Bytes'] = 'ifoutoctets';

   $constantsfield['réseaux > port > nombre d\'erreurs entrées'] = 'ifinerrors';
   $constantsfield['networking > port > number of input errors'] = 'ifinerrors';
   $constantsfield['Netzwerk > Port > Anzahl Input Fehler'] = 'ifinerrors';

   $constantsfield['réseaux > port > nombre d\'erreurs sorties'] = 'ifouterrors';
   $constantsfield['networking > port > number of output errors'] = 'ifouterrors';
   $constantsfield['Netzwerk > Port > Anzahl Fehler Ausgehend'] = 'ifouterrors';

   $constantsfield['réseaux > port > statut de la connexion'] = 'ifstatus';
   $constantsfield['networking > port > connection status'] = 'ifstatus';
   $constantsfield['Netzwerk > Port > Verbingungszustand'] = 'ifstatus';

   $constantsfield['réseaux > port > adresse MAC'] = 'ifPhysAddress';
   $constantsfield['networking > port > MAC address'] = 'ifPhysAddress';
   $constantsfield['Netzwerk > Port > MAC Adresse'] = 'ifPhysAddress';

   $constantsfield['réseaux > port > nom'] = 'ifName';
   $constantsfield['networking > port > name'] = 'ifName';
   $constantsfield['Netzwerk > Port > Name'] = 'ifName';

   $constantsfield['réseaux > port > type'] = 'ifType';
   $constantsfield['networking > ports > type'] = 'ifType';
   $constantsfield['Netzwerk > Ports > Typ'] = 'ifType';

   $constantsfield['réseaux > port > description du port'] = 'ifdescr';
   $constantsfield['networking > port > port description'] = 'ifdescr';
   $constantsfield['Netzwerk > Port > Port Bezeichnung'] = 'ifdescr';

   $constantsfield['réseaux > port > type de duplex'] = 'portDuplex';
   $constantsfield['networking > port > duplex type'] = 'portDuplex';
   $constantsfield['Netzwerk > Port > Duplex Typ'] = 'portDuplex';

   $constantsfield['imprimante > modèle'] = 'model';
   $constantsfield['printer > model'] = 'model';
   $constantsfield['Drucker > Modell'] = 'model';

   $constantsfield['imprimante > fabricant'] = 'enterprise';
   $constantsfield['printer > manufacturer'] = 'enterprise';
   $constantsfield['Drucker > Hersteller'] = 'enterprise';

   $constantsfield['imprimante > numéro de série'] = 'serial';
   $constantsfield['printer > serial number'] = 'serial';
   $constantsfield['Drucker > Seriennummer'] = 'serial';

   $constantsfield['imprimante > contact'] = 'contact';
   $constantsfield['printer > contact'] = 'contact';
   $constantsfield['Drucker > Kontakt'] = 'contact';

   $constantsfield['imprimante > description'] = 'comments';
   $constantsfield['printer > comments'] = 'comments';
   $constantsfield['Drucker > Kommentar'] = 'comments';

   $constantsfield['imprimante > nom'] = 'name';
   $constantsfield['printer > name'] = 'name';
   $constantsfield['Drucker > Name'] = 'name';

   $constantsfield['imprimante > numéro d\'inventaire'] = 'otherserial';
   $constantsfield['printer > Inventory number'] = 'otherserial';
   $constantsfield['Drucker > Inventarnummer'] = 'otherserial';

   $constantsfield['imprimante > mémoire totale'] = 'memory';
   $constantsfield['printer > total memory'] = 'memory';
   $constantsfield['Drucker > Gesamter Speicher'] = 'memory';

   $constantsfield['imprimante > lieu'] = 'location';
   $constantsfield['printer > location'] = 'location';
   $constantsfield['Drucker > Standort'] = 'location';

   $constantsfield['Informations diverses regroupées'] = 'informations';
   $constantsfield['Many informations grouped'] = 'informations';
   $constantsfield['Many informations grouped'] = 'informations';

   $constantsfield['Toner Noir'] = 'tonerblack';
   $constantsfield['Black toner'] = 'tonerblack';

   $constantsfield['Toner Noir Max'] = 'tonerblackmax';
   $constantsfield['Black toner Max'] = 'tonerblackmax';

   $constantsfield['Toner Noir Utilisé'] = 'tonerblackused';

   $constantsfield['Toner Noir Restant'] = 'tonerblackremaining';

   $constantsfield['Toner Noir'] = 'tonerblack2';
   $constantsfield['Black toner'] = 'tonerblack2';

   $constantsfield['Toner Noir Max'] = 'tonerblack2max';
   $constantsfield['Black toner Max'] = 'tonerblack2max';

   $constantsfield['Toner Noir Utilisé'] = 'tonerblack2used';

   $constantsfield['Toner Noir Restant'] = 'tonerblack2remaining';

   $constantsfield['Toner Cyan'] = 'tonercyan';
   $constantsfield['Cyan toner'] = 'tonercyan';

   $constantsfield['Toner Cyan Max'] = 'tonercyanmax';
   $constantsfield['Cyan toner Max'] = 'tonercyanmax';

   $constantsfield['Toner Cyan Utilisé'] = 'tonercyanused';

   $constantsfield['Toner Cyan Restant'] = 'tonercyanremaining';

   $constantsfield['Toner Magenta'] = 'tonermagenta';
   $constantsfield['Magenta toner'] = 'tonermagenta';

   $constantsfield['Toner Magenta Max'] = 'tonermagentamax';
   $constantsfield['Magenta toner Max'] = 'tonermagentamax';

   $constantsfield['Toner Magenta Utilisé'] = 'tonermagentaused';
   $constantsfield['Magenta toner Utilisé'] = 'tonermagentaused';

   $constantsfield['Toner Magenta Restant'] = 'tonermagentaremaining';
   $constantsfield['Magenta toner Restant'] = 'tonermagentaremaining';

   $constantsfield['Toner Jaune'] = 'toneryellow';
   $constantsfield['Yellow toner'] = 'toneryellow';

   $constantsfield['Toner Jaune Max'] = 'toneryellowmax';
   $constantsfield['Yellow toner Max'] = 'toneryellowmax';

   $constantsfield['Toner Jaune Utilisé'] = 'toneryellowused';
   $constantsfield['Yellow toner Utilisé'] = 'toneryellowused';

   $constantsfield['Toner Jaune Restant'] = 'toneryellowremaining';
   $constantsfield['Yellow toner Restant'] = 'toneryellowremaining';

   $constantsfield['Bac récupérateur de déchet'] = 'wastetoner';
   $constantsfield['Waste bin'] = 'wastetoner';
   $constantsfield['Abfalleimer'] = 'wastetoner';

   $constantsfield['Bac récupérateur de déchet Max'] = 'wastetonermax';
   $constantsfield['Waste bin Max'] = 'wastetonermax';

   $constantsfield['Bac récupérateur de déchet Utilisé'] = 'wastetonerused';
   $constantsfield['Waste bin Utilisé'] = 'wastetonerused';

   $constantsfield['Bac récupérateur de déchet Restant'] = 'wastetonerremaining';
   $constantsfield['Waste bin Restant'] = 'wastetonerremaining';

   $constantsfield['Cartouche noir'] = 'cartridgeblack';
   $constantsfield['Black ink cartridge'] = 'cartridgeblack';
   $constantsfield['Schwarze Kartusche'] = 'cartridgeblack';

   $constantsfield['Cartouche noir'] = 'cartridgeblackmatte';
   $constantsfield['Black ink cartridge'] = 'cartridgeblackmatte';
   $constantsfield['Schwarze Kartusche'] = 'cartridgeblackmatte';

   $constantsfield['Cartouche noir photo'] = 'cartridgeblackphoto';
   $constantsfield['Photo black ink cartridge'] = 'cartridgeblackphoto';
   $constantsfield['Photoschwarz Kartusche'] = 'cartridgeblackphoto';

   $constantsfield['Cartouche cyan'] = 'cartridgecyan';
   $constantsfield['Cyan ink cartridge'] = 'cartridgecyan';
   $constantsfield['Cyan Kartusche'] = 'cartridgecyan';

   $constantsfield['Cartouche cyan clair'] = 'cartridgecyanlight';
   $constantsfield['Light cyan ink cartridge'] = 'cartridgecyanlight';
   $constantsfield['Leichtes Cyan Kartusche'] = 'cartridgecyanlight';

   $constantsfield['Cartouche magenta'] = 'cartridgemagenta';
   $constantsfield['Magenta ink cartridge'] = 'cartridgemagenta';
   $constantsfield['Magenta Kartusche'] = 'cartridgemagenta';

   $constantsfield['Cartouche magenta clair'] = 'cartridgemagentalight';
   $constantsfield['Light ink magenta cartridge'] = 'cartridgemagentalight';
   $constantsfield['Leichtes Magenta Kartusche'] = 'cartridgemagentalight';

   $constantsfield['Cartouche jaune'] = 'cartridgeyellow';
   $constantsfield['Yellow ink cartridge'] = 'cartridgeyellow';
   $constantsfield['Gelbe Kartusche'] = 'cartridgeyellow';

   $constantsfield['Cartouche grise'] = 'cartridgegrey';
   $constantsfield['Grey ink cartridge'] = 'cartridgegrey';
   $constantsfield['Grau Kartusche'] = 'cartridgegrey';

   $constantsfield['Cartouche grise clair'] = 'cartridgegreylight';
   $constantsfield['Light Grey ink cartridge'] = 'cartridgegreylight';
   $constantsfield['Leichtes Grau Kartusche'] = 'cartridgegreylight';

   $constantsfield['Cartouche le amplificateur de brillance'] = 'cartridgeglossenhancer';
   $constantsfield['Gloss Enhancer ink cartridge'] = 'cartridgeglossenhancer';
   $constantsfield['Gloss Enhancer Kartusche'] = 'cartridgeglossenhancer';

   $constantsfield['Cartouche bleu'] = 'cartridgeblue';
   $constantsfield['Blue ink cartridge'] = 'cartridgeblue';
   $constantsfield['Kartusche blau'] = 'cartridgeblue';

   $constantsfield['Cartouche vert'] = 'cartridgegreen';
   $constantsfield['green ink cartridge'] = 'cartridgegreen';
   $constantsfield['Kartusche grün'] = 'cartridgegreen';

   $constantsfield['Cartouche rouge'] = 'cartridgered';
   $constantsfield['Red ink cartridge'] = 'cartridgered';
   $constantsfield['Kartusche rot'] = 'cartridgered';

   $constantsfield['Cartouche rouge chromatique'] = 'cartridgechromaticred';
   $constantsfield['Chromatic red ink cartridge'] = 'cartridgechromaticred';
   $constantsfield['Kartusche chromatische rot'] = 'cartridgechromaticred';

   $constantsfield['Kit de maintenance'] = 'maintenancekit';
   $constantsfield['Maintenance kit'] = 'maintenancekit';
   $constantsfield['Wartungsmodul'] = 'maintenancekit';

   $constantsfield['Kit de maintenance Max'] = 'maintenancekitmax';
   $constantsfield['Maintenance kit Max'] = 'maintenancekitmax';

   $constantsfield['Kit de maintenance Utilisé'] = 'maintenancekitused';
   $constantsfield['Maintenance kit Used'] = 'maintenancekitused';

   $constantsfield['Kit de maintenance Restant'] = 'maintenancekitremaining';
   $constantsfield['Maintenance kit Remaining'] = 'maintenancekitremaining';

   $constantsfield['Kit de transfert'] = 'transferkit';
   $constantsfield['Transfer kit'] = 'transferkit';
   $constantsfield['Transfermodul'] = 'transferkit';

   $constantsfield['Kit de transfert Max'] = 'transferkitmax';
   $constantsfield['Transfer kit Max'] = 'transferkitmax';

   $constantsfield['Kit de transfert Utilisé'] = 'transferkitused';
   $constantsfield['Transfer kit Used'] = 'transferkitused';

   $constantsfield['Kit de transfert Restant'] = 'transferkitremaining';
   $constantsfield['Transfer kit Remaining'] = 'transferkitremaining';

   $constantsfield['Kit de fusion'] = 'fuserkit';
   $constantsfield['Fuser kit'] = 'fuserkit';
   $constantsfield['fixiereinheitmodul'] = 'fuserkit';

   $constantsfield['Kit de fusion Max'] = 'fuserkitmax';
   $constantsfield['fusion kit Max'] = 'fuserkitmax';

   $constantsfield['Kit de fusion Utilisé'] = 'fuserkitused';
   $constantsfield['Fuser kit used'] = 'fuserkitused';

   $constantsfield['Kit de fusion Restant'] = 'fuserkitremaining';
   $constantsfield['Fuser kit remaining'] = 'fuserkitremaining';

   $constantsfield['Tambour Noir'] = 'drumblack';
   $constantsfield['Black drum'] = 'drumblack';

   $constantsfield['Tambour Noir Max'] = 'drumblackmax';
   $constantsfield['Black drum Max'] = 'drumblackmax';

   $constantsfield['Tambour Noir Utilisé'] = 'drumblackused';
   $constantsfield['Black drum Utilisé'] = 'drumblackused';

   $constantsfield['Tambour Noir Restant'] = 'drumblackremaining';
   $constantsfield['Black drum Restant'] = 'drumblackremaining';

   $constantsfield['Tambour Cyan'] = 'drumcyan';
   $constantsfield['Cyan drum'] = 'drumcyan';

   $constantsfield['Tambour Cyan Max'] = 'drumcyanmax';
   $constantsfield['Cyan drum Max'] = 'drumcyanmax';

   $constantsfield['Tambour Cyan Utilisé'] = 'drumcyanused';
   $constantsfield['Cyan drum Utilisé'] = 'drumcyanused';

   $constantsfield['Tambour Cyan Restant'] = 'drumcyanremaining';
   $constantsfield['Cyan drumRestant'] = 'drumcyanremaining';

   $constantsfield['Tambour Magenta'] = 'drummagenta';
   $constantsfield['Magenta drum'] = 'drummagenta';

   $constantsfield['Tambour Magenta Max'] = 'drummagentamax';
   $constantsfield['Magenta drum Max'] = 'drummagentamax';

   $constantsfield['Tambour Magenta Utilisé'] = 'drummagentaused';
   $constantsfield['Magenta drum Utilisé'] = 'drummagentaused';

   $constantsfield['Tambour Magenta Restant'] = 'drummagentaremaining';
   $constantsfield['Magenta drum Restant'] = 'drummagentaremaining';

   $constantsfield['Tambour Jaune'] = 'drumyellow';
   $constantsfield['Yellow drum'] = 'drumyellow';

   $constantsfield['Tambour Jaune Max'] = 'drumyellowmax';
   $constantsfield['Yellow drum Max'] = 'drumyellowmax';

   $constantsfield['Tambour Jaune Utilisé'] = 'drumyellowused';
   $constantsfield['Yellow drum Utilisé'] = 'drumyellowused';

   $constantsfield['Tambour Jaune Restant'] = 'drumyellowremaining';
   $constantsfield['Yellow drum Restant'] = 'drumyellowremaining';

   $constantsfield['imprimante > compteur > nombre total de pages imprimées'] =
                  'pagecountertotalpages';
   $constantsfield['printer > meter > total number of printed pages'] = 'pagecountertotalpages';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Seiten'] = 'pagecountertotalpages';

   $constantsfield['imprimante > compteur > nombre de pages noir et blanc imprimées'] =
                  'pagecounterblackpages';
   $constantsfield['printer > meter > number of printed black and white pages'] =
                  'pagecounterblackpages';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedrucker Schwarz/Wei&szlig; Seiten'] =
                  'pagecounterblackpages';

   $constantsfield['imprimante > compteur > nombre de pages couleur imprimées'] =
                  'pagecountercolorpages';
   $constantsfield['printer > meter > number of printed color pages'] = 'pagecountercolorpages';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Farbseiten'] =
                  'pagecountercolorpages';

   $constantsfield['imprimante > compteur > nombre de pages recto/verso imprimées'] =
                  'pagecounterrectoversopages';
   $constantsfield['printer > meter > number of printed duplex pages'] =
                  'pagecounterrectoversopages';
   $constantsfield['Drucker > Messung > Anzahl der gedruckten Duplex Seiten'] =
                  'pagecounterrectoversopages';

   $constantsfield['imprimante > compteur > nombre de pages scannées'] = 'pagecounterscannedpages';
   $constantsfield['printer > meter > nomber of scanned pages'] = 'pagecounterscannedpages';
   $constantsfield['Drucker > Messung > Anzahl der gescannten Seiten'] = 'pagecounterscannedpages';

   $constantsfield['imprimante > compteur > nombre total de pages imprimées (impression)'] =
                  'pagecountertotalpages_print';
   $constantsfield['printer > meter > total number of printed pages (print mode)'] =
                  'pagecountertotalpages_print';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Seiten (Druck)'] =
                  'pagecountertotalpages_print';

   $constantsfield['imprimante > compteur > nombre de pages noir et blanc imprimées (impression)'] =
                  'pagecounterblackpages_print';
   $constantsfield['printer > meter > number of printed black and white pages (print mode)'] =
                  'pagecounterblackpages_print';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Schwarz/Wei&szlig; Seiten (Druck)']=
                  'pagecounterblackpages_print';

   $constantsfield['imprimante > compteur > nombre de pages couleur imprimées (impression)'] =
                  'pagecountercolorpages_print';
   $constantsfield['printer > meter > number of printed color pages (print mode)'] =
                  'pagecountercolorpages_print';
   $constantsfield['Drucker > Messung > Gesamtanzahl farbig gedruckter Seiten (Druck)'] =
                  'pagecountercolorpages_print';

   $constantsfield['imprimante > compteur > nombre total de pages imprimées (copie)'] =
                  'pagecountertotalpages_copy';
   $constantsfield['printer > meter > total number of printed pages (copy mode)'] =
                  'pagecountertotalpages_copy';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Seiten (Kopie)'] =
                  'pagecountertotalpages_copy';

   $constantsfield['imprimante > compteur > nombre de pages noir et blanc imprimées (copie)'] =
                  'pagecounterblackpages_copy';
   $constantsfield['printer > meter > number of printed black and white pages (copy mode)'] =
                  'pagecounterblackpages_copy';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Schwarz/Wei&szlig; Seite (Kopie)'] =
                  'pagecounterblackpages_copy';

   $constantsfield['imprimante > compteur > nombre de pages couleur imprimées (copie)'] =
                  'pagecountercolorpages_copy';
   $constantsfield['printer > meter > number of printed color pages (copy mode)'] =
                  'pagecountercolorpages_copy';
   $constantsfield['Drucker > Messung > Gesamtanzahl farbig gedruckter Seiten (Kopie)'] =
                  'pagecountercolorpages_copy';

   $constantsfield['imprimante > compteur > nombre total de pages imprimées (fax)'] =
                  'pagecountertotalpages_fax';
   $constantsfield['printer > meter > total number of printed pages (fax mode)'] =
                  'pagecountertotalpages_fax';
   $constantsfield['Drucker > Messung > Gesamtanzahl gedruckter Seiten (Fax)'] =
                  'pagecountertotalpages_fax';

   $constantsfield['imprimante > compteur > nombre total de pages larges imprimées'] =
                  'pagecounterlargepages';
   $constantsfield['printer > meter > total number of large printed pages'] =
                  'pagecounterlargepages';

   $constantsfield['imprimante > port > adresse MAC'] = 'ifPhysAddress';
   $constantsfield['printer > port > MAC address'] = 'ifPhysAddress';
   $constantsfield['Drucker > Port > MAC Adresse'] = 'ifPhysAddress';

   $constantsfield['imprimante > port > nom'] = 'ifName';
   $constantsfield['printer > port > name'] = 'ifName';
   $constantsfield['Drucker > Port > Name'] = 'ifName';

   $constantsfield['imprimante > port > adresse IP'] = 'ifaddr';
   $constantsfield['printer > port > IP address'] = 'ifaddr';
   $constantsfield['Drucker > Port > IP Adresse'] = 'ifaddr';

   $constantsfield['imprimante > port > type'] = 'ifType';
   $constantsfield['printer > port > type'] = 'ifType';
   $constantsfield['Drucker > port > Typ'] = 'ifType';

   $constantsfield['imprimante > port > numéro index'] = 'ifIndex';
   $constantsfield['printer > port > index number'] = 'ifIndex';
   $constantsfield['Drucker > Port > Indexnummer'] = 'ifIndex';

   if ($DB->tableExists("glpi_plugin_tracker_snmp_history")) {
      //echo "Converting history port ...\n";
      $i = 0;
      $nb = count($constantsfield);
         $migration->addKey("glpi_plugin_tracker_snmp_history",
                         "Field");
      $migration->addKey("glpi_plugin_tracker_snmp_history",
                         ["Field", "old_value"],
                         "Field_2");
      $migration->addKey("glpi_plugin_tracker_snmp_history",
                         ["Field", "new_value"],
                         "Field_3");
      $migration->migrationOneTable("glpi_plugin_tracker_snmp_history");

      $update = $DB->buildUpdate(
         'glpi_plugin_tracker_snmp_history', [
            'Field'  => new \QueryParam()
         ], [
            'Field'  => new \QueryParam()
         ]
      );
      $stmt = $DB->prepare($update);
      foreach ($constantsfield as $langvalue => $mappingvalue) {
         $i++;
         $stmt->bind_param(
            'ss',
            $mappingvalue,
            $langvalue
         );
         $stmt->execute();
         $migration->displayMessage("$i / $nb");
      }
      mysqli_stmt_close($stmt);
      $migration->displayMessage("$i / $nb");

      // Move connections from glpi_plugin_fusioninventory_snmp_history to
      // glpi_plugin_fusioninventory_snmp_history_connections
      //echo "Moving creation connections history\n";
      $query = "SELECT *
                FROM `glpi_plugin_tracker_snmp_history`
                WHERE `Field` = '0'
                  AND ((`old_value` NOT LIKE '%:%')
                        OR (`old_value` IS NULL))";
      $stmt = null;
      if ($result=$DB->query($query)) {
         $nb = $DB->numrows($result);
         $i = 0;
         $migration->displayMessage("$i / $nb");
         while ($data=$DB->fetchArray($result)) {
            $i++;

            // Search port from mac address
            $iterator = $DB->request([
               'FROM'   => 'glpi_networkports',
               'WHERE'  => ['mac' => $data['new_value']]
            ]);
            if (count($iterator) == 1) {
               $input = [];
               $data_port = $iterator->next();
               $input['FK_port_source'] = $data_port['id'];

               $port_iterator = $DB->request([
                  'FROM'   => 'glpi_networkports',
                  'WHERE'  => [
                     'items_id'  => $data['new_device_ID'],
                     'itemtype'  => $data['new_device_type']
                  ]
               ]);
               if (count($port_iterator) == 1) {
                  if ($stmt == null) {
                     $insert = $DB->buildInsert(
                        'glpi_plugin_fusinvsnmp_networkportconnectionlogs', [
                           'date_mod'                    => new \QueryParam(),
                           'creation'                    => new \QueryParam(),
                           'networkports_id_source'      => new \QueryParam(),
                           'networkports_id_destination' => new \QueryParam()
                        ]
                     );
                     $stmt = $DB->prepare($insert);
                  }
                  $data_port2 = $port_iterator->next();
                  $input['FK_port_destination'] = $data_port2['id'];

                  $input['date'] = $data['date_mod'];
                  $input['creation'] = 1;
                  $input['process_number'] = $data['FK_process'];

                  $stmt->bind_param(
                     'ssss',
                     $input['date'],
                     $input['creation'],
                     $input['FK_port_source'],
                     $input['FK_port_destination']
                  );
                  $stmt->execute();
               }
            }

            $DB->delete(
               'glpi_plugin_tracker_snmp_history', [
                  'id'  => $data['ID']
               ]
            );
            if (preg_match("/000$/", $i)) {
               $migration->displayMessage("$i / $nb");
            }
         }
         $migration->displayMessage("$i / $nb");
      }
      if ($stmt !== null) {
         mysqli_stmt_close($stmt);
      }

      //echo "Moving deleted connections history\n";
      $query = "SELECT *
                FROM `glpi_plugin_tracker_snmp_history`
                WHERE `Field` = '0'
                  AND ((`new_value` NOT LIKE '%:%')
                        OR (`new_value` IS NULL))";

      $stmt = null;
      if ($result=$DB->query($query)) {
         $nb = $DB->numrows($result);
         $i = 0;
         $migration->displayMessage("$i / $nb");
         while ($data=$DB->fetchArray($result)) {
            $i++;

            // Search port from mac address
            $iterator = $DB->request([
               'FROM'   => 'glpi_networkports',
               'WHERE'  => ['mac' => $data['old_value']]
            ]);
            if (count($iterator) == 1) {
               $input = [];
               $data_port = $iterator->next();
               $input['FK_port_source'] = $data_port['id'];

               $port_iterator = $DB->request([
                  'FROM'   => 'glpi_networkports',
                  'WHERE'  => [
                     'items_id'  => $data['old_device_ID'],
                     'itemtype'  => $data['old_device_type']
                  ]
               ]);
               if (count($port_iterator) == 1) {
                  $data_port2 = $port_iterator->next();
                  $input['FK_port_destination'] = $data_port2['id'];

                  $input['date'] = $data['date_mod'];
                  $input['creation'] = 1;
                  $input['process_number'] = $data['FK_process'];
                  if ($input['FK_port_source'] != $input['FK_port_destination']) {
                     if ($stmt == null) {
                        $insert = $DB->buildInsert(
                           'glpi_plugin_fusinvsnmp_networkportconnectionlogs', [
                              'date_mod'                    => new \QueryParam(),
                              'creation'                    => new \QueryParam(),
                              'networkports_id_source'      => new \QueryParam(),
                              'networkports_id_destination' => new \QueryParam()
                           ]
                        );
                        $stmt = $DB->prepare($insert);
                     }

                     $stmt->bind_param(
                        'ssss',
                        $input['date'],
                        $input['creation'],
                        $input['FK_port_source'],
                        $input['FK_port_destination']
                     );
                     $stmt->execute();
                  }
               }
            }

            $DB->delete(
               'glpi_plugin_tracker_snmp_history', [
                  'ID' => $data['ID']
               ]
            );
            if (preg_match("/000$/", $i)) {
               $migration->displayMessage("$i / $nb");
            }
         }
         $migration->displayMessage("$i / $nb");
      }
      if ($stmt !== null) {
         mysqli_stmt_close($stmt);
      }
   }
}


/**
 * Manage the migration of MySQL tables / fields
 *
 * @global object $DB
 * @param object $migration
 * @param array $a_table
 */
function migrateTablesFusionInventory($migration, $a_table) {
   global $DB;

   foreach ($a_table['oldname'] as $oldtable) {
      $migration->renameTable($oldtable, $a_table['name']);
   }

   if (!$DB->tableExists($a_table['name'])) {

      if (strstr($a_table['name'], 'glpi_plugin_fusioninventory_dblock')) {
         $query = "CREATE TABLE `".$a_table['name']."` (
                        `value` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`value`)
                     ) ENGINE=MEMORY  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
      } else {
         $query = "CREATE TABLE `".$a_table['name']."` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        PRIMARY KEY (`id`)
                     ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1";
      }

      $DB->query($query);
   }

   foreach ($a_table['renamefields'] as $old=>$new) {
      $migration->changeField($a_table['name'],
                              $old,
                              $new,
                              $a_table['fields'][$new]['type'],
                              ['value' => $a_table['fields'][$new]['value'],
                                    'update'=> true]);
   }

   foreach ($a_table['oldkeys'] as $field) {
      $migration->dropKey($a_table['name'],
                          $field);
   }
   $migration->migrationOneTable($a_table['name']);

   foreach ($a_table['oldfields'] as $field) {
      $migration->dropField($a_table['name'],
                            $field);
   }
   $migration->migrationOneTable($a_table['name']);

   foreach ($a_table['fields'] as $field=>$data) {
      $migration->changeField($a_table['name'],
                              $field,
                              $field,
                              $data['type'],
                              ['value' => $data['value']]);
   }
   $migration->migrationOneTable($a_table['name']);

   foreach ($a_table['fields'] as $field=>$data) {
      $migration->addField($a_table['name'],
                           $field,
                           $data['type'],
                           ['value' => $data['value']]);
   }
   $migration->migrationOneTable($a_table['name']);

   foreach ($a_table['keys'] as $data) {
      $migration->addKey($a_table['name'],
                         $data['field'],
                         $data['name'],
                         $data['type']);
   }
   $migration->migrationOneTable($a_table['name']);

   $DB->listFields($a_table['name'], false);
}


/**
 * Migrate tables from plugin fusinvdeploy to fusioninventory
 *    all datas in exploded tables are merged and stored in json in order table
 *
 * @global object $DB
 * @param  object $migration
 */
function migrateTablesFromFusinvDeploy ($migration) {
   global $DB;

   if ($DB->tableExists("glpi_plugin_fusioninventory_deployorders")
         && $DB->tableExists("glpi_plugin_fusinvdeploy_checks")
         && $DB->tableExists("glpi_plugin_fusinvdeploy_files")
         && $DB->tableExists("glpi_plugin_fusinvdeploy_actions")) {

      //add json field in deploy order table to store datas from old misc tables
      $field_created = $migration->addField("glpi_plugin_fusioninventory_deployorders",
                                    "json",
                                    "longtext DEFAULT NULL");
      $migration->migrationOneTable("glpi_plugin_fusioninventory_deployorders");

      $final_datas = [];

      //== glpi_plugin_fusioninventory_deployorders ==
      $o_iterator = $DB->request(['FROM' => 'glpi_plugin_fusioninventory_deployorders']);
      while ($o_datas = $o_iterator->next()) {
         $order_id = $o_datas['id'];

         $o_line = [];
         $of_line = [];

         $o_line['checks'] = [];
         $o_line['actions'] = [];
         $o_line['associatedFiles'] = [];

         //=== Checks ===

         if ($DB->tableExists("glpi_plugin_fusinvdeploy_checks")) {
            $c_query = "SELECT type, path, value, 'error' as `return`
               FROM glpi_plugin_fusinvdeploy_checks
               WHERE plugin_fusinvdeploy_orders_id = $order_id
               ORDER BY ranking ASC";
            $c_res = $DB->query($c_query);
            $c_i = 0;
            while ($c_datas = $DB->fetchAssoc($c_res)) {
               foreach ($c_datas as $c_key => $c_value) {
                  //specific case for filesytem sizes, convert to bytes
                  if (!empty($c_value)
                          && is_numeric($c_value)
                          && $c_datas['type'] !== 'freespaceGreater') {
                     $c_value = $c_value * 1024 * 1024;
                  }

                  //construct job check entry
                  $o_line['checks'][$c_i][$c_key] = $c_value;
               }
                $c_i++;
            }
         }

         $files_list = [];
         //=== Files ===
         if ($DB->tableExists("glpi_plugin_fusinvdeploy_files")) {
            $f_query =
               "SELECT id, name, is_p2p as p2p, filesize, mimetype, ".
               "p2p_retention_days as `p2p-retention-duration`, uncompress, sha512 ".
               "FROM glpi_plugin_fusinvdeploy_files ".
               "WHERE plugin_fusinvdeploy_orders_id = $order_id";
            $f_res = $DB->query($f_query);
            while ($f_datas = $DB->fetchAssoc($f_res)) {

               //jump to next entry if sha512 is empty
               // This kind of entries could happen sometimes on upload errors
               if (empty($f_datas['sha512'])) {
                  continue;
               }

               //construct job file entry
               $o_line['associatedFiles'][] = $f_datas['sha512'];

               foreach ($f_datas as $f_key => $f_value) {

                  //we don't store the sha512 field in json
                  if ($f_key == "sha512"
                     || $f_key == "id"
                     || $f_key == "filesize"
                     || $f_key == "mimetype") {
                     continue;
                  }

                  //construct order file entry
                  $of_line[$f_datas['sha512']][$f_key] = $f_value;
               }

               if (!in_array($f_datas['sha512'], $files_list)) {
                  $files_list[] = $f_datas['sha512'];
               }

            }
         }

         //=== Actions ===
         $cmdStatus['RETURNCODE_OK'] = 'okCode';
         $cmdStatus['RETURNCODE_KO'] = 'errorCode';
         $cmdStatus['REGEX_OK'] = 'okPattern';
         $cmdStatus['REGEX_KO'] = 'errorPattern';

         if ($DB->tableExists("glpi_plugin_fusinvdeploy_actions")) {
            $a_query = "SELECT *
               FROM glpi_plugin_fusinvdeploy_actions
               WHERE plugin_fusinvdeploy_orders_id = $order_id
               ORDER BY ranking ASC";
            $a_res = $DB->query($a_query);
            $a_i = 0;
            while ($a_datas = $DB->fetchAssoc($a_res)) {

               //get type
               $type = strtolower(str_replace("PluginFusinvdeployAction_", "", $a_datas['itemtype']));

               //specific case for command type
               $type = str_replace("command", "cmd", $type);

               //table for action itemtype
               $a_table = getTableForItemType($a_datas['itemtype']);

               //get table fields
               $at_query = "SELECT *
                  FROM $a_table
                  WHERE id = ".$a_datas['items_id'];
               $at_res = $DB->query($at_query);
               while ($at_datas = $DB->fetchAssoc($at_res)) {
                  foreach ($at_datas as $at_key => $at_value) {
                     //we don't store the id field of action itemtype table in json
                     if ($at_key == "id") {
                        continue;
                     }

                     //specific case for 'path' field
                     if ($at_key == "path") {
                        $o_line['actions'][$a_i][$type]['list'][] = $at_value;
                     } else {
                        //construct job actions entry
                        $o_line['actions'][$a_i][$type][$at_key] = $at_value;
                     }
                  }

                  //specific case for commands : we must add status and env vars
                  if ($a_datas['itemtype'] === "PluginFusinvdeployAction_Command") {
                     $ret_cmd_query = "SELECT type, value
                        FROM glpi_plugin_fusinvdeploy_actions_commandstatus
                        WHERE plugin_fusinvdeploy_commands_id = ".$at_datas['id'];
                     $ret_cmd_res = $DB->query($ret_cmd_query);
                     while ($res_cmd_datas = $DB->fetchAssoc($ret_cmd_res)) {
                        // Skip empty retchecks type:
                        // This surely means they have been drop at some point but entry has not been
                        // removed from database.
                        if (!empty($res_cmd_datas['type'])) {
                           //construct command status array entry
                           $o_line['actions'][$a_i][$type]['retChecks'][] = [
                              'type'  => $cmdStatus[$res_cmd_datas['type']],
                              'values' => [$res_cmd_datas['value']]
                           ];
                        }
                     }
                  }
               }
               $a_i++;
            }
         }
         $final_datas[$order_id]['jobs'] = $o_line;
         $final_datas[$order_id]['associatedFiles'] = $of_line;
         unset($o_line);
         unset($of_line);
      }
      $options = 0;
      $options = $options | JSON_UNESCAPED_SLASHES;

      //store json in order table
      if (count($final_datas)) {
         $update = $DB->buildUpdate(
            'glpi_plugin_fusioninventory_deployorders', [
               'json'   => new \QueryParam()
            ], [
               'id'     => new \QueryParam()
            ]
         );
         $stmt = $DB->prepare($update);
         foreach ($final_datas as $order_id => $data) {
            $json = $DB->escape(json_encode($data, $options));

            $stmt->bind_param(
               'ss',
               $json,
               $order_id
            );
         }
      }
   }

   //=== Fileparts ===
   if ($DB->tableExists('glpi_plugin_fusinvdeploy_fileparts')
           && $DB->tableExists('glpi_plugin_fusinvdeploy_files')) {
      $files_list = $DB->request('glpi_plugin_fusinvdeploy_files');
      // multipart file datas
      foreach ($files_list as $file) {
         $sha = $file['sha512'];
         if (empty($sha)) {
            continue;
         }
         $shortsha = substr($sha, 0, 6);
         $fp_query = "SELECT  fp.`sha512` as filepart_hash, ".
            "        f.`sha512`  as file_hash      ".
            "FROM `glpi_plugin_fusinvdeploy_files` as f ".
            "INNER JOIN `glpi_plugin_fusinvdeploy_fileparts` as fp ".
            "ON   f.`id` = fp.`plugin_fusinvdeploy_files_id` ".
            "     AND f.`shortsha512` = '{$shortsha}' ".
            "GROUP BY fp.`sha512` ".
            "ORDER BY fp.`id`";

         $fp_res = $DB->query($fp_query);
         if ($DB->numrows($fp_res) > 0) {
            //print("writing file : " . GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/manifests/{$sha}" . "\n");
            $fhandle = fopen(
               GLPI_PLUGIN_DOC_DIR."/fusioninventory/files/manifests/{$sha}",
               'w+'
            );
            while ($fp_datas = $DB->fetchAssoc($fp_res)) {
               if ($fp_datas['file_hash'] === $sha) {
                  fwrite($fhandle, $fp_datas['filepart_hash']."\n");
               }
            }
            fclose($fhandle);
         }
      }
   }

   //migrate fusinvdeploy_files to fusioninventory_deployfiles
   if ($DB->tableExists("glpi_plugin_fusinvdeploy_files")) {
      $DB->query("TRUNCATE TABLE `glpi_plugin_fusioninventory_deployfiles`");
      if ($DB->fieldExists("glpi_plugin_fusinvdeploy_files", "filesize")) {
         $f_query = implode(
            " \n",
               [
               "SELECT  files.`id`, files.`name`,",
               "        files.`filesize`, files.`mimetype`,",
               "        files.`sha512`, files.`shortsha512`,",
               "        files.`create_date`,",
               "        files.`entities_id`, files.`is_recursive`",
               "FROM glpi_plugin_fusinvdeploy_files as files",
               "WHERE",
               "  files.`shortsha512` != \"\""
               ]
         );
         $f_res = $DB->query($f_query);
         while ($f_datas = $DB->fetchAssoc($f_res)) {
            $entry = [
               "id"        => $f_datas["id"],
               "name"      => $f_datas["name"],
               "filesize"  => $f_datas["filesize"],
               "mimetype"  => $f_datas["mimetype"],
               "shortsha512"  => $f_datas["shortsha512"],
               "sha512"  => $f_datas["sha512"],
               "comments"  => "",
               "date_mod"  => $f_datas["create_date"],
               "entities_id"  => $f_datas["entities_id"],
               "is_recursive"  => $f_datas["is_recursive"],
            ];
            $migration->displayMessage("\n");
            // Check if file exists
            $i_DeployFile = new PluginFusioninventoryDeployFile();
            $migration->displayMessage(
               "migrating file ". $entry['name'] .
               " sha:" . $entry['sha512'] .
               "\n"
            );
            if ($i_DeployFile->checkPresenceManifest($entry['sha512'])) {
               $migration->displayMessage(
                  "manifest exists" .
                  "\n"
               );
               $migration->insertInTable(
                  "glpi_plugin_fusioninventory_deployfiles", $entry
               );
            }
         }
      }
   }

   /**
    * JSON orders fixer:
    *    This piece of code makes sure that JSON orders in database are valid and will fix it
    *    otherwise.
    */

   $packages = $DB->request('glpi_plugin_fusioninventory_deploypackages');
   foreach ($packages as $order_config) {
      $json_order = json_decode($order_config['json']);
      //print("deployorders fixer : actual order structure for ID ".$order_config['id']."\n" . print_r($json_order,true) ."\n");

      // Checks for /jobs json property
      if (!isset($json_order->jobs) || !is_object($json_order->jobs)) {
         //print("deployorders fixer : create missing required 'jobs' property\n");
         $json_order->jobs = new stdClass();
      }

      if (!isset($json_order->jobs->checks)) {
         //print("deployorders fixer : create missing required '/jobs/checks' array property\n");
         $json_order->jobs->checks = [];
      }
      if (!isset($json_order->jobs->actions)) {
         //print("deployorders fixer : create missing required '/jobs/actions' array property\n");
         $json_order->jobs->actions = [];
      }
      if (!isset($json_order->jobs->associatedFiles)) {
         //print("deployorders fixer : create missing required '/jobs/associatedFiles' array property\n");
         $json_order->jobs->associatedFiles = [];
      }

      // Checks for /associatedFiles json property
      if (!isset($json_order->associatedFiles) || !is_object($json_order->associatedFiles)) {
         //print("deployorders fixer : create missing required 'associatedFiles' property\n");
         $json_order->associatedFiles = new stdClass();
      }
      //print(
         //"deployorders fixer : final order structure for ID ".$order_config['id']."\n" .
      //   json_encode($json_order,JSON_PRETTY_PRINT) ."\n"
      //);
      $pfDeployPackageItem = new PluginFusioninventoryDeployPackageItem();
      $pfDeployPackageItem->updateOrderJson($order_config['id'], $json_order);
   }

   /**
    * Drop unused tables
    */
   $old_deploy_tables = [
      'glpi_plugin_fusinvdeploy_actions',
      'glpi_plugin_fusinvdeploy_actions_commandenvvariables',
      'glpi_plugin_fusinvdeploy_actions_commands',
      'glpi_plugin_fusinvdeploy_actions_commandstatus',
      'glpi_plugin_fusinvdeploy_actions_copies',
      'glpi_plugin_fusinvdeploy_actions_deletes',
      'glpi_plugin_fusinvdeploy_actions_messages',
      'glpi_plugin_fusinvdeploy_actions_mkdirs',
      'glpi_plugin_fusinvdeploy_actions_moves',
      'glpi_plugin_fusinvdeploy_checks',
      'glpi_plugin_fusinvdeploy_fileparts',
      'glpi_plugin_fusinvdeploy_files',
      'glpi_plugin_fusinvdeploy_files_mirrors',
      'glpi_plugin_fusioninventory_inventorycomputerstorages',
      'glpi_plugin_fusioninventory_inventorycomputerstoragetypes',
      'glpi_plugin_fusioninventory_inventorycomputerstorages_storages'
   ];
   foreach ($old_deploy_tables as $table) {
      $migration->dropTable($table);
   }

   //drop unused views
   $old_deploy_views = [
      'glpi_plugin_fusinvdeploy_taskjobs',
      'glpi_plugin_fusinvdeploy_tasks'
   ];
   foreach ($old_deploy_views as $view) {
      $DB->query("DROP VIEW IF EXISTS $view");
   }
}
