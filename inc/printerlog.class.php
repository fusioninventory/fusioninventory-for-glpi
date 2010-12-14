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

class PluginFusinvsnmpPrinterLog extends CommonDBTM {

	function __construct() {
		$this->table="glpi_plugin_fusinvsnmp_printerlogs";
	}
	
	function countAllEntries($id) {
		global $DB;
		
		$num = 0;
		$query = "SELECT count(DISTINCT `id`)
                FROM ".$this->table."
                WHERE `printers_id` = '".$id."';";
		if ($result_num=$DB->query($query)) {
			if ($field = $DB->result($result_num,0,0)) {
				$num += $field;
         }
		}
		return $num;
	}

	/* Gets history (and the number of entries) of one printer */
	function getEntries($id, $begin, $limit) {
		global $DB;
		
		$datas=array();
		$query = "SELECT *
                FROM ".$this->table."
				    WHERE `printers_id` = '".$id."'
                LIMIT ".$begin.", ".$limit.";";

		if ($result=$DB->query($query)) {
			$i = 0;
			while ($data=$DB->fetch_assoc($result)) {
				$data['date'] = convDateTime($data['date']);
				$datas["$i"] = $data;
				$i++;
			}
			return $datas;
		}
		return false;
	}
	

	
	function stats($id) {
		global $DB;
		
		$query = "SELECT MIN(`date`) AS `min_date`, MIN(`pages`) AS `min_pages`, ".
				 		"MAX(`date`) AS `max_date`, MAX(`pages`) AS `max_pages`
                FROM ".$this->table."
                WHERE `printers_id` = '".$id."';";

		if ($result = $DB->query($query)) {
			if ($fields = $DB->fetch_assoc($result)) {
				$output['num_days'] =
               ceil((strtotime($fields['max_date']) - strtotime($fields['min_date']))/(60*60*24));
				$output['num_pages'] = $fields['max_pages'] - $fields['min_pages'];
				$output['pages_per_day'] = round($output['num_pages'] / $output['num_days']);
				return $output;
			}
		}
		return false;
	}
	
	function showForm($id, $options=array()) {
		global $LANG;
		
		if (!PluginFusioninventoryProfile::haveRight("fusinvsnmp", "printer","r")) {
			return false;
      }
		
		// display stats
		if ($stats = $this->stats($id)) {
				
			$this->showTabs($options);
         $this->showFormHeader($options);
			
			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANG['plugin_fusioninventory']["prt_history"][12]." : </td>";
			echo "<td>".$stats["num_pages"]."</td></tr>";
			
			echo "<tr class='tab_bg_1'>";
			echo "<td>".$LANG['plugin_fusioninventory']["prt_history"][13]." : </td>";
			echo "<td>".$stats["pages_per_day"]."</td></tr>";
			
			echo "</table></div>";
		
		}
		
		// preparing to display history
		if (!isset($_GET['start'])) {
			$_GET['start'] = 0;
      }
		
		$numrows = $this->countAllEntries($id);
		$parameters = "id=".$_GET["id"]."&onglet=".$_SESSION["glpi_onglet"];	
		
		echo "<br>";
		printPager($_GET['start'], $numrows, $_SERVER['PHP_SELF'], $parameters);

		if ($_SESSION["glpilist_limit"] < $numrows) {
			$limit = $_SESSION["glpilist_limit"];
      } else {
			$limit = $numrows;
      }
		// Get history
		if (!($data = $this->getEntries($id, $_GET['start'], $limit))) {
			return false;
      }

		echo "<div align='center'><form method='post' name='printer_history_form'
                 id='printer_history_form'  action=\"".$target."\">";

		echo "<table class='tab_cadre' cellpadding='5'><tr><th colspan='3'>";
		echo $LANG['plugin_fusioninventory']["prt_history"][20]." :</th></tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<th></th>";
		echo "<th>".$LANG['plugin_fusioninventory']["prt_history"][21]." :</th>";
		echo "<th>".$LANG['plugin_fusioninventory']["prt_history"][22]." :</th></tr>";

		for ($i=0 ; $i<$limit ; $i++) {
			echo "<tr class='tab_bg_1'>";
			echo "<td align='center'>";
			echo "<input type='checkbox' name='checked_$i' value='1'>";
			echo "</td>";
			echo "<td align='center'>".$data["$i"]['date']."</td>";
			echo "<td align='center'>".$data["$i"]['pages']."</td>";
			echo "</td></tr>";
			echo "<input type='hidden' name='ID_$i' value='".$data["$i"]['id']."'>";
		}
		
		if (!PluginFusioninventoryProfile::haveRight("fusinvsnmp", "printer","w")) {
			return false;
      }
			
		echo "<input type='hidden' name='limit' value='".$limit."'>";
		echo "<tr class='tab_bg_1'><td colspan='3'>";
		echo "<div align='center'><a onclick= \"if (markAllRows('printer_history_form')) 
                 return false;\"
                 href='".$_SERVER['PHP_SELF']."?select=all'>".$LANG["buttons"][18]."</a>";
		echo " - <a onclick= \"if ( unMarkAllRows('printer_history_form') ) return false;\"
                  href='".$_SERVER['PHP_SELF']."?select=none'>".$LANG["buttons"][19]."</a> ";
		echo "<input type='submit' name='delete' value=\"".$LANG["buttons"][6]."\" class='submit' >
            </div></td></tr>";
		echo "</table></form></div>";
	}


