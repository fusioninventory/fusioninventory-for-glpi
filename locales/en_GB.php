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

$title="FusionInventory";
$version="2.2.1";

$LANG['plugin_fusioninventory']["title"][0]="$title";
$LANG['plugin_fusioninventory']["title"][1]="SNMP information";
$LANG['plugin_fusioninventory']["title"][2]="connections history";
$LANG['plugin_fusioninventory']["title"][3]="[Trk] Errors";
$LANG['plugin_fusioninventory']["title"][4]="[Trk] Cron";
$LANG['plugin_fusioninventory']["title"][5]="FusionInventory's locks";

$LANG['plugin_fusioninventory']['config'][0] = "Inventory frequency (in hours)";
$LANG['plugin_fusioninventory']['config'][1] = "Modules";
$LANG['plugin_fusioninventory']['config'][2] = "Snmp";
$LANG['plugin_fusioninventory']['config'][3] = "Inventory";
$LANG['plugin_fusioninventory']['config'][4] = "Device discovery";
$LANG['plugin_fusioninventory']['config'][5] = "Manage agent directly from GLPI";
$LANG['plugin_fusioninventory']['config'][6] = "Wake On Lan";
$LANG['plugin_fusioninventory']['config'][7] = "SNMP query";

$LANG['plugin_fusioninventory']["profile"][0]="Rights management";
$LANG['plugin_fusioninventory']["profile"][1]="$title"; //interface

$LANG['plugin_fusioninventory']["profile"][10]="Profiles configured";
$LANG['plugin_fusioninventory']["profile"][11]="Computer history";
$LANG['plugin_fusioninventory']["profile"][12]="Printer history";
$LANG['plugin_fusioninventory']["profile"][13]="Printer information";
$LANG['plugin_fusioninventory']["profile"][14]="Network information";
$LANG['plugin_fusioninventory']["profile"][15]="Errors";

$LANG['plugin_fusioninventory']["profile"][16]="SNMP networking";
$LANG['plugin_fusioninventory']["profile"][17]="SNMP peripheral";
$LANG['plugin_fusioninventory']["profile"][18]="SNMP printers";
$LANG['plugin_fusioninventory']["profile"][19]="SNMP models";
$LANG['plugin_fusioninventory']["profile"][20]="SNMP authentication";
$LANG['plugin_fusioninventory']["profile"][21]="Script information";
$LANG['plugin_fusioninventory']["profile"][22]="Network discovery";
$LANG['plugin_fusioninventory']["profile"][23]="General configuration";
$LANG['plugin_fusioninventory']["profile"][24]="SNMP model";
$LANG['plugin_fusioninventory']["profile"][25]="IP range";
$LANG['plugin_fusioninventory']["profile"][26]="Agent";
$LANG['plugin_fusioninventory']["profile"][27]="Agents processes";
$LANG['plugin_fusioninventory']["profile"][28]="Report";
$LANG['plugin_fusioninventory']["profile"][29]="Remote control of agents";
$LANG['plugin_fusioninventory']["profile"][30]="Unknown devices";
$LANG['plugin_fusioninventory']["profile"][31]="device inventory FusionInventory";
$LANG['plugin_fusioninventory']["profile"][32]="SNMP query";
$LANG['plugin_fusioninventory']["profile"][33]="WakeOnLan";
$LANG['plugin_fusioninventory']["profile"][34]="Actions";

$LANG['plugin_fusioninventory']["setup"][2]="Thanks to put all in root entity (see all)";
$LANG['plugin_fusioninventory']["setup"][3]="Plugin configuration".$title;
$LANG['plugin_fusioninventory']["setup"][4]="Install plugin $title $version";
$LANG['plugin_fusioninventory']["setup"][5]="Update plugin $title to version $version";
$LANG['plugin_fusioninventory']["setup"][6]="Uninstall plugin $title $version";
$LANG['plugin_fusioninventory']["setup"][8]="Attention, uninstalling this plugin is an irreversible step.<br> You will lose data.";
$LANG['plugin_fusioninventory']["setup"][11]="Instructions";
$LANG['plugin_fusioninventory']["setup"][12]="FAQ";
$LANG['plugin_fusioninventory']["setup"][13]="Verification of PHP modules";
$LANG['plugin_fusioninventory']["setup"][14]="SNMP extension of PHP isn’t loaded";
$LANG['plugin_fusioninventory']["setup"][15]="PHP/PECL runkit extension isn’t loaded";
$LANG['plugin_fusioninventory']["setup"][16]="Documentation";

