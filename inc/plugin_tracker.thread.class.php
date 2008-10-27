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

	function showProcesses($target)
	{

		global $DB,$LANG,$LANGTRACKER;

		echo "<div id='barre_onglets'><ul id='onglet'>\n";
		echo "<li class='actif'><a href='" . $_SERVER["PHP_SELF"] . "'>&nbsp;".$LANGTRACKER["processes"][0]."&nbsp;</a></li>\n";
		echo "<li><a href=''>&nbsp;".$LANGTRACKER["processes"][11]."&nbsp;</a></li>\n";
		echo "<li><a href=''>&nbsp;".$LANGTRACKER["processes"][12]."&nbsp;</a></li>\n";
		echo "<ul></div>\n";

	   echo "<div align='center'>";
		echo "<form name='processes' action=\"$target\" method=\"post\">";

		echo "<table class='tab_cadre_fixe' cellpadding='9'>";
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
			printer_queries='".$PrinterQueries."', ports_queries='".$PortsQueries."'
		
		WHERE process_id='".$PID."' ";
		
		$DB->query($query);
		
	}
	

}


?>