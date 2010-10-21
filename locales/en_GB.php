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

$title="FusionInventory INVENTORY";
$version="2.3.0-1";

$LANG['plugin_fusinvinventory']["title"][0]="$title";

$LANG['plugin_fusinvinventory']["setup"][17]="Plugin ".$title." need plugin FusionInventory activated before activation.";
$LANG['plugin_fusinvinventory']["setup"][18]="Plugin ".$title." need plugin FusionInventory activated before uninstall.";

$LANG['plugin_fusinvinventory']["menu"][0]="Import agent XML file";
$LANG['plugin_fusinvinventory']["menu"][1]="Criterii rules";

$LANG['plugin_fusinvinventory']["importxml"][0]="Import Agent XML file";
$LANG['plugin_fusinvinventory']["importxml"][1]="Computer injected into GLPI";

$LANG['plugin_fusinvinventory']['rule'][0]="Computer existent criterii rules";
$LANG['plugin_fusinvinventory']['rule'][1]="Global criterii";
$LANG['plugin_fusinvinventory']['rule'][2]="Serial Number";
$LANG['plugin_fusinvinventory']['rule'][3]="MAC address";
$LANG['plugin_fusinvinventory']['rule'][4]="Microsoft product key";
$LANG['plugin_fusinvinventory']['rule'][5]="Computer model";
$LANG['plugin_fusinvinventory']['rule'][6]="Hard disk serial number";
$LANG['plugin_fusinvinventory']['rule'][7]="Partitions serial number";
$LANG['plugin_fusinvinventory']['rule'][8]="Tag";

$LANG['plugin_fusinvinventory']['rule'][30]="Import in asset";
$LANG['plugin_fusinvinventory']['rule'][31]="Import in unknown devices";

$LANG['plugin_fusinvinventory']["xml"][0]="XML FusionInventory";
?>