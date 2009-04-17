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

$title="Tracker";
$version="2.0.0";

$LANGTRACKER["title"][0]="$title";
$LANGTRACKER["title"][1]="SNMP informations";
$LANGTRACKER["title"][2]="connections history";
$LANGTRACKER["title"][3]="[Trk] Errors";
$LANGTRACKER["title"][4]="[Trk] Cron";

$LANGTRACKER["profile"][0]="Rights management";
$LANGTRACKER["profile"][1]="$title"; //interface

$LANGTRACKER["profile"][10]="Profiles configured";
$LANGTRACKER["profile"][11]="Computers history";
$LANGTRACKER["profile"][12]="Printers history";
$LANGTRACKER["profile"][13]="Printers informations";
$LANGTRACKER["profile"][14]="Network informations";
$LANGTRACKER["profile"][15]="Errors";

$LANGTRACKER["profile"][16]="SNMP networking";
$LANGTRACKER["profile"][17]="SNMP peripheral";
$LANGTRACKER["profile"][18]="SNMP printers";
$LANGTRACKER["profile"][19]="Models SNMP";
$LANGTRACKER["profile"][20]="Authentification SNMP";
$LANGTRACKER["profile"][21]="Scripts informations";
$LANGTRACKER["profile"][22]="Networking discover";
$LANGTRACKER["profile"][23]="General configuration";
$LANGTRACKER["profile"][24]="SNMP model";


$LANGTRACKER["setup"][2]="Thanks to put in racine entity (see all)";
$LANGTRACKER["setup"][3]="Configuration of plugin ".$title;
$LANGTRACKER["setup"][4]="Install plugin $title $version";
$LANGTRACKER["setup"][5]="Update plugin $title to version $version";
$LANGTRACKER["setup"][6]="Uninstall plugin $title $version";
$LANGTRACKER["setup"][8]="Attention, uninstallation of plugin is irreversible.<br> You loose all datas.";
$LANGTRACKER["setup"][11]="Instructions";
$LANGTRACKER["setup"][12]="FAQ";
$LANGTRACKER["setup"][13]="Verification of PHP modules";
$LANGTRACKER["setup"][14]="SNMP extension of PHP isn't load";
$LANGTRACKER["setup"][15]="PHP/PECL runkit extension isn't load";

$LANGTRACKER["functionalities"][0]="Features";
$LANGTRACKER["functionalities"][1]="Add / Delete features";
$LANGTRACKER["functionalities"][2]="General configuration";
$LANGTRACKER["functionalities"][3]="SNMP";
$LANGTRACKER["functionalities"][4]="Connection";
$LANGTRACKER["functionalities"][5]="Serser script";

$LANGTRACKER["functionalities"][10]="History activation";
$LANGTRACKER["functionalities"][11]="Connection module activation";
$LANGTRACKER["functionalities"][12]="SNMP networking module activation";
$LANGTRACKER["functionalities"][13]="SNMP peripheral module activation";
$LANGTRACKER["functionalities"][14]="SNMP phones module activation";
$LANGTRACKER["functionalities"][15]="SNMP printers module activation";
$LANGTRACKER["functionalities"][16]="SNMP authentification";
$LANGTRACKER["functionalities"][17]="Database";
$LANGTRACKER["functionalities"][18]="Files";
$LANGTRACKER["functionalities"][19]="Please configure the SNMP authentification in the setup of the plugin";
$LANGTRACKER["functionalities"][20]="Status of active devices";
$LANGTRACKER["functionalities"][21]="Retention of the historical interconnections between material in days (0 = infinity)";
$LANGTRACKER["functionalities"][22]="Retention of the historic changes to the state of ports (0 = infinity)";
$LANGTRACKER["functionalities"][23]="Retention of history unknown MAC addresses (0 = infinity)";
$LANGTRACKER["functionalities"][24]="Retention of historical errors SNMP (0 = infinity))";
$LANGTRACKER["functionalities"][25]="Retention of historical processes scripts (0 = infinity)";
$LANGTRACKER["functionalities"][26]="GLPI URL for agent";

