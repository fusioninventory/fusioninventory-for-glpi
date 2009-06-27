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

	function __construct() {
		$this->table = "glpi_plugin_tracker_unknown_mac";
		$this->type = PLUGIN_TRACKER_MAC_UNKNOWN;
	}


	function showProcesses($target,$array_name="")
	{

		global $DB,$LANG;

		plugin_tracker_checkRight("snmp_scripts_infos","r");
		
		$minfreq = 9999;
		$CommonItem = new CommonItem;
		$Threads = new Threads;


//		$sql = "SELECT ID, process_id, SUM(network_queries) AS network_queries, status, COUNT(*) AS threads_number, " .
//			"MIN(start_time) AS starting_date, MAX(end_time) AS ending_date, TIME_TO_SEC(MAX(end_time))-TIME_TO_SEC(MIN(start_time)) AS duree, " .
//			"end_time >= DATE_ADD(NOW(), INTERVAL -" . $minfreq . " HOUR) AS DoStat, error_msg, network_queries,
//			printer_queries, ports_queries, discovery_queries ";
//		$sql .=    	"FROM glpi_plugin_tracker_processes GROUP BY process_id ORDER BY ID DESC";
		$sql = "SELECT ID, process_id, network_queries, status, thread_id AS threads_number,
			start_time AS starting_date, end_time AS ending_date, TIME_TO_SEC(end_time)-TIME_TO_SEC(start_time) AS duree,
			error_msg, network_queries,
			printer_queries, ports_queries, discovery_queries
			FROM glpi_plugin_tracker_processes
			ORDER BY ID DESC";


		$result = $DB->query($sql);

/*		echo "<div id='barre_onglets'><ul id='onglet'>\n";
		echo "<li ";
		if ($array_name == "")
			echo "class='actif'";
		echo "><a href='plugin_tracker.processes.php'>&nbsp;".$LANG['plugin_tracker']["processes"][0]."&nbsp;</a></li>\n";
		echo "<li ";
		if ($array_name == "errors")
			echo "class='actif'";
		echo "><a href='plugin_tracker.processes.errors.php'>&nbsp;".$LANG['plugin_tracker']["processes"][12]."&nbsp;</a></li>\n";
		echo "<li ";
		if ($array_name == "connection")
			echo "class='actif'";
		echo "><a href='plugin_tracker.processes.connection.php'>&nbsp;".$LANG['plugin_tracker']["snmp"][50]."&nbsp;</a></li>\n";

		echo "<ul>\n";
		echo "</div>\n";
*/
	   echo "<div align='center'>";
//		echo "<form name='processes' action=\"$target\" method=\"post\">";

		echo "<table class='tab_cadre_fixe' cellpadding='9'>";
		
		if ($array_name == "")
		{
			echo "<tr><th colspan='12'>" . $LANG['plugin_tracker']["processes"][0] . "</th></tr>";
			echo "<tr>"; 
			echo "<th></th>";
			echo "<th>".$LANG['plugin_tracker']["processes"][1]."</th>";
			echo "<th>".$LANG['plugin_tracker']["processes"][2]."</th>";
			echo "<th>".$LANG['plugin_tracker']["processes"][3]."</th>";
			echo "<th>".$LANG['plugin_tracker']["processes"][4]."</th>";
			echo "<th>".$LANG['plugin_tracker']["processes"][5]."</th>";
			echo "<th>".$LANG['plugin_tracker']["processes"][6]."</th>";
			echo "<th>".$LANG['plugin_tracker']["processes"][8]."</th>";
			echo "<th>".$LANG['plugin_tracker']["processes"][7]."</th>";
//			echo "<th>".$LANG['plugin_tracker']["discovery"][3]."</th>";
			echo "<th>".$LANG['plugin_tracker']["processes"][9]."</th>";
			echo "<th>".$LANG['plugin_tracker']["processes"][10]."</th>";
			echo "</th></tr>\n";
		
			if ($DB->numrows($result)) {
				while ($thread = $DB->fetch_array($result)){

					echo "<tr class='tab_bg_1'>"; 
					echo "<td width='10'>&nbsp;</td>";
					echo "<td align='center'><!--<a href=\"./plugin_mass_ocs_import.process.form.php?pid=".$thread["process_id"]."\">-->".$thread["process_id"]."<!--</a>--></td>";
					echo "<td align='center'>";
					
					switch($Threads->getProcessStatus($thread["process_id"]))
					{
						case 3 :
							echo "<img src='../pics/export.png'>";
							break;
						case 2 :
							echo "<img src='../pics/wait.png'>";
							break;
						case 1 :
							echo "<img src='../pics/ok2.png'>";
							break;
					}
						
					echo "</td>";
					echo "<td align='center'>".$thread["threads_number"]."</td>";
					echo "<td align='center'>".convDateTime($thread["starting_date"])."</td>";
					echo "<td align='center'>".convDateTime($thread["ending_date"])."</td>";
					echo "<td align='center'>".$thread["network_queries"]."</td>";
					echo "<td align='center'>".$thread["ports_queries"]."</td>";
					echo "<td align='center'>".$thread["printer_queries"]."</td>";
//					echo "<td align='center'>".$thread["discovery_queries"]."</td>";
					echo "<td align='center'>";
					if ($thread["error_msg"] > 0)
						echo "<a href='plugin_tracker.processes.errors.php?process=".$thread["process_id"]."'>".$thread["error_msg"]."</a>";
					else
						echo $thread["error_msg"];
					echo "</td>";
					
					echo "<td align='center'>";
					if ($thread["status"] == 3)
						echo timestampToString($thread["duree"]);
					else
						echo "-----";	
					
					echo "</td>";
					echo "</tr>\n";
				}
			}

		}	
		else if ($array_name == "unknow_mac")
		{
			// Search form in form file
		}
		// **** Display errors on execution **** 
		else if ($array_name == "errors")
		{
			echo "<tr><th colspan='12'>" . $LANG['plugin_tracker']["processes"][12] . "</th></tr>";
			echo "<tr>"; 
			echo"<th></th>";
			echo"<th>".$LANG['plugin_tracker']["processes"][1]."</th>";
			echo"<th>".$LANG["common"][1]."</th>";
			echo"<th>".$LANG['plugin_tracker']["processes"][12]."</th>";
			echo"<th>".$LANG["common"][27]."</th>";
			echo "</th></tr>\n";
			
			$process = '';
			if (isset($_GET['process']))
				$process = " AND 	FK_processes='".$_GET['process']."' ";
			$sql_errors = 	"SELECT *
		   FROM glpi_plugin_tracker_processes_values
		   WHERE snmp_errors!=''
		   ".$process."
		   ORDER BY FK_processes DESC, date DESC";
	     	$result_errors = $DB->query($sql_errors);
			while ($thread_errors = $DB->fetch_array($result_errors))
			{
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'></td>";
				echo "<td align='center'>".$thread_errors["FK_processes"]."</td>";
				
				if ($thread_errors["port"] == "0")
				{
					$on_device = $thread_errors["device_ID"];
					$device_type = $thread_errors["device_type"];
				}
				else
				{
					$query_port = "SELECT * FROM glpi_networking_ports 
					WHERE ID='".$thread_errors["port"]."' ";
					$result_port = $DB->query($query_port);
					$port_name = "";
					while ($thread_port = $DB->fetch_array($result_port))
					{
						$on_device = $thread_port["on_device"];
						$device_type = $thread_port["device_type"];
						$port_name = $thread_port["name"];
					}
				}
				if (isset($on_device) AND isset($device_type))
				{
					$CommonItem->getFromDB($device_type,$on_device);
					echo "<td align='center'>".$CommonItem->getLink(1)."</td>";
				}
				else
					echo "<td align='center'></td>";
				$explode = explode('%',$thread_errors["snmp_errors"]);
				$explode[1] = preg_replace("/\[model(.*?)\]/", "<a href='plugin_tracker.models.form.php?ID=$1'>".$LANG['plugin_tracker']["profile"][24]."</a>",$explode[1]);
				echo "<td align='center'><b>".$LANG['plugin_tracker']["errors"][$explode[0]]."</b>";
				echo "<br/>".str_replace('--','<br/>',$explode[1]);
				echo "</td>";
				echo "<td align='center'>".convDateTime($thread_errors["date"])."</td>";
				echo "</tr>";
			}		
		}
		// **** Display connections on execution **** 
		else if ($array_name == "connection")
		{
			$Netwire = new Netwire;
			$netport=new Netport;			
			
			echo "<tr><th colspan='12'>".$LANG['plugin_tracker']["snmp"][50]."</th></tr>";
			echo "<tr>"; 
			echo"<th></th>";
			echo"<th>".$LANG['plugin_tracker']["processes"][1]."</th>";
			echo"<th>".$LANG["joblist"][0]."</th>";
			echo"<th>".$LANG["common"][1]."</th>";
			echo"<th>".$LANG["setup"][175]."</th>";
			echo"<th>".$LANG["common"][27]."</th>";
			echo"<th>".$LANG["common"][1]."</th>";
			echo"<th>".$LANG["setup"][175]."</th>";
			echo "</th></tr>\n";
		
			$sql_connection = "SELECT *
		   FROM glpi_plugin_tracker_snmp_history
		   WHERE Field=''
		   	OR Field='0'
		   	AND ((new_device_type='2')
		   		OR (old_device_type='2'))
		   ORDER BY FK_process DESC, date_mod DESC";
	     	$result_connection = $DB->query($sql_connection);
			while ($thread_connection = $DB->fetch_array($result_connection))
			{
				echo "<tr class='tab_bg_1'>";
				echo "<td align='center'></td>";
				echo "<td align='center'>".$thread_connection["FK_process"]."</td>";
				
				if (($thread_connection["old_device_ID"] != "0") OR ($thread_connection["new_device_ID"] != "0"))
				{
					// Connections and disconnections
					if ($thread_connection["old_device_ID"] != "0")
					{
						// disconnection
						echo "<td align='center'>".$LANG["central"][6]."</td>";

					}
					else if ($thread_connection["new_device_ID"] != "0")
					{
						// connection
						echo "<td align='center'>".$LANG["log"][55]."</td>";

					}

					$query_port = "SELECT * FROM glpi_networking_ports 
					WHERE ID='".$thread_connection["FK_ports"]."' ";
					$result_port = $DB->query($query_port);
					$port_name = "";
					$device_type = 0;
					$on_device = 0;
					while ($thread_port = $DB->fetch_array($result_port))
					{
						$port_name = $thread_port["name"];
						$device_type = $thread_port["device_type"];
						$on_device = $thread_port["on_device"];
						
					}
					$CommonItem->getFromDB($device_type,$on_device);
					echo "<td align='center'>".$CommonItem->getLink(1)."</td>";
					
					//echo "<td></td>";
					echo "<td align='center'><a href='".GLPI_ROOT."/front/networking.port.php?ID=".$thread_connection["FK_ports"]."'>".$port_name."</a></td>";

					echo "<td align='center'>".$thread_connection["date_mod"]."</td>";
					if ($thread_connection["old_device_ID"] != "0")
					{
						$CommonItem->getFromDB($thread_connection["old_device_type"],$thread_connection["old_device_ID"]);
						echo "<td align='center'>".$CommonItem->getLink(1)."</td>";	
						$queryPort = "SELECT * 
						FROM glpi_networking_ports
						WHERE ifmac='".$thread_connection['old_value']."' 
						LIMIT 0,1";
						$resultPort = $DB->query($queryPort);		
						$dataPort = $DB->fetch_assoc($resultPort);
					}
					else if ($thread_connection["new_device_ID"] != "0")
					{
						$CommonItem->getFromDB($thread_connection["new_device_type"],$thread_connection["new_device_ID"]);
						echo "<td align='center'>".$CommonItem->getLink(1)."</td>";
						$queryPort = "SELECT * 
						FROM glpi_networking_ports
						WHERE ifmac='".$thread_connection['new_value']."' 
						LIMIT 0,1";
						$resultPort = $DB->query($queryPort);		
						$dataPort = $DB->fetch_assoc($resultPort);
					}
					// Search network card with mac address
					echo "<td align='center'><a href='".GLPI_ROOT."/front/networking.port.php?ID=".$dataPort['ID']."'>".$dataPort['name']."</a></td>";



				}
			}
		}
		echo "</table>";
	}
	
	
	
	function addProcess($PID,$num_processes)
	{	
		global $DB;
				
		$query = "INSERT INTO glpi_plugin_tracker_processes
			(start_time,process_id,status,error_msg,thread_id)
		VALUES('".date("Y-m-d H:i:s")."','".$PID."','1',0,'".$num_processes."') ";
		$DB->query($query);
	}
	
	
	function updateProcess($PID, $NetworkQueries=0, $PrinterQueries=0, $DiscoveryQueries=0, $errors=0)
	{
		global $DB;

		$query = "UPDATE glpi_plugin_tracker_processes
		SET network_queries=network_queries + ".$NetworkQueries.",
			printer_queries=printer_queries + ".$PrinterQueries.",
			discovery_queries=discovery_queries + ".$DiscoveryQueries.",
			error_msg=error_msg + ".$errors."
		WHERE process_id='".$PID."' ";
		$DB->query($query);
	}



	function closeProcess($PID)
	{
		global $DB;
		$query = "UPDATE glpi_plugin_tracker_processes
		SET end_time='".date("Y-m-d H:i:s")."', status='3'
		WHERE process_id='".$PID."' ";
		$DB->query($query);
	}
	
	
	
	function addProcessValues($PID, $field,$FK_port=0,$value,$device_ID=0,$device_type=0)
	{
		global $DB;
		
		$query = "INSERT INTO glpi_plugin_tracker_processes_values
		(FK_processes,port,device_ID,device_type,".$field.",date)
		VALUES('".$PID."','".$FK_port."','".$device_ID."','".$device_type."','".$value."','".date("Y-m-d H:i:s")."')";
		$DB->query($query);
	}	


	function getProcessStatus($pid)
	{
		global $DB;
		$sql = "SELECT status FROM glpi_plugin_tracker_processes WHERE process_id=" . $pid;
		$result = $DB->query($sql);
		$status = 0;
		$thread_number = 0;
	
		$thread_number = $DB->numrows($result);
	
		while ($thread = $DB->fetch_array($result)) {
			$status += $thread["status"];
		}
	
		if ($status < $thread_number * 3)
			return 2;
		else
			return 3;
	}



	function lastProcess($type)
	{
		global $DB;
		
		$PID = "";
		switch ($type)
		{
			case NETWORKING_TYPE:
				$query = "SELECT * FROM glpi_plugin_tracker_processes
				WHERE network_queries > 0 
				ORDER BY ID DESC
				LIMIT 0,1 ";
				$result = $DB->query($query);
				$data = $DB->fetch_assoc($result);
				$PID = $data["process_id"];
				break;
		}
		return $PID;
	}
	
	
	
	function unknownMAC($PID,$FK_port,$macaddress,$sport,$ip_unknown='')
	{
		global $DB;

		// Detect if mac adress is different of internal mac address of port
		$query = "SELECT *  FROM glpi_networking_ports
		WHERE ID='".$FK_port."'
		 AND ifmac='".$macaddress."' "; 
		$result = $DB->query($query);		
		if (mysql_num_rows($result) == "0"){
			addLogConnection("remove",$sport,$PID);
			removeConnector($sport);

			// Search IP in OCS IPdiscover if OCS servers specified
			if (empty($ip_unknown))
				$ip_unknown = plugin_tracker_search_ip_ocs_servers($macaddress);

			// Search if a line exist
			$query = "SELECT *  FROM glpi_plugin_tracker_unknown_mac
			WHERE unknow_mac='".$macaddress."'
			ORDER BY end_time DESC
			LIMIT 0,1";
			$result = $DB->query($query);
			if ($DB->numrows($result) == 0)
			{
				// Add in port history connection to this mac address
				plugin_tracker_addLogConnection_unknown_mac($macaddress,$FK_port,$PID);

				// Insert
				$query_ins = "INSERT INTO glpi_plugin_tracker_unknown_mac
					(start_FK_processes, start_time, port,unknow_mac,unknown_ip,end_time,end_FK_processes)
				VALUES ('".$PID."','".date("Y-m-d H:i:s")."','".$FK_port."','".$macaddress."','".$ip_unknown."','".date("Y-m-d H:i:s")."','".$PID."')";
				$DB->query($query_ins);
			}
			else
			{
				while ($data = $DB->fetch_array($result))
				{
					// Add in port history connection to this mac address
					plugin_tracker_addLogConnection_unknown_mac($macaddress,$FK_port,$PID);

					if ($data["port"] == $FK_port)
					{
						// Update
						$query_upd = "UPDATE glpi_plugin_tracker_unknown_mac
						SET end_time='".date("Y-m-d H:i:s")."',end_FK_processes='".$PID."' 
						WHERE ID='".$data["ID"]."' ";
						$DB->query($query_upd);
					}
					else
					{
						// Insert
						$query_ins = "INSERT INTO glpi_plugin_tracker_unknown_mac
							(start_FK_processes, start_time, port,unknow_mac,unknown_ip,end_time,end_FK_processes)
						VALUES ('".$PID."','".date("Y-m-d H:i:s")."','".$FK_port."','".$macaddress."','".$ip_unknown."','".date("Y-m-d H:i:s")."','".$PID."')";
						$DB->query($query_ins);
					}
				}
			}
		}
	}


	
	function getUnknownMacFromPIDandPort($PID,$FK_port)
	{
		global $DB;	
		
		$unknownMac = "";
		$unknownIP = "";
		$query = "SELECT unknow_mac,unknown_ip FROM glpi_plugin_tracker_unknown_mac
		WHERE (start_FK_processes<".$PID." OR start_FK_processes=".$PID.")
			AND (end_FK_processes>".$PID." OR end_FK_processes=".$PID.")
			AND port='".$FK_port."' 
		LIMIT 0,1";
	
		if ($result = $DB->query($query)) {
			while ($data = $DB->fetch_array($result))
			{
				$unknownMac = $data["unknow_mac"];
				$unknownIP = $data["unknown_ip"];
			}
		}
		return array($unknownMac, $unknownIP);
	}
	
	
	
	var $pref ; // process reference
	var $pipes; // stdio
	var $buffer; // output buffer
	
	
	function Create ($file) {
		$t = new Threads;
		$descriptor = array (0 => array ("pipe", "r"), 1 => array ("pipe", "w"), 2 => array ("pipe", "w"));
		$t->pref = proc_open ("php -q $file ", $descriptor, $t->pipes);
		stream_set_blocking ($t->pipes[1], 0);
		return $t;
	}
	
	
	
	function isActive () {
		$this->buffer .= $this->listen();
		$f = stream_get_meta_data ($this->pipes[1]);
		return !$f["eof"];
	}
	
	
	
	function close () {
		$r = proc_close ($this->pref);
		$this->pref = NULL;
		return $r;
	}
	
	
	
	function listen () {
		$buffer = $this->buffer;
		$this->buffer = "";
		while ($r = fgets ($this->pipes[1], 1024)) {
			$buffer .= $r;
		}
		
		return $buffer;
	}
	
	
	
	function getError () {
		$buffer = "";
		while ($r = fgets ($this->pipes[2], 1024)) {
			$buffer .= $r;
		}
		return $buffer;
	}

	
}

?>
