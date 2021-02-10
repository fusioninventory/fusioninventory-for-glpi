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
 * This file is used to manage the printer changes (history).
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
   die("Sorry. You can't access this file directly");
}

/**
 * Manage the printer changes (history).
 */
class PluginFusioninventoryPrinterLog extends CommonDBTM {


   /**
    * Get search function for the class
    *
    * @return array
    */
   function rawSearchOptions() {

      $tab = [];

      $tab[] = [
         'id' => 'common',
         'name' => __('History meter printer', 'fusioninventory')
      ];

      $tab[] = [
         'id'            => '1',
         'table'         => "glpi_printers",
         'field'         => 'name',
         'linkfield'     => 'printers_id',
         'name'          => __('Name'),
         'datatype'      => 'itemlink',
         'itemlink_type' => 'Printer',
      ];

      $tab[] = [
         'id'            => '2',
         'table'         => $this->getTable(),
         'field'         => 'id',
         'name'          => 'id',
      ];

      $tab[] = [
         'id'            => '24',
         'table'         => 'glpi_locations',
         'field'         => 'name',
         'linkfield'     => 'locations_id',
         'name'          => __('Location'),
         'datatype'      => 'itemlink',
         'itemlink_type' => 'Location',
      ];

      $tab[] = [
         'id'            => '19',
         'table'         => 'glpi_printertypes',
         'field'         => 'name',
         'linkfield'     => 'printertypes_id',
         'name'          => __('Type'),
         'datatype'      => 'itemlink',
         'itemlink_type' => 'PrinterType',
      ];

      //      $tab[] = [
      //         'id'        => 2,
      //         'table'     => 'glpi_printermodels',
      //         'field'     => 'name',
      //         'linkfield' => 'printermodels_id',
      //         'name'      => __('Model'),
      //         'datatype'  => 'itemptype',
      //      ];

      $tab[] = [
         'id'        => '18',
         'table'     => 'glpi_states',
         'field'     => 'name',
         'linkfield' => 'states_id',
         'name'      => __('Status'),
         'datatype'  => '>dropdown',
      ];

      $tab[] = [
         'id'        => '20',
         'table'     => 'glpi_printers',
         'field'     => 'serial',
         'linkfield' => 'printers_id',
         'name'      => __('Serial Number'),
      ];

      $tab[] = [
         'id'        => '23',
         'table'     => 'glpi_printers',
         'field'     => 'otherserial',
         'linkfield' => 'printers_id',
         'name'      => __('Inventory number'),
      ];

      $tab[] = [
         'id'        => '21',
         'table'     => 'glpi_users',
         'field'     => 'name',
         'linkfield' => 'users_id',
         'name'      => __('User'),
      ];

      $tab[] = [
         'id'        => '3',
         'table'     => 'glpi_manufacturers',
         'field'     => 'name',
         'linkfield' => 'manufacturers_id',
         'name'      => Manufacturer::getTypeName(1),
      ];

      $joinparams = [
         'jointype'          => 'itemtype_item',
         'specific_itemtype' => 'Printer',
      ];
      $networkNameJoin = [
         'jointype'          => 'itemtype_item',
         'specific_itemtype' => 'NetworkPort',
         'beforejoin'        => [
            'table'          => 'glpi_networkports',
            'joinparams'     => $joinparams,
         ],
      ];

      $tab[] = [
         'id'            => '5',
         'table'         => 'glpi_ipaddresses',
         'field'         => 'name',
         'name'          => __('IP'),
         'forcegroupby'  => true,
         'massiveaction' => false,
         'joinparams'    => [
            'jointype'          => 'itemtype_item',
            'specific_itemtype' => 'NetworkName',
            'beforejoin'        => [
               'table' => 'glpi_networknames',
               'joinparams'
                       => $networkNameJoin,
            ],
         ],
      ];

      $tab[] = [
         'id'        => '6',
         'table'     => 'glpi_plugin_fusioninventory_printerlogs',
         'field'     => 'pages_total',
         'linkfield' => 'id',
         'name'      => __('Total number of printed pages', 'fusioninventory'),
      ];

      $tab[] = [
         'id'        => '7',
         'table'     => 'glpi_plugin_fusioninventory_printerlogs',
         'field'     => 'pages_n_b',
         'linkfield' => 'id',
         'name'      => __('Number of printed black and white pages', 'fusioninventory'),
      ];

      $tab[] = [
         'id'        => '8',
         'table'     => 'glpi_plugin_fusioninventory_printerlogs',
         'field'     => 'pages_color',
         'linkfield' => 'id',
         'name'      => __('Number of printed color pages', 'fusioninventory'),
      ];

      $tab[] = [
         'id'        => '9',
         'table'     => $this->getTable(),
         'field'     => 'pages_recto_verso',
         'linkfield' => 'id',
         'name'      => __('Number of pages printed duplex', 'fusioninventory'),
      ];

      $tab[] = [
         'id'        => '10',
         'table'     => $this->getTable(),
         'field'     => 'scanned',
         'linkfield' => 'id',
         'name'      => __('Number of scanned pages', 'fusioninventory'),
      ];

      $tab[] = [
         'id'        => '11',
         'table'     => $this->getTable(),
         'field'     => 'pages_total_print',
         'linkfield' => 'id',
         'name'      => __('Total number of printed pages (print)', 'fusioninventory'),
      ];

      $tab[] = [
         'id'        => '12',
         'table'     => $this->getTable(),
         'field'     => 'pages_n_b_print',
         'linkfield' => 'id',
         'name'      => __('Number of printed black and white pages (print)', 'fusioninventory'),
      ];

      $tab[] = [
         'id'        => '13',
         'table'     => $this->getTable(),
         'field'     => 'pages_color_print',
         'linkfield' => 'id',
         'name'      => __('Number of printed color pages (print)', 'fusioninventory'),
      ];

      $tab[] = [
         'id'        => '14',
         'table'     => $this->getTable(),
         'field'     => 'pages_total_copy',
         'linkfield' => 'id',
         'name'      => __('Total number of printed pages (copy)', 'fusioninventory'),
      ];

      $tab[] = [
         'id'        => '15',
         'table'     => $this->getTable(),
         'field'     => 'pages_n_b_copy',
         'linkfield' => 'id',
         'name'      => __('Number of printed black and white pages (copy)', 'fusioninventory'),
      ];

      $tab[] = [
         'id'        => '16',
         'table'     => $this->getTable(),
         'field'     => 'pages_color_copy',
         'linkfield' => 'id',
         'name'      => __('Number of printed color pages (copy)', 'fusioninventory'),
      ];

      $tab[] = [
         'id'        => '17',
         'table'     => $this->getTable(),
         'field'     => 'pages_total_fax',
         'linkfield' => 'id',
         'name'      => __('Total number of printed pages (fax)', 'fusioninventory'),
      ];

      return $tab;
   }


