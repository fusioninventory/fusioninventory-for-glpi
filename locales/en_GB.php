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
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

$title="FusionInventory SNMP";
$version="2.3.0-1";

$LANG['plugin_fusinvsnmp']["title"][0]="$title";
$LANG['plugin_fusinvsnmp']["title"][1]="SNMP information";
$LANG['plugin_fusinvsnmp']["title"][2]="connections history";
$LANG['plugin_fusinvsnmp']["title"][3]="[Trk] Errors";
$LANG['plugin_fusinvsnmp']["title"][4]="[Trk] Cron";
$LANG['plugin_fusinvsnmp']["title"][5]="FusionInventory's locks";

$LANG['plugin_fusinvsnmp']['config'][0] = "Inventory frequency (in hours)";
$LANG['plugin_fusinvsnmp']['config'][1] = "Modules";
$LANG['plugin_fusinvsnmp']['config'][2] = "Snmp";
$LANG['plugin_fusinvsnmp']['config'][3] = "Inventory";
$LANG['plugin_fusinvsnmp']['config'][4] = "Devices discovery";
$LANG['plugin_fusinvsnmp']['config'][5] = "Manage agent directly from GLPI";
$LANG['plugin_fusinvsnmp']['config'][6] = "Wake On Lan";
$LANG['plugin_fusinvsnmp']['config'][7] = "SNMP query";

$LANG['plugin_fusinvsnmp']["profile"][0]="Rights management";
$LANG['plugin_fusinvsnmp']["profile"][1]="$title"; //interface

$LANG['plugin_fusinvsnmp']["profile"][10]="Profiles configured";
$LANG['plugin_fusinvsnmp']["profile"][11]="Computer history";
$LANG['plugin_fusinvsnmp']["profile"][12]="Printer history";
$LANG['plugin_fusinvsnmp']["profile"][13]="Printer information";
$LANG['plugin_fusinvsnmp']["profile"][14]="Network information";
$LANG['plugin_fusinvsnmp']["profile"][15]="Errors";

$LANG['plugin_fusinvsnmp']["profile"][16]="SNMP networking";
$LANG['plugin_fusinvsnmp']["profile"][17]="SNMP peripheral";
$LANG['plugin_fusinvsnmp']["profile"][18]="SNMP printers";
$LANG['plugin_fusinvsnmp']["profile"][19]="SNMP models";
$LANG['plugin_fusinvsnmp']["profile"][20]="SNMP authentication";
$LANG['plugin_fusinvsnmp']["profile"][21]="Script information";
$LANG['plugin_fusinvsnmp']["profile"][22]="Network discovery";
$LANG['plugin_fusinvsnmp']["profile"][23]="General configuration";
$LANG['plugin_fusinvsnmp']["profile"][24]="SNMP model";
$LANG['plugin_fusinvsnmp']["profile"][25]="IP range";
$LANG['plugin_fusinvsnmp']["profile"][26]="Agent";
$LANG['plugin_fusinvsnmp']["profile"][27]="Agents processes";
$LANG['plugin_fusinvsnmp']["profile"][28]="Report";
$LANG['plugin_fusinvsnmp']["profile"][29]="Remote control of agents";
$LANG['plugin_fusinvsnmp']["profile"][30]="Unknown devices";
$LANG['plugin_fusinvsnmp']["profile"][31]="device inventory FusionInventory";
$LANG['plugin_fusinvsnmp']["profile"][32]="SNMP query";
$LANG['plugin_fusinvsnmp']["profile"][33]="WakeOnLan";
$LANG['plugin_fusinvsnmp']["profile"][34]="Actions";

$LANG['plugin_fusinvsnmp']["setup"][2]="Thanks to put all in root entity (see all)";
$LANG['plugin_fusinvsnmp']["setup"][3]="Plugin configuration".$title;
$LANG['plugin_fusinvsnmp']["setup"][4]="Install plugin $title $version";
$LANG['plugin_fusinvsnmp']["setup"][5]="Update plugin $title to version $version";
$LANG['plugin_fusinvsnmp']["setup"][6]="Uninstall plugin $title $version";
$LANG['plugin_fusinvsnmp']["setup"][8]="Attention, uninstalling this plugin is an irreversible step.<br> You will loose all datas.";
$LANG['plugin_fusinvsnmp']["setup"][11]="Instructions";
$LANG['plugin_fusinvsnmp']["setup"][12]="FAQ";
$LANG['plugin_fusinvsnmp']["setup"][13]="Verification of PHP modules";
$LANG['plugin_fusinvsnmp']["setup"][14]="SNMP extension of PHP isn't load";
$LANG['plugin_fusinvsnmp']["setup"][15]="PHP/PECL runkit extension isn't load";
$LANG['plugin_fusinvsnmp']["setup"][16]="Documentation";
$LANG['plugin_fusinvsnmp']["setup"][17]="Plugin ".$title." need plugin FusionInventory activated before activation.";
$LANG['plugin_fusinvsnmp']["setup"][18]="Plugin ".$title." need plugin FusionInventory activated before uninstall.";

$LANG['plugin_fusinvsnmp']["functionalities"][0]="Features";
$LANG['plugin_fusinvsnmp']["functionalities"][1]="Add / Delete features";
$LANG['plugin_fusinvsnmp']["functionalities"][2]="General configuration";
$LANG['plugin_fusinvsnmp']["functionalities"][3]="SNMP";
$LANG['plugin_fusinvsnmp']["functionalities"][4]="Connection";
$LANG['plugin_fusinvsnmp']["functionalities"][5]="Server script";
$LANG['plugin_fusinvsnmp']["functionalities"][6]="Legend";
$LANG['plugin_fusinvsnmp']["functionalities"][7]="Lockable fields";

