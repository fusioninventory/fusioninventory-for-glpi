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
   @author    Vincent Mazzoni
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


class PluginFusinvsnmpAgentconfig extends CommonDBTM {

   function canCreate() {
      return PluginFusioninventoryProfile::haveRight("fusinvsnmp", "configuration", "w");
   }

   function canView() {
      return PluginFusioninventoryProfile::haveRight("fusinvsnmp", "configuration", "r");
   }

   function canDelete() {
      return false;
   }

   

   /**
    * Display SNMP configuration form of an agent
    *
    * @global array $LANG
    * 
    * @param integer $agents_id id of the agent
    * @param array $options
    * 
    * @return boolean true 
    */
   function showForm($agents_id, $options=array()) {
      global $LANG;

      $a_agent = $this->find("`plugin_fusioninventory_agents_id`='".$agents_id."'");
      if (count($a_agent) > 0) {
         foreach ($a_agent as $data) {
            $this->getFromDB($data['id']);
         }
      } else {
         $this->getEmpty();
         $pfConfig = new PluginFusioninventoryConfig();
         $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
         unset($this->fields['id']);
         $this->fields['plugin_fusioninventory_agents_id'] = $agents_id;
         $this->fields['threads_netdiscovery'] =
                 $pfConfig->getValue($plugins_id, 'threads_netdiscovery');
         $this->fields['threads_snmpquery'] =
                 $pfConfig->getValue($plugins_id, 'threads_snmpquery');
         $this->fields['senddico'] = 0;
         $this->add($this->fields);
      }

      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusinvsnmp']['agents'][24]."&nbsp;(".strtolower($LANG['plugin_fusinvsnmp']['config'][4]).")&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("threads_netdiscovery", $this->fields["threads_netdiscovery"],1,400);
      echo "</td>";
      echo "<td>".$LANG['plugin_fusinvsnmp']['agents'][24]."&nbsp;(".strtolower($LANG['plugin_fusinvsnmp']['config'][3]).")&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("threads_snmpquery", $this->fields["threads_snmpquery"],1,400);
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons($options);

      return true;
   }

   
   
   /**
    * Load config of an agent
    *
    * @param integer $agents_id require the agent id 
    * 
    * @return nothing data stored in $this
    */
   function loadAgentconfig($agents_id) {

      $a_agent = $this->find("`plugin_fusioninventory_agents_id`='".$agents_id."'", "", 1);
      if (count($a_agent) > 0) {
         foreach ($a_agent as $data) {
            $this->getFromDB($data['id']);
            return;
         }
      }
      // If we are here, agentconfig has been not found
      $this->getEmpty();
      $pfConfig = new PluginFusioninventoryConfig();
      $plugins_id = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
      $this->fields['plugin_fusioninventory_agents_id'] = $agents_id;
      $this->fields['threads_netdiscovery'] =
              $pfConfig->getValue($plugins_id, 'threads_netdiscovery');
      $this->fields['threads_snmpquery'] =
              $pfConfig->getValue($plugins_id, 'threads_snmpquery');
      $this->fields['senddico'] = 0;
      unset($this->fields['id']);
      $this->add($this->fields);
   }
}

?>