   /**
    * Count number entries for the printer
    *
    * @global object $DB
    * @param integer $printers_id
    * @return integer
    */
   function countAllEntries($printers_id) {
      global $DB;

      $num = 0;
      $query = "SELECT count(DISTINCT `id`)
                FROM ".$this->getTable()."
                WHERE `printers_id` = '".$printers_id."';";
      $result_num=$DB->query($query);
      if ($result_num) {
         $field = $DB->result($result_num, 0, 0);
         if ($field) {
            $num += $field;
         }
      }
      return $num;
   }


   /**
    * Get logs of printer
    *
    * @global object $DB
    * @param integer $printers_id
    * @param integer $begin
    * @param integer $limit
    * @return array|false
    */
   function getEntries($printers_id, $begin, $limit) {
      global $DB;

      $datas=[];
      $query = "SELECT *
                FROM ".$this->getTable()."
                WHERE `printers_id` = '".$printers_id."'
                LIMIT ".$begin.", ".$limit.";";
      $result=$DB->query($query);
      if ($result) {
         $i = 0;
         while ($data=$DB->fetchAssoc($result)) {
            $data['date'] = Html::convDateTime($data['date']);
            $datas["$i"] = $data;
            $i++;
         }
         return $datas;
      }
      return false;
   }


   /**
    * Get printed pages statistics
    *
    * @global object $DB
    * @param integer $printers_id
    * @return array|false
    */
   function stats($printers_id) {
      global $DB;

      $query = "SELECT MIN(`date`) AS `min_date`, MIN(`pages`) AS `min_pages`, ".
                  "MAX(`date`) AS `max_date`, MAX(`pages`) AS `max_pages`
                FROM ".$this->getTable()."
                WHERE `printers_id` = '".$printers_id."';";
      $result = $DB->query($query);
      if ($result) {
         $fields = $DB->fetchAssoc($result);
         if ($fields) {
            $output = [];
            $output['num_days'] =
               ceil((strtotime($fields['max_date']) - strtotime($fields['min_date']))/(60*60*24));
            $output['num_pages'] = $fields['max_pages'] - $fields['min_pages'];
            $output['pages_per_day'] = round($output['num_pages'] / $output['num_days']);
            return $output;
         }
      }
      return false;
   }


