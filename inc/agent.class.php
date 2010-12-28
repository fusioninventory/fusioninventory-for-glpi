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

class PluginFusioninventoryAgent extends CommonDBTM {
   
   
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusioninventory']['agents'][28];
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


   function getSearchOptions() {
      global $LANG;

      $tab = array();
    
      $tab['common'] = $LANG['plugin_fusioninventory']["agents"][28];

		$tab[1]['table'] = $this->getTable();
		$tab[1]['field'] = 'name';
		$tab[1]['linkfield'] = 'name';
		$tab[1]['name'] = $LANG['common'][16];
		$tab[1]['datatype'] = 'itemlink';

		$tab[2]['table'] = $this->getTable();
		$tab[2]['field'] = 'last_contact';
		$tab[2]['linkfield'] = 'last_contact';
		$tab[2]['name'] = $LANG['plugin_fusioninventory']["agents"][4];
		$tab[2]['datatype'] = 'datetime';

		$tab[3]['table'] = $this->getTable();
		$tab[3]['field'] = 'lock';
		$tab[3]['linkfield'] = 'lock';
		$tab[3]['name'] = $LANG['plugin_fusioninventory']["agents"][6];
		$tab[3]['datatype'] = 'bool';

		$tab[4]['table'] = $this->getTable();
		$tab[4]['field'] = 'device_id';
		$tab[4]['linkfield'] = 'device_id';
		$tab[4]['name'] = 'Device_id';
		$tab[4]['datatype'] = 'text';

		$tab[5]['table'] = 'glpi_computers';
		$tab[5]['field'] = 'name';
		$tab[5]['linkfield'] = 'items_id';
		$tab[5]['name'] = $LANG['plugin_fusioninventory']["agents"][23];
		$tab[5]['datatype'] = 'itemlink';

		$tab[6]['table'] = $this->getTable();
		$tab[6]['field'] = 'version';
		$tab[6]['linkfield'] = 'version';
		$tab[6]['name'] = $LANG['plugin_fusioninventory']["agents"][25];
		$tab[6]['datatype'] = 'text';

		$tab[7]['table'] = $this->getTable();
		$tab[7]['field'] = 'token';
		$tab[7]['linkfield'] = 'token';
		$tab[7]['name'] = $LANG['plugin_fusioninventory']["agents"][24];
		$tab[7]['datatype'] = 'text';

      return $tab;
   }



   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $ong = array();
      if ((isset($this->fields['id'])) AND ($this->fields['id'] > 0)){
         $ong[1]=$LANG['title'][26];
      }
       $ong[2] = $LANG['plugin_fusioninventory']["agents"][27];
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

      $query = "SELECT * FROM `".$this->getTable()."`
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
            foreach ($a_agent as $data) {
               $input = array();
               $input['id'] = $data['id'];
               $input['token'] = $sxml->TOKEN;
               $input['last_contact'] = date("Y-m-d H:i:s");
               $pta->update($input);
            }
         }
      }
      return 1;
   }
   

   
   function getIPs($items_id, $type = 'Agent') {
      $ip = array();
      if ($type == 'agent') {
         $this->getFromDB($items_id);
         $Computers_id = $this->fields['items_id'];
      } else {
         $Computers_id = $items_id;
      }
      if ($Computers_id != "0") {
         $NetworkPort = new NetworkPort;
         $a_ports = $NetworkPort->find("`itemtype`='Computer'
                             AND `items_id`='".$Computers_id."'
                             AND `ip` IS NOT NULL");
         foreach($a_ports as $data) {
            if ($data['ip'] != '127.0.0.1') {
               $ip[] = $data['ip'];
            }
         }         
      }
      return $ip;
   }


   
   function getAgentWithComputerid($items_id) {

      $agent = $this->find("`itemtype`='Computer' AND `items_id`='".$items_id."'");

      if ($agent) {
         foreach($agent as $data) {
            return $data['id'];
         }
      }
      return false;
   }


   function setAgentWithComputerid($items_id, $device_id) {
      global $DB;

      // Reset if computer connected with an other agent
      $query = "UPDATE `".$this->getTable()."`
         SET `items_id`='0'
         WHERE `items_id`='".$items_id."'
            AND `device_id`!='".$device_id."' ";
      $DB->query($query);

      $agent = $this->InfosByKey($device_id);
      $agent['items_id'] = $items_id;
      $this->update($agent);
   }



   function forceRemoteAgent() {
      global $LANG;

      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();

      echo "<form method='post' name='' id=''  action=\"".GLPI_ROOT . "/plugins/fusioninventory/front/agent.form.php\">";
      echo "<table class='tab_cadre' width='500'>";
      
      echo "<tr>";
      echo "<th colspan='2'>";
      echo $LANG['plugin_fusioninventory']['agents'][15];
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['state'][0]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      $this->getFromDB($_POST['id']);
      $a_ip = $this->getIPs($_POST['id'], 'Computer');
      $waiting = 0;
      foreach($a_ip as $ip) {
         $agentStatus = $PluginFusioninventoryTaskjob->getStateAgent($ip,0);
         if ($agentStatus) {
            $waiting = 1;
            echo $LANG['plugin_fusioninventory']['agents'][22];
            echo "<input type='hidden' name='ip' value='".$ip."' />";
            echo "<input type='hidden' name='token' value='".$this->fields['token']."' />";
         }
      }
      if ($waiting == '0') {
         echo $LANG['plugin_fusioninventory']['agents'][30];
      }
      echo "</td>";
      echo "</tr>";

      if ($waiting == '1') {
         echo "<tr>";
         echo "<th colspan='2'>";
         echo "<input name='startagent' value='".$LANG['plugin_fusioninventory']['agents'][31]."' class='submit' type='submit'>";
         echo "</th>";
         echo "</tr>";
      }

      echo "</table>";
      echo "</form>";
      echo "<br/>";
   }


}

?>