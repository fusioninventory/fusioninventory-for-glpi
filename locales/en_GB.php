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

$title="Tracker";
$version="2.1.1";

$LANG['plugin_tracker']["title"][0]="$title";
$LANG['plugin_tracker']["title"][1]="SNMP information";
$LANG['plugin_tracker']["title"][2]="connections history";
$LANG['plugin_tracker']["title"][3]="[Trk] Errors";
$LANG['plugin_tracker']["title"][4]="[Trk] Cron";

$LANG['plugin_tracker']["profile"][0]="Rights management";
$LANG['plugin_tracker']["profile"][1]="$title"; //interface

$LANG['plugin_tracker']["profile"][10]="Profiles configured";
$LANG['plugin_tracker']["profile"][11]="Computer history";
$LANG['plugin_tracker']["profile"][12]="Printer history";
$LANG['plugin_tracker']["profile"][13]="Printer information";
$LANG['plugin_tracker']["profile"][14]="Network information";
$LANG['plugin_tracker']["profile"][15]="Errors";

$LANG['plugin_tracker']["profile"][16]="SNMP networking";
$LANG['plugin_tracker']["profile"][17]="SNMP peripheral";
$LANG['plugin_tracker']["profile"][18]="SNMP printers";
$LANG['plugin_tracker']["profile"][19]="SNMP models";
$LANG['plugin_tracker']["profile"][20]="SNMP authentification";
$LANG['plugin_tracker']["profile"][21]="Script information";
$LANG['plugin_tracker']["profile"][22]="Network discovery";
$LANG['plugin_tracker']["profile"][23]="General configuration";
$LANG['plugin_tracker']["profile"][24]="SNMP model";
$LANG['plugin_tracker']["profile"][25]="IP range";
$LANG['plugin_tracker']["profile"][26]="Agent";
$LANG['plugin_tracker']["profile"][27]="Agent information";
$LANG['plugin_tracker']["profile"][28]="Report";


$LANG['plugin_tracker']["setup"][2]="Thanks to put all in root entity (see all)";
$LANG['plugin_tracker']["setup"][3]="Plugin configuration".$title;
$LANG['plugin_tracker']["setup"][4]="Install plugin $title $version";
$LANG['plugin_tracker']["setup"][5]="Update plugin $title to version $version";
$LANG['plugin_tracker']["setup"][6]="Uninstall plugin $title $version";
$LANG['plugin_tracker']["setup"][8]="Attention, uninstalling this plugin is an irreversible step.<br> You will loose all datas.";
$LANG['plugin_tracker']["setup"][11]="Instructions";
$LANG['plugin_tracker']["setup"][12]="FAQ";
$LANG['plugin_tracker']["setup"][13]="Verification of PHP modules";
$LANG['plugin_tracker']["setup"][14]="SNMP extension of PHP isn't load";
$LANG['plugin_tracker']["setup"][15]="PHP/PECL runkit extension isn't load";
$LANG['plugin_tracker']["setup"][16]="Documentation";

$LANG['plugin_tracker']["functionalities"][0]="Features";
$LANG['plugin_tracker']["functionalities"][1]="Add / Delete features";
$LANG['plugin_tracker']["functionalities"][2]="General configuration";
$LANG['plugin_tracker']["functionalities"][3]="SNMP";
$LANG['plugin_tracker']["functionalities"][4]="Connection";
$LANG['plugin_tracker']["functionalities"][5]="Server script";
$LANG['plugin_tracker']["functionalities"][6]="Legend";

