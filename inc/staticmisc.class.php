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

class PluginFusinvinventoryStaticmisc {
   static function task_methods() {
      global $LANG;

      $a_tasks = array();
      $a_tasks[] = array('module'         => 'fusinvinventory',
                         'method'         => 'inventory',
                         'selection_type' => 'devices');

      return $a_tasks;
   }

   static function displayMenu() {
      global $LANG;

      $a_menu = array();

      if (PluginFusioninventoryProfile::haveRight("fusinvinventory", "importxml", "r")) {
         $a_menu[0]['name'] = $LANG['plugin_fusinvinventory']['menu'][0];
         $a_menu[0]['pic']  = GLPI_ROOT."/plugins/fusinvinventory/pics/menu_importxml.png";
         $a_menu[0]['link'] = GLPI_ROOT."/plugins/fusinvinventory/front/importxml.php";
      }

      if (PluginFusioninventoryProfile::haveRight("fusinvinventory", "existantrule", "r")) {
         $a_menu[1]['name'] = $LANG['plugin_fusinvinventory']['menu'][1];
         $a_menu[1]['pic']  = GLPI_ROOT."/plugins/fusinvinventory/pics/menu_rules.png";
         $a_menu[1]['link'] = GLPI_ROOT."/plugins/fusinvinventory/front/ruleinventory.php";
      }

      if (PluginFusioninventoryProfile::haveRight("fusinvinventory", "existantrule", "r")) {
         $a_menu[2]['name'] = $LANG['plugin_fusinvinventory']['menu'][3];
         $a_menu[2]['pic']  = GLPI_ROOT."/plugins/fusinvinventory/pics/menu_rules.png";
         $a_menu[2]['link'] = GLPI_ROOT."/plugins/fusinvinventory/front/ruleentity.php";
      }

      if (PluginFusioninventoryProfile::haveRight("fusinvinventory", "blacklist", "r")) {
         $a_menu[3]['name'] = $LANG['plugin_fusinvinventory']['menu'][2];
         $a_menu[3]['pic']  = GLPI_ROOT."/plugins/fusinvinventory/pics/menu_blacklist.png";
         $a_menu[3]['link'] = GLPI_ROOT."/plugins/fusinvinventory/front/blacklist.form.php";
      }

      return $a_menu;
   }


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