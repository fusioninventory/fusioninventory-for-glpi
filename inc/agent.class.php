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

class PluginFusioninventoryAgent extends CommonDBTM {
   

   /**
   * Get name of this type
   *
   *@return text name of this type by language of the user connected
   *
   **/
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
    
      $tab['common'] = $LANG['plugin_fusioninventory']['agents'][28];

		$tab[1]['table'] = $this->getTable();
		$tab[1]['field'] = 'name';
		$tab[1]['linkfield'] = 'name';
		$tab[1]['name'] = $LANG['common'][16];
		$tab[1]['datatype'] = 'itemlink';

		$tab[2]['table'] = $this->getTable();
		$tab[2]['field'] = 'last_contact';
		$tab[2]['linkfield'] = 'last_contact';
		$tab[2]['name'] = $LANG['plugin_fusioninventory']['agents'][4];
		$tab[2]['datatype'] = 'datetime';

		$tab[3]['table'] = $this->getTable();
		$tab[3]['field'] = 'lock';
		$tab[3]['linkfield'] = 'lock';
		$tab[3]['name'] = $LANG['plugin_fusioninventory']['agents'][6];
		$tab[3]['datatype'] = 'bool';

		$tab[4]['table'] = $this->getTable();
		$tab[4]['field'] = 'device_id';
		$tab[4]['linkfield'] = 'device_id';
		$tab[4]['name'] = $LANG['plugin_fusioninventory']['agents'][35];
		$tab[4]['datatype'] = 'text';

		$tab[5]['table'] = 'glpi_computers';
		$tab[5]['field'] = 'name';
		$tab[5]['linkfield'] = 'items_id';
		$tab[5]['name'] = $LANG['plugin_fusioninventory']['agents'][23];
		$tab[5]['datatype'] = 'itemlink';
      $tab[5]['itemlink_type']  = 'Computer';

		$tab[6]['table'] = $this->getTable();
		$tab[6]['field'] = 'version';
		$tab[6]['linkfield'] = 'version';
		$tab[6]['name'] = $LANG['plugin_fusioninventory']['agents'][25];
		$tab[6]['datatype'] = 'text';

		$tab[7]['table'] = $this->getTable();
		$tab[7]['field'] = 'token';
		$tab[7]['linkfield'] = 'token';
		$tab[7]['name'] = $LANG['plugin_fusioninventory']['agents'][24];
		$tab[7]['datatype'] = 'text';

