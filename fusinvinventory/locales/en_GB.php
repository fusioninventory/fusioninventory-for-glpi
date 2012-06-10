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
   @author    David Durieux
   @co-author 
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */

$title="FusionInventory INVENTORY";

$LANG['plugin_fusinvinventory']['title'][0]="FusionInventory INVENTORY";
$LANG['plugin_fusinvinventory']['title'][1]="Local inventory";
$LANG['plugin_fusinvinventory']['title'][2]="VMware host remote inventory";

$LANG['plugin_fusinvinventory']['setup'][17]="Plugin FusionInventory INVENTORY need plugin FusionInventory activated before activation.";
$LANG['plugin_fusinvinventory']['setup'][18]="Plugin FusionInventory INVENTORY need plugin FusionInventory activated before uninstall.";
$LANG['plugin_fusinvinventory']['setup'][20]="Import options";
$LANG['plugin_fusinvinventory']['setup'][21]="Components";
$LANG['plugin_fusinvinventory']['setup'][22]="Global import";
$LANG['plugin_fusinvinventory']['setup'][23]="No import";
$LANG['plugin_fusinvinventory']['setup'][24]="Unique import";
$LANG['plugin_fusinvinventory']['setup'][25]="Registry";
$LANG['plugin_fusinvinventory']['setup'][26]="Processes";
$LANG['plugin_fusinvinventory']['setup'][27]="Unique import on serial number";
$LANG['plugin_fusinvinventory']['setup'][28]="Automatic computers transfer";
$LANG['plugin_fusinvinventory']['setup'][29]="Model for automatic computers transfer in an other entity";
$LANG['plugin_fusinvinventory']['setup'][30]="Network drives";
$LANG['plugin_fusinvinventory']['setup'][31]="Virtual network card";
$LANG['plugin_fusinvinventory']['setup'][32]="This option will not import this item";
$LANG['plugin_fusinvinventory']['setup'][33]="This option will merge items with same name to reduce number of items if this management isn't important";
$LANG['plugin_fusinvinventory']['setup'][34]="This option will create one item for each item found";
$LANG['plugin_fusinvinventory']['setup'][35]="This option will create one item for each item have serial number";
$LANG['plugin_fusinvinventory']['setup'][36]="Default status";

$LANG['plugin_fusinvinventory']['menu'][0]="Import agent XML file";
$LANG['plugin_fusinvinventory']['menu'][1]="Criteria rules";
$LANG['plugin_fusinvinventory']['menu'][2]="BlackList";
$LANG['plugin_fusinvinventory']['menu'][4]="Data integrity check";

$LANG['plugin_fusinvinventory']['importxml'][0]="Import XML file from an Agent";
$LANG['plugin_fusinvinventory']['importxml'][1]="Computer injected into GLPI";
$LANG['plugin_fusinvinventory']['importxml'][2]="No file to import!";
$LANG['plugin_fusinvinventory']['importxml'][3]="XML file not valid!";

$LANG['plugin_fusinvinventory']['rule'][0]="Computer existent criteria rules";
$LANG['plugin_fusinvinventory']['rule'][1]="Existant criterium";
$LANG['plugin_fusinvinventory']['rule'][2]="Serial Number";
$LANG['plugin_fusinvinventory']['rule'][3]="MAC address";
$LANG['plugin_fusinvinventory']['rule'][4]="Microsoft product key";
$LANG['plugin_fusinvinventory']['rule'][5]="Computer model";
$LANG['plugin_fusinvinventory']['rule'][6]="Hard disk serial number";
$LANG['plugin_fusinvinventory']['rule'][7]="Partitions serial number";
$LANG['plugin_fusinvinventory']['rule'][8]="Tag";
$LANG['plugin_fusinvinventory']['rule'][30]="Import in asset";
$LANG['plugin_fusinvinventory']['rule'][31]="Import in unknown devices";
$LANG['plugin_fusinvinventory']['rule'][100]="Entity rules";
$LANG['plugin_fusinvinventory']['rule'][102]="Ignore in FusionInventory import";

$LANG['plugin_fusinvinventory']['blacklist'][0]="blacklisted value";
$LANG['plugin_fusinvinventory']['blacklist'][1]="New value to blacklist";

$LANG['plugin_fusinvinventory']['profile'][2]="Existance criteria";
$LANG['plugin_fusinvinventory']['profile'][3]="XML file manual import";
$LANG['plugin_fusinvinventory']['profile'][4]="Fields blacklist";

$LANG['plugin_fusinvinventory']['antivirus'][0]="Antivirus";
$LANG['plugin_fusinvinventory']['antivirus'][1]="This computer is not a Windows XP and later or no antivirus is installed";
$LANG['plugin_fusinvinventory']['antivirus'][2]="Version";
$LANG['plugin_fusinvinventory']['antivirus'][3]="Up to date";

$LANG['plugin_fusinvinventory']['computer'][0]="Last inventory";
$LANG['plugin_fusinvinventory']['computer'][1]="Owner";
$LANG['plugin_fusinvinventory']['computer'][2]="Company";

$LANG['plugin_fusinvinventory']['vmwareesx'][0]="VMware host";

$LANG['plugin_fusinvinventory']['integrity'][0]="Only in GLPI (check to delete)";
$LANG['plugin_fusinvinventory']['integrity'][1]="Only in last inventory (check to import)";

$LANG['plugin_fusinvinventory']['bios'][0]="BIOS";

?>