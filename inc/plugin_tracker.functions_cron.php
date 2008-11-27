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
// Original Author of file: Nicolas SMOLYNIEC
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')){
	die("Sorry. You can't access directly to this file");
}

function plugin_tracker_printingCounters() {

	$NEEDED_ITEMS = array("printer");
	include (GLPI_ROOT."/inc/includes.php");
	
	$config = new plugin_tracker_config();
	$printer = new plugin_tracker_printer_snmp();
	
	// Get date
	$date = date("Y-m-d H:i:s");

	// if functionality activated
	if ( $config->isActivated('counters_statement') )
		$printer->cron($date);
}

function plugin_tracker_cleaningHistory() {
	$config = new plugin_tracker_config();
	// if functionality activated
	if ( $config->isActivated('cleaning') )
		return false;
	
}
?>