$LANGTRACKER["functionalities"][30]="Status of active material";
$LANGTRACKER["functionalities"][31]="Management cartridges and stock";
$LANGTRACKER["functionalities"][36]="Frequency of meter reading";

$LANGTRACKER["functionalities"][40]="Configuration";
$LANGTRACKER["functionalities"][41]="Status of active material";
$LANGTRACKER["functionalities"][42]="Switch";
$LANGTRACKER["functionalities"][43]="SNMP authentification";

$LANGTRACKER["functionalities"][50]="Number of simultaneous process for the discovery network";
$LANGTRACKER["functionalities"][51]="Number of simultaneous process for querying SNMP";
$LANGTRACKER["functionalities"][52]="Log files activation";
$LANGTRACKER["functionalities"][53]="Number of simultaneous process for post-treatment server script";

$LANGTRACKER["snmp"][0]="SNMP information equipment";
$LANGTRACKER["snmp"][1]="Général";
$LANGTRACKER["snmp"][2]="Cabling";
$LANGTRACKER["snmp"][2]="SNMP data";

$LANGTRACKER["snmp"][11]="Additional information";
$LANGTRACKER["snmp"][12]="Uptime";
$LANGTRACKER["snmp"][13]="CPU usage (in %)";
$LANGTRACKER["snmp"][14]="Memory usage (in %)";

$LANGTRACKER["snmp"][31]="Unable to retrieve SNMP info: This is not a switch !";
$LANGTRACKER["snmp"][32]="Unable to retrieve information SNMP: Hardware inactive !";
$LANGTRACKER["snmp"][33]="Unable to retrieve SNMP information: IP not specified in the basic !";
$LANGTRACKER["snmp"][34]="The switch is connected to which the machine is not filled !";

$LANGTRACKER["snmp"][41]="";
$LANGTRACKER["snmp"][42]="MTU";
$LANGTRACKER["snmp"][43]="Speed";
$LANGTRACKER["snmp"][44]="Internal status";
$LANGTRACKER["snmp"][45]="Last Change";
$LANGTRACKER["snmp"][46]="Number of bytes received";
$LANGTRACKER["snmp"][47]="Number of input errors";
$LANGTRACKER["snmp"][48]="Number of bytes sent";
$LANGTRACKER["snmp"][49]="Number of errors in reception";
$LANGTRACKER["snmp"][50]="Connection";
$LANGTRACKER["snmp"][51]="Duplex";
$LANGTRACKER["snmp"][52]="Date of last TRACKER inventory";
$LANGTRACKER["snmp"][53]="Last inventory";

$LANGTRACKER["snmpauth"][1]="Community";
$LANGTRACKER["snmpauth"][2]="User";
$LANGTRACKER["snmpauth"][3]="Authentication scheme";
$LANGTRACKER["snmpauth"][4]="Encryption protocol for authentication ";
$LANGTRACKER["snmpauth"][5]="Password";
$LANGTRACKER["snmpauth"][6]="Encryption protocol for data (write)";
$LANGTRACKER["snmpauth"][7]="Password (write)";

$LANGTRACKER["cron"][0]="Automatic reading meter";
$LANGTRACKER["cron"][1]="Activate the record";
$LANGTRACKER["cron"][2]="";
$LANGTRACKER["cron"][3]="Default";

$LANGTRACKER["errors"][0]="Errors";
$LANGTRACKER["errors"][1]="IP";
$LANGTRACKER["errors"][2]="Description";
$LANGTRACKER["errors"][3]="Date 1st problem";
$LANGTRACKER["errors"][4]="Date last problem";

$LANGTRACKER["errors"][10]="Inconsistent with the basic GLPI";
$LANGTRACKER["errors"][11]="Position unknown";
$LANGTRACKER["errors"][12]="Unknown IP";

$LANGTRACKER["errors"][20]="SNMP errors";
$LANGTRACKER["errors"][21]="Unable to retrieve information";

