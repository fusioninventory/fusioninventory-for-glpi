<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2010 by the INDEPNET Development Team.

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
// Original Author of file: MAZZONI Vincent
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginFusioninventoryDisplay extends CommonDBTM {
   static function menu() {
      global $CFG_GLPI,$LANG;

      $width="150";

      echo "<br>";
      echo "<div align='center'>
         <table class='tab_cadre'>";

      echo "<tr><th colspan='4'>".$LANG['plugin_fusioninventory']["title"][0]."</th></tr>";

      echo "<tr class='tab_bg_1'><td align='center' width='".$width."' height='130'>";
      if(PluginFusioninventory::haveRight("snmp_models","r")) {
         echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/plugin_fusioninventory.models.php'>
            <img src='".GLPI_ROOT."/plugins/fusioninventory/pics/menu_models.png'/>
            <br/><b>".$LANG['plugin_fusioninventory']["model_info"][4]."</b></a>";
      }
      echo "</td>";

      echo "<td align='center' width='".$width."' height='130'>";
      if(PluginFusioninventory::haveRight("snmp_authentification","r")) {
         echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/plugin_fusioninventory.snmp_auth.php'>
            <img src='".GLPI_ROOT."/plugins/fusioninventory/pics/menu_authentification.png'/>
            <br/><b>".$LANG['plugin_fusioninventory']["model_info"][3]."</b></a>";
      }
      echo "</td>";

      echo "<td align='center' width='".$width."' height='130'>";
      if(PluginFusioninventory::haveRight("rangeip","r")) {
         echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/plugin_fusioninventory.rangeip.php'>
         <img src='".GLPI_ROOT."/plugins/fusioninventory/pics/menu_rangeip.png'/>
         <br/><b>".$LANG['plugin_fusioninventory']["menu"][2]."</b></a>";
      }
      echo "</td>";

      echo "<td align='center' width='".$width."' height='130'>";
      if(PluginFusioninventory::haveRight("agents","r")) {
         echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/plugin_fusioninventory.agents.php'>
         <img src='".GLPI_ROOT."/plugins/fusioninventory/pics/menu_agents.png'/><br/>
         <b>".$LANG['plugin_fusioninventory']["menu"][1]."</b></a>";
      }
      echo "</td>";
      echo "</tr>";


      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' width='".$width."' height='130'>";
      if(PluginFusioninventory::haveRight("remotecontrol","r")) {
         echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/plugin_fusioninventory.task.php'>
            <img src='".GLPI_ROOT."/plugins/fusioninventory/pics/menu_task.png'/>
            <br/><b>".$LANG['plugin_fusioninventory']["task"][1]."</b></a>";
      }
      echo "</td>";

      echo "<td align='center' width='".$width."' height='130'>";
      if(PluginFusioninventory::haveRight("agentsprocesses","r")) {
         echo "<a href='".$CFG_GLPI["root_doc"].
                  "/plugins/fusioninventory/front/plugin_fusioninventory.agents.processes.form.php'>
         <img src='".GLPI_ROOT."/plugins/fusioninventory/pics/menu_info_agents.png'/>
         <br/><b>".$LANG['plugin_fusioninventory']["processes"][19]."</b></a>";
      }
      echo "</td>";

      echo "<td align='center' width='".$width."' height='130'>";
      if(PluginFusioninventory::haveRight("unknowndevices","r")) {
         echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/plugin_fusioninventory.unknown.php'>
            <img src='".GLPI_ROOT."/plugins/fusioninventory/pics/menu_unknown_device.png'/>
            <br/><b>".$LANG['plugin_fusioninventory']["menu"][4]."</b></a>";
      }
      echo "</td>";

      echo "<td align='center' width='".$width."' height='130'>";
      if(PluginFusioninventory::haveRight("reports","r")) {
         echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/plugin_fusioninventory.report.php'>
         <img src='".GLPI_ROOT."/plugins/fusioninventory/pics/menu_rapports.png'/>
         <br/><b>".$LANG['plugin_fusioninventory']["processes"][20]."</b></a>";
      }
      echo "</td>";
      echo "</tr>";

      echo "</table></div>";

   }

   static function mini_menu() {
      global $CFG_GLPI,$LANG;

      $width="50";

      echo "<div align='center'>
         <table class='tab_cadre'>";

   //	echo "<tr><th colspan='8'>".$LANG['plugin_fusioninventory']["menu"][3]."</th></tr>";

      echo "<tr class='tab_bg_1'><td align='center' width='".$width."' height='40'>";
      if(PluginFusioninventory::haveRight("snmp_models","r")) {
         echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/plugin_fusioninventory.models.php'>
            <img src='".GLPI_ROOT."/plugins/fusioninventory/pics/menu_mini_models.png'
                onmouseout=\"cleanhide('menu_mini_models')\"
                onmouseover=\"cleandisplay('menu_mini_models')\" /></a>";
      }
      echo "<span class='over_link' id='menu_mini_models'>".$LANG['plugin_fusioninventory']["model_info"][4].
           "</span>";
      echo "</td>";
      echo "<td align='center' width='".$width."' height='40'>";
      if(PluginFusioninventory::haveRight("snmp_authentification","r")) {
         echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/plugin_fusioninventory.snmp_auth.php'>
            <img src='".GLPI_ROOT."/plugins/fusioninventory/pics/menu_mini_authentification.png'
                onmouseout=\"cleanhide('menu_mini_authentification')\"
                onmouseover=\"cleandisplay('menu_mini_authentification')\" /></a>";
      }
      echo "<span class='over_link' id='menu_mini_authentification'>".
           $LANG['plugin_fusioninventory']["model_info"][3]."</span>";
      echo "</td>";
      echo "<td align='center' width='".$width."' height='40'>";
      if(PluginFusioninventory::haveRight("rangeip","r")) {
         echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/plugin_fusioninventory.rangeip.php'>
         <img src='".GLPI_ROOT."/plugins/fusioninventory/pics/menu_mini_rangeip.png'
                onmouseout=\"cleanhide('menu_mini_rangeip')\"
                onmouseover=\"cleandisplay('menu_mini_rangeip')\" /></a>";
      }
      echo "<span class='over_link' id='menu_mini_rangeip'>".$LANG['plugin_fusioninventory']["menu"][2].
           "</span>";
      echo "</td>";
      echo "<td align='center' width='".$width."' height='40'>";
      if(PluginFusioninventory::haveRight("agents","r")) {
         echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/plugin_fusioninventory.agents.php'>
         <img src='".GLPI_ROOT."/plugins/fusioninventory/pics/menu_mini_agents.png'
                onmouseout=\"cleanhide('menu_mini_agents')\"
                onmouseover=\"cleandisplay('menu_mini_agents')\" /></a>";
      }
      echo "<span class='over_link' id='menu_mini_agents'>".$LANG['plugin_fusioninventory']["menu"][1].
           "</span>";
      echo "</td>";
      echo "<td align='center' width='".$width."' height='40'>";
      if(PluginFusioninventory::haveRight("remotecontrol","r")){
         echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/plugin_fusioninventory.task.php'>
            <img src='".GLPI_ROOT."/plugins/fusioninventory/pics/menu_mini_task.png'
                onmouseout=\"cleanhide('menu_mini_task')\"
                onmouseover=\"cleandisplay('menu_mini_task')\" /></a>";
      }
      echo "<span class='over_link' id='menu_mini_task'>".
               $LANG['plugin_fusioninventory']["task"][1]."</span>";
      echo "</td>";
      echo "<td align='center' width='".$width."' height='40'>";
      if(PluginFusioninventory::haveRight("agentsprocesses","r")) {
         echo "<a href='".$CFG_GLPI["root_doc"].
            "/plugins/fusioninventory/front/plugin_fusioninventory.agents.processes.form.php'>
         <img src='".GLPI_ROOT."/plugins/fusioninventory/pics/menu_mini_info_agents.png'
                onmouseout=\"cleanhide('menu_mini_info_agents')\"
                onmouseover=\"cleandisplay('menu_mini_info_agents')\" /></a>";
      }
      echo "<span class='over_link' id='menu_mini_info_agents'>".
               $LANG['plugin_fusioninventory']["processes"][19]."</span>";
      echo "</td>";
      echo "<td align='center' width='".$width."' height='40'>";
      if(PluginFusioninventory::haveRight("unknowndevices","r")) {
         echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/plugin_fusioninventory.unknown.php'>
            <img src='".GLPI_ROOT."/plugins/fusioninventory/pics/menu_mini_unknown_device.png'
                onmouseout=\"cleanhide('menu_mini_unknown')\"
                onmouseover=\"cleandisplay('menu_mini_unknown')\" /></a>";
      }
      echo "<span class='over_link' id='menu_mini_unknown'>".$LANG['plugin_fusioninventory']["menu"][4].
           "</span>";
      echo "</td>";
      echo "<td align='center' width='".$width."' height='40'>";
      if(PluginFusioninventory::haveRight("reports","r")) {
         echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/fusioninventory/front/plugin_fusioninventory.report.php'>
         <img src='".GLPI_ROOT."/plugins/fusioninventory/pics/menu_mini_rapports.png'
                onmouseout=\"cleanhide('menu_mini_rapports')\"
                onmouseover=\"cleandisplay('menu_mini_rapports')\" /></a>";
      }
      echo "<span class='over_link' id='menu_mini_rapports'>".$LANG['plugin_fusioninventory']["processes"][20].
           "</span>";
      echo "</td>";

      echo "</tr>";

      echo "</table></div>";

      echo "<table>
         <tr>
            <td height='2'></td>
         </tr>
      </table>";

   }

   static function mib_management() {
      global $DB,$CFG_GLPI,$LANG;

      $query = "SELECT *
                FROM `glpi_plugin_fusioninventory_mib`
                ORDER BY `FK_model_infos`;";
      $result = $DB->query($query);
      $number = $DB->numrows($result);

      if($number !="0") {
         echo "<div align='center'><table class='tab_cadre_fixe'>";
         echo "<tr><th colspan='4'>".$LANG['plugin_fusioninventory']["model_info"][5]." :</th></tr>";
         echo "<tr><th>".$LANG["common"][16]."</th>";
         echo "<th>".$LANG['plugin_fusioninventory']["mib"][1]."</th>";
         echo "<th>".$LANG['plugin_fusioninventory']["mib"][2]."</th>";
         echo "<th>".$LANG['plugin_fusioninventory']["mib"][3]."</th>";
         echo "</tr>";

         while ($data=$DB->fetch_array($result)) {
            echo "<tr class='tab_bg_1'>";
            echo "<td align='center'><a href=''><b>
               ".Dropdown::getDropdownName("glpi_plugin_fusioninventory_model_infos",$data["FK_model_infos"]).
               "</b></a></td>";
            echo "<td align='center'>
               ".Dropdown::getDropdownName("glpi_plugin_fusioninventory_mib_label",$data["FK_mib_oid"])."</td>";
            echo "<td align='center'>
               ".Dropdown::getDropdownName("glpi_plugin_fusioninventory_mib_object",$data["FK_mib_object"]).
               "</td>";
            echo "<td align='center'>
               ".Dropdown::getDropdownName("glpi_plugin_fusioninventory_mib_oid",$data["FK_mib_oid"])."</td>";
            echo "</tr>";

         }
         echo "</table></div>";
      } else {
         echo "<div align='center'><table class='tab_cadre_fixe'>";
         echo "<tr><th colspan='3'>".$LANG['plugin_fusioninventory']["model_info"][5].":</th></tr>";
         echo "<tr><th>".$LANG["common"][16]."</th>";
         echo "<th>".$LANG["login"][6]."</th>";
         echo "<th>".$LANG["login"][7]."</th>";
         echo "</tr>";
         echo "</table></div>";
      }
   }

   static function bar($pourcentage, $message="",$order='') {
      if ((!empty($pourcentage)) AND ($pourcentage < 0)) {
         $pourcentage = "";
      } else if ((!empty($pourcentage)) AND ($pourcentage > 100)) {
         $pourcentage = "";
      }
      echo "<div>
               <table class='tab_cadre' width='400'>
                  <tbody>
                     <tr>
                        <td align='center' width='400'>";

      if ((!empty($pourcentage)) OR ($pourcentage == "0")) {
         echo $pourcentage."% ".$message;
      }

      echo						"</td>
                     </tr>
                     <tr>
                        <td>
                           <div>
                           <table cellpadding='0' cellspacing='0'>
                              <tbody>
                                 <tr>
                                    <td width='400' height='0' colspan='2'></td>
                                 </tr>
                                 <tr>";
      if (empty($pourcentage)) {
         echo "<td></td>";
      } else {
         echo "										<td bgcolor='";
         if ($order!= '') {
            if ($pourcentage > 80) {
               echo "red";
            } else if($pourcentage > 60) {
               echo "orange";
            } else {
               echo "green";
            }
         } else {
            if ($pourcentage < 20) {
               echo "red";
            } else if($pourcentage < 40) {
               echo "orange";
            } else {
               echo "green";
            }
         }
         if ($pourcentage == 0) {
            echo "' height='20' width='1'>&nbsp;</td>";
         } else {
            echo "' height='20' width='".(4 * $pourcentage)."'>&nbsp;</td>";
         }
      }
      if ($pourcentage == 0) {
         echo "									<td height='20' width='1'></td>";
      } else {
         echo "									<td height='20' width='".(400 - (4 * $pourcentage))."'></td>";
      }
      echo "								</tr>
                              </tbody>
                           </table>
                        </td>
                     </tr>
                  </tbody>
               </table>
            </div>";
   }

   static function disableDebug() {
      error_reporting(0);
      set_error_handler("plugin_fusioninventory_empty");
   }

   static function reenableusemode() {
      if ($_SESSION['glpi_use_mode']==DEBUG_MODE){
         ini_set('display_errors','On');
         error_reporting(E_ALL | E_STRICT);
         set_error_handler("userErrorHandler");
      }
   }

   static function emptyDisplay() {

   }
}

?>