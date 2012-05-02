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

if (!defined('GLPI_ROOT')) {
   define('GLPI_ROOT', '../../..');
   require GLPI_ROOT . "/inc/includes.php";
   restore_error_handler();

   error_reporting(E_ALL | E_STRICT);
   ini_set('display_errors','On');
}

class FusinvInstall extends PHPUnit_Framework_TestCase {

   public function testDB($pluginname='') {
      global $DB;
       
      if ($pluginname == '') {
         return;
      }
      
       $comparaisonSQLFile = "plugin_".$pluginname."-0.80+1.3-empty.sql";
       // See http://joefreeman.co.uk/blog/2009/07/php-script-to-compare-mysql-database-schemas/
       
       $file_content = file_get_contents("../../".$pluginname."/install/mysql/".$comparaisonSQLFile);
       $a_lines = explode("\n", $file_content);
       
       $a_tables_ref = array();
       $current_table = '';
       foreach ($a_lines as $line) {
          if (strstr($line, "CREATE TABLE ")
                  OR strstr($line, "CREATE VIEW")) {
             $matches = array();
             preg_match("/`(.*)`/", $line, $matches);
             $current_table = $matches[1];
          } else {
             if (preg_match("/^`/", trim($line))) {
                $s_line = explode("`", $line);
                $s_type = explode("COMMENT", $s_line[2]);
                $s_type[0] = trim($s_type[0]);
                $s_type[0] = str_replace(" COLLATE utf8_unicode_ci", "", $s_type[0]);
                $s_type[0] = str_replace(" CHARACTER SET utf8", "", $s_type[0]);
                $a_tables_ref[$current_table][$s_line[1]] = str_replace(",", "", $s_type[0]);
             }
          }
       }
       if (isset($a_tables_ref['glpi_plugin_fusinvdeploy_tasks'])) {
          unset($a_tables_ref['glpi_plugin_fusinvdeploy_tasks']);
       }
       if (isset($a_tables_ref['glpi_plugin_fusinvdeploy_taskjobs'])) {
          unset($a_tables_ref['glpi_plugin_fusinvdeploy_taskjobs']);
       }
       
      // * Get tables from MySQL
      $a_tables_db = array();
      $a_tables = array();
      // SHOW TABLES;
      $query = "SHOW TABLES";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         if ((strstr($data[0], "tracker")
                 OR strstr($data[0], $pluginname))
             AND(!strstr($data[0], "glpi_plugin_fusinvinventory_pcidevices"))
             AND(!strstr($data[0], "glpi_plugin_fusinvinventory_pcivendors"))
             AND(!strstr($data[0], "glpi_plugin_fusinvinventory_usbdevices"))
             AND(!strstr($data[0], "glpi_plugin_fusinvinventory_usbvendors"))
             AND($data[0] != 'glpi_plugin_fusinvdeploy_tasks')
             AND($data[0] != 'glpi_plugin_fusinvdeploy_taskjobs')){
            $data[0] = str_replace(" COLLATE utf8_unicode_ci", "", $data[0]);
            $data[0] = str_replace("( ", "(", $data[0]);
            $data[0] = str_replace(" )", ")", $data[0]);
            $a_tables[] = $data[0];
         }
      }
      
      foreach($a_tables as $table) {
         $query = "SHOW COLUMNS FROM ".$table;
         $result = $DB->query($query);
         while ($data=$DB->fetch_array($result)) {
            $construct = $data['Type'];
//            if ($data['Type'] == 'text') {
//               $construct .= ' COLLATE utf8_unicode_ci';
//            }
            if ($data['Type'] == 'text') {
               if ($data['Null'] == 'NO') {
                  $construct .= ' NOT NULL';
               } else {
                  $construct .= ' DEFAULT NULL';
               }
            } else if ($data['Type'] == 'longtext') {
               if ($data['Null'] == 'NO') {
                  $construct .= ' NOT NULL';
               } else {
                  $construct .= ' DEFAULT NULL';
               }
            } else {
               if ((strstr($data['Type'], "char")
                       OR $data['Type'] == 'datetime'
                       OR strstr($data['Type'], "int"))
                       AND $data['Null'] == 'YES'
                       AND $data['Default'] == '') {
                  $construct .= ' DEFAULT NULL';
               } else {               
                  if ($data['Null'] == 'YES') {
                     $construct .= ' NULL';
                  } else {
                     $construct .= ' NOT NULL';
                  }
                  if ($data['Extra'] == 'auto_increment') {
                     $construct .= ' AUTO_INCREMENT';
                  } else {
//                     if ($data['Type'] != 'datetime') {
                        $construct .= " DEFAULT '".$data['Default']."'";
//                     }
                  }
               }
            }
            $a_tables_db[$table][$data['Field']] = $construct;
         }         
      }
      