$LANGTRACKER["errors"][30]="Wiring error";
$LANGTRACKER["errors"][31]="Wiring problem";

$LANGTRACKER["errors"][101]="Timeout";
$LANGTRACKER["errors"][102]="No SNMP model assigned";
$LANGTRACKER["errors"][103]="No SNMP authentification assigned";

$LANGTRACKER["history"][0] = "Old";
$LANGTRACKER["history"][1] = "New";

$LANGTRACKER["prt_history"][0]="History and Statistics counters printer";

$LANGTRACKER["prt_history"][10]="Statistics counters printer";
$LANGTRACKER["prt_history"][11]="day(s)";
$LANGTRACKER["prt_history"][12]="Total printed pages";
$LANGTRACKER["prt_history"][13]="Pages / day";

$LANGTRACKER["prt_history"][20]="History meter printer";
$LANGTRACKER["prt_history"][21]="Date";
$LANGTRACKER["prt_history"][22]="Meter";


$LANGTRACKER["cpt_history"][0]="History sessions";
$LANGTRACKER["cpt_history"][1]="Contact";
$LANGTRACKER["cpt_history"][2]="Computer";
$LANGTRACKER["cpt_history"][3]="User";
$LANGTRACKER["cpt_history"][4]="State";
$LANGTRACKER["cpt_history"][5]="Date";


$LANGTRACKER["type"][1]="Computer";
$LANGTRACKER["type"][2]="Switch";
$LANGTRACKER["type"][3]="Printer";

$LANGTRACKER["rules"][1]="Rules";

$LANGTRACKER["massiveaction"][1]="Assign SNMP model";
$LANGTRACKER["massiveaction"][2]="Assign SNMP authentification";

$LANGTRACKER["model_info"][1]="SNMP informations";
$LANGTRACKER["model_info"][2]="SNMP version";
$LANGTRACKER["model_info"][3]="SNMP authentification";
$LANGTRACKER["model_info"][4]="SNMP models";
$LANGTRACKER["model_info"][5]="MIB management";
$LANGTRACKER["model_info"][6]="Editing SNMP models";
$LANGTRACKER["model_info"][7]="Creation SNMP models";
$LANGTRACKER["model_info"][8]="Model already exists: import was not made";
$LANGTRACKER["model_info"][9]="Import completed successfully";
$LANGTRACKER["model_info"][10]="Model importation";
$LANGTRACKER["model_info"][11]="Activation";
$LANGTRACKER["model_info"][12]="Model key for discovery";

$LANGTRACKER["mib"][1]="Label MIB";
$LANGTRACKER["mib"][2]="Objet";
$LANGTRACKER["mib"][3]="oid";
$LANGTRACKER["mib"][4]="add an oid...";
$LANGTRACKER["mib"][5]="oid list";
$LANGTRACKER["mib"][6]="Counter ports";
$LANGTRACKER["mib"][7]="Dynamic port (.x)";
$LANGTRACKER["mib"][8]="Link fields";
$LANGTRACKER["mib"][9]="vlan";

$LANGTRACKER["processes"][0]="Information on running scripts";
$LANGTRACKER["processes"][1]="PID";
$LANGTRACKER["processes"][2]="Status";
$LANGTRACKER["processes"][3]="Nomber of process";
$LANGTRACKER["processes"][4]="Start date of execution";
$LANGTRACKER["processes"][5]="End date of execution";
$LANGTRACKER["processes"][6]="Network equipment queried";
$LANGTRACKER["processes"][7]="Printers queried";
$LANGTRACKER["processes"][8]="Ports queried";
$LANGTRACKER["processes"][9]="Errors";
$LANGTRACKER["processes"][10]="Time Script";
$LANGTRACKER["processes"][11]="added fields";
$LANGTRACKER["processes"][12]="SNMP errors";
$LANGTRACKER["processes"][13]="Unknown MAC";
$LANGTRACKER["processes"][14]="List of MAC address unknown ";
$LANGTRACKER["processes"][15]="First PID";
$LANGTRACKER["processes"][16]="Last PID";
$LANGTRACKER["processes"][17]="Date of first detection";
$LANGTRACKER["processes"][18]="Date of last detection";
$LANGTRACKER["processes"][19]="Informations on running agents";
$LANGTRACKER["processes"][20]="Report / statistics";
$LANGTRACKER["processes"][21]="Queried devices";
$LANGTRACKER["processes"][22]="Errors";
$LANGTRACKER["processes"][23]="Total duration of discovery";
$LANGTRACKER["processes"][24]="Total duration of query";