$LANG['plugin_fusinvsnmp']["functionalities"][9]="Retention in days";
$LANG['plugin_fusinvsnmp']["functionalities"][10]="History is_active";
$LANG['plugin_fusinvsnmp']["functionalities"][11]="Connection module is_active";
$LANG['plugin_fusinvsnmp']["functionalities"][12]="SNMP networking module is_active";
$LANG['plugin_fusinvsnmp']["functionalities"][13]="SNMP peripheral module is_active";
$LANG['plugin_fusinvsnmp']["functionalities"][14]="SNMP phones module is_active";
$LANG['plugin_fusinvsnmp']["functionalities"][15]="SNMP printers module is_active";
$LANG['plugin_fusinvsnmp']["functionalities"][16]="SNMP authentication";
$LANG['plugin_fusinvsnmp']["functionalities"][17]="Database";
$LANG['plugin_fusinvsnmp']["functionalities"][18]="Files";
$LANG['plugin_fusinvsnmp']["functionalities"][19]="Please configure the SNMP authentication in the setup of the plugin";
$LANG['plugin_fusinvsnmp']["functionalities"][20]="Status of active devices";
$LANG['plugin_fusinvsnmp']["functionalities"][21]="Retention of the historical interconnections between material in days (0 = infinity)";
$LANG['plugin_fusinvsnmp']["functionalities"][22]="Retention of the historic changes to the state of ports (0 = infinity)";
$LANG['plugin_fusinvsnmp']["functionalities"][23]="Retention of history unknown MAC addresses (0 = infinity)";
$LANG['plugin_fusinvsnmp']["functionalities"][24]="Retention of historical errors SNMP (0 = infinity))";
$LANG['plugin_fusinvsnmp']["functionalities"][25]="Retention of historical processes scripts (0 = infinity)";
$LANG['plugin_fusinvsnmp']["functionalities"][26]="GLPI URL for agent";
$LANG['plugin_fusinvsnmp']["functionalities"][27]="SSL only for agent";
$LANG['plugin_fusinvsnmp']["functionalities"][28]="History configuration";
$LANG['plugin_fusinvsnmp']["functionalities"][29]="List of fields to history";

$LANG['plugin_fusinvsnmp']["functionalities"][30]="Status of active material";
$LANG['plugin_fusinvsnmp']["functionalities"][31]="Management of cartridges and stock";
$LANG['plugin_fusinvsnmp']["functionalities"][32]="Delete agents informations processes after";
$LANG['plugin_fusinvsnmp']["functionalities"][36]="Frequency of meter reading";

$LANG['plugin_fusinvsnmp']["functionalities"][40]="Configuration";
$LANG['plugin_fusinvsnmp']["functionalities"][41]="Status of active material";
$LANG['plugin_fusinvsnmp']["functionalities"][42]="Switch";
$LANG['plugin_fusinvsnmp']["functionalities"][43]="SNMP authentication";

$LANG['plugin_fusinvsnmp']["functionalities"][50]="Number of simultaneous processes for the network discovery";
$LANG['plugin_fusinvsnmp']["functionalities"][51]="Number of simultaneous processes for SNMP queries";
$LANG['plugin_fusinvsnmp']["functionalities"][52]="Log files is_active";
$LANG['plugin_fusinvsnmp']["functionalities"][53]="Number of simultanous processes to be used by server script";

$LANG['plugin_fusinvsnmp']["functionalities"][60]="Clean history";

$LANG['plugin_fusinvsnmp']["functionalities"][70]="Lockable fields configuration";
$LANG['plugin_fusinvsnmp']["functionalities"][71]="Unlockable fields";
$LANG['plugin_fusinvsnmp']["functionalities"][72]="Table";
$LANG['plugin_fusinvsnmp']["functionalities"][73]="Fields";
$LANG['plugin_fusinvsnmp']["functionalities"][74]="Values";
$LANG['plugin_fusinvsnmp']["functionalities"][75]="Locks";

$LANG['plugin_fusinvsnmp']["snmp"][0]="SNMP information of equipment";
$LANG['plugin_fusinvsnmp']["snmp"][1]="General";
$LANG['plugin_fusinvsnmp']["snmp"][2]="Cabling";
$LANG['plugin_fusinvsnmp']["snmp"][3]="SNMP data";

$LANG['plugin_fusinvsnmp']["snmp"][11]="Additional information";
$LANG['plugin_fusinvsnmp']["snmp"][12]="Uptime";
$LANG['plugin_fusinvsnmp']["snmp"][13]="CPU usage (in %)";
$LANG['plugin_fusinvsnmp']["snmp"][14]="Memory usage (in %)";

$LANG['plugin_fusinvsnmp']["snmp"][31]="Unable to retrieve SNMP info: This is not a switch !";
$LANG['plugin_fusinvsnmp']["snmp"][32]="Unable to retrieve SNMP information: Hardware inactive !";
$LANG['plugin_fusinvsnmp']["snmp"][33]="Unable to retrieve SNMP information: IP not specified in the basic !";
$LANG['plugin_fusinvsnmp']["snmp"][34]="The switch is connected to a machine that is not filled !";

$LANG['plugin_fusinvsnmp']["snmp"][40]="Ports array";
$LANG['plugin_fusinvsnmp']["snmp"][41]="Port description";
$LANG['plugin_fusinvsnmp']["snmp"][42]="MTU";
$LANG['plugin_fusinvsnmp']["snmp"][43]="Speed";
$LANG['plugin_fusinvsnmp']["snmp"][44]="Internal status";
$LANG['plugin_fusinvsnmp']["snmp"][45]="Last Change";
$LANG['plugin_fusinvsnmp']["snmp"][46]="Number of bytes received";
$LANG['plugin_fusinvsnmp']["snmp"][47]="Number of input errors";
$LANG['plugin_fusinvsnmp']["snmp"][48]="Number of bytes sent";
$LANG['plugin_fusinvsnmp']["snmp"][49]="Number of errors in reception";
$LANG['plugin_fusinvsnmp']["snmp"][50]="Connection";
$LANG['plugin_fusinvsnmp']["snmp"][51]="Duplex";
$LANG['plugin_fusinvsnmp']["snmp"][52]="Date of last FusionInventory inventory";
$LANG['plugin_fusinvsnmp']["snmp"][53]="Last inventory";

