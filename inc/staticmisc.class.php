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
 * This file is used to manage the specifications of each module and for
 * the task configuration.
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

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the specifications of each module and for the task configuration.
 */
class PluginFusioninventoryStaticmisc {


   /**
    * Get task methods of this plugin fusioninventory
    *
    * @return array('module'=>'value', 'method'=>'value')
    *   module value name of plugin
    *   method value name of method
    */
   static function task_methods() {

      $a_tasks = [
            [   'module'         => 'fusioninventory',
                     'classname'      => 'PluginFusioninventoryWakeonlan',
                     'method'         => 'wakeonlan',
                     'name'           => __('Wake On LAN', 'fusioninventory'),
                     'use_rest'       => false
            ],

            [   'module'         => 'fusioninventory',
                     'method'         => 'inventory',
                     'selection_type' => 'devices',
                     'hidetask'       => 1,
                     'name'           => __('Computer Inventory', 'fusioninventory'),
                     'use_rest'       => false
            ],

            [   'module'         => 'fusioninventory',
                     'classname'      => 'PluginFusioninventoryInventoryComputerESX',
                     'method'         => 'InventoryComputerESX',
                     'selection_type' => 'devices',
                     'name'           => __('VMware host remote inventory', 'fusioninventory'),
                     'task'           => 'ESX',
                     'use_rest'       => true
            ],

            [   'module'         => 'fusioninventory',
                     'classname'      => 'PluginFusioninventoryNetworkDiscovery',
                     'method'         => 'networkdiscovery',
                     'name'           => __('Network discovery', 'fusioninventory')
            ],

            [   'module'         => 'fusioninventory',
                     'classname'      => 'PluginFusioninventoryNetworkInventory',
                     'method'         => 'networkinventory',
                     'name'           => __('Network inventory (SNMP)', 'fusioninventory')
            ],

            [   'module'         => 'fusioninventory',
                     'classname'      => 'PluginFusioninventoryDeployCommon',
                     'method'         => 'deployinstall',
                     'name'           => __('Package deploy', 'fusioninventory'),
                     'task'           => "DEPLOY",
                     'use_rest'       => true
            ],

            [   'module'         => 'fusioninventory',
                     'classname'      => 'PluginFusioninventoryCollect',
                     'method'         => 'collect',
                     'name'           => __('Collect data', 'fusioninventory'),
                     'task'           => "Collect",
                     'use_rest'       => true
            ]
      ];
      return $a_tasks;
   }


   /**
    * Display methods availables
    *
    * @return array
    */
   static function getModulesMethods() {

      $methods = PluginFusioninventoryStaticmisc::getmethods();

      $modules_methods = [];
      $modules_methods[''] = "------";
      foreach ($methods as $method) {
         if (!((isset($method['hidetask']) AND $method['hidetask'] == '1'))) {
            if (isset($method['name'])) {
               $modules_methods[$method['method']] = $method['name'];
            } else {
               $modules_methods[$method['method']] = $method['method'];
            }
         }
      }
      return $modules_methods;
   }


   /**
    * Get types of datas available to select for taskjob definition for WakeOnLan method
    *
    * @param array $a_itemtype types yet added for definitions
    * @return array('itemtype'=>'value', 'itemtype'=>'value'...)
    *   itemtype itemtype of object
    *   value name of the itemtype
    */
   static function task_definitiontype_wakeonlan($a_itemtype) {

      $a_itemtype['Computer'] = Computer::getTypeName();
      $a_itemtype['PluginFusioninventoryDeployGroup']
                              = PluginFusioninventoryDeployGroup::getTypeName();
      return $a_itemtype;
   }


   /**
    * Get all devices of definition type 'Computer' defined in
    * task_definitiontype_wakeonlan
    *
    * @param string $title (not used)
    * @return string unique html element id
    */
   static function task_definitionselection_Computer_wakeonlan($title) {

      $options = [];
      $options['entity'] = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name'] = 'definitionselectiontoadd';
      $rand = Dropdown::show("Computer", $options);
      return $rand;
   }


