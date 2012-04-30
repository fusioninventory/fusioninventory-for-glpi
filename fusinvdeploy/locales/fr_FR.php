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

$title="Déploiement FusionInventory";
$version="2.3.0-1";

$LANG['plugin_fusinvdeploy']['title'][0]="$title";

$LANG['plugin_fusinvdeploy']['massiveactions'][0]="Associer une tâche";
$LANG['plugin_fusinvdeploy']['massiveactions'][1]="Créer une action pour chaque ordinateur";
$LANG['plugin_fusinvdeploy']['massiveactions'][2]="Créer une action pour chaque groupe";

$LANG['plugin_fusinvdeploy']['package'][0]="Actions";
$LANG['plugin_fusinvdeploy']['package'][1]="Exécuter une commande";
$LANG['plugin_fusinvdeploy']['package'][2]="Lancer (exécutable dans le paquet)";
$LANG['plugin_fusinvdeploy']['package'][3]="Exécuter (exécutable du système)";
$LANG['plugin_fusinvdeploy']['package'][4]="Stocker";
$LANG['plugin_fusinvdeploy']['package'][5]="Paquets";
$LANG['plugin_fusinvdeploy']['package'][6]="Gestion des paquets";
$LANG['plugin_fusinvdeploy']['package'][7]="Paquet";
$LANG['plugin_fusinvdeploy']['package'][8]="Gestion des paquets";
$LANG['plugin_fusinvdeploy']['package'][9]="Nombre de fragments";
$LANG['plugin_fusinvdeploy']['package'][10]="Module";
$LANG['plugin_fusinvdeploy']['package'][11]="Vérifications";
$LANG['plugin_fusinvdeploy']['package'][12]="Fichiers";
$LANG['plugin_fusinvdeploy']['package'][13]="Actions";
$LANG['plugin_fusinvdeploy']['package'][14]="Installation";
$LANG['plugin_fusinvdeploy']['package'][15]="Désinstallation";
$LANG['plugin_fusinvdeploy']['package'][16]="Installation d'un paquet";
$LANG['plugin_fusinvdeploy']['package'][17]="Désinstallation d'un paquet";
$LANG['plugin_fusinvdeploy']['package'][18]="Déplacer un fichier";
$LANG['plugin_fusinvdeploy']['package'][19]="Fragments de fichiers";
$LANG['plugin_fusinvdeploy']['package'][20]="Supprimer un fichier";
$LANG['plugin_fusinvdeploy']['package'][21]="Afficher une boite de dialogue";
$LANG['plugin_fusinvdeploy']['package'][22]="Codes retour";
$LANG['plugin_fusinvdeploy']['package'][23]="Une ou plusieurs tâche(s) (#task#) utilise ce paquet, suppression impossible";
$LANG['plugin_fusinvdeploy']['package'][24]="Une ou plusieurs tâche(s) (#task#) utilise ce paquet, édition impossible";
$LANG['plugin_fusinvdeploy']['package'][25]="Nouveau nom";
$LANG['plugin_fusinvdeploy']['package'][26]="Ajouter un paquet";
$LANG['plugin_fusinvdeploy']['package'][27]="Créer un répertoire";
$LANG['plugin_fusinvdeploy']['package'][28]="Copier un fichier";

$LANG['plugin_fusinvdeploy']['files'][0]="Gestion des fichiers";
$LANG['plugin_fusinvdeploy']['files'][1]="Nom de fichier";
$LANG['plugin_fusinvdeploy']['files'][2]="Version";
$LANG['plugin_fusinvdeploy']['files'][3]="Plateforme";
$LANG['plugin_fusinvdeploy']['files'][4]="Fichier à télécharger";
$LANG['plugin_fusinvdeploy']['files'][5]="Dossier dans le paquet";
$LANG['plugin_fusinvdeploy']['files'][6]="Taille maximale des fichiers";
$LANG['plugin_fusinvdeploy']['files'][7]="Télécharger depuis";
$LANG['plugin_fusinvdeploy']['files'][8]="Mon ordinateur";
$LANG['plugin_fusinvdeploy']['files'][9]="Le serveur";

