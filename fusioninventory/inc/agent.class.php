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

class PluginFusioninventoryAgent extends CommonDBTM {
   
   public $dohistory = true;

   /**
   * Get name of this type
   *
   * @return text name of this type by language of the user connected
   *
   **/
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusioninventory']['agents'][28];
   }



   function canCreate() {
      return PluginFusioninventoryProfile::haveRight("fusioninventory", "agent", "w");
   }


   function canView() {
      return PluginFusioninventoryProfile::haveRight("fusioninventory", "agent", "r");
   }

   

   function getSearchOptions() {
      global $LANG;

      $tab = array();
    
      $tab['common'] = $LANG['plugin_fusioninventory']['agents'][28];

      $tab[1]['table']     = $this->getTable();
      $tab[1]['field']     = 'name';
      $tab[1]['linkfield'] = 'name';
      $tab[1]['name']      = $LANG['common'][16];
      $tab[1]['datatype']  = 'itemlink';

      $tab[2]['table']     = 'glpi_entities';
      $tab[2]['field']     = 'completename';
      $tab[2]['name']      = $LANG['entity'][0];

      $tab[3]['table']     = $this->getTable();
      $tab[3]['field']     = 'is_recursive';
      $tab[3]['linkfield'] = 'is_recursive';
      $tab[3]['name']      = $LANG['entity'][9];
      $tab[3]['datatype']  = 'bool';

      $tab[4]['table']     = $this->getTable();
      $tab[4]['field']     = 'last_contact';
      $tab[4]['linkfield'] = '';
      $tab[4]['name']      = $LANG['plugin_fusioninventory']['agents'][4];
      $tab[4]['datatype']  = 'datetime';

      $tab[5]['table']     = $this->getTable();
      $tab[5]['field']     = 'lock';
      $tab[5]['linkfield'] = 'lock';
      $tab[5]['name']      = $LANG['plugin_fusioninventory']['agents'][37];
      $tab[5]['datatype']  = 'bool';

      $tab[6]['table']     = $this->getTable();
      $tab[6]['field']     = 'device_id';
      $tab[6]['linkfield'] = 'device_id';
      $tab[6]['name']      = $LANG['plugin_fusioninventory']['agents'][35];
      $tab[6]['datatype']  = 'text';
      $tab[6]['massiveaction'] = false;

      $tab[7]['table']         = 'glpi_computers';
      $tab[7]['field']         = 'name';
      $tab[7]['linkfield']     = 'items_id';
      $tab[7]['name']          = $LANG['plugin_fusioninventory']['agents'][23];
      $tab[7]['datatype']      = 'itemlink';
      $tab[7]['itemlink_type'] = 'Computer';

      $tab[8]['table']     = $this->getTable();
      $tab[8]['field']     = 'version';
      $tab[8]['linkfield'] = 'version';
      $tab[8]['name']      = $LANG['plugin_fusioninventory']['agents'][25];
      $tab[8]['datatype']  = 'text';
      $tab[8]['massiveaction'] = false;

      $tab[9]['table']     = $this->getTable();
      $tab[9]['field']     = 'token';
      $tab[9]['linkfield'] = 'token';
      $tab[9]['name']      = $LANG['plugin_fusioninventory']['agents'][24];
      $tab[9]['datatype']  = 'text';
      $tab[9]['massiveaction'] = false;

      $tab[10]['table']     = $this->getTable();
      $tab[10]['field']     = 'useragent';
      $tab[10]['linkfield'] = 'useragent';
      $tab[10]['name']      = $LANG['plugin_fusioninventory']['agents'][42];
      $tab[10]['datatype']  = 'text';
      $tab[10]['massiveaction'] = false;
      
      $tab[11]['table']     = $this->getTable();
      $tab[11]['field']     = 'tag';
      $tab[11]['name']      = $LANG['plugin_fusioninventory']['rules'][16];
      $tab[11]['datatype']  = 'text';
      $tab[11]['massiveaction'] = false;
      

      $i = 20;
      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      $a_modules = $pfAgentmodule->find();
      foreach ($a_modules as $data) {
         $tab[$i]['table']         = $pfAgentmodule->getTable();
         $tab[$i]['field']         = $data["modulename"];
         $tab[$i]['linkfield']     = $data["modulename"];
         $tab[$i]['name']          = $LANG['plugin_fusioninventory']['task'][26]." - ".$data["modulename"];
         $tab[$i]['datatype']      = 'bool';
         $tab[$i]['massiveaction'] = false;
         $i++;
      }
      return $tab;
   }



   function defineTabs($options=array()){
      global $LANG;

      $ong = array();
      if ((isset($this->fields['id'])) AND ($this->fields['id'] > 0)){
         $ong[1]=$LANG['title'][26];
      }
      $ong[2] = $LANG['plugin_fusioninventory']['agents'][36];
      $ong[3] = $LANG['title'][38];
      return $ong;
   }

   
   
   /**
    * Display personalized comments (in tooltip) of item
    */
   function getComments() {
      global $LANG;

      $comment = $LANG['plugin_fusioninventory']['agents'][42].' : '.$this->fields['useragent'].'<br/>
         '.$LANG['plugin_fusioninventory']['agents'][4].' : '.Html::convDateTime($this->fields['last_contact']).' minutes';

      if (!empty($comment)) {
         return Html::showToolTip($comment, array('display' => false));
      }

      return $comment;
   }
   


   /**
   * Display form for agent configuration
   *
   * @param $items_id integer ID of the agent
   * @param $options array
   *
   * @return bool true if form is ok
   *
   **/
   function showForm($items_id, $options=array()) {
      global $LANG;

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
      echo "<td>".$LANG['plugin_fusioninventory']['agents'][23]."&nbsp:</td>";
      echo "<td align='center'>";
      if (($this->fields["items_id"] != "0") AND ($this->fields["items_id"] != "")) {
         $oComputer = new Computer();
         $oComputer->getFromDB($this->fields["items_id"]);
         echo $oComputer->getLink(1);
         echo "<input type='hidden' name='items_id' value='".$this->fields["items_id"]."'/>";
      } else {
         Computer_Item::dropdownConnect(COMPUTER_TYPE,COMPUTER_TYPE,'items_id', 
                                        $_SESSION['glpiactive_entity']);
      }
      echo "</td>";
      echo "<td>".$LANG['plugin_fusioninventory']['agents'][24]."&nbsp:</td>";
      echo "<td align='center'>";
      echo $this->fields["token"];
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['agents'][37]."&nbsp:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('lock', $this->fields["lock"]);
      echo "</td>";
      echo "<td>".$LANG['plugin_fusioninventory']['agents'][25]."&nbsp:</td>";
      echo "<td align='center'>";
      $a_versions = importArrayFromDB($this->fields["version"]);
      foreach ($a_versions as $module => $version) {
         echo "<strong>".$module. "</strong>: ".$version."<br/>";
      }
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['agents'][42]."&nbsp:</td>";
      echo "<td align='center'>";
      echo $this->fields["useragent"];
      echo "</td>";
      echo "<td>".$LANG['plugin_fusioninventory']['agents'][4]."&nbsp:</td>";
      echo "<td align='center'>";
      echo Html::convDateTime($this->fields["last_contact"]);
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusioninventory']['rules'][16]."&nbsp:</td>";
      echo "<td align='center'>";
      echo $this->fields["tag"];
      echo "</td>";
      echo "<td></td>";
      echo "<td align='center'>";
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
   * @return array all DB fields of this agent
   *
   **/
   function InfosByKey($device_id) {
      global $DB;

      $query = "SELECT * FROM `".$this->getTable()."`
      WHERE `device_id`='".$device_id."' LIMIT 1";

      $agent = array();
      $result = $DB->query($query);
      if ($result) {
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
   function importToken($pxml) {
      
      if (isset($pxml->DEVICEID)) {
         $pta = new PluginFusioninventoryAgent();
         $a_agent = $pta->find("`device_id`='".$pxml->DEVICEID."'", "", "1");
         if (empty($a_agent)) {
            $a_input = array();
            if (isset($pxml->TOKEN)) {
               $a_input['token'] = $pxml->TOKEN;
            }
            $a_input['name']         = $pxml->DEVICEID;
            $a_input['device_id']    = $pxml->DEVICEID;
            $a_input['entities_id']  = 0;
            $a_input['last_contact'] = date("Y-m-d H:i:s");
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
               $a_input['useragent']    = $_SERVER['HTTP_USER_AGENT'];
            }
            return $pta->add($a_input);
         } else {
            foreach ($a_agent as $data) {
               $input = array();
               $input['id'] = $data['id'];
               if (isset($pxml->TOKEN)) {
                  $input['token'] = $pxml->TOKEN;
               }
               $input['last_contact'] = date("Y-m-d H:i:s");
               if (isset($_SERVER['HTTP_USER_AGENT'])) {
                  $input['useragent']    = $_SERVER['HTTP_USER_AGENT'];
               }
               $pta->update($input);
               return $data['id'];
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
   * @return Array with all IP of this agent or computer
   *
   **/
   function getIPs($items_id, $type = 'Agent') {
      $ip = array();
      $Computers_id = 0;
      if ($type == 'Agent') {
         if ($this->getFromDB($items_id)) {
            $Computers_id = $this->fields['items_id'];
         } else {
            return array();
         }
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
   * @return agent id or False
   *
   **/
   function getAgentWithComputerid($items_id) {

      $agent = $this->find("`items_id`='".$items_id."'", "", 1);

      if (count($agent) == '1') {
         $data = current($agent);
         return $data['id'];
      }
      return false;
   }



   /**
   * Create links between agent and computer.
   *
   * @param $items_id integer ID of the computer
   * @param $device_id value of device_id from XML to identify agent
   *
   * @return Nothing
   *
   **/
   function setAgentWithComputerid($items_id, $device_id) {
      global $DB;

      $a_agent = $this->find("`items_id`='".$items_id."'", "", 1);
      // Is this computer already linked to an agent?
      if ($agent = array_shift($a_agent)) {

         // relation
         if ($agent['device_id'] != $device_id) {
            $input = array();
            $input['id'] = $agent['id'];
            $input['device_id'] = $device_id;
            $this->update($input);
         }

         // Clean up the agent list
         $oldAgents = $this->find(
            // computer linked to the wrong agent
            "(`items_id`='".$items_id."' AND `device_id` <> '".$device_id."')");
         foreach ($oldAgents as $oldAgent) {
            $this->delete($oldAgent);
         }
         $oldAgents = $this->find(
            // the same device_id but linked on the wrong computer 
            "(`device_id`='".$device_id."' AND `items_id`<>'".$items_id."')");
         foreach ($oldAgents as $oldAgent) {
            $input = array();
            $input['id'] = $agent['id'];
            $input['last_contact'] = $oldAgent['last_contact'];
            $input['version'] = $oldAgent['version'];
            $input['name'] = $oldAgent['name'];
            $input['useragent'] = $oldAgent['useragent'];
            $input['token'] = $oldAgent['token'];
            $this->update($input);            
            $this->delete($oldAgent);
         }
      } else { # This is a new computer
         // Link agent with computer
         $agent = $this->InfosByKey($device_id);
         if (isset($agent['id'])) {
             $agent['items_id'] = $items_id;
             $this->update($agent);
         }
      }
   }


   
   /**
   * Display if agent is online
   *
   * @return Nothing (display)
   *
   **/
   function forceRemoteAgent() {
      global $LANG,$CFG_GLPI;

      $agent_id = $this->getAgentWithComputerid($_POST['id']);

      if (!$agent_id) {
         return;
      }

      $pfTaskjob = new PluginFusioninventoryTaskjob();

      echo "<form method='post' name='' id=''  action=\"".$CFG_GLPI['root_doc'] . 
         "/plugins/fusioninventory/front/agent.form.php\">";
      echo "<table class='tab_cadre' width='500'>";
      
      echo "<tr>";
      echo "<th colspan='2'>";
      echo $LANG['plugin_fusioninventory']['agents'][15];
      echo "</th>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['plugin_fusioninventory']['agents'][28]."&nbsp:";
      echo "</td>";
      echo "<td>";
      $pFusioninventoryAgent = new PluginFusioninventoryAgent();
      $pFusioninventoryAgent->getFromDB($agent_id);
      echo $pFusioninventoryAgent->getLink(1);
      
      echo "</td>";
      echo "</tr>";      

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['state'][0]."&nbsp;:";
      echo "</td>";
      echo "<td>";

      $this->getFromDB($agent_id);
      $a_ip = $this->getIPs($_POST['id'], 'Computer');
      $waiting = 0;
      $ip = "";
      while(empty($ip) && count($ip)) {
         $ip = array_shift($a_ip);
      }

      $agentStatus = $pfTaskjob->getRealStateAgent($agent_id);
      switch($agentStatus) {

         case 'running':
            echo $LANG['plugin_fusioninventory']['taskjoblog'][6];
            break;

         case 'noanswer':
            echo $LANG['plugin_fusioninventory']['agents'][30];
            break;

         case 'waiting':
            $waiting = 1;
            echo $LANG['plugin_fusioninventory']['agents'][38];
            echo "<input type='hidden' name='ip' value='".$ip."' />";
            echo "<input type='hidden' name='agent_id' value='".$agent_id."' />";
            break;
         
         default:
            echo "SELinux problem, do 'setsebool -P httpd_can_network_connect on'";
            break;

      }

      echo "</td>";
      echo "</tr>";

      if ($waiting == '1') {
         echo "<tr>";
         echo "<th colspan='2'>";
         echo "<input name='startagent' value=\"".$LANG['plugin_fusioninventory']['agents'][31].
            "\" class='submit' type='submit'>";
         echo "</th>";
         echo "</tr>";
      }

      echo "</table>";
      Html::closeForm();
      echo "<br/>";
   }



   /**
   * Set agent version of each module
   *
   * @param $agent_id integer ID of the agent
   * @param $module value Module name (WAKEONLAN, NETDISCOVERY, INVENTORY, SNMPQUERY...)
   * @param $version value version of the module
   *
   * @return nothing
   *
   **/
   function setAgentVersions($agent_id, $module, $version) {
      $this->getFromDB($agent_id);
      $a_version = importArrayFromDB($this->fields['version']);
      if (!is_array($a_version)) {
         $versionTmp             = $a_version;
         $a_version              = array();
         $a_version["INVENTORY"] = $versionTmp;
      }
      $a_version[$module] = $version;
      $input = array();
      $input['id'] = $this->fields['id'];
      $input['version'] = exportArrayToDB($a_version);
      $this->update($input);
   }

   
   
   /**
    * Return an agent by it deviceid
    * 
    * @param device_id the device_id coming from the agent itself
    * 
    * @return an array with the agent's attributes or an empty array if no agent found
    */
   static function getByDeviceID($device_id) {
      $agents =  getAllDatasFromTable('glpi_plugin_fusioninventory_agents',
                                      "`device_id`='$device_id' AND `lock`='0'");
      if (!empty($agents)) {
         return array_pop($agents);
      } else {
         return false;
      }
   }
   
   
   
   /**
    * Get base URL to communicate with an agent
    * 
    * @param plugins_id ID of the fusioninventory plugin
    * @param ip agent's IP
    * 
    * @return a list of http url to contact the agent
    */
   static function getAgentBaseURLs($plugins_id, $agent_id) {
      $config  = new PluginFusioninventoryConfig();
      $pfAgent = new PluginFusioninventoryAgent();

      $ret = array();

      if ($pfAgent->getFromDB($agent_id)) {
         $computer = new Computer();
         $computer->getFromDB($pfAgent->fields['items_id']);
         if ($computer->fields["name"] && $computer->fields["name"] != "localhost") {
            array_push($ret, "http://".$computer->fields["name"].
               ":".$config->getValue($plugins_id, 'agent_port'));

            $domain = new Domain();
            $domain->getFromDB($computer->fields['domains_id']);
            array_push($ret, "http://".
               $computer->fields["name"].'.'.
               $domain->fields["name"].
               ":".$config->getValue($plugins_id, 'agent_port'));
         }
      }

      # Guess the machine name from the DEVICEID,
      # useful when Windows domain != DNS domain
      $stack = array();
      if(preg_match('/(\S+)-\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2}$/', $pfAgent->fields['name'], $stack)) {
         array_push($ret, "http://".$stack[1].":".$config->getValue($plugins_id, 'agent_port'));
      }

      $a_ips = $pfAgent->getIPs($agent_id);
      foreach ($a_ips as $ip) {
         if ($ip != '') {
            array_push($ret, "http://".$ip.":".$config->getValue($plugins_id, 'agent_port'));
         }
      }
      return $ret;
   }

   
   
   /**
    * URL to get agent's state
    * 
    * @param plugins_id ID of the fusioninventory plugin
    * @param ip agent's IP
    * 
    * @return an array of http url to get the agent's state
    */
   static function getAgentStatusURLs($plugins_id, $agent_id) {
      $ret = array();
      foreach (self::getAgentBaseURLs($plugins_id, $agent_id) as $url) {
         array_push($ret, $url."/status");
      }
      return $ret;
   }

   
   
   /**
    * URL to ask the agent to wake up
    * 
    * @param integer plugins_id ID of the fusioninventory plugin
    * @param interger agents_id agent id
    * 
    * @return an http url to ask the agent to wake up
    */
   static function getAgentRunURLs($plugins_id, $agent_id) {
      $pfAgent = new PluginFusioninventoryAgent();

      $pfAgent->getFromDB($agent_id);

      $ret = array();
      foreach (self::getAgentBaseURLs($plugins_id, $agent_id) as $url) {
         array_push($ret, $url."/now/".$pfAgent->fields['token']);
      }
      return $ret;
   }

   

   /**
    * Show configuration form of agent
    * 
    * @global type $LANG 
    * 
    */
   static function showConfig() {
      global $LANG;

      echo "<table width='950' class='tab_cadre_fixe'>";
      
      echo "<tr>";
      echo "<th colspan='2'>";
      echo $LANG['plugin_fusioninventory']['agents'][43];
      echo "</th>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td width='50%'>";
      $array = explode("/", $_SERVER['HTTP_REFERER']);
      $create_url = $array[0]."//".$array[2].  str_replace("front/wizard.php", "", $_SERVER['PHP_SELF']);
      echo $LANG['plugin_fusioninventory']['agents'][44]."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<strong>".$create_url."</strong>";
      
      echo "</td>";
      echo "</tr>";
      echo "</table>";
   }   
   
   
   
   /**
    * Disable data to put in table glpi_logs
    * 
    */   
   function pre_updateInDB() {
      if (isset($this->oldvalues['version'])
              AND $this->input['version'] == $this->oldvalues['version']) {
         
         $key = array_search('version', $this->updates);
         unset($this->updates[$key]);
         unset($this->oldvalues['version']);
      }
      if (isset($this->oldvalues['last_contact'])) {
         $key = array_search('last_contact', $this->updates);
         unset($this->oldvalues['last_contact']);
      }
      if (isset($this->oldvalues['token'])) {
         $key = array_search('token', $this->updates);
         unset($this->oldvalues['token']);
      }
   }
}

?>