   /**
    * Display form
    *
    * @param integer $printers_id
    * @param array $options
    * @return boolean
    */
   function showForm($printers_id, $options = []) {

      if (!Session::haveRight('plugin_fusioninventory_printer', READ)) {
         return false;
      }

      // display stats
      $stats = $this->stats($printers_id);
      if ($stats) {
         $this->initForm($printers_id, $options);
         $this->showFormHeader($options);

         echo "<tr class='tab_bg_1'>";
         echo "<td>".__('Total printed pages', 'fusioninventory')." : </td>";
         echo "<td>".$stats["num_pages"]."</td></tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td>".__('Pages / day', 'fusioninventory')." : </td>";
         echo "<td>".$stats["pages_per_day"]."</td></tr>";

         echo "</table></div>";

      }

      // preparing to display history
      if (!isset($_GET['start'])) {
         $_GET['start'] = 0;
      }

      $numrows = $this->countAllEntries($printers_id);
      $parameters = "id=".$_GET["id"]."&onglet=".$_SESSION["glpi_onglet"];

      echo "<br>";
      Html::printPager($_GET['start'], $numrows, $_SERVER['PHP_SELF'], $parameters);

      $limit = $numrows;
      if ($_SESSION["glpilist_limit"] < $numrows) {
         $limit = $_SESSION["glpilist_limit"];
      }
      // Get history
      $data = $this->getEntries($printers_id, $_GET['start'], $limit);
      if (!($data)) {
         return false;
      }

      echo "<div align='center'><form method='post' name='printer_history_form'
                 id='printer_history_form'  action=\"".Toolbox::getItemTypeFormURL(__CLASS__)."\">";

      echo "<table class='tab_cadre' cellpadding='5'><tr><th colspan='3'>";
      echo __('History meter printer', 'fusioninventory')." :</th></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<th></th>";
      echo "<th>"._n('Date', 'Dates', 1)." :</th>";
      echo "<th>".__('Meter', 'fusioninventory')." :</th></tr>";

      for ($i=0; $i<$limit; $i++) {
         echo "<tr class='tab_bg_1'>";
         echo "<td align='center'>";
         Html::showCheckbox(['name' => "checked_$i", 'value' => 1]);
         echo "</td>";
         echo "<td align='center'>".$data["$i"]['date']."</td>";
         echo "<td align='center'>".$data["$i"]['pages']."</td>";
         echo "</td></tr>";
         echo "<input type='hidden' name='ID_$i' value='".$data["$i"]['id']."'>";
      }

      if (!Session::haveRight('plugin_fusioninventory_printer', UPDATE)) {
         return false;
      }

      echo "<input type='hidden' name='limit' value='".$limit."'>";
      echo "<tr class='tab_bg_1'><td colspan='3'>";
      echo "<div align='center'><a onclick= \"if (markAllRows('printer_history_form'))
                 return FALSE;\"
                 href='".$_SERVER['PHP_SELF']."?select=all'>".
                 __('Check All', 'fusioninventory')."</a>";
      echo " - <a onclick= \"if (unMarkAllRows('printer_history_form')) return FALSE;\"
                  href='".$_SERVER['PHP_SELF']."?select=none'>".
                  __('Uncheck All', 'fusioninventory')."</a> ";
      echo "<input type='submit' name='delete' value=\"".__('Delete', 'fusioninventory').
            "\" class='submit' ></div></td></tr>";
      echo "</table>";
      Html::closeForm();
      echo "</div>";
      return true;
   }