      return $tab;
   }



   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI;

      $ong = array();
      if ((isset($this->fields['id'])) AND ($this->fields['id'] > 0)){
         $ong[1]=$LANG['title'][26];
      }
       $ong[2] = $LANG['plugin_fusioninventory']['agents'][27];
      return $ong;
   }



   /**
   * Display form for agent configuration
   *
   * @param $items_id integer ID of the agent
   * @param $options array
   *
   *@return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;

      if ($items_id!='') {
         $this->getFromDB($items_id);
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
      echo "<td>".$LANG['plugin_fusioninventory']['agents'][35]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo $this->fields["device_id"];
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['agents'][23]." :</td>";
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
      echo "<td>".$LANG['plugin_fusioninventory']['agents'][24]." :</td>";
      echo "<td align='center'>";
      echo $this->fields["token"];
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['agents'][6]." :</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('lock', $this->fields["lock"]);
      echo "</td>";
      echo "<td>".$LANG['plugin_fusioninventory']['agents'][25]." :</td>";
      echo "<td align='center'>";
      $a_versions = importArrayFromDB($this->fields["version"]);
      foreach ($a_versions as $module => $version) {
         echo "<strong>".$module. "</strong>: ".$version."<br/>";
      }
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td></td>";
      echo "<td align='center'>";
      echo "</td>";
      echo "<td>".$LANG['plugin_fusioninventory']['agents'][4]." :</td>";
      echo "<td align='center'>";
      echo convDateTime($this->fields["last_contact"]);
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }



   /**
   * Get agent informations by device_id
   *
   * @param $device_id value device_id unique of agent (key)
   *
   *@return array all DB fields of this agent
   *
   **/
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



   /**
   * Import agent : create if not exist and update if yet exist
   *
   * @param $p_xml simpleXMLobject
   *
   **/
   function importToken($p_xml) {
      $sxml = @simplexml_load_string($p_xml,'SimpleXMLElement', LIBXML_NOCDATA);

      if (isset($sxml->DEVICEID)) {
         $pta = new PluginFusioninventoryAgent();
         $a_agent = $pta->find("`device_id`='".$sxml->DEVICEID."'", "", "1");
         if (empty($a_agent)) {
            $a_input = array();
            if (isset($sxml->TOKEN)) {
               $a_input['token'] = $sxml->TOKEN;
            }
            $a_input['name'] = $sxml->DEVICEID;
            $a_input['device_id'] = $sxml->DEVICEID;
            $a_input['last_contact'] = date("Y-m-d H:i:s");
            $pta->add($a_input);
            return;
         } else {
            foreach ($a_agent as $data) {
               $input = array();
               $input['id'] = $data['id'];
               if (isset($sxml->TOKEN)) {
                  $input['token'] = $sxml->TOKEN;
               }
               $input['last_contact'] = date("Y-m-d H:i:s");
               $pta->update($input);
            }
         }
      }
      return;
   }
   


   /**
   * Get all IP of an agent or a computer
   *
   * @param $items_id integer ID of the agent
   * @param $type 'Agent' by default to get IP of agent or of a computer if set other text
   *
   *@return Array with all IP of this agent or computer
   *
   **/
   function getIPs($items_id, $type = 'Agent') {
      $ip = array();
      $Computers_id = 0;
      if ($type == 'Agent') {
         $this->getFromDB($items_id);
         $Computers_id = $this->fields['items_id'];
      } else {
         $Computers_id = $items_id;
      }
      if ($Computers_id != "0") {
         $NetworkPort = new NetworkPort();
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



   /**
   * Get agent id of a computer
   *
   * @param $items_id integer ID of the computer
   *
   *@return agent id or False
   *
   **/
   function getAgentWithComputerid($items_id) {

      $agent = $this->find("`items_id`='".$items_id."'");

      if ($agent) {
         $data = current($agent);
         return $data['id'];
      }
      return false;
   }



   /**
   * Make link between agent and computer
   *
   * @param $items_id integer ID of the computer
   * @param $device_id value of device_id from XML to identify agent
   *
   *@return Nothing
   *
   **/
   function setAgentWithComputerid($items_id, $device_id) {
      global $DB;

      // Reset if computer connected with an other agent
      $query = "UPDATE `".$this->getTable()."`
         SET `items_id`='0'
         WHERE `items_id`='".$items_id."'
            AND `device_id`!='".$device_id."' ";
      $DB->query($query);

      // Link agent with computer
      $agent = $this->InfosByKey($device_id);
      if (isset($agent['id'])) {
         $agent['items_id'] = $items_id;
         $this->update($agent);
      }
   }


   
   /**
   * Display if agent is online
   *
   *@return Nothing (display)
   *
   **/
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
      $agent_id = $this->getAgentWithComputerid($_POST['id']);
      $this->getFromDB($agent_id);
      $a_ip = $this->getIPs($_POST['id'], 'Computer');
      $waiting = 0;
      foreach($a_ip as $ip) {
         $agentStatus = $PluginFusioninventoryTaskjob->getStateAgent($ip,0);
         if ($agentStatus) {
            if ($waiting == '0') {
               $waiting = 1;
               echo $LANG['plugin_fusioninventory']['agents'][22];
               echo "<input type='hidden' name='ip' value='".$ip."' />";
               echo "<input type='hidden' name='agent_id' value='".$agent_id."' />";
               break;
            }
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
         echo "<input name='startagent' value=\"".$LANG['plugin_fusioninventory']['agents'][31]."\" class='submit' type='submit'>";
         echo "</th>";
         echo "</tr>";
      }

      echo "</table>";
      echo "</form>";
      echo "<br/>";
   }



   /**
   * Set agent version of each module
   *
   * @param $agent_id integer ID of the agent
   * @param $module value Module name (WAKEONLAN, NETDISCOVERY, INVENTORY, SNMPQUERY...)
   * @param $version value version of the module
   *
   *@return nothing
   *
   **/
   function setAgentVersions($agent_id, $module, $version) {
      $this->getFromDB($agent_id);
      $a_version = importArrayFromDB($this->fields['version']);
      if (!is_array($a_version)) {
         $versionTmp = $a_version;
         $a_version = array();
         $a_version["INVENTORY"] = $versionTmp;
      }
      $a_version[$module] = $version;
      $this->fields['version'] = exportArrayToDB($a_version);
      $this->update($this->fields);
   }

}

?>