   /**
    * Get all devices of definition type 'PluginFusioninventoryDeployGroup'
    * defined in task_definitiontype_wakeonlan
    *
    * @param string $title (not used)
    * @return string unique html element id
    */
   static function task_definitionselection_PluginFusioninventoryDeployGroup_wakeonlan($title) {
      $options = [];
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'definitionselectiontoadd';
      return Dropdown::show("PluginFusioninventoryDeployGroup", $options);
   }


   /**
    * Get all methods of this plugin
    *
    * @return array('module'=>'value', 'method'=>'value')
    *   module value name of plugin
    *   method value name of method
    *
    */
   static function getmethods() {
      $a_methods = call_user_func(['PluginFusioninventoryStaticmisc', 'task_methods']);
      $a_modules = PluginFusioninventoryModule::getAll();
      foreach ($a_modules as $data) {
         $class = $class= PluginFusioninventoryStaticmisc::getStaticMiscClass($data['directory']);
         if (is_callable([$class, 'task_methods'])) {
            $a_methods = array_merge($a_methods,
               call_user_func([$class, 'task_methods']));
         }
      }
      return $a_methods;
   }


   /**
    * Get name of the staticmisc class for a module
    *
    * @param string $module the module name
    * @return string the name of the staticmisc class associated with it
    */
   static function getStaticMiscClass($module) {
      return "Plugin".ucfirst($module)."Staticmisc";
   }


   /**
    * Get types of datas available to select for taskjob definition for ESX method
    *
    * @param array $a_itemtype array types yet added for definitions
    * @return array('itemtype'=>'value', 'itemtype'=>'value'...)
    *   itemtype itemtype of object
    *   value name of the itemtype
    */
   static function task_definitiontype_InventoryComputerESX($a_itemtype) {
      $a_itemtype['PluginFusioninventoryCredentialIp'] =
                       PluginFusioninventoryCredentialIp::getTypeName();
      return $a_itemtype;
   }


   /**
    * Get all devices of definition type 'PluginFusioninventoryCredentialIp'
    * defined in task_definitiontype_InventoryComputerESX
    *
    * @global object $DB
    * @param string (not used)
    * @return string unique html element id
    */
   static function task_definitionselection_PluginFusioninventoryCredentialIp_InventoryComputerESX($title) {
      global $DB;

      $query = "SELECT `a`.`id`, `a`.`name`
                FROM `glpi_plugin_fusioninventory_credentialips` as `a`
                LEFT JOIN `glpi_plugin_fusioninventory_credentials` as `c`
                   ON `c`.`id` = `a`.`plugin_fusioninventory_credentials_id`
                WHERE `c`.`itemtype`='PluginFusioninventoryInventoryComputerESX'";
      $query.= getEntitiesRestrictRequest(' AND', 'a');
      $results = $DB->query($query);

      $agents = [];
      //$agents['.1'] = __('All');

      while ($data = $DB->fetchArray($results)) {
         $agents[$data['id']] = $data['name'];
      }
      if (!empty($agents)) {
         return Dropdown::showFromArray('definitionselectiontoadd', $agents);
      }
   }


   //------------------------------------------ Actions-------------------------------------//


   /**
    * Get action types for InventoryComputerESX
    *
    * @param array $a_itemtype
    * @return array
    */
   static function task_actiontype_InventoryComputerESX($a_itemtype) {
      return ['' => Dropdown::EMPTY_VALUE ,
                   'PluginFusioninventoryAgent' => __('Agents', 'fusioninventory')];

   }


   /**
    * Get all devices of action type 'PluginFusioninventoryCredentialIp'
    * defined in task_actiontype_InventoryComputerESX
    *
    * @global object $DB
    * @return string unique html element id
    */
   static function task_actionselection_PluginFusioninventoryCredentialIp_InventoryComputerESX() {
      global $DB;

      $options = [];
      $options['name'] = 'definitionactiontoadd';

      $query = "SELECT `a`.`id`, `a`.`name`
                FROM `glpi_plugin_fusioninventory_credentialips` as `a`
                LEFT JOIN `glpi_plugin_fusioninventory_credentials` as `c`
                   ON `c`.`id` = `a`.`plugin_fusioninventory_credentials_id`
                WHERE `c`.`itemtype`='PluginFusioninventoryInventoryComputerESX'";
      $query.= getEntitiesRestrictRequest(' AND', 'glpi_plugin_fusioninventory_credentialips');

      $results = $DB->query($query);
      $credentialips = [];
      while ($data = $DB->fetchArray($results)) {
         $credentialips[$data['id']] = $data['name'];
      }
      return Dropdown::showFromArray('actionselectiontoadd', $credentialips);
   }