   /**
    * Display printer graph form
    *
    * @global object $DB
    * @global array $CFG_GLPI
    * @param integer $id the id of printer
    * @param array $options
    */
   function showGraph($id, $options = []) {
      global $DB, $CFG_GLPI;

      $printer = new Printer();

      $where='';
      $begin='';
      $end='';
      $timeUnit='day';
      $graphField='pages_total';
      $pagecounters = [];$graphType='day';
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_begin'])) {
         $begin = $_SESSION['glpi_plugin_fusioninventory_graph_begin'];
      }
      if ($begin == 'NULL' OR $begin == '') {
         $begin = date("Y-m-01"); // first day of current month
      }
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_end'])) {
         $end=$_SESSION['glpi_plugin_fusioninventory_graph_end'];
      }
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_type'])) {
         $graphType = $_SESSION['glpi_plugin_fusioninventory_graph_type'];
      }
      if ($end == 'NULL' OR $end == '') {
         $end = date("Y-m-d"); // today
      }
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_timeUnit'])) {
         $timeUnit = $_SESSION['glpi_plugin_fusioninventory_graph_timeUnit'];
      }
      if (!isset($_SESSION['glpi_plugin_fusioninventory_graph_printersComp'])) {
         $_SESSION['glpi_plugin_fusioninventory_graph_printersComp']=[];
      }
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_printerCompAdd'])) {
         $printerCompAdd = $_SESSION['glpi_plugin_fusioninventory_graph_printerCompAdd'];
         if (!key_exists($printerCompAdd,
                         $_SESSION['glpi_plugin_fusioninventory_graph_printersComp'])) {
            $oPrinter = new Printer();
            if ($oPrinter->getFromDB($printerCompAdd)) {
               $_SESSION['glpi_plugin_fusioninventory_graph_printersComp'][$printerCompAdd] =
                     $oPrinter->getField('name');
            }
         }
      } else if (isset($_SESSION['glpi_plugin_fusioninventory_graph_printerCompRemove'])) {
         unset($_SESSION['glpi_plugin_fusioninventory_graph_printersComp'][$_SESSION['glpi_plugin_fusioninventory_graph_printerCompRemove']]);
      }
      $oPrinter = new Printer();
      $printers = $_SESSION['glpi_plugin_fusioninventory_graph_printersComp'];
      $printersView = $printers; // printers without the current printer
      if (isset($printersView[$id])) {
         unset($printersView[$id]);
      } else {
         if ($oPrinter->getFromDB($id)) {
            $printers[$id] = $oPrinter->getField('name');
         }
      }

      $printersList = '';
      foreach ($printers as $printers_id=>$printername) {
         if ($printersList != '') {
            $printersList .= '<br/>';
         }
         if ($printers_id == $id) {
            $printersList .= $printername;
         } else {
            $oPrinter->getFromDB($printers_id);
            $printersList .= $oPrinter->getLink(1);
         }
      }
      $printersIds = "";
      foreach (array_keys($printers) as $printerId) {
         if ($printersIds != '') {
            $printersIds.=', ';
         }
         $printersIds .= $printerId;
      }

      $where = " WHERE `printers_id` IN(".$printersIds.")";
      if ($begin!='' || $end!='') {
         $where .= " AND " .self::getDateRequest("`date`", $begin, $end);
      }
      $group = '';
      switch ($timeUnit) {
         case 'day':
            $group = "GROUP BY `printers_id`, `year`, `month`, `day`";
            break;
         case 'week':
            $group = "GROUP BY `printers_id`, `year`, `month`, `week`";
            break;
         case 'month':
            $group = "GROUP BY `printers_id`, `year`, `month`";
            break;
         case 'year':
            $group = "GROUP BY `printers_id`, `year`";
            break;
      }

      echo "<form method='post' name='snmp_form' id='snmp_form' action='".
              Plugin::getWebDir('fusioninventory')."/front/printer_info.form.php'>";
      echo "<table class='tab_cadre' cellpadding='5' width='950'>";
      $mapping = new PluginFusioninventoryMapping();
      $maps = $mapping->find(['itemtype' => 'Printer']);
      foreach ($maps as $mapfields) {
         if (!isset($mapfields["shortlocale"])) {
            $mapfields["shortlocale"] = $mapfields["locale"];
         }
         $pagecounters[$mapfields['name']] = $mapping->getTranslation($mapfields);
      }

      echo "<tr class='tab_bg_1'>";
      echo "<th colspan='4'>";
      echo __('Printed page counter', 'fusioninventory');
      echo "</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='left'>".__('Start date')."&nbsp;:</td>";
      echo "<td class='left'>";
      Html::showDateField("graph_begin", ['value' => $begin]);
      echo "</td>";
      echo "<td class='left'>".__('Time unit', 'fusioninventory')."&nbsp;:</td>";
      echo "<td class='left'>";
      $elementsTime=['day'  => _n('Day', 'Days', 1),
                          'week' => __('Week'),
                          'month'=> _n('Month', 'Months', 1),
                          'year' => __('Year', 'fusioninventory')];

      Dropdown::showFromArray('graph_timeUnit', $elementsTime,
                              ['value'=>$timeUnit]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='left'>".__('End date')."&nbsp;:</td>";
      echo "<td class='left'>";
      Html::showDateField("graph_end", ['value' => $end]);
      echo "</td>";
      echo "<td class='left'>".__('Display', 'fusioninventory')."&nbsp;:</td>";
      echo "<td class='left'>";
      $elements=['total'=>__('Total counter', 'fusioninventory'),

                    'day'=>__('pages per day', 'fusioninventory')];

      Dropdown::showFromArray('graph_type', $elements,
                              ['value'=>$graphType]);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_2'>";
      echo "<td class='center' colspan='4'>
              <input type='submit' class='submit' name='graph_plugin_fusioninventory_printer_period'
                      value='" . __('Update') . "'/>";
      echo "</td>";
      echo "</tr>\n";

      echo "<tr>";
      echo "<th colspan='4'>".__('Printers to compare', 'fusioninventory')."</th>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='left' rowspan='3'>".__('Printer')."&nbsp;:</td>";
      echo "<td class='left' rowspan='3'>";
      echo $printersList;
      echo "</td>";
      echo "<td class='left'>".__('Add a printer', 'fusioninventory')."&nbsp;:</td>";
      echo "<td class='left'>";
      $printersused = [];
      foreach ($printersView as $printer_id=>$name) {
         $printersused[] = $printer_id;
      }
      $printer->getFromDB($id);
      Dropdown::show('Printer', ['name'    =>'graph_printerCompAdd',
                                      'entity'  => $printer->fields['entities_id'],
                                      'used'    => $printersused]);
      echo "&nbsp;<input type='submit' value=\"".__('Add')."\" class='submit' ".
              "name='graph_plugin_fusioninventory_printer_add'>";
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='left'>".__('Remove a printer', 'fusioninventory')."&nbsp;:</td>";
      echo "<td class='left'>";
      $printersTmp = $printersView;
      $printersTmp[0] = "-----";
      asort($printersTmp);
      Dropdown::showFromArray('graph_printerCompRemove', $printersTmp);
      echo "&nbsp;<input type='submit' value=\"".__('Delete', 'fusioninventory')."\" ".
              "class='submit' name='graph_plugin_fusioninventory_printer_remove'>";
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='2'></td>";
      echo "</tr>";

      echo "</table>";
      Html::closeForm();

      $elementsField=[
          'pages_total'       => $pagecounters['pagecountertotalpages'],
          'pages_n_b'         => $pagecounters['pagecounterblackpages'],
          'pages_color'       => $pagecounters['pagecountercolorpages'],
          'pages_recto_verso' => $pagecounters['pagecounterrectoversopages'],
          'scanned'           => $pagecounters['pagecounterscannedpages'],
          'pages_total_print' => $pagecounters['pagecountertotalpages_print'],
          'pages_n_b_print'   => $pagecounters['pagecounterblackpages_print'],
          'pages_color_print' => $pagecounters['pagecountercolorpages_print'],
          'pages_total_copy'  => $pagecounters['pagecountertotalpages_copy'],
          'pages_n_b_copy'    => $pagecounters['pagecounterblackpages_copy'],
          'pages_color_copy'  => $pagecounters['pagecountercolorpages_copy'],
          'pages_total_fax'   => $pagecounters['pagecountertotalpages_fax']];

      echo "<br/>";
      $a_graph = [];
      foreach ($elementsField as $graphField=>$name) {
         $query = "SELECT `printers_id`, DAY(`date`)-1 AS `day`, WEEK(`date`) AS `week`,
                    MONTH(`date`) AS `month`, YEAR(`date`) AS `year`, `date`,
                    `$graphField`
             FROM `glpi_plugin_fusioninventory_printerlogs`"
             .$where.
                " AND `".$graphField."` > 0 "
             .$group;
         $result = $DB->query($query);
         if ($DB->numrows($result) == 0 AND $graphField != "pages_total") {
            unset($elementsField[$graphField]);
         }
      }
      foreach ($elementsField as $graphField=>$name) {
         $query = "SELECT `printers_id`, DAY(`date`)-1 AS `day`, WEEK(`date`) AS `week`,
                    MONTH(`date`) AS `month`, YEAR(`date`) AS `year`, `date`,
                    `$graphField`
             FROM `glpi_plugin_fusioninventory_printerlogs`"
             .$where
             .$group."
             ORDER BY `year`, `month`, `day`, `printers_id`";

         $input = [];
         $result = $DB->query($query);

         if ($result) {
            if ($DB->numrows($result) != 0) {
               $pages = [];
               $data = [];
               $date = '';
               while ($data = $DB->fetchAssoc($result)) {
                  switch ($timeUnit) {

                     case 'day':
                        $split = explode(" ", $data['date']);
                        $date = $split[0];
                        break;

                     case 'week':
                        $split = explode(" ", $data['date']);
                        $date = $split[0];
                        break;

                     case 'month':
                        $split = explode(" ", $data['date']);
                        $split2 = explode("-", $split[0]);
                        $date = $split2[0]."-".$split2[1];
                        break;

                     case 'year':
                        $split = explode(" ", $data['date']);
                        $split2 = explode("-", $split[0]);
                        $date = $split2[0];
                        break;

                  }

                  if ($graphType == 'day') {
                     if (!isset($pages[$data['printers_id']])) {
                        $pages[$data['printers_id']] = $data[$graphField];
                     } else {
                        $y = $data[$graphField] - $pages[$data['printers_id']];
                        if ($y < 0) {
                           $y = 0;
                        }
                        $input[] = ['x' => $date,
                                         'y' => $y];
                        if ($data[$graphField] > 0) {
                           $pages[$data['printers_id']] = $data[$graphField];
                        }
                     }
                  } else {
                     $input[] = ['x' => $date,
                                      'y' => $data[$graphField]];
                  }
               }
            } else {
               if ($graphType == 'day') {
                  $input[] = ['x' => date("Y-m-d"),
                                   'y' => 0];
               }
            }
         }

         $continue = 1;

         if (($continue == '0') OR ($continue == '-1')) {
            echo "<table class='tab_cadre' cellpadding='5' width='900'>";
            echo "<tr class='tab_bg_1'>";
            echo "<th>";
            echo $name;
            echo "</th>";
            echo "</tr>";

            echo "<tr class='tab_bg_1'>";
            echo "<td align='center'>";
            if ($continue == '0') {
               echo __('Too datas to display', 'fusioninventory');
            }
            echo "</td>";
            echo "</tr>";

            echo "</table><br/>";
         } else {
            if (count($input) > 0) {
               $split = explode(' > ', $name);
               $a_graph[] = [
                   'key'    => $split[count($split) - 1],
                   'values' => $input
               ];
            }
         }
      }

      // Display graph
      echo '<div id="chartPrinter">'.
             '<svg style="height: 400px; width: 950px;"></svg>'.
           '</div>';

      echo "<script type='text/javascript'>
      function drawGraph() {
         var chart = nv.models.multiBarChart();

         chart.yAxis
             .tickFormat(d3.format(',0f'));

        d3.select('#chartPrinter svg')
           .datum(exampleData())
          .transition().duration(500).call(chart);

        nv.utils.windowResize(chart.update);
    }
    ";

      echo '   function exampleData() {
      return '.json_encode($a_graph).'
   }

   drawGraph();
</script>';

   }

   public static function getDateRequest($field, $begin, $end) {
      $sql = '';
      if (!empty($begin)) {
         $sql .= " $field >= '$begin' ";
      }

      if (!empty($end)) {
         if (!empty($sql)) {
            $sql .= " AND ";
         }
         $sql .= " $field <= ADDDATE('$end' , INTERVAL 1 DAY) ";
      }
      return " (".$sql.") ";
   }
}
