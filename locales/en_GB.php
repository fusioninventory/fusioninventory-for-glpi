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
$version="2.0.0";

$LANG['tracker']["title"][0]="$title";
$LANG['tracker']["title"][1]="SNMP information";
$LANG['tracker']["title"][2]="connections history";
$LANG['tracker']["title"][3]="[Trk] Errors";
$LANG['tracker']["title"][4]="[Trk] Cron";

$LANG['tracker']["profile"][0]="Rights management";
$LANG['tracker']["profile"][1]="$title"; //interface

$LANG['tracker']["profile"][10]="Profiles configured";
$LANG['tracker']["profile"][11]="Computer history";
$LANG['tracker']["profile"][12]="Printer history";
$LANG['tracker']["profile"][13]="Printer information";
$LANG['tracker']["profile"][14]="Network information";
$LANG['tracker']["profile"][15]="Errors";

$LANG['tracker']["profile"][16]="SNMP networking";
$LANG['tracker']["profile"][17]="SNMP peripheral";
$LANG['tracker']["profile"][18]="SNMP printers";
$LANG['tracker']["profile"][19]="SNMP models";
$LANG['tracker']["profile"][20]="SNMP authentification";
$LANG['tracker']["profile"][21]="Script information";
$LANG['tracker']["profile"][22]="Network discovery";
$LANG['tracker']["profile"][23]="General configuration";
$LANG['tracker']["profile"][24]="SNMP model";
$LANG['tracker']["profile"][25]="IP range";
$LANG['tracker']["profile"][26]="Agent";
$LANG['tracker']["profile"][27]="Agent information";
$LANG['tracker']["profile"][28]="Report";


$LANG['tracker']["setup"][2]="Thanks to put all in root entity (see all)";
$LANG['tracker']["setup"][3]="Plugin configuration".$title;
$LANG['tracker']["setup"][4]="Install plugin $title $version";
$LANG['tracker']["setup"][5]="Update plugin $title to version $version";
$LANG['tracker']["setup"][6]="Uninstall plugin $title $version";
$LANG['tracker']["setup"][8]="Attention, uninstalling this plugin is an irreversible step.<br> You will loose all datas.";
$LANG['tracker']["setup"][11]="Instructions";
$LANG['tracker']["setup"][12]="FAQ";
$LANG['tracker']["setup"][13]="Verification of PHP modules";
$LANG['tracker']["setup"][14]="SNMP extension of PHP isn't load";
$LANG['tracker']["setup"][15]="PHP/PECL runkit extension isn't load";

$LANG['tracker']["functionalities"][0]="Features";
$LANG['tracker']["functionalities"][1]="Add / Delete features";
$LANG['tracker']["functionalities"][2]="General configuration";
$LANG['tracker']["functionalities"][3]="SNMP";
$LANG['tracker']["functionalities"][4]="Connection";
$LANG['tracker']["functionalities"][5]="Server script";

$LANG['tracker']["functionalities"][10]="History activation";
$LANG['tracker']["functionalities"][11]="Connection module activation";
$LANG['tracker']["functionalities"][12]="SNMP networking module activation";
$LANG['tracker']["functionalities"][13]="SNMP peripheral module activation";
$LANG['tracker']["functionalities"][14]="SNMP phones module activation";
$LANG['tracker']["functionalities"][15]="SNMP printers module activation";
$LANG['tracker']["functionalities"][16]="SNMP authentification";
$LANG['tracker']["functionalities"][17]="Database";
$LANG['tracker']["functionalities"][18]="Files";
$LANG['tracker']["functionalities"][19]="Please configure the SNMP authentification in the setup of the plugin";
$LANG['tracker']["functionalities"][20]="Status of active devices";
$LANG['tracker']["functionalities"][21]="Retention of the historical interconnections between material in days (0 = infinity)";
$LANG['tracker']["functionalities"][22]="Retention of the historic changes to the state of ports (0 = infinity)";
$LANG['tracker']["functionalities"][23]="Retention of history unknown MAC addresses (0 = infinity)";
$LANG['tracker']["functionalities"][24]="Retention of historical errors SNMP (0 = infinity))";
$LANG['tracker']["functionalities"][25]="Retention of historical processes scripts (0 = infinity)";
$LANG['tracker']["functionalities"][26]="GLPI URL for agent";
$LANG['tracker']["functionalities"][27]="SSL only for agent";