$LANG['plugin_fusinvsnmp']["snmpauth"][1]="Community";
$LANG['plugin_fusinvsnmp']["snmpauth"][2]="User";
$LANG['plugin_fusinvsnmp']["snmpauth"][3]="Authentication scheme";
$LANG['plugin_fusinvsnmp']["snmpauth"][4]="Encryption protocol for authentication ";
$LANG['plugin_fusinvsnmp']["snmpauth"][5]="Password";
$LANG['plugin_fusinvsnmp']["snmpauth"][6]="Encryption protocol for data (write)";
$LANG['plugin_fusinvsnmp']["snmpauth"][7]="Password (write)";

$LANG['plugin_fusinvsnmp']["cron"][0]="Automatic reading meter";
$LANG['plugin_fusinvsnmp']["cron"][1]="Activate the record";
$LANG['plugin_fusinvsnmp']["cron"][2]="";
$LANG['plugin_fusinvsnmp']["cron"][3]="Default";

$LANG['plugin_fusinvsnmp']["errors"][0]="Errors";
$LANG['plugin_fusinvsnmp']["errors"][1]="IP";
$LANG['plugin_fusinvsnmp']["errors"][2]="Description";
$LANG['plugin_fusinvsnmp']["errors"][3]="Date first problem";
$LANG['plugin_fusinvsnmp']["errors"][4]="Date last problem";

$LANG['plugin_fusinvsnmp']["errors"][10]="Inconsistent with the basic GLPI";
$LANG['plugin_fusinvsnmp']["errors"][11]="Position unknown";
$LANG['plugin_fusinvsnmp']["errors"][12]="Unknown IP";

$LANG['plugin_fusinvsnmp']["errors"][20]="SNMP errors";
$LANG['plugin_fusinvsnmp']["errors"][21]="Unable to retrieve information";
$LANG['plugin_fusinvsnmp']["errors"][22]="Unattended element in";
$LANG['plugin_fusinvsnmp']["errors"][23]="Unable to identify device";

$LANG['plugin_fusinvsnmp']["errors"][30]="Wiring error";
$LANG['plugin_fusinvsnmp']["errors"][31]="Wiring problem";

$LANG['plugin_fusinvsnmp']["errors"][50]="GLPI version not compatible need 0.78";

$LANG['plugin_fusinvsnmp']["errors"][101]="Timeout";
$LANG['plugin_fusinvsnmp']["errors"][102]="No SNMP model assigned";
$LANG['plugin_fusinvsnmp']["errors"][103]="No SNMP authentication assigned";
$LANG['plugin_fusinvsnmp']["errors"][104]="Error message";

$LANG['plugin_fusinvsnmp']["history"][0] = "Old";
$LANG['plugin_fusinvsnmp']["history"][1] = "New";
$LANG['plugin_fusinvsnmp']["history"][2] = "Disconnect";
$LANG['plugin_fusinvsnmp']["history"][3] = "Connection";

$LANG['plugin_fusinvsnmp']["prt_history"][0]="History and Statistics of printer counters";

$LANG['plugin_fusinvsnmp']["prt_history"][10]="Printer counter statistics";
$LANG['plugin_fusinvsnmp']["prt_history"][11]="day(s)";
$LANG['plugin_fusinvsnmp']["prt_history"][12]="Total printed pages";
$LANG['plugin_fusinvsnmp']["prt_history"][13]="Pages / day";

$LANG['plugin_fusinvsnmp']["prt_history"][20]="History meter printer";
$LANG['plugin_fusinvsnmp']["prt_history"][21]="Date";
$LANG['plugin_fusinvsnmp']["prt_history"][22]="Meter";

$LANG['plugin_fusinvsnmp']["prt_history"][30]="Display";
$LANG['plugin_fusinvsnmp']["prt_history"][31]="Time unit";
$LANG['plugin_fusinvsnmp']["prt_history"][32]="Add a printer";
$LANG['plugin_fusinvsnmp']["prt_history"][33]="Remove a printer";
$LANG['plugin_fusinvsnmp']["prt_history"][34]="day";
$LANG['plugin_fusinvsnmp']["prt_history"][35]="week";
$LANG['plugin_fusinvsnmp']["prt_history"][36]="month";
$LANG['plugin_fusinvsnmp']["prt_history"][37]="year";

$LANG['plugin_fusinvsnmp']["cpt_history"][0]="History sessions";
$LANG['plugin_fusinvsnmp']["cpt_history"][1]="Contact";
$LANG['plugin_fusinvsnmp']["cpt_history"][2]="Computer";
$LANG['plugin_fusinvsnmp']["cpt_history"][3]="User";
$LANG['plugin_fusinvsnmp']["cpt_history"][4]="State";
$LANG['plugin_fusinvsnmp']["cpt_history"][5]="Date";

$LANG['plugin_fusinvsnmp']["type"][1]="Computer";
$LANG['plugin_fusinvsnmp']["type"][2]="Switch";
$LANG['plugin_fusinvsnmp']["type"][3]="Printer";

$LANG['plugin_fusinvsnmp']["rules"][1]="Rules";

$LANG['plugin_fusinvsnmp']['rule'][0]="Networking equipment existent criterii rules";
$LANG['plugin_fusinvsnmp']['rule'][1]="Existant criterii";
$LANG['plugin_fusinvsnmp']['rule'][2]="Serial Number";
$LANG['plugin_fusinvsnmp']['rule'][3]="MAC address";
$LANG['plugin_fusinvsnmp']['rule'][5]="Networking equipment model";
$LANG['plugin_fusinvsnmp']['rule'][6]="Networking equipment name";

$LANG['plugin_fusinvsnmp']['rule'][30]="Import in asset";
$LANG['plugin_fusinvsnmp']['rule'][31]="Import in unknown devices";