$LANG['plugin_fusinvdeploy']['packagefiles'][0]="Fichiers liés aux paquets";

$LANG['plugin_fusinvdeploy']['deploystatus'][0]="Etat des déploiements";
$LANG['plugin_fusinvdeploy']['deploystatus'][1]="logs associés";
$LANG['plugin_fusinvdeploy']['deploystatus'][2]="L'agent a reçu l'ordre";
$LANG['plugin_fusinvdeploy']['deploystatus'][3]="L'agent a commencé à vérifier le miroir pour télécharger le fichier";
$LANG['plugin_fusinvdeploy']['deploystatus'][4]="Preparation du répertoire de travail";
$LANG['plugin_fusinvdeploy']['deploystatus'][5]="L'agent traite l'ordre";

$LANG['plugin_fusinvdeploy']['config'][0]="Adresse du serveur GLPI (sans le http://)";
$LANG['plugin_fusinvdeploy']['config'][1]="Dossier racine pour l'envoi de fichier depuis le serveur";

$LANG['plugin_fusinvdeploy']['setup'][17]="Le plugin ".$title." a besoin que le plugin FusionInventory soit activé pour être lui-même activé.";
$LANG['plugin_fusinvdeploy']['setup'][18]="Le plugin ".$title." a besoin que le plugin FusionInventory soit activé pour être lui-même désinstallé.";
$LANG['plugin_fusinvdeploy']['setup'][19]="Le plugin ".$title." a besoin que le plugin Webservices (>= 1.2.0) soit installé pour être lui-même activé.";
$LANG['plugin_fusinvdeploy']['setup'][20]="Le plugin ".$title." a besoin que le plugin Webservices (>= 1.2.0) soit installé pour être lui-même désinstallé.";
$LANG['plugin_fusinvdeploy']['setup'][21]="Le plugin ".$title." a besoin que le plugin FusionInventory INVENTORY soit installé pour être lui-même activé.";

$LANG['plugin_fusinvdeploy']['profile'][1]="$title";
$LANG['plugin_fusinvdeploy']['profile'][2]="Gestion des paquets";
$LANG['plugin_fusinvdeploy']['profile'][3]="Statut des déploiements";


$LANG['plugin_fusinvdeploy']['form']['label'][0] = "Type";
$LANG['plugin_fusinvdeploy']['form']['label'][1] = "Nom";
$LANG['plugin_fusinvdeploy']['form']['label'][2] = "Valeur";
$LANG['plugin_fusinvdeploy']['form']['label'][3] = "Unité";
$LANG['plugin_fusinvdeploy']['form']['label'][4] = "Activé";
$LANG['plugin_fusinvdeploy']['form']['label'][5] = "Fichier";
$LANG['plugin_fusinvdeploy']['form']['label'][6] = "Déploiement P2P";
$LANG['plugin_fusinvdeploy']['form']['label'][7] = "Date d&#145;ajout";
$LANG['plugin_fusinvdeploy']['form']['label'][8] = "Durée de validité";
$LANG['plugin_fusinvdeploy']['form']['label'][9] = "Rétention des données durant (jours)";
$LANG['plugin_fusinvdeploy']['form']['label'][10] = "Id";
$LANG['plugin_fusinvdeploy']['form']['label'][11] = "Commande";
$LANG['plugin_fusinvdeploy']['form']['label'][12] = "Lecteur ou répertoire";
$LANG['plugin_fusinvdeploy']['form']['label'][13] = "Clef";
$LANG['plugin_fusinvdeploy']['form']['label'][14] = "Valeur de la clef";
$LANG['plugin_fusinvdeploy']['form']['label'][15] = "Fichier manquant";
$LANG['plugin_fusinvdeploy']['form']['label'][16] = "De";
$LANG['plugin_fusinvdeploy']['form']['label'][17] = "Vers";
$LANG['plugin_fusinvdeploy']['form']['label'][18] = "Suppression";
$LANG['plugin_fusinvdeploy']['form']['label'][19] = "Décompresser";
$LANG['plugin_fusinvdeploy']['form']['label'][20] = "Erreur de transfert : la taille du fichier est trop grosse";
$LANG['plugin_fusinvdeploy']['form']['label'][21] = "Taille";
$LANG['plugin_fusinvdeploy']['form']['label'][22] = "Erreur lors de la copie du fichier";