$LANG['tracker']["functionalities"][30]="Status of active material";
$LANG['tracker']["functionalities"][31]="Management of cartridges and stock";
$LANG['tracker']["functionalities"][36]="Frequency of meter reading";

$LANG['tracker']["functionalities"][40]="Configuration";
$LANG['tracker']["functionalities"][41]="Status of active material";
$LANG['tracker']["functionalities"][42]="Switch";
$LANG['tracker']["functionalities"][43]="SNMP authentification";

$LANG['tracker']["functionalities"][50]="Number of simultaneous processes for the network discovery";
$LANG['tracker']["functionalities"][51]="Number of simultaneous processes for SNMP queries";
$LANG['tracker']["functionalities"][52]="Log files activation";
$LANG['tracker']["functionalities"][53]="Number of simultanous processes to be used by server script";

$LANG['tracker']["snmp"][0]="SNMP information of equipment";
$LANG['tracker']["snmp"][1]="General";
$LANG['tracker']["snmp"][2]="Cabling";
$LANG['tracker']["snmp"][2]="SNMP data";

$LANG['tracker']["snmp"][11]="Additional information";
$LANG['tracker']["snmp"][12]="Uptime";
$LANG['tracker']["snmp"][13]="CPU usage (in %)";
$LANG['tracker']["snmp"][14]="Memory usage (in %)";

$LANG['tracker']["snmp"][31]="Unable to retrieve SNMP info: This is not a switch !";
$LANG['tracker']["snmp"][32]="Unable to retrieve SNMP information: Hardware inactive !";
$LANG['tracker']["snmp"][33]="Unable to retrieve SNMP information: IP not specified in the basic !";
$LANG['tracker']["snmp"][34]="The switch is connected to a machine that is not filled !";

$LANG['tracker']["snmp"][41]="";
$LANG['tracker']["snmp"][42]="MTU";
$LANG['tracker']["snmp"][43]="Speed";
$LANG['tracker']["snmp"][44]="Internal status";
$LANG['tracker']["snmp"][45]="Last Change";
$LANG['tracker']["snmp"][46]="Number of bytes received";
$LANG['tracker']["snmp"][47]="Number of input errors";
$LANG['tracker']["snmp"][48]="Number of bytes sent";
$LANG['tracker']["snmp"][49]="Number of errors in reception";
$LANG['tracker']["snmp"][50]="Connection";
$LANG['tracker']["snmp"][51]="Duplex";
$LANG['tracker']["snmp"][52]="Date of last TRACKER inventory";
$LANG['tracker']["snmp"][53]="Last inventory";

$LANG['tracker']["snmpauth"][1]="Community";
$LANG['tracker']["snmpauth"][2]="User";
$LANG['tracker']["snmpauth"][3]="Authentication scheme";
$LANG['tracker']["snmpauth"][4]="Encryption protocol for authentication ";
$LANG['tracker']["snmpauth"][5]="Password";
$LANG['tracker']["snmpauth"][6]="Encryption protocol for data (write)";
$LANG['tracker']["snmpauth"][7]="Password (write)";

$LANG['tracker']["cron"][0]="Automatic reading meter";
$LANG['tracker']["cron"][1]="Activate the record";
$LANG['tracker']["cron"][2]="";
$LANG['tracker']["cron"][3]="Default";

$LANG['tracker']["errors"][0]="Errors";
$LANG['tracker']["errors"][1]="IP";
$LANG['tracker']["errors"][2]="Description";
$LANG['tracker']["errors"][3]="Date first problem";
$LANG['tracker']["errors"][4]="Date last problem";

$LANG['tracker']["errors"][10]="Inconsistent with the basic GLPI";
$LANG['tracker']["errors"][11]="Position unknown";
$LANG['tracker']["errors"][12]="Unknown IP";

