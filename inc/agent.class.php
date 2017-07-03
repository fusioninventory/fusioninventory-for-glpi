<?php

/**
 * FusionInventory
 *
 * Copyright (C) 2010-2016 by the FusionInventory Development Team.
 *
 * http://www.fusioninventory.org/
 * https://github.com/fusioninventory/fusioninventory-for-glpi
 * http://forge.fusioninventory.org/
 *
 * ------------------------------------------------------------------------
 *
 * LICENSE
 *
 * This file is part of FusionInventory project.
 *
 * FusionInventory is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * FusionInventory is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with FusionInventory. If not, see <http://www.gnu.org/licenses/>.
 *
 * ------------------------------------------------------------------------
 *
 * This file is used to manage the agents
 *
 * ------------------------------------------------------------------------
 *
 * @package   FusionInventory
 * @author    David Durieux
 * @copyright Copyright (c) 2010-2016 FusionInventory team
 * @license   AGPL License 3.0 or (at your option) any later version
 *            http://www.gnu.org/licenses/agpl-3.0-standalone.html
 * @link      http://www.fusioninventory.org/
 * @link      https://github.com/fusioninventory/fusioninventory-for-glpi
 *
 */

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/**
 * Manage the FusionInventory agents.
 */
class PluginFusioninventoryAgent extends CommonDBTM {

   /**
    * We activate the history.
    *
    * @var boolean
    */
   public $dohistory = TRUE;