$LANGTRACKER["state"][0]="Computer start";
$LANGTRACKER["state"][1]="Computer stop";
$LANGTRACKER["state"][2]="User connection";
$LANGTRACKER["state"][3]="User disconnection";


$LANGTRACKER["mapping"][1]="networking > location";
$LANGTRACKER["mapping"][2]="networking > firmware";
$LANGTRACKER["mapping"][3]="networking > uptime";
$LANGTRACKER["mapping"][4]="networking > port > mtu";
$LANGTRACKER["mapping"][5]="networking > port > speed";
$LANGTRACKER["mapping"][6]="networking > port > internal status";
$LANGTRACKER["mapping"][7]="networking > ports > Last Change";
$LANGTRACKER["mapping"][8]="networking > port > number of bytes entered";
$LANGTRACKER["mapping"][9]="networking > port > number of bytes out";
$LANGTRACKER["mapping"][10]="networking > port > number of input errors";
$LANGTRACKER["mapping"][11]="networking > port > number of errors output";
$LANGTRACKER["mapping"][12]="networking > CPU usage";
$LANGTRACKER["mapping"][13]="networking > serial number";
$LANGTRACKER["mapping"][14]="networking > port > connection status";
$LANGTRACKER["mapping"][15]="networking > port > MAC address";
$LANGTRACKER["mapping"][16]="networking > port > name";
$LANGTRACKER["mapping"][17]="networking > model";
$LANGTRACKER["mapping"][18]="networking > ports > type";
$LANGTRACKER["mapping"][19]="networking > VLAN";
$LANGTRACKER["mapping"][20]="networking > name";
$LANGTRACKER["mapping"][21]="networking > total memory";
$LANGTRACKER["mapping"][22]="networking > free memory";
$LANGTRACKER["mapping"][23]="networking > port > port description";
$LANGTRACKER["mapping"][24]="printer > name";
$LANGTRACKER["mapping"][25]="printer > model";
$LANGTRACKER["mapping"][26]="printer > total memory";
$LANGTRACKER["mapping"][27]="printer > serial number";
$LANGTRACKER["mapping"][28]="printer > meter > total number of printed pages";
$LANGTRACKER["mapping"][29]="printer > meter > number of printed black and white pages";
$LANGTRACKER["mapping"][30]="printer > meter > number of printed color pages";
$LANGTRACKER["mapping"][31]="printer > meter > number of printed monochrome pages";
$LANGTRACKER["mapping"][32]="printer > meter > number of printed color pages";
$LANGTRACKER["mapping"][33]="networking > port > duplex type";
$LANGTRACKER["mapping"][34]="printer > consumables > black cartridge (%)";
$LANGTRACKER["mapping"][35]="printer > consumables > photo black cartridge (%)";
$LANGTRACKER["mapping"][36]="printer > consumables > cyan cartridge (%)";
$LANGTRACKER["mapping"][37]="printer > consumables > yellow cartridge (%)";
$LANGTRACKER["mapping"][38]="printer > consumables > magenta cartridge (%)";
$LANGTRACKER["mapping"][39]="printer > consumables > light cyan cartridge (%)";
$LANGTRACKER["mapping"][40]="printer > consumables > light magenta cartridge (%)";
$LANGTRACKER["mapping"][41]="printer > consumables > photoconductor (%)";
$LANGTRACKER["mapping"][42]="printer > consumables > black photoconductor (%)";
$LANGTRACKER["mapping"][43]="printer > consumables > color photoconductor (%)";
$LANGTRACKER["mapping"][44]="printer > consumables > cyan photoconductor (%)";
$LANGTRACKER["mapping"][45]="printer > consumables > yellow photoconductor (%)";
$LANGTRACKER["mapping"][46]="printer > consumables > magenta photoconductor (%)";
$LANGTRACKER["mapping"][47]="printer > consumables > black transfer unit (%)";
$LANGTRACKER["mapping"][48]="printer > consumables > cyan transfer unit (%)";
$LANGTRACKER["mapping"][49]="printer > consumables > yellow transfer unit (%)";
$LANGTRACKER["mapping"][50]="printer > consumables > magenta transfer unit (%)";
$LANGTRACKER["mapping"][51]="printer > consumables > waste bin (%)";
$LANGTRACKER["mapping"][52]="printer > consumables > four (%)";
$LANGTRACKER["mapping"][53]="printer > consumables > cleaning module (%)";
$LANGTRACKER["mapping"][54]="printer > meter > number of printed duplex pages";
$LANGTRACKER["mapping"][55]="printer > meter > nomber of scanned pages";
$LANGTRACKER["mapping"][56]="printer > location";
$LANGTRACKER["mapping"][57]="printer > port > name";
$LANGTRACKER["mapping"][58]="printer > port > MAC address";
$LANGTRACKER["mapping"][59]="printer > consumables > black cartridge (max ink)";
$LANGTRACKER["mapping"][60]="printer > consumables > black cartridge (remaining ink )";
$LANGTRACKER["mapping"][61]="printer > consumables > cyan cartridge (max ink)";
$LANGTRACKER["mapping"][62]="printer > consumables > cyan cartridge (remaining ink)";
$LANGTRACKER["mapping"][63]="printer > consumables > yellow cartridge (max ink)";
$LANGTRACKER["mapping"][64]="printer > consumables > yellow cartridge (remaining ink)";
$LANGTRACKER["mapping"][65]="printer > consumables > magenta cartridge (max ink)";
$LANGTRACKER["mapping"][66]="printer > consumables > magenta cartridge (remaining ink)";
$LANGTRACKER["mapping"][67]="printer > consumables > light cyan cartridge (max ink)";
$LANGTRACKER["mapping"][68]="printer > consumables > light cyan cartridge (remaining ink)";
$LANGTRACKER["mapping"][69]="printer > consumables > light magenta cartridge (max ink)";
$LANGTRACKER["mapping"][70]="printer > consumables > light magenta cartridge (remaining ink)";
$LANGTRACKER["mapping"][71]="printer > consumables > photoconductor (max ink)";
$LANGTRACKER["mapping"][72]="printer > consumables > photoconductor (remaining ink)";
$LANGTRACKER["mapping"][73]="printer > consumables > black photoconductor (max ink)";
$LANGTRACKER["mapping"][74]="printer > consumables > black photoconductor (remaining ink)";
$LANGTRACKER["mapping"][75]="printer > consumables > color photoconductor (max ink)";
$LANGTRACKER["mapping"][76]="printer > consumables > color photoconductor (remaining ink)";
$LANGTRACKER["mapping"][77]="printer > consumables > cyan photoconductor (max ink)";
$LANGTRACKER["mapping"][78]="printer > consumables > cyan photoconductor (remaining ink)";
$LANGTRACKER["mapping"][79]="printer > consumables > yellow photoconductor (max ink)";
$LANGTRACKER["mapping"][80]="printer > consumables > yellow photoconductor (remaining ink)";
$LANGTRACKER["mapping"][81]="printer > consumables > magenta photoconductor (max ink)";
$LANGTRACKER["mapping"][82]="printer > consumables > magenta photoconductor (remaining ink)";
$LANGTRACKER["mapping"][83]="printer > consumables > black transfer unit (max ink)";
$LANGTRACKER["mapping"][84]="printer > consumables > black transfer unit (remaining ink)";
$LANGTRACKER["mapping"][85]="printer > consumables > cyan transfer unit (max ink)";
$LANGTRACKER["mapping"][86]="printer > consumables > cyan transfer unit (remaining ink)";
$LANGTRACKER["mapping"][87]="printer > consumables > yellow transfer unit (max ink)";
$LANGTRACKER["mapping"][88]="printer > consumables > yellow transfer unit (remaining ink)";
$LANGTRACKER["mapping"][89]="printer > consumables > magenta transfer unit (max ink)";
$LANGTRACKER["mapping"][90]="printer > consumables > magenta transfer unit (remaining ink)";
$LANGTRACKER["mapping"][91]="printer > consumables > waste bin (max ink)";
$LANGTRACKER["mapping"][92]="printer > consumables > waste bin (remaining ink)";
$LANGTRACKER["mapping"][93]="printer > consumables > four (max ink)";
$LANGTRACKER["mapping"][94]="printer > consumables > four (remaining ink)";
$LANGTRACKER["mapping"][95]="printer > consumables > cleaning module (max ink)";
$LANGTRACKER["mapping"][96]="printer > consumables > cleaning module (remaining ink)";
$LANGTRACKER["mapping"][97]="printer > port > type";
$LANGTRACKER["mapping"][98]="printer > consumables > Maintenance kit (max)";
$LANGTRACKER["mapping"][99]="printer > consumables > Maintenance kit (remaining)";
$LANGTRACKER["mapping"][400]="printer > consumables > Maintenance kit (%)";
$LANGTRACKER["mapping"][401]="networking > CPU user";
$LANGTRACKER["mapping"][402]="networking > CPU système";
$LANGTRACKER["mapping"][403]="networking > contact";
$LANGTRACKER["mapping"][404]="networking > comments";
$LANGTRACKER["mapping"][405]="printer > contact";
$LANGTRACKER["mapping"][406]="printer > comments";
$LANGTRACKER["mapping"][407]="printer > port > IP address";
$LANGTRACKER["mapping"][408]="networking > port > index number";
$LANGTRACKER["mapping"][409]="networking > CDP address";
$LANGTRACKER["mapping"][410]="networking > CDP port";
$LANGTRACKER["mapping"][411]="networking > trunk port status";
$LANGTRACKER["mapping"][412]="networking > mac addresses filtered (dot1dTpFdbAddress)";
$LANGTRACKER["mapping"][413]="networking > mac addresses memorised (ipNetToMediaPhysAddress)";
$LANGTRACKER["mapping"][414]="networking > ports instances (dot1dTpFdbPort)";
$LANGTRACKER["mapping"][415]="networking > port number associed to port ID (dot1dBasePortIfIndex)";
$LANGTRACKER["mapping"][416]="printer > port > index number";
$LANGTRACKER["mapping"][417]="networking > MAC address";

