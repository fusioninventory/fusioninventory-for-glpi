<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2016 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2016 FusionInventory team
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

class PluginFusioninventoryAgentmodule extends CommonDBTM {

   static $rightname = "plugin_fusioninventory_agent";

   /**
    * Display tab
    *
    * @param CommonGLPI $item
    * @param integer $withtemplate
    *
    * @return varchar name of the tab(s) to display
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {

      if ($item->getType()=='PluginFusioninventoryConfig') {
         return __('Agents modules', 'fusioninventory');
      } else if ($item->getType()=='PluginFusioninventoryAgent') {
         return __('Agents modules', 'fusioninventory');
      }
      return '';
   }



   /**
    * Display content of tab
    *
    * @param CommonGLPI $item
    * @param integer $tabnum
    * @param interger $withtemplate
    *
    * @return boolean TRUE
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType()=='PluginFusioninventoryConfig') {
         $pfAgentmodule = new self();
         $pfAgentmodule->showForm();
      } else if ($item->getType()=='PluginFusioninventoryAgent') {
         $pfAgentmodule = new self();
         $pfAgentmodule->showFormAgentException($item->getID());
      }
      return TRUE;
   }



   /**
   * Display form forconfiguration of agent modules
   *
   * @return bool TRUE if form is ok
   *
   **/
   function showForm() {

      $pfAgent = new PluginFusioninventoryAgent();

      $a_modules = $this->find();
      foreach ($a_modules as $data) {
         echo "<form name='form_ic' method='post' action='".
                 Toolbox::getItemTypeFormURL(__CLASS__)."'>";
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr>";
         echo "<th width='130'>".__('Module', 'fusioninventory')."</th>";
         echo "<th width='180'>".__('Activation (by default)', 'fusioninventory')."</th>";
         echo "<th>".__('Exceptions', 'fusioninventory')."</th>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         $a_methods = PluginFusioninventoryStaticmisc::getmethods();
         $modulename = $data["modulename"];
         $use_rest = FALSE;

         foreach ($a_methods as $datamod) {
            if ((strtolower($data["modulename"]) == strtolower($datamod['method'])) ||
                isset($datamod['task'])
                  && (strtolower($data["modulename"]) == strtolower($datamod['task']))) {
               if (isset($datamod['use_rest']) && $datamod['use_rest'] == TRUE) {
                  $use_rest = TRUE;
               }
               if (isset($datamod['name'])) {
                  $modulename = $datamod['name'];
               }
               break;
            }
         }
         // Hack for snmpquery
            if ($data["modulename"] == 'SNMPQUERY') {
               $modulename = __('Network inventory (SNMP)', 'fusioninventory');

            }
         // Hack for deploy
            if ($data["modulename"] == 'DEPLOY') {
               $modulename = __('Package deployment', 'fusioninventory');

            }

         echo "<td align='center'><strong>".$modulename."</strong></td>";
         echo "<td align='center'>";
         $checked = $data['is_active'];

         Html::showCheckbox(array('name'    => 'activation', 
                                  'value'   => '1', 
                                  'checked' => $checked));
         echo "</td>";
         echo "<td>";
            echo "<table>";
            echo "<tr>";
            echo "<td width='45%'>";
            $a_agentList = importArrayFromDB($data['exceptions']);
            $a_used = array();
            foreach ($a_agentList as $agent_id) {
               $a_used[] = $agent_id;
            }
            Dropdown::show("PluginFusioninventoryAgent", array("name" => "agent_to_add[]",
                                                               "used" => $a_used));
            echo "</td>";
            echo "<td align='center'>";
            echo "<input type='submit' class='submit' name='agent_add' value='" .
               __s('Add') . " >>'>";
            echo "<br><br>";
            echo "<input type='submit' class='submit' name='agent_delete' value='<< " .
               __s('Delete') . "'>";
            echo "</td>";
            echo "<td width='45%'>";

            echo "<select size='6' name='agent_to_delete[]'>";
            foreach ($a_agentList as $agent_id) {
               $pfAgent->getFromDB($agent_id);
               echo "<option value='".$agent_id."'>".$pfAgent->getName()."</option>";
            }
            echo "</select>";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
         echo "</td>";

         echo "<tr>";
         echo "<td class='tab_bg_2 center' colspan='3'>";
         echo "<input type='submit' name='update' value=\"".__s('Update')."\" class='submit'>";
         echo "</td>";
         echo "</tr>";
         echo "</table>";
         echo Html::hidden('id', array('value' => $data['id']));
         Html::closeForm();
         echo "<br/>";
      }
      return TRUE;
   }



