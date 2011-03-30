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
      return array( array('module'         => 'fusinvinventory',
                          'method'         => 'inventory',
                          'selection_type' => 'devices'),
                    array('module'         => 'fusioninventory',
                          'method'         => 'esx',
                          'selection_type' => 'devices'));
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
         $a_menu[4]['link'] = GLPI_ROOT."/plugins/fusinvinventory/front/libintegrity.form.php";
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

      $a_profil = array();
      $a_profil[] = array('profil'  => 'existantrule',
                          'name'    => $LANG['plugin_fusinvinventory']['profile'][2]);
      $a_profil[] = array('profil'  => 'importxml',
                          'name'    => $LANG['plugin_fusinvinventory']['profile'][3]);
      $a_profil[] = array('profil'  => 'blacklist',
                          'name'    => $LANG['plugin_fusinvinventory']['profile'][4]);

      return $a_profil;
   }
}

?>