$LANG['plugin_fusioninventory']["functionalities"][0]="Features";
$LANG['plugin_fusioninventory']["functionalities"][1]="Add / Delete features";
$LANG['plugin_fusioninventory']["functionalities"][2]="General configuration";
$LANG['plugin_fusioninventory']["functionalities"][3]="SNMP";
$LANG['plugin_fusioninventory']["functionalities"][4]="Connection";
$LANG['plugin_fusioninventory']["functionalities"][5]="Server script";
$LANG['plugin_fusioninventory']["functionalities"][6]="Legend";
$LANG['plugin_fusioninventory']["functionalities"][7]="Lockable fields";
$LANG['plugin_fusioninventory']["functionalities"][8]="Never";
$LANG['plugin_fusioninventory']["functionalities"][9]="Retention in days";
$LANG['plugin_fusioninventory']["functionalities"][10]="History activation";
$LANG['plugin_fusioninventory']["functionalities"][11]="Connection module activation";
$LANG['plugin_fusioninventory']["functionalities"][12]="SNMP networking module activation";
$LANG['plugin_fusioninventory']["functionalities"][13]="SNMP peripheral module activation";
$LANG['plugin_fusioninventory']["functionalities"][14]="SNMP phones module activation";
$LANG['plugin_fusioninventory']["functionalities"][15]="SNMP printers module activation";
$LANG['plugin_fusioninventory']["functionalities"][16]="SNMP authentication";
$LANG['plugin_fusioninventory']["functionalities"][17]="Database";
$LANG['plugin_fusioninventory']["functionalities"][18]="Files";
$LANG['plugin_fusioninventory']["functionalities"][19]="Please configure the SNMP authentication in the setup of the plugin";
$LANG['plugin_fusioninventory']["functionalities"][20]="Status of active devices";
$LANG['plugin_fusioninventory']["functionalities"][21]="Retention of equipment interconnection history in days (0 = infinity)";
$LANG['plugin_fusioninventory']["functionalities"][22]="Retention of port state change history in days (0 = infinity)";
$LANG['plugin_fusioninventory']["functionalities"][23]="Retention of unknown MAC address history in days (0 = infinity)";
$LANG['plugin_fusioninventory']["functionalities"][24]="Retention of SNMP error history in days (0 = infinity))";
$LANG['plugin_fusioninventory']["functionalities"][25]="Retention of script process history in days (0 = infinity)";
$LANG['plugin_fusioninventory']["functionalities"][26]="GLPI URL for agent";
$LANG['plugin_fusioninventory']["functionalities"][27]="SSL only for agent";
$LANG['plugin_fusioninventory']["functionalities"][28]="History configuration";
$LANG['plugin_fusioninventory']["functionalities"][29]="List of fields for which to keep history";

$LANG['plugin_fusioninventory']["functionalities"][30]="Status of active equipment";
$LANG['plugin_fusioninventory']["functionalities"][31]="Management of cartridges and stock";
$LANG['plugin_fusioninventory']["functionalities"][32]="Delete agent processes information after";
$LANG['plugin_fusioninventory']["functionalities"][36]="Frequency of meter reading";

$LANG['plugin_fusioninventory']["functionalities"][40]="Configuration";
$LANG['plugin_fusioninventory']["functionalities"][41]="Status of active equipment";
$LANG['plugin_fusioninventory']["functionalities"][42]="Switch";
$LANG['plugin_fusioninventory']["functionalities"][43]="SNMP authentication";

$LANG['plugin_fusioninventory']["functionalities"][50]="Number of simultaneous processes for network discovery";
$LANG['plugin_fusioninventory']["functionalities"][51]="Number of simultaneous processes for SNMP queries";
$LANG['plugin_fusioninventory']["functionalities"][52]="Log files activation";
$LANG['plugin_fusioninventory']["functionalities"][53]="Number of simultanous processes to be used by server script";

$LANG['plugin_fusioninventory']["functionalities"][60]="Clean history";
$LANG['plugin_fusioninventory']["functionalities"][61]="Always";

$LANG['plugin_fusioninventory']["functionalities"][70]="Lockable fields configuration";
$LANG['plugin_fusioninventory']["functionalities"][71]="Unlockable fields";
$LANG['plugin_fusioninventory']["functionalities"][72]="Table";
$LANG['plugin_fusioninventory']["functionalities"][73]="Fields";
$LANG['plugin_fusioninventory']["functionalities"][74]="Values";
$LANG['plugin_fusioninventory']["functionalities"][75]="Locked";

$LANG['plugin_fusioninventory']["snmp"][0]="SNMP information of equipment";
$LANG['plugin_fusioninventory']["snmp"][1]="General";
$LANG['plugin_fusioninventory']["snmp"][2]="Cabling";
$LANG['plugin_fusioninventory']["snmp"][2]="SNMP data";

$LANG['plugin_fusioninventory']["snmp"][11]="Additional information";
$LANG['plugin_fusioninventory']["snmp"][12]="Uptime";
$LANG['plugin_fusioninventory']["snmp"][13]="CPU usage (in %)";
$LANG['plugin_fusioninventory']["snmp"][14]="Memory usage (in %)";

$LANG['plugin_fusioninventory']["snmp"][31]="Unable to retrieve SNMP info: This is not a switch!";
$LANG['plugin_fusioninventory']["snmp"][32]="Unable to retrieve SNMP information: Hardware inactive!";
$LANG['plugin_fusioninventory']["snmp"][33]="Unable to retrieve SNMP information: IP not specified in the basic settings!";
$LANG['plugin_fusioninventory']["snmp"][34]="The switch is connected to a machine that is not recorded!";

$LANG['plugin_fusioninventory']["snmp"][40]="Port array";
$LANG['plugin_fusioninventory']["snmp"][41]="Port description";
$LANG['plugin_fusioninventory']["snmp"][42]="MTU";
$LANG['plugin_fusioninventory']["snmp"][43]="Speed";
$LANG['plugin_fusioninventory']["snmp"][44]="Internal status";
$LANG['plugin_fusioninventory']["snmp"][45]="Last change";
$LANG['plugin_fusioninventory']["snmp"][46]="Number of bytes received";
$LANG['plugin_fusioninventory']["snmp"][47]="Number of input errors";
$LANG['plugin_fusioninventory']["snmp"][48]="Number of bytes sent";
$LANG['plugin_fusioninventory']["snmp"][49]="Number of output errors";
$LANG['plugin_fusioninventory']["snmp"][50]="Connection";
$LANG['plugin_fusioninventory']["snmp"][51]="Duplex";
$LANG['plugin_fusioninventory']["snmp"][52]="Date of last FusionInventory inventory";
$LANG['plugin_fusioninventory']["snmp"][53]="Last inventory";
$LANG['plugin_fusioninventory']["snmp"][54]="View complete history";