       // Compare
      $tables_toremove = array_diff_assoc($a_tables_db, $a_tables_ref);
      $tables_toadd = array_diff_assoc($a_tables_ref, $a_tables_db);
       
      // See tables missing or to delete
      $this->assertEquals(count($tables_toadd), 0, 'Tables missing '.print_r($tables_toadd));
      $this->assertEquals(count($tables_toremove), 0, 'Tables to delete '.print_r($tables_toremove));
      
      // See if fields are same
      foreach ($a_tables_db as $table=>$data) {
         if (isset($a_tables_ref[$table])) {
            $fields_toremove = array_diff_assoc($data, $a_tables_ref[$table]);
            $fields_toadd = array_diff_assoc($a_tables_ref[$table], $data);
            echo "======= DB ============== Ref =======> ".$table."\n";
            
            print_r($data);
            print_r($a_tables_ref[$table]);
            
            // See tables missing or to delete
            $this->assertEquals(count($fields_toadd), 0, 'Fields missing/not good in '.$table.' '.print_r($fields_toadd));
            $this->assertEquals(count($fields_toremove), 0, 'Fields to delete in '.$table.' '.print_r($fields_toremove));
            
         }         
      }
      
      /*
       * Check if all modules registered
       */
      $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_agentmodules` 
         WHERE `modulename`='WAKEONLAN'";
      $result = $DB->query($query);
      $this->assertEquals($DB->numrows($result), 1, 'WAKEONLAN module not registered');
      
      $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_agentmodules` 
         WHERE `modulename`='INVENTORY'";
      $result = $DB->query($query);
      $this->assertEquals($DB->numrows($result), 1, 'INVENTORY module not registered');
      
      $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_agentmodules` 
         WHERE `modulename`='ESX'";
      $result = $DB->query($query);
      $this->assertEquals($DB->numrows($result), 1, 'ESX module not registered');
      
      $query = "SELECT `url` FROM `glpi_plugin_fusioninventory_agentmodules` 
         WHERE `modulename`='ESX'";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $url = 0;
         if (!empty($data['url'])
                 AND strstr($data['url'], "http")
                 AND strstr($data['url'], "/esx")) {
            $url = 1;
         }
         $this->assertEquals($url, 1, 'ESX module url not right');
      }
      
      $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_agentmodules` 
         WHERE `modulename`='SNMPQUERY'";
      $result = $DB->query($query);
      $this->assertEquals($DB->numrows($result), 1, 'SNMPQUERY module not registered');
      
      $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_agentmodules` 
         WHERE `modulename`='NETDISCOVERY'";
      $result = $DB->query($query);
      $this->assertEquals($DB->numrows($result), 1, 'NETDISCOVERY module not registered');
      
      $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_agentmodules` 
         WHERE `modulename`='DEPLOY'";
      $result = $DB->query($query);
      $this->assertEquals($DB->numrows($result), 1, 'DEPLOY module not registered');
      
      
      /*
       * Verify in taskjob definition PluginFusinvsnmpIPRange not exist
       */
      $query = "SELECT * FROM `glpi_plugin_fusioninventory_taskjobs`";
      $result = $DB->query($query);
      while ($data=$DB->fetch_array($result)) {
         $snmprangeip = 0;
         if (strstr($data['definition'], "PluginFusinvsnmpIPRange")) {
            $snmprangeip = 1;
         }
         $this->assertEquals($snmprangeip, 0, 'Have some "PluginFusinvsnmpIPRange" items in taskjob definition');
      }
      