   /**
    * Get all devices of action type 'PluginFusioninventoryAgent'
    * defined in task_actiontype_InventoryComputerESX
    *
    * @return string unique html element id
    */
   static function task_actionselection_PluginFusioninventoryAgent_InventoryComputerESX() {

      $array = [];
      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      $array1 = $pfAgentmodule->getAgentsCanDo(strtoupper("InventoryComputerESX"));
      foreach ($array1 as $id => $data) {
         $array[$id] = $data['name'];
      }
      asort($array);
      return Dropdown::showFromArray('actionselectiontoadd', $array);
   }


   //------------------------------------------ ---------------------------------------------//
   //------------------------------------------ REST PARAMS---------------------------------//
   //------------------------------------------ -------------------------------------------//


   /**
    * Get ESX task parameters to send to the agent
    * For the moment it's hardcoded, but in a future release it may be in DB
    *
    * @param integer $entities_id id of the entity
    * @return array
    */
   static function task_ESX_getParameters($entities_id) {
      return ['periodicity' => 3600, 'delayStartup' => 3600, 'task' => 'ESX',
                    "remote" => PluginFusioninventoryAgentmodule::getUrlForModule('ESX', $entities_id)];

   }


   //------------------------------- Network tools ------------------------------------//

   // *** NETWORKDISCOVERY ***


   /**
    * Definition types for network discovery
    *
    * @param array $a_itemtype
    * @return array
    */
   static function task_definitiontype_networkdiscovery($a_itemtype) {
      $a_itemtype['PluginFusioninventoryIPRange'] = __('IP Ranges', 'fusioninventory');
      return $a_itemtype;
   }


   /**
    * Get all ip ranges of definition type 'PluginFusioninventoryIPRange'
    * defined in task_definitiontype_networkdiscovery
    *
    * @param string $title (not used)
    * @return string unique html element id
    */
   static function task_definitionselection_PluginFusioninventoryIPRange_networkdiscovery($title) {
      $options = [];
      $options['entity'] = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name'] = 'definitionselectiontoadd';
      $rand = Dropdown::show("PluginFusioninventoryIPRange", $options);
      return $rand;
   }


   // *** NETWORKINVENTORY ***


   /**
    * Definition types for network inventory
    *
    * @param array $a_itemtype
    * @return array
    */
   static function task_definitiontype_networkinventory($a_itemtype) {
      $a_itemtype['PluginFusioninventoryIPRange'] = __('IP Ranges', 'fusioninventory');

      $a_itemtype['NetworkEquipment'] = NetworkEquipment::getTypeName();
      $a_itemtype['Printer'] = Printer::getTypeName();

      return $a_itemtype;
   }


   /**
    * Get all ip ranges of definition type 'PluginFusioninventoryIPRange'
    * defined in task_definitiontype_networkinventory
    *
    * @param string $title (not used)
    * @return string unique html element id
    */
   static function task_definitionselection_PluginFusioninventoryIPRange_networkinventory($title) {
      $rand = PluginFusioninventoryStaticmisc::task_definitionselection_PluginFusioninventoryIPRange_networkdiscovery($title);
      return $rand;
   }


   /**
    * Get all devices of definition type 'NetworkEquipment'
    * defined in task_definitiontype_networkinventory
    *
    * @param string $title (not used)
    * @return string unique html element id
    */
   static function task_definitionselection_NetworkEquipment_networkinventory($title) {
      $options = [];
      $options['entity'] = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name'] = 'definitionselectiontoadd';
      $rand = Dropdown::show("NetworkEquipment", $options);
      return $rand;
   }