$LANG['plugin_fusioninventory']["snmpauth"][1]="Community";
$LANG['plugin_fusioninventory']["snmpauth"][2]="User";
$LANG['plugin_fusioninventory']["snmpauth"][3]="Authentication scheme";
$LANG['plugin_fusioninventory']["snmpauth"][4]="Encryption protocol for authentication ";
$LANG['plugin_fusioninventory']["snmpauth"][5]="Password";
$LANG['plugin_fusioninventory']["snmpauth"][6]="Encryption protocol for data (write)";
$LANG['plugin_fusioninventory']["snmpauth"][7]="Password (write)";

$LANG['plugin_fusioninventory']["cron"][0]="Automatic reading meter";
$LANG['plugin_fusioninventory']["cron"][1]="Activate the record";
$LANG['plugin_fusioninventory']["cron"][2]="";
$LANG['plugin_fusioninventory']["cron"][3]="Default";

$LANG['plugin_fusioninventory']["errors"][0]="Errors";
$LANG['plugin_fusioninventory']["errors"][1]="IP";
$LANG['plugin_fusioninventory']["errors"][2]="Description";
$LANG['plugin_fusioninventory']["errors"][3]="Date first problem";
$LANG['plugin_fusioninventory']["errors"][4]="Date last problem";

$LANG['plugin_fusioninventory']["errors"][10]="Inconsistent with the basic GLPI";
$LANG['plugin_fusioninventory']["errors"][11]="Position unknown";
$LANG['plugin_fusioninventory']["errors"][12]="Unknown IP";

$LANG['plugin_fusioninventory']["errors"][20]="SNMP errors";
$LANG['plugin_fusioninventory']["errors"][21]="Unable to retrieve information";
$LANG['plugin_fusioninventory']["errors"][22]="Unattended element in";
$LANG['plugin_fusioninventory']["errors"][23]="Unable to identify device";

$LANG['plugin_fusioninventory']["errors"][30]="Wiring error";
$LANG['plugin_fusioninventory']["errors"][31]="Wiring problem";

$LANG['plugin_fusioninventory']["errors"][50]="GLPI version not compatible: need 0.72.1";

$LANG['plugin_fusioninventory']["errors"][101]="Timeout";
$LANG['plugin_fusioninventory']["errors"][102]="No SNMP model assigned";
$LANG['plugin_fusioninventory']["errors"][103]="No SNMP authentication assigned";
$LANG['plugin_fusioninventory']["errors"][104]="Error message";

$LANG['plugin_fusioninventory']["history"][0] = "Old";
$LANG['plugin_fusioninventory']["history"][1] = "New";
$LANG['plugin_fusioninventory']["history"][2] = "Disconnect";
$LANG['plugin_fusioninventory']["history"][3] = "Connection";

$LANG['plugin_fusioninventory']["prt_history"][0]="History and Statistics of printer counters";

$LANG['plugin_fusioninventory']["prt_history"][10]="Printer counter statistics";
$LANG['plugin_fusioninventory']["prt_history"][11]="day(s)";
$LANG['plugin_fusioninventory']["prt_history"][12]="Total printed pages";
$LANG['plugin_fusioninventory']["prt_history"][13]="Pages / day";

$LANG['plugin_fusioninventory']["prt_history"][20]="Printer meter history";
$LANG['plugin_fusioninventory']["prt_history"][21]="Date";
$LANG['plugin_fusioninventory']["prt_history"][22]="Meter";

$LANG['plugin_fusioninventory']["prt_history"][30]="Display";
$LANG['plugin_fusioninventory']["prt_history"][31]="Time unit";
$LANG['plugin_fusioninventory']["prt_history"][32]="Add a printer";
$LANG['plugin_fusioninventory']["prt_history"][33]="Remove a printer";
$LANG['plugin_fusioninventory']["prt_history"][34]="day";
$LANG['plugin_fusioninventory']["prt_history"][35]="week";
$LANG['plugin_fusioninventory']["prt_history"][36]="month";
$LANG['plugin_fusioninventory']["prt_history"][37]="year";

$LANG['plugin_fusioninventory']["cpt_history"][0]="History sessions";
$LANG['plugin_fusioninventory']["cpt_history"][1]="Contact";
$LANG['plugin_fusioninventory']["cpt_history"][2]="Computer";
$LANG['plugin_fusioninventory']["cpt_history"][3]="User";
$LANG['plugin_fusioninventory']["cpt_history"][4]="State";
$LANG['plugin_fusioninventory']["cpt_history"][5]="Date";

$LANG['plugin_fusioninventory']["type"][1]="Computer";
$LANG['plugin_fusioninventory']["type"][2]="Switch";
$LANG['plugin_fusioninventory']["type"][3]="Printer";

$LANG['plugin_fusioninventory']["rules"][1]="Rules";

$LANG['plugin_fusioninventory']["massiveaction"][1]="Assign SNMP model";
$LANG['plugin_fusioninventory']["massiveaction"][2]="Assign SNMP authentication";

