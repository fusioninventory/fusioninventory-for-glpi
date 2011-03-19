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

$title="FusionInventory";
$version="2.4.0";

$LANG['plugin_fusioninventory']['title'][0]="$title";
$LANG['plugin_fusioninventory']['title'][1]="FusInv";
$LANG['plugin_fusioninventory']['title'][5]="Verrous";

$LANG['plugin_fusioninventory']['config'][0] = "Fréquence des inventaires (en heures)";

$LANG['plugin_fusioninventory']['profile'][0]="Gestion des droits";
$LANG['plugin_fusioninventory']['profile'][2]="Agents";
$LANG['plugin_fusioninventory']['profile'][3]="Controle à distance des agents";
$LANG['plugin_fusioninventory']['profile'][4]="Configuration";
$LANG['plugin_fusioninventory']['profile'][5]="WakeOnLan";
$LANG['plugin_fusioninventory']['profile'][6]="Matériel inconnu";
$LANG['plugin_fusioninventory']['profile'][7]="Tâches";

$LANG['plugin_fusioninventory']['setup'][16]="Documentation";
$LANG['plugin_fusioninventory']['setup'][17]="Les autres plugins FusionInventory (fusinv...) doivent être désinstallés avant de désinstaller le plugin FusionInventory.";

$LANG['plugin_fusioninventory']['functionalities'][0]="Fonctionnalités";
$LANG['plugin_fusioninventory']['functionalities'][2]="Configuration générale";
$LANG['plugin_fusioninventory']['functionalities'][6]="Légende";
$LANG['plugin_fusioninventory']['functionalities'][8]="Port de l'agent";
$LANG['plugin_fusioninventory']['functionalities'][9]="Rétention en jours";
$LANG['plugin_fusioninventory']['functionalities'][16]="Stockage de l'authentification SNMP";
$LANG['plugin_fusioninventory']['functionalities'][17]="Base de données";
$LANG['plugin_fusioninventory']['functionalities'][18]="Fichiers";
$LANG['plugin_fusioninventory']['functionalities'][19]="Veuillez configurer le stockage de l'authentification SNMP dans la configuration du plugin";
$LANG['plugin_fusioninventory']['functionalities'][27]="SSL seulement pour l'agent";
$LANG['plugin_fusioninventory']['functionalities'][29]="Liste des champs à historiser";
$LANG['plugin_fusioninventory']['functionalities'][32]="Effacer les tâches terminées après";
$LANG['plugin_fusioninventory']['functionalities'][60]="Nettoyage de l'historique";
$LANG['plugin_fusioninventory']['functionalities'][73]="Champs";
$LANG['plugin_fusioninventory']['functionalities'][74]="Valeurs";
$LANG['plugin_fusioninventory']['functionalities'][75]="Verrous";
$LANG['plugin_fusioninventory']['functionalities'][76]="Extra-debug";

$LANG['plugin_fusioninventory']['errors'][22]="Elément inattendu dans";
$LANG['plugin_fusioninventory']['errors'][50]="La version de GLPI n'est pas compatible, vous avez besoin de la version 0.78";

$LANG['plugin_fusioninventory']['rules'][2]="Règles d'import et de liaison des matériels";
$LANG['plugin_fusioninventory']['rules'][3]="Chercher les matériels GLPI ayant le statut";
$LANG['plugin_fusioninventory']['rules'][4]="Entité de destination de la machine";
$LANG['plugin_fusioninventory']['rules'][5]="Liaison FusionInventory";
$LANG['plugin_fusioninventory']['rules'][6] = "Liaison si possible, sinon import refusé";
$LANG['plugin_fusioninventory']['rules'][7] = "Liaison si possible";
$LANG['plugin_fusioninventory']['rules'][8] = "Envoyer";
$LANG['plugin_fusioninventory']['rules'][9]  = "existe";
$LANG['plugin_fusioninventory']['rules'][10]  = "n'existe pas";
$LANG['plugin_fusioninventory']['rules'][11] = "est déjà présent dans GLPI";
$LANG['plugin_fusioninventory']['rules'][12] = "est vide";
$LANG['plugin_fusioninventory']['rules'][13] = "Numéro de série du disque dur";
$LANG['plugin_fusioninventory']['rules'][14] = "Numéro de série de partition disque";
$LANG['plugin_fusioninventory']['rules'][15] = "uuid";
$LANG['plugin_fusioninventory']['rules'][16] = "Etiquette FusionInventory";

$LANG['plugin_fusioninventory']['rulesengine'][152] = "Matériel à importer";

$LANG['plugin_fusioninventory']['choice'][0] = "Non";
$LANG['plugin_fusioninventory']['choice'][1] = "Oui";
$LANG['plugin_fusioninventory']['choice'][2] = "ou";
$LANG['plugin_fusioninventory']['choice'][3] = "et";

$LANG['plugin_fusioninventory']['processes'][1]="PID";
$LANG['plugin_fusioninventory']['processes'][38]="Numéro de process";

$LANG['plugin_fusioninventory']['menu'][1]="Gestion des agents";
$LANG['plugin_fusioninventory']['menu'][3]="Menu";
$LANG['plugin_fusioninventory']['menu'][4]="Matériel inconnu";
$LANG['plugin_fusioninventory']['menu'][7]="Actions en cours d'exécution";

$LANG['plugin_fusioninventory']['discovery'][5]="Nombre de matériels importés";
$LANG['plugin_fusioninventory']['discovery'][9]="Nombre de matériels non importés car type non défini";