$LANG['tracker']["errors"][20]="SNMP errors";
$LANG['tracker']["errors"][21]="Unable to retrieve information";

$LANG['tracker']["errors"][30]="Wiring error";
$LANG['tracker']["errors"][31]="Wiring problem";

$LANG['tracker']["errors"][101]="Timeout";
$LANG['tracker']["errors"][102]="No SNMP model assigned";
$LANG['tracker']["errors"][103]="No SNMP authentification assigned";

$LANG['tracker']["history"][0] = "Old";
$LANG['tracker']["history"][1] = "New";
$LANG['tracker']["history"][2] = "Disconnect";
$LANG['tracker']["history"][3] = "Connection";

$LANG['tracker']["prt_history"][0]="History and Statistics of printer counters";

$LANG['tracker']["prt_history"][10]="Printer counter statistics";
$LANG['tracker']["prt_history"][11]="day(s)";
$LANG['tracker']["prt_history"][12]="Total printed pages";
$LANG['tracker']["prt_history"][13]="Pages / day";

$LANG['tracker']["prt_history"][20]="History meter printer";
$LANG['tracker']["prt_history"][21]="Date";
$LANG['tracker']["prt_history"][22]="Meter";


$LANG['tracker']["cpt_history"][0]="History sessions";
$LANG['tracker']["cpt_history"][1]="Contact";
$LANG['tracker']["cpt_history"][2]="Computer";
$LANG['tracker']["cpt_history"][3]="User";
$LANG['tracker']["cpt_history"][4]="State";
$LANG['tracker']["cpt_history"][5]="Date";


$LANG['tracker']["type"][1]="Computer";
$LANG['tracker']["type"][2]="Switch";
$LANG['tracker']["type"][3]="Printer";

$LANG['tracker']["rules"][1]="Rules";

$LANG['tracker']["massiveaction"][1]="Assign SNMP model";
$LANG['tracker']["massiveaction"][2]="Assign SNMP authentification";

$LANG['tracker']["model_info"][1]="SNMP information";
$LANG['tracker']["model_info"][2]="SNMP version";
$LANG['tracker']["model_info"][3]="SNMP authentification";
$LANG['tracker']["model_info"][4]="SNMP models";
$LANG['tracker']["model_info"][5]="MIB management";
$LANG['tracker']["model_info"][6]="Edit SNMP model";
$LANG['tracker']["model_info"][7]="Create SNMP model";
$LANG['tracker']["model_info"][8]="Model already exists: import was not done";
$LANG['tracker']["model_info"][9]="Import completed successfully";
$LANG['tracker']["model_info"][10]="SNMP model import";
$LANG['tracker']["model_info"][11]="Activation";
$LANG['tracker']["model_info"][12]="Key for model discovery";


$LANG['tracker']["mib"][1]="MIB Label";
$LANG['tracker']["mib"][2]="Object";
$LANG['tracker']["mib"][3]="oid";
$LANG['tracker']["mib"][4]="add an oid...";
$LANG['tracker']["mib"][5]="oid list";
$LANG['tracker']["mib"][6]="Port Counters";
$LANG['tracker']["mib"][7]="Dynamic port (.x)";
$LANG['tracker']["mib"][8]="Linked fields";
$LANG['tracker']["mib"][9]="Vlan";

$LANG['tracker']["processes"][0]="History of script executions";
$LANG['tracker']["processes"][1]="PID";
$LANG['tracker']["processes"][2]="Status";
$LANG['tracker']["processes"][3]="Number of processes";
$LANG['tracker']["processes"][4]="Start date of execution";
$LANG['tracker']["processes"][5]="End date of execution";
$LANG['tracker']["processes"][6]="Network equipment queried";
$LANG['tracker']["processes"][7]="Printers queried";
$LANG['tracker']["processes"][8]="Ports queried";
$LANG['tracker']["processes"][9]="Errors";
$LANG['tracker']["processes"][10]="Time Script";
$LANG['tracker']["processes"][11]="added fields";
$LANG['tracker']["processes"][12]="SNMP errors";
$LANG['tracker']["processes"][13]="Unknown MAC";
$LANG['tracker']["processes"][14]="List of unknown MAC addresses";
$LANG['tracker']["processes"][15]="First PID";
$LANG['tracker']["processes"][16]="Last PID";
$LANG['tracker']["processes"][17]="Date of first detection";
$LANG['tracker']["processes"][18]="Date of last detection";
$LANG['tracker']["processes"][19]="History of agent executions";
$LANG['tracker']["processes"][20]="Reports and Statistics";
$LANG['tracker']["processes"][21]="Queried devices";
$LANG['tracker']["processes"][22]="Errors";
$LANG['tracker']["processes"][23]="Total duration of discovery";
$LANG['tracker']["processes"][24]="Total duration of query";

