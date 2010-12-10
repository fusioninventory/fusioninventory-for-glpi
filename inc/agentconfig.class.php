<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Copynetwork (C) 2003-2010 by the INDEPNET Development Team.

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
// Original Author of file: Vincent MAZZONI
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
	die("Sorry. You can't access directly to this file");
}


class PluginFusinvsnmpAgentconfig extends CommonDBTM {

   function __construct() {
      $this->table = "glpi_plugin_fusinvsnmp_agentconfigs";
      $this->type = 'PluginFusinvsnmpAgentconfig';
   }

   function showForm($id, $options=array()) {
      global $DB,$CFG_GLPI,$LANG;

      $a_agent = $this->find("`plugin_fusioninventory_agents_id`='".$id."'");
      if (count($a_agent) > 0) {
         foreach ($a_agent as $data) {
            $this->getFromDB($data['id']);
         }
      } else {
         $this->getEmpty();
         unset($this->fields['id']);
         $this->fields['plugin_fusioninventory_agents_id'] = $id;
         $this->fields['threads_netdiscovery'] = 1;
         $this->fields['threads_snmpquery'] = 1;
         $this->add($this->fields);
      }

      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusinvsnmp']['agents'][26]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo $this->fields["version_netdiscovery"];
      echo "</td>";
      echo "<td>".$LANG['plugin_fusinvsnmp']['agents'][27]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo $this->fields["version_snmpquery"];
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['plugin_fusinvsnmp']['agents'][24]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("threads_netdiscovery", $this->fields["threads_netdiscovery"],1,400);
      echo "</td>";
      echo "<td>".$LANG['plugin_fusinvsnmp']['agents'][24]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("threads_snmpquery", $this->fields["threads_snmpquery"],1,400);
      echo "</td>";
      echo "</tr>";
      

      $this->showFormButtons($options);

      return true;
   }

   function loadAgentconfig($agents_id) {

      $a_agent = $this->find("`plugin_fusioninventory_agents_id`='".$agents_id."'");
      if (count($a_agent) > 0) {
         foreach ($a_agent as $data) {
            $this->getFromDB($data['id']);
            return;
         }
      }
      // If we are here, agentconfig has been not found
      $this->getEmpty();
      $this->fields['plugin_fusioninventory_agents_id'] = $agents_id;
      $this->fields['threads_netdiscovery'] = 1;
      $this->fields['threads_snmpquery'] = 1;
      unset($this->fields['id']);
      $this->add($this->fields);
   }


}

?>