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

class PluginFusinvsnmpIPRange extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_fusinvsnmp_ipranges";
      $this->type  = "PluginFusinvsnmpIPRange";
	}

   static function getTypeName() {
      global $LANG;

      if ((isset($_POST['glpi_tab'])) AND ($_POST['glpi_tab'] == 1)) {
         // Permanent task discovery
         return $LANG['plugin_fusinvsnmp']["task"][15];
      } else if ((isset($_POST['glpi_tab'])) AND ($_POST['glpi_tab'] == 2)) {
         // Permanent task inventory
         return $LANG['plugin_fusinvsnmp']["task"][16];
      } else {
         return $LANG['plugin_fusinvsnmp']["iprange"][5];
      }
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


   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI,$DB;

      $ong = array();
      if ((isset($this->fields['id'])) AND ($this->fields['id'] > 0)){
         $ong[1] = $LANG['plugin_fusinvsnmp']["task"][15];
         $ong[2] = $LANG['plugin_fusinvsnmp']["task"][16];
         $ong[3] = $LANG['plugin_fusioninventory']["task"][18];
      }
      return $ong;
   }


	function showForm($id, $options=array()) {
		global $DB,$CFG_GLPI,$LANG;

		if ($id!='') {
			$this->getFromDB($id);
      } else {
			$this->getEmpty();
      }

      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG["common"][16] . "</td>";
		echo "<td align='center'>";
		echo "<input type='text' name='name' value='".$this->fields["name"]."'/>";
		echo "</td>";

      echo "<th colspan='2'>";
      echo $LANG['plugin_fusinvsnmp']['config'][4];
      echo "</th>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusinvsnmp']["iprange"][0] . "</td>";
		echo "<td align='center'>";
      if (empty($this->fields["ip_start"]))
         $this->fields["ip_start"] = "...";
      $ipexploded = explode(".", $this->fields["ip_start"]);
      $i = 0;
      foreach ($ipexploded as $ipnum) {
         if ($ipnum > 255) {
            $ipexploded[$i] = '';
         }
         $i++;
      }
		echo "<input type='text' value='".$ipexploded[0]."' name='ip_start0' id='ip_start0' size='3' maxlength='3' >.";
		echo "<input type='text' value='".$ipexploded[1]."' name='ip_start1' id='ip_start1' size='3' maxlength='3' >.";
		echo "<input type='text' value='".$ipexploded[2]."' name='ip_start2' id='ip_start2' size='3' maxlength='3' >.";
		echo "<input type='text' value='".$ipexploded[3]."' name='ip_start3' id='ip_start3' size='3' maxlength='3' >";
		echo "</td>";

		echo "<td align='center'>" . $LANG['plugin_fusinvsnmp']["iprange"][3] . "</td>";
		echo "<td align='center'>";
		Dropdown::showYesNo("discover",$this->fields["discover"]);
		echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>" . $LANG['plugin_fusinvsnmp']["iprange"][1] . "</td>";
		echo "<td align='center'>";
      unset($ipexploded);
      if (empty($this->fields["ip_end"]))
         $this->fields["ip_end"] = "...";
      $ipexploded = explode(".", $this->fields["ip_end"]);
      $i = 0;
      foreach ($ipexploded as $ipnum) {
         if ($ipnum > 255) {
            $ipexploded[$i] = '';
         }
         $i++;
      }
      
      echo "<SCRIPT language=javascript>
      function test(id) {
         if (document.getElementById('ip_end' + id).value == '') {
            if (id == 3) {
               document.getElementById('ip_end' + id).value = '254';
            } else {
               document.getElementById('ip_end' + id).value = document.getElementById('ip_start' + id).value;
            }
         }
      }
      </SCRIPT>";

		echo "<input type='text' value='".$ipexploded[0]."' name='ip_end0' id='ip_end0' size='3' maxlength='3' onSelect='test(0)'>.";
		echo "<input type='text' value='".$ipexploded[1]."' name='ip_end1' id='ip_end1' size='3' maxlength='3' onSelect='test(1)'>.";
		echo "<input type='text' value='".$ipexploded[2]."' name='ip_end2' id='ip_end2' size='3' maxlength='3' onSelect='test(2)'>.";
		echo "<input type='text' value='".$ipexploded[3]."' name='ip_end3' id='ip_end3' size='3' maxlength='3' onSelect='test(3)'>";
		echo "</td>";

      echo "<th colspan='2'>";
      echo $LANG['plugin_fusinvsnmp']['config'][3];
      echo "</th>";
		echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      if (isMultiEntitiesMode()) {
         echo "<td align='center'>".$LANG['entity'][0]."</td>";
         echo "<td align='center'>";
         Dropdown::show('Entity',
                        array('name'=>'entities_id',
                              'value'=>$this->fields["entities_id"]));
         echo "</td>";
      } else {
         echo "<td colspan='2'></td>";
      }

      echo "<td align='center'>" . $LANG['plugin_fusinvsnmp']["iprange"][3] . "</td>";
		echo "<td align='center'>";
		Dropdown::showYesNo("query",$this->fields["query"]);
		echo "</td>";

      echo "</tr>";

      $this->showFormButtons($options);
      $this->addDivForTabs();

      }



   function Counter($agent_id, $type) {
      global $DB;
      
      $count = 0;
      switch ($type) {

         case "discover":
            $query = "SELECT COUNT(*) as count FROM `glpi_plugin_fusinvsnmp_ipranges`
               WHERE `plugin_fusioninventory_agents_id_discover`='".$agent_id."'
                  AND `discover`='1' ";
            break;

         case "query":
            $query = "SELECT COUNT(*) as count FROM `glpi_plugin_fusinvsnmp_ipranges`
               WHERE `plugin_fusioninventory_agents_id_query`='".$agent_id."'
                  AND `query`='1' ";
            break;

      }

      if ($result = $DB->query($query)) {
         $res = $DB->fetch_assoc($result);
         $count = $res["count"];
      }
      return $count;
   }


   function ListRange($agent_id, $type) {
      global $DB;

      $ranges = array();
      switch ($type) {

         case "discover":
            $query = "SELECT * FROM `glpi_plugin_fusinvsnmp_ipranges`
               WHERE `plugin_fusioninventory_agents_id_discover`='".$agent_id."'
                  AND `discover`='1' ";
            break;

         case "query":
            $query = "SELECT * FROM `glpi_plugin_fusinvsnmp_ipranges`
               WHERE `plugin_fusioninventory_agents_id_query`='".$agent_id."'
                  AND `query`='1' ";
            break;

      }
      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            while ($data=$DB->fetch_array($result)) {
               $ranges[$data["id"]] = $data;
            }
         }
      }
      return $ranges;
   }


   function checkip($a_input) {
      global $LANG;

      $count = 0;
      foreach ($a_input as $num=>$value) {
         if (strstr($num, "ip_")) {
            if (($value>255) OR (!is_numeric($value)) OR strstr($value, ".")) {
               $count++;
               $a_input[$num] = "<font color='#ff0000'>".$a_input[$num]."</font>";
            }
         }
      }

      if ($count == '0') {
         return true;
      } else {
         addMessageAfterRedirect("<font color='#ff0000'>".$LANG['plugin_fusinvsnmp']["iprange"][7].
            "</font><br/>".
            $LANG['plugin_fusinvsnmp']["iprange"][0]." : ".
            $a_input['ip_start0'].".".$a_input['ip_start1'].".".
            $a_input['ip_start2'].".".$a_input['ip_start3']."<br/>".
            $LANG['plugin_fusinvsnmp']["iprange"][1]." : ".
            $a_input['ip_end0'].".".$a_input['ip_end1'].".".
            $a_input['ip_end2'].".".$a_input['ip_end3']);
         return false;
      }
   }


   function permanentTask($items_id, $module_name) {
      global $LANG;

      $PluginFusioninventoryAgentmodule = new PluginFusioninventoryAgentmodule;
      $PluginFusioninventoryAgent = new PluginFusioninventoryAgent;
      $PluginFusioninventoryTask = new PluginFusioninventoryTask;

      $permanent = exportArrayToDB(array($this->type=>$items_id, 'module'=>$module_name));
      // Get on task & taskjob (and create this task if not exist)
      if ($a_task = $PluginFusioninventoryTask->find("`permanent` LIKE '".$permanent."'", "`id` DESC", "1")) {
         foreach($a_task as $task_id=>$datas) {
            
         }
      } else {
         // Create task
         $input = array();
         $input['name'] = $module_name." of IP Range (permanent)";
         $input['date_creation'] = date("Y-m-d H:i:s");
         $input['is_active'] = 0;
         $input['permanent'] = $permanent;

         $task_id = $PluginFusioninventoryTask->add($input);
      }
      // Get task job or create if not exist

      

      $this->fields['is_active'] = 0;
      $this->fields['id'] = 0;


      $options = array();
      $this->showFormHeader($options);
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][60]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo("is_active",$this->fields["is_active"]);
      echo "</td>";
      echo "</td>";

      echo "<td>".$LANG['plugin_fusioninventory']["task"][17]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("periodicity", "", 0, 300);
      echo "&nbsp;";
      $a_time = array();
      $a_time[] = "------";
      $a_time[] = "minutes";
      $a_time[] = "heures";
      $a_time[] = "jours";
      $a_time[] = "mois";
      Dropdown::showFromArray("tt", $a_time, array('value'=>0));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['plugin_fusinvsnmp']["agents"][25];
      echo "</td>";
      echo "<td>";
      $a_agents = $PluginFusioninventoryAgentmodule->getAgentsCanDo($module_name);
      $a_list = array();
      $a_list[0] = "[ ".$LANG['plugin_fusinvsnmp']['agents'][28]." ]";
      foreach($a_agents as $agent_id=>$data) {
         // TODO : display only agent associated with computer and have ip in this range
         $a_list[$agent_id] = $data['name']." / ".$data['version'];
      }
      Dropdown::showFromArray('selection', $a_list);
      echo "</td>";
      echo "<td>";
      echo $LANG['plugin_fusinvsnmp']['task'][17]."&nbsp:";
      echo "</td>";
      echo "<td>";
      $com = array();
      $com['push'] = "push";
      $com['pull'] = "pull";
      Dropdown::showFromArray("communication", $com, array('value'=>$data["communication"]));
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);

      echo $LANG['title'][38]."<br/>";
   }

}

?>