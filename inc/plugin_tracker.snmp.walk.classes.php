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

if (!defined('GLPI_ROOT'))
	die("Sorry. You can't access directly to this file");


class plugin_tracker_walk extends CommonDBTM
{
	function __construct()
	{
		$this->table = "glpi_plugin_tracker_walks";
		$this->type = -1;
	}



	function GetoidValues($FK_agent_process,$ID_Device,$type)
	{
		global $DB;

		$query = "SELECT * FROM glpi_plugin_tracker_walks
		WHERE on_device='".$ID_Device."'
			AND device_type='".$type."'
			AND FK_agents_processes='".$FK_agent_process."'";
		$result=$DB->query($query);
		while ( $data=$DB->fetch_array($result) )
		{
			$oidvalues[$data['oid']] = $data['value'];
		}
		return $oidvalues;
	}
	
	

	function GetoidValuesFromWalk($oidvalues,$oidsModel,$oid_dyn=0)
	{
		foreach ($oidvalues as $oid=>$value)
		{
			if (ereg($oidsModel."\.", $oid))
			{
				if ($oid_dyn == "0")
					$List[] = $value;
				else if ($oid_dyn == "1")
					$List[] = str_replace($oidsModel.".", "", $oid);
			}
		}
		if (!isset($List))
			return;
		return $List;
	}
	
	
	
	function GetValuesFromOid($oid)
	{
	
	
	
	}
}
?>