   /**
   * Display form to add exception of modules activation for each agent
   *
   * @param interger $items_id ID of the agent
   *
   * @return bool TRUE if form is ok
   *
   **/
   function showFormAgentException($items_id) {
      global $CFG_GLPI;

      $pfAgent = new PluginFusioninventoryAgent();
      $pfAgent->getFromDB($items_id);
      $canedit = $pfAgent->can($items_id, UPDATE);

      echo "<br/>";
      if ($canedit) {
         echo "<form name='form_ic' method='post' action='".$CFG_GLPI['root_doc'].
               "/plugins/fusioninventory/front/agentmodule.form.php'>";
      }
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";
      echo "<th>".__('Module', 'fusioninventory')."</th>";
      echo "<th>Activation</th>";
      echo "<th>".__('Module', 'fusioninventory')."</th>";
      echo "<th>Activation</th>";
      echo "</tr>";

      $a_modules = $this->find();
      $i = 0;
      $a_methods = PluginFusioninventoryStaticmisc::getmethods();
      foreach ($a_modules as $data) {
         if ($i == 0) {
            echo "<tr class='tab_bg_1'>";
         }
         $modulename = $data["modulename"];
         foreach ($a_methods as $datamod) {
            if ((strtolower($data["modulename"]) == strtolower($datamod['method'])) ||
                isset($datamod['task'])
                  && (strtolower($data["modulename"]) == strtolower($datamod['task']))) {
               if (isset($datamod['name'])) {
                  $modulename = $datamod['name'];
               }
               break;
            }
         }
         // Hack for snmpquery
            if ($data["modulename"] == 'SNMPQUERY') {
               $modulename = __('Network inventory (SNMP)', 'fusioninventory');

            }
         // Hack for deploy
            if ($data["modulename"] == 'DEPLOY') {
               $modulename = __('Package deployment', 'fusioninventory');

            }

         echo "<td width='50%'>".$modulename." :</td>";
         echo "<td align='center'>";

         $checked = $data['is_active'];
         $a_agentList = importArrayFromDB($data['exceptions']);
         if (in_array($items_id, $a_agentList)) {
            if ($checked == 1) {
               $checked = 0;
            } else {
               $checked = 1;
            }
         }
         $check = "";
         Html::showCheckbox(array('name'    => "activation-".$data["modulename"], 
                                  'value'   => '1', 
                                  'checked' => $checked));
         echo "</td>";
         if ($i == 1) {
            echo "</tr>";
            $i = -1;
         }
         $i++;
      }
      if ($i == 1) {
         echo "<td></td>";
         echo "<td></td>";
         echo "</tr>";
      }
      if ($canedit) {
         echo "<tr>";
         echo "<td class='tab_bg_2 center' colspan='4'>";
         echo Html::hidden('id', array('value' => $items_id));
         echo "<input type='submit' name='updateexceptions' ".
                 "value=\"".__('Update')."\" class='submit'>";
         echo "</td>";
         echo "</tr>";
         echo "</table>";
         Html::closeForm();
      } else {
         echo "</table>";
      }
   }



   /**
   * Get data (activation, exceptions...) for a module
   *
   * @param $module_name value Name of the module
   *
   * @return array all DB fields for this module
   *
   **/
   function getActivationExceptions($module_name) {
      $a_modules = $this->find("`modulename`='".$module_name."'", "", 1);
      return current($a_modules);
   }



