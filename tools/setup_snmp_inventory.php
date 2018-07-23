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
 * This file is used to manage the extended information of a computer.
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    Walid Nouh
 * @copyright Copyright (c) 2010-2018 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

chdir(dirname($_SERVER["SCRIPT_FILENAME"]));

 include ("../../../inc/includes.php");

if (isset($_SERVER['argv'])) {
   for ($i=1; $i<$_SERVER['argc']; $i++) {
      $it    = explode("=", $_SERVER['argv'][$i], 2);
      $it[0] = preg_replace('/^--/', '', $it[0]);

      $_GET[$it[0]] = (isset($it[1]) ? $it[1] : true);
   }
}

if (isset($_GET['help']) || !count($_GET)) {
   echo "This script create an IP Range, a community, and 2 SNMP tasks: one for discovery and one for inventory\n";
   echo "Usage: php setup_snmp_inventory.php [--name] [--ip_start] [--ip_end] [--community] [--version] [--device_id] [--url] [--comp_name] [--user]\n";
   echo "Options:\n";
   echo "--name: name of the IP range to create (default 'localhost')\n";
   echo "--ip_start: IP range starting address (default '127.0.0.1')\n";
   echo "--ip_end: IP range ending address (default '127.0.0.1')\n";
   echo "--community: SNMP Community to create (default public)\n";
   echo "--version: SNMP community version to use (default 2c)\n";
   echo "--device_id: Agent's device ID (mandatory)\n";
   echo "--url: Agent base url (default 'http://localhost/glpi')\n";
   echo "--comp_name: Name of the computer to create (mandatory)\n";
   echo "--user: User to display in GLPI historical\n";
   exit(0);
}

$params = [
   'name'      => 'localhost',
   'ip_start'  => '127.0.0.1',
   'ip_end'    => '127.0.0.1',
   'community' => 'public',
   'version'   => '2c',
   'device_id' => '',
   'url'       => 'http://localhost/glpi',
   'comp_name' => '',
   'user'      => 'teclib'
];
foreach ($params as $key => $value) {
   if (isset($_GET[$key]) && $_GET[$key] != '') {
      $params[$key] = $_GET[$key];
   }
}
echo "\nScript to fill database with snmp tasks starting\n";

$_SESSION["glpicronuserrunning"] = $_SESSION["glpiname"] = $params['user'];

$entity = new PluginFusioninventoryEntity();
if ($entity->update(['id' => 1, 'agent_base_url' => $params['url']])) {
   echo "agent_base_url set to ".$params['url']."\n";
} else {
   echo "Error setting agent_base_url. Exiting\n";
}

$range        = new PluginFusioninventoryIPRange();
$params_range = $params;
$result       = $range->getFromDBByCrit(['name' => $params['name']]);
if ($result > 0) {
   $ipranges_id = $range->getID();
   echo "IP Range exists, id=$ipranges_id\n";
} else {
   $ipranges_id = $range->add($params_range);
   echo "IP Range added, id=$ipranges_id\n";
}

$auth        = new PluginFusioninventoryConfigSecurity();
$params_auth = [
   'name'          => $params['name'],
   'community'     => $params['community'],
   'snmpversion'   => $params['version']
];
$result = $auth->getFromDBByCrit($params_auth);
if ($result > 0) {
   $auths_id = $auth->getID();
   echo "SNMP community exists, id=$auths_id\n";
} else {
   $auths_id = $auth->add($params_auth);
   echo "SNMP community added, id=$auths_id\n";
}
if ($auths_id && $ipranges_id) {
   $authrange = new PluginFusioninventoryIPRange_ConfigSecurity();
   $params_iprange = [
      'plugin_fusioninventory_ipranges_id'         => $ipranges_id,
      'plugin_fusioninventory_configsecurities_id' => $auths_id
   ];
   $result = $authrange->getFromDBByCrit($params_iprange);
   if ($result < 0 || !$result) {
      $authrange->add($params_iprange);
      echo "SNMP community ".$params['community']." added to range".$params['name']."\n";
   } else {
      echo "SNMP community ".$params['community']." already present to range".$params['name']."\n";
   }
}

if ($params['device_id'] == '') {
   echo "Exiting: no deviceid set!\n";
}

if ($params['comp_name'] == '') {
   echo "Exiting: no computer_name set!\n";
}

$module = new PluginFusioninventoryAgentmodule();
echo "Enabled modules netdiscovery & networkinventory by default\n";
$DB->query("UPDATE `glpi_plugin_fusioninventory_agentmodules` SET `is_active`=1");

$computer        = new Computer();
$params_computer = ['name' => $params['comp_name'], 'entities_id' => 0];
$result          = $computer->getFromDBByCrit($params_computer);
if ($result > 0) {
   $computers_id = $computer->getID();
   echo "Computer ".$params['comp_name']." already present with id $computers_id\n";
} else {
   $computers_id    = $computer->add($params_computer);
   echo "Computer ".$params['comp_name']." added with id $computers_id\n";
}

$agent  = new PluginFusioninventoryAgent();
$result = $agent->getFromDBByCrit(['device_id' => $params['device_id']]);
if ($result > 0) {
   $agents_id = $agent->getID();
} else {
   $agents_id = $agent->add([
      'name'         => $params['device_id'],
      'device_id'    => $params['device_id'],
      'computers_id' => $computers_id,
      'entities_id'  => 0
   ]);
   echo "Agent ".$params['device_id']." added with id $agents_id\n";
}

$task      = new PluginFusioninventoryTask();
$taskjob   = new PluginFusioninventoryTaskjob();
$task_name = $params['name'].'-disco';
$result    = $task->getFromDBByCrit(['name' => $task_name]);
if ($result > 0) {
   $tasks_id_disco = $task->getID();
   echo "Discovery task ".$task_name." already present with id $tasks_id_disco\n";
} else {
   $tasks_id_disco = $task->add([
      'name'                    => $task_name,
      'entities_id'             => 0,
      'reprepare_if_successful' => 1,
      'is_active'               => 1
   ]);
   echo "Discovery task ".$task_name." added with id $tasks_id_disco\n";

   if ($tasks_id_disco) {
      $taskjob->add([
         'name' => $task_name,
         'plugin_fusioninventory_tasks_id' => $tasks_id_disco,
         'method'  => 'networkdiscovery',
         'targets' => "[{\"PluginFusioninventoryIPRange\":\"$ipranges_id\"}]",
         'actors'  => "[{\"PluginFusioninventoryAgent\":\"$agents_id\"}]"
      ]);
   }

}

$task_name = $params['name'].'-inv';
$result    = $task->getFromDBByCrit(['name' => $task_name]);
if ($result > 0) {
   $tasks_id_inv = $task->getID();
   echo "Inventory task ".$task_name." already present with id $tasks_id_inv\n";
} else {
   $tasks_id_inv = $task->add([
      'name'                    => $task_name,
      'entities_id'             => 0,
      'reprepare_if_successful' => 1,
      'is_active'               => 1
   ]);
   echo "Inventory task ".$task_name." added with id $tasks_id_inv\n";

   if ($tasks_id_inv) {
      $taskjob->add([
         'name' => $task_name,
         'plugin_fusioninventory_tasks_id' => $tasks_id_inv,
         'method'  => 'networkdiscovery',
         'targets' => "[{\"PluginFusioninventoryIPRange\":\"$ipranges_id\"}]",
         'actors'  => "[{\"PluginFusioninventoryAgent\":\"$agents_id\"}]"
      ]);
   }

}
