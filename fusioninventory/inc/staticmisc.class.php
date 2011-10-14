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
                         'name'                 => $LANG['plugin_fusioninventory']['profile'][5]);

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
      global $LANG;

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
      global $LANG;

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
         if (is_callable(array('Plugin'.ucfirst($data['directory']).'Staticmisc', 'task_methods'))) {
            $a_methods = array_merge($a_methods, 
               call_user_func(array('Plugin'.ucfirst($data['directory']).'Staticmisc', 'task_methods')));
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

      $a_profil = array();
      $a_profil[] = array('profil'  => 'agent',
                          'name'    => $LANG['plugin_fusioninventory']['profile'][2]);
      $a_profil[] = array('profil'  => 'remotecontrol',
                          'name'    => $LANG['plugin_fusioninventory']['profile'][3]);
      $a_profil[] = array('profil'  => 'configuration',
                          'name'    => $LANG['plugin_fusioninventory']['profile'][4]);
      $a_profil[] = array('profil'  => 'wol',
                          'name'    => $LANG['plugin_fusioninventory']['profile'][5]);
      $a_profil[] = array('profil'  => 'unknowndevice',
                          'name'    => $LANG['plugin_fusioninventory']['profile'][6]);
      $a_profil[] = array('profil'  => 'task',
                          'name'    => $LANG['plugin_fusioninventory']['profile'][7]);

      return $a_profil;
   }

}

?>