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

class PluginTrackerAgentsInventoryState extends CommonDBTM {

   function __construct() {
		$this->table = "glpi_plugin_tracker_agents_inventory_state";
	}

   function computerState($target, $ID) {
      global $DB, $LANG;

      $np = new Netport;
      $pta = new PluginTrackerAgents;

      echo "<br/>";
      echo "<div align='center'>";
      echo "<form method='post' name='' id=''  action=\"" . $target . "\">";
		echo "<table  class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th colspan='2'>";
      echo $LANG['plugin_tracker']["agents"][15];
      echo "</th>";
      echo "</tr>";

      $a_datas = $this->find("`device_id`='".$ID."'", "", "1");
      if (empty($a_datas)) {
         // Ajouter une entrée
         $this->fields['device_id'] = $ID;
         $this->fields['date_mod'] = date("Y-m-d H:i:s");
         $data['ID'] = $this->addToDB();
         $data['date_mod'] = $this->fields['date_mod'];
         $data['state'] = 0;
      } else {
         // Afficher l'état
         foreach($a_datas as $device_id=>$values) {
            $data = $a_datas[$device_id];
         }
      }
      echo "<tr class='tab_bg_1'>";
      echo "<td align='center'>";
      echo convDateTime($data['date_mod']);
      echo " : </td>";
      echo "<td align='center'>";
      switch ($data['state']) {
         
         case 0:
            echo $LANG['plugin_tracker']["agents"][16];
            break;

         case 1:
            echo $LANG['plugin_tracker']["agents"][22];
            break;

         case 2:
            echo $LANG['plugin_tracker']["agents"][17];
            break;

         case 3:
            echo $LANG['plugin_tracker']["agents"][18];
            break;

         case 4:
            echo $LANG['plugin_tracker']["agents"][19];
            break;

         case 5:
            echo $LANG['plugin_tracker']["agents"][20];
            break;

         case 6:
            echo $LANG['plugin_tracker']["agents"][21];
            break;

         default:
            break;
      }

      echo "</td>";
      echo "</tr>";

      $ip = "";
      if (($data['state'] == 0) OR ($data['state'] == 6)) {
         $a_data = $np->find("`on_device`='".$ID."' AND `device_type`='1'");
         foreach ($a_data as $port_id=>$port) {
            echo "<tr class='tab_bg_1'>";
            echo "<td align='center'>";
            if(!($fp = fsockopen($port['ifaddr'], 62354, $errno, $errstr, 1))) {
                echo $port['ifaddr']." : </td><td align='center'><b>".$LANG['plugin_tracker']["task"][9]."</b>";
            } else {
               echo $port['ifaddr']." : </td><td align='center'><b>".$LANG['plugin_tracker']["task"][8]."</b>";
               $ip = $port['ifaddr'];
               fclose($fp);
            }
            echo "</td>";
            echo "</tr>";
         }
      }

      echo "<tr class='tab_bg_2'>";
		echo "<td align='center' colspan='2'>";
      $a_datasagent = $pta->find("`on_device`='".$ID."' AND `device_type`='1' ", "", "1");
      if (!empty($a_datasagent)) {
         foreach ($a_datasagent as $agent_id=>$dataagent) {
            echo "<input type='hidden' name='agentID' value='".$agent_id."'/>";
         }
      }
      
      echo "<input type='hidden' name='ID' value='".$ID."'/>";
      echo "<input type='hidden' name='ip' value='".$ip."'/>";
      
      echo "<input type='submit' name='startagent' value=\"".$LANG['plugin_tracker']["task"][12]."\" class='submit' >";
      echo "</td>";
      echo "</tr>";
      
      echo "</table>";
      echo "</form>";
      echo "</div>";

      $glpiroot = GLPI_ROOT."/plugins/tracker/front/";
      if (strstr($_SERVER["PHP_SELF"], "tracker")) {
         $glpiroot = '../plugins/tracker/front/';
      }

      if (($data['state'] > 0) AND ($data['state'] < 6)) {
      echo "<script type='text/javascript'>
Ext.getCmp('tracker_1').getUpdater().startAutoRefresh(3,'".$glpiroot."plugin_tracker.agents.state.php?ID=".$ID."');
      
      </script>";
      } else {
      echo "<script type='text/javascript'>
Ext.getCmp('tracker_1').getUpdater().stopAutoRefresh();

      </script>";
      }
   }
   

   function changeStatus($device_id, $newstate) {
      global $DB;

      $query = "SELECT * FROM `".$this->table."`
      WHERE `device_id`='".$device_id."'";

      $agent = array();
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) == 1) {
            $data = $DB->fetch_assoc($result);
            $a_input['ID'] = $data['ID'];
            $a_input['date_mod'] = date("Y-m-d H:i:s");
            $a_input['state'] = $newstate;
            $this->update($a_input);
         }
      }
   }
}

?>