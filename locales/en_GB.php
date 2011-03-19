<?php

/*
   ----------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2011 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ----------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation, either version 2 of the License, or
   any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with FusionInventory.  If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------
   Original Author of file: David DURIEUX
   Co-authors of file: Mathieu SIMON
   Purpose of file:
   ----------------------------------------------------------------------
 */

$title="FusionInventory";
$version="2.3.0";

$LANG['plugin_fusioninventory']['title'][0]="$title";
$LANG['plugin_fusioninventory']['title'][1]="FusInv";
$LANG['plugin_fusioninventory']['title'][5]="Locks";

$LANG['plugin_fusioninventory']['config'][0] = "Inventory frequency (in hours)";

$LANG['plugin_fusioninventory']['profile'][0]="Rights management";
$LANG['plugin_fusioninventory']['profile'][2]="Agents";
$LANG['plugin_fusioninventory']['profile'][3]="Agent remote control";
$LANG['plugin_fusioninventory']['profile'][4]="Configuration";
$LANG['plugin_fusioninventory']['profile'][5]="Wake On LAN";
$LANG['plugin_fusioninventory']['profile'][6]="Unknown devices";
$LANG['plugin_fusioninventory']['profile'][7]="Tasks";

$LANG['plugin_fusioninventory']['setup'][16]="Documentation";
$LANG['plugin_fusioninventory']['setup'][17]="Other FusionInventory plugins (fusinv...) must be uninstalled before removing the FusionInventory plugin";

$LANG['plugin_fusioninventory']['functionalities'][0]="Features";
$LANG['plugin_fusioninventory']['functionalities'][2]="General configuration";
$LANG['plugin_fusioninventory']['functionalities'][6]="Legend";
$LANG['plugin_fusioninventory']['functionalities'][8]="Agent port";
$LANG['plugin_fusioninventory']['functionalities'][9]="Retention in days";
$LANG['plugin_fusioninventory']['functionalities'][16]="SNMP authentication";
$LANG['plugin_fusioninventory']['functionalities'][17]="Database";
$LANG['plugin_fusioninventory']['functionalities'][18]="Files";
$LANG['plugin_fusioninventory']['functionalities'][19]="Please configure the SNMP authentication in the setup of the plugin";
$LANG['plugin_fusioninventory']['functionalities'][27]="SSL-only for agent";
$LANG['plugin_fusioninventory']['functionalities'][29]="List of fields to history";
$LANG['plugin_fusioninventory']['functionalities'][32]="Delete tasks after";
$LANG['plugin_fusioninventory']['functionalities'][60]="Clean history";
$LANG['plugin_fusioninventory']['functionalities'][73]="Fields";
$LANG['plugin_fusioninventory']['functionalities'][74]="Values";
$LANG['plugin_fusioninventory']['functionalities'][75]="Locks";
$LANG['plugin_fusioninventory']['functionalities'][76]="Extra-debug";

$LANG['plugin_fusioninventory']['errors'][22]="Unattended element in";
$LANG['plugin_fusioninventory']['errors'][50]="Your GLPI version not compatible, require 0.78";

$LANG['plugin_fusioninventory']['rules'][2]="Equipment import and link rules";
$LANG['plugin_fusioninventory']['rules'][3]="Search GLPI equipment with the status";
$LANG['plugin_fusioninventory']['rules'][4]="Destination of equipment entity";
$LANG['plugin_fusioninventory']['rules'][5]="FusionInventory link";
$LANG['plugin_fusioninventory']['rules'][6] = "Link if possible, else import denied";
$LANG['plugin_fusioninventory']['rules'][7] = "Link if possible";
$LANG['plugin_fusioninventory']['rules'][8] = "Send";
$LANG['plugin_fusioninventory']['rules'][9]  = "exist";
$LANG['plugin_fusioninventory']['rules'][10]  = "not exist";
$LANG['plugin_fusioninventory']['rules'][11] = "in present in GLPI";
$LANG['plugin_fusioninventory']['rules'][12] = "is empty";
$LANG['plugin_fusioninventory']['rules'][13] = "Hard disk serial number";
$LANG['plugin_fusioninventory']['rules'][14] = "Partition serial number";
$LANG['plugin_fusioninventory']['rules'][15] = "UUID";
$LANG['plugin_fusioninventory']['rules'][16] = "FusionInventory tag";

$LANG['plugin_fusioninventory']['rulesengine'][152] = "Assets to import";

$LANG['plugin_fusioninventory']['choice'][0] = "No";
$LANG['plugin_fusioninventory']['choice'][1] = "Yes";
$LANG['plugin_fusioninventory']['choice'][2] = "or";
$LANG['plugin_fusioninventory']['choice'][3] = "and";

$LANG['plugin_fusioninventory']['processes'][1]="PID";
$LANG['plugin_fusioninventory']['processes'][38]="Process number";

$LANG['plugin_fusioninventory']['menu'][1]="Agents management";
$LANG['plugin_fusioninventory']['menu'][3]="Menu";
$LANG['plugin_fusioninventory']['menu'][4]="Unknown device";
$LANG['plugin_fusioninventory']['menu'][7]="Running jobs";