$LANG['tracker']["state"][0]="Computer start";
$LANG['tracker']["state"][1]="Computer stop";
$LANG['tracker']["state"][2]="User connection";
$LANG['tracker']["state"][3]="User disconnection";


$LANG['tracker']["mapping"][1]="networking > location";
$LANG['tracker']["mapping"][2]="networking > firmware";
$LANG['tracker']["mapping"][3]="networking > uptime";
$LANG['tracker']["mapping"][4]="networking > port > mtu";
$LANG['tracker']["mapping"][5]="networking > port > speed";
$LANG['tracker']["mapping"][6]="networking > port > internal status";
$LANG['tracker']["mapping"][7]="networking > ports > Last Change";
$LANG['tracker']["mapping"][8]="networking > port > number of bytes entered";
$LANG['tracker']["mapping"][9]="networking > port > number of bytes out";
$LANG['tracker']["mapping"][10]="networking > port > number of input errors";
$LANG['tracker']["mapping"][11]="networking > port > number of errors output";
$LANG['tracker']["mapping"][12]="networking > CPU usage";
$LANG['tracker']["mapping"][13]="networking > serial number";
$LANG['tracker']["mapping"][14]="networking > port > connection status";
$LANG['tracker']["mapping"][15]="networking > port > MAC address";
$LANG['tracker']["mapping"][16]="networking > port > name";
$LANG['tracker']["mapping"][17]="networking > model";
$LANG['tracker']["mapping"][18]="networking > ports > type";
$LANG['tracker']["mapping"][19]="networking > VLAN";
$LANG['tracker']["mapping"][20]="networking > name";
$LANG['tracker']["mapping"][21]="networking > total memory";
$LANG['tracker']["mapping"][22]="networking > free memory";
$LANG['tracker']["mapping"][23]="networking > port > port description";
$LANG['tracker']["mapping"][24]="printer > name";
$LANG['tracker']["mapping"][25]="printer > model";
$LANG['tracker']["mapping"][26]="printer > total memory";
$LANG['tracker']["mapping"][27]="printer > serial number";
$LANG['tracker']["mapping"][28]="printer > meter > total number of printed pages";
$LANG['tracker']["mapping"][29]="printer > meter > number of printed black and white pages";
$LANG['tracker']["mapping"][30]="printer > meter > number of printed color pages";
$LANG['tracker']["mapping"][31]="printer > meter > number of printed monochrome pages";
$LANG['tracker']["mapping"][32]="printer > meter > number of printed color pages";
$LANG['tracker']["mapping"][33]="networking > port > duplex type";
$LANG['tracker']["mapping"][34]="printer > consumables > black cartridge (%)";
$LANG['tracker']["mapping"][35]="printer > consumables > photo black cartridge (%)";
$LANG['tracker']["mapping"][36]="printer > consumables > cyan cartridge (%)";
$LANG['tracker']["mapping"][37]="printer > consumables > yellow cartridge (%)";
$LANG['tracker']["mapping"][38]="printer > consumables > magenta cartridge (%)";
$LANG['tracker']["mapping"][39]="printer > consumables > light cyan cartridge (%)";
$LANG['tracker']["mapping"][40]="printer > consumables > light magenta cartridge (%)";
$LANG['tracker']["mapping"][41]="printer > consumables > photoconductor (%)";
$LANG['tracker']["mapping"][42]="printer > consumables > black photoconductor (%)";
$LANG['tracker']["mapping"][43]="printer > consumables > color photoconductor (%)";
$LANG['tracker']["mapping"][44]="printer > consumables > cyan photoconductor (%)";
$LANG['tracker']["mapping"][45]="printer > consumables > yellow photoconductor (%)";
$LANG['tracker']["mapping"][46]="printer > consumables > magenta photoconductor (%)";
$LANG['tracker']["mapping"][47]="printer > consumables > black transfer unit (%)";
$LANG['tracker']["mapping"][48]="printer > consumables > cyan transfer unit (%)";
$LANG['tracker']["mapping"][49]="printer > consumables > yellow transfer unit (%)";
$LANG['tracker']["mapping"][50]="printer > consumables > magenta transfer unit (%)";
$LANG['tracker']["mapping"][51]="printer > consumables > waste bin (%)";
$LANG['tracker']["mapping"][52]="printer > consumables > four (%)";
$LANG['tracker']["mapping"][53]="printer > consumables > cleaning module (%)";
$LANG['tracker']["mapping"][54]="printer > meter > number of printed duplex pages";
$LANG['tracker']["mapping"][55]="printer > meter > nomber of scanned pages";
$LANG['tracker']["mapping"][56]="printer > location";
$LANG['tracker']["mapping"][57]="printer > port > name";
$LANG['tracker']["mapping"][58]="printer > port > MAC address";
$LANG['tracker']["mapping"][59]="printer > consumables > black cartridge (max ink)";
$LANG['tracker']["mapping"][60]="printer > consumables > black cartridge (remaining ink )";
$LANG['tracker']["mapping"][61]="printer > consumables > cyan cartridge (max ink)";
$LANG['tracker']["mapping"][62]="printer > consumables > cyan cartridge (remaining ink)";
$LANG['tracker']["mapping"][63]="printer > consumables > yellow cartridge (max ink)";
$LANG['tracker']["mapping"][64]="printer > consumables > yellow cartridge (remaining ink)";
$LANG['tracker']["mapping"][65]="printer > consumables > magenta cartridge (max ink)";
$LANG['tracker']["mapping"][66]="printer > consumables > magenta cartridge (remaining ink)";
$LANG['tracker']["mapping"][67]="printer > consumables > light cyan cartridge (max ink)";
$LANG['tracker']["mapping"][68]="printer > consumables > light cyan cartridge (remaining ink)";
$LANG['tracker']["mapping"][69]="printer > consumables > light magenta cartridge (max ink)";
$LANG['tracker']["mapping"][70]="printer > consumables > light magenta cartridge (remaining ink)";
$LANG['tracker']["mapping"][71]="printer > consumables > photoconductor (max ink)";
$LANG['tracker']["mapping"][72]="printer > consumables > photoconductor (remaining ink)";
$LANG['tracker']["mapping"][73]="printer > consumables > black photoconductor (max ink)";
$LANG['tracker']["mapping"][74]="printer > consumables > black photoconductor (remaining ink)";
$LANG['tracker']["mapping"][75]="printer > consumables > color photoconductor (max ink)";
$LANG['tracker']["mapping"][76]="printer > consumables > color photoconductor (remaining ink)";
$LANG['tracker']["mapping"][77]="printer > consumables > cyan photoconductor (max ink)";
$LANG['tracker']["mapping"][78]="printer > consumables > cyan photoconductor (remaining ink)";
$LANG['tracker']["mapping"][79]="printer > consumables > yellow photoconductor (max ink)";
$LANG['tracker']["mapping"][80]="printer > consumables > yellow photoconductor (remaining ink)";
$LANG['tracker']["mapping"][81]="printer > consumables > magenta photoconductor (max ink)";
$LANG['tracker']["mapping"][82]="printer > consumables > magenta photoconductor (remaining ink)";
$LANG['tracker']["mapping"][83]="printer > consumables > black transfer unit (max ink)";
$LANG['tracker']["mapping"][84]="printer > consumables > black transfer unit (remaining ink)";
$LANG['tracker']["mapping"][85]="printer > consumables > cyan transfer unit (max ink)";
$LANG['tracker']["mapping"][86]="printer > consumables > cyan transfer unit (remaining ink)";
$LANG['tracker']["mapping"][87]="printer > consumables > yellow transfer unit (max ink)";
$LANG['tracker']["mapping"][88]="printer > consumables > yellow transfer unit (remaining ink)";
$LANG['tracker']["mapping"][89]="printer > consumables > magenta transfer unit (max ink)";
$LANG['tracker']["mapping"][90]="printer > consumables > magenta transfer unit (remaining ink)";
$LANG['tracker']["mapping"][91]="printer > consumables > waste bin (max ink)";
$LANG['tracker']["mapping"][92]="printer > consumables > waste bin (remaining ink)";
$LANG['tracker']["mapping"][93]="printer > consumables > four (max ink)";
$LANG['tracker']["mapping"][94]="printer > consumables > four (remaining ink)";
$LANG['tracker']["mapping"][95]="printer > consumables > cleaning module (max ink)";
$LANG['tracker']["mapping"][96]="printer > consumables > cleaning module (remaining ink)";
$LANG['tracker']["mapping"][97]="printer > port > type";
$LANG['tracker']["mapping"][98]="printer > consumables > Maintenance kit (max)";
$LANG['tracker']["mapping"][99]="printer > consumables > Maintenance kit (remaining)";
$LANG['tracker']["mapping"][400]="printer > consumables > Maintenance kit (%)";
$LANG['tracker']["mapping"][401]="networking > CPU user";
$LANG['tracker']["mapping"][402]="networking > CPU system";
$LANG['tracker']["mapping"][403]="networking > contact";
$LANG['tracker']["mapping"][404]="networking > comments";
$LANG['tracker']["mapping"][405]="printer > contact";
$LANG['tracker']["mapping"][406]="printer > comments";
$LANG['tracker']["mapping"][407]="printer > port > IP address";
$LANG['tracker']["mapping"][408]="networking > port > numÃ©ro index";
$LANG['tracker']["mapping"][409]="networking > Adress CDP";
$LANG['tracker']["mapping"][410]="networking > Port CDP";
$LANG['tracker']["mapping"][411]="networking > Trunk port status";
$LANG['tracker']["mapping"][412]="networking > MAC address filters (dot1dTpFdbAddress)";
$LANG['tracker']["mapping"][413]="networking > Physical addresses in memory (ipNetToMediaPhysAddress)";
$LANG['tracker']["mapping"][414]="networking > instances de ports (dot1dTpFdbPort)";
$LANG['tracker']["mapping"][415]="networking > numÃ©ro de ports associÃ© ID du port (dot1dBasePortIfIndex)";
$LANG['tracker']["mapping"][416]="printer > port > index number";
$LANG['tracker']["mapping"][417]="networking > MAC address";
$LANG['tracker']["mapping"][418]="printer > Inventory number";
$LANG['tracker']["mapping"][419]="networking > Inventory number";
$LANG['tracker']["mapping"][420]="printer > manufacturer";
$LANG['tracker']["mapping"][421]="networking > IP addresses";