   /**
    * Get all devices of definition type 'Printer'
    * defined in task_definitiontype_networkinventory
    *
    * @param string $title (not used)
    * @return string unique html element id
    */
   static function task_definitionselection_Printer_networkinventory($title) {

      $options = [];
      $options['entity'] = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name'] = 'definitionselectiontoadd';
      $rand = Dropdown::show("Printer", $options);
      return $rand;
   }


   /**
    * Get agents allowed to do network discovery
    *
    * @return array
    */
   static function task_networkdiscovery_agents() {

      $array = [];
      $array["-.1"] = __('Auto managenement dynamic of agents', 'fusioninventory');

      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      $array1 = $pfAgentmodule->getAgentsCanDo('NETWORKDISCOVERY');
      foreach ($array1 as $id => $data) {
         $array["PluginFusioninventoryAgent-".$id] =
                 __('Auto managenement dynamic of agents', 'fusioninventory')." - ".$data['name'];
      }
      return $array;
   }


   /**
    * Get types of actions for network inventory
    *
    * @return array
    */
   static function task_action_networkinventory() {
      $a_itemtype = [];
      $a_itemtype[] = "Printer";
      $a_itemtype[] = "NetworkEquipment";
      $a_itemtype[] = 'PluginFusioninventoryIPRange';

      return $a_itemtype;
   }


   /**
    * Get selection type for network inventory
    *
    * @param string $itemtype
    * @return string
    */
   static function task_selection_type_networkinventory($itemtype) {
      $selection_type = '';
      switch ($itemtype) {

         case 'PluginFusioninventoryIPRange':
            $selection_type = 'iprange';
            break;

         case "Printer";
         case "NetworkEquipment";
            $selection_type = 'devices';
            break;

      }
      return $selection_type;
   }


   /**
    * Get selection type for network discovery
    *
    * @param string $itemtype
    * @return array
    */
   static function task_selection_type_networkdiscovery($itemtype) {
      $selection_type = '';
      switch ($itemtype) {

         case 'PluginFusioninventoryIPRange':
            $selection_type = 'iprange';
            break;

      }
      return $selection_type;
   }


   /* Deploy definitions */


   /**
    * Get definition types for deploy install
    *
    * @param string $a_itemtype
    * @return array
    */
   static function task_definitiontype_deployinstall($a_itemtype) {
      return ['' => Dropdown::EMPTY_VALUE,
                   'PluginFusioninventoryDeployPackage' => __('Package')];
   }


   /**
    * Get all packages of definition type 'PluginFusioninventoryDeployPackage'
    * defined in task_definitiontype_deployinstall
    *
    * @param string $title (not used)
    * @return string unique html element id
    */
   static function task_definitionselection_PluginFusioninventoryDeployPackage_deployinstall() {
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'definitionselectiontoadd';
      return Dropdown::show("PluginFusioninventoryDeployPackage", $options);
   }


   /* Deploy Actions */


   /**
    * Get types of action for deployinstall
    *
    * @param array $a_itemtype
    * @return array
    */
   static function task_actiontype_deployinstall($a_itemtype) {
      return ['' => Dropdown::EMPTY_VALUE,
                   'Computer'                         => __('Computers'),
                   'PluginFusioninventoryDeployGroup' => PluginFusioninventoryDeployGroup::getTypeName(),
                   'Group'                            => __('Group')
                  ];
   }


   /**
    * Get all computers of action type 'Computer'
    * defined in task_actiontype_deployinstall
    *
    * @return string unique html element id
    */
   static function task_actionselection_Computer_deployinstall() {
      $options = [];
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      $options['condition']   =
         implode( " ",
            [
               '`id` IN ( ',
               '  SELECT agents.`computers_id`',
               '  FROM `glpi_plugin_fusioninventory_agents` as agents',
               '  LEFT JOIN `glpi_plugin_fusioninventory_agentmodules` as module',
               '  ON module.modulename = "DEPLOY"',
               '  WHERE',
               '        (  module.is_active=1',
               '           AND module.exceptions NOT LIKE CONCAT(\'%"\',agents.`id`,\'"%\') )',
               '     OR (  module.is_active=0',
               '           AND module.exceptions LIKE CONCAT(\'%"\',agents.`id`,\'"%\') )',
               ')'
            ]
         );
      return Dropdown::show("Computer", $options);
   }


