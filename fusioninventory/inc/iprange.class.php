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

class PluginFusioninventoryIPRange extends CommonDBTM {


   static function getTypeName() {
      global $LANG;

      if (isset($_SERVER['HTTP_REFERER']) AND strstr($_SERVER['HTTP_REFERER'], 'iprange')) {

         if ((isset($_POST['glpi_tab'])) AND ($_POST['glpi_tab'] == 1)) {
            // Permanent task discovery
            return $LANG['plugin_fusioninventory']['task'][43];
         } else if ((isset($_POST['glpi_tab'])) AND ($_POST['glpi_tab'] == 2)) {
            // Permanent task inventory
            return $LANG['plugin_fusioninventory']['task'][44];
         } else {
            return $LANG['plugin_fusioninventory']['iprange'][2];
         }
      } else {
         return $LANG['plugin_fusioninventory']['iprange'][2];
      }
   }

   
   
   function canCreate() {
      return PluginFusioninventoryProfile::haveRight("fusioninventory", "iprange", "w");
   }


   function canView() {
      return PluginFusioninventoryProfile::haveRight("fusioninventory", "iprange", "r");
   }


   
   function getSearchOptions() {
      global $LANG;

      $tab = array();

      $tab['common'] = $LANG['plugin_fusioninventory']['menu'][2];

      $tab[1]['table'] = $this->getTable();
      $tab[1]['field'] = 'name';
      $tab[1]['linkfield'] = 'name';
      $tab[1]['name'] = $LANG['common'][16];
      $tab[1]['datatype'] = 'itemlink';

      $tab[2]['table'] = 'glpi_entities';
      $tab[2]['field'] = 'completename';
      $tab[2]['linkfield'] = 'entities_id';
      $tab[2]['name'] = $LANG['entity'][0];

      $tab[3]['table'] = $this->getTable();
      $tab[3]['field'] = 'ip_start';
      $tab[3]['linkfield'] = 'ip_start';
      $tab[3]['name'] = $LANG['plugin_fusioninventory']['iprange'][0];

       $tab[4]['table'] = $this->getTable();
      $tab[4]['field'] = 'ip_end';
      $tab[4]['linkfield'] = 'ip_end';
      $tab[4]['name'] = $LANG['plugin_fusioninventory']['iprange'][1];

      return $tab;
   }



   function defineTabs($options=array()){
      global $LANG;

      $ong = array();
      if ((isset($this->fields['id'])) AND ($this->fields['id'] > 0)){
         $ong[1] = $LANG['plugin_fusioninventory']['task'][18];
      }
      return $ong;
   }



   function showForm($id, $options=array()) {
      global $LANG;

      if ($id!='') {
         $this->getFromDB($id);
      } else {
         $this->getEmpty();
      }

      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' colspan='2'>" . $LANG["common"][16] . "</td>";
      echo "<td align='center' colspan='2'>";
      echo "<input type='text' name='name' value='".$this->fields["name"]."'/>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' colspan='2'>" . $LANG['plugin_fusioninventory']['iprange'][0] . "</td>";
      echo "<td align='center' colspan='2'>";
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
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' colspan='2'>" . $LANG['plugin_fusioninventory']['iprange'][1] . "</td>";
      echo "<td align='center' colspan='2'>";
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
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      if (isMultiEntitiesMode()) {
         echo "<td align='center' colspan='2'>".$LANG['entity'][0]."</td>";
         echo "<td align='center' colspan='2'>";
         Dropdown::show('Entity',
                        array('name'=>'entities_id',
                              'value'=>$this->fields["entities_id"]));
         echo "</td>";
      } else {
         echo "<td colspan='2'></td>";
      }
      echo "</tr>";

      $this->showFormButtons($options);
      $this->addDivForTabs();
   }