$LANG['plugin_fusioninventory']['discovery'][5]="Number of imported devices";
$LANG['plugin_fusioninventory']['discovery'][9]="Number of devices not imported because type not defined";

$LANG['plugin_fusioninventory']['agents'][4]="Last contact";
$LANG['plugin_fusioninventory']['agents'][6]="disable";
$LANG['plugin_fusioninventory']['agents'][15]="Agent state";
$LANG['plugin_fusioninventory']['agents'][17]="The agent is running";
$LANG['plugin_fusioninventory']['agents'][22]="Waiting";
$LANG['plugin_fusioninventory']['agents'][23]="Computer link";
$LANG['plugin_fusioninventory']['agents'][24]="Token";
$LANG['plugin_fusioninventory']['agents'][25]="Version";
$LANG['plugin_fusioninventory']['agents'][27]="Agents modules";
$LANG['plugin_fusioninventory']['agents'][28]="Agent";
$LANG['plugin_fusioninventory']['agents'][30]="Impossible to communicate with agent!";
$LANG['plugin_fusioninventory']['agents'][31]="Force inventory";
$LANG['plugin_fusioninventory']['agents'][32]="Auto managenement dynamic of agents";
$LANG['plugin_fusioninventory']['agents'][33]="Auto managenement dynamic of agents (same subnet)";
$LANG['plugin_fusioninventory']['agents'][34]="Activation (by default)";
$LANG['plugin_fusioninventory']['agents'][35]="Device_id";
$LANG['plugin_fusioninventory']['agents'][36]="Agent modules";
$LANG['plugin_fusioninventory']['agents'][37]="locked";
$LANG['plugin_fusioninventory']['agents'][38]="Available";
$LANG['plugin_fusioninventory']['agents'][39]="Running";
$LANG['plugin_fusioninventory']['agents'][40]="Computer without known IP";

$LANG['plugin_fusioninventory']['unknown'][2]="Approved devices";
$LANG['plugin_fusioninventory']['unknown'][4]="Network hub";
$LANG['plugin_fusioninventory']['unknown'][5]="Import unknown device into asset";

$LANG['plugin_fusioninventory']['task'][0]="Task";
$LANG['plugin_fusioninventory']['task'][1]="Task management";
$LANG['plugin_fusioninventory']['task'][2]="Action";
$LANG['plugin_fusioninventory']['task'][14]="Scheduled date";
$LANG['plugin_fusioninventory']['task'][16]="New action";
$LANG['plugin_fusioninventory']['task'][17]="Periodicity";
$LANG['plugin_fusioninventory']['task'][18]="Tasks";
$LANG['plugin_fusioninventory']['task'][19]="Running tasks";
$LANG['plugin_fusioninventory']['task'][20]="Finished tasks";
$LANG['plugin_fusioninventory']['task'][21]="Action on this device";
$LANG['plugin_fusioninventory']['task'][22]="Only scheduled tasks";
$LANG['plugin_fusioninventory']['task'][24]="Number of trials";
$LANG['plugin_fusioninventory']['task'][25]="Time between 2 trials (in minutes)";
$LANG['plugin_fusioninventory']['task'][26]="Module";
$LANG['plugin_fusioninventory']['task'][27]="Definition";
$LANG['plugin_fusioninventory']['task'][28]="Action";
$LANG['plugin_fusioninventory']['task'][29]="Type";
$LANG['plugin_fusioninventory']['task'][30]="Selection";
$LANG['plugin_fusioninventory']['task'][31]="Time between task start and start this action";
$LANG['plugin_fusioninventory']['task'][32]="Force the end";
$LANG['plugin_fusioninventory']['task'][33]="Communication type";
$LANG['plugin_fusioninventory']['task'][34]="Permanent";
$LANG['plugin_fusioninventory']['task'][35]="minutes";
$LANG['plugin_fusioninventory']['task'][36]="hours";
$LANG['plugin_fusioninventory']['task'][37]="days";
$LANG['plugin_fusioninventory']['task'][38]="months";
$LANG['plugin_fusioninventory']['task'][39]="Unable to run task because some jobs is running yet!";
$LANG['plugin_fusioninventory']['task'][40]="Force running";

$LANG['plugin_fusioninventory']['taskjoblog'][1]="Started";
$LANG['plugin_fusioninventory']['taskjoblog'][2]="Ok";
$LANG['plugin_fusioninventory']['taskjoblog'][3]="Error / rescheduled";
$LANG['plugin_fusioninventory']['taskjoblog'][4]="Error";
$LANG['plugin_fusioninventory']['taskjoblog'][5]="unknown";
$LANG['plugin_fusioninventory']['taskjoblog'][6]="Running";
$LANG['plugin_fusioninventory']['taskjoblog'][7]="Prepared";

$LANG['plugin_fusioninventory']['update'][0]="your history table has more than 300 000 entries, you must run this command to finish the update : ";

$LANG['plugin_fusioninventory']['xml'][0]="XML";

$LANG['plugin_fusioninventory']['codetasklog'][1]="Bad token, impossible to start agent";
$LANG['plugin_fusioninventory']['codetasklog'][2]="Agent stopped/crashed";
?>