$LANG['plugin_tracker']["functionalities"][10]="History activation";
$LANG['plugin_tracker']["functionalities"][11]="Connection module activation";
$LANG['plugin_tracker']["functionalities"][12]="SNMP networking module activation";
$LANG['plugin_tracker']["functionalities"][13]="SNMP peripheral module activation";
$LANG['plugin_tracker']["functionalities"][14]="SNMP phones module activation";
$LANG['plugin_tracker']["functionalities"][15]="SNMP printers module activation";
$LANG['plugin_tracker']["functionalities"][16]="SNMP authentification";
$LANG['plugin_tracker']["functionalities"][17]="Database";
$LANG['plugin_tracker']["functionalities"][18]="Files";
$LANG['plugin_tracker']["functionalities"][19]="Please configure the SNMP authentification in the setup of the plugin";
$LANG['plugin_tracker']["functionalities"][20]="Status of active devices";
$LANG['plugin_tracker']["functionalities"][21]="Retention of the historical interconnections between material in days (0 = infinity)";
$LANG['plugin_tracker']["functionalities"][22]="Retention of the historic changes to the state of ports (0 = infinity)";
$LANG['plugin_tracker']["functionalities"][23]="Retention of history unknown MAC addresses (0 = infinity)";
$LANG['plugin_tracker']["functionalities"][24]="Retention of historical errors SNMP (0 = infinity))";
$LANG['plugin_tracker']["functionalities"][25]="Retention of historical processes scripts (0 = infinity)";
$LANG['plugin_tracker']["functionalities"][26]="GLPI URL for agent";
$LANG['plugin_tracker']["functionalities"][27]="SSL only for agent";
$LANG['plugin_tracker']["functionalities"][28]="History configuration";
$LANG['plugin_tracker']["functionalities"][29]="List of fields to history";

$LANG['plugin_tracker']["functionalities"][30]="Status of active material";
$LANG['plugin_tracker']["functionalities"][31]="Management of cartridges and stock";
$LANG['plugin_tracker']["functionalities"][36]="Frequency of meter reading";

$LANG['plugin_tracker']["functionalities"][40]="Configuration";
$LANG['plugin_tracker']["functionalities"][41]="Status of active material";
$LANG['plugin_tracker']["functionalities"][42]="Switch";
$LANG['plugin_tracker']["functionalities"][43]="SNMP authentification";

$LANG['plugin_tracker']["functionalities"][50]="Number of simultaneous processes for the network discovery";
$LANG['plugin_tracker']["functionalities"][51]="Number of simultaneous processes for SNMP queries";
$LANG['plugin_tracker']["functionalities"][52]="Log files activation";
$LANG['plugin_tracker']["functionalities"][53]="Number of simultanous processes to be used by server script";

$LANG['plugin_tracker']["functionalities"][60]="Clean history";

$LANG['plugin_tracker']["snmp"][0]="SNMP information of equipment";
$LANG['plugin_tracker']["snmp"][1]="General";
$LANG['plugin_tracker']["snmp"][2]="Cabling";
$LANG['plugin_tracker']["snmp"][2]="SNMP data";

$LANG['plugin_tracker']["snmp"][11]="Additional information";
$LANG['plugin_tracker']["snmp"][12]="Uptime";
$LANG['plugin_tracker']["snmp"][13]="CPU usage (in %)";
$LANG['plugin_tracker']["snmp"][14]="Memory usage (in %)";

$LANG['plugin_tracker']["snmp"][31]="Unable to retrieve SNMP info: This is not a switch !";
$LANG['plugin_tracker']["snmp"][32]="Unable to retrieve SNMP information: Hardware inactive !";
$LANG['plugin_tracker']["snmp"][33]="Unable to retrieve SNMP information: IP not specified in the basic !";
$LANG['plugin_tracker']["snmp"][34]="The switch is connected to a machine that is not filled !";

$LANG['plugin_tracker']["snmp"][40]="Ports array";
$LANG['plugin_tracker']["snmp"][41]="";
$LANG['plugin_tracker']["snmp"][42]="MTU";
$LANG['plugin_tracker']["snmp"][43]="Speed";
$LANG['plugin_tracker']["snmp"][44]="Internal status";
$LANG['plugin_tracker']["snmp"][45]="Last Change";
$LANG['plugin_tracker']["snmp"][46]="Number of bytes received";
$LANG['plugin_tracker']["snmp"][47]="Number of input errors";
$LANG['plugin_tracker']["snmp"][48]="Number of bytes sent";
$LANG['plugin_tracker']["snmp"][49]="Number of errors in reception";
$LANG['plugin_tracker']["snmp"][50]="Connection";
$LANG['plugin_tracker']["snmp"][51]="Duplex";
$LANG['plugin_tracker']["snmp"][52]="Date of last TRACKER inventory";
$LANG['plugin_tracker']["snmp"][53]="Last inventory";

$LANG['plugin_tracker']["snmpauth"][1]="Community";
$LANG['plugin_tracker']["snmpauth"][2]="User";
$LANG['plugin_tracker']["snmpauth"][3]="Authentication scheme";
$LANG['plugin_tracker']["snmpauth"][4]="Encryption protocol for authentication ";
$LANG['plugin_tracker']["snmpauth"][5]="Password";
$LANG['plugin_tracker']["snmpauth"][6]="Encryption protocol for data (write)";
$LANG['plugin_tracker']["snmpauth"][7]="Password (write)";