   /**
    * Check if IP is valid
    *
    * @param $a_input array of IPs 
    * 
    * @return true or false 
    */
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
         addMessageAfterRedirect("<font color='#ff0000'>".$LANG['plugin_fusioninventory']['iprange'][7].
            "</font><br/>".
            $LANG['plugin_fusioninventory']['iprange'][0]." : ".
            $a_input['ip_start0'].".".$a_input['ip_start1'].".".
            $a_input['ip_start2'].".".$a_input['ip_start3']."<br/>".
            $LANG['plugin_fusioninventory']['iprange'][1]." : ".
            $a_input['ip_end0'].".".$a_input['ip_end1'].".".
            $a_input['ip_end2'].".".$a_input['ip_end3']);
         return false;
      }
   }



   /**
    * Manage permanent task
    * 
    * @param type $items_id
    * @param type $module_name value name of module
    * @param type $allowcreate integer allow or not to create the task
    * 
    * @return nothing
    */
   function permanentTask($items_id, $module_name, $allowcreate=0) {
      global $LANG,$CFG_GLPI;

      $method = '';
      if ($module_name == "NETDISCOVERY") {
         $method = "netdiscovery";
      } else if ($module_name == "SNMPQUERY") {
         $method = "snmpinventory";
      }

      $PluginFusioninventoryTask = new PluginFusioninventoryTask();
      $PluginFusioninventoryTaskjob = new PluginFusioninventoryTaskjob();
      $PluginFusioninventoryTaskjoblog = new PluginFusioninventoryTaskjoblog();

      $permanent = exportArrayToDB(array($this->getType()=>$items_id, 'module'=>$module_name));

      $task_id = 0;
      $taskjob_id= 0;
      // Get on task & taskjob (and create this task if not exist)
      if ($a_task = $PluginFusioninventoryTask->find("`permanent` LIKE '".$permanent."'", "`id` DESC", "1")) {
         $data = current($a_task);
         $task_id = $data['id'];

         $a_taskjob = $PluginFusioninventoryTaskjob->find("`plugin_fusioninventory_tasks_id`='".$task_id."'", "id DESC");
         $data = current($a_taskjob);
         $taskjob_id = $data['id'];
      } else {
         if ($allowcreate != "1") {
            echo "<table class='tab_cadre_fixe'>";
            echo "<tr class='tab_bg_1'>";
            echo "<th>".$LANG['plugin_fusioninventory']['task'][0]."</th>";
            echo "</tr>";
            echo "<tr class='tab_bg_1'>";
            echo "<td align='center'>";
            echo "<a href='".getItemTypeFormURL('PluginFusioninventoryIPRange');
            echo "?id=".$_POST['id']."&allowcreate=1'>";
            echo $LANG['plugin_fusinvsnmp']['task'][18]."</a>";
            echo "</td>";
            echo "</tr>";
            echo "</table>";
            return;
         }
         // Create task
         $input = array();
         $this->getFromDB($items_id);
         $input['name'] = $module_name." of IP Range (permanent) : ".$this->getName();
         $input['date_creation'] = date("Y-m-d H:i:s");
         $input['is_active'] = 0;
         $input['permanent'] = $permanent;
         $input["entities_id"] = $this->getEntityID();
         $input['date_scheduled'] = date("Y-m-d H:i:s");
         $input['periodicity_count'] = "1";
         $input['periodicity_type'] = "hours";

         $task_id = $PluginFusioninventoryTask->add($input);

         $input = array();
         $input['plugin_fusioninventory_tasks_id'] = $task_id;
         $input['plugins_id'] = PluginFusioninventoryModule::getModuleId('fusinvsnmp');
         $input['method'] = $method;
         $input['action'] = '[{"PluginFusioninventoryAgent":".2"}]';
         $input['definition'] = '[{"PluginFusioninventoryIPRange":"'.$_POST['id'].'"}]';
         $input["entities_id"] = $this->getEntityID();

         $taskjob_id = $PluginFusioninventoryTaskjob->add($input);
         glpi_header(str_replace("&allowcreate=1", "",$_SERVER['HTTP_REFERER']));
      }
      // Get task job or create if not exist
      $PluginFusioninventoryTask->getFromDB($task_id);
      $PluginFusioninventoryTaskjob->getFromDB($taskjob_id);

      $options = array();
      $options['target'] = getItemTypeFormURL('PluginFusioninventoryIPRange');
      $PluginFusioninventoryTaskjob->showFormHeader($options);
      
      echo "</td>";
      echo "<td colspan='4' align='center'>";
      echo "<a href='".$CFG_GLPI['root_doc']."/plugins/fusioninventory/front/taskjob.form.php?id=".$taskjob_id."'>".
         $LANG['plugin_fusioninventory']['task'][44]."</a>";
      echo "</td>";
      echo "</tr>";
      
      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][60]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo("is_active",$PluginFusioninventoryTask->fields["is_active"]);
      echo "</td>";
      echo "</td>";

      echo "<td>".$LANG['plugin_fusioninventory']['task'][17]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showInteger("periodicity_count", $PluginFusioninventoryTask->fields['periodicity_count'], 0, 300);
      $a_time = array();
      $a_time[] = "------";
      $a_time['minutes'] = $LANG['job'][22];
      $a_time['hours'] = $LANG['job'][21];
      $a_time['days'] = ucfirst($LANG['calendar'][12]);
      $a_time['months'] = ucfirst($LANG['calendar'][14]);
      Dropdown::showFromArray("periodicity_type", $a_time, array('value'=>$PluginFusioninventoryTask->fields['periodicity_type']));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['plugin_fusioninventory']['agents'][25];
      echo "</td>";
      echo "<td>";

      $defaultValue = '';

      if (!empty($PluginFusioninventoryTaskjob->fields['action'])) {
         $array = importArrayFromDB($PluginFusioninventoryTaskjob->fields['action']);
         $defaultValue = current(current($array));
      }
      $a_data = $PluginFusioninventoryTaskjob->get_agents($module_name);
      Dropdown::showFromArray('action', $a_data, array('value' => $defaultValue));

      echo "</td>";
      echo "<td>";
      echo $LANG['plugin_fusioninventory']['task'][43]."&nbsp:";
      echo "</td>";
      echo "<td>";
      $com = array();
      $com['push'] = $LANG['plugin_fusioninventory']['task'][41];
      $com['pull'] = $LANG['plugin_fusioninventory']['task'][42];
      Dropdown::showFromArray("communication", $com, array('value'=>$PluginFusioninventoryTask->fields["communication"]));
      echo "</td>";
      echo "</tr>";

      echo "<input name='task_id' type='hidden' value='".$task_id."' />";
      echo "<input name='taskjob_id' type='hidden' value='".$taskjob_id."' />";
      echo "<input name='iprange' type='hidden' value='".$_POST['id']."' />";

      $PluginFusioninventoryTaskjob->showFormButtons($options);

      $PluginFusioninventoryTaskjoblog->showHistory($PluginFusioninventoryTaskjob->fields['id']);
   }


   
   /**
    * Get ip in long format
    * 
    * @param $ip ip in format ipv4
    * 
    * @return $int integer 
    */
   function getIp2long($ip) {
      $int = ip2long($ip);
      if ($int < 0) {
         $int = sprintf("%u\n", ip2long($ip));
      }
      return $int;
   }
}

?>