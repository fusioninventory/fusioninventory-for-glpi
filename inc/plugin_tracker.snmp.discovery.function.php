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

if (!defined('GLPI_ROOT'))
	die("Sorry. You can't access directly to this file");


function plugin_tracker_discovery_scan($Array_IP,$PID)
{

	global $DB,$TRACKER_MAPPING_DISCOVERY;

	$Thread = new Threads;
	$plugin_tracker_snmp = new plugin_tracker_snmp;
	$plugin_tracker_snmp_auth = new plugin_tracker_snmp_auth;
	$conf = new plugin_tracker_config;

	// Clear DB table
	$query = "TRUNCATE TABLE `glpi_plugin_tracker_discover`";
	$DB->query($query);

	// Load snmp auth
	$snmp_auth = $plugin_tracker_snmp_auth->GetInfos("all","",0);

	$i = 0;
	$ip1 = $Array_IP["ip11"];
	$ip2 = $Array_IP["ip12"];
	$ip3 = $Array_IP["ip13"];
	$ip4 = $Array_IP["ip14"];
	$s = 0;
	$nb_process_discovery = $conf->getValue('nb_process_discovery');
	// Prepare processes
	$while = 'while (';
	for ($i = 1;$i <= $nb_process_discovery;$i++)
	{
		if ($i == $nb_process_discovery)
			$while .= '$t['.$i.']->isActive()';
		else
			$while .= '$t['.$i.']->isActive() || ';
	}
	
	$while .= ') {';
	for ($i = 1;$i <= $nb_process_discovery;$i++)
	{
		$while .= 'echo $t['.$i.']->listen();';
	}
	$while .= '}';
	
	$close = '';
	for ($i = 1;$i <= $nb_process_discovery;$i++)
	{
		$close .= 'echo $t['.$i.']->close();';
	}	
	// End processes
	while ($i != 1)
	{
		$s++;
		$t[$s] = $Thread->create("tracker_fullsync.php --discovery_process=1 --ip1=".$ip1." --ip2=".$ip2." --ip3=".$ip3." --ip4=".$ip4);
		$lance_eval = 0;
		if ($nb_process_discovery == $s)
		{
			eval($while);
			eval($close);
			$Thread->updateProcess($PID,0, 0, $s, 0);
			$s = 0;
			$lance_eval = 1;
		}

		// Increment for next IP
		if (($ip1 == $Array_IP["ip21"]) 
			AND ($ip2 == $Array_IP["ip22"])
			AND ($ip3 == $Array_IP["ip23"]))
		{
			if ($ip4 == $Array_IP["ip24"])
				$i = 1;
		}
		else if (($ip1 == $Array_IP["ip21"]) 
			AND ($ip2 == $Array_IP["ip22"]))
		{
			if ($ip4 == "254")
			{
				$ip4 = -1;
				$ip3++;
			}
		}
		else if (($ip1 == $Array_IP["ip21"]))
		{
			if (($ip4 == "254") AND ($ip3 == "254"))
			{
				$ip4 = -1;
				$ip3 = 0;
				$ip2++;
			}
			else if ($ip4 == "254")
			{
				$ip4 = -1;
				$ip3++;
			}
		}
		else
		{
			if (($ip4 == "254") AND ($ip3 == "254") AND ($ip2 == "254"))
			{
				$ip4 = -1;
				$ip3 = 0;
				$ip2 = 0;
				$ip1++;
			}
			else if (($ip4 == "254") AND ($ip3 == "254"))
			{
				$ip4 = -1;
				$ip3 = 0;
				$ip2++;
			}
			else if ($ip4 == "254")
			{
				$ip4 = -1;
				$ip3++;
			}
				
		}
		$ip4++;
		// In case when the last process list are not complete, we run while
		if (($lance_eval == "0") AND ($i == "1"))
		{
			$nb = $s;
			$s++;
			for ($s;$s <= $nb_process_discovery ;$s++)
			{
				$while = str_replace("|| \$t[".$s."]->isActive()", "", $while);
				$while = str_replace("echo \$t[".$s."]->listen();", "", $while);
				$close = str_replace("echo \$t[".$s."]->close();", "", $close);
			
			}
			eval($while);
			eval($close);
			// Update processes
			$Thread->updateProcess($PID,0, 0, $nb, 0);
			$s = 0;
		}
	}
	$query = "UPDATE glpi_plugin_tracker_discover_conf
	SET discover='0'
	WHERE ID='1' ";
	$DB->query($query);
	return $nb_process_discovery;
}



function plugin_tracker_discovery_update_conf($data)
{
	global $DB;
	
	$discover = 0;
	$getserialnumber = 0;
	if ($_POST['activation'] == "discover")
		$discover = 1;
	if ($_POST['activation'] == "getserialnumber")
		$getserialnumber = 1;

	$query = "UPDATE glpi_plugin_tracker_discover_conf
	SET ifaddr_start='".$_POST['ip11'].".".$_POST['ip12'].".".$_POST['ip13'].".".$_POST['ip14']."',
	ifaddr_end='".$_POST['ip21'].".".$_POST['ip22'].".".$_POST['ip23'].".".$_POST['ip24']."',
	discover='".$discover."',getserialnumber='".$getserialnumber."'
	WHERE ID='1' ";

	$DB->query($query);	
}

	