$LANG['plugin_fusinvdeploy']['form']['action'][0] = "Ajouter";
$LANG['plugin_fusinvdeploy']['form']['action'][1] = "Supprimer";
$LANG['plugin_fusinvdeploy']['form']['action'][2] = "Valider";
$LANG['plugin_fusinvdeploy']['form']['action'][3] = "Selectionnez votre fichier";
$LANG['plugin_fusinvdeploy']['form']['action'][4] = "Fichier bien enregistré!";
$LANG['plugin_fusinvdeploy']['form']['action'][5] = "Ou URL";
$LANG['plugin_fusinvdeploy']['form']['action'][6] = "Ajouter un code retour";
$LANG['plugin_fusinvdeploy']['form']['action'][7] = "Supprimer un code retour";

$LANG['plugin_fusinvdeploy']['form']['title'][0] = "Editer un contrôle";
$LANG['plugin_fusinvdeploy']['form']['title'][1] = "Ajouter un contrôle";
$LANG['plugin_fusinvdeploy']['form']['title'][2] = "Contrôles à effectuer";
$LANG['plugin_fusinvdeploy']['form']['title'][3] = "Fichiers à copier sur la machine";
$LANG['plugin_fusinvdeploy']['form']['title'][4] = "Ajouter un fichier";
$LANG['plugin_fusinvdeploy']['form']['title'][5] = "Editer un fichier";
$LANG['plugin_fusinvdeploy']['form']['title'][6] = "Ajouter une action";
$LANG['plugin_fusinvdeploy']['form']['title'][7] = "Editer une action";
$LANG['plugin_fusinvdeploy']['form']['title'][8] = "Actions à réaliser";
$LANG['plugin_fusinvdeploy']['form']['title'][9] = "Supprimer contrôle";
$LANG['plugin_fusinvdeploy']['form']['title'][10] = "Ajouter un ordre";
$LANG['plugin_fusinvdeploy']['form']['title'][11] = "Supprimer un ordre";
$LANG['plugin_fusinvdeploy']['form']['title'][12] = "Editer un ordre";
$LANG['plugin_fusinvdeploy']['form']['title'][13] = "Supprimer fichier";
$LANG['plugin_fusinvdeploy']['form']['title'][14] = "Supprimer action";
$LANG['plugin_fusinvdeploy']['form']['title'][15] = "durant l\'installation";
$LANG['plugin_fusinvdeploy']['form']['title'][16] = "durant la désinstallation";
$LANG['plugin_fusinvdeploy']['form']['title'][17] = "avant l\'installation";
$LANG['plugin_fusinvdeploy']['form']['title'][18] = "avant la désinstallation";

$LANG['plugin_fusinvdeploy']['form']['message'][0] = "Formulaire vide";
$LANG['plugin_fusinvdeploy']['form']['message'][1] = "Formulaire invalide";
$LANG['plugin_fusinvdeploy']['form']['message'][2] = "Chargement...";
$LANG['plugin_fusinvdeploy']['form']['message'][3] = "Fichier déjà existant";

$LANG['plugin_fusinvdeploy']['form']['check'][0] = "Clef de registre existe";
$LANG['plugin_fusinvdeploy']['form']['check'][1] = "Clef de registre n\'existe pas";
$LANG['plugin_fusinvdeploy']['form']['check'][2] = "Clef de registre est égale";
$LANG['plugin_fusinvdeploy']['form']['check'][3] = "Fichier existe";
$LANG['plugin_fusinvdeploy']['form']['check'][4] = "Fichier n\'existe pas";
$LANG['plugin_fusinvdeploy']['form']['check'][5] = "Taille du fichier supérieure";
$LANG['plugin_fusinvdeploy']['form']['check'][6] = "Hash512 du fichier";
$LANG['plugin_fusinvdeploy']['form']['check'][7] = "Espace libre";
$LANG['plugin_fusinvdeploy']['form']['check'][8] = "Taille du fichier égale";
$LANG['plugin_fusinvdeploy']['form']['check'][9] = "Taille du fichier inférieure";

