<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

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

// ----------------------------------------------------------------------
// Original Author of file: DURIEUX David
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}

class PluginFusinvsnmpNetworkPortConnectionLog extends CommonDBTM {


   function migration() {
      global $DB;

      $ptsnmph = new PluginFusinvsnmpNetworkPortLog;

      $sql_connection = "SELECT * FROM `glpi_plugin_fusinvsnmp_networkportlogs`
                        WHERE `field`='0'
                        ORDER BY `plugin_fusioninventory_agentprocesses_id` DESC, `date_mod` DESC;";
      $result_connection = $DB->query($sql_connection);
      while ($thread_connection = $DB->fetch_array($result_connection)) {
         $input = array();
         $input['plugin_fusioninventory_agentprocesses_id'] = $thread_connection['plugin_fusioninventory_agentprocesses_id'];
         $input['date'] = $thread_connection['date_mod'];
         if (($thread_connection["old_device_ID"] != "0")
                 OR ($thread_connection["new_device_ID"] != "0")) {

            if ($thread_connection["old_device_ID"] != "0") {
               // disconnection
               $input['creation'] = '0';
            } else if ($thread_connection["new_device_ID"] != "0") {
               // connection
               $input['creation'] = '1';
            }
            $input['networkports_id_source'] = $thread_connection["networkports_id"];

            if ($thread_connection["old_device_ID"] != "0") {
               $queryPort = "SELECT *
                             FROM `glpi_networkports`
                             WHERE `mac`='".$thread_connection['old_value']."'
                             LIMIT 0,1;";
               $resultPort = $DB->query($queryPort);
               $dataPort = $DB->fetch_assoc($resultPort);
            } else if ($thread_connection["new_device_ID"] != "0") {
               $queryPort = "SELECT *
                             FROM `glpi_networkports`
                             WHERE `mac`='".$thread_connection['new_value']."'
                             LIMIT 0,1;";
               $resultPort = $DB->query($queryPort);
               $dataPort = $DB->fetch_assoc($resultPort);
            }
            $input['networkports_id_destination'] = $dataPort['id'];
            $this->add($input);
            $ptsnmph->deleteFromDB($thread_connection['id'], 1);
         }
      }
   }

   function showForm($input='') {
      global $DB,$LANG,$CFG_GLPI,$INFOFORM_PAGES;

      $np = new NetworkPort;

      echo "<table class='tab_cadre' cellpadding='5' width='950'>";
      echo "<tr class='tab_bg_1'>";

      echo "<th>";
      echo $LANG['plugin_fusioninventory']['processes'][1];
      echo " <a href='".GLPI_ROOT."/plugins/fusioninventory/front/agentprocess.form.php'>(".$LANG['common'][66].")</a>";
      echo "</th>";

      echo "<th>";
      echo $LANG['common'][27];
      echo "</th>";

      echo "<th>";
      echo $LANG['common'][1];
      echo "</th>";

      echo "<th>";
      echo $LANG['joblist'][0];
      echo "</th>";


      echo "<th>";
      echo $LANG['common'][1];
      echo "</th>";

      echo "</tr>";

      if (!isset($input['plugin_fusioninventory_agentprocesses_id'])) {
         $condition = '';
      } else {
         $condition = "WHERE `plugin_fusioninventory_agentprocesses_id`='".$input['plugin_fusioninventory_agentprocesses_id']."'";
         if (isset($input['created'])) {
            $condition .= " AND `creation`='".$input['created']."' ";
         }
      }
      $query = "SELECT * FROM `".$this->getTable()."`
         ".$condition."
         ORDER BY `date`DESC , `plugin_fusioninventory_agentprocesses_id` DESC";
      if (!isset($input['process_number'])) {
         $query .= " LIMIT 0,500";
      }

		if ($result = $DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            echo "<tr class='tab_bg_1 center'>";

            echo "<td>";
            echo "<a href='".GLPI_ROOT."/plugins/fusioninventory/front/agentprocess.form.php?h_process_number=".$data['plugin_fusioninventory_agentprocesses_id']."'>".
            $data['plugin_fusioninventory_agentprocesses_id']."</a>";
            echo "</td>";

            echo "<td>";
            echo convDateTime($data['date']);
            echo "</td>";

            echo "<td>";
            $np->getFromDB($data['networkports_id_source']);
            $item = new $np->fields["itemtype"];
            $item->getFromDB($np->fields["items_id"]);
            $link1 = $item->getLink(1);

            $link = "<a href=\"" . $CFG_GLPI["root_doc"] . "/front/networkport.form.php?id=" . $np->fields["id"] . "\">";
            if (rtrim($np->fields["name"]) != "")
               $link .= $np->fields["name"];
            else
               $link .= $LANG['common'][0];
            $link .= "</a>";
            echo $link." ".$LANG['networking'][25]." ".$link1;
            echo "</td>";

            echo "<td>";
            if ($data['creation'] == '1') {
               echo "<img src='".GLPI_ROOT."/plugins/fusioninventory/pics/connection_ok.png'/>";
            } else {
               echo "<img src='".GLPI_ROOT."/plugins/fusioninventory/pics/connection_notok.png'/>";
            }
            echo "</td>";

            echo "<td>";
            $np->getFromDB($data['networkports_id_destination']);
            $item = new $np->fields["itemtype"];
            $item->getFromDB($np->fields["items_id"]);
            $link1 = $item->getLink(1);
            $link = "<a href=\"" . $CFG_GLPI["root_doc"] . "/front/networkport.form.php?id=" . $np->fields["id"] . "\">";
            if (rtrim($np->fields["name"]) != "")
               $link .= $np->fields["name"];
            else
               $link .= $LANG['common'][0];
            $link .= "</a>";
            echo $link." ".$LANG['networking'][25]." ".$link1;
            echo "</td>";

            echo "</tr>";
         }
      }
      echo "</table>";
   }
  
}

?>
