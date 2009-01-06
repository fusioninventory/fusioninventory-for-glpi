<?php
/*
 * @version $Id: tracker_fullsync.php 4980 2007-05-15 13:32:29Z walid $
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
// Purpose of file: Run fullsync as planified task
// ----------------------------------------------------------------------

function usage() {
	echo "Usage:\n";
	echo "\t" . $_SERVER["argv"][0]. " [--args]\n";
	echo "\n\tArguments:\n";
	echo "\t\t--nolog: output to console\n";
	echo "\t\t--networking_type: get SNMP only for networking devices (switchs)\n";
	echo "\t\t--printer_type: get SNMP only for printer devices\n";
	echo "\t\t--discovery: discover networking devices with IP range\n";
	echo "\t\t--discovery_serial: get serial number from discovery devices by SNMP\n";	
}

function readargs () {
	global $server_id, $thread_nbr, $log;
	
	for ($i=1 ; $i<$_SERVER["argc"] ; $i++) {
		$it = split("=",$_SERVER["argv"][$i]);
		
		switch ($it[0]) {
			case '--nolog':
				fclose($log);
				$log=STDOUT;
				break;
			case '--networking_type':
				break;
			case '--printer_type':
				break;
			case '--discovery':
				break;
			case '--discovery_serial':
				break;
			default: 
				usage();
				exit(1);
		}
	}	
}
function exit_if_soft_lock() {
	if (file_exists(TRACKER_IMPORT_LOCKFILE)) {
		echo "Software lock : script can't run !\n";
    	exit (1);
	}
}
function exit_if_already_running($pidfile)
{
  	# No pidfile, probably no daemon present
  	#
  	if (!file_exists($pidfile)) {
  		//echo "No $pidfile\n";
  		return 1;
  	}
  	$pid=intval(file_get_contents($pidfile));

  	# No pid, probably no daemon present
  	#
  	if (!$pid) {
  		//echo "Empty $pidfile\n";
  		return 1;  	
  	}
  	if (@pcntl_getpriority($pid)===false) {
  		//echo "Dead $pid\n";
  		return 1;  	
  	}
  	exit (1);
}
function cleanup ($pidfile) {
	@unlink($pidfile);
	
	$dir=opendir(GLPI_LOCK_DIR);
	if ($dir) while ($name=readdir($dir)) {
		if (strpos($name, "lock_entity")===0)
			unlink(GLPI_LOCK_DIR."/".$name);
	}
}

if (!isset($_SERVER["argv"][0])) {
	header("HTTP/1.0 403 Forbidden");
	die("403 Forbidden");
}
ini_set("memory_limit","-1");
ini_set("max_execution_time", "0");

chdir(dirname($_SERVER["argv"][0]));
define ("GLPI_ROOT", realpath(dirname($_SERVER["argv"][0])."/../../.."));
require GLPI_ROOT."/config/based_config.php";
define("TRACKER_IMPORT_LOCKFILE", GLPI_LOCK_DIR."/tracker_import.lock");

$process_id=date("zHi");
$server_id="";
$thread_nbr=2;

if (function_exists("sys_get_temp_dir")) {
	# PHP > 5.2.x
	$pidfile = sys_get_temp_dir()."/ocsng_fullsync.pid";	
}
else if (DIRECTORY_SEPARATOR=='/') {
	# Unix/Linux	
	$pidfile = "/tmp/ocsng_fullsync.pid";
}
else {
	# Windows	
	$pidfile = GLPI_LOG_DIR . "/ocsng_fullsync.pid";
}
$logfilename = GLPI_LOG_DIR."/ocsng_fullsync.log";

if (!is_writable(GLPI_LOCK_DIR)) {
	echo "\tERROR : " .GLPI_LOCK_DIR. " not writable\n";
	echo "\trun script as 'apache' user\n";
	exit (1);	
}
$log=fopen($logfilename, "at");
readargs();

exit_if_soft_lock();
exit_if_already_running($pidfile);
cleanup($pidfile);

//workaround to work with php4
if(!function_exists('file_put_contents')) {
	function file_put_contents($filename, $data, $file_append = false) {
	  $fp = fopen($filename, (!$file_append ? 'w+' : 'a+'));
	  if(!$fp) {
	   trigger_error('file_put_contents ne peut pas écrire dans le fichier.', E_USER_ERROR);
	   return;
	  }
	  fputs($fp, $data);
	  fclose($fp);
	}
}
else
	//Only available with PHP5 or later
	file_put_contents($pidfile, getmypid());

fwrite($log, date("r") . " " . $_SERVER["argv"][0] . " started\n");

$cmd="php -q -d -f tracker_fullsync.php --ocs_server_id=$server_id --managedeleted=1";
$out=array();
$ret=0;
exec($cmd, $out, $ret);
foreach ($out as $line) fwrite ($log, $line."\n");

if (function_exists("pcntl_fork")) {
	# Unix/Linux
	$pids=array();
	for ($i=0 ; $i<$thread_nbr ; ){
		$i++;
		$pid=pcntl_fork();
		if ($pid == -1)
			fwrite ($log, "Could not fork\n");
		else if ($pid) {
			fwrite ($log, "$pid Started\n");
			$pids[$pid]=1;
		}
		else  {
			$cmd="php -q -d -f tracker_fullsync.php --thread_id=$i --process_id=$process_id";
			$out=array();
			exec($cmd, $out, $ret);
			foreach ($out as $line) fwrite ($log, $line."\n");
			exit($ret);	
		}
	}
	$status=0;
	while (count($pids)) {
		$pid=pcntl_wait($status);
		if ($pid<0) {
			fwrite ($log, "Cound not wait\n");
			exit (1);
		} else {
			unset($pids[$pid]);
			fwrite ($log, "$pid ended, waiting for " . count($pids) . " running thread\n");
		}
	}
} else {
	# Windows - No fork, so Only one process :(
	$cmd="php -q -d -f tracker_fullsync.php --thread_id=$i --process_id=$process_id";
	$out=array();
	exec($cmd, $out, $ret);
	foreach ($out as $line) fwrite ($log, $line."\n");
}

cleanup($pidfile);
fwrite ($log, date("r") . " " . $_SERVER["argv"][0] . " ended\n\n");
?>