$LANG['plugin_fusioninventory']["model_info"][1]="SNMP information";
$LANG['plugin_fusioninventory']["model_info"][2]="SNMP version";
$LANG['plugin_fusioninventory']["model_info"][3]="SNMP authentication";
$LANG['plugin_fusioninventory']["model_info"][4]="SNMP models";
$LANG['plugin_fusioninventory']["model_info"][5]="MIB management";
$LANG['plugin_fusioninventory']["model_info"][6]="Edit SNMP model";
$LANG['plugin_fusioninventory']["model_info"][7]="Create SNMP model";
$LANG['plugin_fusioninventory']["model_info"][8]="Model already exists: import was not done";
$LANG['plugin_fusioninventory']["model_info"][9]="Import completed successfully";
$LANG['plugin_fusioninventory']["model_info"][10]="Import SNMP model";
$LANG['plugin_fusioninventory']["model_info"][11]="Activation";
$LANG['plugin_fusioninventory']["model_info"][12]="Key for model discovery";
$LANG['plugin_fusioninventory']["model_info"][13]="Load the correct model";
$LANG['plugin_fusioninventory']["model_info"][14]="Load the correct SNMP model";
$LANG['plugin_fusioninventory']["model_info"][15]="Mass import of models";
$LANG['plugin_fusioninventory']["model_info"][16]="Mass import of models in folder plugins/fusioninventory/models/";

$LANG['plugin_fusioninventory']["mib"][1]="MIB Label";
$LANG['plugin_fusioninventory']["mib"][2]="Object";
$LANG['plugin_fusioninventory']["mib"][3]="OID";
$LANG['plugin_fusioninventory']["mib"][4]="add an OID...";
$LANG['plugin_fusioninventory']["mib"][5]="OID list";
$LANG['plugin_fusioninventory']["mib"][6]="Port Counters";
$LANG['plugin_fusioninventory']["mib"][7]="Dynamic port (.x)";
$LANG['plugin_fusioninventory']["mib"][8]="Linked fields";
$LANG['plugin_fusioninventory']["mib"][9]="VLAN";

$LANG['plugin_fusioninventory']["processes"][0]="History of script executions";
$LANG['plugin_fusioninventory']["processes"][1]="PID";
$LANG['plugin_fusioninventory']["processes"][2]="Status";
$LANG['plugin_fusioninventory']["processes"][3]="Number of processes";
$LANG['plugin_fusioninventory']["processes"][4]="Start date of execution";
$LANG['plugin_fusioninventory']["processes"][5]="End date of execution";
$LANG['plugin_fusioninventory']["processes"][6]="Network equipment queried";
$LANG['plugin_fusioninventory']["processes"][7]="Printers queried";
$LANG['plugin_fusioninventory']["processes"][8]="Ports queried";
$LANG['plugin_fusioninventory']["processes"][9]="Errors";
$LANG['plugin_fusioninventory']["processes"][10]="Time Script";
$LANG['plugin_fusioninventory']["processes"][11]="added fields";
$LANG['plugin_fusioninventory']["processes"][12]="SNMP errors";
$LANG['plugin_fusioninventory']["processes"][13]="Unknown MAC";
$LANG['plugin_fusioninventory']["processes"][14]="List of unknown MAC addresses";
$LANG['plugin_fusioninventory']["processes"][15]="First PID";
$LANG['plugin_fusioninventory']["processes"][16]="Last PID";
$LANG['plugin_fusioninventory']["processes"][17]="Date of first detection";
$LANG['plugin_fusioninventory']["processes"][18]="Date of last detection";
$LANG['plugin_fusioninventory']["processes"][19]="History of agent executions";
$LANG['plugin_fusioninventory']["processes"][20]="Reports and Statistics";
$LANG['plugin_fusioninventory']["processes"][21]="Queried devices";
$LANG['plugin_fusioninventory']["processes"][22]="Errors";
$LANG['plugin_fusioninventory']["processes"][23]="Total duration of discovery";
$LANG['plugin_fusioninventory']["processes"][24]="Total duration of query";
$LANG['plugin_fusioninventory']["processes"][25]="Agent";
$LANG['plugin_fusioninventory']["processes"][26]="Discover";
$LANG['plugin_fusioninventory']["processes"][27]="Query";
$LANG['plugin_fusioninventory']["processes"][28]="Core";
$LANG['plugin_fusioninventory']["processes"][29]="Threads";
$LANG['plugin_fusioninventory']["processes"][30]="Discovered";
$LANG['plugin_fusioninventory']["processes"][31]="Existent";
$LANG['plugin_fusioninventory']["processes"][32]="Imported";
$LANG['plugin_fusioninventory']["processes"][33]="Queried";
$LANG['plugin_fusioninventory']["processes"][34]="In error";
$LANG['plugin_fusioninventory']["processes"][35]="Created connections";
$LANG['plugin_fusioninventory']["processes"][36]="Deleted connections";
$LANG['plugin_fusioninventory']["processes"][37]="IP total";

$LANG['plugin_fusioninventory']["state"][0]="Computer start";
$LANG['plugin_fusioninventory']["state"][1]="Computer stop";
$LANG['plugin_fusioninventory']["state"][2]="User connection";
$LANG['plugin_fusioninventory']["state"][3]="User disconnection";

