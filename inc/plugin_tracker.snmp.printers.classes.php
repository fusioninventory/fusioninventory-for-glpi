<?php

/*
 * @version $Id$
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copynetwork (C) 2003-2006 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org/
 ----------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI; if not, write to the Free Software
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

class PluginTrackerPrintersCartridges extends CommonDBTM {
   
	function __construct() {
		$this->table = "glpi_plugin_tracker_printers_cartridges";
		//$this->type = PLUGIN_TRACKER_PRINTERS_CARTRIDGES;
	}
}

class PluginTrackerPrinters extends CommonDBTM {

	function __construct() {
		$this->table = "glpi_plugin_tracker_printers";
		//$this->type = PLUGIN_TRACKER_PRINTERS_CARTRIDGES;
	}



	function showFormPrinter($target,$ID) {
		global $DB,$CFG_GLPI,$LANG,$TRACKER_MAPPING;	
	
		plugin_tracker_checkRight("snmp_printers","r");
	
		include (GLPI_ROOT . "/plugins/tracker/inc_constants/plugin_tracker.snmp.mapping.constant.php");
	
		$this->ID = $ID;
		
		$plugin_tracker_printers = new PluginTrackerPrinters;
//		$config_snmp_printer = new PluginTrackerConfigSNMPPrinter;
		$plugin_tracker_snmp = new PluginTrackerSNMP;

		$query = "SELECT * 
                FROM `glpi_plugin_tracker_printers`
                WHERE `FK_printers`=".$ID." ";

		$result = $DB->query($query);		
		$data = $DB->fetch_assoc($result);
		
		// Add in database if not exist
		if ($DB->numrows($result) == "0") {
			$query_add = "INSERT INTO `glpi_plugin_tracker_printers` (`FK_printers`)
                              VALUES('".$ID."') ";
			
			$DB->query($query_add);
		}
		
		// Form printer informations
//		echo "<br>";
		echo "<div align='center'><form method='post' name='snmp_form' id='snmp_form'
                 action=\"".$target."\">";

		echo "<table class='tab_cadre' cellpadding='5' width='950'>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<th colspan='3'>";
		echo $LANG['plugin_tracker']["snmp"][11];
		echo "</th>";
		echo "</tr>";
		
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_tracker']["model_info"][4]."</td>";
		echo "<td align='center'>";
		$query_models = "SELECT * 
                       FROM `glpi_plugin_tracker_model_infos`
                       WHERE `device_type`!=3
                             AND `device_type`!=0";
		$result_models=$DB->query($query_models);
		$exclude_models = array();
		while ($data_models=$DB->fetch_array($result_models)) {
			$exclude_models[] = $data_models['ID'];		
		}
		dropdownValue("glpi_plugin_tracker_model_infos","FK_model_infos",$data["FK_model_infos"],
                     0,-1,'',$exclude_models);
      echo "</td>";
      echo "<td align='center'>";
      echo " <input type='submit' name='GetRightModel' value='".
             $LANG['plugin_tracker']["model_info"][13]."' class='submit'/></td>";
      echo "</td>";
		echo "</tr>";
	
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_tracker']["functionalities"][43]."</td>";
		echo "<td align='center'>";
		plugin_tracker_snmp_auth_dropdown($data["FK_snmp_connection"]);
		echo "</td>";
      echo "<td>";
      echo "</td>";
      echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td align='center'>".$LANG['plugin_tracker']["functionalities"][36]."</td>";
		echo "<td align='center'>";
		$dropdown[1] = $LANG["planning"][5];
		$dropdown[7] = $LANG["planning"][6];
		$dropdown[30] = $LANG["planning"][14];
		$dropdown[365] = $LANG["financial"][9];
		dropdownArrayValues("frequence_days",$dropdown, $data["frequence_days"]);
		echo "</td>";
      echo "<td>";
      echo "</td>";
		echo "</tr>";		
		
		echo "<tr class='tab_bg_1'>";
		echo "<td align='center' colspan='2' height='30'>";
		echo $LANG['plugin_tracker']["snmp"][52].": ".convDateTime($data["last_tracker_update"]);
		echo "</td>";
      echo "<td>";
      echo "</td>";
		echo "</tr>";

		echo "<tr class='tab_bg_1'>";
		echo "<td colspan='3'>";
		echo "<div align='center'>";
		echo "<input type='hidden' name='ID' value='".$ID."'>";
		echo "<input type='submit' name='update' value=\"".$LANG["buttons"][7]."\" class='submit' >";
		echo "</td>";
		echo "</tr>";

		echo "</table></form>";
		echo "</div>";

		// ** FORM FOR CARTRIDGES

		// get infos to get visible or not the counters
			$snmp_model_ID = $plugin_tracker_snmp->GetSNMPModel($ID,PRINTER_TYPE);
			// ** Get link OID fields
			$Array_Object_TypeNameConstant= $plugin_tracker_snmp->GetLinkOidToFields($ID,PRINTER_TYPE); 
			$mapping_name=array();
			foreach ($Array_Object_TypeNameConstant as $object=>$mapping_type_name) {
				if (strstr($mapping_type_name, "cartridges")) {
					$explode[1] = str_replace ("MAX", "", $mapping_type_name);
					$explode[1] = str_replace ("REMAIN", "", $explode[1]);
               switch($explode[1]) {
                     CASE "cartridgesblack":
                        $mapping_name[$explode[1]] = "1";
                        break;

                     CASE "cartridgescyan":
                        $mapping_name[$explode[1]] = "2";
                        break;

                     CASE "cartridgesmagenta":
                        $mapping_name[$explode[1]] = "3";
                        break;

                     CASE "cartridgesyellow":
                        $mapping_name[$explode[1]] = "4";
                        break;

                     default:
                        $mapping_name[$explode[1]] = "5";
               }
				}
			}

		echo "<div align='center'><form method='post' name='snmp_form' id='snmp_form'
                 action=\"".$target."\">";
		echo "<table class='tab_cadre' cellpadding='5' width='950'>";
		echo "<tr class='tab_bg_1'>";
		echo "<th align='center' colspan='3'>";
		echo $LANG["cartridges"][16];
		echo "</th>";
		echo "</tr>";

		asort($mapping_name);
		foreach ($mapping_name as $cartridge_name=>$val) {
			$state = $plugin_tracker_printers->cartridges_state($ID, $cartridge_name);
			echo "<tr class='tab_bg_1'>";
			echo "<td align='center'>";
			echo $TRACKER_MAPPING[PRINTER_TYPE][$cartridge_name]['shortname'];
			echo " : ";
			echo "</td>";
			echo "<td align='center'>";
//			if ($config_snmp_printer->getValue('manage_cartridges') == "1") {
//				echo "<form method='post' name='snmp_form' id='snmp_form'  action=\"".$target."\">";
//				dropdownValue("glpi_cartridges_type","FK_cartridges",$state['FK_cartridges'],0);
//				echo "<input type='hidden' name='ID' value='".$ID."' />";
//				echo "<input type='hidden' name='object_name' value='".$cartridge_name."' />";
//				echo "<input name='update_cartridges' value='update_cartridges' src='".GLPI_ROOT.
//                 "/pics/actualiser.png' class='calendrier' type='image'>";
//				echo "</form>";
//			}
			echo "</td>";
			echo "<td align='center'>";
			plugin_tracker_Bar($state['state']);
			echo "</td>";
			echo "</tr>";
		}

		echo "</table></form>";
  		echo "</div>";
	}



	function showFormPrinter_pagescounter($target,$ID) {
		global $DB,$CFG_GLPI,$LANG,$TRACKER_MAPPING;	
		
		$plugin_tracker_printers = new PluginTrackerPrinters;
		$plugin_tracker_snmp = new PluginTrackerSNMP;
	
		$this->ID = $ID;
		
		$query = "SELECT * 
                FROM `glpi_plugin_tracker_printers`
                WHERE `FK_printers`=".$ID." ";

		$result = $DB->query($query);		
		$data = $DB->fetch_assoc($result);
		
		switch ($data['frequence_days']) {
			case 1:
				$frequence = "day";
				break;

			case 7:
				$frequence = "week";
				break;

			case 30:
				$frequence = "month";
				break;

			case 365:
				$frequence = "year";
				break;
		} 
		// get infos to get visible or not the counters
			$snmp_model_ID = $plugin_tracker_snmp->GetSNMPModel($ID,PRINTER_TYPE);
			// ** Get link OID fields
			$Array_Object_TypeNameConstant= $plugin_tracker_snmp->GetLinkOidToFields($ID,PRINTER_TYPE); 
			$mapping_name=array();
			foreach ($Array_Object_TypeNameConstant as $object=>$mapping_type_name) {
				//$explode = explode("||", $mapping_type_name);
				$mapping_name[$mapping_type_name] = "1";
			}	
		
		// Form pages counter
//		echo "<br>";
		echo "<div align='center'><form method='post' name='snmp_form' id='snmp_form'
                 action=\"".$target."\">";
		echo "<table class='tab_cadre' cellpadding='5' width='950'>";
		echo "<tr class='tab_bg_1'>";
		echo "<th colspan='3'>";
		echo $LANG["cartridges"][39];
		echo "</th>";
		echo "</tr>";

		if ( (isset($mapping_name['pagecountertotalpages']))
           AND ($mapping_name['pagecountertotalpages'] == "1") ) {
			// Total page counter
			echo "<tr class='tab_bg_1'>";
			echo "<th colspan='3'>";
			echo $LANG['plugin_tracker']["mapping"][128];
			echo "</th>";
			echo "</tr>";
	
			echo "<tr class='tab_bg_1'>";
			echo "<td colspan='3'>";
         // calendrier
         if (!isset($_SESSION["datetotalpages"])) {
            $_SESSION["datetotalpages"]="";
         }
         plugin_tracker_printer_calendar($_SESSION["datetotalpages"],"datetotalpages",$target);
         // fin calendrier
			echo "</td>";
			echo "</tr>";
	
			echo "<tr class='tab_bg_1'>";
			echo "<td colspan='3'>";
			$Array = $plugin_tracker_printers->
                     getPagesCount($ID,$frequence,$_SESSION["datetotalpages"],'pages_total');

			echo "<table class='tab_cadre' cellpadding='5' width='900'>";
			$plugin_tracker_printers->
            counter_page_arrayLine_display($LANG["common"][27],$Array['dates'],1);
			$plugin_tracker_printers->
            counter_page_arrayLine_display($LANG["printers"][31],$Array['count']);
			$ecart= $plugin_tracker_printers->
                 counter_page_arrayLine_display_difference("ecart",$Array['count'],$Array['dates']);
			echo "</table>";
			$plugin_tracker_printers->
            graphBy($ecart,$LANG['plugin_tracker']["mapping"][128],
                    $LANG['plugin_tracker']["printer"][0],1,$frequence);
			echo "</td>";
			echo "</tr>";
		}
		
		if ( (isset($mapping_name['pagecounterblackpages']))
           AND ($mapping_name['pagecounterblackpages'] == "1") ) {
			// ** Black & white page counter
			echo "<tr class='tab_bg_1'>";
			echo "<th colspan='3'>";
			echo $LANG['plugin_tracker']["mapping"][129];
			echo "</th>";
			echo "</tr>";
	
			echo "<tr class='tab_bg_1'>";
			echo "<td colspan='3'>";
         if (!isset($_SESSION["dateblackpages"])) {
            $_SESSION["dateblackpages"]="";
         }
         plugin_tracker_printer_calendar($_SESSION["dateblackpages"],"dateblackpages",$target);
         // fin calendrier
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td colspan='3'>";
         $Array = $plugin_tracker_printers->
                  getPagesCount($ID,$frequence,$_SESSION["dateblackpages"],'pages_n_b');

         echo "<table class='tab_cadre' cellpadding='5' width='900'>";
         $plugin_tracker_printers->
            counter_page_arrayLine_display($LANG["common"][27],$Array['dates'],1);
         $plugin_tracker_printers->
            counter_page_arrayLine_display($LANG["printers"][31],$Array['count']);
         $ecart= $plugin_tracker_printers->
                 counter_page_arrayLine_display_difference("ecart",$Array['count'],$Array['dates']);
         echo "</table>";
			$plugin_tracker_printers->
            graphBy($ecart,$LANG['plugin_tracker']["mapping"][129],
                    $LANG['plugin_tracker']["printer"][0],1,$frequence);
			echo "</td>";
			echo "</tr>";
		}

		if ( (isset($mapping_name['pagecountercolorpages']))
           AND ($mapping_name['pagecountercolorpages'] == "1") ) {
			// ** Color page counter
			echo "<tr class='tab_bg_1'>";
			echo "<th colspan='3'>";
			echo $LANG['plugin_tracker']["mapping"][130];
			echo "</th>";
			echo "</tr>";
	
			echo "<tr class='tab_bg_1'>";
			echo "<td colspan='3'>";

         if (!isset($_SESSION["datecolorpages"])) {
            $_SESSION["datecolorpages"]="";
         }
         plugin_tracker_printer_calendar($_SESSION["datecolorpages"],"datecolorpages",$target);
         // fin calendrier
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td colspan='3'>";
         $Array = $plugin_tracker_printers->
                  getPagesCount($ID,$frequence,$_SESSION["datecolorpages"],'pages_color');

         echo "<table class='tab_cadre' cellpadding='5' width='900'>";
         $plugin_tracker_printers->
            counter_page_arrayLine_display($LANG["common"][27],$Array['dates'],1);
         $plugin_tracker_printers->
            counter_page_arrayLine_display($LANG["printers"][31],$Array['count']);
         $ecart= $plugin_tracker_printers->
                 counter_page_arrayLine_display_difference("ecart",$Array['count'],$Array['dates']);
         echo "</table>";
			$plugin_tracker_printers->
            graphBy($ecart,$LANG['plugin_tracker']["mapping"][130],
                    $LANG['plugin_tracker']["printer"][0],1,$frequence);
			echo "</td>";
			echo "</tr>";
		}

		if ( (isset($mapping_name['pagecounterrectoversopages']))
           AND ($mapping_name['pagecounterrectoversopages'] == "1") ) {
			// ** Recto/Verso page counter
			echo "<tr class='tab_bg_1'>";
			echo "<th colspan='3'>";
			echo $LANG['plugin_tracker']["mapping"][154];
			echo "</th>";
			echo "</tr>";
	
			echo "<tr class='tab_bg_1'>";
			echo "<td colspan='3'>";

         if (!isset($_SESSION["daterectoversopages"])) {
            $_SESSION["daterectoversopages"]="";
         }
         plugin_tracker_printer_calendar($_SESSION["daterectoversopages"],"daterectoversopages",
                                         $target);
         // fin calendrier
         echo "</td>";
         echo "</tr>";

         echo "<tr class='tab_bg_1'>";
         echo "<td colspan='3'>";
         $Array= $plugin_tracker_printers->
                 getPagesCount($ID,$frequence,$_SESSION["daterectoversopages"],'pages_recto_verso');

         echo "<table class='tab_cadre' cellpadding='5' width='900'>";
         $plugin_tracker_printers->
            counter_page_arrayLine_display($LANG["common"][27],$Array['dates'],1);
         $plugin_tracker_printers->
            counter_page_arrayLine_display($LANG["printers"][31],$Array['count']);
         $ecart= $plugin_tracker_printers->
                 counter_page_arrayLine_display_difference("ecart",$Array['count'],$Array['dates']);
         echo "</table>";
			$plugin_tracker_printers->
            graphBy($ecart,$LANG['plugin_tracker']["mapping"][154],
                    $LANG['plugin_tracker']["printer"][0],1,$frequence);
			echo "</td>";
			echo "</tr>";
		}

		if ( (isset($mapping_name['pagecounterscannedpages']))
           AND ($mapping_name['pagecounterscannedpages'] == "1") ) {
			// ** Scanned page counter
			echo "<tr class='tab_bg_1'>";
			echo "<th colspan='3'>";
			echo $LANG['plugin_tracker']["mapping"][155];
			echo "</th>";
			echo "</tr>";

			echo "<tr class='tab_bg_1'>";
			echo "<td colspan='3'>";

			if (!isset($_SESSION["datescannedpages"])) {
				$_SESSION["datescannedpages"]="";
         }
			plugin_tracker_printer_calendar($_SESSION["datescannedpages"],"datescannedpages",$target);
			// fin calendrier
			echo "</td>";
			echo "</tr>";
	
			echo "<tr class='tab_bg_1'>";
			echo "<td colspan='3'>";
			$Array = $plugin_tracker_printers->
                  getPagesCount($ID,$frequence,$_SESSION["datescannedpages"],'scanned');
		
			echo "<table class='tab_cadre' cellpadding='5' width='900'>";
			$plugin_tracker_printers->
            counter_page_arrayLine_display($LANG["common"][27],$Array['dates'],1);
			$plugin_tracker_printers->
            counter_page_arrayLine_display($LANG["printers"][31],$Array['count']);
			$ecart= $plugin_tracker_printers->
                 counter_page_arrayLine_display_difference("ecart",$Array['count'],$Array['dates']);
			echo "</table>";
			$plugin_tracker_printers->
            graphBy($ecart,$LANG['plugin_tracker']["mapping"][155],
                    $LANG['plugin_tracker']["printer"][0],1,$frequence);
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}
			
		
		
	function update_printers_infos($ID, $FK_model_infos, $FK_snmp_connection) {
		global $DB;
		
		$query = "UPDATE `glpi_plugin_tracker_printers`
                SET `FK_model_infos`='".$FK_model_infos."',
                    `FK_snmp_connection`='".$FK_snmp_connection."'
                WHERE `FK_printers`='".$ID."' ";
	
		$DB->query($query);
	}	
	
	
	
	function counter_page_arrayLine_display($title,$array,$th=0) {
		$td = "td";
		if ($th == "1") {
			$td = "th";
      }
		echo "<tr class='tab_bg_1'>";
		echo "<th>".$title."</th>";
//		foreach ($array AS $value)
		for ($i = 0 ; $i < count($array) ; $i++) {
			$explode = explode(" ", $array[$i]);
			if ($th == "1") {
				$explode[0] = convdate($explode[0]);
         }
			echo "<".$td." align='center'>".$explode[0]."</".$td.">";
		}
		echo "</tr>";
	}
	
	
	
	function counter_page_arrayLine_display_difference($title,$array,$arraydates) {
		echo "<tr class='tab_bg_1'>";
		echo "<th>".$title."</th>";
		$i = 1;
		$j = 0;
		$ecart = array();
		for ($i = 0 ; $i < count($array) ; $i++) {
			if ($i == (count($array) - 1)) {
				echo "<td align='center'></td>";
         } else {
				if (($array[$i+1] - $array[($i)]) == "0") {
					echo "<td align='center'>".($array[$i+1] - $array[($i)])."</td>";
					$ecart[$arraydates[$i]] = ($array[$i+1] - $array[($i)]);
				} else {
					echo "<td align='center'>".($array[$i+1] - $array[($i)])."</td>";
					$ecart[$arraydates[$i]] = ($array[$i+1] - $array[($i)]);
				}
			}
		}
		echo "</tr>";
		return $ecart;	
	}
	
	
	
	/** Get groups assigned to tickets between 2 dates
	* BASED ON SPIP DISPLAY GRAPH : www.spip.net
	* @param $type string : "month" or "year" or "day" or "week"
	* @param $entrees array : array containing data to displayed
	* @param $titre string : title 
	* @param $unit string : unit 
	* @param $showtotal boolean : also show total values ?
	* @return array contains the distinct groups assigned to a tickets
	*/
	function graphBy($entrees,$titre="",$unit="",$showtotal=1,$type="month") {
		global $DB,$CFG_GLPI,$LANG;
		
		ksort($entrees);
		$total="";
		if ($showtotal==1) {
			$total=array_sum($entrees);
      }
	
		echo "<p align='center'>";
		echo "<font face='verdana,arial,helvetica,sans-serif' size='2'>
            <strong>$titre - $total $unit</strong></font>";
		echo "<div class='center'><center>";
	
		if (count($entrees)>0) {
	
			$max = max($entrees);
			$maxgraph = substr(ceil(substr($max,0,2) / 10)."000000000000", 0, strlen($max));
	
			if ($maxgraph < 10) {
				$maxgraph = 10;
         }
			if (1.1 * $maxgraph < $max) {
				$maxgraph.="0";
         }
			if (0.8*$maxgraph > $max) {
				$maxgraph = 0.8 * $maxgraph;
         }
			$rapport = 200 / $maxgraph;
	
			$largeur = floor(420 / (count($entrees)));
			if ($largeur < 1) {
				$largeur = 1;
         }
			if ($largeur > 50) {
				$largeur = 50;
         }
		}
	
		echo "<table cellpadding='0' cellspacing='0' border='0' >
		<tr><td style='background-image:url(".$CFG_GLPI["root_doc"]."/pics/fond-stats.gif)' >";
		echo "<table cellpadding='0' cellspacing='0' border='0'><tr>";
		echo "<td bgcolor='black'><img src='".$CFG_GLPI["root_doc"].
           "/pics/noir.png' width='1' height='200' alt=''></td>";
	
		// Presentation graphique
		$n = 0;
		$decal = 0;
		$tab_moyenne = "";
		$total_loc=0;
		while (list($key, $value) = each($entrees)) {
			$n++;
	
			if ($decal == 30) $decal = 0;
			$decal ++;
			$tab_moyenne[$decal] = $value;
	
			$total_loc = $total_loc + $value;
			reset($tab_moyenne);
	
			$moyenne = 0;
			while (list(,$val_tab) = each($tab_moyenne)) {
				$moyenne += $val_tab;
         }
			$moyenne = $moyenne / count($tab_moyenne);
	
			$hauteur_moyenne = round($moyenne * $rapport) ;
			$hauteur = round($value * $rapport)	;
			echo "<td valign='bottom' width=".$largeur.">";
	
			if ($hauteur >= 0) {
				if ($hauteur_moyenne > $hauteur) {
					$difference = ($hauteur_moyenne - $hauteur) -1;
					echo "<img alt=\"$key: $value\" title=\"$key: $value\"  src='".$CFG_GLPI["root_doc"].
                    "/pics/moyenne.png' width=".$largeur." height='1' >";
					echo "<img alt=\"$key: $value\" title=\"$key: $value\"  src='".$CFG_GLPI["root_doc"].
                    "/pics/rien.gif' width=".$largeur." height=".$difference." >";
					echo "<img alt=\"$key: $value\" title=\"$key: $value\"  src='".$CFG_GLPI["root_doc"].
                    "/pics/noir.png' width=".$largeur." height='1' >";
					if (strstr($key, "-01")) { // janvier en couleur foncee
						echo "<img alt=\"$key: $value\" title=\"$key: $value\" 
                             src='".$CFG_GLPI["root_doc"]."/pics/fondgraph1.png'
                             width=".$largeur." height=".$hauteur." >";
               } else {
						echo "<img alt=\"$key: $value\" title=\"$key: $value\" 
                             src='".$CFG_GLPI["root_doc"]."/pics/fondgraph2.png'
                             width=".$largeur." height=".$hauteur." >";
               }
				} else if ($hauteur_moyenne < $hauteur) {
					$difference = ($hauteur - $hauteur_moyenne) -1;
					echo "<img alt=\"$key: $value\" title=\"$key: $value\" 
                          src='".$CFG_GLPI["root_doc"]."/pics/noir.png' width=".$largeur."
                          height='1'>";
					if (strstr($key, "-01")) { // janvier en couleur foncee
						$couleur =  "1";
						$couleur2 =  "2";
					} else {
						$couleur = "2";
						$couleur2 = "1";
					}
					echo "<img alt=\"$key: $value\" title=\"$key: $value\" 
                          src='".$CFG_GLPI["root_doc"]."/pics/fondgraph$couleur.png'
                          width=".$largeur." height=".$difference.">";
					echo "<img alt=\"$key: $value\" title=\"$key: $value\" 
                          src='".$CFG_GLPI["root_doc"]."/pics/moyenne.png'
                          width=".$largeur." height='1'>";
					echo "<img alt=\"$key: $value\" title=\"$key: $value\" 
                          src='".$CFG_GLPI["root_doc"]."/pics/fondgraph$couleur.png'
                          width=".$largeur." height=".$hauteur_moyenne.">";
				} else {
					echo "<img alt=\"$key: $value\" title=\"$key: $value\" 
                          src='".$CFG_GLPI["root_doc"]."/pics/noir.png'
                          width=".$largeur." height='1'>";
					if (strstr($key, "-01")) { // janvier en couleur foncee
						echo "<img alt=\"$key: $val_tab\" title=\"$key: $value\" 
                             src='".$CFG_GLPI["root_doc"]."/pics/fondgraph1.png'
                             width=".$largeur." height=".$hauteur.">";
               } else {
						echo "<img alt=\"$key: $value\" title=\"$key: $value\" 
                             src='".$CFG_GLPI["root_doc"]."/pics/fondgraph2.png'
                             width=".$largeur." height=".$hauteur.">";
               }
				}
			}
			echo "<img alt=\"$value\" title=\"$value\"  src='".$CFG_GLPI["root_doc"]."/pics/rien.gif'
                    width=".$largeur." height='1'>";
			echo "</td>\n";
		}
		echo "<td bgcolor='black'><img src='".$CFG_GLPI["root_doc"]."/pics/noir.png' width='1'
                                     height='1' alt=''></td>";
		echo "</tr>";
		if ($largeur>10) {
			echo "<tr><td></td>";
			foreach ($entrees as $key => $val) {
				if ($type=="month") {
					$splitter=explode("-",$key);
					echo "<td class='center'>".utf8_substr($LANG["calendarM"][$splitter[1]-1],0,3).
                    "</td>";
				} else if ($type=="year") {
					echo "<td class='center'>".substr($key,2,2)."</td>";
            } else if ($type=="day") {
					echo "<td class='center'>".substr($key,8,2)."</td>";
            } else if ($type=="week") {
					$val = explode(" ",$key);
	       		$date = explode("-",$val[0]);
	       		$time = explode(":",$val[1]);
					echo "<td class='center'>".
                    date('W',mktime($time[0],$time[1],$time[2],$date[1],$date[2],$date[0]))."</td>";
				}
			}
			echo "</tr>";
		}
	
		if ($maxgraph<=10) {
			$r=2;
      } else if ($maxgraph<=100) {
			$r=1;
      } else {
			$r=0;
      }
		echo "</table>";
		echo "</td>";
		echo "<td style='background-image:url(".$CFG_GLPI["root_doc"]."/pics/fond-stats.gif)' 
                valign='bottom'><img src='".$CFG_GLPI["root_doc"]."/pics/rien.gif'
                style='background-color:black;' width='3' height='1' alt=''></td>";
		echo "<td><img src='".$CFG_GLPI["root_doc"]."/pics/rien.gif' width='5' height='1' alt=''>
            </td>";
		echo "<td valign='top'>";
		echo "<table cellpadding='0' cellspacing='0' border='0'>";
		echo "<tr><td height='15' valign='top'>";		
		echo "<font face='arial,helvetica,sans-serif' size='1'><strong>".
            formatNumber($maxgraph,false,$r)."</strong></font>";
		echo "</td></tr>";
		echo "<tr><td height='25' valign='middle'>";		
		echo "<font face='arial,helvetica,sans-serif' size='1' color='#999999'>".
            formatNumber(7*($maxgraph/8),false,$r)."</font>";
		echo "</td></tr>";
		echo "<tr><td height='25' valign='middle'>";		
		echo "<font face='arial,helvetica,sans-serif' size='1'>".
            formatNumber(3*($maxgraph/4),false,$r)."</font>";
		echo "</td></tr>";
		echo "<tr><td height='25' valign='middle'>";		
		echo "<font face='arial,helvetica,sans-serif' size='1' color='#999999'>".
            formatNumber(5*($maxgraph/8),false,$r)."</font>";
		echo "</td></tr>";
		echo "<tr><td height='25' valign='middle'>";		
		echo "<font face='arial,helvetica,sans-serif' size='1'><strong>".
            formatNumber($maxgraph/2,false,$r)."</strong></font>";
		echo "</td></tr>";
		echo "<tr><td height='25' valign='middle'>";		
		echo "<font face='arial,helvetica,sans-serif' size='1' color='#999999'>".
            formatNumber(3*($maxgraph/8),false,$r)."</font>";
		echo "</td></tr>";
		echo "<tr><td height='25' valign='middle'>";		
		echo "<font face='arial,helvetica,sans-serif' size='1'>".
            formatNumber($maxgraph/4,false,$r)."</font>";
		echo "</td></tr>";
		echo "<tr><td height='25' valign='middle'>";		
		echo "<font face='arial,helvetica,sans-serif' size='1' color='#999999'>".
            formatNumber(1*($maxgraph/8),false,$r)."</font>";
		echo "</td></tr>";
		echo "<tr><td height='10' valign='bottom'>";		
		echo "<font face='arial,helvetica,sans-serif' size='1'><strong>0</strong></font>";
		echo "</td>";
	
		echo "</tr></table>";
		echo "</td></tr></table>";
		echo "</center></div>";
	}


	
	function cartridges_state($FK_printers, $object_name) {
		global $DB;
		
		$datas = array();
		$query = "SELECT * 
                FROM `glpi_plugin_tracker_printers_cartridges`
                WHERE `FK_printers`='".$FK_printers."'
                      AND `object_name`='".$object_name."' ";
		if ($result=$DB->query($query)) {
			if ($DB->numrows($result) == "0") {
				$datas['FK_cartridges'] = "";
				$datas['state'] = "";
			} else {
				$data = $DB->fetch_assoc($result);
				$datas['FK_cartridges'] = $data['FK_cartridges'];
				$datas['state'] = $data['state'];
				if (($datas['state']) < 0) {
					$datas['state'] = "0";
            }
			}
		}
		return $datas;
	}
	
	

	function getPagesCount($id,$frequence,$date_end,$field) {
		global $DB;	
		
		$dates = plugin_tracker_date(9,$frequence,$date_end);
		$query = "SELECT * 
                FROM `glpi_plugin_tracker_printers_history`
                WHERE `FK_printers`=".$id."
                      AND `date` IN ('".$dates[0]." 00:00:00'";
		for ($i = 1 ; $i < count($dates) ; $i++) {
			$query .= ",'".$dates[$i]." 00:00:00'";
		}
		$query .= ") 
                ORDER BY `date` DESC
                LIMIT 0,9";

		$dates_ex = $dates;

		for ($i = 0 ; $i < count($dates) ; $i++) {
			$dates[$i] = $dates[$i]." 00:00:00";
			$page_scanned_counter[$i] = 0;
		}
		$dates_flip = array_flip($dates);
		$count = "";
		if ($result=$DB->query($query)) {
			while ($data=$DB->fetch_array($result)) {
				$dates[$dates_flip[$data['date']]] = $data['date'];
				$page_scanned_counter[$dates_flip[$data['date']]] = $data[$field];
				if ((!empty($data[$field])) AND ($count == "")) {
					$count = $data[$field];
            }
			}
		}

		for ($i = (count($dates) -1);$i >= 0;$i--) {
			if (($page_scanned_counter[$i] == "0") OR (empty($page_scanned_counter[$i]))) {
				$page_scanned_counter[$i] = $count;
         }
			$count = $page_scanned_counter[$i];
		}
		$Array['dates'] = $dates;
		$Array['count'] = $page_scanned_counter;
		return($Array);
	}

   /**
    * Show printer graph form
    *
    *@param $p_target
    *@param $p_ID
    *@return nothing (print the form)
    **/
   function showFormPrinter_graph($p_target, $p_ID) {
      global $TRACKER_MAPPING, $LANG;

      include_once(GLPI_ROOT."/plugins/tracker/inc_constants/plugin_tracker.snmp.mapping.constant.php");

      $target = $p_target;
      $where=''; $begin=''; $end=''; $timeUnit='date'; $graphField='pages_total'; $printersComp = array();
      if (isset($_SESSION['glpi_plugin_tracker_graph_begin'])) $begin=$_SESSION['glpi_plugin_tracker_graph_begin'];
      if (isset($_SESSION['glpi_plugin_tracker_graph_end'])) $end=$_SESSION['glpi_plugin_tracker_graph_end'];
      if (isset($_SESSION['glpi_plugin_tracker_graph_timeUnit'])) $timeUnit=$_SESSION['glpi_plugin_tracker_graph_timeUnit'];
      if (isset($_SESSION['glpi_plugin_tracker_graph_graphField'])) $graphField=$_SESSION['glpi_plugin_tracker_graph_graphField'];
      if (!isset($_SESSION['glpi_plugin_tracker_graph_printersComp'])) $_SESSION['glpi_plugin_tracker_graph_printersComp']=array();
      if (isset($_SESSION['glpi_plugin_tracker_graph_printerCompAdd'])) {
         $printerCompAdd=$_SESSION['glpi_plugin_tracker_graph_printerCompAdd'];
         if (!key_exists($printerCompAdd, $_SESSION['glpi_plugin_tracker_graph_printersComp'])) {
            $ci=new CommonItem();
            if ($ci->getFromDB(PRINTER_TYPE, $printerCompAdd)){
               $_SESSION['glpi_plugin_tracker_graph_printersComp'][$printerCompAdd] = $ci->getField('name');
            }
         }
      } elseif (isset($_SESSION['glpi_plugin_tracker_graph_printerCompRemove'])) {
         unset($_SESSION['glpi_plugin_tracker_graph_printersComp'][$_SESSION['glpi_plugin_tracker_graph_printerCompRemove']]);
      }

      $printers = $_SESSION['glpi_plugin_tracker_graph_printersComp'];
      $printersView = $printers; // printers without the current printer
      if (isset($printersView[$p_ID])) {
         unset($printersView[$p_ID]);
      } else {
         $ci=new CommonItem();
         if ($ci->getFromDB(PRINTER_TYPE, $p_ID)){
            $printers[$p_ID] = $ci->getField('name');
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

      $where = " WHERE `FK_printers` IN(".$printersIds.")";
      if ($begin!='' || $end!='') {
            $where .= " AND " .$this->getDateRequest("`date`",$begin,$end);
         }
      switch ($timeUnit) {
         case 'date':
            $group = "GROUP BY `FK_printers`, `year`, `month`, `date`";
            break;
         case 'week':
            $group = "GROUP BY `FK_printers`, `year`, `month`, `week`";
            break;
         case 'month':
            $group = "GROUP BY `FK_printers`, `year`, `month`";
            break;
         case 'year':
            $group = "GROUP BY `FK_printers`, `year`";
            break;
      }

      $query = "SELECT `FK_printers`, `date`, WEEK(`date`) AS `week`,
                       MONTH(`date`) AS `month`, YEAR(`date`) AS `year`,
                       SUM(`$graphField`) AS `$graphField`
                FROM `glpi_plugin_tracker_printers_history`"
                .$where
                .$group."
                ORDER BY `date`, `FK_printers`";

      echo "<form method='post' name='printerGraph_form' id='printerGraph_form'
                  action='".$p_target."'>";
      echo "<table class='tab_cadre_fixe' cellpadding='2'>";

      echo "<tr>";
      echo "<th colspan='4'>"."Tracker"."</th>";
      echo "</tr>";
      echo "<tr class='tab_bg_1'><td class='left'>".$LANG['plugin_tracker']["prt_history"][30]."&nbsp;:</td><td class='left' colspan='2'>";
      $elementsField=array('pages_total'=>$TRACKER_MAPPING[PRINTER_TYPE]['pagecountertotalpages']['shortname'],
                      'pages_n_b'=>$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterblackpages']['shortname'],
                      'pages_color'=>$TRACKER_MAPPING[PRINTER_TYPE]['pagecountercolorpages']['shortname'],
                      'pages_recto_verso'=>$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterrectoversopages']['shortname'],
                      'scanned'=>$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterscannedpages']['shortname'],
                      'pages_total_print'=>$TRACKER_MAPPING[PRINTER_TYPE]['pagecountertotalpages_print']['shortname'],
                      'pages_n_b_print'=>$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterblackpages_print']['shortname'],
                      'pages_color_print'=>$TRACKER_MAPPING[PRINTER_TYPE]['pagecountercolorpages_print']['shortname'],
                      'pages_total_copy'=>$TRACKER_MAPPING[PRINTER_TYPE]['pagecountertotalpages_copy']['shortname'],
                      'pages_n_b_copy'=>$TRACKER_MAPPING[PRINTER_TYPE]['pagecounterblackpages_copy']['shortname'],
                      'pages_color_copy'=>$TRACKER_MAPPING[PRINTER_TYPE]['pagecountercolorpages_copy']['shortname'],
                      'pages_total_fax'=>$TRACKER_MAPPING[PRINTER_TYPE]['pagecountertotalpages_fax']['shortname']);
      dropdownArrayValues('graph_graphField', $elementsField, $graphField);
      echo "</td></tr>\n";
      echo "<tr class='tab_bg_1'><td class='left'>".$LANG['search'][8]."&nbsp;:</td>
                                 <td class='left' colspan='2'>";
      showDateFormItem("graph_begin", $begin);
      echo "</td></tr>\n";
      echo "<tr class='tab_bg_1'><td class='left'>".$LANG['search'][9]."&nbsp;:</td>
                                 <td class='left' colspan='2'>";
      showDateFormItem("graph_end", $end);
      echo "<tr class='tab_bg_1'><td class='left'>".$LANG['plugin_tracker']["prt_history"][31]."&nbsp;:</td>
                                 <td class='left' colspan='2'>";
      $elementsTime=array('date'=>$LANG['plugin_tracker']["prt_history"][34],
                          'week'=>$LANG['plugin_tracker']["prt_history"][35],
                          'month'=>$LANG['plugin_tracker']["prt_history"][36],
                          'year'=>$LANG['plugin_tracker']["prt_history"][37]);
      dropdownArrayValues('graph_timeUnit', $elementsTime, $timeUnit);
      echo "</td></tr>\n";
      echo "<tr class='tab_bg_1'><td class='left'>".$LANG['Menu'][2]."&nbsp;:</td>
                                 <td class='left' colspan='2'>";
      echo $printersList;
      echo "</td></tr>\n";
      echo "<tr class='tab_bg_2'><td class='center' colspan='3'>
               <input type='submit' class=\"submit\" name='graph_plugin_tracker_printer_period'
                      value='" . $LANG["buttons"][7] . "'>";
      echo "</td></tr>\n";

      echo "<tr class='tab_bg_1'><td class='left'>".$LANG['plugin_tracker']["prt_history"][32]."&nbsp;:</td><td class='left'>";
      dropdownConnect(PRINTER_TYPE,PRINTER_TYPE,"graph_printerCompAdd", -1, 0, array_keys($printers));
      echo "</td><td class='left'>\n";
      echo "<input type='submit' value=\"".$LANG['buttons'][8]."\" class='submit' name='graph_plugin_tracker_printer_add'>";
      echo "</td></tr>\n";

      echo "<tr class='tab_bg_1'><td class='left'>".$LANG['plugin_tracker']["prt_history"][33]."&nbsp;:</td>
                                 <td class='left'>";
      $printersTmp = $printersView;
      $printersTmp[0] = "-----";
      asort($printersTmp);
      dropdownArrayValues('graph_printerCompRemove', $printersTmp);
      echo "</td><td class='left'>\n";
      echo "<input type='submit' value=\"".$LANG['buttons'][6]."\" class='submit' name='graph_plugin_tracker_printer_remove'>";
      echo "</td></tr>\n";
      echo "</table>";
      echo "</form>";

      echo "<div class=center>";
      $title = $elementsField[$graphField];
      if (count($printers)) {
         $ptg = new PluginTrackerGraph($query, $graphField, $timeUnit, $printers, $title);
      }
      echo '</div>';
   }

   /**
    * Add dates for request
    *
    * @param $field : table.field to request
    * @param $begin date : begin date
    * @param $end date : end date
    *
    * @return sql
    */
   function getDateRequest($field,$begin, $end) {
   // TODO : replace in 0.80 by same function in core/inc/db.function.php
      $sql = '';
      if (!empty($begin)) {
            $sql .= " $field >= '$begin' ";
      }
      if (!empty($end)) {
         if (!empty($sql)){
            $sql.= " AND ";
         }
         $sql .= " $field <= ADDDATE('$end' , INTERVAL 1 DAY) ";
      }
      return " (".$sql.") ";
   }
}

?>