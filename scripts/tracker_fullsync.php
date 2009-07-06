<?php
/*
 * @version $Id: ocsng_fullsync.php 4980 2007-05-15 13:32:29Z walid $
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2008 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

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
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------
ini_set("memory_limit","-1");
ini_set("max_execution_time", "0");

$xml_auth_rep = "";

# Converts cli parameter to web parameter for compatibility
if ($argv) {
	for ($i=1;$i<count($argv);$i++)
	{
		$it = split("=",$argv[$i]);
		$it[0] = preg_replace('/^--/i','',$it[0]);
		$_GET[$it[0]] = $it[1];
	}
}

// Can't run on MySQL replicate
$USEDBREPLICATE=0;
$DBCONNECTION_REQUIRED=1;


// MASS IMPORT for OCSNG
define('GLPI_ROOT', '../../..');

$NEEDED_ITEMS=array("computer","device","printer","networking","peripheral","monitor","software","infocom",
	"phone","tracking","enterprise","reservation","setup","group","registry","rulesengine","ocsng","admininfo");
include (GLPI_ROOT . "/config/based_config.php");
include (GLPI_ROOT . "/inc/includes.php");
include (GLPI_ROOT . "/plugins/tracker/inc/plugin_tracker.snmp.mapping.constant.php");

$CFG_GLPI["debug"]=0; 

//Check if plugin is installed, ie if tables are present
if (!TableExists("glpi_plugin_tracker_networking")) {
	echo "Plugin Tracker pas installé!!";
	exit(1);
}

$thread_nbr='';
$thread_id='';
$synchronized_nbr= 0;
$linked_nbr= 0;
$imported_nbr= 0;
$failed_nbr= 0;
$fields=array();
$type='';

$logs = new plugin_tracker_logs;

if(isset($_GET['update_device_process'])){
	// tracker_fullsync.php --update_device_process=1 --id=".$IDDevice." --FK_process=".$FK_process." --FK_agent_process=".$ArrayListAgentProcess[$num]." --type=".$ArrayListType[$num]);

	$processes = new plugin_tracker_Threads;
	$processes_values = plugin_tracker_UpdateDeviceBySNMP_process($_GET['id'],$_GET['FK_process'],$xml_auth_rep,$_GET['type'],$_GET['FK_agent_process']);
}
else
{
	if (isset($_GET["type"]))
		$type=$_GET["type"];
	
	//Get the script's process identifier
	if (isset($_GET["process_id"]))
		$fields["process_id"] = $_GET["process_id"];

	$config_snmp_script = new glpi_plugin_tracker_config_snmp_script;
	$nb_process_query = $config_snmp_script->getValue('nb_process');

	// Add process into database
	$processes = new plugin_tracker_Threads;
	$processes->addProcess($fields["process_id"],$nb_process_query);
	
	// SNMP is working
	$logs->write("tracker_snmp",">>>>>>>>>> Starting Script <<<<<<<<<<",'');

	$OS = "";
	if (isset($_SERVER["OSTYPE"]))
		$OS = $_SERVER["OSTYPE"];
	else if (isset($_SERVER["OS"]))
		$OS = $_SERVER["OS"];

	$logs->write("tracker_snmp","Operating System = ".$OS,'');

	$query = "SELECT process_number FROM glpi_plugin_tracker_agents_processes
	ORDER BY process_number";
	$result=$DB->query($query);
	while ( $data=$DB->fetch_array($result) )
	{
		// Test if XLM file from Agent exist
		if (file_exists(GLPI_PLUGIN_DOC_DIR."/tracker/".$data['process_number']."-device.xml"))
		{
			$xml_file[] = GLPI_PLUGIN_DOC_DIR."/tracker/".$data['process_number']."-device.xml";
			$xml = simplexml_load_file(GLPI_PLUGIN_DOC_DIR."/tracker/".$data['process_number']."-device.xml");
			foreach($xml->device as $device)
			{
				$ArrayListDevice[] = $device->infos->id;
				$ArrayListType[] = $device->infos->type;
				$ArrayListAgentProcess[] = $data['process_number'];
			}
		}
	}
	if (isset($ArrayListDevice))
		plugin_tracker_UpdateDeviceBySNMP_startprocess($ArrayListDevice,$fields["process_id"],$xml_auth_rep,$ArrayListType,$ArrayListAgentProcess);

	foreach ( $xml_file as $num=>$filename )
		unlink($filename);

	// Create connections between switchs
	$tmpc = new plugin_tracker_tmpconnections;
	$tmpc->WireInterSwitchs();

	$processes->closeProcess($fields["process_id"]);
}

?>