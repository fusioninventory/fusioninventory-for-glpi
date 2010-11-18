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

class PluginFusioninventoryAgent extends CommonDBTM {
   
   function __construct() {
      $this->table = "glpi_plugin_fusioninventory_agents";
      $this->type = 'PluginFusioninventoryAgent';
   }


   
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusioninventory']["agents"][26];
   }

   function canCreate() {
      return true;
   }

   function canView() {
      return true;
   }

   function canCancel() {
      return true;
   }

   function canUndo() {
      return true;
   }

   function canValidate() {
      return true;
   }



   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $ptc = new PluginFusioninventoryConfig;

      $ong = array();
      if ((isset($this->fields['id'])) AND ($this->fields['id'] > 0)){
         $ong[1]=$LANG['plugin_fusioninventory']["agents"][9];
      }
       $ong[2] = "activation modules"; //activation des modules
      // $ong[3] = actions (tâches)
      return $ong;
   }


   function showForm($id, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;

      if ($id!='') {
         $this->getFromDB($id);
      } else {
         $this->getEmpty();
      }

      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][16]." :</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' value='".$this->fields["name"]."' size='30'/>";
      echo "</td>";
      echo "<td>Device_id :</td>";
      echo "<td align='center'>";
      echo $this->fields["device_id"];
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']["agents"][23]." :</td>";
      echo "<td align='center'>";
      if (($this->fields["items_id"] != "0") AND ($this->fields["items_id"] != "")) {
         $oComputer = new Computer();
         $oComputer->getFromDB($this->fields["items_id"]);
         echo $oComputer->getLink(1);
         echo "<input type='hidden' name='items_id' value='".$this->fields["items_id"]."'/>";
      } else {
         Computer_Item::dropdownConnect(COMPUTER_TYPE,COMPUTER_TYPE,'items_id', $_SESSION['glpiactive_entity']);
      }
      echo "</td>";
      echo "<td>".$LANG['plugin_fusioninventory']["agents"][24]." :</td>";
      echo "<td align='center'>";
      echo $this->fields["token"];
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']["agents"][6]." :</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('lock', $this->fields["lock"]);
      echo "</td>";
      echo "<td>".$LANG['plugin_fusioninventory']["agents"][25]." :</td>";
      echo "<td align='center'>";
      echo $this->fields["version"];
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td></td>";
      echo "<td align='center'>";
      echo "</td>";
      echo "<td>".$LANG['plugin_fusioninventory']["agents"][4]." :</td>";
      echo "<td align='center'>";
      echo convDateTime($this->fields["last_contact"]);
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }



   function InfosByKey($device_id) {
      global $DB;

      $query = "SELECT * FROM `".$this->table."`
      WHERE `device_id`='".$device_id."' LIMIT 1";

      $agent = array();
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            $agent = $DB->fetch_assoc($result);
         }
      }
      return $agent;
   }



   function importToken($p_xml) {
      $sxml = @simplexml_load_string($p_xml,'SimpleXMLElement', LIBXML_NOCDATA);

      if ((isset($sxml->DEVICEID)) AND (isset($sxml->TOKEN))) {
         $pta = new PluginFusioninventoryAgent;
         $a_agent = $pta->find("`device_id`='".$sxml->DEVICEID."'", "", "1");
         if (empty($a_agent)) {
            $a_input = array();
            $a_input['token'] = $sxml->TOKEN;
            $a_input['name'] = $sxml->DEVICEID;
            $a_input['device_id'] = $sxml->DEVICEID;
            $a_input['last_contact'] = date("Y-m-d H:i:s");
            $pta->add($a_input);
            return 2;
         } else {
            foreach ($a_agent as $id_agent=>$dataInfos) {
               $input = array();
               $input['id'] = $id_agent;
               $input['token'] = $sxml->TOKEN;
               $input['last_contact'] = date("Y-m-d H:i:s");
               $pta->update($input);
            }
         }
      }
      return 1;
   }
   

   
   function getIPs($items_id) {
      $ip = array();
      $this->getFromDB($items_id);
      if ($this->fields['items_id'] != "0") {
         $NetworkPort = new NetworkPort;
         $a_ports = $NetworkPort->find("`itemtype`='Computer'
                             AND `items_id`='".$this->fields['items_id']."'
                             AND `ip` IS NOT NULL");
         foreach($a_ports as $ports_id=>$data) {
            $ip[] = $data['ip'];
         }         
      }
      return $ip;
   }


   
   function getAgentWithComputerid($items_id) {

      $agent = $this->find("`itemtype`='Computer' AND `items_id`='".$items_id."'");

      if ($agent) {
         foreach($agent as $agent_id=>$data) {
            return $agent_id;
         }
      }
      return false;
   }


   function setAgentWithComputerid($items_id, $device_id) {
      global $DB;

      // Reset if computer connected with an other agent
      $query = "UPDATE `".$this->table."`
         SET `items_id`='0'
         WHERE `items_id`='".$items_id."'
            AND `device_id`!='".$device_id."' ";
      $DB->query($query);

      $agent = $this->InfosByKey($device_id);
      $agent['items_id'] = $items_id;
      $this->update($agent);
   }

}

?>