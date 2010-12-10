<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org//
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory plugins.

   FusionInventory is free software; you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation; either version 2 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory; if not, write to the Free Software
   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
   ------------------------------------------------------------------------
 */

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryStaticmisc {
   static function task_methods() {
      global $LANG;

      $a_tasks = array();
      $a_tasks[] = array('module'               => 'fusioninventory',
                         'method'               => 'wakeonlan',
                         'selection_type'       => 'devices',
                         'selection_type_name'  => $LANG['common'][1]);
      $a_tasks[] = array('module'         => 'fusioninventory',
                         'method'         => 'wakeonlan',
                         'selection_type' => 'rules');
      $a_tasks[] = array('module'         => 'fusioninventory',
                         'method'         => 'wakeonlan',
                         'selection_type' => 'devicegroups');
      $a_tasks[] = array('module'         => 'fusioninventory',
                         'method'         => 'wakeonlan',
                         'selection_type' => 'fromothertasks');
      return $a_tasks;
   }

   static function getmethods() {
      $a_methods = call_user_func(array('PluginFusioninventoryStaticmisc', 'task_methods'));
      $a_modules = PluginFusioninventoryModule::getAll();
      foreach ($a_modules as $data) {
         if (is_callable(array('Plugin'.$data['directory'].'Staticmisc', 'task_methods'))) {
            $a_methods = array_merge($a_methods, 
               call_user_func(array('Plugin'.$data['directory'].'Staticmisc', 'task_methods')));
         }
      }
      return $a_methods;
   }

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