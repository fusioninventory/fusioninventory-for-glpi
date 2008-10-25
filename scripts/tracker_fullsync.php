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

# Converts cli parameter to web parameter for compatibility
if ($argv) {
	for ($i=1;$i<count($argv);$i++)
	{
		$it = split("=",$argv[$i]);
		$it[0] = eregi_replace('^--','',$it[0]);
		$_GET[$it[0]] = $it[1];
	}
}

// Can't run on MySQL replicate
$USEDBREPLICATE=0;
$DBCONNECTION_REQUIRED=1;


// MASS IMPORT for OCSNG
define('GLPI_ROOT', '../../..');

$NEEDED_ITEMS=array("mass_ocs_import","ocsng","computer","device","printer","networking","peripheral","monitor","software","infocom",
	"phone","tracking","enterprise","reservation","setup","rulesengine","rule.ocs","group","registry","rule.softwarecategories", 
	"rule.dictionnary.software", "rule.dictionnary.dropdown");
include (GLPI_ROOT . "/config/based_config.php");
include (GLPI_ROOT . "/inc/includes.php");

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


//Get script configuration

$config = new plugin_tracker_snmp;

$ArrayListNetworking = $config->getNetworkList();

$config->UpdateNetworkBySNMP($ArrayListNetworking);









// ***********************************************************************//
// ********************************* EXIT ********************************//
// ***********************************************************************//
exit();
define('ifNumber','1.3.6.1.2.1.2.1.0');
runkit_constant_remove('ifNumber');
define('ifNumber','1.3.6.1.2.1.2.1.0');
echo snmpget('192.168.1.191', 'public',"1.3.6.1.2.1.2.1.0");

echo "\n";
/* $result[0] = @snmpwalk('192.168.1.191', 'public', "1.3.6.1.2.1.2.1");
if ($result[0] != false )
{
	$pos = strpos($result[0], " ");
	$result[1] = substr($result[0], $pos+1);
	// if "" into the string
	$result[2] = str_replace('"', '', $result[1]);
	echo $result[2];
}
else
{
	echo "false";
}
*/



exit();
//Get script configuration
$config = new MassOCSImportConfig;
$config->getFromDB(1);

if (!isset($_GET["ocs_server_id"]) || $_GET["ocs_server_id"] == '') {
	$ocs_server_id = $config->fields["default_ocs_server"];
} else {
	$ocs_server_id = $_GET["ocs_server_id"];
}