   /**
    * Show printer graph form
    **/
   function showGraph($id, $options=array()) {
      global $LANG, $DB;

      $where=''; $begin=''; $end=''; $timeUnit='day'; $graphField='pages_total'; $printersComp = array();$graphType='day';
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_begin'])) {
         $begin=$_SESSION['glpi_plugin_fusioninventory_graph_begin'];
      }
      if ( $begin == 'NULL' OR $begin == '' ) $begin=date("Y-m-01"); // first day of current month
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_end'])) {
         $end=$_SESSION['glpi_plugin_fusioninventory_graph_end'];
      }
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_type'])) {
         $graphType = $_SESSION['glpi_plugin_fusioninventory_graph_type'];
      }
      if ( $end == 'NULL' OR $end == '' ) $end=date("Y-m-d");; // today
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_timeUnit'])) $timeUnit=$_SESSION['glpi_plugin_fusioninventory_graph_timeUnit'];
      if (!isset($_SESSION['glpi_plugin_fusioninventory_graph_printersComp'])) $_SESSION['glpi_plugin_fusioninventory_graph_printersComp']=array();
      if (isset($_SESSION['glpi_plugin_fusioninventory_graph_printerCompAdd'])) {
         $printerCompAdd=$_SESSION['glpi_plugin_fusioninventory_graph_printerCompAdd'];
         if (!key_exists($printerCompAdd, $_SESSION['glpi_plugin_fusioninventory_graph_printersComp'])) {
            $oPrinter = new Printer();
            if ($oPrinter->getFromDB($printerCompAdd)){
               $_SESSION['glpi_plugin_fusioninventory_graph_printersComp'][$printerCompAdd] = $oPrinter->getField('name');
            }
         }
      } elseif (isset($_SESSION['glpi_plugin_fusioninventory_graph_printerCompRemove'])) {
         unset($_SESSION['glpi_plugin_fusioninventory_graph_printersComp'][$_SESSION['glpi_plugin_fusioninventory_graph_printerCompRemove']]);
      }

      $printers = $_SESSION['glpi_plugin_fusioninventory_graph_printersComp'];
      $printersView = $printers; // printers without the current printer
      if (isset($printersView[$id])) {
         unset($printersView[$id]);
      } else {
         $oPrinter = new Printer();
         if ($oPrinter->getFromDB($id)){
            $printers[$id] = $oPrinter->getField('name');
         }
      }

      $printersList = '';
      foreach ($printers as $printer) {
         if ($printersList != '') $printersList .= '<BR>';
         $printersList .= $printer;
      }
      $printersIds = "";
      foreach (array_keys($printers) as $printerId) {
         if ($printersIds != '') $printersIds.=', ';
         $printersIds .= $printerId;
      }

      $where = " WHERE `printers_id` IN(".$printersIds.")";
      if ($begin!='' || $end!='') {
            $where .= " AND " .getDateRequest("`date`",$begin,$end);
         }
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

