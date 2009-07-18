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

function plugin_tracker_search_ip_ocs_servers($MAC) {
	global $DBocs, $DB;

	$listserver = plugin_tracker_getOCSServerID();
	foreach ($listserver as $num=> $ocs_server_id) {
		checkOCSconnection($ocs_server_id);
		$res = $DBocs->query("SELECT IP FROM netmap WHERE MAC='".strtoupper($MAC)."'");
		if ($DBocs->numrows($res) == 1) {
			return $DBocs->result($res,0,"IP");
      } else {
			return '';
      }
	}
	
}

function plugin_tracker_search_name_ocs_servers($MAC) {
	global $DBocs, $DB;

	$listserver = plugin_tracker_getOCSServerID();
	foreach ($listserver as $num=> $ocs_server_id) {
		checkOCSconnection($ocs_server_id);
		$res = $DBocs->query("SELECT NAME FROM netmap WHERE MAC='".strtoupper($MAC)."'");
		if ($DBocs->numrows($res) == 1) {
			return $DBocs->result($res,0,"NAME");
      } else {
			return '';
      }
	}

}


/**
 * Get a random ocs_server_id
 * @return an ocs server id
 */
function plugin_tracker_getOCSServerID() {
	global $DB;
	$list = array();
	$sql = "SELECT ID FROM glpi_ocs_config";
	$result = $DB->query($sql);
	if ($DB->numrows($result) > 0) {
		$datas = $DB->fetch_array($result);
		$list[] = $datas["ID"];
	}
	return $list;
}

?>