   /**
    * Get all computers of action type 'Group'
    * defined in task_actiontype_deployinstall
    *
    * @return string unique html element id
    */
   static function task_actionselection_Group_deployinstall() {
      $options = [];
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      return Dropdown::show("Group", $options);
   }


   /**
    * Get all computers of action type 'PluginFusioninventoryDeployGroup'
    * defined in task_actiontype_deployinstall
    *
    * @return string unique html element id
    */
   static function task_actionselection_PluginFusioninventoryDeployGroup_deployinstall() {
      $options = [];
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      return Dropdown::show("PluginFusioninventoryDeployGroup", $options);
   }


   /**
    * Get Deploy paramaters: url for communication with server
    *
    * @param integer $entities_id
    * @return array
    */
   static function task_deploy_getParameters($entities_id) {
      return [
         "task" => "Deploy",
         "remote" => PluginFusioninventoryAgentmodule::getUrlForModule('Deploy', $entities_id)
      ];
   }


   /* Collect */


   /**
    * Get definition types of collect
    *
    * @param array $a_itemtype
    * @return array
    */
   static function task_definitiontype_collect($a_itemtype) {
      return ['' => Dropdown::EMPTY_VALUE,
                   'PluginFusioninventoryCollect' => __('Collect information', 'fusioninventory')];
   }


   /**
    * Get all collects of definition type 'PluginFusioninventoryCollect'
    * defined in task_definitiontype_collect
    *
    * @param string (not used)
    * @return string unique html element id
    */
   static function task_definitionselection_PluginFusioninventoryCollect_collect() {
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'definitionselectiontoadd';
      return Dropdown::show("PluginFusioninventoryCollect", $options);
   }


   /**
    * Get action types for collect
    *
    * @param array $a_itemtype
    * @return array
    */
   static function task_actiontype_collect($a_itemtype) {
      return ['' => Dropdown::EMPTY_VALUE,
                   'Computer'                         => __('Computers'),
                   'PluginFusioninventoryDeployGroup' => PluginFusioninventoryDeployGroup::getTypeName(),
                   'Group'                            => __('Group')
                  ];
   }


   /**
    * Get all computers of action type 'Computer'
    * defined in task_actiontype_collect
    *
    * @return string unique html element id
    */
   static function task_actionselection_Computer_collect() {
      $options = [];
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      $options['condition']   =
         implode( " ",
            [
               '`id` IN ( ',
               '  SELECT agents.`computers_id`',
               '  FROM `glpi_plugin_fusioninventory_agents` as agents',
               '  LEFT JOIN `glpi_plugin_fusioninventory_agentmodules` as module',
               '  ON module.modulename = "Collect"',
               '  WHERE',
               '        (  module.is_active=1',
               '           AND module.exceptions NOT LIKE CONCAT(\'%"\',agents.`id`,\'"%\') )',
               '     OR (  module.is_active=0',
               '           AND module.exceptions LIKE CONCAT(\'%"\',agents.`id`,\'"%\') )',
               ')'
            ]
         );
      return Dropdown::show("Computer", $options);
   }


   /**
    * Get all computers of action type 'Group'
    * defined in task_actiontype_collect
    *
    * @return string unique html element id
    */
   static function task_actionselection_Group_collect() {
      $options = [];
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      return Dropdown::show("Group", $options);
   }


   /**
    * Get all computers of action type 'PluginFusioninventoryDeployGroup'
    * defined in task_actiontype_collect
    *
    * @return string unique html element id
    */
   static function task_actionselection_PluginFusioninventoryDeployGroup_collect() {
      $options = [];
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      return Dropdown::show("PluginFusioninventoryDeployGroup", $options);
   }


   /**
    *
    * Get collect parameters (URL to dialog with server)
    *
    * @param integer $entities_id
    * @return array
    */
   static function task_collect_getParameters($entities_id) {
      return [
         "task" => "Collect",
         "remote" => PluginFusioninventoryAgentmodule::getUrlForModule('Collect', $entities_id)
      ];
   }
}