$LANG['plugin_fusinvsnmp']["massiveaction"][1]="Assign SNMP model";
$LANG['plugin_fusinvsnmp']["massiveaction"][2]="Assign SNMP authentication";

$LANG['plugin_fusinvsnmp']["model_info"][1]="SNMP information";
$LANG['plugin_fusinvsnmp']["model_info"][2]="SNMP version";
$LANG['plugin_fusinvsnmp']["model_info"][3]="SNMP authentication";
$LANG['plugin_fusinvsnmp']["model_info"][4]="SNMP models";
$LANG['plugin_fusinvsnmp']["model_info"][5]="MIB management";
$LANG['plugin_fusinvsnmp']["model_info"][6]="Edit SNMP model";
$LANG['plugin_fusinvsnmp']["model_info"][7]="Create SNMP model";
$LANG['plugin_fusinvsnmp']["model_info"][8]="Model already exists: import was not done";
$LANG['plugin_fusinvsnmp']["model_info"][9]="Import completed successfully";
$LANG['plugin_fusinvsnmp']["model_info"][10]="SNMP model import";
$LANG['plugin_fusinvsnmp']["model_info"][11]="is_active";
$LANG['plugin_fusinvsnmp']["model_info"][12]="Key for model discovery";
$LANG['plugin_fusinvsnmp']["model_info"][13]="Load the correct model";
$LANG['plugin_fusinvsnmp']["model_info"][14]="Load the correct SNMP model";
$LANG['plugin_fusinvsnmp']["model_info"][15]="Mass import of models";
$LANG['plugin_fusinvsnmp']["model_info"][16]="Mass import of models in folder plugins/fusioninventory/models/";

$LANG['plugin_fusinvsnmp']["mib"][1]="MIB Label";
$LANG['plugin_fusinvsnmp']["mib"][2]="Object";
$LANG['plugin_fusinvsnmp']["mib"][3]="oid";
$LANG['plugin_fusinvsnmp']["mib"][4]="add an oid...";
$LANG['plugin_fusinvsnmp']["mib"][5]="oid list";
$LANG['plugin_fusinvsnmp']["mib"][6]="Port Counters";
$LANG['plugin_fusinvsnmp']["mib"][7]="Dynamic port (.x)";
$LANG['plugin_fusinvsnmp']["mib"][8]="Linked fields";
$LANG['plugin_fusinvsnmp']["mib"][9]="Vlan";

$LANG['plugin_fusinvsnmp']["processes"][0]="History of script executions";
$LANG['plugin_fusinvsnmp']["processes"][1]="PID";
$LANG['plugin_fusinvsnmp']["processes"][2]="Status";
$LANG['plugin_fusinvsnmp']["processes"][3]="Number of processes";
$LANG['plugin_fusinvsnmp']["processes"][4]="Start date of execution";
$LANG['plugin_fusinvsnmp']["processes"][5]="End date of execution";
$LANG['plugin_fusinvsnmp']["processes"][6]="Network equipment queried";
$LANG['plugin_fusinvsnmp']["processes"][7]="Printers queried";
$LANG['plugin_fusinvsnmp']["processes"][8]="Ports queried";
$LANG['plugin_fusinvsnmp']["processes"][9]="Errors";
$LANG['plugin_fusinvsnmp']["processes"][10]="Time Script";
$LANG['plugin_fusinvsnmp']["processes"][11]="added fields";
$LANG['plugin_fusinvsnmp']["processes"][12]="SNMP errors";
$LANG['plugin_fusinvsnmp']["processes"][13]="Unknown MAC";
$LANG['plugin_fusinvsnmp']["processes"][14]="List of unknown MAC addresses";
$LANG['plugin_fusinvsnmp']["processes"][15]="First PID";
$LANG['plugin_fusinvsnmp']["processes"][16]="Last PID";
$LANG['plugin_fusinvsnmp']["processes"][17]="Date of first detection";
$LANG['plugin_fusinvsnmp']["processes"][18]="Date of last detection";
$LANG['plugin_fusinvsnmp']["processes"][19]="History of agent executions";
$LANG['plugin_fusinvsnmp']["processes"][20]="Reports and Statistics";
$LANG['plugin_fusinvsnmp']["processes"][21]="Queried devices";
$LANG['plugin_fusinvsnmp']["processes"][22]="Errors";
$LANG['plugin_fusinvsnmp']["processes"][23]="Total duration of discovery";
$LANG['plugin_fusinvsnmp']["processes"][24]="Total duration of query";
$LANG['plugin_fusinvsnmp']["processes"][25]="Agent";
$LANG['plugin_fusinvsnmp']["processes"][26]="Discover";
$LANG['plugin_fusinvsnmp']["processes"][27]="Query";
$LANG['plugin_fusinvsnmp']["processes"][28]="Core";
$LANG['plugin_fusinvsnmp']["processes"][29]="Threads";
$LANG['plugin_fusinvsnmp']["processes"][30]="Discovered";
$LANG['plugin_fusinvsnmp']["processes"][31]="Existent";
$LANG['plugin_fusinvsnmp']["processes"][32]="Imported";
$LANG['plugin_fusinvsnmp']["processes"][33]="Queried";
$LANG['plugin_fusinvsnmp']["processes"][34]="In error";
$LANG['plugin_fusinvsnmp']["processes"][35]="Created connections";
$LANG['plugin_fusinvsnmp']["processes"][36]="Deleted connections";
$LANG['plugin_fusinvsnmp']["processes"][37]="IP total";

$LANG['plugin_fusinvsnmp']["state"][0]="Computer start";
$LANG['plugin_fusinvsnmp']["state"][1]="Computer stop";
$LANG['plugin_fusinvsnmp']["state"][2]="User connection";
$LANG['plugin_fusinvsnmp']["state"][3]="User disconnection";

