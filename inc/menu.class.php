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


class PluginFusioninventoryMenu {

   /**
   * Display the menu of FusionInventory
   *
   *@param type value "big" or "mini"
   *
   *@return nothing
   **/
   static function displayMenu($type = "big") {
      global $LANG;

      // FOR THE BETA
      echo "<a href='http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/wiki/Beta_test'>
         <img src='".GLPI_ROOT."/plugins/fusioninventory/pics/betaIII.png'/></a><br/>";

      // END FOR THE BETA

      $width_status = 0;

      $a_menu = array();
      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "agent", "r")) {
         $a_menu[0]['name'] = $LANG['plugin_fusioninventory']['menu'][1];
         $a_menu[0]['pic']  = GLPI_ROOT."/plugins/fusioninventory/pics/menu_agents.png";
         $a_menu[0]['link'] = GLPI_ROOT."/plugins/fusioninventory/front/agent.php";
      }

      if(PluginFusioninventoryProfile::haveRight("fusioninventory", "remotecontrol","r")) {
         $a_menu[2]['name'] = $LANG['plugin_fusioninventory']['task'][1];
         $a_menu[2]['pic']  = GLPI_ROOT."/plugins/fusioninventory/pics/menu_task.png";
         $a_menu[2]['link'] = GLPI_ROOT."/plugins/fusioninventory/front/task.php";
      }

      if(PluginFusioninventoryProfile::haveRight("fusioninventory", "task","r")) {
         $a_menu[3]['name'] = $LANG['plugin_fusioninventory']['menu'][7];
         $a_menu[3]['pic']  = GLPI_ROOT."/plugins/fusioninventory/pics/menu_runningjob.png";
         $a_menu[3]['link'] = GLPI_ROOT."/plugins/fusioninventory/front/taskjob.php";
      }

      //if (PluginFusioninventoryProfile::haveRight("fusioninventory", "unknowndevice", "r")) {
         $a_menu[4]['name'] = $LANG['plugin_fusioninventory']['rules'][2];
         $a_menu[4]['pic']  = GLPI_ROOT."/plugins/fusioninventory/pics/menu_rules.png";
         $a_menu[4]['link'] = GLPI_ROOT."/plugins/fusioninventory/front/ruleimportequipment.php";
      //}


      if (PluginFusioninventoryProfile::haveRight("fusioninventory", "unknowndevice", "r")) {
         $a_menu[5]['name'] = $LANG['plugin_fusioninventory']['menu'][4];
         $a_menu[5]['pic']  = GLPI_ROOT."/plugins/fusioninventory/pics/menu_unknown_device.png";
         $a_menu[5]['link'] = GLPI_ROOT."/plugins/fusioninventory/front/unknowndevice.php";
      }

      echo "<div align='center'>";
      echo "<table width='950'>";
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
         $class = "Plugin".ucfirst($datas['directory'])."Staticmisc";
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
      echo "</div>";
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
      $height = 0;
      if ($type == "big") {
         $width="120";
         $height="100";
      } else if ($type == "mini") {
         $width="50";
         $height="40";
      }

      if (((count($a_menu) * $width) + $width_status) > $width_max) {
         $width_status = 0;
         echo "</td>";
         echo "</tr>";
         echo "</table>";
         echo "<table>";
         echo "<tr>";
         echo "<td>";
      } else {
         echo "</td>";
         echo "<td>";
      }
      $width_status = ((count($a_menu) * $width) + $width_status);

      echo "<table class='tab_cadre'>";

      echo "<tr>";
      echo "<th colspan='".count($a_menu)."' nowrap>".$LANG['plugin_'.$plugin_name]['title'][0]."</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      $i = 0;
      foreach ($a_menu as $menu_id) {         
         echo "<td align='center' width='".$width."' height='".$height."'>";
         if ($type == "big") {
            echo "<a href='".$menu_id['link']."'>
            <img src='".$menu_id['pic']."'/>
            <br/><b>".$menu_id['name']."</b></a>";
         } else if ($type == "mini") {
            $i++;
            $menu_id['pic'] = str_replace("/menu_","/menu_mini_" ,$menu_id['pic']);
            echo "<a href='".$menu_id['link']."'>
               <img src='".$menu_id['pic']."'
                onmouseout=\"cleanhide('".$plugin_name."_".$i."')\"
                onmouseover=\"cleandisplay('".$plugin_name."_".$i."')\" /></a>";
            echo "<span class='over_link' id='".$plugin_name."_".$i."'>".$menu_id['name']."</span>";
         }
         echo "</td>";
      }
      echo "</tr>";

      echo "</table>";

      return $width_status;
   }
}

?>