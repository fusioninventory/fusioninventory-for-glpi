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
   die("Sorry. You can't access directly to this file");
}


class PluginFusioninventoryMenu {

   /**
   * Display the menu of FusionInventory
   *
   *@param type value "big" or "mini"
   *
   *@return nothing
   **/
   static function displayMenu($type = "big") {
      global $LANG,$CFG_GLPI;

      if (PLUGIN_FUSIONINVENTORY_OFFICIAL_RELEASE != 1) {
         echo "<center>";
         echo "<a href='http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/wiki/Beta_test'>";
         echo "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/beta_1.png'/></a>";
         echo "&nbsp;<a href='https://www.transifex.net/projects/p/FusionInventory/'>";
         echo "<img src='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/Translate.png'/></a>";
         echo "</center><br/>";
      }

      $width_status = 0;

      $a_menu = array();
      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "agent", "r")) {
         $a_menu[0]['name'] = $LANG['plugin_fusioninventory']['menu'][1];
         $a_menu[0]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_agents.png";
         $a_menu[0]['link'] = getItemTypeSearchURL('PluginFusioninventoryAgent');
      }

      if(PluginFusioninventoryProfile::haveRight("fusioninventory", "task","r")) {
         $a_menu[2]['name'] = $LANG['plugin_fusioninventory']['task'][1];
         $a_menu[2]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_task.png";
         $a_menu[2]['link'] = getItemTypeSearchURL('PluginFusioninventoryTask');
      }

      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "task","r")) {
         $a_menu[3]['name'] = $LANG['plugin_fusioninventory']['menu'][7];
         $a_menu[3]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_runningjob.png";
         $a_menu[3]['link'] = getItemTypeSearchURL('PluginFusioninventoryTaskJob');
      }

      if (haveRight("rule_ocs","r")) {
         $a_menu[4]['name'] = $LANG['plugin_fusioninventory']['rules'][2];
         $a_menu[4]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rules.png";
         $a_menu[4]['link'] = getItemTypeSearchURL('PluginFusioninventoryRuleImportEquipment');
      }


      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "unknowndevice", "r")) {
         $a_menu[5]['name'] = $LANG['plugin_fusioninventory']['menu'][4];
         $a_menu[5]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_unknown_device.png";
         $a_menu[5]['link'] = getItemTypeSearchURL('PluginFusioninventoryUnknownDevice');
      }

      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "iprange", "r")) {
         $a_menu[6]['name'] = $LANG['plugin_fusioninventory']['menu'][2];
         $a_menu[6]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_rangeip.png";
         $a_menu[6]['link'] = getItemTypeSearchURL('PluginFusioninventoryIPRange');
      }

      if (PluginFusioninventoryCredential::hasAlLeastOneType() 
            && PluginFusioninventoryProfile::haveRight("fusioninventory", "credential", "r")) {
         $a_menu[7]['name'] = $LANG['plugin_fusioninventory']['menu'][5];
         $a_menu[7]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_authentification.png";
         $a_menu[7]['link'] = getItemTypeSearchURL('PluginFusioninventoryCredential');

      }
      
      if (PluginFusioninventoryCredential::hasAlLeastOneType() 
            && PluginFusioninventoryProfile::haveRight("fusioninventory", "credentialip", "r")) {
         $a_menu[8]['name'] = $LANG['plugin_fusioninventory']['menu'][6];
         $a_menu[8]['pic']  = $CFG_GLPI['root_doc']."/plugins/fusioninventory/pics/menu_credentialips.png";
         $a_menu[8]['link'] = getItemTypeSearchURL('PluginFusioninventoryCredentialip');

      }

      echo "<div align='center' style='z-index: 1;position:absolute;width: 100%; margin: 0 auto;'>";
      echo "<table width='100%'>";

      echo "<tr>";
      echo "<td align='center'>";

      echo "<table>";
      echo "<tr>";
      echo "<td>";
      if (!empty($a_menu)) {
         $width_status = PluginFusioninventoryMenu::htmlMenu("fusioninventory", $a_menu, $type, 
                                                             $width_status);
      }

      // Get menu from plugins fusinv...
      $a_modules = PluginFusioninventoryModule::getAll();
      foreach ($a_modules as $datas) {
         $class = PluginFusioninventoryStaticmisc::getStaticMiscClass($datas['directory']);
         if (is_callable(array($class, "displayMenu"))) {
            $a_menu = call_user_func(array($class, "displayMenu"));
            if (!empty($a_menu)) {
               $width_status = PluginFusioninventoryMenu::htmlMenu($datas['directory'], $a_menu, 
                                                                   $type, $width_status);
            }
         }
      }
      echo "</td>";
      echo "</tr>";
      echo "</table>";

      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "</div><br/><br/><br/>";
   }


   
   /**
    * htmlMenu
    *
    *@param $plugin_name value Plugin directory (name)
    *@param $a_menu array menu of each module
    *@param $type value "big" or "mini"
    *@param $width_status integer width of space before and after menu position
    *
    *@return $width_status integer total width used by menu
    **/
   static function htmlMenu($plugin_name, $a_menu = array(), $type = "big", $width_status='300') {
      global $LANG;

      $width_max = 950;

      $width = 0;
      $width="230";
 
      if (($width + $width_status) > $width_max) {
         $width_status = 0;
         echo "</td>";
         echo "</tr>";
         echo "</table>";
         echo "<table>";
         echo "<tr>";
         echo "<td valign='top'>";
      } else {
         echo "</td>";
         echo "<td valign='top'>";
      }
      $width_status = ($width + $width_status);

      echo "<table class='tab_cadre'
         onMouseOver='document.getElementById(\"menu".$plugin_name."\").style.display=\"block\"'
         onMouseOut='document.getElementById(\"menu".$plugin_name."\").style.display=\"none\"'>";

      echo "<tr>";
      echo "<th colspan='".count($a_menu)."' nowrap width='".$width."'>
         &nbsp;".str_replace("FusionInventory ","",$LANG['plugin_'.$plugin_name]['title'][0])."&nbsp;</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1' id='menu".$plugin_name."' style='display:none'>";
      echo "<td>";

      echo "<table>";
      foreach ($a_menu as $menu_id) {
         echo "<tr>";
         $menu_id['pic'] = str_replace("/menu_","/menu_mini_",$menu_id['pic']);
         echo "<th>
               <img src='".$menu_id['pic']."' width='16' height='16'/></th>";
         echo "<th colspan='".(count($a_menu) - 1)."' width='190'>
                  <a href='".$menu_id['link']."'>".$menu_id['name']."</a></th>";
         echo "</tr>";
      }
      echo "</table>";

      echo "</td>";
      echo "</tr>";
      echo "</table>";

      return $width_status;
   }
}

?>