   /**
    * The right name for this class
    *
    * @var string
    */
   static $rightname = 'plugin_fusioninventory_agent';

   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb=0) {
      return __('Agent', 'fusioninventory');
   }



   /**
    * Get search function for the class
    *
    * @return array
    */
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
      $tab[2]['datatype'] = 'dropdown';

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



   /**
    * Define tabs to display on form page
    *
    * @param array $options
    * @return array containing the tabs name
    */
   function defineTabs($options=array()) {

      $ong = array();
      $this->addDefaultFormTab($ong);
      $this->addStandardTab('PluginFusioninventoryAgentmodule', $ong, $options);
      $this->addStandardTab('Log', $ong, $options);

      return $ong;
   }



   /**
    * Get the tab name used for item
    *
    * @param object $item the item object
    * @param integer $withtemplate 1 if is a template form
    * @return string name of the tab
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      $tab_names = array();
      if ( $this->can(0, CREATE)
         && PluginFusioninventoryToolbox::isAFusionInventoryDevice($item)) {
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



   /**
    * Display the content of the tab
    *
    * @param object $item
    * @param integer $tabnum number of the tab to display
    * @param integer $withtemplate 1 if is a template form
    * @return boolean
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType() == 'Computer') {

         // Possibility to remote agent
         if (PluginFusioninventoryToolbox::isAllowurlfopen(1)) {
            $pfAgent = new PluginFusioninventoryAgent();
            if ($pfAgent->getAgentWithComputerid($item->fields['id'])) {
               $pfAgent->showRemoteStatus($item);
               return TRUE;
            }
         }
      }
      return FALSE;
   }



   /**
    * Get comments of the object
    *
    * @return string comments in HTML format
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
    * Get the massive actions for this object
    *
    * @param object|null $checkitem
    * @return array list of actions
    */
   function getSpecificMassiveActions($checkitem=NULL) {

      $actions = array();
      if (Session::haveRight("plugin_fusioninventory_agent", UPDATE)) {
         $pfAgentmodule = new PluginFusioninventoryAgentmodule();
         $a_modules = $pfAgentmodule->find();
         foreach ($a_modules as $data) {
            $actions[__CLASS__.MassiveAction::CLASS_ACTION_SEPARATOR.$data["modulename"]] =
                     __('Module', 'fusioninventory')." - ".$data['modulename'];
         }
         $actions[__CLASS__.MassiveAction::CLASS_ACTION_SEPARATOR.'transfert'] = __('Transfer');
      }
      return $actions;
   }



   /**
    * Display form related to the massive action selected
    *
    * @param object $ma MassiveAction instance
    * @return boolean
    */
   static function showMassiveActionsSubForm(MassiveAction $ma) {

      if ($ma->getAction() == 'transfert') {
         Dropdown::show('Entity');
         echo "<br><br>".Html::submit(__('Post'),
                                      array('name' => 'massiveaction'));
         return TRUE;
      }
      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      $a_modules = $pfAgentmodule->find();
      foreach ($a_modules as $data) {
         if ($ma->getAction() == $data['modulename']) {
            Dropdown::showYesNo($ma->getAction());
            echo "<br><br>".Html::submit(__('Post'),
                                         array('name' => 'massiveaction'));
            return TRUE;
         }
      }
      return parent::showMassiveActionsSubForm($ma);
   }



   /**
    * Execution code for massive action
    *
    * @param object $ma MassiveAction instance
    * @param object $item item on which execute the code
    * @param array $ids list of ID on which execute the code
    */
   static function processMassiveActionsForOneItemtype(MassiveAction $ma, CommonDBTM $item,
                                                       array $ids) {

      $pfAgent = new self();

      switch ($ma->getAction()) {

         case 'transfert' :
            foreach ($ids as $key) {
               if ($pfAgent->getFromDB($key)) {
                  $input = array();
                  $input['id'] = $key;
                  $input['entities_id'] = filter_input(INPUT_POST, "entities_id");
                  if ($pfAgent->update($input)) {
                     //set action massive ok for this item
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_OK);
                  } else {
                     // KO
                     $ma->itemDone($item->getType(), $key, MassiveAction::ACTION_KO);
                  }
               }
            }
            return;
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
    * @param integer $agents_id ID of the agent
    * @param array $options
    * @return boolean
    */
   function showForm($agents_id, $options=array()) {
      global $CFG_GLPI;

      if ($agents_id!='') {
         $this->getFromDB($agents_id);
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
      $this->initForm($agents_id, $options);
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
         echo "&nbsp;<a class='pointer' onclick='submitGetLink(\"".
               $CFG_GLPI['root_doc']."/plugins/fusioninventory/front/agent.form.php\", ".
               "{\"disconnect\": \"disconnect\",
                 \"computers_id\": ".$this->fields['computers_id'].",
                 \"id\": ".$this->fields['id'].",
                 \"_glpi_csrf_token\": \"".Session::getNewCSRFToken()."\"});'>".
               "<img src='".$CFG_GLPI['root_doc']."/pics/delete.png' /></a>";
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
   * Disconnect an agent from a computer
   * @params POST parameters
   * @return void
   */
   function disconnect($params) {
      if (isset($params['computers_id']) && isset($params['id'])) {
         $pfComputer = new PluginFusioninventoryInventoryComputerComputer();
         $pfComputer->deleteByCriteria(['computers_id' => $params['computers_id']]);
         $this->update(['id' => $params['id'], 'computers_id' => 0]);
      }
   }

   /**
    * Get agent information by device_id
    *
    * @global object $DB
    * @param string $device_id
    * @return array all data of agent from database
    */
   function infoByKey($device_id) {
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
    * Import token: create of update it in database
    *
    * @param array $arrayinventory
    * @return integer id of the agent from database
    */
   function importToken($arrayinventory) {

      if (isset($arrayinventory['DEVICEID'])) {
         $a_agent = $this->find("`device_id`='".$arrayinventory['DEVICEID']."'", "", "1");
         if (empty($a_agent)) {
            $a_input = array();
            if (isset($arrayinventory['TOKEN'])) {
               $a_input['token'] = $arrayinventory['TOKEN'];
            }
            $a_input['name']         = $arrayinventory['DEVICEID'];
            $a_input['device_id']    = $arrayinventory['DEVICEID'];
            $a_input['entities_id']  = 0;
            $a_input['last_contact'] = date("Y-m-d H:i:s");
            $a_input['useragent'] = filter_input(INPUT_SERVER, "HTTP_USER_AGENT");
            $agents_id = $this->add($a_input);
            if ($agents_id) {
               return $agents_id;
            }
         } else {
            foreach ($a_agent as $data) {
               $input = array();
               $input['id'] = $data['id'];
               if (isset($arrayinventory['TOKEN'])) {
                  $input['token'] = $arrayinventory['TOKEN'];
               }
               $input['last_contact'] = date("Y-m-d H:i:s");
               $input['useragent'] = filter_input(INPUT_SERVER, "HTTP_USER_AGENT");
               $this->update($input);
               return $data['id'];
            }
         }
      }
      return 0;
   }



   /**
    * Get all IP of the computer linked with this agent
    *
    * @return array list of IP
    */
   function getIPs() {
      if (!isset($this->fields['computers_id'])
              || $this->fields['computers_id'] == 0) {
         trigger_error('Agent must be initialized');
      }
      $ip_addresses = PluginFusioninventoryToolbox::getIPforDevice('Computer', $this->fields['computers_id']);
      return $ip_addresses;
   }



   /**
    * Get the agent id linked to this computer id
    *
    * @param integer $computers_id id of the agent
    * @return integer|false integer if found agent id, otherwise false
    */
   function getAgentWithComputerid($computers_id) {

      $agent = $this->find("`computers_id`='".$computers_id."'", "", 1);
      if (count($agent) == 1) {
         $data = current($agent);
         $this->getFromDB($data['id']);
         return $data['id'];
      }
      return FALSE;
   }



   /**
    * Get the agents id of a list of computers id
    *
    * @param array $computer_ids list of id of computers
    * @return array list of agents [id] => information of agent
    */
   function getAgentsFromComputers($computer_ids = array()) {

      if (count($computer_ids) == 0) {
         return array();
      }
      $agents = $this->find(
              "`computers_id` in ('".implode("','", $computer_ids)."')");
      return $agents;
   }



   /**
    * Get the computer linked with this agent
    *
    * @return object|false return Computer object if exist, otherwise false
    */
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
    * Link a computer with an agent
    *
    * @param integer $computers_id id of the computer
    * @param string $device_id devide_id of the agent
    * @param integer $entities_id id of the entity
    * @return boolean true if successfully linked
    */
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
         $agent = $this->infoByKey($device_id);
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
    * Display form with the remotely status of agent (available, not available,
    * waiting, running...)
    *
    * @global array $CFG_GLPI
    * @param object $computer Computer object
    */
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
      if (is_null($computer) || !isset($computer->fields['id'])) {
         return;
      }

      $agent_id = $this->fields['id'];

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
    * Get the remotely status of the agent (available, not available, waiting,
    * running...)
    *
    * @return array
    */
   function getStatus() {

      $url_addresses = $this->getAgentStatusURLs();

      PluginFusioninventoryDisplay::disableDebug();

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
      foreach ($url_addresses as $url) {
         $stream = fopen($url, 'r', false, $ctx);
         if ($stream) {
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
      PluginFusioninventoryDisplay::reenableusemode();

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
    * Send a request to the remotely agent to run now
    *
    * @return boolean true if send successfully, otherwise false
    */
   function wakeUp() {

      $ret = FALSE;

      PluginFusioninventoryDisplay::disableDebug();
      $urls = $this->getAgentRunURLs();

      $ctx = stream_context_create(array('http' => array('timeout' => 2)));
      foreach ($urls as $url) {
         if (!$ret) {
            if (@file_get_contents($url, 0, $ctx) !== FALSE) {
               $ret = TRUE;
               break;
            }
         }
      }
      PluginFusioninventoryDisplay::reenableusemode();

      return $ret;
   }



   /**
    * Store version of each module of agent
    *
    * @param integer $agent_id id of the agent
    * @param string $module name of the module (inventory, deploy...)
    * @param string $version version of the module
    */
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
    * Get the version of agent (it's the same number as inventory module)
    *
    * @param integer $agent_id id of the agent
    * @return string version of agent
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
    * get the agent by the device_id
    *
    * @param string $device_id the device_id sent by the agent
    * @return array|false agent information if found, otherwise false
    */
   static function getByDeviceID($device_id) {
      $agents = getAllDatasFromTable('glpi_plugin_fusioninventory_agents',
                                      "`device_id`='$device_id' AND `lock`='0'");
      if (!empty($agents)) {
         return array_pop($agents);
      } else {
         return FALSE;
      }
   }



   /**
    * Get / generate the URLs to communicate with current agent
    *
    * @return array list of HTTP URL used to contact the agent
    */
   public function getAgentBaseURLs() {
      $config  = new PluginFusioninventoryConfig();

      $port = $config->getValue('agent_port');
      $url_addresses = array();


      if (isset($this->fields['id'])) {
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
      if (preg_match('/(\S+)-\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2}$/',
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
    * Get the URLs used to get the status of the agent
    *
    * @return array list of HTTP URL to get the agent's state
    */
   public function getAgentStatusURLs() {
      $ret = array();

      foreach ($this->getAgentBaseURLs() as $url) {
         array_push($ret, $url."/status");
      }
      return $ret;
   }



   /**
    * Get the URLs used to wake up the agent
    *
    * @return array liste of HTTP URL to ask the agent to wake up
    */
   public function getAgentRunURLs() {
      $ret = array();

      foreach ($this->getAgentBaseURLs() as $url) {
         array_push($ret, $url."/now/".$this->fields['token']);
      }
      return $ret;
   }



   /**
    * Display configuration form of agent
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
      $array = explode("/", filter_input(INPUT_SERVER, "HTTP_REFERER"));
      $create_url = $array[0]."//".$array[2].
              str_replace("front/wizard.php", "", filter_input(INPUT_SERVER, "PHP_SELF"));
      echo __('Communication url of the server', 'fusioninventory')."&nbsp;:";
      echo "</td>";
      echo "<td>";
      echo "<strong>".$create_url."</strong>";

      echo "</td>";
      echo "</tr>";
      echo "</table>";
   }



   /**
    * Disable data to put in glpi_logs because don't want to write all these
    * very often changes
    */
   function pre_updateInDB() {
      if (isset($this->oldvalues['version'])
              AND $this->input['version'] == $this->oldvalues['version']) {

         $key = array_search('version', $this->updates);
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
    * Display agent information for a computer
    *
    * @param integer $computers_id id of the computer
    * @param integer $colspan the number of columns of the form (2 by default)
    */
   function showInfoForComputer($computers_id, $colspan = 2) {

      if ($this->getAgentWithComputerid($computers_id)) {

         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('Agent', 'fusioninventory').'</td>';
         echo '<td>'.$this->getLink(1).'</td>';
         echo '</tr>';

         if ($colspan == 2) {
            echo '</tr>';
            echo '<tr class="tab_bg_1">';
         }
         echo '<td>'.__('Useragent', 'fusioninventory').'</td>';
         echo '<td>'.$this->fields['useragent'].'</td>';
         if ($colspan == 2) {
            echo '</tr>';
         }

         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('FusionInventory tag', 'fusioninventory').'</td>';
         echo '<td>'.$this->fields['tag'].'</td>';
         if ($colspan == 4) {
            echo '<td colspan=\'2\'></td>';
         }
         echo '</tr>';

         echo '<tr class="tab_bg_1">';
         echo '<td>';
         echo __('Last contact', 'fusioninventory');
         echo '</td>';
         echo '<td>';
         echo Html::convDateTime($this->fields['last_contact']);
         echo '</td>';
         echo '</tr>';

      }
   }



   /**
    * Cron task: clean or do defined action when agent not have been contacted
    * the server since xx days
    *
    * @global object $DB
    * @param object $task
    * @return boolean true if successfull, otherwise false
    */
   static function cronCleanoldagents($task=NULL) {
      global $DB;

      $pfConfig = new PluginFusioninventoryConfig();
      $pfAgent  = new PluginFusioninventoryAgent();

      $retentiontime = $pfConfig->getValue('agents_old_days');
      if ($retentiontime == 0) {
         return TRUE;
      }
      $sql = "SELECT * FROM `glpi_plugin_fusioninventory_agents`
                   WHERE `last_contact` < date_add(now(), interval -".$retentiontime." day)";
      $result = $DB->query($sql);

      if ($result) {
         $cron_status = FALSE;
         $action = $pfConfig->getValue('agents_action');
         if ($action == PluginFusioninventoryConfig::ACTION_CLEAN) {
            //delete agents
            while ($data = $DB->fetch_array($result)) {
               $pfAgent->delete($data);
               $task->addVolume(1);
               $cron_status = TRUE;
            }
         } else {
            //change status of agents
            while ($data = $DB->fetch_array($result)) {
               $computer = new Computer();
               if ($computer->getFromDB($data['computers_id'])) {
                  $computer->update(array(
                      'id' => $data['computers_id'],
                      'states_id' => $pfConfig->getValue('agents_status')));
                  $task->addVolume(1);
                  $cron_status = TRUE;
               }
            }
         }
      }
      return $cron_status;
   }
}

?>