$LANG['plugin_tracker']["cron"][0]="Automatic reading meter";
$LANG['plugin_tracker']["cron"][1]="Activate the record";
$LANG['plugin_tracker']["cron"][2]="";
$LANG['plugin_tracker']["cron"][3]="Default";

$LANG['plugin_tracker']["errors"][0]="Errors";
$LANG['plugin_tracker']["errors"][1]="IP";
$LANG['plugin_tracker']["errors"][2]="Description";
$LANG['plugin_tracker']["errors"][3]="Date first problem";
$LANG['plugin_tracker']["errors"][4]="Date last problem";

$LANG['plugin_tracker']["errors"][10]="Inconsistent with the basic GLPI";
$LANG['plugin_tracker']["errors"][11]="Position unknown";
$LANG['plugin_tracker']["errors"][12]="Unknown IP";

$LANG['plugin_tracker']["errors"][20]="SNMP errors";
$LANG['plugin_tracker']["errors"][21]="Unable to retrieve information";

$LANG['plugin_tracker']["errors"][30]="Wiring error";
$LANG['plugin_tracker']["errors"][31]="Wiring problem";

$LANG['plugin_tracker']["errors"][50]="GLPI version not compatible need 0.72.1";

$LANG['plugin_tracker']["errors"][101]="Timeout";
$LANG['plugin_tracker']["errors"][102]="No SNMP model assigned";
$LANG['plugin_tracker']["errors"][103]="No SNMP authentification assigned";

$LANG['plugin_tracker']["history"][0] = "Old";
$LANG['plugin_tracker']["history"][1] = "New";
$LANG['plugin_tracker']["history"][2] = "Disconnect";
$LANG['plugin_tracker']["history"][3] = "Connection";

$LANG['plugin_tracker']["prt_history"][0]="History and Statistics of printer counters";

$LANG['plugin_tracker']["prt_history"][10]="Printer counter statistics";
$LANG['plugin_tracker']["prt_history"][11]="day(s)";
$LANG['plugin_tracker']["prt_history"][12]="Total printed pages";
$LANG['plugin_tracker']["prt_history"][13]="Pages / day";

$LANG['plugin_tracker']["prt_history"][20]="History meter printer";
$LANG['plugin_tracker']["prt_history"][21]="Date";
$LANG['plugin_tracker']["prt_history"][22]="Meter";


$LANG['plugin_tracker']["cpt_history"][0]="History sessions";
$LANG['plugin_tracker']["cpt_history"][1]="Contact";
$LANG['plugin_tracker']["cpt_history"][2]="Computer";
$LANG['plugin_tracker']["cpt_history"][3]="User";
$LANG['plugin_tracker']["cpt_history"][4]="State";
$LANG['plugin_tracker']["cpt_history"][5]="Date";


$LANG['plugin_tracker']["type"][1]="Computer";
$LANG['plugin_tracker']["type"][2]="Switch";
$LANG['plugin_tracker']["type"][3]="Printer";

$LANG['plugin_tracker']["rules"][1]="Rules";

$LANG['plugin_tracker']["massiveaction"][1]="Assign SNMP model";
$LANG['plugin_tracker']["massiveaction"][2]="Assign SNMP authentification";

$LANG['plugin_tracker']["model_info"][1]="SNMP information";
$LANG['plugin_tracker']["model_info"][2]="SNMP version";
$LANG['plugin_tracker']["model_info"][3]="SNMP authentification";
$LANG['plugin_tracker']["model_info"][4]="SNMP models";
$LANG['plugin_tracker']["model_info"][5]="MIB management";
$LANG['plugin_tracker']["model_info"][6]="Edit SNMP model";
$LANG['plugin_tracker']["model_info"][7]="Create SNMP model";
$LANG['plugin_tracker']["model_info"][8]="Model already exists: import was not done";
$LANG['plugin_tracker']["model_info"][9]="Import completed successfully";
$LANG['plugin_tracker']["model_info"][10]="SNMP model import";
$LANG['plugin_tracker']["model_info"][11]="Activation";
$LANG['plugin_tracker']["model_info"][12]="Key for model discovery";
$LANG['plugin_tracker']["model_info"][13]="Load right model";
$LANG['plugin_tracker']["model_info"][14]="Load right SNMP model";