$LANG['plugin_fusioninventory']["mapping"][1]="networking > location";
$LANG['plugin_fusioninventory']["mapping"][2]="networking > firmware";
$LANG['plugin_fusioninventory']["mapping"][3]="networking > uptime";
$LANG['plugin_fusioninventory']["mapping"][4]="networking > port > mtu";
$LANG['plugin_fusioninventory']["mapping"][5]="networking > port > speed";
$LANG['plugin_fusioninventory']["mapping"][6]="networking > port > internal status";
$LANG['plugin_fusioninventory']["mapping"][7]="networking > ports > Last change";
$LANG['plugin_fusioninventory']["mapping"][8]="networking > port > number of bytes in";
$LANG['plugin_fusioninventory']["mapping"][9]="networking > port > number of bytes out";
$LANG['plugin_fusioninventory']["mapping"][10]="networking > port > number of input errors";
$LANG['plugin_fusioninventory']["mapping"][11]="networking > port > number of output errors";
$LANG['plugin_fusioninventory']["mapping"][12]="networking > CPU usage";
$LANG['plugin_fusioninventory']["mapping"][13]="networking > serial number";
$LANG['plugin_fusioninventory']["mapping"][14]="networking > port > connection status";
$LANG['plugin_fusioninventory']["mapping"][15]="networking > port > MAC address";
$LANG['plugin_fusioninventory']["mapping"][16]="networking > port > name";
$LANG['plugin_fusioninventory']["mapping"][17]="networking > model";
$LANG['plugin_fusioninventory']["mapping"][18]="networking > ports > type";
$LANG['plugin_fusioninventory']["mapping"][19]="networking > VLAN";
$LANG['plugin_fusioninventory']["mapping"][20]="networking > name";
$LANG['plugin_fusioninventory']["mapping"][21]="networking > total memory";
$LANG['plugin_fusioninventory']["mapping"][22]="networking > free memory";
$LANG['plugin_fusioninventory']["mapping"][23]="networking > port > port description";
$LANG['plugin_fusioninventory']["mapping"][24]="printer > name";
$LANG['plugin_fusioninventory']["mapping"][25]="printer > model";
$LANG['plugin_fusioninventory']["mapping"][26]="printer > total memory";
$LANG['plugin_fusioninventory']["mapping"][27]="printer > serial number";
$LANG['plugin_fusioninventory']["mapping"][28]="printer > meter > total number of printed pages";
$LANG['plugin_fusioninventory']["mapping"][29]="printer > meter > number of printed black and white pages";
$LANG['plugin_fusioninventory']["mapping"][30]="printer > meter > number of printed color pages";
$LANG['plugin_fusioninventory']["mapping"][31]="printer > meter > number of printed monochrome pages";
$LANG['plugin_fusioninventory']["mapping"][32]="printer > meter > number of printed color pages";
$LANG['plugin_fusioninventory']["mapping"][33]="networking > port > duplex type";
$LANG['plugin_fusioninventory']["mapping"][34]="printer > consumables > black cartridge (%)";
$LANG['plugin_fusioninventory']["mapping"][35]="printer > consumables > photo black cartridge (%)";
$LANG['plugin_fusioninventory']["mapping"][36]="printer > consumables > cyan cartridge (%)";
$LANG['plugin_fusioninventory']["mapping"][37]="printer > consumables > yellow cartridge (%)";
$LANG['plugin_fusioninventory']["mapping"][38]="printer > consumables > magenta cartridge (%)";
$LANG['plugin_fusioninventory']["mapping"][39]="printer > consumables > light cyan cartridge (%)";
$LANG['plugin_fusioninventory']["mapping"][40]="printer > consumables > light magenta cartridge (%)";
$LANG['plugin_fusioninventory']["mapping"][41]="printer > consumables > photoconductor (%)";
$LANG['plugin_fusioninventory']["mapping"][42]="printer > consumables > black photoconductor (%)";
$LANG['plugin_fusioninventory']["mapping"][43]="printer > consumables > color photoconductor (%)";
$LANG['plugin_fusioninventory']["mapping"][44]="printer > consumables > cyan photoconductor (%)";
$LANG['plugin_fusioninventory']["mapping"][45]="printer > consumables > yellow photoconductor (%)";
$LANG['plugin_fusioninventory']["mapping"][46]="printer > consumables > magenta photoconductor (%)";
$LANG['plugin_fusioninventory']["mapping"][47]="printer > consumables > black transfer unit (%)";
$LANG['plugin_fusioninventory']["mapping"][48]="printer > consumables > cyan transfer unit (%)";
$LANG['plugin_fusioninventory']["mapping"][49]="printer > consumables > yellow transfer unit (%)";
$LANG['plugin_fusioninventory']["mapping"][50]="printer > consumables > magenta transfer unit (%)";
$LANG['plugin_fusioninventory']["mapping"][51]="printer > consumables > waste bin (%)";
$LANG['plugin_fusioninventory']["mapping"][52]="printer > consumables > four (%)";
$LANG['plugin_fusioninventory']["mapping"][53]="printer > consumables > cleaning module (%)";
$LANG['plugin_fusioninventory']["mapping"][54]="printer > meter > number of printed duplex pages";
$LANG['plugin_fusioninventory']["mapping"][55]="printer > meter > nomber of scanned pages";
$LANG['plugin_fusioninventory']["mapping"][56]="printer > location";
$LANG['plugin_fusioninventory']["mapping"][57]="printer > port > name";
$LANG['plugin_fusioninventory']["mapping"][58]="printer > port > MAC address";
$LANG['plugin_fusioninventory']["mapping"][59]="printer > consumables > black cartridge (max ink)";
$LANG['plugin_fusioninventory']["mapping"][60]="printer > consumables > black cartridge (remaining ink )";
$LANG['plugin_fusioninventory']["mapping"][61]="printer > consumables > cyan cartridge (max ink)";
$LANG['plugin_fusioninventory']["mapping"][62]="printer > consumables > cyan cartridge (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][63]="printer > consumables > yellow cartridge (max ink)";
$LANG['plugin_fusioninventory']["mapping"][64]="printer > consumables > yellow cartridge (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][65]="printer > consumables > magenta cartridge (max ink)";
$LANG['plugin_fusioninventory']["mapping"][66]="printer > consumables > magenta cartridge (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][67]="printer > consumables > light cyan cartridge (max ink)";
$LANG['plugin_fusioninventory']["mapping"][68]="printer > consumables > light cyan cartridge (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][69]="printer > consumables > light magenta cartridge (max ink)";
$LANG['plugin_fusioninventory']["mapping"][70]="printer > consumables > light magenta cartridge (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][71]="printer > consumables > photoconductor (max ink)";
$LANG['plugin_fusioninventory']["mapping"][72]="printer > consumables > photoconductor (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][73]="printer > consumables > black photoconductor (max ink)";
$LANG['plugin_fusioninventory']["mapping"][74]="printer > consumables > black photoconductor (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][75]="printer > consumables > color photoconductor (max ink)";
$LANG['plugin_fusioninventory']["mapping"][76]="printer > consumables > color photoconductor (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][77]="printer > consumables > cyan photoconductor (max ink)";
$LANG['plugin_fusioninventory']["mapping"][78]="printer > consumables > cyan photoconductor (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][79]="printer > consumables > yellow photoconductor (max ink)";
$LANG['plugin_fusioninventory']["mapping"][80]="printer > consumables > yellow photoconductor (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][81]="printer > consumables > magenta photoconductor (max ink)";
$LANG['plugin_fusioninventory']["mapping"][82]="printer > consumables > magenta photoconductor (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][83]="printer > consumables > black transfer unit (max ink)";
$LANG['plugin_fusioninventory']["mapping"][84]="printer > consumables > black transfer unit (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][85]="printer > consumables > cyan transfer unit (max ink)";
$LANG['plugin_fusioninventory']["mapping"][86]="printer > consumables > cyan transfer unit (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][87]="printer > consumables > yellow transfer unit (max ink)";
$LANG['plugin_fusioninventory']["mapping"][88]="printer > consumables > yellow transfer unit (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][89]="printer > consumables > magenta transfer unit (max ink)";
$LANG['plugin_fusioninventory']["mapping"][90]="printer > consumables > magenta transfer unit (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][91]="printer > consumables > waste bin (max ink)";
$LANG['plugin_fusioninventory']["mapping"][92]="printer > consumables > waste bin (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][93]="printer > consumables > four (max ink)";
$LANG['plugin_fusioninventory']["mapping"][94]="printer > consumables > four (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][95]="printer > consumables > cleaning module (max ink)";
$LANG['plugin_fusioninventory']["mapping"][96]="printer > consumables > cleaning module (remaining ink)";
$LANG['plugin_fusioninventory']["mapping"][97]="printer > port > type";
$LANG['plugin_fusioninventory']["mapping"][98]="printer > consumables > Maintenance kit (max)";
$LANG['plugin_fusioninventory']["mapping"][99]="printer > consumables > Maintenance kit (remaining)";
$LANG['plugin_fusioninventory']["mapping"][400]="printer > consumables > Maintenance kit (%)";
$LANG['plugin_fusioninventory']["mapping"][401]="networking > CPU usage (user)";
$LANG['plugin_fusioninventory']["mapping"][402]="networking > CPU usage (system)";
$LANG['plugin_fusioninventory']["mapping"][403]="networking > contact";
$LANG['plugin_fusioninventory']["mapping"][404]="networking > comments";
$LANG['plugin_fusioninventory']["mapping"][405]="printer > contact";
$LANG['plugin_fusioninventory']["mapping"][406]="printer > comments";
$LANG['plugin_fusioninventory']["mapping"][407]="printer > port > IP address";
$LANG['plugin_fusioninventory']["mapping"][408]="networking > port > index number";
$LANG['plugin_fusioninventory']["mapping"][409]="networking > CDP address";
$LANG['plugin_fusioninventory']["mapping"][410]="networking > CDP port";
$LANG['plugin_fusioninventory']["mapping"][411]="networking > port > trunk/tagged";
$LANG['plugin_fusioninventory']["mapping"][412]="networking > MAC address filters (dot1dTpFdbAddress)";
$LANG['plugin_fusioninventory']["mapping"][413]="networking > Physical addresses in memory (ipNetToMediaPhysAddress)";
$LANG['plugin_fusioninventory']["mapping"][414]="networking > Port instances (dot1dTpFdbPort)";
$LANG['plugin_fusioninventory']["mapping"][415]="networking > Port number associated with port ID (dot1dBasePortIfIndex)";
$LANG['plugin_fusioninventory']["mapping"][416]="printer > port > index number";
$LANG['plugin_fusioninventory']["mapping"][417]="networking > MAC address";
$LANG['plugin_fusioninventory']["mapping"][418]="printer > Inventory number";
$LANG['plugin_fusioninventory']["mapping"][419]="networking > Inventory number";
$LANG['plugin_fusioninventory']["mapping"][420]="printer > manufacturer";
$LANG['plugin_fusioninventory']["mapping"][421]="networking > IP addresses";
$LANG['plugin_fusioninventory']["mapping"][422]="networking > portVlanIndex";
$LANG['plugin_fusioninventory']["mapping"][423]="printer > meter > total number of printed pages (print mode)";
$LANG['plugin_fusioninventory']["mapping"][424]="printer > meter > number of printed black and white pages (print mode)";
$LANG['plugin_fusioninventory']["mapping"][425]="printer > meter > number of printed color pages (print mode)";
$LANG['plugin_fusioninventory']["mapping"][426]="printer > meter > total number of printed pages (copy mode)";
$LANG['plugin_fusioninventory']["mapping"][427]="printer > meter > number of printed black and white pages (copy mode)";
$LANG['plugin_fusioninventory']["mapping"][428]="printer > meter > number of printed color pages (copy mode)";
$LANG['plugin_fusioninventory']["mapping"][429]="printer > meter > total number of printed pages (fax mode)";
$LANG['plugin_fusioninventory']["mapping"][430]="networking > port > vlan";


