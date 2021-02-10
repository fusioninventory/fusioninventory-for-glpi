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
 * This file is used to manage the IP ranges for network discovery and
 * network inventory.
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
 * Manage the IP ranges for network discovery and network inventory.
 */
class PluginFusioninventoryIPRange extends CommonDBTM {

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
   static $rightname = 'plugin_fusioninventory_iprange';


   /**
    * Check if can create an IP range
    *
    * @return true
    */
   static function canCreate() {
      return true;
   }


   /**
    * Get name of this type by language of the user connected
    *
    * @param integer $nb number of elements
    * @return string name of this type
    */
   static function getTypeName($nb = 0) {

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
      }
      return __('IP Ranges', 'fusioninventory');
   }


   /**
    * Get comments of the object
    *
    * @return string comments in HTML format
    */
   function getComments() {
      $comment = $this->fields['ip_start']." -> ".$this->fields['ip_end'];
      return Html::showToolTip($comment, ['display' => false]);
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
         'name' => __('IP range configuration', 'fusioninventory')
      ];

      $tab[] = [
         'id'           => '1',
         'table'        => $this->getTable(),
         'field'        => 'name',
         'name'         => __('Name'),
         'datatype'     => 'itemlink',
         'autocomplete' => true,
      ];

      $tab[] = [
         'id'        => '2',
         'table'     => 'glpi_entities',
         'field'     => 'completename',
         'linkfield' => 'entities_id',
         'name'      => Entity::getTypeName(1),
         'datatype'  => 'dropdown',
      ];

      $tab[] = [
         'id'        => '3',
         'table'     => $this->getTable(),
         'field'     => 'ip_start',
         'name'      => __('Start of IP range', 'fusioninventory'),
      ];

      $tab[] = [
         'id'        => '4',
         'table'     => $this->getTable(),
         'field'     => 'ip_end',
         'name'      => __('End of IP range', 'fusioninventory'),
      ];

      $tab[] = [
         'id'            => '5',
         'table'         => 'glpi_plugin_fusioninventory_configsecurities',
         'field'         => 'name',
         'datatype'      => 'dropdown',
         'right'         => 'all',
         'name'          => __('SNMP credentials', 'fusioninventory'),
         'forcegroupby'  => true,
         'massiveaction' => false,
         'joinparams'    => [
            'beforejoin' => [
               'table'      => "glpi_plugin_fusioninventory_ipranges_configsecurities",
               'joinparams' => [
                  'jointype' => 'child',
               ],
            ],
         ],
      ];

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
      $ong[$this->getType().'$task'] = _n('Task', 'Tasks', 2);
      $this->addStandardTab('Log', $ong, $options);
      return $ong;
   }


   /**
    * Display the content of the tab
    *
    * @param object $item
    * @param integer $tabnum number of the tab to display
    * @param integer $withtemplate 1 if is a template form
    * @return boolean
    */
   static function displayTabContentForItem(CommonGLPI $item, $tabnum = 1, $withtemplate = 0) {
      if ($tabnum == 'task') {
         $pfTask = new PluginFusioninventoryTask();
         $pfTask->showJobLogs();
         return true;
      }
      return false;
   }


   /**
    * Display form
    *
    * @param integer $id
    * @param array $options
    * @return true
    */
   function showForm($id, $options = []) {

      $this->initForm($id, $options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'>";
      echo "<td align='center' colspan='2'>" . __('Name') . "</td>";
      echo "<td align='center' colspan='2'>";
      Html::autocompletionTextField($this, 'name');
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
      $j = 0;
      foreach ($ipexploded as $ipnum) {
         if ($ipnum > 255) {
            $ipexploded[$j] = '';
         }
         $j++;
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
         echo "<td align='center' colspan='2'>".Entity::getTypeName(1)."</td>";
         echo "<td align='center' colspan='2'>";
         Dropdown::show('Entity',
                        ['name'=>'entities_id',
                              'value'=>$this->fields["entities_id"]]);
         echo "</td>";
      } else {
         echo "<td colspan='2'></td>";
      }
      echo "</tr>";

      $this->showFormButtons($options);

      return true;
   }


   /**
    * Check if IP is valid
    *
    * @param array $a_input array of IPs
    * @return boolean
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
         return true;
      } else {
          Session::addMessageAfterRedirect("<font color='#ff0000'>".__('Bad IP', 'fusioninventory').
            "</font><br/>".
            __('Start of IP range', 'fusioninventory')." : ".
            $a_input['ip_start0'].".".$a_input['ip_start1'].".".
            $a_input['ip_start2'].".".$a_input['ip_start3']."<br/>".
            __('End of IP range', 'fusioninventory')." : ".
            $a_input['ip_end0'].".".$a_input['ip_end1'].".".
            $a_input['ip_end2'].".".$a_input['ip_end3']);
         return false;
      }
   }


   /**
    * Get ip in long format
    *
    * @param string $ip IP in format IPv4
    * @return integer $int
    */
   function getIp2long($ip) {
      $int = ip2long($ip);
      if ($int < 0) {
         $int = sprintf("%u\n", ip2long($ip));
      }
      return $int;
   }


   /**
    * After purge item, delete SNMP credentials linked to this ip range
    */
   function post_purgeItem() {
      $pfIPRange_ConfigSecurity = new PluginFusioninventoryIPRange_ConfigSecurity();
      $a_data = getAllDataFromTable('glpi_plugin_fusioninventory_ipranges_configsecurities',
         ['plugin_fusioninventory_ipranges_id' => $this->fields['id']]);
      foreach ($a_data as $data) {
         $pfIPRange_ConfigSecurity->delete($data);
      }
      parent::post_deleteItem();
   }


   /**
    * Get the massive actions for this object
    *
    * @param object|null $checkitem
    * @return array list of actions
    */
   function getSpecificMassiveActions($checkitem = null) {

      $actions = [];
      if (Session::haveRight("plugin_fusioninventory_task", UPDATE)) {
         $actions['PluginFusioninventoryTask'.MassiveAction::CLASS_ACTION_SEPARATOR.'addtojob_target'] = __('Target a task', 'fusioninventory');
      }
      return $actions;
   }
}