$LANG['tracker']["mapping"][101]="";
$LANG['tracker']["mapping"][102]="";
$LANG['tracker']["mapping"][103]="";
$LANG['tracker']["mapping"][104]="MTU";
$LANG['tracker']["mapping"][105]="Speed";
$LANG['tracker']["mapping"][106]="Internal status";
$LANG['tracker']["mapping"][107]="Last Change";
$LANG['tracker']["mapping"][108]="Number of received bytes";
$LANG['tracker']["mapping"][109]="Number of outgoing bytes";
$LANG['tracker']["mapping"][110]="Number of input errors";
$LANG['tracker']["mapping"][111]="Number of output errors";
$LANG['tracker']["mapping"][112]="CPU usage";
$LANG['tracker']["mapping"][113]="";
$LANG['tracker']["mapping"][114]="Connection";
$LANG['tracker']["mapping"][115]="Internal MAC address";
$LANG['tracker']["mapping"][116]="Name";
$LANG['tracker']["mapping"][117]="Model";
$LANG['tracker']["mapping"][118]="Type";
$LANG['tracker']["mapping"][119]="VLAN";
$LANG['tracker']["mapping"][128]="Total number of printed pages";
$LANG['tracker']["mapping"][129]="Number of printed black and white pages";
$LANG['tracker']["mapping"][130]="Number of printed color pages";
$LANG['tracker']["mapping"][131]="Number of printed monochrome pages";
$LANG['tracker']["mapping"][132]="Number of printed color pages";
$LANG['tracker']["mapping"][134]="Black cartridge";
$LANG['tracker']["mapping"][135]="Photo black cartridge";
$LANG['tracker']["mapping"][136]="Cyan cartridge";
$LANG['tracker']["mapping"][137]="Yellow cartridge";
$LANG['tracker']["mapping"][138]="Magenta cartridge";
$LANG['tracker']["mapping"][139]="Light cyan cartridge";
$LANG['tracker']["mapping"][140]="Light magenta cartridge";
$LANG['tracker']["mapping"][141]="Photoconductor";
$LANG['tracker']["mapping"][142]="Black photoconductor";
$LANG['tracker']["mapping"][143]="Color photoconductor";
$LANG['tracker']["mapping"][144]="Cyan photoconductor";
$LANG['tracker']["mapping"][145]="Yellow photoconductor";
$LANG['tracker']["mapping"][146]="Magenta photoconductor";
$LANG['tracker']["mapping"][147]="Black transfer unit";
$LANG['tracker']["mapping"][148]="Cyan transfer unit";
$LANG['tracker']["mapping"][149]="Yellow transfer unit";
$LANG['tracker']["mapping"][150]="Magenta transfer unit";
$LANG['tracker']["mapping"][151]="Waste bin";
$LANG['tracker']["mapping"][152]="Four";
$LANG['tracker']["mapping"][153]="Cleaning module";
$LANG['tracker']["mapping"][154]="Number of pages printed duplex";
$LANG['tracker']["mapping"][155]="Number of scanned pages";
$LANG['tracker']["mapping"][156]="Maintenance kit";