      /*
       * Verify cron created
       */
      $crontask = new CronTask();
      $this->assertTrue($crontask->getFromDBbyName('PluginFusioninventoryTaskjob', 'taskscheduler'), 
              'Cron taskscheduler not created');
      $this->assertTrue($crontask->getFromDBbyName('PluginFusioninventoryTaskjobstatus', 'cleantaskjob'), 
              'Cron cleantaskjob not created');
      $this->assertTrue($crontask->getFromDBbyName('PluginFusinvsnmpNetworkPortLog', 'cleannetworkportlogs'), 
              'Cron cleannetworkportlogs not created');
      
      
      /*
       * Verify config fields added
       */
      $plugin = new Plugin();
      $data = $plugin->find("directory='fusioninventory'");
      $plugins_id = 0;
      if (count($data)) {
         $fields = current($data);
         $plugins_id = $fields['id'];
      }
      $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_configs` 
         WHERE `plugins_id`='".$plugins_id."'
            AND `type`='ssl_only'";
      $result = $DB->query($query);
      $this->assertEquals($DB->numrows($result), 1, "type 'ssl_only' not added in config for plugins ".$plugins_id);

      $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_configs` 
         WHERE `plugins_id`='".$plugins_id."'
            AND `type`='delete_task'";
      $result = $DB->query($query);
      $this->assertEquals($DB->numrows($result), 1, "type 'delete_task' not added in config");
      
      $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_configs` 
         WHERE `plugins_id`='".$plugins_id."'
            AND `type`='inventory_frequence'";
      $result = $DB->query($query);
      $this->assertEquals($DB->numrows($result), 1, "type 'inventory_frequence' not added in config");
 
      $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_configs` 
         WHERE `plugins_id`='".$plugins_id."'
            AND `type`='agent_port'";
      $result = $DB->query($query);
      $this->assertEquals($DB->numrows($result), 1, "type 'agent_port' not added in config");
 
      $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_configs` 
         WHERE `plugins_id`='".$plugins_id."'
            AND `type`='extradebug'";
      $result = $DB->query($query);
      $this->assertEquals($DB->numrows($result), 1, "type 'extradebug' not added in config");
 
      $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_configs` 
         WHERE `plugins_id`='".$plugins_id."'
            AND `type`='users_id'";
      $result = $DB->query($query);
      $this->assertEquals($DB->numrows($result), 1, "type 'users_id' not added in config");

      $query = "SELECT `id` FROM `glpi_plugin_fusioninventory_configs` 
         WHERE `plugins_id`='".$plugins_id."'
            AND `type`='version'";
      $result = $DB->query($query);
      $this->assertEquals($DB->numrows($result), 1, "type 'version' not added in config");

      
      
      // TODO : test glpi_displaypreferences, rules, bookmark...

      
      if ($pluginname == 'fusinvsnmp') {
         
      /*
       * Verify SNMP models have a right itemtype
       */         
      $query = "SELECT * FROM `glpi_plugin_fusinvsnmp_models`
         WHERE `itemtype` NOT IN('Computer','NetworkEquipment', 'Printer')";
      $result = $DB->query($query);
      $this->assertEquals($DB->numrows($result), 0, "SNMP models have invalid itemtype");
         
      
      /*
       * Verify SNMP models not in double
       */
      $query = "SELECT count(*) as cnt, `name` FROM `glpi_plugin_fusinvsnmp_models` 
         GROUP BY `name` 
         HAVING cnt >1";
      $result = $DB->query($query);
      $this->assertEquals($DB->numrows($result), 0, "SNMP models are in double (name of models)");
         
         
      }
      
   }
}

require_once 'Install/AllTests.php';
require_once 'Update/AllTests.php';

class FusinvInstall_AllTests  {

   public static function suite() {

      $suite = new PHPUnit_Framework_TestSuite('FusinvInstall');
      $suite->addTest(Install_AllTests::suite());
      $suite->addTest(Update_AllTests::suite());
      return $suite;
   }
}
?>