$LANG['plugin_fusinvsnmp']["mapping"][1]="networking > location";
$LANG['plugin_fusinvsnmp']["mapping"][2]="networking > firmware";
$LANG['plugin_fusinvsnmp']["mapping"][3]="networking > uptime";
$LANG['plugin_fusinvsnmp']["mapping"][4]="networking > port > mtu";
$LANG['plugin_fusinvsnmp']["mapping"][5]="networking > port > speed";
$LANG['plugin_fusinvsnmp']["mapping"][6]="networking > port > internal status";
$LANG['plugin_fusinvsnmp']["mapping"][7]="networking > ports > Last Change";
$LANG['plugin_fusinvsnmp']["mapping"][8]="networking > port > number of bytes entered";
$LANG['plugin_fusinvsnmp']["mapping"][9]="networking > port > number of bytes out";
$LANG['plugin_fusinvsnmp']["mapping"][10]="networking > port > number of input errors";
$LANG['plugin_fusinvsnmp']["mapping"][11]="networking > port > number of errors output";
$LANG['plugin_fusinvsnmp']["mapping"][12]="networking > CPU usage";
$LANG['plugin_fusinvsnmp']["mapping"][13]="networking > serial number";
$LANG['plugin_fusinvsnmp']["mapping"][14]="networking > port > connection status";
$LANG['plugin_fusinvsnmp']["mapping"][15]="networking > port > MAC address";
$LANG['plugin_fusinvsnmp']["mapping"][16]="networking > port > name";
$LANG['plugin_fusinvsnmp']["mapping"][17]="networking > model";
$LANG['plugin_fusinvsnmp']["mapping"][18]="networking > ports > type";
$LANG['plugin_fusinvsnmp']["mapping"][19]="networking > VLAN";
$LANG['plugin_fusinvsnmp']["mapping"][20]="networking > name";
$LANG['plugin_fusinvsnmp']["mapping"][21]="networking > total memory";
$LANG['plugin_fusinvsnmp']["mapping"][22]="networking > free memory";
$LANG['plugin_fusinvsnmp']["mapping"][23]="networking > port > port description";
$LANG['plugin_fusinvsnmp']["mapping"][24]="printer > name";
$LANG['plugin_fusinvsnmp']["mapping"][25]="printer > model";
$LANG['plugin_fusinvsnmp']["mapping"][26]="printer > total memory";
$LANG['plugin_fusinvsnmp']["mapping"][27]="printer > serial number";
$LANG['plugin_fusinvsnmp']["mapping"][28]="printer > meter > total number of printed pages";
$LANG['plugin_fusinvsnmp']["mapping"][29]="printer > meter > number of printed black and white pages";
$LANG['plugin_fusinvsnmp']["mapping"][30]="printer > meter > number of printed color pages";
$LANG['plugin_fusinvsnmp']["mapping"][31]="printer > meter > number of printed monochrome pages";
$LANG['plugin_fusinvsnmp']["mapping"][32]="printer > meter > number of printed color pages";
$LANG['plugin_fusinvsnmp']["mapping"][33]="networking > port > duplex type";
$LANG['plugin_fusinvsnmp']["mapping"][34]="printer > consumables > black cartridge (%)";
$LANG['plugin_fusinvsnmp']["mapping"][35]="printer > consumables > photo black cartridge (%)";
$LANG['plugin_fusinvsnmp']["mapping"][36]="printer > consumables > cyan cartridge (%)";
$LANG['plugin_fusinvsnmp']["mapping"][37]="printer > consumables > yellow cartridge (%)";
$LANG['plugin_fusinvsnmp']["mapping"][38]="printer > consumables > magenta cartridge (%)";
$LANG['plugin_fusinvsnmp']["mapping"][39]="printer > consumables > light cyan cartridge (%)";
$LANG['plugin_fusinvsnmp']["mapping"][40]="printer > consumables > light magenta cartridge (%)";
$LANG['plugin_fusinvsnmp']["mapping"][41]="printer > consumables > photoconductor (%)";
$LANG['plugin_fusinvsnmp']["mapping"][42]="printer > consumables > black photoconductor (%)";
$LANG['plugin_fusinvsnmp']["mapping"][43]="printer > consumables > color photoconductor (%)";
$LANG['plugin_fusinvsnmp']["mapping"][44]="printer > consumables > cyan photoconductor (%)";
$LANG['plugin_fusinvsnmp']["mapping"][45]="printer > consumables > yellow photoconductor (%)";
$LANG['plugin_fusinvsnmp']["mapping"][46]="printer > consumables > magenta photoconductor (%)";
$LANG['plugin_fusinvsnmp']["mapping"][47]="printer > consumables > black transfer unit (%)";
$LANG['plugin_fusinvsnmp']["mapping"][48]="printer > consumables > cyan transfer unit (%)";
$LANG['plugin_fusinvsnmp']["mapping"][49]="printer > consumables > yellow transfer unit (%)";
$LANG['plugin_fusinvsnmp']["mapping"][50]="printer > consumables > magenta transfer unit (%)";
$LANG['plugin_fusinvsnmp']["mapping"][51]="printer > consumables > waste bin (%)";
$LANG['plugin_fusinvsnmp']["mapping"][52]="printer > consumables > four (%)";
$LANG['plugin_fusinvsnmp']["mapping"][53]="printer > consumables > cleaning module (%)";
$LANG['plugin_fusinvsnmp']["mapping"][54]="printer > meter > number of printed duplex pages";
$LANG['plugin_fusinvsnmp']["mapping"][55]="printer > meter > nomber of scanned pages";
$LANG['plugin_fusinvsnmp']["mapping"][56]="printer > location";
$LANG['plugin_fusinvsnmp']["mapping"][57]="printer > port > name";
$LANG['plugin_fusinvsnmp']["mapping"][58]="printer > port > MAC address";
$LANG['plugin_fusinvsnmp']["mapping"][59]="printer > consumables > black cartridge (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][60]="printer > consumables > black cartridge (remaining ink )";
$LANG['plugin_fusinvsnmp']["mapping"][61]="printer > consumables > cyan cartridge (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][62]="printer > consumables > cyan cartridge (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][63]="printer > consumables > yellow cartridge (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][64]="printer > consumables > yellow cartridge (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][65]="printer > consumables > magenta cartridge (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][66]="printer > consumables > magenta cartridge (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][67]="printer > consumables > light cyan cartridge (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][68]="printer > consumables > light cyan cartridge (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][69]="printer > consumables > light magenta cartridge (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][70]="printer > consumables > light magenta cartridge (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][71]="printer > consumables > photoconductor (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][72]="printer > consumables > photoconductor (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][73]="printer > consumables > black photoconductor (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][74]="printer > consumables > black photoconductor (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][75]="printer > consumables > color photoconductor (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][76]="printer > consumables > color photoconductor (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][77]="printer > consumables > cyan photoconductor (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][78]="printer > consumables > cyan photoconductor (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][79]="printer > consumables > yellow photoconductor (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][80]="printer > consumables > yellow photoconductor (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][81]="printer > consumables > magenta photoconductor (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][82]="printer > consumables > magenta photoconductor (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][83]="printer > consumables > black transfer unit (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][84]="printer > consumables > black transfer unit (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][85]="printer > consumables > cyan transfer unit (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][86]="printer > consumables > cyan transfer unit (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][87]="printer > consumables > yellow transfer unit (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][88]="printer > consumables > yellow transfer unit (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][89]="printer > consumables > magenta transfer unit (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][90]="printer > consumables > magenta transfer unit (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][91]="printer > consumables > waste bin (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][92]="printer > consumables > waste bin (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][93]="printer > consumables > four (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][94]="printer > consumables > four (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][95]="printer > consumables > cleaning module (max ink)";
$LANG['plugin_fusinvsnmp']["mapping"][96]="printer > consumables > cleaning module (remaining ink)";
$LANG['plugin_fusinvsnmp']["mapping"][97]="printer > port > type";
$LANG['plugin_fusinvsnmp']["mapping"][98]="printer > consumables > Maintenance kit (max)";
$LANG['plugin_fusinvsnmp']["mapping"][99]="printer > consumables > Maintenance kit (remaining)";
$LANG['plugin_fusinvsnmp']["mapping"][400]="printer > consumables > Maintenance kit (%)";
$LANG['plugin_fusinvsnmp']["mapping"][401]="networking > CPU user";
$LANG['plugin_fusinvsnmp']["mapping"][402]="networking > CPU system";
$LANG['plugin_fusinvsnmp']["mapping"][403]="networking > contact";
$LANG['plugin_fusinvsnmp']["mapping"][404]="networking > comments";
$LANG['plugin_fusinvsnmp']["mapping"][405]="printer > contact";
$LANG['plugin_fusinvsnmp']["mapping"][406]="printer > comments";
$LANG['plugin_fusinvsnmp']["mapping"][407]="printer > port > IP address";
$LANG['plugin_fusinvsnmp']["mapping"][408]="networking > port > numÃ©ro index";
$LANG['plugin_fusinvsnmp']["mapping"][409]="networking > Adress CDP";
$LANG['plugin_fusinvsnmp']["mapping"][410]="networking > Port CDP";
$LANG['plugin_fusinvsnmp']["mapping"][411]="networking > port > trunk/tagged";
$LANG['plugin_fusinvsnmp']["mapping"][412]="networking > MAC address filters (dot1dTpFdbAddress)";
$LANG['plugin_fusinvsnmp']["mapping"][413]="networking > Physical addresses in memory (ipNetToMediaPhysAddress)";
$LANG['plugin_fusinvsnmp']["mapping"][414]="networking > instances de ports (dot1dTpFdbPort)";
$LANG['plugin_fusinvsnmp']["mapping"][415]="networking > numÃ©ro de ports associÃ© id du port (dot1dBasePortIfIndex)";
$LANG['plugin_fusinvsnmp']["mapping"][416]="printer > port > index number";
$LANG['plugin_fusinvsnmp']["mapping"][417]="networking > MAC address";
$LANG['plugin_fusinvsnmp']["mapping"][418]="printer > Inventory number";
$LANG['plugin_fusinvsnmp']["mapping"][419]="networking > Inventory number";
$LANG['plugin_fusinvsnmp']["mapping"][420]="printer > manufacturer";
$LANG['plugin_fusinvsnmp']["mapping"][421]="networking > IP addresses";
$LANG['plugin_fusinvsnmp']["mapping"][422]="networking > portVlanIndex";
$LANG['plugin_fusinvsnmp']["mapping"][423]="printer > meter > total number of printed pages (print)";
$LANG['plugin_fusinvsnmp']["mapping"][424]="printer > meter > number of printed black and white pages (print)";
$LANG['plugin_fusinvsnmp']["mapping"][425]="printer > meter > number of printed color pages (print)";
$LANG['plugin_fusinvsnmp']["mapping"][426]="printer > meter > total number of printed pages (copy)";
$LANG['plugin_fusinvsnmp']["mapping"][427]="printer > meter > number of printed black and white pages (copy)";
$LANG['plugin_fusinvsnmp']["mapping"][428]="printer > meter > number of printed color pages (copy)";
$LANG['plugin_fusinvsnmp']["mapping"][429]="printer > meter > total number of printed pages (fax)";
$LANG['plugin_fusinvsnmp']["mapping"][430]="networking > port > vlan";


