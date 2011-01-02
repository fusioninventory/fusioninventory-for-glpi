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

class PluginFusioninventoryAgentmodule extends CommonDBTM {

   
   // Configuration of agentmodule
   function showForm() {
      global $DB,$CFG_GLPI,$LANG;

      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent;

      $a_modules = $this->find();
      foreach ($a_modules as $data) {
         echo "<form name='form_ic' method='post' action='".GLPI_ROOT.
               "/plugins/fusioninventory/front/agentmodule.form.php'>";
         echo "<table class='tab_cadre_fixe'>";
         echo "<tr>";
         echo "<th width='130'>Module</th>";
         echo "<th width='180'>Activation (by default)</th>";
         echo "<th>Exceptions</th>";
         echo "</tr>";
         
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'><strong>".$data["modulename"]."</strong></td>";
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
            Dropdown::show("PluginFusioninventoryAgent", array("name" => "agent_to_add[]", "used" => $a_used));
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
               $PluginFusioninventoryAgent->getFromDB($agent_id);
               echo "<option value='".$agent_id."'>".$PluginFusioninventoryAgent->getName()."</option>";
            }
            echo "</select>";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
         echo "</td>";
         echo "<tr>";
         echo "<td class='tab_bg_2 center' colspan='3'>";
         echo "<input type='submit' name='update' value=\"".$LANG['buttons'][7]."\" class='submit'>";
         echo "</td>";
         echo "</tr>";
         echo "</table>";
         echo "<input type='hidden' name='id' value='".$data['id']."' />";
         echo "</form>";
         echo "<br/>";
      }
      return true;
   }



   // add exception of activation of modules in each agent
   function showFormAgentException($id, $options=array()) {
      global $LANG;

      $canedit = 1;
      echo "<br/>";
      if ($canedit) {
         echo "<form name='form_ic' method='post' action='".GLPI_ROOT.
               "/plugins/fusioninventory/front/agentmodule.form.php'>";
      }
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";
      echo "<th>Module</th>";
      echo "<th>Activation</th>";
      echo "<th>Module</th>";
      echo "<th>Activation</th>";
      echo "</tr>";

      $a_modules = $this->find();
      $i = 0;
      foreach ($a_modules as $data) {
         if ($i == 0)
            echo "<tr class='tab_bg_1'>";
         echo "<td width='50%'>".$data["modulename"]." :</td>";
         echo "<td align='center'>";

         $checked = $data['is_active'];
         $a_agentList = importArrayFromDB($data['exceptions']);
         if (in_array($id, $a_agentList)) {
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
      if ($i == 1) {
         echo "<td></td>";
         echo "<td></td>";
         echo "</tr>";
      }
      if ($canedit) {
         echo "<tr>";
         echo "<td class='tab_bg_2 center' colspan='4'>";
         echo "<input type='hidden' name='id' value=\"".$id."\">";
         echo "<input type='submit' name='updateexceptions' value=\"".$LANG['buttons'][7]."\" class='submit'>";
         echo "</td>";
         echo "</tr>";
         echo "</table></form>";
      } else {
         echo "</table>";
      }
   }

   

   function getActivationExceptions($module_name) {
      $a_modules = $this->find("`modulename`='".$module_name."' ");
      foreach ($a_modules as $data) {
         return $data;
      }
   }


   
   function getAgentsCanDo($module_name, $items_id=0) {

      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent;

      $agentModule = $this->getActivationExceptions($module_name);
      $where = "";
      if ($agentModule['is_active'] == 0) {
         $a_agentList = importArrayFromDB($agentModule['exceptions']);
         if (count($a_agentList) > 0) {
            $where = " `id` IN (";
            $i = 0;
            $sep  = '';
            foreach ($a_agentList as $agent_id) {
               if (($items_id != '0') AND ($items_id == $agent_id)) {
                  return true;
               }
               if ($i> 0) {
                  $sep  = ',';
               }
               $where .= $sep.$agent_id;
               $i++;
            }
            $where .= ") ";
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
         }
         if ($items_id != '0') {
            $a_agents = $PluginFusioninventoryAgent->find($where);
            if(array_key_exists($items_id, $a_agents)) {
               return true;
            }
         }
      }

      if ($items_id == '0') {
         $a_agents = $PluginFusioninventoryAgent->find($where);
         return $a_agents;
      } else {
         return false;
      }
   }



   function deleteModule($plugins_id) {

      $a_agentmodule = $this->find("`plugins_id`='".$plugins_id."'");
      foreach($a_agentmodule as $data) {
         $this->fields['id'] = $data['id'];
         $this->deleteFromDB();
      }
   }


}

?>