$LANGTRACKER["mapping"][101]="";
$LANGTRACKER["mapping"][102]="";
$LANGTRACKER["mapping"][103]="";
$LANGTRACKER["mapping"][104]="MTU";
$LANGTRACKER["mapping"][105]="Speed";
$LANGTRACKER["mapping"][106]="Internal status";
$LANGTRACKER["mapping"][107]="Last Change";
$LANGTRACKER["mapping"][108]="Number of bytes entered";
$LANGTRACKER["mapping"][109]="Number of bytes out";
$LANGTRACKER["mapping"][110]="Number of input errors";
$LANGTRACKER["mapping"][111]="Number of errors output";
$LANGTRACKER["mapping"][112]="CPU usage";
$LANGTRACKER["mapping"][113]="";
$LANGTRACKER["mapping"][114]="Connection";
$LANGTRACKER["mapping"][115]="Internal MAC address";
$LANGTRACKER["mapping"][116]="Name";
$LANGTRACKER["mapping"][117]="Model";
$LANGTRACKER["mapping"][118]="Type";
$LANGTRACKER["mapping"][119]="VLAN";
$LANGTRACKER["mapping"][128]="Total number of printed pages";
$LANGTRACKER["mapping"][129]="Number of printed black and white pages";
$LANGTRACKER["mapping"][130]="Number of printed color pages";
$LANGTRACKER["mapping"][131]="Number of printed monochrome pages";
$LANGTRACKER["mapping"][132]="Number of printed color pages";
$LANGTRACKER["mapping"][134]="Black cartridge";
$LANGTRACKER["mapping"][135]="Photo black cartridge";
$LANGTRACKER["mapping"][136]="Cyan cartridge";
$LANGTRACKER["mapping"][137]="Yellow cartridge";
$LANGTRACKER["mapping"][138]="Magenta cartridge";
$LANGTRACKER["mapping"][139]="Light cyan cartridge";
$LANGTRACKER["mapping"][140]="Light magenta cartridge";
$LANGTRACKER["mapping"][141]="Photoconductor";
$LANGTRACKER["mapping"][142]="Black photoconductor";
$LANGTRACKER["mapping"][143]="Color photoconductor";
$LANGTRACKER["mapping"][144]="Cyan photoconductor";
$LANGTRACKER["mapping"][145]="Yellow photoconductor";
$LANGTRACKER["mapping"][146]="Magenta photoconductor";
$LANGTRACKER["mapping"][147]="Black transfer unit";
$LANGTRACKER["mapping"][148]="Cyan transfer unit";
$LANGTRACKER["mapping"][149]="Yellow transfer unit";
$LANGTRACKER["mapping"][150]="Magenta transfer unit";
$LANGTRACKER["mapping"][151]="Waste bin";
$LANGTRACKER["mapping"][152]="Four";
$LANGTRACKER["mapping"][153]="Cleaning module";
$LANGTRACKER["mapping"][154]="Number of printed duplex pages";
$LANGTRACKER["mapping"][155]="Nomber of scanned pages";
$LANGTRACKER["mapping"][156]="Maintenance kit";