$LANG['plugin_fusinvsnmp']["mapping"][101]="";
$LANG['plugin_fusinvsnmp']["mapping"][102]="";
$LANG['plugin_fusinvsnmp']["mapping"][103]="";
$LANG['plugin_fusinvsnmp']["mapping"][104]="MTU";
$LANG['plugin_fusinvsnmp']["mapping"][105]="Speed";
$LANG['plugin_fusinvsnmp']["mapping"][106]="Internal status";
$LANG['plugin_fusinvsnmp']["mapping"][107]="Last Change";
$LANG['plugin_fusinvsnmp']["mapping"][108]="Number of received bytes";
$LANG['plugin_fusinvsnmp']["mapping"][109]="Number of outgoing bytes";
$LANG['plugin_fusinvsnmp']["mapping"][110]="Number of input errors";
$LANG['plugin_fusinvsnmp']["mapping"][111]="Number of output errors";
$LANG['plugin_fusinvsnmp']["mapping"][112]="CPU usage";
$LANG['plugin_fusinvsnmp']["mapping"][113]="";
$LANG['plugin_fusinvsnmp']["mapping"][114]="Connection";
$LANG['plugin_fusinvsnmp']["mapping"][115]="Internal MAC address";
$LANG['plugin_fusinvsnmp']["mapping"][116]="Name";
$LANG['plugin_fusinvsnmp']["mapping"][117]="Model";
$LANG['plugin_fusinvsnmp']["mapping"][118]="Type";
$LANG['plugin_fusinvsnmp']["mapping"][119]="VLAN";
$LANG['plugin_fusinvsnmp']["mapping"][128]="Total number of printed pages";
$LANG['plugin_fusinvsnmp']["mapping"][129]="Number of printed black and white pages";
$LANG['plugin_fusinvsnmp']["mapping"][130]="Number of printed color pages";
$LANG['plugin_fusinvsnmp']["mapping"][131]="Number of printed monochrome pages";
$LANG['plugin_fusinvsnmp']["mapping"][132]="Number of printed color pages";
$LANG['plugin_fusinvsnmp']["mapping"][134]="Black cartridge";
$LANG['plugin_fusinvsnmp']["mapping"][135]="Photo black cartridge";
$LANG['plugin_fusinvsnmp']["mapping"][136]="Cyan cartridge";
$LANG['plugin_fusinvsnmp']["mapping"][137]="Yellow cartridge";
$LANG['plugin_fusinvsnmp']["mapping"][138]="Magenta cartridge";
$LANG['plugin_fusinvsnmp']["mapping"][139]="Light cyan cartridge";
$LANG['plugin_fusinvsnmp']["mapping"][140]="Light magenta cartridge";
$LANG['plugin_fusinvsnmp']["mapping"][141]="Photoconductor";
$LANG['plugin_fusinvsnmp']["mapping"][142]="Black photoconductor";
$LANG['plugin_fusinvsnmp']["mapping"][143]="Color photoconductor";
$LANG['plugin_fusinvsnmp']["mapping"][144]="Cyan photoconductor";
$LANG['plugin_fusinvsnmp']["mapping"][145]="Yellow photoconductor";
$LANG['plugin_fusinvsnmp']["mapping"][146]="Magenta photoconductor";
$LANG['plugin_fusinvsnmp']["mapping"][147]="Black transfer unit";
$LANG['plugin_fusinvsnmp']["mapping"][148]="Cyan transfer unit";
$LANG['plugin_fusinvsnmp']["mapping"][149]="Yellow transfer unit";
$LANG['plugin_fusinvsnmp']["mapping"][150]="Magenta transfer unit";
$LANG['plugin_fusinvsnmp']["mapping"][151]="Waste bin";
$LANG['plugin_fusinvsnmp']["mapping"][152]="Four";
$LANG['plugin_fusinvsnmp']["mapping"][153]="Cleaning module";
$LANG['plugin_fusinvsnmp']["mapping"][154]="Number of pages printed duplex";
$LANG['plugin_fusinvsnmp']["mapping"][155]="Number of scanned pages";
$LANG['plugin_fusinvsnmp']["mapping"][156]="Maintenance kit";
$LANG['plugin_fusinvsnmp']["mapping"][157]="Black toner";
$LANG['plugin_fusinvsnmp']["mapping"][158]="Cyan toner";
$LANG['plugin_fusinvsnmp']["mapping"][159]="Magenta toner";
$LANG['plugin_fusinvsnmp']["mapping"][160]="Yellow toner";
$LANG['plugin_fusinvsnmp']["mapping"][161]="Black drum";
$LANG['plugin_fusinvsnmp']["mapping"][162]="Cyan drum";
$LANG['plugin_fusinvsnmp']["mapping"][163]="Magenta drum";
$LANG['plugin_fusinvsnmp']["mapping"][164]="Yellow drum";
$LANG['plugin_fusinvsnmp']["mapping"][165]="Many informations grouped";
$LANG['plugin_fusinvsnmp']["mapping"][166]="Black toner 2";
$LANG['plugin_fusinvsnmp']["mapping"][1423]="Total number of printed pages (print)";
$LANG['plugin_fusinvsnmp']["mapping"][1424]="Number of printed black and white pages (print)";
$LANG['plugin_fusinvsnmp']["mapping"][1425]="Number of printed color pages (print)";
$LANG['plugin_fusinvsnmp']["mapping"][1426]="Total number of printed pages (copy)";
$LANG['plugin_fusinvsnmp']["mapping"][1427]="Number of printed black and white pages (copy)";
$LANG['plugin_fusinvsnmp']["mapping"][1428]="Number of printed color pages (copy)";
$LANG['plugin_fusinvsnmp']["mapping"][1429]="Total number of printed pages (fax)";


