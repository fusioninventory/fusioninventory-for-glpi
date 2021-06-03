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
   public $dohistory = true;

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
   static function getTypeName($nb = 0) {
      return __('Agent', 'fusioninventory');
   }


   /**
    * Get search function for the class
    *
    * @return array
    */
   function rawSearchOptions() {

      $tab = [];

      $tab[] = [
         'id' => 'common',
         'name' => __('Agent', 'fusioninventory')
      ];

      $tab[] = [
         'id'        => '1',
         'table'     => $this->getTable(),
         'field'     => 'name',
         'name'      => __('Name'),
         'datatype'  => 'itemlink',
         'autocomplete' => true,
      ];

      $tab[] = [
         'id'       => '2',
         'table'    => 'glpi_entities',
         'field'    => 'completename',
         'name'     => Entity::getTypeName(1),
         'datatype' => 'dropdown',
      ];

      $tab[] = [
         'id'        => '3',
         'table'     => $this->getTable(),
         'field'     => 'is_recursive',
         'name'      => __('Child entities'),
         'datatype'  => 'bool',
      ];

      $tab[] = [
         'id'        => '4',
         'table'     => $this->getTable(),
         'field'     => 'last_contact',
         'name'      => __('Last contact', 'fusioninventory'),
         'datatype'  => 'datetime',
      ];

      $tab[] = [
         'id'        => '5',
         'table'     => $this->getTable(),
         'field'     => 'lock',
         'name'      => __('locked', 'fusioninventory'),
         'datatype'  => 'bool',
      ];

      $tab[] = [
         'id'            => '6',
         'table'         => $this->getTable(),
         'field'         => 'device_id',
         'name'          => __('Device_id', 'fusioninventory'),
         'datatype'      => 'text',
         'massiveaction' => false,
      ];

      $tab[] = [
         'id'            => '7',
         'table'         => 'glpi_computers',
         'field'         => 'name',
         'name'          => __('Computer link', 'fusioninventory'),
         'datatype'      => 'itemlink',
         'itemlink_type' => 'Computer',
         'massiveaction' => false,
      ];

      $tab[] = [
         'id'            => '8',
         'table'         => $this->getTable(),
         'field'         => 'version',
         'name'          => _n('Version', 'Versions', 1),
         'datatype'      => 'text',
         'massiveaction' => false,
      ];

      $tab[] = [
         'id'            => '9',
         'table'         => $this->getTable(),
         'field'         => 'token',
         'name'          => __('Token'),
         'datatype'      => 'text',
         'massiveaction' => false,
      ];

      $tab[] = [
         'id'            => '10',
         'table'         => $this->getTable(),
         'field'         => 'useragent',
         'name'          => __('Useragent', 'fusioninventory'),
         'datatype'      => 'text',
         'massiveaction' => false,
      ];

      $tab[] = [
         'id'            => '11',
         'table'         => $this->getTable(),
         'field'         => 'tag',
         'name'          => __('FusionInventory tag', 'fusioninventory'),
         'datatype'      => 'text',
         'massiveaction' => false,
      ];

      $tab[] = [
         'id'       => '12',
         'table'    => $this->getTable(),
         'field'    => 'threads_networkdiscovery',
         'name'     => __('Threads number', 'fusioninventory') . "&nbsp;(" .
            strtolower(__('Network discovery', 'fusioninventory')) .
            ")",
         'datatype' => 'integer',
      ];

      $tab[] = [
         'id'       => '13',
         'table'    => $this->getTable(),
         'field'    => 'threads_networkinventory',
         'name'     => __('Threads number', 'fusioninventory') . "&nbsp;(" .
            strtolower(__('Network inventory (SNMP)', 'fusioninventory')) .
            ")",
         'datatype' => 'integer',
      ];

      $tab[] = [
         'id'        => '14',
         'table'     => $this->getTable(),
         'field'     => 'agent_port',
         'name'      => __('Agent port', 'fusioninventory'),
         'datatype'  => 'integer',
      ];

      $i = 20;
      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      $a_modules = $pfAgentmodule->find();
      foreach ($a_modules as $data) {
         $tab[] = [
            'id'            => $i,
            'table'         => $pfAgentmodule->getTable(),
            'field'         => $data["modulename"],
            'linkfield'     => $data["modulename"],
            'name'          => __('Module', 'fusioninventory') . " - " . $data["modulename"],
            'datatype'      => 'bool',
            'massiveaction' => false,
         ];
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
   function defineTabs($options = []) {

      $ong = [];
      $this->addDefaultFormTab($ong);
      $this->addStandardTab('PluginFusioninventoryAgentmodule', $ong, $options);
      $this->addStandardTab('Log', $ong, $options);

      return $ong;
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
         return Html::showToolTip($comment, ['display' => false]);
      }
      return $comment;
   }


   /**
    * Get the massive actions for this object
    *
    * @param object|null $checkitem
    * @return array list of actions
    */
   function getSpecificMassiveActions($checkitem = null) {

      $actions = [];
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
                                      ['name' => 'massiveaction']);
         return true;
      }
      $pfAgentmodule = new PluginFusioninventoryAgentmodule();
      $a_modules = $pfAgentmodule->find();
      foreach ($a_modules as $data) {
         if ($ma->getAction() == $data['modulename']) {
            Dropdown::showYesNo($ma->getAction());
            echo "<br><br>".Html::submit(__('Post'),
                                         ['name' => 'massiveaction']);
            return true;
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
                  $input = [];
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
   function showForm($agents_id, $options = []) {
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
      Html::autocompletionTextField($this, 'name', ['size' => 40]);
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
                           ['value' => $this->fields["computers_id"]]);
         echo "&nbsp;<a class='pointer' onclick='submitGetLink(\"".
               Plugin::getWebDir('fusioninventory')."/front/agent.form.php\", ".
               "{\"disconnect\": \"disconnect\",
                 \"computers_id\": ".$this->fields['computers_id'].",
                 \"id\": ".$this->fields['id'].",
                 \"_glpi_csrf_token\": \"".Session::getNewCSRFToken()."\"});'>".
               "<img src='".$CFG_GLPI['root_doc']."/pics/delete.png' /></a>";
      } else {
         Computer_Item::dropdownConnect("Computer", "Computer", 'computers_id',
                                        $this->fields['entities_id']);
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
      echo "<td>"._n('Version', 'Versions', 1)."&nbsp:</td>";
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
      Dropdown::showNumber("threads_networkdiscovery", [
             'value' => $this->fields["threads_networkdiscovery"],
             'toadd' => [ __('General setup') ],
             'min' => 1,
             'max' => 400]
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
      Dropdown::showNumber("timeout_networkdiscovery", [
             'value' => $this->fields["timeout_networkdiscovery"],
             'toadd' => [ __('General setup') ],
             'min' => 1,
             'max' => 60]
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
      Dropdown::showNumber("threads_networkinventory", [
             'value' => $this->fields["threads_networkinventory"],
             'toadd' => [ __('General setup') ],
             'min' => 1,
             'max' => 400]
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
      Dropdown::showNumber("timeout_networkinventory", [
             'value' => $this->fields["timeout_networkinventory"],
             'toadd' => [ __('General setup') ],
             'min' => 1,
             'max' => 60]
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

      return true;
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

      $iterator = $DB->request([
         'FROM'   => $this->getTable(),
         'WHERE'  => ['device_id' => $device_id],
         'START'  => 0,
         'LIMIT'  => 1
      ]);

      $agent = [];
      if (count($iterator)) {
         $agent = $iterator->next();
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
         $a_agent = $this->find(['device_id' => $arrayinventory['DEVICEID']], [], 1);
         if (empty($a_agent)) {
            $a_input = [];
            if (isset($arrayinventory['TOKEN'])) {
               $a_input['token'] = $arrayinventory['TOKEN'];
            }
            $a_input['name']         = $arrayinventory['DEVICEID'];
            $a_input['device_id']    = $arrayinventory['DEVICEID'];
            $a_input['entities_id']  = 0;
            $a_input['last_contact'] = date("Y-m-d H:i:s");
            $a_input['useragent'] = filter_input(INPUT_SERVER, "HTTP_USER_AGENT");
            // Set default number of threads for network tasks to 0 to follow general setup
            $a_input['threads_networkdiscovery'] = 0;
            $a_input['threads_networkinventory'] = 0;
            $agents_id = $this->add($a_input);
            if ($agents_id) {
               return $agents_id;
            }
         } else {
            foreach ($a_agent as $data) {
               $input = [];
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

      $agent = $this->find(['computers_id' => $computers_id], [], 1);
      if (count($agent) == 1) {
         $data = current($agent);
         $this->getFromDB($data['id']);
         return $data['id'];
      }
      return false;
   }


   /**
    * Get the agents id of a list of computers id
    *
    * @param array $computer_ids list of id of computers
    * @return array list of agents [id] => information of agent
    */
   function getAgentsFromComputers($computer_ids = []) {

      if (count($computer_ids) == 0) {
         return [];
      }
      $agents = $this->find(['computers_id' => $computer_ids]);
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

      $a_agent = $this->find(['computers_id' => $computers_id], [], 1);
      // Is this computer already linked to an agent?
      $agent = array_shift($a_agent);
      if (is_array($agent)) {

         // relation
         if ($agent['device_id'] != $device_id
                 || $agent['entities_id'] != $entities_id) {
            $input = [];
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
            ['device_id' => $device_id, 'computers_id' => ['!=', $computers_id]]);
         foreach ($oldAgents as $oldAgent) {
            $input = [];
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
         return true;
      } else { // This is a new computer
         // Link agent with computer
         $agent = $this->infoByKey($device_id);
         if (isset($agent['id'])) {
             $agent['computers_id'] = $computers_id;
             $agent['entities_id']  = $entities_id;
             $this->update($agent);
             return true;
         }
      }
      return false;
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
      $fi_path = Plugin::getWebDir('fusioninventory');

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo __('Status')."&nbsp;:";
      echo "</td>";
      echo "<td>";

      $load_anim   = '<i class="fas fa-sync fa-spin fa-fw"></i>';

      echo Html::scriptBlock("$(function() {
         var waiting = false;

         var refresh_status = function(display_refresh) {
            var display_refresh = (typeof display_refresh !== 'undefined') ? display_refresh : true;
            $('#agent_status').html('$load_anim');
            $('#refresh_status').hide();
            $('#force_inventory_button').hide();

            $.get('$fi_path/ajax/remote_status.php', {
               id: $agent_id,
               action: 'get_status'
            }, function(answer) {
               if (typeof answer.waiting != 'undefined'
                   && answer.waiting == true) {
                  $('#force_inventory_button').show();
                  waiting = true;
               }

               $('#agent_status').html(answer.message);
               if (display_refresh) {
                  $('#refresh_status').show();
               }
            });
         };

         var force_inventory = function() {
            $('#agent_status').html('$load_anim');
            $('#refresh_status').hide();
            waiting = false;
            $('#force_inventory_button').hide();
            $.get('$fi_path/ajax/remote_status.php', {
               id: $agent_id,
               action: 'start_agent'
            }, function(answer) {
               refresh_status(false);
               displayAjaxMessageAfterRedirect();

               // add a loop for checking status (set a max iterations to avoid infinite looping)
               var loop_index = 0;
               var myloop = setInterval(function() {
                  if (loop_index > 30 || waiting) {
                     clearInterval(myloop);
                     $('#refresh_status').show();
                     return;
                  }
                  refresh_status(false);
                  loop_index++;
               }, 2000);
            });
         };

         $(document)
            .ready(function() {
                refresh_status();
            })
            .on('click', '#refresh_status', function() {
               refresh_status();
            })
            .on('click', '#force_inventory_button', function() {
               force_inventory();
            });
      });");
      echo "<span id='refresh_status'><i class='fas fa-sync'></i></span>";
      echo "<span id='agent_status'>".
           __("not yet requested, refresh?", 'fusioninventory').
           "</span>";
      echo "</td>";

      echo "<td colspan='2'>";
      echo "<span id='force_inventory_button'><i class='fas fa-bolt'></i>".
           __('Force inventory', 'fusioninventory').
           "</span>";
      echo "</td>";
      echo "</tr>";
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

      $ctx = stream_context_create([
         'http' => [
            'timeout' => 1
            ]
         ]
      );

      $contents="";
      $url_ok = null;
      $url_headers=[];
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

      $status = [
         "url_ok" => $url_ok,
         "message" => ""
      ];

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

      $ret = false;

      PluginFusioninventoryDisplay::disableDebug();
      $urls = $this->getAgentRunURLs();

      $ctx = stream_context_create(['http' => ['timeout' => 2]]);
      foreach ($urls as $url) {
         if (!$ret) {
            if (@file_get_contents($url, 0, $ctx) !== false) {
               $ret = true;
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
         $a_version              = [];
         $a_version["INVENTORY"] = $versionTmp;
      }
      $a_version[$module] = $version;
      $input = [];
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
      $agents = getAllDataFromTable(
         'glpi_plugin_fusioninventory_agents',
         [
            'device_id' => $device_id,
            'lock'      => 0
         ]
      );
      if (!empty($agents)) {
         return array_pop($agents);
      } else {
         return false;
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
      $url_addresses = [];

      if (isset($this->fields['id'])) {
         $computer = $this->getAssociatedComputer();
         if ($this->fields['agent_port'] != ''
                 && is_numeric($this->fields['agent_port'])) {
            $port = $this->fields['agent_port'];
         }
         if ($computer->fields["name"] && $computer->fields["name"] != "localhost") {
            array_push($url_addresses, "http://".$computer->fields["name"].
               ":".$port);

            $ditem = new Domain_Item();
            if ($ditem->getFromDBByCrit(['itemtype' => 'Computer', 'items_id' => $computer->fields['id']])) {
               $domain = new Domain();
               $domain->getFromDB($ditem->fields['domains_id']);
               array_push($url_addresses, "http://".
                  $computer->fields["name"].'.'.
                  $domain->fields["name"].
                  ":".$port);
            }
         }
      }

      // Guess the machine name from the DEVICEID,
      // useful when Windows domain != DNS domain
      $stack = [];
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
      $ret = [];

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
      $ret = [];

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
    * @param Computer the computer
    * @param integer $colspan the number of columns of the form (2 by default)
    */
   function showInfoForComputer(Computer $computer, $colspan = 2) {

      if ($this->getAgentWithComputerid($computer->getID())) {

         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('Agent', 'fusioninventory').'</td>';
         echo '<td>'.$this->getLink(1).'</td>';

         if ($colspan == 2) {
            echo '</tr>';
            echo '<tr class="tab_bg_1">';
         }
         echo '<td>'.__('Useragent', 'fusioninventory').'</td>';
         echo '<td>'.$this->fields['useragent'].'</td>';
         echo '</tr>';

         $this->showRemoteStatus($computer);

         echo '<tr class="tab_bg_1">';
         echo '<td>'.__('FusionInventory tag', 'fusioninventory').'</td>';
         echo '<td>'.$this->fields['tag'].'</td>';
         if ($colspan == 2) {
            echo '</tr>';
            echo '<tr class="tab_bg_1">';
         }

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
    * @return boolean true if successful, otherwise false
    */
   static function cronCleanoldagents($task = null) {
      global $DB;

      $pfConfig = new PluginFusioninventoryConfig();
      $pfAgent  = new PluginFusioninventoryAgent();

      $retentiontime = $pfConfig->getValue('agents_old_days');
      if ($retentiontime == 0) {
         return true;
      }

      $iterator = $DB->request([
         'FROM'   => 'glpi_plugin_fusioninventory_agents',
         'WHERE'  => [
            'last_contact' => ['<', new QueryExpression("date_add(now(), interval -".$retentiontime." day)")]
         ]
      ]);

      if (count($iterator)) {
         $cron_status = false;
         $action = $pfConfig->getValue('agents_action');
         if ($action == PluginFusioninventoryConfig::ACTION_CLEAN) {
            //delete agents
            while ($data = $iterator->next()) {
               $pfAgent->delete($data);
               $task->addVolume(1);
               $cron_status = true;
            }
         } else {
            //change status of agents
            while ($data = $iterator->next()) {
               $computer = new Computer();
               if ($computer->getFromDB($data['computers_id'])) {
                  $computer->update([
                      'id' => $data['computers_id'],
                      'states_id' => $pfConfig->getValue('agents_status')]);
                  $task->addVolume(1);
                  $cron_status = true;
               }
            }
         }
      }
      return $cron_status;
   }


}

