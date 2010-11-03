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

$title="FusionInventory DEPLOY";
$version="2.3.0-1";

$LANG['plugin_fusinvdeploy']["title"][0]="$title";

$LANG['plugin_fusinvdeploy']["package"][0]="Action";
$LANG['plugin_fusinvdeploy']["package"][1]="Commande";
$LANG['plugin_fusinvdeploy']["package"][2]="Lancer (exécutable dans le paquet)";
$LANG['plugin_fusinvdeploy']["package"][3]="Exécuter (exécutable du système)";
$LANG['plugin_fusinvdeploy']["package"][4]="Stocker";
$LANG['plugin_fusinvdeploy']["package"][5]="Paquets";
$LANG['plugin_fusinvdeploy']["package"][6]="Gestion des packages";
$LANG['plugin_fusinvdeploy']["package"][7]="Paquet";
$LANG['plugin_fusinvdeploy']['package'][8]="Gestion des paquets";
$LANG['plugin_fusinvdeploy']['package'][9]="Nombre de fragments";
$LANG['plugin_fusinvdeploy']['package'][10]="Module";
$LANG['plugin_fusinvdeploy']['package'][11]="Ce paquet n'a pas encore été généré";
$LANG['plugin_fusinvdeploy']['package'][12]="générer le paquet";
$LANG['plugin_fusinvdeploy']['package'][13]="re-générer le package";

$LANG['plugin_fusinvdeploy']['files'][0]="Gestion des fichiers";
$LANG['plugin_fusinvdeploy']['files'][1]="Nom de fichier";
$LANG['plugin_fusinvdeploy']['files'][2]="Version";
$LANG['plugin_fusinvdeploy']['files'][3]="Plateforme";
$LANG['plugin_fusinvdeploy']['files'][4]="Fichier à télécharger";
$LANG['plugin_fusinvdeploy']['files'][5]="Dossier dans le package";

$LANG['plugin_fusinvdeploy']['packagefiles'][0]="Fichiers liés aux packages";

$LANG['plugin_fusinvdeploy']["deploystatus"][0]="Etat des deploiements";

$LANG['plugin_fusinvdeploy']["config"][0]="Addresse du serveur GLPI (sans le http://)";

$LANG['plugin_fusinvdeploy']["setup"][17]="Le plugin ".$title." a besoin que le plugin FusionInventory soit activé pour être lui-même activé.";
$LANG['plugin_fusinvdeploy']["setup"][18]="Le plugin ".$title." a besoin que le plugin FusionInventory soit activé pour être lui-même désinstallé.";

$LANG['plugin_fusinvdeploy']['profile'][1]="$title";
$LANG['plugin_fusinvdeploy']['profile'][2]="Gestion des packages";
$LANG['plugin_fusinvdeploy']['profile'][3]="Statut des déploiements";
?>