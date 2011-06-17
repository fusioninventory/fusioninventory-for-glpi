<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusinvinventoryStaticmisc {

   /**
   * Get task methods of this plugin fusinvinventory
   *
   * @return array ('module'=>'value', 'method'=>'value')
   *   module value name of plugin
   *   method value name of method
   **/
   static function task_methods() {
      global $LANG;

      $methods[] =  array('module'         => 'fusinvinventory',
                          'method'         => 'inventory',
                          'selection_type' => 'devices',
                          'hidetask'       => 1,
                          'name'           => $LANG['Menu'][38],
                          'use_rest'       => false);
                          
     //if (PluginFusioninventoryCredential::hasAlLeastOneType()) {
     $methods[] = array('module'         => 'fusinvinventory',
                        'method'         => 'ESX',
                        'selection_type' => 'devices',
                        'name'           => $LANG['plugin_fusinvinventory']['title'][2],
                        'use_rest'       => true);
        //}
        return $methods;
   }

   /**
   * Display menu of this plugin
   *
   * @return array
   *
   **/
   static function displayMenu() {
      global $LANG;

      $a_menu = array();

      if (PluginFusioninventoryProfile::haveRight("fusinvinventory", "importxml", "r")) {
         $a_menu[0]['name'] = $LANG['plugin_fusinvinventory']['menu'][0];
         $a_menu[0]['pic']  = GLPI_ROOT."/plugins/fusinvinventory/pics/menu_importxml.png";
         $a_menu[0]['link'] = GLPI_ROOT."/plugins/fusinvinventory/front/importxml.php";
      }

      if (PluginFusioninventoryProfile::haveRight("fusinvinventory", "existantrule", "r")) {
         $a_menu[2]['name'] = $LANG['plugin_fusinvinventory']['menu'][3];
         $a_menu[2]['pic']  = GLPI_ROOT."/plugins/fusioninventory/pics/menu_rules.png";
         $a_menu[2]['link'] = GLPI_ROOT."/plugins/fusinvinventory/front/ruleentity.php";
      }

      if (PluginFusioninventoryProfile::haveRight("fusinvinventory", "blacklist", "r")) {
         $a_menu[3]['name'] = $LANG['plugin_fusinvinventory']['menu'][2];
         $a_menu[3]['pic']  = GLPI_ROOT."/plugins/fusinvinventory/pics/menu_blacklist.png";
         $a_menu[3]['link'] = GLPI_ROOT."/plugins/fusinvinventory/front/blacklist.php";
      }

      if (PluginFusioninventoryProfile::haveRight("fusinvinventory", "importxml","w")) {
         $a_menu[4]['name'] = $LANG['plugin_fusinvinventory']['menu'][4];
         $a_menu[4]['pic']  = GLPI_ROOT."/plugins/fusinvinventory/pics/menu_checkintegrity.png";
         $a_menu[4]['link'] = GLPI_ROOT."/plugins/fusinvinventory/front/libintegrity.php";
      }
      return $a_menu;
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

      return array(array('profil'  => 'existantrule',
                         'name'    => $LANG['plugin_fusinvinventory']['profile'][2]),
                   array('profil'  => 'importxml',
                         'name'    => $LANG['plugin_fusinvinventory']['profile'][3]),
                   array('profil'  => 'blacklist',
                         'name'    => $LANG['plugin_fusinvinventory']['profile'][4]),
                   array('profil'  => 'ESX',
                         'name'    => $LANG['plugin_fusinvinventory']['vmwareesx'][0]));
   }
   
   static function credential_types() {
     global $LANG;
 
     $tmp = array ('itemtype'  => 'PluginFusinvinventoryVmwareESX', //Credential itemtype
                   'name'      => $LANG['plugin_fusinvinventory']['vmwareesx'][0], //Label
                   'targets'   => array('Computer'));
                   
     return array($tmp);
   }

   //------------------------------------------ ---------------------------------------------//
   //------------------------------------------ TASKS --------------------------------------//
   //------------------------------------------ -------------------------------------------//

   //------------------------------------------ Selection---------------------------------//


   /**
   * Get types of datas available to select for taskjob definition for WakeOnLan method
   *
   * @param $a_itemtype array types yet added for definitions
   *
   * @return array ('itemtype'=>'value','itemtype'=>'value'...)
   *   itemtype itemtype of object
   *   value name of the itemtype
   **/
   static function task_definitiontype_ESX($a_itemtype) {
      return array ('' => DROPDOWN_EMPTY_VALUE, 
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
      global $DB, $LANG;

      $query = "SELECT `a`.`id`, `a`.`name` 
                FROM `glpi_plugin_fusioninventory_credentialips` as `a` 
                LEFT JOIN `glpi_plugin_fusioninventory_credentials` as `c` 
                   ON `c`.`id` = `a`.`plugin_fusioninventory_credentials_id` 
                WHERE `c`.`itemtype`='PluginFusinvinventoryVmwareESX'";
      $query.= getEntitiesRestrictRequest(' AND','glpi_plugin_fusioninventory_credentialips');
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
      return array ('' => DROPDOWN_EMPTY_VALUE, 
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
      $agent = new PluginFusioninventoryAgent();
      $exceptions = json_decode($module_infos['exceptions'],true);

      if (!empty($exceptions)) {
         $in = " AND `a`.`id` NOT IN (".implode($exceptions,',').")";
      } else {
         $in = "";
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

   //------------------------------------------ ---------------------------------------------//
   //------------------------------------------ REST PARAMS---------------------------------//
   //------------------------------------------ -------------------------------------------//

   /**
    * Get ESX task parameters to send to the agent
    * For the moment it's hardcoded, but in a future release it may be in DB
    * @return an array of parameters
    */
   static function task_ESX_getParameters() {
      global $CFG_GLPI;

      return array ('periodicity' => 3600, 'delayStartup' => 3600, 'task' => 'ESX', 
                    'remote' => PluginFusioninventoryAgentmodule::getUrlForModule('ESX'));
   }
}

?>