$LANG['plugin_fusinvsnmp']["printer"][0]="pages";

$LANG['plugin_fusinvsnmp']["menu"][0]="Information about discovered devices";
$LANG['plugin_fusinvsnmp']["menu"][1]="Agent configuration";
$LANG['plugin_fusinvsnmp']["menu"][2]="IP range configuration";
$LANG['plugin_fusinvsnmp']["menu"][3]="Menu";
$LANG['plugin_fusinvsnmp']["menu"][4]="Unknown device";
$LANG['plugin_fusinvsnmp']["menu"][5]="Switchs ports history";
$LANG['plugin_fusinvsnmp']["menu"][6]="Unused switchs ports";

$LANG['plugin_fusinvsnmp']["buttons"][0]="Discover";

$LANG['plugin_fusinvsnmp']["discovery"][0]="IP range to scan";
$LANG['plugin_fusinvsnmp']["discovery"][1]="Discovered devices";
$LANG['plugin_fusinvsnmp']["discovery"][2]="is_active in the script automatically";
$LANG['plugin_fusinvsnmp']["discovery"][3]="Discover";
$LANG['plugin_fusinvsnmp']["discovery"][4]="Serial number";
$LANG['plugin_fusinvsnmp']["discovery"][5]="Number of imported devices";
$LANG['plugin_fusinvsnmp']["discovery"][6]="Primary criteria for existence";
$LANG['plugin_fusinvsnmp']["discovery"][7]="Secondary criteria for existence ";
$LANG['plugin_fusinvsnmp']["discovery"][8]="If a device returns empty fields on first ciriteria, second one will be used.";
$LANG['plugin_fusinvsnmp']["discovery"][9]="Number of devices not imported because type non defined";