function plugin_tracker_discovery_update_devices($array, $target)
{
	global $DB;

	foreach ($array as $key=>$value)
	{
		if (ereg("model_infos", $key))
		{
			$explode = explode ("-", $key);
			$query = "UPDATE glpi_plugin_tracker_discover
			SET FK_model_infos='".$value."',type='".$array['type-'.$explode[1]]."'
			WHERE ID='".$explode[1]."' ";
			$DB->query($query);
		}
	}
}



function plugin_tracker_discovery_import($discovery_ID)
{
	global $DB,$CFG_GLPI,$LANG,$LANGTRACKER;
	
	$td = new plugin_tracker_discovery;
	
	$td->getFromDB($discovery_ID);
	
	$Import = 0;

	switch ($td->fields['type'])
	{
		case PRINTER_TYPE :
			$Printer = new Printer;
			$Netport = new Netport;
			$tracker_printers = new plugin_tracker_printers;

			$data["FK_entities"] = $td->fields["FK_entities"];
			$data["name"] = $td->fields["name"];
			$data["serial"] = $td->fields["serialnumber"];
			$data["comments"] = $td->fields["descr"];
			$ID_Device = $Printer->add($data);

			$data_Port['on_device'] = $ID_Device;
			$data_Port['device_type'] = $td->fields['type'];
			$data_Port['ifaddr'] = $td->fields['ifaddr'];
			$Netport->add($data_Port);

			$data_tracker["FK_printers"] = $ID_Device;
			$data_tracker["FK_model_infos"] = $td->fields["FK_model_infos"];
			$data_tracker["FK_snmp_connection"] = $td->fields["FK_snmp_connection"];
			$tracker_printers->add($data_tracker);			
			
			$query_del = "DELETE FROM glpi_plugin_tracker_discover
			WHERE ID='".$discovery_ID."' ";
			$DB->query($query_del);
			$Import++;
			break;
		case NETWORKING_TYPE :
			$Netdevice = new Netdevice;
			$tracker_networking = new glpi_plugin_tracker_networking;

			$data["FK_entities"] = $td->fields["FK_entities"];
			$data["name"] = $td->fields["name"];
			$data["serial"] = $td->fields["serialnumber"];
			$data["comments"] = $td->fields["descr"];
			$data["ifaddr"] = $td->fields["ifaddr"];
			$ID_Device = $Netdevice->add($data);

			$data_tracker["FK_networking"] = $ID_Device;
			$data_tracker["FK_model_infos"] = $td->fields["FK_model_infos"];
			$data_tracker["FK_snmp_connection"] = $td->fields["FK_snmp_connection"];
			$tracker_networking->add($data_tracker);

			$query_del = "DELETE FROM glpi_plugin_tracker_discovery
			WHERE ID='".$discovery_ID."' ";
			$DB->query($query_del);
			$Import++;
			break;
		case PERIPHERAL_TYPE :
			$Peripheral = new Peripheral;
			$Netport = new Netport;

			$data["FK_entities"] = $td->fields["FK_entities"];
			$data["name"] = $td->fields["name"];
			$data["serial"] = $td->fields["serialnumber"];
			$data["comments"] = $td->fields["descr"];
			$ID_Device = $Peripheral->add($data);

			$data_Port['on_device'] = $ID_Device;
			$data_Port['device_type'] = $td->fields['type'];
			$data_Port['ifaddr'] = $td->fields['ifaddr'];
			$Netport->add($data_Port);
			
			$query_del = "DELETE FROM glpi_plugin_tracker_discover
			WHERE ID='".$discovery_ID."' ";
			$DB->query($query_del);
			$Import++;
			break;
		case COMPUTER_TYPE :
			$Computer = new Computer;
			$Netport = new Netport;

			$data["FK_entities"] = $td->fields["FK_entities"];
			$data["name"] = $td->fields["name"];
			$data["serial"] = $td->fields["serialnumber"];
			$data["comments"] = $td->fields["descr"];
			$ID_Device = $Computer->add($data);

			$data_Port['on_device'] = $ID_Device;
			$data_Port['device_type'] = $td->fields['type'];
			$data_Port['ifaddr'] = $td->fields['ifaddr'];
			$Netport->add($data_Port);

			$query_del = "DELETE FROM glpi_plugin_tracker_discover
			WHERE ID='".$discovery_ID."' ";
			$DB->query($query_del);
			$Import++;
			break;
		case PHONE_TYPE :
			$Phone = new Phone;
			$Netport = new Netport;

			$data["FK_entities"] = $td->fields["FK_entities"];
			$data["name"] = $td->fields["name"];
			$data["serial"] = $td->fields["serialnumber"];
			$data["comments"] = $td->fields["descr"];
			$ID_Device = $Phone->add($data);

			$data_Port['on_device'] = $ID_Device;
			$data_Port['device_type'] = $td->fields['type'];
			$data_Port['ifaddr'] = $td->fields['ifaddr'];
			$Netport->add($data_Port);

			$query_del = "DELETE FROM glpi_plugin_tracker_discover
			WHERE ID='".$discovery_ID."' ";
			$DB->query($query_del);
			$Import++;
			break;
	}
	if ($Import != "0")
		addMessageAfterRedirect($LANGTRACKER["discovery"][5]." : ".$Import );
}




?>