$LANG['plugin_fusinvdeploy']['form']['mirror'][1] = "Mirroir";
$LANG['plugin_fusinvdeploy']['form']['mirror'][2] = "Mirroirs";
$LANG['plugin_fusinvdeploy']['form']['mirror'][3] = "Adresse du mirroir";

$LANG['plugin_fusinvdeploy']['form']['command_status'][0] = "Faites votre choix...";
$LANG['plugin_fusinvdeploy']['form']['command_status'][1] = "Type";
$LANG['plugin_fusinvdeploy']['form']['command_status'][2] = "Valeur";
$LANG['plugin_fusinvdeploy']['form']['command_status'][3] = "Code retour attendu";
$LANG['plugin_fusinvdeploy']['form']['command_status'][4] = "Code retour invalide";
$LANG['plugin_fusinvdeploy']['form']['command_status'][5] = "Expression r&eacute;guli&egrave;re attendue";
$LANG['plugin_fusinvdeploy']['form']['command_status'][6] = "Expression r&eacute;guli&egrave;re invalide";

$LANG['plugin_fusinvdeploy']['form']['command_envvariable'][1] = "Variable d'environnement";

$LANG['plugin_fusinvdeploy']['form']['action_message'][1] = "Titre";
$LANG['plugin_fusinvdeploy']['form']['action_message'][2] = "Contenu";
$LANG['plugin_fusinvdeploy']['form']['action_message'][3] = "Type";
$LANG['plugin_fusinvdeploy']['form']['action_message'][4] = "Informations";
$LANG['plugin_fusinvdeploy']['form']['action_message'][5] = "Report de l\'installation";

$LANG['plugin_fusinvdeploy']['task'][0] = "Tâches de deploiement";
$LANG['plugin_fusinvdeploy']['task'][1] = "Tâches";
$LANG['plugin_fusinvdeploy']['task'][3] = "Ajouter tâche";
$LANG['plugin_fusinvdeploy']['task'][5] = "Tâche";
$LANG['plugin_fusinvdeploy']['task'][7] = "Actions";
$LANG['plugin_fusinvdeploy']['task'][8] = "Liste des actions";
$LANG['plugin_fusinvdeploy']['task'][11] = "Editer une tâche";
$LANG['plugin_fusinvdeploy']['task'][12] = "Ajouter une tâche";
$LANG['plugin_fusinvdeploy']['task'][13] = "Liste des ordres";
$LANG['plugin_fusinvdeploy']['task'][14] = "Options avancées";
$LANG['plugin_fusinvdeploy']['task'][15] = "Ajouter un ordre";
$LANG['plugin_fusinvdeploy']['task'][16] = "Supprimer un ordre";
$LANG['plugin_fusinvdeploy']['task'][17] = "Modifier un ordre";
$LANG['plugin_fusinvdeploy']['task'][18] = "---";
$LANG['plugin_fusinvdeploy']['task'][19] = "Edition impossible, cette tâche est active";
$LANG['plugin_fusinvdeploy']['task'][20] = "Tâche active, suppression impossible";

$LANG['plugin_fusinvdeploy']['group'][0] = "Ensembles d'ordinateurs";
$LANG['plugin_fusinvdeploy']['group'][1] = "Ensemble statique";
$LANG['plugin_fusinvdeploy']['group'][2] = "Ensemble dynamique";
$LANG['plugin_fusinvdeploy']['group'][3] = "Ensemble d'ordinateur";
$LANG['plugin_fusinvdeploy']['group'][4] = "Ajouter ensemble";
$LANG['plugin_fusinvdeploy']['group'][5] = "Si aucune ligne de la liste n'est selectionné, le champ texte de gauche sera utilisé pour la recherche";

$LANG['plugin_fusinvdeploy']['menu'][1] = "Gestion des paquets";
$LANG['plugin_fusinvdeploy']['menu'][2] = "Mirroir";
$LANG['plugin_fusinvdeploy']['menu'][3] = "Tâches de deploiement";
$LANG['plugin_fusinvdeploy']['menu'][4] = "Ensembles d'ordinateurs";
$LANG['plugin_fusinvdeploy']['menu'][5] = "Etat des déploiements";
?>
