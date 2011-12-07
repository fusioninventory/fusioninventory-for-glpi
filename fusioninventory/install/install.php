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

function pluginFusioninventoryInstall($version, $migration='') {
   global $DB,$LANG,$CFG_GLPI;

   if ($migration == '') {
      $migration = new Migration($version);
   }
   
   $migration->displayMessage("Installation of plugin FusionInventory");
   
   /*
    * Load classes
    */
      if (!class_exists('PluginFusioninventoryProfile')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/profile.class.php");
      }
      if (!class_exists('PluginFusioninventoryAgentmodule')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/agentmodule.class.php");
      }
      if (!class_exists('PluginFusioninventoryStaticmisc')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/staticmisc.class.php");
      }
      if (!class_exists('PluginFusioninventorySetup')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/setup.class.php");
      }
      if (!class_exists('PluginFusioninventoryUnknownDevice')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/unknowndevice.class.php");
      }
      if (!class_exists('PluginFusioninventoryInventoryRuleImportCollection')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/inventoryruleimportcollection.class.php");
      }
      if (!class_exists('PluginFusioninventoryInventoryRuleImport')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/inventoryruleimport.class.php");
      }
      if (!class_exists('PluginFusioninventoryModule')) { // if plugin is unactive
         include(GLPI_ROOT . "/plugins/fusioninventory/inc/module.class.php");
      }
      
   // Get informations of plugin

   /*
    * Clean if Fusion / Tracker has been installed and uninstalled (not clean correctly)
    */
      $migration->displayMessage("Clean data from old installation of the plugin");
      $sql = "DELETE FROM `glpi_displaypreferences`
         WHERE `itemtype`='5150'";
      $DB->query($sql);
      $sql = "DELETE FROM `glpi_displaypreferences`
         WHERE `itemtype`='5151'";
      $DB->query($sql);
      $sql = "DELETE FROM `glpi_displaypreferences`
         WHERE `itemtype`='5152'";
      $DB->query($sql);
      $sql = "DELETE FROM `glpi_displaypreferences`
         WHERE `itemtype`='5153'";
      $DB->query($sql);
      $sql = "DELETE FROM `glpi_displaypreferences`
         WHERE `itemtype`='5156'";
      $DB->query($sql);
      $sql = "DELETE FROM `glpi_displaypreferences`
         WHERE `itemtype`='5157'";
      $DB->query($sql);
      $sql = "DELETE FROM `glpi_displaypreferences`
         WHERE `itemtype`='5158'";
      $DB->query($sql);
      $sql = "DELETE FROM `glpi_displaypreferences`
         WHERE `itemtype`='5159'";
      $DB->query($sql);
      $sql = "DELETE FROM `glpi_displaypreferences`
         WHERE `itemtype`='5161'";
      $DB->query($sql);
      $sql = "DELETE FROM `glpi_displaypreferences`
         WHERE `itemtype`='5165'";
      $DB->query($sql);
      $sql = "DELETE FROM `glpi_displaypreferences`
         WHERE `itemtype`='5166'";
      $DB->query($sql);
      $sql = "DELETE FROM `glpi_displaypreferences`
         WHERE `itemtype`='5167'";
      $DB->query($sql);
      $sql = "DELETE FROM `glpi_displaypreferences`
         WHERE `itemtype`='5168'";
      $DB->query($sql);
      // Purge network ports have itemtype 5153
      $networkPort = new NetworkPort();
      $sql = "SELECT * FROM `glpi_networkports`
         WHERE `itemtype`='5153'";
      $result=$DB->query($sql);
      while ($data=$DB->fetch_array($result)) {
         $networkPort->delete(array('id'=>$data['id']), 1);
      }


   /*
    * Remove old rules
    */
      $migration->displayMessage("Clean rules from old installation of the plugin");
      $Rule = new Rule();
      $a_rules = $Rule->find("`sub_type`='PluginFusioninventoryInventoryRuleImport'");
      foreach ($a_rules as $data) {
         $Rule->delete($data);
      }
      $a_rules = $Rule->find("`sub_type`='PluginFusinvinventoryRuleEntity'");
      foreach ($a_rules as $data) {
         $Rule->delete($data);
      }



   /*
    * Create DB structure
    */
      $migration->displayMessage("Creation tables in database");   
      $DB_file = GLPI_ROOT ."/plugins/fusioninventory/install/mysql/plugin_fusioninventory-"
                 .$version."-empty.sql";
      if (!$DB->runFile($DB_file)) {
         $migration->displayMessage("Error on creation tables in database");
      }
      if (!$DB->runFile(GLPI_ROOT ."/plugins/fusioninventory/install/mysql/usbid.sql")) {
         $migration->displayMessage("Error on creation table usbid in database");
      }
      if (!$DB->runFile(GLPI_ROOT ."/plugins/fusioninventory/install/mysql/pciid.sql")) {
         $migration->displayMessage("Error on creation table pciid in database");
      }
   
   /*
    * Creation of folders
    */
      $migration->displayMessage("Creation of folders");
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/tmp');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml/computer')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml/computer');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml/printer')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml/printer');
      }
      if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml/networkequipment')) {
         mkdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/xml/networkequipment');
      }
      
      
   /*
    * Manage profiles
    */
      $migration->displayMessage("Initialize profiles");
      $plugin = new Plugin();
      $data = $plugin->find("`name` = 'FusionInventory'");
      $fields = current($data);
      $plugins_id = $fields['id'];
      PluginFusioninventoryProfile::initProfile('fusioninventory', $plugins_id);

   
   
   
   /*
    * bug of purge network port when purge unknown devices, so we clean
    */
      $sql = "SELECT `glpi_networkports`.`id` as nid FROM `glpi_networkports`
         LEFT JOIN `glpi_plugin_fusioninventory_unknowndevices`
            ON `glpi_plugin_fusioninventory_unknowndevices`.`id` = `glpi_networkports`.`items_id`
         WHERE `itemtype`='PluginFusioninventoryUnknownDevice'
            AND `glpi_plugin_fusioninventory_unknowndevices`.`id` IS NULL ";
      $result=$DB->query($sql);
      while ($data=$DB->fetch_array($result)) {
         $networkPort->delete(array('id'=>$data['nid']), 1);
      }

      
   /*
    * Add config
    */
      $migration->displayMessage("Initialize configuration");
      $PluginFusioninventoryConfig = new PluginFusioninventoryConfig();
      $PluginFusioninventoryConfig->initConfigModule();
   
   
   

   /*
    * Register Agent TASKS
    */
      $migration->displayMessage("Initialize agent TASKS");
      PluginFusioninventoryProfile::changeProfile($plugins_id);
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "WAKEONLAN";
      $input['is_active']  = 0;
      $input['exceptions'] = exportArrayToDB(array());
      $PluginFusioninventoryAgentmodule->add($input);

      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
      $input = array();
      $input['plugins_id'] = $plugins_id;
      $input['modulename'] = "INVENTORY";
      $input['is_active']  = 1;
      $input['exceptions'] = exportArrayToDB(array());
      $input['url']        = '';
      $PluginFusioninventoryAgentmodule->add($input);

      $input['modulename'] = "ESX";
      $input['is_active']  = 0;
      $url= '';
      if (isset($_SERVER['HTTP_REFERER'])) {
         $url = $_SERVER['HTTP_REFERER'];
      }
      $input['url'] = PluginFusioninventoryCommunicationRest::getDefaultRestURL($_SERVER['HTTP_REFERER'], 
                                                                                 'fusinvinventory', 
                                                                                 'esx');
      $PluginFusioninventoryAgentmodule->add($input);
   
   
   
   /*
    * Add cron task
    */
      $migration->displayMessage("Initialize cron task");
      CronTask::Register('PluginFusioninventoryTaskjob', 'taskscheduler', '60', 
                         array('mode' => 2, 'allowmode' => 3, 'logs_lifetime'=> 30));
      Crontask::Register('PluginFusioninventoryTaskjobstatus', 'cleantaskjob', (3600 * 24), 
                         array('mode' => 2, 'allowmode' => 3, 'logs_lifetime' => 30));


      
   /*
    * Create rules
    */      
      $migration->displayMessage("Create rules");
      $PluginFusioninventorySetup = new PluginFusioninventorySetup();
      $PluginFusioninventorySetup->initRules();
      
      
      
   /*
    *  Import OCS locks
    */
      $migration->displayMessage("Import OCS locks if exists");
      include_once GLPI_ROOT . "/plugins/fusioninventory/inc/lock.class.php";
      include_once GLPI_ROOT . "/plugins/fusioninventory/inc/inventorycomputerlib.class.php";
      include_once GLPI_ROOT . "/plugins/fusioninventory/inc/inventorycomputerlibhook.class.php";
      $PluginFusioninventoryLock = new PluginFusioninventoryLock();
      $PluginFusioninventoryLock->importFromOcs();
      
}

?>