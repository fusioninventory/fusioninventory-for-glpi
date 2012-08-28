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

class PluginFusioninventoryAgentmodule extends CommonDBTM {
   

   /**
    * Display tab
    * 
    * @global array $LANG
    * 
    * @param CommonGLPI $item
    * @param integer $withtemplate
    * 
    * @return varchar name of the tab(s) to display
    */
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $LANG;

      if ($item->getType()=='PluginFusioninventoryConfig') {
         return $LANG['plugin_fusioninventory']['agents'][27];
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
    * @return boolean true
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {

      if ($item->getType()=='PluginFusioninventoryConfig') {
         $pfAgentmodule = new self();
         $pfAgentmodule->showForm();
      }
      return true;
   }
   

   
   /**
   * Display form forconfiguration of agent modules
   *
   * @return bool true if form is ok
   *
   **/
   function showForm() {
      global $LANG;

      $pfAgent = new PluginFusioninventoryAgent();
      $plugin = new Plugin();

      $a_modules = $this->find();
      foreach ($a_modules as $data) {
         $plugin->getFromDB($data['plugins_id']);
         if ($plugin->isActivated($plugin->fields['directory'])) {
            echo "<form name='form_ic' method='post' action='". Toolbox::getItemTypeFormURL(__CLASS__)."'>";
            echo "<table class='tab_cadre_fixe'>";
            echo "<tr>";
            echo "<th width='130'>".$LANG['plugin_fusioninventory']['task'][26]."</th>";
            echo "<th width='180'>".$LANG['plugin_fusioninventory']['agents'][34]."</th>";
            echo "<th>".$LANG['plugin_fusioninventory']['agents'][45]."</th>";
            echo "</tr>";

            echo "<tr class='tab_bg_1'>";
            $a_methods = PluginFusioninventoryStaticmisc::getmethods();
            $modulename = $data["modulename"];
            $use_rest = false;

            foreach ($a_methods as $datamod) {

               if ((strtolower($data["modulename"]) == strtolower($datamod['method'])) ||
                   isset($datamod['task']) 
                     && (strtolower($data["modulename"]) == strtolower($datamod['task']))) {
                  if (isset($datamod['use_rest']) && $datamod['use_rest'] == true) {
                     $use_rest = true;
                  }
                  if (isset($datamod['name'])) {
                     $modulename = $datamod['name'];
                  }
                  break;
               }
            }
            // Hack for snmpquery
               if ($modulename == 'SNMPQUERY') {
                  $modulename = $LANG['plugin_fusinvsnmp']['config'][3];
               }
            echo "<td align='center'><strong>".$modulename."</strong></td>";
            echo "<td align='center'>";
            $checked = $data['is_active'];
            $check = "";
            if ($checked == 1)
               $check = "checked='checked'";
            echo "<input type='checkbox' name='activation' value='Activation' ".$check." />";
            echo "</td>";
            echo "<td>";
               echo "<table>";
               echo "<tr>";
               echo "<td>";
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
                  $LANG['buttons'][8] . " >>'>";
               echo "<br><br>";
               echo "<input type='submit' class='submit' name='agent_delete' value='<< " .
                  $LANG['buttons'][6] . "'>";
               echo "</td>";
               echo "<td>";

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

            if ($use_rest) {
               echo "<tr>";
               echo "<td class='tab_bg_2 center'>";
               echo $LANG['plugin_fusioninventory']['agents'][41];
               echo "</td><td colspan='2'>";
               echo "<input type='text' name='url' value='".$data['url']."' size='70'>";
               echo "</td>";
               echo "</tr>";
            } else {
               echo "<input type='hidden' name='url' value='' />";
            }

            echo "<tr>";
            echo "<td class='tab_bg_2 center' colspan='3'>";
            echo "<input type='submit' name='update' value=\"".$LANG['buttons'][7]."\" class='submit'>";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            echo "<input type='hidden' name='id' value='".$data['id']."' />";
            Html::closeForm();
            echo "<br/>";
         }
      }
      return true;
   }



   /**
   * Display form to add exception of modules activation for each agent
   *
   * @param interger $items_id ID of the agent
   * @param array $options
   *
   * @return bool true if form is ok
   *
   **/
   function showFormAgentException($items_id, $options=array()) {
      global $LANG,$CFG_GLPI;

      $plugin = new Plugin();
      
      $canedit = 1;
      echo "<br/>";
      if ($canedit) {
         echo "<form name='form_ic' method='post' action='".$CFG_GLPI['root_doc'].
               "/plugins/fusioninventory/front/agentmodule.form.php'>";
      }
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";
      echo "<th>".$LANG['plugin_fusioninventory']['task'][26]."</th>";
      echo "<th>Activation</th>";
      echo "<th>".$LANG['plugin_fusioninventory']['task'][26]."</th>";
      echo "<th>Activation</th>";
      echo "</tr>";

      $a_modules = $this->find();
      $i = 0;
      foreach ($a_modules as $data) {
         $plugin->getFromDB($data['plugins_id']);
         if ($plugin->isActivated($plugin->fields['directory'])) {
            if ($i == 0)
               echo "<tr class='tab_bg_1'>";
            $a_methods = PluginFusioninventoryStaticmisc::getmethods();
            $modulename = $data["modulename"];
            foreach ($a_methods as $datamod) {
               if (isset($datamod['name'])
                       AND (strtolower($data["modulename"]) == strtolower($datamod['method']))) {
                  $modulename = $datamod['name'];
               }
            }
            // Hack for snmpquery
               if ($modulename == 'SNMPQUERY') {
                  $modulename = $LANG['plugin_fusinvsnmp']['config'][3];
               }
            echo "<td width='50%'>".$modulename." :</td>";
            echo "<td align='center'>";

            $checked = $data['is_active'];
            $a_agentList = importArrayFromDB($data['exceptions']);
            if (in_array($items_id, $a_agentList)) {
               if ($checked == 1)
                  $checked = 0;
               else
                  $checked = 1;
            }
            $check = "";
            if ($checked == 1)
               $check = "checked='checked'";
            echo "<input type='checkbox' name='activation-".$data["modulename"]."' value='Activation' ".$check." />";
            echo "</td>";
            if ($i == 1) {
               echo "</tr>";
               $i = -1;
            }
            $i++;
         }
      }
      if ($i == 1) {
         echo "<td></td>";
         echo "<td></td>";
         echo "</tr>";
      }
      if ($canedit) {
         echo "<tr>";
         echo "<td class='tab_bg_2 center' colspan='4'>";
         echo "<input type='hidden' name='id' value=\"".$items_id."\">";
         echo "<input type='submit' name='updateexceptions' value=\"".$LANG['buttons'][7]."\" class='submit'>";
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
                  $sep  = ',';
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
                  $sep  = ',';
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
      $a_agents = array();
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
   function getAgentCanDo($module_name, $items_id) {
      
      if ($module_name == 'SNMPINVENTORY') {
         $module_name = 'SNMPQUERY';
      }
      $agentModule = $this->getActivationExceptions($module_name);

      if ($agentModule['is_active'] == 0) {
         $a_agentList = importArrayFromDB($agentModule['exceptions']);
         if (in_array($items_id, $a_agentList)) {
            return true;
         } else {
            return false;
         }
      } else {
         $a_agentList = importArrayFromDB($agentModule['exceptions']);
         if (in_array($items_id, $a_agentList)) {
            return false;
         } else {
            return true;
         } 
      }
   }
   


   /**
   * Delete module line
   *
   * @param $plugins_id integer id of the plugin (with modules it manage)
   *
   * @return nothing
   *
   **/
   function deleteModule($plugins_id) {

      $a_agentmodule = $this->find("`plugins_id`='".$plugins_id."'");
      foreach($a_agentmodule as $data) {
         $this->fields['id'] = $data['id'];
         $this->deleteFromDB();
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
   static function getUrlForModule($modulename) {
      global $DB;

      $query = "SELECT url_base FROM `glpi_configs` LIMIT 1";
      $result = $DB->query($query);
      $data = $DB->fetch_assoc($result);
      return
         $data['url_base'].
         '/plugins/fusinv'.
         strtolower($modulename).
         '/b/'.
         strtolower($modulename);
   }
}

?>