$LANG['plugin_tracker']["mib"][1]="MIB Label";
$LANG['plugin_tracker']["mib"][2]="Object";
$LANG['plugin_tracker']["mib"][3]="oid";
$LANG['plugin_tracker']["mib"][4]="add an oid...";
$LANG['plugin_tracker']["mib"][5]="oid list";
$LANG['plugin_tracker']["mib"][6]="Port Counters";
$LANG['plugin_tracker']["mib"][7]="Dynamic port (.x)";
$LANG['plugin_tracker']["mib"][8]="Linked fields";
$LANG['plugin_tracker']["mib"][9]="Vlan";

$LANG['plugin_tracker']["processes"][0]="History of script executions";
$LANG['plugin_tracker']["processes"][1]="PID";
$LANG['plugin_tracker']["processes"][2]="Status";
$LANG['plugin_tracker']["processes"][3]="Number of processes";
$LANG['plugin_tracker']["processes"][4]="Start date of execution";
$LANG['plugin_tracker']["processes"][5]="End date of execution";
$LANG['plugin_tracker']["processes"][6]="Network equipment queried";
$LANG['plugin_tracker']["processes"][7]="Printers queried";
$LANG['plugin_tracker']["processes"][8]="Ports queried";
$LANG['plugin_tracker']["processes"][9]="Errors";
$LANG['plugin_tracker']["processes"][10]="Time Script";
$LANG['plugin_tracker']["processes"][11]="added fields";
$LANG['plugin_tracker']["processes"][12]="SNMP errors";
$LANG['plugin_tracker']["processes"][13]="Unknown MAC";
$LANG['plugin_tracker']["processes"][14]="List of unknown MAC addresses";
$LANG['plugin_tracker']["processes"][15]="First PID";
$LANG['plugin_tracker']["processes"][16]="Last PID";
$LANG['plugin_tracker']["processes"][17]="Date of first detection";
$LANG['plugin_tracker']["processes"][18]="Date of last detection";
$LANG['plugin_tracker']["processes"][19]="History of agent executions";
$LANG['plugin_tracker']["processes"][20]="Reports and Statistics";
$LANG['plugin_tracker']["processes"][21]="Queried devices";
$LANG['plugin_tracker']["processes"][22]="Errors";
$LANG['plugin_tracker']["processes"][23]="Total duration of discovery";
$LANG['plugin_tracker']["processes"][24]="Total duration of query";

$LANG['plugin_tracker']["state"][0]="Computer start";
$LANG['plugin_tracker']["state"][1]="Computer stop";
$LANG['plugin_tracker']["state"][2]="User connection";
$LANG['plugin_tracker']["state"][3]="User disconnection";


