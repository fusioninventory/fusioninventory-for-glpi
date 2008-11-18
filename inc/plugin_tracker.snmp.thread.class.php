<?php
/*
 ----------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

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

// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

class Threads extends CommonDBTM
{

	function showProcesses($target,$array_name="")
	{

		global $DB,$LANG,$LANGTRACKER;

		$minfreq = 9999;
		$CommonItem = new CommonItem;

		$sql = 	"SELECT ID, process_id, SUM(network_queries) AS network_queries, status, COUNT(*) AS threads_number, " .
			"MIN(start_time) AS starting_date, MAX(end_time) AS ending_date, TIME_TO_SEC(MAX(end_time))-TIME_TO_SEC(MIN(start_time)) AS duree, " .
			"end_time >= DATE_ADD(NOW(), INTERVAL -" . $minfreq . " HOUR) AS DoStat, error_msg, network_queries, 
			printer_queries, ports_queries ".
		      	"FROM glpi_plugin_tracker_processes GROUP BY process_id ORDER BY ID DESC";
	     	$result = $DB->query($sql);

		echo "<div id='barre_onglets'><ul id='onglet'>\n";
		echo "<li ";
		if ($array_name == "")
			echo "class='actif'";
		echo "><a href='plugin_tracker.processes.php'>&nbsp;".$LANGTRACKER["processes"][0]."&nbsp;</a></li>\n";
		echo "<li ";
		if ($array_name == "unknow_mac")
			echo "class='actif'";
		echo "><a href='plugin_tracker.processes.unknow_mac.php'>&nbsp;".$LANGTRACKER["processes"][13]."&nbsp;</a></li>\n";
		echo "<li><a href=''>&nbsp;".$LANGTRACKER["processes"][11]."&nbsp;</a></li>\n";
		echo "<li><a href=''>&nbsp;".$LANGTRACKER["processes"][12]."&nbsp;</a></li>\n";
		echo "<ul></div>\n";

	   echo "<div align='center'>";
		echo "<form name='processes' action=\"$target\" method=\"post\">";

		echo "<table class='tab_cadre_fixe' cellpadding='9'>";
		
		if ($array_name == "")
		{
			echo "<tr><th colspan='12'>" . $LANGTRACKER["processes"][0] . "</th></tr>";
			echo "<tr>"; 
			echo"<th></th>";
			echo"<th>".$LANGTRACKER["processes"][1]."</th>";
			echo"<th>".$LANGTRACKER["processes"][2]."</th>";
			echo"<th>".$LANGTRACKER["processes"][3]."</th>";
			echo"<th>".$LANGTRACKER["processes"][4]."</th>";
			echo"<th>".$LANGTRACKER["processes"][5]."</th>";
			echo"<th>".$LANGTRACKER["processes"][6]."</th>";
			echo"<th>".$LANGTRACKER["processes"][7]."</th>";
			echo"<th>".$LANGTRACKER["processes"][8]."</th>";
			echo"<th>".$LANGTRACKER["processes"][9]."</th>";
			echo"<th>".$LANGTRACKER["processes"][10]."</th>";		
			echo "</th></tr>\n";
		
// VERIFIER

			if ($DB->numrows($result)) {
				while ($thread = $DB->fetch_array($result)){
					//if ($config->fields["display_empty"] || $thread["status"] != STATE_FINISHED || (!$config->fields["display_empty"] && $thread["total_machines"] > 0 && $thread["status"] == STATE_FINISHED))
					//{
						echo "<tr class='tab_bg_1'>"; 
						
						//if ($canedit){
						//	echo "<td width='10'>";
						//	$sel="";
						//	if (isset($_GET["select"])&&$_GET["select"]=="all") $sel="checked";
						//	echo "<input type='checkbox' name='item[".$thread["process_id"]."]' value='1' $sel>";
						//	echo "</td>";
						//}
						//else	 echo "<td width='10'>&nbsp;</td>";
						echo "<td width='10'>&nbsp;</td>";
					
						echo "<td align='center'><a href=\"./plugin_mass_ocs_import.process.form.php?pid=".$thread["process_id"]."\">".$thread["process_id"]."</a></td>";
						echo "<td align='center'>";
						
						switch(getProcessStatus($thread["process_id"]))
						{
							case STATE_FINISHED :
								echo "<img src='../pics/export.png'>";
								break;
							case STATE_RUNNING :
								echo "<img src='../pics/wait.png'>";
								break;
							case STATE_STARTED :
								echo "<img src='../pics/ok2.png'>";
								break;
						}
							
						echo "</td>";
						echo "<td align='center'>".$thread["threads_number"]."</td>";
						echo "<td align='center'>".convDateTime($thread["starting_date"])."</td>";
						echo "<td align='center'>".convDateTime($thread["ending_date"])."</td>";
						echo "<td align='center'>".$thread["network_queries"]."</td>";
						echo "<td align='center'>".$thread["printer_queries"]."</td>";
						echo "<td align='center'>".$thread["ports_queries"]."</td>";
						echo "<td align='center'>".$thread["error_msg"]."</td>";
						
						echo "<td align='center'>";
						if ($thread["status"] == STATE_FINISHED)
							echo timestampToString($thread["duree"]);
						else
							echo "-----";	
						
						echo "</td>";
						
						//echo "<td align='center'>"; 
						//if ($thread["ocs_server_id"] != -1)
						//{
						//	$ocsConfig = getOcsConf($thread["ocs_server_id"]);
						//	echo "<a href=\"".GLPI_ROOT."/front/ocsng.form.php?ID=".$ocsConfig["ID"]."\">".$ocsConfig["name"]."</a>";
						//}
						//else
						//	echo $OCSMASSIMPORTLANG["config"][22];
							
						//echo "</td>";
						echo "</tr>\n";
					//}	
				}
			}

// FIN DES CHOSES A VERIFIER
		}	
		else if ($array_name == "unknow_mac")
		{
			echo "<tr><th colspan='12'>" . $LANGTRACKER["processes"][14] . "</th></tr>";
			echo "<tr>"; 
			echo"<th></th>";
			echo"<th>".$LANGTRACKER["processes"][1]."</th>";
			echo"<th>".$LANG["common"][1]."</th>";
			echo"<th>".$LANG["setup"][175]."</th>";
			echo"<th>".$LANG["networking"][15]."</th>";
			echo"<th>".$LANG["common"][27]."</th>";
			echo "</th></tr>\n";
		
			$sql_mac = 	"SELECT *
		   FROM glpi_plugin_tracker_processes_values 
		   ORDER BY FK_processes DESC, date DESC";
	     	$result_mac = $DB->query($sql_mac);
			while ($thread_mac = $DB->fetch_array($result_mac))
			{
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'></td>";
				echo "<td align='center'>".$thread_mac["FK_processes"]."</td>";
				
				
				$query_port = "SELECT * FROM glpi_networking_ports 
				WHERE ID='".$thread_mac["port"]."' ";
				$result_port = $DB->query($query_port);
				$port_name = "";
				while ($thread_port = $DB->fetch_array($result_port))
				{
					$on_device = $thread_port["on_device"];
					$device_type = $thread_port["device_type"];
					$port_name = $thread_port["name"];
				}
				if (isset($on_device) AND isset($device_type))
				{
					$CommonItem->getFromDB($device_type,$on_device);
					echo "<td align='center'>".$CommonItem->getLink(1)."</td>";
				}
				else
				{
					echo "<td align='center'></td>";
				}
				echo "<td align='center'><a href='".GLPI_ROOT."/front/networking.port.php?ID=".$thread_mac["port"]."'>".$port_name."</a></td>";
				echo "<td align='center'>".$thread_mac["unknow_mac"]."</td>";
				echo "<td align='center'>".$thread_mac["date"]."</td>";
				echo "</tr>";
			
			}
		
		}
		
		echo "</table>";

	}
	
	
	
	function addProcess($PID)
	{
	
		global $DB;
				
		$query = "INSERT INTO glpi_plugin_tracker_processes
			(start_time,process_id,status)
			
		VALUES('".date("Y-m-d H:i:s")."','".$PID."','1') ";
		
		$DB->query($query);

	}
	
	
	function updateProcess($PID, $NetworkQueries, $PrinterQueries, $portsQueries, $errors)
	{
	
		global $DB;
		
		$query = "UPDATE glpi_plugin_tracker_processes
		
		SET end_time='".date("Y-m-d H:i:s")."', status='3', error_msg='".$errors."', network_queries='".$NetworkQueries."',
			printer_queries='".$PrinterQueries."', ports_queries='".$portsQueries."'
		
		WHERE process_id='".$PID."' ";
		
		$DB->query($query);
	
	}


	function addProcessValues($PID, $field,$FK_port,$value)
	{
	
		global $DB;
		
		$query = "INSERT INTO glpi_plugin_tracker_processes_values
		(FK_processes,port,".$field.",date)
		VALUES('".$PID."','".$FK_port."','".$value."','".date("Y-m-d H:i:s")."')";
		
		$DB->query($query);

	}	

}


?>