   /**
   * Get agents can do a "module name"
   *
   * @param $module_name value Name of the module
   *
   * @return array of agents
   *
   **/
   function getAgentsCanDo($module_name) {

      $pfAgent = new PluginFusioninventoryAgent();

      if ($module_name == 'SNMPINVENTORY') {
         $module_name = 'SNMPQUERY';
      }
      $agentModule = $this->getActivationExceptions($module_name);

      $where = "";
      if ($agentModule['is_active'] == 0) {
         $a_agentList = importArrayFromDB($agentModule['exceptions']);
         if (count($a_agentList) > 0) {
            $where = " `id` IN (";
            $i = 0;
            $sep  = '';
            foreach ($a_agentList as $agent_id) {
               if ($i> 0) {
                  $sep  = ', ';
               }
               $where .= $sep.$agent_id;
               $i++;
            }
            $where .= ") ";
            if (isset($_SESSION['glpiactiveentities_string'])) {
               $where .= getEntitiesRestrictRequest("AND", $pfAgent->getTable());
            }
         } else {
            return array();
         }
      } else {
         $a_agentList = importArrayFromDB($agentModule['exceptions']);
         if (count($a_agentList) > 0) {
            $where = " `id` NOT IN (";
            $i = 0;
            $sep  = '';
            foreach ($a_agentList as $agent_id) {
               if ($i> 0) {
                  $sep  = ', ';
               }
               $where .= $sep.$agent_id;
               $i++;
            }
            $where .= ") ";
            if (isset($_SESSION['glpiactiveentities_string'])) {
               $where .= getEntitiesRestrictRequest("AND", $pfAgent->getTable());
            }
         }
      }
      $a_agents = $pfAgent->find($where);
      return $a_agents;
   }



   /**
   * Get if agent allowed to do this TASK
   *
   * @param $module_name value Name of the module
   * @param $items_id integer id of the agent
   *
   * @return bool
   *
   **/
   function isAgentCanDo($module_name, $items_id) {

      $agentModule = $this->getActivationExceptions($module_name);

      if ($agentModule['is_active'] == 0) {
         $a_agentList = importArrayFromDB($agentModule['exceptions']);
         if (in_array($items_id, $a_agentList)) {
            return TRUE;
         } else {
            return FALSE;
         }
      } else {
         $a_agentList = importArrayFromDB($agentModule['exceptions']);
         if (in_array($items_id, $a_agentList)) {
            return FALSE;
         } else {
            return TRUE;
         }
      }
   }

   /**
    * agent's ids with module activations.
    * @since 0.85+1.0
    * @param $agent_ids List of agent's ids.
    * @param $methods Methods requested.
    * @return The list filtered by activated on the requested methods.
    */
   function filterAgentsByMethods($agent_ids, $methods=array()) {

      $available_methods = PluginFusioninventoryStaticmisc::task_methods();
      $methods_requested = array();

      foreach($available_methods as $method_info) {
         if (in_array($method_info, $methods)){
            $methods_requested = $method_info;
         }
      }
   }

   /**
   * Get URL for module (for REST)
   *
   * @param $module value name of module
   *
   * @return nothing
   *
   **/
   static function getUrlForModule($modulename, $entities_id=-1) {
      // Get current entity URL if it exists ...
      $pfEntity = new PluginFusioninventoryEntity();
      $baseUrl = $pfEntity->getValue('agent_base_url', $entities_id);
      if (! empty($baseUrl)) {
         PluginFusioninventoryToolbox::logIfExtradebug(
            "pluginFusioninventory-agent-url",
            "Entity ".$entities_id.", agent base URL: ".$baseUrl
         );

         if ($baseUrl != 'N/A') {
            return $baseUrl.'/plugins/fusioninventory/b/'.
                    strtolower($modulename).'/';
         }
      }

      // ... else use global plugin configuration parameter.
      $config = new PluginFusioninventoryConfig();
      if (strlen($pfEntity->getValue('agent_base_url', $entities_id))<10) {
         PluginFusioninventoryCommunicationRest::sendError();
         exit;
         // die ("agent_base_url is unset!\n");
      }

      PluginFusioninventoryToolbox::logIfExtradebug(
         "pluginFusioninventory-agent-url",
         "Global configuration URL: ".$pfEntity->getValue('agent_base_url', $entities_id)
      );

      # Construct the path to the JSON back from the agent_base_url.
      # agent_base_url is the initial URL used by the agent
      return $pfEntity->getValue('agent_base_url', $entities_id).'/plugins/fusioninventory/b/'.
              strtolower($modulename).'/';
   }



   /**
    * Get modules in the table
    */
   static function getModules() {
      $a_modules = array();
      $a_data = getAllDatasFromTable(PluginFusioninventoryAgentmodule::getTable());
      foreach ($a_data as $data) {
         $a_modules[] = $data['modulename'];
      }
      return $a_modules;
   }
}

?>
