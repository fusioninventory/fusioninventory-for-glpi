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
      global $LANG;

      $a_tasks = array();
      $a_tasks[] = array('module'               => 'fusioninventory',
                         'method'               => 'wakeonlan',
                         'name'                 => $LANG['plugin_fusioninventory']['profile'][5],
                         'use_rest'             => false);
      
      $a_tasks[] =  array('module'         => 'fusioninventory',
                          'method'         => 'inventory',
                          'selection_type' => 'devices',
                          'hidetask'       => 1,
                          'name'           => $LANG['Menu'][38],
                          'use_rest'       => false);
                          
      $a_tasks[] = array('module'        => 'fusioninventory',
                         'method'         => 'ESX',
                         'selection_type' => 'devices',
                         'name'           => $LANG['plugin_fusinvinventory']['title'][2],
                         'use_rest'       => true);
      
      return $a_tasks;
   }

   

   /**
   * Get types of datas available to select for taskjob definition for WakeOnLan method
   *
   * @param $a_itemtype array types yet added for definitions
   *
   * @return array ('itemtype'=>'value','itemtype'=>'value'...)
   *   itemtype itemtype of object
   *   value name of the itemtype
   **/
   static function task_definitiontype_wakeonlan($a_itemtype) {

      $a_itemtype['Computer'] = Computer::getTypeName();

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
   * Get all profiles defined for this plugin
   *
   * @return array [integer] array('profile'=>'value', 'name'=>'value')
   *   profile value profile name
   *   name value description name (LANG) of the profile
   *
   **/
   static function profiles() {
      global $LANG;

      return array(array('profil'  => 'agent',
                         'name'    => $LANG['plugin_fusioninventory']['profile'][2]),
                   array('profil'  => 'remotecontrol',
                         'name'    => $LANG['plugin_fusioninventory']['profile'][3]),
                   array('profil'  => 'configuration',
                         'name'    => $LANG['plugin_fusioninventory']['profile'][4]),
                   array('profil'  => 'wol',
                         'name'    => $LANG['plugin_fusioninventory']['profile'][5]),
                   array('profil'  => 'unknowndevice',
                         'name'    => $LANG['plugin_fusioninventory']['profile'][6]),
                   array('profil'  => 'task',
                         'name'    => $LANG['plugin_fusioninventory']['task'][18]),
                   array('profil'  => 'iprange',
                         'name'    => $LANG['plugin_fusioninventory']['menu'][2]),
                   array('profil'  => 'credential',
                         'name'    => $LANG['plugin_fusioninventory']['menu'][5]),
                   array('profil'  => 'credentialip',
                         'name'    => $LANG['plugin_fusioninventory']['menu'][6]),
          
          
                   array('profil'  => 'existantrule',
                         'name'    => $LANG['plugin_fusinvinventory']['profile'][2]),
                   array('profil'  => 'importxml',
                         'name'    => $LANG['plugin_fusinvinventory']['profile'][3]),
                   array('profil'  => 'blacklist',
                         'name'    => $LANG['plugin_fusinvinventory']['profile'][4]),
                   array('profil'  => 'ESX',
                         'name'    => $LANG['plugin_fusinvinventory']['vmwareesx'][0]));
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
   * @return array ('itemtype'=>'value','itemtype'=>'value'...)
   *   itemtype itemtype of object
   *   value name of the itemtype
   **/
   static function task_definitiontype_ESX($a_itemtype) {
      return array ('' => Dropdown::EMPTY_VALUE , 
                    'PluginFusioninventoryCredentialIp' => PluginFusioninventoryCredentialIp::getTypeName());
   }

   
   
   /**
   * Get all devices of definition type 'Computer' defined in task_definitiontype_wakeonlan
   *
   * @param $title value ???(not used I think)
   *
   * @return dropdown list of computers
   *
   **/
   static function task_definitionselection_PluginFusioninventoryCredentialIp_ESX($title) {
      global $DB;

      $query = "SELECT `a`.`id`, `a`.`name` 
                FROM `glpi_plugin_fusioninventory_credentialips` as `a` 
                LEFT JOIN `glpi_plugin_fusioninventory_credentials` as `c` 
                   ON `c`.`id` = `a`.`plugin_fusioninventory_credentials_id` 
                WHERE `c`.`itemtype`='PluginFusinvinventoryVmwareESX'";
      $query.= getEntitiesRestrictRequest(' AND','a');
      $results = $DB->query($query);

      $agents = array();
      //$agents['.1'] = $LANG['common'][66];
      while ($data = $DB->fetch_array($results)) {
         $agents[$data['id']] = $data['name'];
      }
      if (!empty($agents)) {
         return Dropdown::showFromArray('definitionselectiontoadd',$agents);
      }
   }


   //------------------------------------------ Actions-------------------------------------//

   static function task_actiontype_ESX($a_itemtype) {
      global $LANG;
      return array ('' => Dropdown::EMPTY_VALUE , 
                    'PluginFusioninventoryAgent' => $LANG['plugin_fusioninventory']['profile'][2]);
   }

   
   
   /**
   * Get all devices of definition type 'Computer' defined in task_definitiontype_wakeonlan
   *
   * @return dropdown list of computers
   *
   **/
   static function task_actionselection_PluginFusioninventoryCredentialIp_ESX() {
      global $DB;

      $options = array();
      $options['name'] = 'definitionactiontoadd';

      $module = new PluginFusioninventoryAgentmodule();
      $module_infos = $module->getActivationExceptions('esx');
      $exceptions = json_decode($module_infos['exceptions'],true);

      $in = "";
      if (!empty($exceptions)) {
         $in = " AND `a`.`id` NOT IN (".implode($exceptions,',').")";
      }

      $query = "SELECT `a`.`id`, `a`.`name` 
                FROM `glpi_plugin_fusioninventory_credentialips` as `a` 
                LEFT JOIN `glpi_plugin_fusioninventory_credentials` as `c` 
                   ON `c`.`id` = `a`.`plugin_fusioninventory_credentials_id` 
                WHERE `c`.`itemtype`='PluginFusioninventoryVmwareESX'";
      $query.= getEntitiesRestrictRequest(' AND','glpi_plugin_fusioninventory_credentialips');
      
      $results = $DB->query($query);
      $credentialips = array();
      while ($data = $DB->fetch_array($results)) {
         $credentialips[$data['id']] = $data['name'];
      }
      return Dropdown::showFromArray('actionselectiontoadd',$credentialips);
   }
   

   
   static function task_actionselection_PluginFusioninventoryAgent_ESX() {
      
      $array = array();
      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule();
      $array1 = $PluginFusioninventoryAgentmodule->getAgentsCanDo(strtoupper("ESX"));
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
   static function task_ESX_getParameters() {

      return array ('periodicity' => 3600, 'delayStartup' => 3600, 'task' => 'ESX', 
                    'remote' => PluginFusioninventoryAgentmodule::getUrlForModule('ESX'));
   }

   
}

?>