$LANG['plugin_fusioninventory']['agents'][4]="Dernier contact de l'agent";
$LANG['plugin_fusioninventory']['agents'][6]="Désactivé";
$LANG['plugin_fusioninventory']['agents'][15]="Statut de l'agent";
$LANG['plugin_fusioninventory']['agents'][17]="L'agent s'exécute";
$LANG['plugin_fusioninventory']['agents'][22]="En attente";
$LANG['plugin_fusioninventory']['agents'][23]="Lié à l'ordinateur";
$LANG['plugin_fusioninventory']['agents'][24]="Jeton";
$LANG['plugin_fusioninventory']['agents'][25]="Version";
$LANG['plugin_fusioninventory']['agents'][27]="Modules des agents";
$LANG['plugin_fusioninventory']['agents'][28]="Agent";
$LANG['plugin_fusioninventory']['agents'][30]="Impossible de joindre l'agent!";
$LANG['plugin_fusioninventory']['agents'][31]="Forcer l'inventaire";
$LANG['plugin_fusioninventory']['agents'][32]="Auto gestion dynamique des agents";
$LANG['plugin_fusioninventory']['agents'][33]="Auto gestion dynamique des agents (même Sous-réseau)";
$LANG['plugin_fusioninventory']['agents'][34]="Activation (par défaut)";
$LANG['plugin_fusioninventory']['agents'][35]="Identifiant";
$LANG['plugin_fusioninventory']['agents'][36]="Modules de l'agent";
$LANG['plugin_fusioninventory']['agents'][37]="Verrouillé";
$LANG['plugin_fusioninventory']['agents'][38]="Disponible";
$LANG['plugin_fusioninventory']['agents'][39]="En cours d'éxécution";
$LANG['plugin_fusioninventory']['agents'][40]="Ordinateur sans IP connue";

$LANG['plugin_fusioninventory']['unknown'][2]="Matériel approuvé";
$LANG['plugin_fusioninventory']['unknown'][4]="Hub réseau";
$LANG['plugin_fusioninventory']['unknown'][5]="Matériel inconnu à importer dans l'inventaire";

$LANG['plugin_fusioninventory']['task'][0]="Tâche";
$LANG['plugin_fusioninventory']['task'][1]="Gestion des tâches";
$LANG['plugin_fusioninventory']['task'][2]="Action";
$LANG['plugin_fusioninventory']['task'][14]="Date d'exécution";
$LANG['plugin_fusioninventory']['task'][16]="Nouvelle action";
$LANG['plugin_fusioninventory']['task'][17]="Périodicité";
$LANG['plugin_fusioninventory']['task'][18]="Tâches";
$LANG['plugin_fusioninventory']['task'][19]="Tâches en cours";
$LANG['plugin_fusioninventory']['task'][20]="Tâches terminées";
$LANG['plugin_fusioninventory']['task'][21]="Action sur ce matériel";
$LANG['plugin_fusioninventory']['task'][22]="Tâches planifiées uniquement";
$LANG['plugin_fusioninventory']['task'][24]="Nombre d'essais";
$LANG['plugin_fusioninventory']['task'][25]="Temps entre 2 essais (en minutes)";
$LANG['plugin_fusioninventory']['task'][26]="Module";
$LANG['plugin_fusioninventory']['task'][27]="Définition";
$LANG['plugin_fusioninventory']['task'][28]="Action";
$LANG['plugin_fusioninventory']['task'][29]="Type";
$LANG['plugin_fusioninventory']['task'][30]="Selection";
$LANG['plugin_fusioninventory']['task'][31]="Temps entre le démarrage de la tâche et le démarrage de cette action";
$LANG['plugin_fusioninventory']['task'][32]="Forcer l'arrêt";
$LANG['plugin_fusioninventory']['task'][33]="Communication";
$LANG['plugin_fusioninventory']['task'][34]="Permanente";
$LANG['plugin_fusioninventory']['task'][35]="minutes";
$LANG['plugin_fusioninventory']['task'][36]="heures";
$LANG['plugin_fusioninventory']['task'][37]="jours";
$LANG['plugin_fusioninventory']['task'][38]="mois";
$LANG['plugin_fusioninventory']['task'][39]="Impossible de lancer la tâche car il reste des actions en cours!";
$LANG['plugin_fusioninventory']['task'][40]="Forcer l'exécution";

$LANG['plugin_fusioninventory']['taskjoblog'][1]="Démarré";
$LANG['plugin_fusioninventory']['taskjoblog'][2]="Ok";
$LANG['plugin_fusioninventory']['taskjoblog'][3]="Erreur / replannifié";
$LANG['plugin_fusioninventory']['taskjoblog'][4]="Erreur";
$LANG['plugin_fusioninventory']['taskjoblog'][5]="Inconnu";
$LANG['plugin_fusioninventory']['taskjoblog'][6]="En cours";
$LANG['plugin_fusioninventory']['taskjoblog'][7]="Préparé";

$LANG['plugin_fusioninventory']['update'][0]="Votre historique fait plus de 300 000 lignes, il faut lancer la commande suivante en ligne de commande pour finir la mise à jour : ";

$LANG['plugin_fusioninventory']['xml'][0]="XML";

$LANG['plugin_fusioninventory']['codetasklog'][1]="Mauvais jeton, impossible d'agir sur l'agent";
$LANG['plugin_fusioninventory']['codetasklog'][2]="Agent arrêté ou crashé";
?>