$LANG['plugin_fusioninventory']["mapping"][101]="";
$LANG['plugin_fusioninventory']["mapping"][102]="";
$LANG['plugin_fusioninventory']["mapping"][103]="";
$LANG['plugin_fusioninventory']["mapping"][104]="MTU";
$LANG['plugin_fusioninventory']["mapping"][105]="Speed";
$LANG['plugin_fusioninventory']["mapping"][106]="Internal status";
$LANG['plugin_fusioninventory']["mapping"][107]="Last Change";
$LANG['plugin_fusioninventory']["mapping"][108]="Number of bytes received";
$LANG['plugin_fusioninventory']["mapping"][109]="Number of bytes sent";
$LANG['plugin_fusioninventory']["mapping"][110]="Number of input errors";
$LANG['plugin_fusioninventory']["mapping"][111]="Number of output errors";
$LANG['plugin_fusioninventory']["mapping"][112]="CPU usage";
$LANG['plugin_fusioninventory']["mapping"][113]="";
$LANG['plugin_fusioninventory']["mapping"][114]="Connection";
$LANG['plugin_fusioninventory']["mapping"][115]="Internal MAC address";
$LANG['plugin_fusioninventory']["mapping"][116]="Name";
$LANG['plugin_fusioninventory']["mapping"][117]="Model";
$LANG['plugin_fusioninventory']["mapping"][118]="Type";
$LANG['plugin_fusioninventory']["mapping"][119]="VLAN";
$LANG['plugin_fusioninventory']["mapping"][128]="Total number of printed pages";
$LANG['plugin_fusioninventory']["mapping"][129]="Number of printed black and white pages";
$LANG['plugin_fusioninventory']["mapping"][130]="Number of printed color pages";
$LANG['plugin_fusioninventory']["mapping"][131]="Number of printed monochrome pages";
$LANG['plugin_fusioninventory']["mapping"][132]="Number of printed color pages";
$LANG['plugin_fusioninventory']["mapping"][134]="Black cartridge";
$LANG['plugin_fusioninventory']["mapping"][135]="Photo black cartridge";
$LANG['plugin_fusioninventory']["mapping"][136]="Cyan cartridge";
$LANG['plugin_fusioninventory']["mapping"][137]="Yellow cartridge";
$LANG['plugin_fusioninventory']["mapping"][138]="Magenta cartridge";
$LANG['plugin_fusioninventory']["mapping"][139]="Light cyan cartridge";
$LANG['plugin_fusioninventory']["mapping"][140]="Light magenta cartridge";
$LANG['plugin_fusioninventory']["mapping"][141]="Photoconductor";
$LANG['plugin_fusioninventory']["mapping"][142]="Black photoconductor";
$LANG['plugin_fusioninventory']["mapping"][143]="Color photoconductor";
$LANG['plugin_fusioninventory']["mapping"][144]="Cyan photoconductor";
$LANG['plugin_fusioninventory']["mapping"][145]="Yellow photoconductor";
$LANG['plugin_fusioninventory']["mapping"][146]="Magenta photoconductor";
$LANG['plugin_fusioninventory']["mapping"][147]="Black transfer unit";
$LANG['plugin_fusioninventory']["mapping"][148]="Cyan transfer unit";
$LANG['plugin_fusioninventory']["mapping"][149]="Yellow transfer unit";
$LANG['plugin_fusioninventory']["mapping"][150]="Magenta transfer unit";
$LANG['plugin_fusioninventory']["mapping"][151]="Waste bin";
$LANG['plugin_fusioninventory']["mapping"][152]="Four";
$LANG['plugin_fusioninventory']["mapping"][153]="Cleaning module";
$LANG['plugin_fusioninventory']["mapping"][154]="Number of pages printed duplex";
$LANG['plugin_fusioninventory']["mapping"][155]="Number of scanned pages";
$LANG['plugin_fusioninventory']["mapping"][156]="Maintenance kit";
$LANG['plugin_fusioninventory']["mapping"][157]="Black toner";
$LANG['plugin_fusioninventory']["mapping"][158]="Cyan toner";
$LANG['plugin_fusioninventory']["mapping"][159]="Magenta toner";
$LANG['plugin_fusioninventory']["mapping"][160]="Yellow toner";
$LANG['plugin_fusioninventory']["mapping"][161]="Black drum";
$LANG['plugin_fusioninventory']["mapping"][162]="Cyan drum";
$LANG['plugin_fusioninventory']["mapping"][163]="Magenta drum";
$LANG['plugin_fusioninventory']["mapping"][164]="Yellow drum";
$LANG['plugin_fusioninventory']["mapping"][165]="Many informations grouped";
$LANG['plugin_fusioninventory']["mapping"][1423]="Total number of printed pages (print mode)";
$LANG['plugin_fusioninventory']["mapping"][1424]="Number of printed black and white pages (print mode)";
$LANG['plugin_fusioninventory']["mapping"][1425]="Number of printed color pages (print mode)";
$LANG['plugin_fusioninventory']["mapping"][1426]="Total number of printed pages (copy mode)";
$LANG['plugin_fusioninventory']["mapping"][1427]="Number of printed black and white pages (copy mode)";
$LANG['plugin_fusioninventory']["mapping"][1428]="Number of printed color pages (copy mode)";
$LANG['plugin_fusioninventory']["mapping"][1429]="Total number of printed pages (fax mode)";


