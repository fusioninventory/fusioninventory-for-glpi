<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2013 by the FusionInventory Development Team.

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
   @copyright Copyright (c) 2010-2013 FusionInventory team
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

   public $dohistory = TRUE;

   static function getTypeName($nb=0) {

      if (isset($_SERVER['HTTP_REFERER']) AND strstr($_SERVER['HTTP_REFERER'], 'iprange')) {

         if ((isset($_POST['glpi_tab'])) AND ($_POST['glpi_tab'] == 1)) {
            // Permanent task discovery
            return __('Communication mode', 'fusioninventory');

         } else if ((isset($_POST['glpi_tab'])) AND ($_POST['glpi_tab'] == 2)) {
            // Permanent task inventory
            return __('See all informations of task', 'fusioninventory');

         } else {
            return __('IP Ranges', 'fusioninventory');

         }
      } else {
         return __('IP Ranges', 'fusioninventory');

      }
   }


   function getComments() {
      $comment = $this->fields['ip_start']." -> ".$this->fields['ip_end'];
      return Html::showToolTip($comment, array('display' => FALSE));
   }


   static function canCreate() {
      return PluginFusioninventoryProfile::haveRight("iprange", "w");
   }


   static function canView() {
      return PluginFusioninventoryProfile::haveRight("iprange", "r");
   }



   function getSearchOptions() {

      $tab = array();

      $tab['common'] = __('IP range configuration', 'fusioninventory');


      $tab[1]['table'] = $this->getTable();
      $tab[1]['field'] = 'name';
      $tab[1]['linkfield'] = 'name';
      $tab[1]['name'] = __('Name');

      $tab[1]['datatype'] = 'itemlink';

      $tab[2]['table'] = 'glpi_entities';
      $tab[2]['field'] = 'completename';
      $tab[2]['linkfield'] = 'entities_id';
      $tab[2]['name'] = __('Entity');


      $tab[3]['table'] = $this->getTable();
      $tab[3]['field'] = 'ip_start';
      $tab[3]['linkfield'] = 'ip_start';
      $tab[3]['name'] = __('Start of IP range', 'fusioninventory');


       $tab[4]['table'] = $this->getTable();
      $tab[4]['field'] = 'ip_end';
      $tab[4]['linkfield'] = 'ip_end';
      $tab[4]['name'] = __('End of IP range', 'fusioninventory');


      return $tab;
   }



   function defineTabs($options=array()){

      $ong = array();
      if ((isset($this->fields['id'])) AND ($this->fields['id'] > 0)){
         $ong[1] = _n('Task', 'Tasks', 2);
         //$pfTaskjob->manageTasksByObject("PluginFusioninventoryIPRange", $_POST['id']);
      }
      $this->addStandardTab('Log', $ong, $options);
      return $ong;
   }



   function showForm($id, $options=array()) {

      if ($id!='') {
         $this->getFromDB($id);
      } else {
         $this->getEmpty();
      }

      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' colspan='2'>" . __('Name') . "</td>";
      echo "<td align='center' colspan='2'>";
      Html::autocompletionTextField($this,'name');
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' colspan='2'>" . __('Start of IP range', 'fusioninventory') . "</td>";
      echo "<td align='center' colspan='2'>";
      if (empty($this->fields["ip_start"])) {
         $this->fields["ip_start"] = "...";
      }
      $ipexploded = explode(".", $this->fields["ip_start"]);
      $i = 0;
      foreach ($ipexploded as $ipnum) {
         if ($ipnum > 255) {
            $ipexploded[$i] = '';
         }
         $i++;
      }
      echo "<input type='text' value='".$ipexploded[0].
              "' name='ip_start0' id='ip_start0' size='3' maxlength='3' >.";
      echo "<input type='text' value='".$ipexploded[1].
              "' name='ip_start1' id='ip_start1' size='3' maxlength='3' >.";
      echo "<input type='text' value='".$ipexploded[2].
              "' name='ip_start2' id='ip_start2' size='3' maxlength='3' >.";
      echo "<input type='text' value='".$ipexploded[3].
              "' name='ip_start3' id='ip_start3' size='3' maxlength='3' >";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' colspan='2'>" . __('End of IP range', 'fusioninventory') . "</td>";
      echo "<td align='center' colspan='2'>";
      unset($ipexploded);
      if (empty($this->fields["ip_end"])) {
         $this->fields["ip_end"] = "...";
      }
      $ipexploded = explode(".", $this->fields["ip_end"]);
      $i = 0;
      foreach ($ipexploded as $ipnum) {
         if ($ipnum > 255) {
            $ipexploded[$i] = '';
         }
         $i++;
      }

      echo "<script type='text/javascript'>
      function test(id) {
         if (document.getElementById('ip_end' + id).value == '') {
            if (id == 3) {
               document.getElementById('ip_end' + id).value = '254';
            } else {
               document.getElementById('ip_end' + id).value = ".
                  "document.getElementById('ip_start' + id).value;
            }
         }
      }
      </script>";

      echo "<input type='text' value='".$ipexploded[0].
              "' name='ip_end0' id='ip_end0' size='3' maxlength='3' onfocus='test(0)'>.";
      echo "<input type='text' value='".$ipexploded[1].
              "' name='ip_end1' id='ip_end1' size='3' maxlength='3' onfocus='test(1)'>.";
      echo "<input type='text' value='".$ipexploded[2].
              "' name='ip_end2' id='ip_end2' size='3' maxlength='3' onfocus='test(2)'>.";
      echo "<input type='text' value='".$ipexploded[3].
              "' name='ip_end3' id='ip_end3' size='3' maxlength='3' onfocus='test(3)'>";
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      if (Session::isMultiEntitiesMode()) {
         echo "<td align='center' colspan='2'>".__('Entity')."</td>";
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
    * @return TRUE or FALSE
    */
   function checkip($a_input) {

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
         return TRUE;
      } else {
          Session::addMessageAfterRedirect("<font color='#ff0000'>".__('Bad IP', 'fusioninventory').
            "</font><br/>".
            __('Start of IP range', 'fusioninventory')." : ".
            $a_input['ip_start0'].".".$a_input['ip_start1'].".".
            $a_input['ip_start2'].".".$a_input['ip_start3']."<br/>".
            __('End of IP range', 'fusioninventory')." : ".
            $a_input['ip_end0'].".".$a_input['ip_end1'].".".
            $a_input['ip_end2'].".".$a_input['ip_end3']);
         return FALSE;
      }
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
