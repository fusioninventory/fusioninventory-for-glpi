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
$LANG['plugin_fusinvinventory']["menu"][1]="Règles de critères";
$LANG['plugin_fusinvinventory']["menu"][2]="BlackList";

$LANG['plugin_fusinvinventory']["importxml"][0]="Import de fichier XML provenant de l'agent";
$LANG['plugin_fusinvinventory']["importxml"][1]="Ordinateur importé dans GLPI";

$LANG['plugin_fusinvinventory']['rule'][0]="Règles de critères d'existence d'ordinateur";
$LANG['plugin_fusinvinventory']['rule'][1]="Critère d'existence";
$LANG['plugin_fusinvinventory']['rule'][2]="Numéro de série";
$LANG['plugin_fusinvinventory']['rule'][3]="Adresse MAC";
$LANG['plugin_fusinvinventory']['rule'][4]="Clé produit Microsoft";
$LANG['plugin_fusinvinventory']['rule'][5]="Modèle d'ordinateur";
$LANG['plugin_fusinvinventory']['rule'][6]="Numéro de série des disques durs";
$LANG['plugin_fusinvinventory']['rule'][7]="Numéro de série des partitions";
$LANG['plugin_fusinvinventory']['rule'][8]="Tag";

$LANG['plugin_fusinvinventory']['rule'][30]="Import dans l'inventaire";
$LANG['plugin_fusinvinventory']['rule'][31]="Import dans le matériel inconnu";

$LANG['plugin_fusinvinventory']["xml"][0]="XML FusionInventory";

$LANG['plugin_fusinvinventory']["blacklist"][0]="Valeurs blacklistées";
$LANG['plugin_fusinvinventory']["blacklist"][1]="Nouvelle valeur à blacklister";

$LANG['plugin_fusinvinventory']['profile'][1]="$title";
$LANG['plugin_fusinvinventory']['profile'][2]="Règles d'existence";
$LANG['plugin_fusinvinventory']['profile'][3]="Import manuel de fichier XML";
$LANG['plugin_fusinvinventory']['profile'][4]="Blacklist de champs";

$LANG['plugin_fusinvinventory']['antivirus'][0]="Antivirus";
$LANG['plugin_fusinvinventory']['antivirus'][1]="Pas d'antivirus sur cet ordinateur";
$LANG['plugin_fusinvinventory']['antivirus'][2]="Version";
$LANG['plugin_fusinvinventory']['antivirus'][3]="A jour";


?>