$LANG['plugin_fusioninventory']["printer"][0]="pages";

$LANG['plugin_fusioninventory']["menu"][0]="Information about discovered devices";
$LANG['plugin_fusioninventory']["menu"][1]="Agent configuration";
$LANG['plugin_fusioninventory']["menu"][2]="IP range configuration";
$LANG['plugin_fusioninventory']["menu"][3]="Menu";
$LANG['plugin_fusioninventory']["menu"][4]="Unknown device";
$LANG['plugin_fusioninventory']["menu"][5]="Switch ports history";
$LANG['plugin_fusioninventory']["menu"][6]="Unused switch ports";

$LANG['plugin_fusioninventory']["buttons"][0]="Discover";

$LANG['plugin_fusioninventory']["discovery"][0]="IP range to scan";
$LANG['plugin_fusioninventory']["discovery"][1]="Discovered devices";
$LANG['plugin_fusioninventory']["discovery"][2]="Activation in the script automatically";
$LANG['plugin_fusioninventory']["discovery"][3]="Discover";
$LANG['plugin_fusioninventory']["discovery"][4]="Serial number";
$LANG['plugin_fusioninventory']["discovery"][5]="Number of imported devices";
$LANG['plugin_fusioninventory']["discovery"][6]="Primary criteria for existence";
$LANG['plugin_fusioninventory']["discovery"][7]="Secondary criteria for existence ";
$LANG['plugin_fusioninventory']["discovery"][8]="If a device returns empty fields on primary criteria, secondary criteria will be used.";
$LANG['plugin_fusioninventory']["discovery"][9]="Number of devices not imported because type undefined";

