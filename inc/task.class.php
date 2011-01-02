<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

class PluginFusioninventoryTask extends CommonDBTM {

   function getSearchOptions() {
      global $LANG;

      $sopt = array();

      $sopt['common'] = $LANG['plugin_fusioninventory']['task'][0];

      $sopt[1]['table']          = $this->getTable();
      $sopt[1]['field']          = 'name';
      $sopt[1]['linkfield']      = '';
      $sopt[1]['name']           = $LANG['common'][16];
      $sopt[1]['datatype']       = 'itemlink';

      $sopt[2]['table']          = $this->getTable();
      $sopt[2]['field']          = 'date_creation';
      $sopt[2]['linkfield']      = '';
      $sopt[2]['name']           = $LANG['common'][27];
      $sopt[2]['datatype']       = 'datetime';

      $sopt[3]['table']          = $this->getTable();
      $sopt[3]['field']          = 'entities_id';
      $sopt[3]['linkfield']      = '';
      $sopt[3]['name']           = $LANG['entity'][0];

      $sopt[4]['table']          = $this->getTable();
      $sopt[4]['field']          = 'comment';
      $sopt[4]['linkfield']      = '';
      $sopt[4]['name']           = $LANG['common'][25];

      $sopt[5]['table']          = $this->getTable();
      $sopt[5]['field']          = 'is_active';
      $sopt[5]['linkfield']      = '';
      $sopt[5]['name']           = $LANG['common'][60];
      $sopt[5]['datatype']       = 'bool';

      $sopt[6]['table']          = $this->getTable();
      $sopt[6]['field']          = 'communication';
      $sopt[6]['linkfield']      = '';
      $sopt[6]['name']           = 'Communication';

      $sopt[7]['table']          = $this->getTable();
      $sopt[7]['field']          = 'permanent';
      $sopt[7]['linkfield']      = '';
      $sopt[7]['name']           = 'permanent';
      $sopt[7]['datatype']       = 'bool';

      $sopt[30]['table']          = $this->getTable();
      $sopt[30]['field']          = 'id';
      $sopt[30]['linkfield']      = '';
      $sopt[30]['name']           = $LANG['common'][2];

      return $sopt;
   }


   
   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_fusioninventory']['task'][1];
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

   function canUpdate() {
      return true;
   }



   function defineTabs($options=array()){
      global $LANG,$CFG_GLPI,$DB;

      $ong = array();
      $ong[1] = $LANG['title'][26];

      if ($this->fields['id'] > 0) {
         $pft = new PluginFusioninventoryTaskjob;
         $a_taskjob = $pft->find("`plugin_fusioninventory_tasks_id`='".$_GET['id']."'
               AND `rescheduled_taskjob_id`='0' ", "date_scheduled,id");
         $i = 1;
         foreach($a_taskjob as $datas) {
            $i++;
            $ong[$i] = $LANG['plugin_fusioninventory']['task'][2]." ".($i-1);
         }

         $i++;
         $ong[$i] = $LANG['plugin_fusioninventory']['task'][16];
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
      echo "<td>".$LANG['common'][16]."&nbsp;:</td>";
      echo "<td align='center'>";
      echo "<input type='text' name='name' size='40' value='".$this->fields["name"]."'/>";
      echo "</td>";

      echo "<td>".$LANG['plugin_fusioninventory']['task'][17]."&nbsp;:</td>";
      echo "<td align='center'>";
      $a_periodicity = array();
      if (strstr($this->fields['periodicity'], "-")) {
         $a_periodicity = explode("-", $this->fields['periodicity']);
      } else {
         $a_periodicity[] = 0;
         $a_periodicity[] = '';
      }
      Dropdown::showInteger("periodicity-1", $a_periodicity[0], 0, 300);
      $a_time = array();
      $a_time[] = "------";
      $a_time[] = "minutes";
      $a_time[] = "heures";
      $a_time[] = "jours";
      $a_time[] = "mois";
      Dropdown::showFromArray("periodicity-2", $a_time, array('value'=>$a_periodicity[1]));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>".$LANG['common'][60]."&nbsp;:</td>";
      echo "<td align='center'>";
      Dropdown::showYesNo("is_active",$this->fields["is_active"]);
      echo "</td>";

      echo "<td rowspan='3'>".$LANG['common'][25]."&nbsp;:</td>";
      echo "<td align='center' rowspan='3'>";
      echo "<textarea cols='45' rows='3' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>Communication&nbsp;:</td>";
      echo "<td align='center'>";
      $com = array();
      $com['push'] = "push";
      $com['pull'] = "pull";
      Dropdown::showFromArray("communication", $com, array('value'=>$this->fields["communication"]));
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>Permanent&nbsp;:</td>";
      echo "<td align='center'>";
      if ($this->fields['permanent'] != NULL) {
         echo $LANG['choice'][1];
      } else {
         echo $LANG['choice'][0];
      }
      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }

   

   function ListTask($agent_id, $action) {
      global $DB;

      $tasks = array();
      $list = $this->find("`agent_id`='".$agent_id."' AND `action`='".$action."' ");
      foreach ($list as $data) {
         switch ($data['itemtype']) {

            case NETWORKING_TYPE:
               $query = "SELECT glpi_plugin_fusioninventory_tasks.id as id, param, ip, single,
                           glpi_plugin_fusioninventory_tasks.items_id as items_id, glpi_plugin_fusioninventory_tasks.itemtype as itemtype
                        FROM `glpi_plugin_fusioninventory_tasks`
                        INNER JOIN glpi_networkequipments on glpi_plugin_fusioninventory_tasks.items_id=glpi_networkequipments.id
                        WHERE `agent_id`='".$agent_id."'
                           AND `action`='".$action."'";
               break;

            case COMPUTER_TYPE:
            case PRINTER_TYPE:
               $query = "SELECT glpi_plugin_fusioninventory_tasks.id as id, param, ip, single,
                           glpi_plugin_fusioninventory_tasks.items_id as items_id, glpi_plugin_fusioninventory_tasks.itemtype as itemtype
                        FROM `glpi_plugin_fusioninventory_tasks`
                        INNER JOIN glpi_networkports on (glpi_plugin_fusioninventory_tasks.items_id=glpi_networkports.items_id
                                                      AND glpi_plugin_fusioninventory_tasks.itemtype=glpi_networkports.itemtype)
                        WHERE `agent_id`='".$agent_id."'
                           AND `action`='".$action."'
                           AND `ip`!='127.0.0.1'";

               break;
         }
      }

      if ($result = $DB->query($query)) {
         if ($DB->numrows($result) != 0) {
            while ($data=$DB->fetch_array($result)) {
               $tasks[$data["id"]] = $data;
               switch ($tasks[$data["id"]]["itemtype"]) {
                  case "networking":
                     $tasks[$data["id"]]["itemtype"]='NETWORKING';
                     break;
                  case "printer":
                     $tasks[$data["id"]]["itemtype"]='PRINTER';
                     break;
               }
            }
         }
      }
      return $tasks;
   }

}

?>