//      $query = "SELECT `printers_id`, DAY(`date`) AS `day`, WEEK(`date`) AS `week`,
//                       MONTH(`date`) AS `month`, YEAR(`date`) AS `year`,
//                       SUM(`$graphField`) AS `$graphField`
//                FROM `glpi_plugin_fusinvsnmp_printerlogs`"
//                .$where
//                .$group."
//                ORDER BY `year`, `month`, `day`, `printers_id`";

      echo "<form method='post' name='snmp_form' id='snmp_form' action='".GLPI_ROOT."/plugins/fusinvsnmp/front/printer_info.form.php'>";
      echo "<table class='tab_cadre' cellpadding='5' width='950'>";
      $mapping = new PluginFusioninventoryMapping;
      $maps = $mapping->find("`itemtype`='Printer'");
      foreach ($maps as $num=>$mapfields) {
         if (!isset($mapfields["shortlocale"])) {
            $mapfields["shortlocale"] = $mapfields["locale"];
         }
         $pagecounters[$mapfields['name']] = $LANG['plugin_fusinvsnmp']["mapping"][$mapfields["shortlocale"]];
      }

      echo "<tr class='tab_bg_1'>";
      echo "<td class='left'>".$LANG['search'][8]."&nbsp;:</td>";
      echo "<td class='left' colspan='2'>";
      showDateFormItem("graph_begin", $begin);
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='left'>".$LANG['search'][9]."&nbsp;:</td>";
      echo "<td class='left' colspan='2'>";
      showDateFormItem("graph_end", $end);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='left'>".$LANG['plugin_fusioninventory']["prt_history"][31]."&nbsp;:</td>";
      echo "<td class='left' colspan='2'>";
      $elementsTime=array('day'=>$LANG['plugin_fusioninventory']["prt_history"][34],
                          'week'=>$LANG['plugin_fusioninventory']["prt_history"][35],
                          'month'=>$LANG['plugin_fusioninventory']["prt_history"][36],
                          'year'=>$LANG['plugin_fusioninventory']["prt_history"][37]);
      Dropdown::showFromArray('graph_timeUnit', $elementsTime,
                              array('value'=>$timeUnit));
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='left'>".$LANG['plugin_fusinvsnmp']["stats"][2]."&nbsp;:</td>";
      echo "<td class='left' colspan='2'>";
      $elements=array('total'=>$LANG['plugin_fusinvsnmp']["stats"][0],
                    'day'=>$LANG['plugin_fusinvsnmp']["stats"][1]);
      Dropdown::showFromArray('graph_type', $elements,
                              array('value'=>$graphType));
      echo "</td>";
      echo "</tr>";


      echo "<tr class='tab_bg_1'>";
      echo "<td class='left'>".$LANG['Menu'][2]."&nbsp;:</td>";
      echo "<td class='left' colspan='2'>";
      echo $printersList;
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_2'>";
      echo "<td class='center' colspan='3'>
               <input type='submit' class='submit' name='graph_plugin_fusioninventory_printer_period'
                      value='" . $LANG["buttons"][7] . "'/>";
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='left'>".$LANG['plugin_fusioninventory']["prt_history"][32]."&nbsp;:</td>";
      echo "<td class='left'>";
      $printersused = array();
      foreach($printersView as $printer_id=>$name) {
         $printersused[] = $printer_id;
      }
      Dropdown::show('Printer', array('name'    =>'graph_printerCompAdd',
                                      'entiry'  => $_SESSION['glpiactive_entity'],
                                      'used'    => $printersused));
      echo "</td>";
      echo "<td class='left'>\n";
      echo "<input type='submit' value=\"".$LANG['buttons'][8]."\" class='submit' name='graph_plugin_fusioninventory_printer_add'>";
      echo "</td>";
      echo "</tr>\n";

      echo "<tr class='tab_bg_1'>";
      echo "<td class='left'>".$LANG['plugin_fusioninventory']["prt_history"][33]."&nbsp;:</td>";
      echo "<td class='left'>";
      $printersTmp = $printersView;
      $printersTmp[0] = "-----";
      asort($printersTmp);
      Dropdown::showFromArray('graph_printerCompRemove', $printersTmp);
      echo "</td>";
      echo "<td class='left'>\n";
      echo "<input type='submit' value=\"".$LANG['buttons'][6]."\" class='submit' name='graph_plugin_fusioninventory_printer_remove'>";
      echo "</td>";
      echo "</tr>\n";
      echo "</table>";
      echo "</form>";

      $elementsField=array('pages_total'=>$pagecounters['pagecountertotalpages'],
                      'pages_n_b'=>$pagecounters['pagecounterblackpages'],
                      'pages_color'=>$pagecounters['pagecountercolorpages'],
                      'pages_recto_verso'=>$pagecounters['pagecounterrectoversopages'],
                      'scanned'=>$pagecounters['pagecounterscannedpages'],
                      'pages_total_print'=>$pagecounters['pagecountertotalpages_print'],
                      'pages_n_b_print'=>$pagecounters['pagecounterblackpages_print'],
                      'pages_color_print'=>$pagecounters['pagecountercolorpages_print'],
                      'pages_total_copy'=>$pagecounters['pagecountertotalpages_copy'],
                      'pages_n_b_copy'=>$pagecounters['pagecounterblackpages_copy'],
                      'pages_color_copy'=>$pagecounters['pagecountercolorpages_copy'],
                      'pages_total_fax'=>$pagecounters['pagecountertotalpages_fax']);

      echo "<br/>";
      foreach($elementsField as $graphField=>$name) {
         $query = "SELECT `printers_id`, DAY(`date`) AS `day`, WEEK(`date`) AS `week`,
                    MONTH(`date`) AS `month`, YEAR(`date`) AS `year`,
                    `$graphField`
             FROM `glpi_plugin_fusinvsnmp_printerlogs`"
             .$where
             .$group."
             ORDER BY `year`, `month`, `day`, `printers_id`";

         $input = array();
         if ($result = $DB->query($query)) {
            if ($DB->numrows($result) != 0) {
               $pages = array();
               while ($data = $DB->fetch_assoc($result)) {
                  switch($timeUnit) {

                     case 'day':
                        $time=mktime(0,0,0,$data['month'],$data['day'],$data['year']);
                        $dayofweek=date("w",$time);
                        if ($dayofweek==0) {
                           $dayofweek=7;
                        }

                        $date= $LANG['calendarDay'][$dayofweek%7]." ".$data['day']." ".$LANG['calendarM'][$data['month']-1];
                        break;

                     case 'week':
                        $date= $data['day']."/".$data['month'];
                        break;

                     case 'month':
                        $date= $data['month']."/".$data['year'];
                        break;

                     case 'year':
                        $date = $data['year'];
                        break;

                  }

                  if ($graphType == 'day') {
                     if (!isset($pages[$data['printers_id']])) {
                        $pages[$data['printers_id']] = $data[$graphField];
                     }
                     $oPrinter->getFromDB($data['printers_id']);

                     $input[$oPrinter->getName()][$date] = $data[$graphField] - $pages[$data['printers_id']];
                     $pages[$data['printers_id']] = $data[$graphField];
                  } else {
                     $oPrinter->getFromDB($data['printers_id']);
                     $input[$oPrinter->getName()][$date] = $data[$graphField];
                  }
               }
            }
         }
// TODO : correct title (not total of printed)
         if ($graphType == 'day') {
            $type = 'bar';
         } else {
            $type = 'line';
         }

         Stat::showGraph($input,
                  array('title'  => $name,
                     'unit'      => '',
                     'type'      => $type,
                     'height'    => 400,
                     'showtotal' => false));
      }
   }

}

?>