<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}


class PluginFusioninventoryMenu {

   static function displayMenu($type = "big") {
      global $CFG_GLPI,$LANG;

      $width_status = 0;

      $a_menu = array();
//      if (PluginFusioninventoryProfile::haveRight("Fusioninventory", "agents", "r")) {
         $a_menu[0]['name'] = $LANG['plugin_fusioninventory']["menu"][1];
         $a_menu[0]['pic']  = GLPI_ROOT."/plugins/fusioninventory/pics/menu_agents.png";
         $a_menu[0]['link'] = $CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/agent.php";
//      }
//      if(PluginFusioninventoryProfile::haveRight("Fusioninventory", "agentprocesses","r")) {
         $a_menu[1]['name'] = $LANG['plugin_fusioninventory']["processes"][19];
         $a_menu[1]['pic']  = GLPI_ROOT."/plugins/fusioninventory/pics/menu_info_agents.png";
         $a_menu[1]['link'] = $CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/agentprocess.form.php";
//      }
//      if(PluginFusioninventoryProfile::haveRight("Fusioninventory", "remotecontrol","r")) {
         $a_menu[2]['name'] = $LANG['plugin_fusioninventory']["task"][1];
         $a_menu[2]['pic']  = GLPI_ROOT."/plugins/fusioninventory/pics/menu_task.png";
         $a_menu[2]['link'] = $CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/task.php";
//      }

      echo "<div align='center'>";
      echo "<table width='950'>";
      echo "<tr>";
      echo "<td>";
      $width_status = PluginFusioninventoryMenu::htmlMenu("fusioninventory", $a_menu, $type, $width_status);

      // Get menu from plugins fusinv...
      $a_modules = PluginFusioninventoryModule::getAll();
      foreach ($a_modules as $module_id=>$datas) {
         $a_menu = call_user_func("plugin_".$datas['name']."_displayMenu");
         $width_status = PluginFusioninventoryMenu::htmlMenu($datas['name'], $a_menu, $type, $width_status);
      }
      echo "</td>";
      echo "</tr>";
      echo "</table>";
      echo "</div>";
   }


   static function htmlMenu($plugin_name, $a_menu = array(), $type = "big", $width_status) {
      global $LANG;

      $width_max = 950;

      if ($type == "big") {
         $width="140";
         $height="120";
      } else if ($type == "mini") {
         $width="50";
         $height="40";
      }

      if (((count($a_menu) * $width) + $width_status) > $width_max) {
         $width_status = 0;
         echo "</td>";
         echo "</tr>";
         echo "<tr>";
         echo "<td>";
      } else {
         echo "</td>";
         echo "<td>";
      }
      $width_status = ((count($a_menu) * $width) + $width_status);

      echo "<table class='tab_cadre'>";

      echo "<tr>";
      echo "<th colspan='4'>".$LANG['plugin_'.$plugin_name]["title"][0]."</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      foreach ($a_menu as $menu_id) {
         echo "<td align='center' width='".$width."' height='".$height."'>";
         echo "<a href='".$menu_id['link']."'>
         <img src='".$menu_id['pic']."'/>
         <br/><b>".$menu_id['name']."</b></a>";

         echo "</td>";
      }
      echo "</tr>";

      echo "</table>";

      return $width_status;
   }
}

?>