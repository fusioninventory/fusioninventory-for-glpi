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

class PluginFusionInventoryHistoryConnections extends CommonDBTM {

   function __construct() {
		$this->table = "glpi_plugin_fusioninventory_snmp_history_connections";
	}

   function migration() {
      global $DB;

      $ptsnmph = new PluginFusionInventorySNMPHistory;

      $sql_connection = "SELECT * FROM `glpi_plugin_fusioninventory_snmp_history`
                        WHERE `Field`='0'
                        ORDER BY `FK_process` DESC, `date_mod` DESC;";
      $result_connection = $DB->query($sql_connection);
      while ($thread_connection = $DB->fetch_array($result_connection)) {
         $input = array();
         $input['process_number'] = $thread_connection['FK_process'];
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
            $input['FK_port_source'] = $thread_connection["FK_ports"];

            if ($thread_connection["old_device_ID"] != "0") {
               $queryPort = "SELECT *
                             FROM `glpi_networking_ports`
                             WHERE `ifmac`='".$thread_connection['old_value']."'
                             LIMIT 0,1;";
               $resultPort = $DB->query($queryPort);
               $dataPort = $DB->fetch_assoc($resultPort);
            } else if ($thread_connection["new_device_ID"] != "0") {
               $queryPort = "SELECT *
                             FROM `glpi_networking_ports`
                             WHERE `ifmac`='".$thread_connection['new_value']."'
                             LIMIT 0,1;";
               $resultPort = $DB->query($queryPort);
               $dataPort = $DB->fetch_assoc($resultPort);
            }
            $input['FK_port_destination'] = $dataPort['ID'];
            $this->add($input);
            $ptsnmph->deleteFromDB($thread_connection['ID'], 1);
         }
      }
   }

   function showForm($input='') {
      global $DB,$LANG,$CFG_GLPI,$INFOFORM_PAGES;

      $CommonItem = new CommonItem;
      $np = new Netport;

      echo "<table class='tab_cadre' cellpadding='5' width='950'>";
      echo "<tr class='tab_bg_1'>";

      echo "<th>";
      echo $LANG['plugin_fusioninventory']["processes"][1];
      echo " <a href='".GLPI_ROOT."/plugins/fusioninventory/front/plugin_fusioninventory.agents.processes.form.php'>(".$LANG['common'][66].")</a>";
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

      if (!isset($input['process_number'])) {
         $condition = '';
      } else {
         $condition = "WHERE `process_number`='".$input['process_number']."'";
         if (isset($input['created'])) {
            $condition .= " AND `creation`='".$input['created']."' ";
         }
      }
      $query = "SELECT * FROM `".$this->table."`
         ".$condition."
         ORDER BY `date`DESC , `process_number` DESC";
		if ($result = $DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
            echo "<tr class='tab_bg_1 center'>";

            echo "<td>";
            echo "<a href='".GLPI_ROOT."/plugins/fusioninventory/front/plugin_fusioninventory.agents.processes.form.php?h_process_number=".$data['process_number']."'>".
            $data['process_number']."</a>";
            echo "</td>";

            echo "<td>";
            echo convDateTime($data['date']);
            echo "</td>";

            echo "<td>";
            $np->getFromDB($data['FK_port_source']);
            $CommonItem->getFromDB($np->fields["device_type"],
                                   $np->fields["on_device"]);
            $link1 = $CommonItem->getLink(1);

            $link = "<a href=\"" . $CFG_GLPI["root_doc"] . "/front/networking.port.php?ID=" . $np->fields["ID"] . "\">";
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
            $np->getFromDB($data['FK_port_destination']);
            $CommonItem->getFromDB($np->fields["device_type"],
                                   $np->fields["on_device"]);
            $link1 = $CommonItem->getLink(1);
            $link = "<a href=\"" . $CFG_GLPI["root_doc"] . "/front/networking.port.php?ID=" . $np->fields["ID"] . "\">";
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