<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2016 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010

   ------------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryStaticmisc {

   /**
   * Get task methods of this plugin fusioninventory
   *
   * @return array ('module'=>'value', 'method'=>'value')
   *   module value name of plugin
   *   method value name of method
   **/
   static function task_methods() {

      $a_tasks = array(
            array(   'module'         => 'fusioninventory',
                     'classname'      => 'PluginFusioninventoryWakeonlan',
                     'method'         => 'wakeonlan',
                     'name'           => __('Wake On LAN', 'fusioninventory'),
                     'use_rest'       => FALSE
            ),

            array(   'module'         => 'fusioninventory',
                     'method'         => 'inventory',
                     'selection_type' => 'devices',
                     'hidetask'       => 1,
                     'name'           => __('Computer Inventory', 'fusioninventory'),
                     'use_rest'       => FALSE
            ),

            array(   'module'         => 'fusioninventory',
                     'classname'      => 'PluginFusioninventoryInventoryComputerESX',
                     'method'         => 'InventoryComputerESX',
                     'selection_type' => 'devices',
                     'name'           => __('VMware host remote inventory', 'fusioninventory'),
                     'task'           => 'ESX',
                     'use_rest'       => TRUE
            ),

            array(   'module'         => 'fusioninventory',
                     'classname'      => 'PluginFusioninventoryNetworkDiscovery',
                     'method'         => 'networkdiscovery',
                     'name'           => __('Network discovery', 'fusioninventory')
            ),

            array(   'module'         => 'fusioninventory',
                     'classname'      => 'PluginFusioninventoryNetworkInventory',
                     'method'         => 'networkinventory',
                     'name'           => __('Network inventory (SNMP)', 'fusioninventory')
            ),

            array(   'module'         => 'fusioninventory',
                     'classname'      => 'PluginFusioninventoryDeployCommon',
                     'method'         => 'deployinstall',
                     'name'           => __('Package install', 'fusioninventory'),
                     'task'           => "DEPLOY",
                     'use_rest'       => TRUE
            ),

            array(   'module'         => 'fusioninventory',
                     'classname'      => 'PluginFusioninventoryDeployCommon',
                     'method'         => 'deployuninstall',
                     'name'           => __('Package uninstall', 'fusioninventory'),
                     'task'           => "DEPLOY",
                     'use_rest'       => TRUE
            ),

            array(   'module'         => 'fusioninventory',
                     'classname'      => 'PluginFusioninventoryCollect',
                     'method'         => 'collect',
                     'name'           => __('Collect data', 'fusioninventory'),
                     'task'           => "Collect",
                     'use_rest'       => TRUE
            )
      );
      return $a_tasks;
   }

   /**
   * Display methods availables
   *
   * @param $myname value name of dropdown
   * @param $value value name of the method (used for edit taskjob)
   * @param $entity_restrict restriction of entity if required
   *
   * @return value rand of the dropdown
   *
   **/
   static function getModulesMethods() {

      $methods = PluginFusioninventoryStaticmisc::getmethods();

      $modules_methods = array();
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
   * @param $a_itemtype array types yet added for definitions
   *
   * @return array ('itemtype'=>'value', 'itemtype'=>'value'...)
   *   itemtype itemtype of object
   *   value name of the itemtype
   **/
   static function task_definitiontype_wakeonlan($a_itemtype) {

      $a_itemtype['Computer'] = Computer::getTypeName();
      $a_itemtype['PluginFusioninventoryDeployGroup'] = __('Dynamic Group');
      return $a_itemtype;
   }



   /**
   * Get all devices of definition type 'Computer' defined in task_definitiontype_wakeonlan
   *
   * @param $title value ???(not used I think)
   *
   * @return dropdown list of computers
   *
   **/
   static function task_definitionselection_Computer_wakeonlan($title) {

      $options = array();
      $options['entity'] = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name'] = 'definitionselectiontoadd';
      $rand = Dropdown::show("Computer", $options);
      return $rand;
   }



   static function task_definitionselection_PluginFusioninventoryDeployGroup_wakeonlan($title) {
      $options = array();
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'definitionselectiontoadd';
      return Dropdown::show("PluginFusioninventoryDeployGroup", $options);
   }



   /**
   * Get all methods of this plugin
   *
   * @return array ('module'=>'value', 'method'=>'value')
   *   module value name of plugin
   *   method value name of method
   *
   **/
   static function getmethods() {
      $a_methods = call_user_func(array('PluginFusioninventoryStaticmisc', 'task_methods'));
      $a_modules = PluginFusioninventoryModule::getAll();
      foreach ($a_modules as $data) {
         $class = $class= PluginFusioninventoryStaticmisc::getStaticmiscClass($data['directory']);
         if (is_callable(array($class, 'task_methods'))) {
            $a_methods = array_merge($a_methods,
               call_user_func(array($class, 'task_methods')));
         }
      }
      return $a_methods;
   }



   /**
    * Get name of the staticmisc class for a module
    * @param module the module name
    *
    * @return the name of the staticmisc class associated with it
    */
   static function getStaticMiscClass($module) {
      return "Plugin".ucfirst($module)."Staticmisc";
   }



   /**
   * Get types of datas available to select for taskjob definition for ESX method
   *
   * @param $a_itemtype array types yet added for definitions
   *
   * @return array ('itemtype'=>'value', 'itemtype'=>'value'...)
   *   itemtype itemtype of object
   *   value name of the itemtype
   **/
   static function task_definitiontype_InventoryComputerESX($a_itemtype) {
      $a_itemtype['PluginFusioninventoryCredentialIp'] =
                       PluginFusioninventoryCredentialIp::getTypeName();
      return $a_itemtype;
   }



   /**
   * Get all devices of definition type 'Computer' defined in task_definitiontype_wakeonlan
   *
   * @param $title value ???(not used I think)
   *
   * @return dropdown list of computers
   *
   **/
   static function task_definitionselection_PluginFusioninventoryCredentialIp_InventoryComputerESX($title) {
      global $DB;

      $query = "SELECT `a`.`id`, `a`.`name`
                FROM `glpi_plugin_fusioninventory_credentialips` as `a`
                LEFT JOIN `glpi_plugin_fusioninventory_credentials` as `c`
                   ON `c`.`id` = `a`.`plugin_fusioninventory_credentials_id`
                WHERE `c`.`itemtype`='PluginFusioninventoryInventoryComputerESX'";
      $query.= getEntitiesRestrictRequest(' AND', 'a');
      $results = $DB->query($query);

      $agents = array();
      //$agents['.1'] = __('All');

      while ($data = $DB->fetch_array($results)) {
         $agents[$data['id']] = $data['name'];
      }
      if (!empty($agents)) {
         return Dropdown::showFromArray('definitionselectiontoadd', $agents);
      }
   }



   //------------------------------------------ Actions-------------------------------------//

   static function task_actiontype_InventoryComputerESX($a_itemtype) {
      return array ('' => Dropdown::EMPTY_VALUE ,
                    'PluginFusioninventoryAgent' => __('Agents', 'fusioninventory'));

   }



   /**
   * Get all devices of definition type 'Computer' defined in task_definitiontype_wakeonlan
   *
   * @return dropdown list of computers
   *
   **/
   static function task_actionselection_PluginFusioninventoryCredentialIp_InventoryComputerESX() {
      global $DB;

      $options = array();
      $options['name'] = 'definitionactiontoadd';

      $module = new PluginFusioninventoryAgentmodule();
      $module_infos = $module->getActivationExceptions('InventoryComputerESX');
      $exceptions = json_decode($module_infos['exceptions'], TRUE);

      $in = "";
      if (!empty($exceptions)) {
         $in = " AND `a`.`id` NOT IN (".implode($exceptions, ', ').")";
      }

      $query = "SELECT `a`.`id`, `a`.`name`
                FROM `glpi_plugin_fusioninventory_credentialips` as `a`
                LEFT JOIN `glpi_plugin_fusioninventory_credentials` as `c`
                   ON `c`.`id` = `a`.`plugin_fusioninventory_credentials_id`
                WHERE `c`.`itemtype`='PluginFusioninventoryInventoryComputerESX'";
      $query.= getEntitiesRestrictRequest(' AND', 'glpi_plugin_fusioninventory_credentialips');

      $results = $DB->query($query);
      $credentialips = array();
      while ($data = $DB->fetch_array($results)) {
         $credentialips[$data['id']] = $data['name'];
      }
      return Dropdown::showFromArray('actionselectiontoadd', $credentialips);
   }



   static function task_actionselection_PluginFusioninventoryAgent_InventoryComputerESX() {

      $array = array();
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
    * @return an array of parameters
    */
   static function task_ESX_getParameters($entities_id) {
      return array ('periodicity' => 3600, 'delayStartup' => 3600, 'task' => 'ESX',
                    "remote" => PluginFusioninventoryAgentmodule::getUrlForModule('ESX', $entities_id));

   }



   //------------------------------- Network tools ------------------------------------//

   // *** NETWORKDISCOVERY ***
   static function task_definitiontype_networkdiscovery($a_itemtype) {
      $a_itemtype['PluginFusioninventoryIPRange'] = __('IP Ranges', 'fusioninventory');
      return $a_itemtype;
   }



   static function task_definitionselection_PluginFusioninventoryIPRange_networkdiscovery($title) {

      $options = array();
      $options['entity'] = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name'] = 'definitionselectiontoadd';
      $rand = Dropdown::show("PluginFusioninventoryIPRange", $options);
      return $rand;
   }



   // *** NETWORKINVENTORY ***
   static function task_definitiontype_networkinventory($a_itemtype) {

      $a_itemtype['PluginFusioninventoryIPRange'] = __('IP Ranges', 'fusioninventory');

      $a_itemtype['NetworkEquipment'] = NetworkEquipment::getTypeName();
      $a_itemtype['Printer'] = Printer::getTypeName();

      return $a_itemtype;
   }



   static function task_definitionselection_PluginFusioninventoryIPRange_networkinventory($title) {
      $rand = PluginFusioninventoryStaticmisc::task_definitionselection_PluginFusioninventoryIPRange_networkdiscovery($title);
      return $rand;
   }



   static function task_definitionselection_NetworkEquipment_networkinventory($title) {

      $options = array();
      $options['entity'] = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name'] = 'definitionselectiontoadd';
      $rand = Dropdown::show("NetworkEquipment", $options);
      return $rand;
   }



   static function task_definitionselection_Printer_networkinventory($title) {

      $options = array();
      $options['entity'] = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name'] = 'definitionselectiontoadd';
      $rand = Dropdown::show("Printer", $options);
      return $rand;
   }



   static function task_networkdiscovery_agents() {

      $array = array();
      $array["-.1"] = __('Auto managenement dynamic of agents', 'fusioninventory');

      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      $array1 = $pfAgentmodule->getAgentsCanDo('NETWORKDISCOVERY');
      foreach ($array1 as $id => $data) {
         $array["PluginFusioninventoryAgent-".$id] =
                 __('Auto managenement dynamic of agents', 'fusioninventory')." - ".$data['name'];
      }
      return $array;
   }



   # Actions with itemtype autorized
   static function task_action_networkinventory() {
      $a_itemtype = array();
      $a_itemtype[] = "Printer";
      $a_itemtype[] = "NetworkEquipment";
      $a_itemtype[] = 'PluginFusioninventoryIPRange';

      return $a_itemtype;
   }



   # Selection type for actions
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



   static function task_selection_type_networkdiscovery($itemtype) {
      $selection_type = '';
      switch ($itemtype) {

         case 'PluginFusioninventoryIPRange':
            $selection_type = 'iprange';
            break;

         // __('Auto managenement dynamic of agents', 'fusioninventory')


      }

      return $selection_type;
   }



   /*
    * Deploy definitions
    */

   static function task_definitiontype_deployinstall($a_itemtype) {
      return array('' => Dropdown::EMPTY_VALUE,
                   'PluginFusioninventoryDeployPackage' => __('Package'));
   }



   static function task_definitiontype_deployuninstall($a_itemtype) {
      return array('' => Dropdown::EMPTY_VALUE,
                   'PluginFusioninventoryDeployPackage' => __('Package'));
   }



   static function task_definitionselection_PluginFusioninventoryDeployPackage_deployinstall() {
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'definitionselectiontoadd';
      return Dropdown::show("PluginFusioninventoryDeployPackage", $options);
   }



   static function task_definitionselection_PluginFusioninventoryDeployPackage_deployuninstall() {
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'definitionselectiontoadd';
      return Dropdown::show("PluginFusioninventoryDeployPackage", $options);
   }

   /*
    *  Deploy Actions
    */

   static function task_actiontype_deployinstall($a_itemtype) {
      return array('' => Dropdown::EMPTY_VALUE,
                   'Computer'                         => __('Computers'),
                   'PluginFusioninventoryDeployGroup' => __('Fusinv', 'fusioninventory'). ' - ' .__('Group'),
                   'Group'                            => __('Group')
                  );
   }



   static function task_actiontype_deployuninstall($a_itemtype) {
      return array('' => Dropdown::EMPTY_VALUE,
                   'Computer'                         => __('Computers'),
                   'PluginFusioninventoryDeployGroup' => __('Fusinv'). ' - ' .__('Group'),
                   'Group'                            => __('Group')

                  );
   }

   static function task_actionselection_Computer_deployinstall() {
      $options = array();
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      $options['condition']   =
         implode( " ",
            array(
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
            )
         );
      return Dropdown::show("Computer", $options);
   }



   static function task_actionselection_Computer_deployuninstall() {
      $options = array();
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      $options['condition']   =
         implode( " ",
            array(
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
            )
         );
      return Dropdown::show("Computer", $options);
   }



   static function task_actionselection_Group_deployinstall() {
      $options = array();
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      return Dropdown::show("Group", $options);
   }



   static function task_actionselection_Group_deployuninstall() {
      $options = array();
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      return Dropdown::show("Group", $options);
   }



   static function task_actionselection_PluginFusioninventoryDeployGroup_deployinstall() {
      $options = array();
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      return Dropdown::show("PluginFusioninventoryDeployGroup", $options);
   }



   static function task_actionselection_PluginFusioninventoryDeployGroup_deployuninstall() {
      $options = array();
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      return Dropdown::show("PluginFusioninventoryDeployGroup", $options);
   }



   static function task_deploy_getParameters($entities_id) {
      return array(
         "task" => "Deploy",
         "remote" => PluginFusioninventoryAgentmodule::getUrlForModule('Deploy', $entities_id)
      );
   }



   /*
    * Collect
    */
   static function task_definitiontype_collect($a_itemtype) {
      return array('' => Dropdown::EMPTY_VALUE,
                   'PluginFusioninventoryCollect' => __('Collect information', 'fusioninventory'));
   }



   static function task_definitionselection_PluginFusioninventoryCollect_collect() {
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'definitionselectiontoadd';
      return Dropdown::show("PluginFusioninventoryCollect", $options);
   }



   static function task_actiontype_collect($a_itemtype) {
      return array('' => Dropdown::EMPTY_VALUE,
                   'Computer'                         => __('Computers'),
                   'PluginFusioninventoryDeployGroup' => __('Dynamic Group'),
                   'Group'                            => __('Group')
                  );
   }



   static function task_actionselection_Computer_collect() {
      $options = array();
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      $options['condition']   =
         implode( " ",
            array(
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
            )
         );
      return Dropdown::show("Computer", $options);
   }



   static function task_actionselection_Group_collect() {
      $options = array();
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      return Dropdown::show("Group", $options);
   }



   static function task_actionselection_PluginFusioninventoryDeployGroup_collect() {
      $options = array();
      $options['entity']      = $_SESSION['glpiactive_entity'];
      $options['entity_sons'] = 1;
      $options['name']        = 'actionselectiontoadd';
      return Dropdown::show("PluginFusioninventoryDeployGroup", $options);
   }



   static function task_collect_getParameters($entities_id) {
      return array(
         "task" => "Collect",
         "remote" => PluginFusioninventoryAgentmodule::getUrlForModule('Collect', $entities_id)
      );
   }

}

?>