$LANG['plugin_tracker']["mapping"][1]="networking > location";
$LANG['plugin_tracker']["mapping"][2]="networking > firmware";
$LANG['plugin_tracker']["mapping"][3]="networking > uptime";
$LANG['plugin_tracker']["mapping"][4]="networking > port > mtu";
$LANG['plugin_tracker']["mapping"][5]="networking > port > speed";
$LANG['plugin_tracker']["mapping"][6]="networking > port > internal status";
$LANG['plugin_tracker']["mapping"][7]="networking > ports > Last Change";
$LANG['plugin_tracker']["mapping"][8]="networking > port > number of bytes entered";
$LANG['plugin_tracker']["mapping"][9]="networking > port > number of bytes out";
$LANG['plugin_tracker']["mapping"][10]="networking > port > number of input errors";
$LANG['plugin_tracker']["mapping"][11]="networking > port > number of errors output";
$LANG['plugin_tracker']["mapping"][12]="networking > CPU usage";
$LANG['plugin_tracker']["mapping"][13]="networking > serial number";
$LANG['plugin_tracker']["mapping"][14]="networking > port > connection status";
$LANG['plugin_tracker']["mapping"][15]="networking > port > MAC address";
$LANG['plugin_tracker']["mapping"][16]="networking > port > name";
$LANG['plugin_tracker']["mapping"][17]="networking > model";
$LANG['plugin_tracker']["mapping"][18]="networking > ports > type";
$LANG['plugin_tracker']["mapping"][19]="networking > VLAN";
$LANG['plugin_tracker']["mapping"][20]="networking > name";
$LANG['plugin_tracker']["mapping"][21]="networking > total memory";
$LANG['plugin_tracker']["mapping"][22]="networking > free memory";
$LANG['plugin_tracker']["mapping"][23]="networking > port > port description";
$LANG['plugin_tracker']["mapping"][24]="printer > name";
$LANG['plugin_tracker']["mapping"][25]="printer > model";
$LANG['plugin_tracker']["mapping"][26]="printer > total memory";
$LANG['plugin_tracker']["mapping"][27]="printer > serial number";
$LANG['plugin_tracker']["mapping"][28]="printer > meter > total number of printed pages";
$LANG['plugin_tracker']["mapping"][29]="printer > meter > number of printed black and white pages";
$LANG['plugin_tracker']["mapping"][30]="printer > meter > number of printed color pages";
$LANG['plugin_tracker']["mapping"][31]="printer > meter > number of printed monochrome pages";
$LANG['plugin_tracker']["mapping"][32]="printer > meter > number of printed color pages";
$LANG['plugin_tracker']["mapping"][33]="networking > port > duplex type";
$LANG['plugin_tracker']["mapping"][34]="printer > consumables > black cartridge (%)";
$LANG['plugin_tracker']["mapping"][35]="printer > consumables > photo black cartridge (%)";
$LANG['plugin_tracker']["mapping"][36]="printer > consumables > cyan cartridge (%)";
$LANG['plugin_tracker']["mapping"][37]="printer > consumables > yellow cartridge (%)";
$LANG['plugin_tracker']["mapping"][38]="printer > consumables > magenta cartridge (%)";
$LANG['plugin_tracker']["mapping"][39]="printer > consumables > light cyan cartridge (%)";
$LANG['plugin_tracker']["mapping"][40]="printer > consumables > light magenta cartridge (%)";
$LANG['plugin_tracker']["mapping"][41]="printer > consumables > photoconductor (%)";
$LANG['plugin_tracker']["mapping"][42]="printer > consumables > black photoconductor (%)";
$LANG['plugin_tracker']["mapping"][43]="printer > consumables > color photoconductor (%)";
$LANG['plugin_tracker']["mapping"][44]="printer > consumables > cyan photoconductor (%)";
$LANG['plugin_tracker']["mapping"][45]="printer > consumables > yellow photoconductor (%)";
$LANG['plugin_tracker']["mapping"][46]="printer > consumables > magenta photoconductor (%)";
$LANG['plugin_tracker']["mapping"][47]="printer > consumables > black transfer unit (%)";
$LANG['plugin_tracker']["mapping"][48]="printer > consumables > cyan transfer unit (%)";
$LANG['plugin_tracker']["mapping"][49]="printer > consumables > yellow transfer unit (%)";
$LANG['plugin_tracker']["mapping"][50]="printer > consumables > magenta transfer unit (%)";
$LANG['plugin_tracker']["mapping"][51]="printer > consumables > waste bin (%)";
$LANG['plugin_tracker']["mapping"][52]="printer > consumables > four (%)";
$LANG['plugin_tracker']["mapping"][53]="printer > consumables > cleaning module (%)";
$LANG['plugin_tracker']["mapping"][54]="printer > meter > number of printed duplex pages";
$LANG['plugin_tracker']["mapping"][55]="printer > meter > nomber of scanned pages";
$LANG['plugin_tracker']["mapping"][56]="printer > location";
$LANG['plugin_tracker']["mapping"][57]="printer > port > name";
$LANG['plugin_tracker']["mapping"][58]="printer > port > MAC address";
$LANG['plugin_tracker']["mapping"][59]="printer > consumables > black cartridge (max ink)";
$LANG['plugin_tracker']["mapping"][60]="printer > consumables > black cartridge (remaining ink )";
$LANG['plugin_tracker']["mapping"][61]="printer > consumables > cyan cartridge (max ink)";
$LANG['plugin_tracker']["mapping"][62]="printer > consumables > cyan cartridge (remaining ink)";
$LANG['plugin_tracker']["mapping"][63]="printer > consumables > yellow cartridge (max ink)";
$LANG['plugin_tracker']["mapping"][64]="printer > consumables > yellow cartridge (remaining ink)";
$LANG['plugin_tracker']["mapping"][65]="printer > consumables > magenta cartridge (max ink)";
$LANG['plugin_tracker']["mapping"][66]="printer > consumables > magenta cartridge (remaining ink)";
$LANG['plugin_tracker']["mapping"][67]="printer > consumables > light cyan cartridge (max ink)";
$LANG['plugin_tracker']["mapping"][68]="printer > consumables > light cyan cartridge (remaining ink)";
$LANG['plugin_tracker']["mapping"][69]="printer > consumables > light magenta cartridge (max ink)";
$LANG['plugin_tracker']["mapping"][70]="printer > consumables > light magenta cartridge (remaining ink)";
$LANG['plugin_tracker']["mapping"][71]="printer > consumables > photoconductor (max ink)";
$LANG['plugin_tracker']["mapping"][72]="printer > consumables > photoconductor (remaining ink)";
$LANG['plugin_tracker']["mapping"][73]="printer > consumables > black photoconductor (max ink)";
$LANG['plugin_tracker']["mapping"][74]="printer > consumables > black photoconductor (remaining ink)";
$LANG['plugin_tracker']["mapping"][75]="printer > consumables > color photoconductor (max ink)";
$LANG['plugin_tracker']["mapping"][76]="printer > consumables > color photoconductor (remaining ink)";
$LANG['plugin_tracker']["mapping"][77]="printer > consumables > cyan photoconductor (max ink)";
$LANG['plugin_tracker']["mapping"][78]="printer > consumables > cyan photoconductor (remaining ink)";
$LANG['plugin_tracker']["mapping"][79]="printer > consumables > yellow photoconductor (max ink)";
$LANG['plugin_tracker']["mapping"][80]="printer > consumables > yellow photoconductor (remaining ink)";
$LANG['plugin_tracker']["mapping"][81]="printer > consumables > magenta photoconductor (max ink)";
$LANG['plugin_tracker']["mapping"][82]="printer > consumables > magenta photoconductor (remaining ink)";
$LANG['plugin_tracker']["mapping"][83]="printer > consumables > black transfer unit (max ink)";
$LANG['plugin_tracker']["mapping"][84]="printer > consumables > black transfer unit (remaining ink)";
$LANG['plugin_tracker']["mapping"][85]="printer > consumables > cyan transfer unit (max ink)";
$LANG['plugin_tracker']["mapping"][86]="printer > consumables > cyan transfer unit (remaining ink)";
$LANG['plugin_tracker']["mapping"][87]="printer > consumables > yellow transfer unit (max ink)";
$LANG['plugin_tracker']["mapping"][88]="printer > consumables > yellow transfer unit (remaining ink)";
$LANG['plugin_tracker']["mapping"][89]="printer > consumables > magenta transfer unit (max ink)";
$LANG['plugin_tracker']["mapping"][90]="printer > consumables > magenta transfer unit (remaining ink)";
$LANG['plugin_tracker']["mapping"][91]="printer > consumables > waste bin (max ink)";
$LANG['plugin_tracker']["mapping"][92]="printer > consumables > waste bin (remaining ink)";
$LANG['plugin_tracker']["mapping"][93]="printer > consumables > four (max ink)";
$LANG['plugin_tracker']["mapping"][94]="printer > consumables > four (remaining ink)";
$LANG['plugin_tracker']["mapping"][95]="printer > consumables > cleaning module (max ink)";
$LANG['plugin_tracker']["mapping"][96]="printer > consumables > cleaning module (remaining ink)";
$LANG['plugin_tracker']["mapping"][97]="printer > port > type";
$LANG['plugin_tracker']["mapping"][98]="printer > consumables > Maintenance kit (max)";
$LANG['plugin_tracker']["mapping"][99]="printer > consumables > Maintenance kit (remaining)";
$LANG['plugin_tracker']["mapping"][400]="printer > consumables > Maintenance kit (%)";
$LANG['plugin_tracker']["mapping"][401]="networking > CPU user";
$LANG['plugin_tracker']["mapping"][402]="networking > CPU system";
$LANG['plugin_tracker']["mapping"][403]="networking > contact";
$LANG['plugin_tracker']["mapping"][404]="networking > comments";
$LANG['plugin_tracker']["mapping"][405]="printer > contact";
$LANG['plugin_tracker']["mapping"][406]="printer > comments";
$LANG['plugin_tracker']["mapping"][407]="printer > port > IP address";
$LANG['plugin_tracker']["mapping"][408]="networking > port > numÃ©ro index";
$LANG['plugin_tracker']["mapping"][409]="networking > Adress CDP";
$LANG['plugin_tracker']["mapping"][410]="networking > Port CDP";
$LANG['plugin_tracker']["mapping"][411]="networking > Trunk port status";
$LANG['plugin_tracker']["mapping"][412]="networking > MAC address filters (dot1dTpFdbAddress)";
$LANG['plugin_tracker']["mapping"][413]="networking > Physical addresses in memory (ipNetToMediaPhysAddress)";
$LANG['plugin_tracker']["mapping"][414]="networking > instances de ports (dot1dTpFdbPort)";
$LANG['plugin_tracker']["mapping"][415]="networking > numÃ©ro de ports associÃ© ID du port (dot1dBasePortIfIndex)";
$LANG['plugin_tracker']["mapping"][416]="printer > port > index number";
$LANG['plugin_tracker']["mapping"][417]="networking > MAC address";
$LANG['plugin_tracker']["mapping"][418]="printer > Inventory number";
$LANG['plugin_tracker']["mapping"][419]="networking > Inventory number";
$LANG['plugin_tracker']["mapping"][420]="printer > manufacturer";
$LANG['plugin_tracker']["mapping"][421]="networking > IP addresses";
$LANG['plugin_tracker']["mapping"][422]="networking > portVlanIndex";
$LANG['plugin_tracker']["mapping"][423]="printer > meter > total number of printed pages (print)";
$LANG['plugin_tracker']["mapping"][424]="printer > meter > number of printed black and white pages (print)";
$LANG['plugin_tracker']["mapping"][425]="printer > meter > number of printed color pages (print)";
$LANG['plugin_tracker']["mapping"][426]="printer > meter > total number of printed pages (copy)";
$LANG['plugin_tracker']["mapping"][427]="printer > meter > number of printed black and white pages (copy)";
$LANG['plugin_tracker']["mapping"][428]="printer > meter > number of printed color pages (copy)";
$LANG['plugin_tracker']["mapping"][429]="printer > meter > total number of printed pages (fax)";