$LANG['tracker']["printer"][0]="pages";


$LANG['tracker']["menu"][1]="Agent configuration";
$LANG['tracker']["menu"][2]="IP range configuration";
$LANG['tracker']["menu"][3]="Menu";

$LANG['tracker']["menu"][0]="Information about discovered devices";

$LANG['tracker']["buttons"][0]="Discover";

$LANG['tracker']["discovery"][0]="IP range to scan";
$LANG['tracker']["discovery"][1]="Discovered devices";
$LANG['tracker']["discovery"][2]="Activation in the script automatically";
$LANG['tracker']["discovery"][3]="Discover";
$LANG['tracker']["discovery"][4]="Serial number";
$LANG['tracker']["discovery"][5]="Number of imported devices";
$LANG['tracker']["discovery"][6]="Primary criteria for existence";
$LANG['tracker']["discovery"][7]="Secondary criteria for existence ";
$LANG['tracker']["discovery"][8]="If a device returns empty fields on first ciriteria, second one will be used.";

$LANG['tracker']["rangeip"][0]="Start of IP range";
$LANG['tracker']["rangeip"][1]="End of IP range";
$LANG['tracker']["rangeip"][2]="IP Ranges";
$LANG['tracker']["rangeip"][3]="Interrogation";


$LANG['tracker']["agents"][0]="SNMP Agent";
$LANG['tracker']["agents"][2]="Number of threads used by core for querying devices";
$LANG['tracker']["agents"][3]="Number of threads used by core for network discovery";
$LANG['tracker']["agents"][4]="Last scan";
$LANG['tracker']["agents"][5]="Agent version";
$LANG['tracker']["agents"][6]="Active";
$LANG['tracker']["agents"][7]="Export agent configuration";
$LANG['tracker']["agents"][8]="Fragments en Ko";
$LANG['tracker']["agents"][9]="Advanced options";
$LANG['tracker']["agents"][10]="Number of core(s) (CPU) used for querying devices";
$LANG['tracker']["agents"][11]="Number of core(s) (CPU) used for network discovery";

?>