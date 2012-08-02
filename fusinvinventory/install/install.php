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
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

function pluginFusinvinventoryInstall($version, $migration='') {
   global $DB;

   if ($migration == '') {
      $migration = new Migration($version);
   }
   
   // Get informations of plugin


   // Installation
   // Add new module in plugin_fusioninventory (core)
   $a_plugin = plugin_version_fusinvinventory();
   // Create database
   $empty_sql = "plugin_fusinvinventory-".$a_plugin['version']."-empty.sql";
   foreach (array($empty_sql, 'usbid.sql', 'pciid.sql') as $sql) {
      //Add tables for pciids
      $DB_file = GLPI_ROOT ."/plugins/fusinvinventory/install/mysql/$sql";
      $DBf_handle = fopen($DB_file, "rt");
      $sql_query = fread($DBf_handle, filesize($DB_file));
      fclose($DBf_handle);
      foreach ( explode(";\n", "$sql_query") as $sql_line) {
         if (Toolbox::get_magic_quotes_runtime()) $sql_line=Toolbox::stripslashes_deep($sql_line);
         if (!empty($sql_line)) {
            $DB->query($sql_line)/* or die($DB->error())*/;
         }
      }
   }

   // Create folder in GLPI_PLUGIN_DOC_DIR
   if (!is_dir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'])) {
      mkdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname']);
   }

   include_once (GLPI_ROOT . "/plugins/fusioninventory/inc/staticmisc.class.php");
   include_once (GLPI_ROOT . "/plugins/fusinvinventory/inc/staticmisc.class.php");
   $plugins_id = PluginFusioninventoryModule::getModuleId($a_plugin['shortname']);
   PluginFusioninventoryProfile::initProfile($a_plugin['shortname'], $plugins_id);
   PluginFusioninventoryProfile::changeProfile($plugins_id);

   $pfAgentmodule = new PluginFusioninventoryAgentmodule;
   $input = array();
   $input['plugins_id'] = $plugins_id;
   $input['modulename'] = "INVENTORY";
   $input['is_active']  = 1;
   $input['exceptions'] = exportArrayToDB(array());
   $input['url']        = '';
   $pfAgentmodule->add($input);

   $input['modulename'] = "ESX";
   $input['is_active']  = 0;
   $url= '';
   if (isset($_SERVER['HTTP_REFERER'])) {
      $url = $_SERVER['HTTP_REFERER'];
   }
   $input['url'] = '';
   $pfAgentmodule->add($input);

    include(GLPI_ROOT . "/plugins/fusinvinventory/inc/config.class.php");
   // Create configuration
   $pfConfig = new PluginFusinvinventoryConfig();
   $pfConfig->initConfigModule();

   // Récupérer la config des entités des regles OCS
   if (!class_exists('PluginFusinvinventoryRuleEntityCollection')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusinvinventory/inc/ruleentitycollection.class.php");
   }
   if (!class_exists('PluginFusinvinventoryRuleEntity')) { // if plugin is unactive
      include(GLPI_ROOT . "/plugins/fusinvinventory/inc/ruleentity.class.php");
   }
   $Rule = new Rule();
   $RuleCriteria = new RuleCriteria();
   $RuleAction = new RuleAction();

   $a_rules = $Rule->find("`sub_type`='RuleOcs'", "`ranking`");
   foreach($a_rules as $data) {
      $rulecollection = new PluginFusinvinventoryRuleEntityCollection();
      $input = $data;
      unset($input['id']);
      $input['sub_type'] = 'PluginFusinvinventoryRuleEntity';
      $data['comment'] = Toolbox::addslashes_deep($data['comment']);
      $rule_id = $rulecollection->add($input);

      // Add criteria
      $rule = $rulecollection->getRuleClass();
      $rulecriteria = new RuleCriteria(get_class($rule));
      $a_criteria = $RuleCriteria->find("`rules_id`='".$data['id']."'");
      foreach ($a_criteria as $datacrit) {
         $input = $datacrit;
         unset($input['id']);
         switch ($input['criteria']) {

               case 'IPADDRESS':
                  $input['criteria'] = 'ip';
                  break;

               case 'TAG':
                  $input['criteria'] = 'tag';
                  break;

               case 'DOMAIN':
                  $input['criteria'] = 'domain';
                  break;

               case 'IPSUBNET':
                  $input['criteria'] = 'subnet';
                  break;

               case 'SSN':
                  $input['criteria'] = 'serial';
                  break;

               case 'MACHINE_NAME':
                  $input['criteria'] = 'name';
                  break;
         }

         $input['rules_id'] = $rule_id;
         if (($input['criteria'] != 'OCS_SERVER')
               AND ($input['criteria'] != 'DESCRIPTION')){
            $rulecriteria->add($input);
         }
      }

      // Add action
      $ruleaction = new RuleAction(get_class($rule));
      $a_rules = $RuleAction->find("`rules_id`='".$data['id']."'");
      foreach ($a_rules as $dataaction) {
         $input = $dataaction;
         unset($input['id']);
         if ($input['field'] == '_ignore_ocs_import') {
            $input['field'] = "_ignore_import";
         }
         $input['rules_id'] = $rule_id;
         $ruleaction->add($input);
      }
   }

   // Import OCS locks
   include_once GLPI_ROOT . "/plugins/fusinvinventory/inc/lock.class.php";
   include_once GLPI_ROOT . "/plugins/fusinvinventory/inc/lib.class.php";
   include_once GLPI_ROOT . "/plugins/fusinvinventory/inc/libhook.class.php";
   $pfLock = new PluginFusinvinventoryLock();
   $pfLock->importFromOcs();
}


function pluginFusinvinventoryUninstall() {
   global $DB;

   // Get informations of plugin
   $a_plugin = plugin_version_fusinvinventory();

   $pfSetup = new PluginFusioninventorySetup();

   if (file_exists(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname'])) {
      $pfSetup->rrmdir(GLPI_PLUGIN_DOC_DIR.'/'.$a_plugin['shortname']);
   }
   // Delete files of lib
   if (file_exists(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/DataFilter')) {
      $pfSetup->rrmdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/DataFilter');
   }
   if (file_exists(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/criterias')) {
      $pfSetup->rrmdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/criterias');
   }
   if (file_exists(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/machines')) {
      $pfSetup->rrmdir(GLPI_PLUGIN_DOC_DIR.'/fusioninventory/machines');
   }

   PluginFusioninventoryTask::cleanTasksbyMethod('inventory');

   // Delete config
   $pfConfig = new PluginFusioninventoryConfig;
   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');
   $pfConfig->cleanConfig($plugins_id);


   PluginFusioninventoryProfile::cleanProfile($a_plugin['shortname']);

   $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvinventory');
   $pfAgentmodule = new PluginFusioninventoryAgentmodule;
   $pfAgentmodule->deleteModule($plugins_id);

   // Delete rules
   $Rule = new Rule();
   $a_rules = $Rule->find("`sub_type`='PluginFusinvinventoryRuleEntity'");
   foreach ($a_rules as $data) {
      $Rule->delete($data);
   }

   // Delete display preferences
   $query="DELETE FROM `glpi_displaypreferences`
        WHERE `itemtype` LIKE 'PluginFusinvinventory%';";
   $DB->query($query) or die($DB->error());
   
   $query = "SHOW TABLES;";
   $result=$DB->query($query);
   while ($data=$DB->fetch_array($result)) {
      if (strstr($data[0],"glpi_plugin_".$a_plugin['shortname']."_")){
         $query_delete = "DROP TABLE `".$data[0]."`;";
         $DB->query($query_delete) or die($DB->error());
      }
   }
   return true;
}

?>