if (isset($_GET["managedeleted"]) && $_GET["managedeleted"]==1 )
{
	echo "Clean old Not Imported machine list (". cleanNotImported($ocs_server_id) . ")\n";

	if ($ocs_server_id != -1) {
		FirstPass($ocs_server_id);

	} else {
		echo "Manage delete items in all OCS server\n";

		//Import from all the OCS servers 
		$query = "SELECT ID, name FROM glpi_ocs_config";
		$result = $DB->query($query);
		
		while ($ocs_server = $DB->fetch_array($result))	{
			FirstPass($ocs_server["ID"]);
		}
	}
} else { // not managedeleted
	    
	if (isset($_GET["thread_nbr"]) || isset($_GET["thread_id"])) {
		if (!isset($_GET["thread_id"]) || $_GET["thread_id"] > $_GET["thread_nbr"] || $_GET["thread_id"] <= 0) {
			echo ("thread_id invalid: thread_id must be between 1 and thread_nbr\n");
			exit (1);
		}
	
		$thread_nbr=$_GET["thread_nbr"];
		$thread_id=$_GET["thread_id"];
	
		echo "Thread #$thread_id : starting ($thread_id/$thread_nbr)\n";
		
	} else {
		$thread_nbr=-1;
		$thread_id=-1;
	}
	
		
	//Get the script's process identifier
	if (isset($_GET["process_id"]))
		$fields["process_id"] = $_GET["process_id"];
		
	$threadrecord = new ThreadRecord;
	
	//Prepare datas to log in db
	$fields["start_time"] = date("Y-m-d H:i:s");
	$fields["thread_id"] = $thread_id;
	$fields["status"]=STATE_STARTED;
	$fields["ocs_server_id"] = $ocs_server_id;
	$fields["imported_machines_number"]=0;
	$fields["synchronized_machines_number"]=0;
	$fields["failed_rules_machines_number"]=0;
	$fields["linked_machines_number"]=0;
	$fields["total_number_machines"]=0;
	$fields["error_msg"]='';
	$tid = $threadrecord->add($fields);
	$fields["ID"]=$tid;
		
	if ($ocs_server_id != -1) {
		$result = SecondPass($ocs_server_id,$thread_nbr,$thread_id,$fields,$config);
		if ($result) {
			$fields["imported_machines_number"]=$result["imported"];
			$fields["synchronized_machines_number"]=$result["synchronized"];
			$fields["failed_rules_machines_number"]=$result["failed"];
			$fields["linked_machines_number"]=$result["linked"];
		}
	} else {
		//Import from all the OCS servers 
		$query = "SELECT ID, name FROM glpi_ocs_config";
		$res = $DB->query($query);
		
		while ($ocs_server = $DB->fetch_array($res)) {
			$result = SecondPass($ocs_server["ID"],$thread_nbr,$thread_id,$fields,$config,
				$fields["imported_machines_number"], $fields["synchronized_machines_number"],
				$fields["failed_rules_machines_number"], $fields["linked_machines_number"]);

			if ($result) {	
				$fields["imported_machines_number"]=$result["imported"];
				$fields["synchronized_machines_number"]=$result["synchronized"];	
				$fields["failed_rules_machines_number"]=$result["failed"];
				$fields["linked_machines_number"]=$result["linked"];
			}
		}
	}
	
	//Write in db all the informations about this thread
	$fields["total_number_machines"]=
		$fields["imported_machines_number"]+
		$fields["synchronized_machines_number"]+
		$fields["failed_rules_machines_number"]+
		$fields["linked_machines_number"];
	$fields["end_time"] = date("Y-m-d H:i:s");
	$fields["status"]=STATE_FINISHED;
	$fields["error_msg"]="";
	if ($config->fields["enable_logging"])
		$threadrecord->update($fields);

	echo "\rThread #".$thread_id." : done !!\n";
}

function FirstPass ($ocs_server_id) {

	global $DB, $DBocs;

	if (checkOCSconnection($ocs_server_id)) {
		
		// Compute lastest new computer
		$query = "SELECT MAX(ID) FROM hardware";
		$max_id=0;
		if ($result=$DBocs->query($query)) {
			if($DBocs->numrows($result)>0){
				$max_id=$DBocs->result($result,0,0);
			}
		}

		// Compute lastest synchronization date
		$query = "SELECT MAX(last_ocs_update) FROM glpi_ocs_link WHERE ocs_server_id=$ocs_server_id";
		$max_date="0000-00-00 00:00:00";
		if ($result=$DB->query($query)) {
			if ($DB->numrows($result)>0){
					$max_date=$DB->result($result,0,0);
			}
		} 
		
		// Store result for second pass (multi-thread)
		$server = new MassOCSImportServer;
		$fields["max_ocs_id"]=$max_id;
		$fields["max_glpi_date"]=$max_date;
		$fields["ocs_server_id"]=$ocs_server_id;

		if ($server->getFromDB($ocs_server_id)) {
			$fields["ID"]=$server->fields["ID"];
			$server->update($fields);
		} else {
			$fields["ID"]=$server->add($fields);
		}
		
		// Handle ID changed or PC deleted in OCS.
		$cfg_ocs=getOcsConf($ocs_server_id);
		echo "Manage delete items in OCS server #$ocs_server_id: \"".$cfg_ocs["name"]."\"\n";
		ocsManageDeleted($ocs_server_id);
	}		
}