$LANG['plugin_fusioninventory']["rangeip"][0]="Start of IP range";
$LANG['plugin_fusioninventory']["rangeip"][1]="End of IP range";
$LANG['plugin_fusioninventory']["rangeip"][2]="IP Ranges";
$LANG['plugin_fusioninventory']["rangeip"][3]="Query";
$LANG['plugin_fusioninventory']["rangeip"][4]="Incorrect IP address";

$LANG['plugin_fusioninventory']["agents"][0]="SNMP Agent";
$LANG['plugin_fusioninventory']["agents"][2]="Number of threads used by core for device query";
$LANG['plugin_fusioninventory']["agents"][3]="Number of threads used by core for network discovery";
$LANG['plugin_fusioninventory']["agents"][4]="Last scan";
$LANG['plugin_fusioninventory']["agents"][5]="Agent version";
$LANG['plugin_fusioninventory']["agents"][6]="Lock";
$LANG['plugin_fusioninventory']["agents"][7]="Export agent configuration";
$LANG['plugin_fusioninventory']["agents"][9]="Advanced options";
$LANG['plugin_fusioninventory']["agents"][12]="Discovery Agent";
$LANG['plugin_fusioninventory']["agents"][13]="Query Agent";
$LANG['plugin_fusioninventory']["agents"][14]="Agent actions";
$LANG['plugin_fusioninventory']["agents"][15]="Agent state";
$LANG['plugin_fusioninventory']["agents"][16]="Initialized";
$LANG['plugin_fusioninventory']["agents"][17]="Agent is running";
$LANG['plugin_fusioninventory']["agents"][18]="Inventory has been received";
$LANG['plugin_fusioninventory']["agents"][19]="Inventory has been sended to OCS server";
$LANG['plugin_fusioninventory']["agents"][20]="Synchronisation between OCS and GLPI is running";
$LANG['plugin_fusioninventory']["agents"][21]="Inventory terminated";
$LANG['plugin_fusioninventory']["agents"][22]="Wait";
$LANG['plugin_fusioninventory']["agents"][23]="Computer link";

$LANG['plugin_fusioninventory']["unknown"][0]="DNS Name";
$LANG['plugin_fusioninventory']["unknown"][1]="Network port name";
$LANG['plugin_fusioninventory']["unknown"][2]="Approved devices";
$LANG['plugin_fusioninventory']["unknown"][3]="Discovered by agent";
$LANG['plugin_fusioninventory']["unknown"][4]="Network hub";
$LANG['plugin_fusioninventory']["unknown"][5]="Imported from unknown devices (FusionInventory)";

$LANG['plugin_fusioninventory']["task"][0]="Task";
$LANG['plugin_fusioninventory']["task"][1]="Task management";
$LANG['plugin_fusioninventory']["task"][2]="Action";
$LANG['plugin_fusioninventory']["task"][3]="Unit";
$LANG['plugin_fusioninventory']["task"][4]="Get information now";
$LANG['plugin_fusioninventory']["task"][5]="Select OCS Agent";
$LANG['plugin_fusioninventory']["task"][6]="Get state";
$LANG['plugin_fusioninventory']["task"][7]="State";
$LANG['plugin_fusioninventory']["task"][8]="Ready";
$LANG['plugin_fusioninventory']["task"][9]="Not responding";
$LANG['plugin_fusioninventory']["task"][10]="Running... not available";
$LANG['plugin_fusioninventory']["task"][11]="Agent has been notified and begun running";
$LANG['plugin_fusioninventory']["task"][12]="Wake agent";
$LANG['plugin_fusioninventory']["task"][13]="Agent(s) unavailable";

$LANG['plugin_fusioninventory']["constructdevice"][0]="Management of equipment MIBs";
$LANG['plugin_fusioninventory']["constructdevice"][1]="Automatic creation of models";
$LANG['plugin_fusioninventory']["constructdevice"][2]="Generate discovery file";
$LANG['plugin_fusioninventory']["constructdevice"][3]="Delete unused models";
$LANG['plugin_fusioninventory']["constructdevice"][4]="Export all models";
$LANG['plugin_fusioninventory']["constructdevice"][5]="Re-create model comments";
$LANG['plugin_fusioninventory']["constructdevice"][6]="Already exists";

$LANG['plugin_fusioninventory']["update"][0]="your history table has more than 300,000 entries; you must run this command to finish update: ";

?>
