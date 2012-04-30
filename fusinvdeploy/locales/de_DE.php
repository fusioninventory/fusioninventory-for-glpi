<?php

/*
   ------------------------------------------------------------------------
   FusionInventory
   Copyright (C) 2010-2012 by the FusionInventory Development Team.

   http://www.fusioninventory.org/   http://forge.fusioninventory.org/
   ------------------------------------------------------------------------

   LICENSE

   This file is part of FusionInventory project.

   FusionInventory is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as published by
   the Free Software Foundation, either version 3 of the License, or
   (at your option) any later version.

   FusionInventory is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public License
   along with Behaviors. If not, see <http://www.gnu.org/licenses/>.

   ------------------------------------------------------------------------

   @package   FusionInventory
   @author    Mathieu Simon
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

$title="FusionInventory DEPLOY";
$version="2.3.0-1";

$LANG['plugin_fusinvdeploy']['title'][0]="$title";

$LANG['plugin_fusinvdeploy']['massiveactions'][0]="Target a deployment task";
$LANG['plugin_fusinvdeploy']['massiveactions'][1]="Create a job for each computer";
$LANG['plugin_fusinvdeploy']['massiveactions'][2]="Create a job for each group";

$LANG['plugin_fusinvdeploy']['package'][0]="Aktion";
$LANG['plugin_fusinvdeploy']['package'][1]="Befehl";
$LANG['plugin_fusinvdeploy']['package'][2]="Ausführen (ausführbare Datei im Paket)";
$LANG['plugin_fusinvdeploy']['package'][3]="Ausführen (ausführbare Systemdatei)";
$LANG['plugin_fusinvdeploy']['package'][4]="Speichern";
$LANG['plugin_fusinvdeploy']['package'][5]="Pakete";
$LANG['plugin_fusinvdeploy']['package'][6]="Paketverwaltung";
$LANG['plugin_fusinvdeploy']['package'][7]="Paket";
$LANG['plugin_fusinvdeploy']['package'][8]="Paketverwaltung";
$LANG['plugin_fusinvdeploy']['package'][9]="Anzahl Fragmente";
$LANG['plugin_fusinvdeploy']['package'][10]="Modul";
$LANG['plugin_fusinvdeploy']['package'][11]="Diess Paket wurde noch nicht generiert";
$LANG['plugin_fusinvdeploy']['package'][12]="Paket generieren";
$LANG['plugin_fusinvdeploy']['package'][13]="Paket erneut generieren";
$LANG['plugin_fusinvdeploy']['package'][14]="Installation";
$LANG['plugin_fusinvdeploy']['package'][15]="Uninstallation";
$LANG['plugin_fusinvdeploy']['package'][16]="Package install";
$LANG['plugin_fusinvdeploy']['package'][17]="Package uninstall";
$LANG['plugin_fusinvdeploy']['package'][18]="Move a file";
$LANG['plugin_fusinvdeploy']['package'][19]="pieces of files";
$LANG['plugin_fusinvdeploy']['package'][20]="Delete a file";
$LANG['plugin_fusinvdeploy']['package'][21]="Show dialog";
$LANG['plugin_fusinvdeploy']['package'][22]="Return codes";
$LANG['plugin_fusinvdeploy']['package'][23]="One or more task(s) (#task#) uses this package, delete denied";
$LANG['plugin_fusinvdeploy']['package'][24]="One or more task(s) (#task#) uses this package, edit denied";
$LANG['plugin_fusinvdeploy']['package'][25]="New name";
$LANG['plugin_fusinvdeploy']['package'][26]="Add a package";
$LANG['plugin_fusinvdeploy']['package'][27]="make a directory";
$LANG['plugin_fusinvdeploy']['package'][28]="File copy";

$LANG['plugin_fusinvdeploy']['files'][0]="Dateiverwaltung";
$LANG['plugin_fusinvdeploy']['files'][1]="Dateiname";
$LANG['plugin_fusinvdeploy']['files'][2]="Version";
$LANG['plugin_fusinvdeploy']['files'][3]="Betriebssystem";
$LANG['plugin_fusinvdeploy']['files'][4]="Dateien zum herunterladen";
$LANG['plugin_fusinvdeploy']['files'][5]="Ordner im Paket";
$LANG['plugin_fusinvdeploy']['files'][6]="Maximum file size";
$LANG['plugin_fusinvdeploy']['files'][7]="Upload From";
$LANG['plugin_fusinvdeploy']['files'][8]="My computer";
$LANG['plugin_fusinvdeploy']['files'][9]="The server";;

$LANG['plugin_fusinvdeploy']['packagefiles'][0]="An Pakete gebundene Dateien";

$LANG['plugin_fusinvdeploy']['deploystatus'][0]="Deployment state";
$LANG['plugin_fusinvdeploy']['deploystatus'][1]="associated logs";
$LANG['plugin_fusinvdeploy']['deploystatus'][2]="The agent received the job request";
$LANG['plugin_fusinvdeploy']['deploystatus'][3]="The agent started to check the mirror to download the file";
$LANG['plugin_fusinvdeploy']['deploystatus'][4]="Preparing the working directory";
$LANG['plugin_fusinvdeploy']['deploystatus'][5]="The agent is processing the job";

$LANG['plugin_fusinvdeploy']['config'][0]="Adresse des GLPI-Servers (ohne http://)";
$LANG['plugin_fusinvdeploy']['config'][1]="Root folder for sending file from server";

$LANG['plugin_fusinvdeploy']['setup'][17]="Das Plugin ".$title." benötigt ein aktiviertes FusionInventory Plugin um selber aktiviert zu werden.";
$LANG['plugin_fusinvdeploy']['setup'][18]="Das Plugin ".$title." benötigt ein aktiviertes FusionInventory Plugin um selber deinstalliert werden zu können.";
$LANG['plugin_fusinvdeploy']['setup'][19]="Plugin ".$title." needs Webservices plugin (>= 1.2.0) installed before activation.";
$LANG['plugin_fusinvdeploy']['setup'][20]="Plugin ".$title." needs Webservices plugin (>= 1.2.0) installed before uninstall.";
$LANG['plugin_fusinvdeploy']['setup'][21]="Plugin ".$title." needs FusionInventory INVENTORY plugin installed before uninstall.";

$LANG['plugin_fusinvdeploy']['profile'][1]="$title";
$LANG['plugin_fusinvdeploy']['profile'][2]="Paketverwaltung";
$LANG['plugin_fusinvdeploy']['profile'][3]="Deployment-Status";


$LANG['plugin_fusinvdeploy']['form']['label'][0] = "Type";
$LANG['plugin_fusinvdeploy']['form']['label'][1] = "Name";
$LANG['plugin_fusinvdeploy']['form']['label'][2] = "Value";
$LANG['plugin_fusinvdeploy']['form']['label'][3] = "Unit";
$LANG['plugin_fusinvdeploy']['form']['label'][4] = "Active";
$LANG['plugin_fusinvdeploy']['form']['label'][5] = "File";
$LANG['plugin_fusinvdeploy']['form']['label'][6] = "P2P deployment";
$LANG['plugin_fusinvdeploy']['form']['label'][7] = "Date added";
$LANG['plugin_fusinvdeploy']['form']['label'][8] = "Validity time";
$LANG['plugin_fusinvdeploy']['form']['label'][9] = "Data retention duration (days)";
$LANG['plugin_fusinvdeploy']['form']['label'][10] = "Id";
$LANG['plugin_fusinvdeploy']['form']['label'][11] = "Command";
$LANG['plugin_fusinvdeploy']['form']['label'][12] = "Disk or directory";
$LANG['plugin_fusinvdeploy']['form']['label'][13] = "Key";
$LANG['plugin_fusinvdeploy']['form']['label'][14] = "Key value";
$LANG['plugin_fusinvdeploy']['form']['label'][15] = "File missing";
$LANG['plugin_fusinvdeploy']['form']['label'][16] = "From";
$LANG['plugin_fusinvdeploy']['form']['label'][17] = "To";
$LANG['plugin_fusinvdeploy']['form']['label'][18] = "Removal";
$LANG['plugin_fusinvdeploy']['form']['label'][19] = "Uncompress";
$LANG['plugin_fusinvdeploy']['form']['label'][20] = "transfer error: the file size is too big";
$LANG['plugin_fusinvdeploy']['form']['label'][21] = "Filesize";
$LANG['plugin_fusinvdeploy']['form']['label'][22] = "Failed to copy file";

$LANG['plugin_fusinvdeploy']['form']['action'][0] = "Add";
$LANG['plugin_fusinvdeploy']['form']['action'][1] = "Delete";
$LANG['plugin_fusinvdeploy']['form']['action'][2] = "OK";
$LANG['plugin_fusinvdeploy']['form']['action'][3] = "Select your file";
$LANG['plugin_fusinvdeploy']['form']['action'][4] = "File saved!";
$LANG['plugin_fusinvdeploy']['form']['action'][5] = "Or URL";
$LANG['plugin_fusinvdeploy']['form']['action'][6] = "Add return code";
$LANG['plugin_fusinvdeploy']['form']['action'][7] = "Delete return code";

$LANG['plugin_fusinvdeploy']['form']['title'][0] = "Edit check";
$LANG['plugin_fusinvdeploy']['form']['title'][1] = "Add check";
$LANG['plugin_fusinvdeploy']['form']['title'][2] = "List of checks";
$LANG['plugin_fusinvdeploy']['form']['title'][3] = "Files to copy on computer";
$LANG['plugin_fusinvdeploy']['form']['title'][4] = "Add file";
$LANG['plugin_fusinvdeploy']['form']['title'][5] = "Edit file";
$LANG['plugin_fusinvdeploy']['form']['title'][6] = "Add command";
$LANG['plugin_fusinvdeploy']['form']['title'][7] = "Edit command";
$LANG['plugin_fusinvdeploy']['form']['title'][8] = "Actions to achieve";
$LANG['plugin_fusinvdeploy']['form']['title'][9] = "Delete a check";
$LANG['plugin_fusinvdeploy']['form']['title'][10] = "Add order";
$LANG['plugin_fusinvdeploy']['form']['title'][11] = "Delete order";
$LANG['plugin_fusinvdeploy']['form']['title'][12] = "Edit order";
$LANG['plugin_fusinvdeploy']['form']['title'][13] = "Delete file";
$LANG['plugin_fusinvdeploy']['form']['title'][14] = "Delete command";
$LANG['plugin_fusinvdeploy']['form']['title'][15] = "during installation";
$LANG['plugin_fusinvdeploy']['form']['title'][16] = "during uninstallation";
$LANG['plugin_fusinvdeploy']['form']['title'][17] = "before installation";
$LANG['plugin_fusinvdeploy']['form']['title'][18] = "before uninstallation";

$LANG['plugin_fusinvdeploy']['form']['message'][0] = "Empty form";
$LANG['plugin_fusinvdeploy']['form']['message'][1] = "Invalid form";
$LANG['plugin_fusinvdeploy']['form']['message'][2] = "Loading...";
$LANG['plugin_fusinvdeploy']['form']['message'][3] = "File already exist";

$LANG['plugin_fusinvdeploy']['form']['check'][0] = "Register key exist";
$LANG['plugin_fusinvdeploy']['form']['check'][1] = "Register key missing";
$LANG['plugin_fusinvdeploy']['form']['check'][2] = "Register key value";
$LANG['plugin_fusinvdeploy']['form']['check'][3] = "File exist";
$LANG['plugin_fusinvdeploy']['form']['check'][4] = "File missing";
$LANG['plugin_fusinvdeploy']['form']['check'][5] = "File size";
$LANG['plugin_fusinvdeploy']['form']['check'][6] = "Hash512 of file";
$LANG['plugin_fusinvdeploy']['form']['check'][7] = "Free space";
$LANG['plugin_fusinvdeploy']['form']['check'][8] = "Filesize equal";
$LANG['plugin_fusinvdeploy']['form']['check'][9] = "Filesize lower";

$LANG['plugin_fusinvdeploy']['form']['mirror'][1] = "Mirror";
$LANG['plugin_fusinvdeploy']['form']['mirror'][2] = "Mirrors";
$LANG['plugin_fusinvdeploy']['form']['mirror'][3] = "Address of the mirror";

$LANG['plugin_fusinvdeploy']['form']['command_status'][0] = "Make your choice...";
$LANG['plugin_fusinvdeploy']['form']['command_status'][1] = "Type";
$LANG['plugin_fusinvdeploy']['form']['command_status'][2] = "Value";
$LANG['plugin_fusinvdeploy']['form']['command_status'][3] = "Expected return code";
$LANG['plugin_fusinvdeploy']['form']['command_status'][4] = "Invalid return code";
$LANG['plugin_fusinvdeploy']['form']['command_status'][5] = "Expected regular expression";
$LANG['plugin_fusinvdeploy']['form']['command_status'][6] = "Invalid regular expression";

$LANG['plugin_fusinvdeploy']['form']['command_envvariable'][1] = "Environment variable";

$LANG['plugin_fusinvdeploy']['form']['action_message'][1] = "Title";
$LANG['plugin_fusinvdeploy']['form']['action_message'][2] = "Content";
$LANG['plugin_fusinvdeploy']['form']['action_message'][3] = "Type";
$LANG['plugin_fusinvdeploy']['form']['action_message'][4] = "Informations";
$LANG['plugin_fusinvdeploy']['form']['action_message'][5] = "report of the install";

$LANG['plugin_fusinvdeploy']['task'][0] = "Deployement task";
$LANG['plugin_fusinvdeploy']['task'][1] = "Task";
$LANG['plugin_fusinvdeploy']['task'][3] = "Add task";
$LANG['plugin_fusinvdeploy']['task'][5] = "Task";
$LANG['plugin_fusinvdeploy']['task'][7] = "Actions";
$LANG['plugin_fusinvdeploy']['task'][8] = "Actions list";
$LANG['plugin_fusinvdeploy']['task'][11] = "Edit task";
$LANG['plugin_fusinvdeploy']['task'][12] = "Add a task";
$LANG['plugin_fusinvdeploy']['task'][13] = "Order list";
$LANG['plugin_fusinvdeploy']['task'][14] = "Advanced options";
$LANG['plugin_fusinvdeploy']['task'][15] = "Add order";
$LANG['plugin_fusinvdeploy']['task'][16] = "Delete order";
$LANG['plugin_fusinvdeploy']['task'][17] = "Edit order";
$LANG['plugin_fusinvdeploy']['task'][18] = "---";
$LANG['plugin_fusinvdeploy']['task'][19] = "Edit impossible, this task is active";
$LANG['plugin_fusinvdeploy']['task'][20] = "This task is active. delete denied";

$LANG['plugin_fusinvdeploy']['group'][0] = "Sets";
$LANG['plugin_fusinvdeploy']['group'][1] = "Static set";
$LANG['plugin_fusinvdeploy']['group'][2] = "dynamic set";
$LANG['plugin_fusinvdeploy']['group'][3] = "Set";
$LANG['plugin_fusinvdeploy']['group'][4] = "Add set";
$LANG['plugin_fusinvdeploy']['group'][5] = "If no line in the list is selected, the text fields on the left will be used for search.";

$LANG['plugin_fusinvdeploy']['menu'][1] = "Paketverwaltung";
$LANG['plugin_fusinvdeploy']['menu'][2] = "Mirror";
$LANG['plugin_fusinvdeploy']['menu'][3] = "Deployment tasks";
$LANG['plugin_fusinvdeploy']['menu'][4] = "Sets";
$LANG['plugin_fusinvdeploy']['menu'][5] = "Deployment status";
?>
