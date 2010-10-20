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

$LANG['plugin_fusinvinventory']["setup"][17]="Le plugin ".$title." a besoin que le plugin FusionInventory soit activé pour être lui-même activé.";
$LANG['plugin_fusinvinventory']["setup"][18]="Le plugin ".$title." a besoin que le plugin FusionInventory soit activé pour être lui-même désinstallé.";

$LANG['plugin_fusinvinventory']["menu"][0]="Importer un fichier XML de l'agent";

$LANG['plugin_fusinvinventory']["importxml"][0]="Import de fichier XML provenant de l'agent";
$LANG['plugin_fusinvinventory']["importxml"][1]="Ordinateur importé dans GLPI";

$LANG['plugin_fusinvinventory']['rule'][0]="Règles de critères d'existence d'ordinateur";
$LANG['plugin_fusinvinventory']['rule'][1]="Critères globaux";
$LANG['plugin_fusinvinventory']['rule'][2]="Numéro de série";
$LANG['plugin_fusinvinventory']['rule'][3]="Adresse MAC";
$LANG['plugin_fusinvinventory']['rule'][4]="Clé produit Microsoft";
$LANG['plugin_fusinvinventory']['rule'][5]="Modèle d'ordinateur";
$LANG['plugin_fusinvinventory']['rule'][6]="Numéro de série des disques durs";
$LANG['plugin_fusinvinventory']['rule'][7]="Numéro de série des partitions";

?>