$LANG['plugin_tracker']["mapping"][101]="";
$LANG['plugin_tracker']["mapping"][102]="";
$LANG['plugin_tracker']["mapping"][103]="";
$LANG['plugin_tracker']["mapping"][104]="MTU";
$LANG['plugin_tracker']["mapping"][105]="Speed";
$LANG['plugin_tracker']["mapping"][106]="Internal status";
$LANG['plugin_tracker']["mapping"][107]="Last Change";
$LANG['plugin_tracker']["mapping"][108]="Number of received bytes";
$LANG['plugin_tracker']["mapping"][109]="Number of outgoing bytes";
$LANG['plugin_tracker']["mapping"][110]="Number of input errors";
$LANG['plugin_tracker']["mapping"][111]="Number of output errors";
$LANG['plugin_tracker']["mapping"][112]="CPU usage";
$LANG['plugin_tracker']["mapping"][113]="";
$LANG['plugin_tracker']["mapping"][114]="Connection";
$LANG['plugin_tracker']["mapping"][115]="Internal MAC address";
$LANG['plugin_tracker']["mapping"][116]="Name";
$LANG['plugin_tracker']["mapping"][117]="Model";
$LANG['plugin_tracker']["mapping"][118]="Type";
$LANG['plugin_tracker']["mapping"][119]="VLAN";
$LANG['plugin_tracker']["mapping"][128]="Total number of printed pages";
$LANG['plugin_tracker']["mapping"][129]="Number of printed black and white pages";
$LANG['plugin_tracker']["mapping"][130]="Number of printed color pages";
$LANG['plugin_tracker']["mapping"][131]="Number of printed monochrome pages";
$LANG['plugin_tracker']["mapping"][132]="Number of printed color pages";
$LANG['plugin_tracker']["mapping"][134]="Black cartridge";
$LANG['plugin_tracker']["mapping"][135]="Photo black cartridge";
$LANG['plugin_tracker']["mapping"][136]="Cyan cartridge";
$LANG['plugin_tracker']["mapping"][137]="Yellow cartridge";
$LANG['plugin_tracker']["mapping"][138]="Magenta cartridge";
$LANG['plugin_tracker']["mapping"][139]="Light cyan cartridge";
$LANG['plugin_tracker']["mapping"][140]="Light magenta cartridge";
$LANG['plugin_tracker']["mapping"][141]="Photoconductor";
$LANG['plugin_tracker']["mapping"][142]="Black photoconductor";
$LANG['plugin_tracker']["mapping"][143]="Color photoconductor";
$LANG['plugin_tracker']["mapping"][144]="Cyan photoconductor";
$LANG['plugin_tracker']["mapping"][145]="Yellow photoconductor";
$LANG['plugin_tracker']["mapping"][146]="Magenta photoconductor";
$LANG['plugin_tracker']["mapping"][147]="Black transfer unit";
$LANG['plugin_tracker']["mapping"][148]="Cyan transfer unit";
$LANG['plugin_tracker']["mapping"][149]="Yellow transfer unit";
$LANG['plugin_tracker']["mapping"][150]="Magenta transfer unit";
$LANG['plugin_tracker']["mapping"][151]="Waste bin";
$LANG['plugin_tracker']["mapping"][152]="Four";
$LANG['plugin_tracker']["mapping"][153]="Cleaning module";
$LANG['plugin_tracker']["mapping"][154]="Number of pages printed duplex";
$LANG['plugin_tracker']["mapping"][155]="Number of scanned pages";
$LANG['plugin_tracker']["mapping"][156]="Maintenance kit";
$LANG['plugin_tracker']["mapping"][1423]="Total number of printed pages (print)";
$LANG['plugin_tracker']["mapping"][1424]="Number of printed black and white pages (print)";
$LANG['plugin_tracker']["mapping"][1425]="Number of printed color pages (print)";
$LANG['plugin_tracker']["mapping"][1426]="Total number of printed pages (copy)";
$LANG['plugin_tracker']["mapping"][1427]="Number of printed black and white pages (copy)";
$LANG['plugin_tracker']["mapping"][1428]="Number of printed color pages (copy)";
$LANG['plugin_tracker']["mapping"][1429]="Total number of printed pages (fax)";


