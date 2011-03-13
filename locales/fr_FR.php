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
   Co-authors of file:
   Purpose of file:
   ----------------------------------------------------------------------
 */

$title="FusionInventory INVENTORY";
$version="2.3.0-1";

$LANG['plugin_fusinvinventory']['title'][0]="$title";

$LANG['plugin_fusinvinventory']['setup'][17]="Le plugin ".$title." a besoin que le plugin FusionInventory soit activé pour être lui-même activé.";
$LANG['plugin_fusinvinventory']['setup'][18]="Le plugin ".$title." a besoin que le plugin FusionInventory soit activé pour être lui-même désinstallé.";
$LANG['plugin_fusinvinventory']['setup'][20]="Options d'importation";
$LANG['plugin_fusinvinventory']['setup'][21]="Composants";
$LANG['plugin_fusinvinventory']['setup'][22]="Import global";
$LANG['plugin_fusinvinventory']['setup'][23]="Pas d'import";
$LANG['plugin_fusinvinventory']['setup'][24]="Import unique";
$LANG['plugin_fusinvinventory']['setup'][25]="Base de registre";
$LANG['plugin_fusinvinventory']['setup'][26]="Processus";
$LANG['plugin_fusinvinventory']['setup'][27]="Import unique sur numéro de série";
$LANG['plugin_fusinvinventory']['setup'][28]="Transfert automatique d'ordinateurs";
$LANG['plugin_fusinvinventory']['setup'][29]="Modèle pour le transfert automatique d'ordinateurs dans une autre entité";

$LANG['plugin_fusinvinventory']['menu'][0]="Importer un fichier XML de l'agent";
$LANG['plugin_fusinvinventory']['menu'][1]="Règles de critères";
$LANG['plugin_fusinvinventory']['menu'][2]="Liste noire";
$LANG['plugin_fusinvinventory']['menu'][3]="Règles d'entités";
$LANG['plugin_fusinvinventory']['menu'][4]="Contrôle d'intégrité des données";

$LANG['plugin_fusinvinventory']['importxml'][0]="Import de fichier XML provenant de l'agent";
$LANG['plugin_fusinvinventory']['importxml'][1]="Ordinateur importé dans GLPI";
$LANG['plugin_fusinvinventory']['importxml'][2]="Fichier manquant !";
$LANG['plugin_fusinvinventory']['importxml'][3]="Fichier XML non valide !";

$LANG['plugin_fusinvinventory']['rule'][0]="Règles de critères d'existence d'ordinateur";
$LANG['plugin_fusinvinventory']['rule'][1]="Critère d'existence";
$LANG['plugin_fusinvinventory']['rule'][2]="Numéro de série";
$LANG['plugin_fusinvinventory']['rule'][3]="Adresse MAC";
$LANG['plugin_fusinvinventory']['rule'][4]="Clé produit Microsoft";
$LANG['plugin_fusinvinventory']['rule'][5]="Modèle d'ordinateur";
$LANG['plugin_fusinvinventory']['rule'][6]="Numéro de série des disques durs";
$LANG['plugin_fusinvinventory']['rule'][7]="Numéro de série des partitions";
$LANG['plugin_fusinvinventory']['rule'][8]="Etiquette";
$LANG['plugin_fusinvinventory']['rule'][30]="Import dans l'inventaire";
$LANG['plugin_fusinvinventory']['rule'][31]="Import dans le matériel inconnu";
$LANG['plugin_fusinvinventory']['rule'][100]="Règles d'entité";
$LANG['plugin_fusinvinventory']['rule'][101]="Etiquette";
$LANG['plugin_fusinvinventory']['rule'][102]="Ignorer lors de l'import FusionInventory";

$LANG['plugin_fusinvinventory']['blacklist'][0]="Valeur en liste noire";
$LANG['plugin_fusinvinventory']['blacklist'][1]="Nouvelle valeur à ajouter en liste noire";

$LANG['plugin_fusinvinventory']['profile'][2]="Règles d'existence";
$LANG['plugin_fusinvinventory']['profile'][3]="Import manuel de fichier XML";
$LANG['plugin_fusinvinventory']['profile'][4]="Liste noire de champs";

$LANG['plugin_fusinvinventory']['antivirus'][0]="Antivirus";
$LANG['plugin_fusinvinventory']['antivirus'][1]="Pas d'antivirus sur cet ordinateur";
$LANG['plugin_fusinvinventory']['antivirus'][2]="Version";
$LANG['plugin_fusinvinventory']['antivirus'][3]="A jour";

$LANG['plugin_fusinvinventory']['computer'][0]="Dernier inventaire";
?>