$LANGTRACKER["printer"][0]="pages";
$LANGTRACKER["menu"][1]="Agents management";
$LANGTRACKER["menu"][2]="IP range";
$LANGTRACKER["menu"][3]="Menu";

$LANGTRACKER["menu"][0]="Network devices discover";

$LANGTRACKER["buttons"][0]="Discover";

$LANGTRACKER["discovery"][0]="Ip range to scan";
$LANGTRACKER["discovery"][1]="Discovered devices";
$LANGTRACKER["discovery"][2]="Activation in the script automatically";
$LANGTRACKER["discovery"][3]="Discover";
$LANGTRACKER["discovery"][4]="Serial number";
$LANGTRACKER["discovery"][5]="Number of imported devices";
$LANGTRACKER["discovery"][6]="Criteria for existence";
$LANGTRACKER["discovery"][7]="Secondary criteria for existence ";
$LANGTRACKER["discovery"][8]="if all criteria for existence are fields empty on device, you can select secondary criteria.";

$LANGTRACKER["rangeip"][0]="Start IP range";
$LANGTRACKER["rangeip"][1]="End IP range";
$LANGTRACKER["rangeip"][2]="IP range";
$LANGTRACKER["rangeip"][3]="Query";

$LANGTRACKER["agents"][0]="SNMP agent";
$LANGTRACKER["agents"][2]="Query threads (by core)";
$LANGTRACKER["agents"][3]="Discovery threads (by core)";
$LANGTRACKER["agents"][4]="Last ascent";
$LANGTRACKER["agents"][5]="Agent version";
$LANGTRACKER["agents"][6]="Lock";
$LANGTRACKER["agents"][7]="Export agent config";
$LANGTRACKER["agents"][8]="Fragments in Ko";
$LANGTRACKER["agents"][9]="Advanced options";
$LANGTRACKER["agents"][10]="Query core (CPU)";
$LANGTRACKER["agents"][11]="Discovery core (CPU)";
?>