$LANG['plugin_tracker']["printer"][0]="pages";


$LANG['plugin_tracker']["menu"][1]="Agent configuration";
$LANG['plugin_tracker']["menu"][2]="IP range configuration";
$LANG['plugin_tracker']["menu"][3]="Menu";
$LANG['plugin_tracker']["menu"][4]="Unknown device";

$LANG['plugin_tracker']["menu"][0]="Information about discovered devices";

$LANG['plugin_tracker']["buttons"][0]="Discover";

$LANG['plugin_tracker']["discovery"][0]="IP range to scan";
$LANG['plugin_tracker']["discovery"][1]="Discovered devices";
$LANG['plugin_tracker']["discovery"][2]="Activation in the script automatically";
$LANG['plugin_tracker']["discovery"][3]="Discover";
$LANG['plugin_tracker']["discovery"][4]="Serial number";
$LANG['plugin_tracker']["discovery"][5]="Number of imported devices";
$LANG['plugin_tracker']["discovery"][6]="Primary criteria for existence";
$LANG['plugin_tracker']["discovery"][7]="Secondary criteria for existence ";
$LANG['plugin_tracker']["discovery"][8]="If a device returns empty fields on first ciriteria, second one will be used.";

$LANG['plugin_tracker']["rangeip"][0]="Start of IP range";
$LANG['plugin_tracker']["rangeip"][1]="End of IP range";
$LANG['plugin_tracker']["rangeip"][2]="IP Ranges";
$LANG['plugin_tracker']["rangeip"][3]="Interrogation";


$LANG['plugin_tracker']["agents"][0]="SNMP Agent";
$LANG['plugin_tracker']["agents"][2]="Number of threads used by core for querying devices";
$LANG['plugin_tracker']["agents"][3]="Number of threads used by core for network discovery";
$LANG['plugin_tracker']["agents"][4]="Last scan";
$LANG['plugin_tracker']["agents"][5]="Agent version";
$LANG['plugin_tracker']["agents"][6]="Lock";
$LANG['plugin_tracker']["agents"][7]="Export agent configuration";
$LANG['plugin_tracker']["agents"][8]="Fragments en Ko";
$LANG['plugin_tracker']["agents"][9]="Advanced options";
$LANG['plugin_tracker']["agents"][10]="Number of core(s) (CPU) used for querying devices";
$LANG['plugin_tracker']["agents"][11]="Number of core(s) (CPU) used for network discovery";

?>