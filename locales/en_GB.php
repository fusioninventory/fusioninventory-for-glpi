<?php
/*
 * @version $Id$
 ----------------------------------------------------------------------
 FusionInventory
 Coded by the FusionInventory Development Team.

 http://www.fusioninventory.org/   http://forge.fusioninventory.org//
 ----------------------------------------------------------------------

 LICENSE

 This file is part of FusionInventory plugins.

 FusionInventory is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 FusionInventory is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FusionInventory; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 ------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: David DURIEUX
// Purpose of file:
// ----------------------------------------------------------------------

$title="FusionInventory DEPLOY";
$version="2.3.0-1";

$LANG['plugin_fusinvdeploy']['title'][0]="$title";

$LANG['plugin_fusinvdeploy']['package'][0]="Action";
$LANG['plugin_fusinvdeploy']['package'][1]="Command";
$LANG['plugin_fusinvdeploy']['package'][2]="Lancer (running file in package)";
$LANG['plugin_fusinvdeploy']['package'][3]="Exécuter (running file of system)";
$LANG['plugin_fusinvdeploy']['package'][4]="Store";
$LANG['plugin_fusinvdeploy']['package'][5]="Packages";
$LANG['plugin_fusinvdeploy']['package'][6]="Package management";
$LANG['plugin_fusinvdeploy']['package'][7]="Package";
$LANG['plugin_fusinvdeploy']['package'][8]="Package management";
$LANG['plugin_fusinvdeploy']['package'][9]="Fragments number";
$LANG['plugin_fusinvdeploy']['package'][10]="Module";
$LANG['plugin_fusinvdeploy']['package'][11]="This package has not been yet created";
$LANG['plugin_fusinvdeploy']['package'][12]="Create package";
$LANG['plugin_fusinvdeploy']['package'][13]="re-create package";
$LANG['plugin_fusinvdeploy']['package'][14]="Installation";
$LANG['plugin_fusinvdeploy']['package'][15]="Uninstallation";
$LANG['plugin_fusinvdeploy']['package'][16]="Package install";
$LANG['plugin_fusinvdeploy']['package'][17]="Package uninstall";
$LANG['plugin_fusinvdeploy']['package'][18]="Move a file";
$LANG['plugin_fusinvdeploy']['package'][19]="pieces of files";
$LANG['plugin_fusinvdeploy']['package'][20]="Delete a file";
$LANG['plugin_fusinvdeploy']['package'][21]="Show dialog";

$LANG['plugin_fusinvdeploy']['files'][0]="Files management";
$LANG['plugin_fusinvdeploy']['files'][1]="Filename";
$LANG['plugin_fusinvdeploy']['files'][2]="Version";
$LANG['plugin_fusinvdeploy']['files'][3]="Operating system";
$LANG['plugin_fusinvdeploy']['files'][4]="File to upload";
$LANG['plugin_fusinvdeploy']['files'][5]="Folder in package";

$LANG['plugin_fusinvdeploy']['packagefiles'][0]="Files linked with package";

$LANG['plugin_fusinvdeploy']['deploystatus'][0]="Deployement state";

$LANG['plugin_fusinvdeploy']['config'][0]="Addresse du serveur GLPI (sans le http://)";

$LANG['plugin_fusinvdeploy']['setup'][17]="Plugin ".$title." need plugin FusionInventory activated before activation.";
$LANG['plugin_fusinvdeploy']['setup'][18]="Plugin ".$title." need plugin FusionInventory activated before uninstall.";

$LANG['plugin_fusinvdeploy']['profile'][1]="$title";
$LANG['plugin_fusinvdeploy']['profile'][2]="Manage packages";
$LANG['plugin_fusinvdeploy']['profile'][3]="Deployment status";


$LANG['plugin_fusinvdeploy']['form']['label'][0] = "Type";
$LANG['plugin_fusinvdeploy']['form']['label'][1] = "Name";
$LANG['plugin_fusinvdeploy']['form']['label'][2] = "Value";
$LANG['plugin_fusinvdeploy']['form']['label'][3] = "Unit";
$LANG['plugin_fusinvdeploy']['form']['label'][4] = "Active";
$LANG['plugin_fusinvdeploy']['form']['label'][5] = "File";
$LANG['plugin_fusinvdeploy']['form']['label'][6] = "P2P deployment";
$LANG['plugin_fusinvdeploy']['form']['label'][7] = "Date added";
$LANG['plugin_fusinvdeploy']['form']['label'][8] = "Validity time";
$LANG['plugin_fusinvdeploy']['form']['label'][9] = "If yes, for a period";
$LANG['plugin_fusinvdeploy']['form']['label'][10] = "Id";
$LANG['plugin_fusinvdeploy']['form']['label'][11] = "Command";
$LANG['plugin_fusinvdeploy']['form']['label'][12] = "Disk or directory";
$LANG['plugin_fusinvdeploy']['form']['label'][13] = "Key";
$LANG['plugin_fusinvdeploy']['form']['label'][14] = "Key exist";
$LANG['plugin_fusinvdeploy']['form']['label'][15] = "Key missing";
$LANG['plugin_fusinvdeploy']['form']['label'][16] = "Key value";
$LANG['plugin_fusinvdeploy']['form']['label'][17] = "Exist";
$LANG['plugin_fusinvdeploy']['form']['label'][18] = "Missing";

$LANG['plugin_fusinvdeploy']['form']['action'][0] = "Add";
$LANG['plugin_fusinvdeploy']['form']['action'][1] = "Delete";
$LANG['plugin_fusinvdeploy']['form']['action'][2] = "Save";
$LANG['plugin_fusinvdeploy']['form']['action'][3] = "Select your file";

$LANG['plugin_fusinvdeploy']['form']['title'][0] = "Edit a check";
$LANG['plugin_fusinvdeploy']['form']['title'][1] = "Add a check";
$LANG['plugin_fusinvdeploy']['form']['title'][2] = "List of check";
$LANG['plugin_fusinvdeploy']['form']['title'][3] = "List of files";
$LANG['plugin_fusinvdeploy']['form']['title'][4] = "Add file";
$LANG['plugin_fusinvdeploy']['form']['title'][5] = "Edit file";
$LANG['plugin_fusinvdeploy']['form']['title'][6] = "Add command";
$LANG['plugin_fusinvdeploy']['form']['title'][7] = "Edit command";
$LANG['plugin_fusinvdeploy']['form']['title'][8] = "Command list";

$LANG['plugin_fusinvdeploy']['form']['message'][0] = "Empty form";
$LANG['plugin_fusinvdeploy']['form']['message'][1] = "Invalid form";
$LANG['plugin_fusinvdeploy']['form']['message'][2] = "Loading...";

$LANG['plugin_fusinvdeploy']['form']['check'][0] = "Register key exist";
$LANG['plugin_fusinvdeploy']['form']['check'][1] = "Register key missing";
$LANG['plugin_fusinvdeploy']['form']['check'][2] = "Register key value";
$LANG['plugin_fusinvdeploy']['form']['check'][3] = "File exist";
$LANG['plugin_fusinvdeploy']['form']['check'][4] = "File missing";
$LANG['plugin_fusinvdeploy']['form']['check'][5] = "File size";
$LANG['plugin_fusinvdeploy']['form']['check'][6] = "Hash512 of file";
$LANG['plugin_fusinvdeploy']['form']['check'][7] = "Free space";
?>