$LANG['plugin_fusinvsnmp']["iprange"][0]="Start of IP range";
$LANG['plugin_fusinvsnmp']["iprange"][1]="End of IP range";
$LANG['plugin_fusinvsnmp']["iprange"][2]="IP Ranges";
$LANG['plugin_fusinvsnmp']["iprange"][3]="Query";
$LANG['plugin_fusinvsnmp']["iprange"][4]="Incorrect IP address";
$LANG['plugin_fusinvsnmp']["iprange"][5]="Edit IP range";
$LANG['plugin_fusinvsnmp']["iprange"][6]="Create range IP";
$LANG['plugin_fusinvsnmp']["iprange"][7]="Bad IP";

$LANG['plugin_fusinvsnmp']["agents"][0]="SNMP Agent";
$LANG['plugin_fusinvsnmp']["agents"][2]="Number of threads used by core for querying devices";
$LANG['plugin_fusinvsnmp']["agents"][3]="Number of threads used by core for network discovery";
$LANG['plugin_fusinvsnmp']["agents"][4]="Last scan";
$LANG['plugin_fusinvsnmp']["agents"][5]="Agent version";
$LANG['plugin_fusinvsnmp']["agents"][6]="Lock";
$LANG['plugin_fusinvsnmp']["agents"][7]="Export agent configuration";
$LANG['plugin_fusinvsnmp']["agents"][9]="Advanced options";
$LANG['plugin_fusinvsnmp']["agents"][12]="Discovery Agent";
$LANG['plugin_fusinvsnmp']["agents"][13]="Query Agent";
$LANG['plugin_fusinvsnmp']["agents"][14]="Agent actions";
$LANG['plugin_fusinvsnmp']["agents"][15]="Agent state";
$LANG['plugin_fusinvsnmp']["agents"][16]="Initialized";
$LANG['plugin_fusinvsnmp']["agents"][17]="Agent is running";
$LANG['plugin_fusinvsnmp']["agents"][18]="Inventory has been received";
$LANG['plugin_fusinvsnmp']["agents"][19]="Inventory has been sended to OCS server";
$LANG['plugin_fusinvsnmp']["agents"][20]="Synchronisation between OCS and GLPI is running";
$LANG['plugin_fusinvsnmp']["agents"][21]="Inventory terminated";
$LANG['plugin_fusinvsnmp']["agents"][22]="Wait";
$LANG['plugin_fusinvsnmp']["agents"][23]="Computer link";
$LANG['plugin_fusinvsnmp']["agents"][24]="SNMP - Threads";
$LANG['plugin_fusinvsnmp']["agents"][25]="Agent(s)";

$LANG['plugin_fusinvsnmp']["task"][0]="Task";
$LANG['plugin_fusinvsnmp']["task"][1]="Task management";
$LANG['plugin_fusinvsnmp']["task"][2]="Action";
$LANG['plugin_fusinvsnmp']["task"][3]="Unit";
$LANG['plugin_fusinvsnmp']["task"][4]="Get now informations";
$LANG['plugin_fusinvsnmp']["task"][5]="Select OCS Agent";
$LANG['plugin_fusinvsnmp']["task"][6]="Get state";
$LANG['plugin_fusinvsnmp']["task"][7]="State";
$LANG['plugin_fusinvsnmp']["task"][8]="Ready";
$LANG['plugin_fusinvsnmp']["task"][9]="Not respond";
$LANG['plugin_fusinvsnmp']["task"][10]="Running... not available";
$LANG['plugin_fusinvsnmp']["task"][11]="Agent has been notified and begin running";
$LANG['plugin_fusinvsnmp']["task"][12]="Wake agent";
$LANG['plugin_fusinvsnmp']["task"][13]="Agent(s) unvailable";
$LANG['plugin_fusinvsnmp']["task"][14]="Planified on";
$LANG['plugin_fusinvsnmp']["task"][15]="Permanent task - Discovery";
$LANG['plugin_fusinvsnmp']["task"][16]="Permanent task - Inventory";

$LANG['plugin_fusinvsnmp']["constructdevice"][0]="Gestion des mib de matériel";
$LANG['plugin_fusinvsnmp']["constructdevice"][1]="Automatic creation of models";
$LANG['plugin_fusinvsnmp']["constructdevice"][2]="Generate discovery file";
$LANG['plugin_fusinvsnmp']["constructdevice"][3]="Delete models non used";
$LANG['plugin_fusinvsnmp']["constructdevice"][4]="Export all models";
$LANG['plugin_fusinvsnmp']["constructdevice"][5]="Re-create models comments";

$LANG['plugin_fusinvsnmp']["update"][0]="your history table have more than 300 000 entries, you must run this command to finish update : ";

$LANG['plugin_fusinvsnmp']["stats"][0]="Total counter";
$LANG['plugin_fusinvsnmp']["stats"][1]="pages per day";
$LANG['plugin_fusinvsnmp']["stats"][2]="Display";

?>