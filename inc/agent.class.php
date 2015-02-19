<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2014 by the FusionInventory Development Team.

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
   along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    David Durieux
   @co-author
   @copyright Copyright (c) 2010-2014 FusionInventory team
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

   public $dohistory = TRUE;

   static $rightname = 'plugin_fusioninventory_agent';

   /**
   * Get name of this type
   *
   * @return text name of this type by language of the user connected
   *
   **/
   static function getTypeName($nb=0) {
      return __('Agent', 'fusioninventory');
   }



   function getSearchOptions() {

      $tab = array();

      $tab['common'] = __('Agent', 'fusioninventory');

      $tab[1]['table']     = $this->getTable();
      $tab[1]['field']     = 'name';
      $tab[1]['linkfield'] = 'name';
      $tab[1]['name']      = __('Name');
      $tab[1]['datatype']  = 'itemlink';

      $tab[2]['table']     = 'glpi_entities';
      $tab[2]['field']     = 'completename';
      $tab[2]['name']      = __('Entity');

      $tab[3]['table']     = $this->getTable();
      $tab[3]['field']     = 'is_recursive';
      $tab[3]['linkfield'] = 'is_recursive';
      $tab[3]['name']      = __('Child entities');
      $tab[3]['datatype']  = 'bool';

      $tab[4]['table']     = $this->getTable();
      $tab[4]['field']     = 'last_contact';
      $tab[4]['linkfield'] = '';
      $tab[4]['name']      = __('Last contact', 'fusioninventory');
      $tab[4]['datatype']  = 'datetime';

      $tab[5]['table']     = $this->getTable();
      $tab[5]['field']     = 'lock';
      $tab[5]['linkfield'] = 'lock';
      $tab[5]['name']      = __('locked', 'fusioninventory');
      $tab[5]['datatype']  = 'bool';

      $tab[6]['table']     = $this->getTable();
      $tab[6]['field']     = 'device_id';
      $tab[6]['linkfield'] = 'device_id';
      $tab[6]['name']      = __('Device_id', 'fusioninventory');
      $tab[6]['datatype']  = 'text';
      $tab[6]['massiveaction'] = FALSE;

      $tab[7]['table']         = 'glpi_computers';
      $tab[7]['field']         = 'name';
      $tab[7]['name']          = __('Computer link', 'fusioninventory');
      $tab[7]['datatype']      = 'itemlink';
      $tab[7]['itemlink_type'] = 'Computer';
      $tab[7]['massiveaction'] = FALSE;

      $tab[8]['table']     = $this->getTable();
      $tab[8]['field']     = 'version';
      $tab[8]['linkfield'] = 'version';
      $tab[8]['name']      = __('Version');
      $tab[8]['datatype']  = 'text';
      $tab[8]['massiveaction'] = FALSE;

      $tab[9]['table']     = $this->getTable();
      $tab[9]['field']     = 'token';
      $tab[9]['linkfield'] = 'token';
      $tab[9]['name']      = __('Token');
      $tab[9]['datatype']  = 'text';
      $tab[9]['massiveaction'] = FALSE;

      $tab[10]['table']     = $this->getTable();
      $tab[10]['field']     = 'useragent';
      $tab[10]['linkfield'] = 'useragent';
      $tab[10]['name']      = __('Useragent', 'fusioninventory');
      $tab[10]['datatype']  = 'text';
      $tab[10]['massiveaction'] = FALSE;

      $tab[11]['table']     = $this->getTable();
      $tab[11]['field']     = 'tag';
      $tab[11]['name']      = __('FusionInventory tag', 'fusioninventory');
      $tab[11]['datatype']  = 'text';
      $tab[11]['massiveaction'] = FALSE;

      $tab[12]['table']     = $this->getTable();
      $tab[12]['field']     = 'threads_networkdiscovery';
      $tab[12]['name']      = __('Threads number', 'fusioninventory')."&nbsp;(".
                                 strtolower(__('Network discovery', 'fusioninventory')).
                                 ")";
      $tab[12]['datatype']  = 'integer';

      $tab[13]['table']     = $this->getTable();
      $tab[13]['field']     = 'threads_networkinventory';
      $tab[13]['name']      = __('Threads number', 'fusioninventory')."&nbsp;(".
                                 strtolower(__('Network inventory (SNMP)', 'fusioninventory')).
                                 ")";
      $tab[13]['datatype']  = 'integer';

      $tab[14]['table']     = $this->getTable();
      $tab[14]['field']     = 'agent_port';
      $tab[14]['linkfield'] = 'agent_port';
      $tab[14]['name']      = __('Agent port', 'fusioninventory');

      $i = 20;
      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      $a_modules = $pfAgentmodule->find();
      foreach ($a_modules as $data) {
         $tab[$i]['table']         = $pfAgentmodule->getTable();
         $tab[$i]['field']         = $data["modulename"];
         $tab[$i]['linkfield']     = $data["modulename"];
         $tab[$i]['name']          = __('Module', 'fusioninventory')." - ".$data["modulename"];
         $tab[$i]['datatype']      = 'bool';
         $tab[$i]['massiveaction'] = FALSE;
         $i++;
      }
      return $tab;
   }



   function defineTabs($options=array()){

      $ong = array();
      $this->addDefaultFormTab($ong);
      $this->addStandardTab('PluginFusioninventoryAgentmodule', $ong, $options);
      $this->addStandardTab('Log', $ong, $options);

      return $ong;
   }

   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $CFG_GLPI;
      $tab_names = array();
      if ( $this->can(0, CREATE) ) {
         if ($item->getType() == 'Computer') {
            $tab_names[] = __('FusInv', 'fusioninventory').' '. __('Agent');
         }

      }

      if (!empty($tab_names)) {
         return $tab_names;
      } else {
         return '';
      }
   }

   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType() == 'Computer') {

         // Possibility to remote agent
         if (PluginFusioninventoryToolbox::isAllowurlfopen(1)) {
            $pfAgent = new PluginFusioninventoryAgent();
            if ($pfAgent->getAgentWithComputerid($item->fields['id'])) {
               $pfAgent->showRemoteStatus($item);
            }
         }
      }
   }

   /**
    * Display personalized comments (in tooltip) of item
    */
   function getComments() {

      $comment = __('Useragent', 'fusioninventory').' : '.$this->fields['useragent'].'<br/>'.
         __('Last contact', 'fusioninventory').' : '.
         Html::convDateTime($this->fields['last_contact']).' minutes';

      if (!empty($comment)) {
         return Html::showToolTip($comment, array('display' => FALSE));
      }
      return $comment;
   }



   /**
    * Massive action ()
    */
   function getSpecificMassiveActions($checkitem=NULL) {

      $actions = array();
      if (Session::haveRight("plugin_fusioninventory_agent", UPDATE)) {
         $pfAgentmodule = new PluginFusioninventoryAgentmodule();
         $a_modules = $pfAgentmodule->find();
         foreach ($a_modules as $data) {
            $actions['PluginFusioninventoryAgent'.MassiveAction::CLASS_ACTION_SEPARATOR.$data["modulename"]] =
                     __('Module', 'fusioninventory')." - ".$data['modulename'];
         }
         $actions['PluginFusioninventoryAgent'.MassiveAction::CLASS_ACTION_SEPARATOR.'transfert'] = __('Transfer');
      }

      return $actions;
   }



   /**
    * @since version 0.85
    *
    * @see CommonDBTM::showMassiveActionsSubForm()
   **/
   static function showMassiveActionsSubForm(MassiveAction $ma) {

      switch ($ma->getAction()) {
         case 'transfert' :
            Dropdown::show('Entity');
            echo "<br><br>".Html::submit(__('Post'),
                                         array('name' => 'massiveaction'));
            return true;

      }
      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      $a_modules = $pfAgentmodule->find();
      foreach ($a_modules as $data) {
         if ($ma->getAction() == $data['modulename']) {
            Dropdown::showYesNo($ma->getAction());
            echo "<br><br>".Html::submit(__('Post'),
                                         array('name' => 'massiveaction'));
            return true;
         }
      }
      return parent::showMassiveActionsSubForm($ma);
   }



   static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                       array $ids) {

      switch ($ma->getAction()) {

         case 'transfert' :
            $pfDeployPackage = new PluginFusioninventoryDeployPackage();
            foreach ($ids as $key) {
               if ($pfDeployPackage->getFromDB($key)) {
                  $input = array();
                  $input['id'] = $key;
                  $input['entities_id'] = $ma->POST['entities_id'];
                  $pfDeployPackage->update($input);
               }
               $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
            }
            return;
            break;

      }

      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      $a_modules = $pfAgentmodule->find();
      foreach ($a_modules as $data2) {
         if ($ma->getAction() == $data2['modulename']) {
            foreach ($ids as $key) {
               if ($ma->POST[$data2['modulename']] == $data2['is_active']) {
                  // Remove from exceptions
                  $a_exceptions = importArrayFromDB($data2['exceptions']);
                  if (in_array($key, $a_exceptions)) {
                     foreach ($a_exceptions as $key2=>$value2) {
                        if ($value2 == $key) {
                           unset($a_exceptions[$key2]);
                        }
                     }
                  }
                  $data2['exceptions'] = exportArrayToDB($a_exceptions);
               } else {
                  // Add to exceptions
                  $a_exceptions = importArrayFromDB($data2['exceptions']);
                  if (!in_array($key, $a_exceptions)) {
                     $a_exceptions[] = (string)$key;
                  }
                  $data2['exceptions'] = exportArrayToDB($a_exceptions);
               }
               $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
            }
            $pfAgentmodule->update($data2);
         }
      }

      return;
   }



   /**
   * Display form for agent configuration
   *
   * @param $computers_id integer ID of the agent
   * @param $options array
   *
   * @return bool TRUE if form is ok
   *
   **/
   function showForm($computers_id, $options=array()) {


      if ($computers_id!='') {
         $this->getFromDB($computers_id);
      } else {
         $this->getEmpty();
         $pfConfig = new PluginFusioninventoryConfig();
         unset($this->fields['id']);
         $this->fields['threads_networkdiscovery'] =
                 $pfConfig->getValue('threads_networkdiscovery');
         $this->fields['timeout_networkdiscovery'] =
                 $pfConfig->getValue('timeout_networkdiscovery');
         $this->fields['threads_networkinventory'] =
                 $pfConfig->getValue('threads_networkinventory');
         $this->fields['timeout_networkinventory'] =
                 $pfConfig->getValue('timeout_networkinventory');
         $this->fields['senddico'] = 0;
      }
      $this->initForm($computers_id, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Name')." :</td>";
      echo "<td align='center'>";
      Html::autocompletionTextField($this,'name', array('size' => 40));
      echo "</td>";
      echo "<td>".__('Device_id', 'fusioninventory')."&nbsp;:</td>";
      echo "<td align='center'>";
      echo $this->fields["device_id"];
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Computer link', 'fusioninventory')."&nbsp:</td>";
      echo "<td align='center'>";
      if (!empty($this->fields["computers_id"])) {
         $oComputer = new Computer();
         $oComputer->getFromDB($this->fields["computers_id"]);
         echo $oComputer->getLink(1);
         echo Html::hidden('computers_id',
                           array('value' => $this->fields["computers_id"]));
      } else {
         Computer_Item::dropdownConnect("Computer", "Computer", 'computers_id',
                                        $_SESSION['glpiactive_entity']);
      }
      echo "</td>";
      echo "<td>".__('Token')."&nbsp:</td>";
      echo "<td align='center'>";
      echo $this->fields["token"];
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('locked', 'fusioninventory')."&nbsp:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo('lock', $this->fields["lock"]);
      echo "</td>";
      echo "<td>".__('Version')."&nbsp:</td>";
      echo "<td align='center'>";
      $a_versions = importArrayFromDB($this->fields["version"]);
      foreach ($a_versions as $module => $version) {
         echo "<strong>".$module. "</strong>: ".$version."<br/>";
      }
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Threads number', 'fusioninventory')."&nbsp;".
              "(".strtolower(__('Network discovery', 'fusioninventory')).")&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showNumber("threads_networkdiscovery", array(
             'value' => $this->fields["threads_networkdiscovery"],
             'min' => 1,
             'max' => 400)
         );

      echo "</td>";
      echo "<td>".__('Useragent', 'fusioninventory')."&nbsp:</td>";
      echo "<td align='center'>";
      echo $this->fields["useragent"];
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('SNMP timeout', 'fusioninventory')."&nbsp;".
              "(".strtolower(__('Network discovery', 'fusioninventory')).")&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showNumber("timeout_networkdiscovery", array(
             'value' => $this->fields["timeout_networkdiscovery"],
             'min' => 0,
             'max' => 60)
         );
      echo "</td>";
      echo "<td>".__('Last contact', 'fusioninventory')."&nbsp:</td>";
      echo "<td align='center'>";
      echo Html::convDateTime($this->fields["last_contact"]);
      echo "</td>";
      echo "</tr>";


      echo "<tr class='tab_bg_1'>";
      echo "<td>".__('Threads number', 'fusioninventory')."&nbsp;".
              "(".strtolower(__('Network inventory (SNMP)', 'fusioninventory')).")&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showNumber("threads_networkinventory", array(
             'value' => $this->fields["threads_networkinventory"],
             'min' => 1,
             'max' => 400)
      );
      echo "</td>";
      echo "<td>".__('FusionInventory tag', 'fusioninventory')."&nbsp:</td>";
      echo "<td align='center'>";
      echo $this->fields["tag"];
      echo "</td>";
      echo "</tr>";

      echo "<td>".__('SNMP timeout', 'fusioninventory')."&nbsp;".
              "(".strtolower(__('Network inventory (SNMP)', 'fusioninventory')).")&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showNumber("timeout_networkinventory", array(
             'value' => $this->fields["timeout_networkinventory"],
             'min' => 0,
             'max' => 60)
      );
      echo "</td>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      $pfConfig = new PluginFusioninventoryConfig();
      echo "<td>".__('Agent port', 'fusioninventory')." (".
              __('if empty use port configured in general options', 'fusioninventory')
              ." <i>".$pfConfig->getValue('agent_port')."</i>)&nbsp:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='agent_port' value='".$this->fields['agent_port']."'/>";
      echo "</td>";
      echo "<td colspan='2'>";
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      return TRUE;
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
   function importToken($arrayinventory) {

      if (isset($arrayinventory['DEVICEID'])) {
         $pfAgent = new PluginFusioninventoryAgent();
         $a_agent = $pfAgent->find("`device_id`='".$arrayinventory['DEVICEID']."'", "", "1");
         if (empty($a_agent)) {
            $a_input = array();
            if (isset($arrayinventory['TOKEN'])) {
               $a_input['token'] = $arrayinventory['TOKEN'];
            }
            $a_input['name']         = $arrayinventory['DEVICEID'];
            $a_input['device_id']    = $arrayinventory['DEVICEID'];
            $a_input['entities_id']  = 0;
            $a_input['last_contact'] = date("Y-m-d H:i:s");
            if (isset($_SERVER['HTTP_USER_AGENT'])) {
               $a_input['useragent'] = $_SERVER['HTTP_USER_AGENT'];
            }
            return $pfAgent->add($a_input);
         } else {
            foreach ($a_agent as $data) {
               $input = array();
               $input['id'] = $data['id'];
               if (isset($arrayinventory['TOKEN'])) {
                  $input['token'] = $arrayinventory['TOKEN'];
               }
               $input['last_contact'] = date("Y-m-d H:i:s");
               if (isset($_SERVER['HTTP_USER_AGENT'])) {
                  $input['useragent'] = $_SERVER['HTTP_USER_AGENT'];
               }
               $pfAgent->update($input);
               return $data['id'];
            }
         }
      }
      return;
   }



   /**
   * Get all IP of an agent or a computer
   *
   * @param $items_id integer ID of the item
   * @param $type 'Agent' by default to get IP of agent or of a computer if set other text
   *
   * @return Every IP addresses registered for this agent or false
   *
   **/
   function getIPs() {
      $ip_addresses = array();

      $computers_id = 0;

      if (isset($this->fields['computers_id']) ) {
         if ( $this->fields['computers_id'] > 0 ) {
         }
      } else {
         trigger_error('Agent must be initialized');
      }

      $ip_addresses = PluginFusioninventoryToolbox::getIPforDevice('Computer', $this->fields['computers_id']);

      return $ip_addresses;
   }



   /**
   * Get agent id of a computer
   *
   * @param $computers_id integer ID of the computer
   *
   * @return agent id or False
   *
   **/
   function getAgentWithComputerid($computers_id) {

      $agent = $this->find("`computers_id`='".$computers_id."'", "", 1);

      if (count($agent) == '1') {
         $data = current($agent);
         $this->getFromDB($data['id']);
         return $data['id'];
      }
      return FALSE;
   }

   /**
   * Get agent id of a computer
   *
   * @param $computers_id integer ID of the computer
   *
   * @return agent id or False
   *
   **/
   function getAgentsFromComputers($computer_ids = array()) {

      if (count($computer_ids) == 0) {
         return array();
      }

      $computer_ids = "'" . implode("','", $computer_ids) . "'";

      $agents = $this->find("`computers_id` in (".$computer_ids.")", "");

      return $agents;
   }

   /**
   * Get Computer associated with this agent
   *
   * @return A Computer object or False
   *
   **/

   function getAssociatedComputer() {

      $computer = new Computer();

      if (!isset($this->fields['id'])) {
         trigger_error("Agent must be initialized!");
         return false;
      }

      $computer->getFromDB($this->fields['computers_id']);
      return $computer;
   }


   /**
   * Create links between agent and computer.
   *
   * @param $computers_id integer ID of the computer
   * @param $device_id value of device_id from XML to identify agent
   * @param $entities_id integer ID of the computer entity
   *
   * @return Nothing
   *
   **/
   function setAgentWithComputerid($computers_id, $device_id, $entities_id) {

      $a_agent = $this->find("`computers_id`='".$computers_id."'", "", 1);
      // Is this computer already linked to an agent?
      $agent = array_shift($a_agent);
      if (is_array($agent)) {

         // relation
         if ($agent['device_id'] != $device_id
                 || $agent['entities_id'] != $entities_id) {
            $input = array();
            $input['id'] = $agent['id'];
            $input['device_id'] = $device_id;
            $input['entities_id'] = $entities_id;
            $this->update($input);
         }

//         // Clean up the agent list
//         $oldAgent_deviceids = $this->find(
//            // computer linked to the wrong agent
//            "(`computers_id`='".$computers_id."' AND `device_id` <> '".$device_id."')");
//         foreach ($oldAgent_deviceids as $oldAgent) {
//            $this->delete($oldAgent);
//         }
         $oldAgents = $this->find(
            // the same device_id but linked on the wrong computer
            "(`device_id`='".$device_id."' AND `computers_id`<>'".$computers_id."')");
         foreach ($oldAgents as $oldAgent) {
            $input = array();
            $input['id']            = $agent['id'];
            $input['last_contact']  = $oldAgent['last_contact'];
            $input['version']       = $oldAgent['version'];
            $input['name']          = $oldAgent['name'];
            $input['useragent']     = $oldAgent['useragent'];
            $input['token']         = $oldAgent['token'];
            $input['tag']           = $oldAgent['tag'];
            $input['entities_id']   = $entities_id;
            $this->update($input);
            $this->delete($oldAgent);
         }
         return TRUE;
      } else { # This is a new computer
         // Link agent with computer
         $agent = $this->InfosByKey($device_id);
         if (isset($agent['id'])) {
             $agent['computers_id'] = $computers_id;
             $agent['entities_id']  = $entities_id;
             $this->update($agent);
             return TRUE;
         }
      }
      return FALSE;
   }



   /**
   * Display if agent is online
   *
   * @return Nothing (display)
   *
   **/
   function showRemoteStatus($computer = null) {
      global $CFG_GLPI;

      /**
       * Check for initialized agent
       */
      if (!isset($this->fields['id'])) {
         return;
      }

      /**
       * Check for initialized $computer
       */
      if ( is_null($computer) && !isset($computer->fields['id']) ) {
         return;
      }

      $agent_id = $this->fields['id'];

      $pfTaskjob = new PluginFusioninventoryTaskjob();

      echo "<form method='post' name='' id=''  action=\"".$CFG_GLPI['root_doc'] .
         "/plugins/fusioninventory/front/agent.form.php\">";
      echo "<table class='tab_cadre' width='500'>";

      echo "<tr>";
      echo "<th colspan='2'>";
      echo __('Agent state', 'fusioninventory');
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Agent', 'fusioninventory')."&nbsp:";
      echo "</td>";
      echo "<td>";
      echo $this->getLink(1);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Status')."&nbsp;:";
      echo "</td>";
      echo "<td>";

      $waiting = 0;

      $agentStatus = $this->getStatus();

      switch($agentStatus['message']) {

         case 'executing scheduled tasks':
         case 'running':
            echo __('Running');
            break;

         case 'noanswer':
            echo __('Impossible to communicate with agent!', 'fusioninventory');
            break;

         case 'waiting':
            $waiting = 1;
            echo __(
               'Available on <a target="_blank" href="'. $agentStatus['url_ok'] . '">' .
               $agentStatus['url_ok'] .
               '</a>'
            );
            echo Html::hidden('agent_id', array('value' => $agent_id));
            break;

         default:
            if (strstr($agentStatus['message'], 'running')) {
               echo $agentStatus['message'];
            } else {
               echo "SELinux problem, do 'setsebool -P httpd_can_network_connect on'";
            }
            break;

      }

      echo "</td>";
      echo "</tr>";

      if ($waiting == '1') {
         echo "<tr>";
         echo "<th colspan='2'>";
         echo "<input name='startagent' value=\"".__('Force inventory', 'fusioninventory').
            "\" class='submit' type='submit'>";
         echo "</th>";
         echo "</tr>";
      }

      echo "</table>";
      Html::closeForm();
      echo "<br/>";
   }

   /**
    * Get current state of the agent
    *
    * @param $items_id integer id of the agent
    *
    * @return string message/state of the agent
    *
    */
   function getStatus() {

      $url_addresses = $this->getAgentStatusURLs();

      $this->disableDebug();

      ob_start();
      ini_set("allow_url_fopen", "1");

      $ctx = stream_context_create(array(
         'http' => array(
            'timeout' => 1
            )
         )
      );

      $contents="";
      $url_ok = null;
      $url_headers=array();
      foreach( $url_addresses as $url) {
         if ( $stream = fopen($url, 'r', false, $ctx) ) {
            //$result = file_get_contents($url, FALSE, $ctx);
            $contents = stream_get_contents($stream);
            $url_headers[$url] = stream_get_meta_data($stream);
            fclose($stream);
            if ($contents !== false) {
               $url_ok = $url;
               break;
            }
         }
      }
      $error = ob_get_contents();
      ob_end_clean();
      $this->restoreDebug();

      $status = array(
         "url_ok" => $url_ok,
         "message" => ""
      );

      if ($contents !== "") {
         $status['message'] = preg_replace("/^status: /", "", $contents);
      }

      if ($contents == '' AND !strstr($error, "failed to open stream: Permission denied")) {
         $status['message'] = "noanswer";
      }

      return $status;
   }


   /**
   * Start agent remotly from server
   *
   * @param $agent_id integer id of the agent
   *
   * @return bool TRUE if agent wake up
   *
   **/
   function wakeUp() {

      $ret = FALSE;

      $this->disableDebug();
      $urls = $this->getAgentRunURLs();

      $ctx = stream_context_create(array('http' => array('timeout' => 2)));
      foreach ( $urls as $url ) {
         if (!$ret) {
            if (@file_get_contents($url, 0, $ctx) !== FALSE) {
               $ret = TRUE;
               break;
            }
         }
      }
      $this->restoreDebug();

      return $ret;
   }

   /**
   * Get state of agent
   *
   * @param $ip value IP address of the computer where agent is installed
   * @param $agentid integer id of the agent
   *
   * @return bool TRUE if agent is ready else FALSE
   *
   **/
   function isAgentAlive() {

      if ( $this->getStatus() === 'waiting') {
         return true;
      }

      return false;
   }


   /**
   * Disable debug mode because we don't want the errors
   *
   **/
   function disableDebug() {
      error_reporting(0);
      set_error_handler(array($this, 'errorempty'));
   }

   /**
   * When debug is disabled, we transfer every errors in this emtpy function.
   *
   **/
   static function errorempty() {}

   /**
   * Resotre debug mode if it has been explicitely set by the user in his settings.
   *
   **/
   function restoreDebug() {
      if ($_SESSION['glpi_use_mode'] == Session::DEBUG_MODE){
         ini_set('display_errors', 'On');
         // Recommended development settings
         error_reporting(E_ALL | E_STRICT);
         set_error_handler(array('Toolbox', 'userErrorHandlerDebug'));
      } else {
         ini_set('display_errors', 'Off');
         error_reporting(E_ALL);
         set_error_handler(array('Toolbox', 'userErrorHandlerNormal'));
      }

   }

   /**
   * Set agent version of each module
   *
   * @param $agent_id integer ID of the agent
   * @param $module value Module name (WAKEONLAN, NETWORKDISCOVERY, INVENTORY, NETWORKINVENTORY...)
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
    * Get agent version
    *
    * @param type $agent_id
    */
   function getAgentVersion($agent_id) {
      $this->getFromDB($agent_id);
      $a_version = importArrayFromDB($this->fields['version']);
      if (isset($a_version['INVENTORY'])) {
         return str_replace('v', '', $a_version['INVENTORY']);
      }
      return '0';
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
         return FALSE;
      }
   }



   /**
    * Get base URL to communicate with an agent
    *
    * @param ip agent's IP
    *
    * @return a list of http url to contact the agent
    */
   public function getAgentBaseURLs() {
      $config  = new PluginFusioninventoryConfig();

      $port = $config->getValue('agent_port');
      $url_addresses = array();


      if ( isset($this->fields['id']) ) {
         $computer = $this->getAssociatedComputer();
         if ($this->fields['agent_port'] != ''
                 && is_numeric($this->fields['agent_port'])) {
            $port = $this->fields['agent_port'];
         }
         if ($computer->fields["name"] && $computer->fields["name"] != "localhost") {
            array_push($url_addresses, "http://".$computer->fields["name"].
               ":".$port);

            $domain = new Domain();
            if ($computer->fields['domains_id'] != 0) {
               $domain->getFromDB($computer->fields['domains_id']);
               array_push($url_addresses, "http://".
                  $computer->fields["name"].'.'.
                  $domain->fields["name"].
                  ":".$port);
            }
         }
      }

      # Guess the machine name from the DEVICEID,
      # useful when Windows domain != DNS domain
      $stack = array();
      if(preg_match('/(\S+)-\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2}$/',
                    $this->fields['name'],
                    $stack)) {
         array_push($url_addresses, "http://".$stack[1].":".$port);
      }

      $ip_addresses = $this->getIPs();
      foreach ($ip_addresses as $ip_address) {
         if ($ip_address != '') {
            array_push($url_addresses, "http://".$ip_address.":".$port);
         }
      }
      return $url_addresses;
   }



   /**
    * URL to get agent's state
    *
    * @param ip agent's IP
    *
    * @return an array of http url to get the agent's state
    */
   public function getAgentStatusURLs() {
      $ret = array();

      foreach ($this->getAgentBaseURLs() as $url) {
         array_push($ret, $url."/status");
      }
      return $ret;
   }



   /**
    * URL to ask the agent to wake up
    *
    * @param interger agents_id agent id
    *
    * @return an http url to ask the agent to wake up
    */
   public function getAgentRunURLs() {
      $ret = array();

      foreach ($this->getAgentBaseURLs() as $url) {
         array_push($ret, $url."/now/".$this->fields['token']);
      }
      return $ret;
   }



   /**
    * Show configuration form of agent
    *
    */
   static function showConfig() {

      echo "<table width='950' class='tab_cadre_fixe'>";

      echo "<tr>";
      echo "<th colspan='2'>";
      echo __('Informations for agent configuration', 'fusioninventory');

      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td width='50%'>";
      $array = explode("/", $_SERVER['HTTP_REFERER']);
      $create_url = $array[0]."//".$array[2].
              str_replace("front/wizard.php", "", $_SERVER['PHP_SELF']);
      echo __('Communication url of the server', 'fusioninventory')."&nbsp;:";
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



   /**
    * Display agent infos for a computer
    *
    * @param type $computers_id id of the computer
    */
   function showInfoForComputer($computers_id) {

      if ($this->getAgentWithComputerid($computers_id)) {

         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('Agent', 'fusioninventory').'</td>';
         echo '<td>'.$this->getLink(1).'</td>';
         echo '</tr>';

         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('Useragent', 'fusioninventory').'</td>';
         echo '<td>'.$this->fields['useragent'].'</td>';
         echo '</tr>';

         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('FusionInventory tag', 'fusioninventory').'</td>';
         echo '<td>'.$this->fields['tag'].'</td>';
         echo '</tr>';
      }
   }



   /**
   * Clean agent too old (so haven't contacted glpi
    * since xx days)
   *
   * @return bool cron is ok or not
   *
   **/
   static function cronCleanoldagents() {
      global $DB;

      $pfConfig = new PluginFusioninventoryConfig();
      $pfAgent  = new PluginFusioninventoryAgent();

      $retentiontime = $pfConfig->getValue('agents_old_days');
      if ($retentiontime == 0) {
         return TRUE;
      }
      $sql = "SELECT * FROM `glpi_plugin_fusioninventory_agents`
                WHERE `last_contact` < date_add(now(), interval -".$retentiontime." day)";
      $result=$DB->query($sql);
      if ($result) {
         while ($data=$DB->fetch_array($result)) {
            $pfAgent->delete($data);
         }
      }
      return TRUE;
   }
}

?>
