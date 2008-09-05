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
$version="0.1";

$LANGTRACKER["title"][0]="$title";
$LANGTRACKER["title"][1]="[Trk] Infos";
$LANGTRACKER["title"][2]="[Trk] History";
$LANGTRACKER["title"][3]="[Trk] Errors";
$LANGTRACKER["title"][4]="[Trk] Cron";


$LANGTRACKER["profile"][0]="Rights management";
$LANGTRACKER["profile"][1]="$title"; //interface

$LANGTRACKER["profile"][10]="List of profiles already configured";
$LANGTRACKER["profile"][11]="Historique Ordinateurs";
$LANGTRACKER["profile"][12]="Historique Imprimantes";
$LANGTRACKER["profile"][13]="Infos Imprimantes";
$LANGTRACKER["profile"][14]="Infos Réseau";
$LANGTRACKER["profile"][15]="Erreurs courantes";


$LANGTRACKER["setup"][2]="Please, place yourself on root entity (see all)";
$LANGTRACKER["setup"][3]="Plugin configuration".$title;
$LANGTRACKER["setup"][4]="Install plugin $title $version";
$LANGTRACKER["setup"][6]="Uninstall plugin $title $version";
$LANGTRACKER["setup"][8]="Warning, the uninstallation of the plugin is irreversible.<br> You will lose all the data.";
$LANGTRACKER["setup"][11]="Instructions";
$LANGTRACKER["setup"][12]="FAQ";


$LANGTRACKER["functionalities"][0]="Functionnalities";
$LANGTRACKER["functionalities"][1]="Add / delete functionnalities";

$LANGTRACKER["functionalities"][10]="Computer Connections";
$LANGTRACKER["functionalities"][11]="Wire control";
$LANGTRACKER["functionalities"][12]="Checking of the switch and of the connection port.";
$LANGTRACKER["functionalities"][13]="Activation of  the history";
$LANGTRACKER["functionalities"][14]="Update contact field";
$LANGTRACKER["functionalities"][15]="Update GLPI user field";

$LANGTRACKER["functionalities"][20]="Printers";
$LANGTRACKER["functionalities"][21]="Statement of printing counters";
$LANGTRACKER["functionalities"][22]="Default value";
$LANGTRACKER["functionalities"][23]="When the counter statement of a specific printer is set to default, this is this value which will be effective.";

$LANGTRACKER["functionalities"][30]="History cleaning";
$LANGTRACKER["functionalities"][31]="Activate cleaning";
$LANGTRACKER["functionalities"][32]="Cleaning depth (by days)";

$LANGTRACKER["functionalities"][40]="Configuration";
$LANGTRACKER["functionalities"][41]="State of active material";
$LANGTRACKER["functionalities"][42]="Switch";


$LANGTRACKER["snmp"][0]="SNMP info about material";
$LANGTRACKER["snmp"][1]="General";
$LANGTRACKER["snmp"][2]="Wire";

$LANGTRACKER["snmp"][31]="Unable to get SNMP info : This is not a switch !";
$LANGTRACKER["snmp"][32]="Unable to get SNMP info : Materiel not active !";
$LANGTRACKER["snmp"][33]="Unable to get SNMP info : IP not precised into database !";
$LANGTRACKER["snmp"][34]="The switch the device is connected to is not specified !";


$LANGTRACKER["cron"][0]="Automatic statement of the counter";
$LANGTRACKER["cron"][1]="Activate the statement";
$LANGTRACKER["cron"][2]="";
$LANGTRACKER["cron"][3]="Default";

$LANGTRACKER["errors"][0]="Errors";
$LANGTRACKER["errors"][1]="IP";
$LANGTRACKER["errors"][2]="Description";
$LANGTRACKER["errors"][3]="Date of 1st pb";
$LANGTRACKER["errors"][4]="Date of last pb";

$LANGTRACKER["errors"][10]="Mismatch with GLPI database";
$LANGTRACKER["errors"][11]="Unknown computer";
$LANGTRACKER["errors"][12]="Unknown IP";

$LANGTRACKER["errors"][20]="SNMP Error";
$LANGTRACKER["errors"][21]="Unable to get info";

$LANGTRACKER["errors"][30]="Wire error";
$LANGTRACKER["errors"][31]="Wire problem";


$LANGTRACKER["prt_history"][0]="Gistory and statistics of printing counters";

$LANGTRACKER["prt_history"][10]="Statistics of printing counters these last";
$LANGTRACKER["prt_history"][11]="Day(s)";
$LANGTRACKER["prt_history"][12]="Total printed pages";
$LANGTRACKER["prt_history"][13]="Pages / day";

$LANGTRACKER["prt_history"][20]="History of printing counters";
$LANGTRACKER["prt_history"][21]="Date";
$LANGTRACKER["prt_history"][22]="Counter";


$LANGTRACKER["cpt_history"][0]="Sessions history";
$LANGTRACKER["cpt_history"][1]="User";
$LANGTRACKER["cpt_history"][2]="State";
$LANGTRACKER["cpt_history"][2]="Date";


$LANGTRACKER["type"][1]="Computer";
$LANGTRACKER["type"][2]="Switch";
$LANGTRACKER["type"][3]="Printer";
?>