function SecondPass ($ocs_server_id, $thread_nbr, $thread_id,$fields,$config,$imported_nbr=0,$synchronized_nbr=0,$failed_nbr=0,$linked_nbr=0) {
	
	$server = new MassOCSImportServer;
	$cfg_ocs=getOcsConf($ocs_server_id);

	if (!$server->getFromDB($ocs_server_id)) {
		echo "thread #".$thread_id." : cannot get server information : ".$cfg_ocs["name"]."\n";
		return false;
		
	} else if (!checkOCSconnection($ocs_server_id)) {

		echo "thread #".$thread_id." : cannot contact server : ".$cfg_ocs["name"]."\n";
		return false;

	} else {
		return importFromOcsServer($cfg_ocs,$server,$thread_nbr, $thread_id,$fields,$config,$imported_nbr,$synchronized_nbr,$failed_nbr,$linked_nbr);
	}	
}

function importFromOcsServer($cfg_ocs, $server, $thread_nbr, $thread_id,$fields,$config,$imported_nbr,$synchronized_nbr,$failed_nbr,$linked_nbr) {
	
	global $DBocs;
 	
	echo "thread #".$thread_id." : import computers from server: '".$cfg_ocs["name"]."'\n";

	$where_multi_thread = '';
	if ($thread_nbr != -1 && $thread_id != -1 && $thread_nbr > 1) {
		$where_multi_thread = " AND ID % $thread_nbr = ".($thread_id-1);
	}
	if ($config->fields["import_limit"] > 0) {
		$where_limit = " LIMIT ".$config->fields["import_limit"];
	} else {
		$where_limit = "";
	}
		
	$query_ocs = "SELECT ID FROM hardware INNER JOIN accountinfo ON (hardware.ID = accountinfo.HARDWARE_ID) "
		." WHERE ((CHECKSUM&".intval($cfg_ocs["checksum"]).")>0 OR LASTDATE > '".$server->fields["max_glpi_date"]."') "
		." AND TIMESTAMP(LASTDATE) < (NOW()-180)"
		." AND ID<=".intval($server->fields["max_ocs_id"])." $where_multi_thread $where_limit";

	if (!empty ($cfg_ocs["tag_limit"])) {
		$splitter = explode("$", $cfg_ocs["tag_limit"]);
		if (count($splitter)) {
			$query_ocs .= " AND accountinfo.TAG IN ('" . $splitter[0] . "'";
			for ($i = 1; $i < count($splitter); $i++){
				$query_ocs .= ",'" .$splitter[$i] . "'";
			}
			$query_ocs .=")";
		}
	}
	//echo "Sql:$query_ocs\n";
	$result_ocs = $DBocs->query($query_ocs);
	$nb=$DBocs->numrows($result_ocs);
	echo "thread #$thread_id : $nb computer(s)\n";
	
	$fields["total_number_machines"]+=$nb;
	
	for($i=0 ; $data=$DBocs->fetch_array($result_ocs) ; $i++){
		if ( $i == $config->fields["thread_log_frequency"])
		{
			$fields["status"]=STATE_RUNNING;
			$fields["imported_machines_number"]=$imported_nbr;
			$fields["synchronized_machines_number"]=$synchronized_nbr;	
			$fields["failed_rules_machines_number"]=$failed_nbr;	
			$fields["linked_machines_number"]=$linked_nbr;
			
			if ($config->fields["enable_logging"])
			{
				$threadrecord = new ThreadRecord;
				$threadrecord->update($fields);
			}
			$i=0;
		}
		
		echo ".";
		switch (ocsProcessComputer($data["ID"],$cfg_ocs["ID"],1,-1,1))
		{
			case 0 :
				$synchronized_nbr++;
				break;
			case 1:
				$imported_nbr++;
				break;
			case 2:
				logNotImported($cfg_ocs["ID"],$data["ID"]);
				$failed_nbr++;
				break;
			case 3:
				$linked_nbr++;
				break;		
		}			
	}
	return array("synchronized"=>$synchronized_nbr,"imported"=>$imported_nbr,"failed"=>$failed_nbr,"linked"=>$linked_nbr);
}
?>