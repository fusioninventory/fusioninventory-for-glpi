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

$title="Déploiement FusionInventory";
$version="2.3.0-1";

$LANG['plugin_fusinvdeploy']['title'][0]="$title";

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

$LANG['plugin_fusinvdeploy']['files'][0]="Gestion des fichiers";
$LANG['plugin_fusinvdeploy']['files'][1]="Nom de fichier";
$LANG['plugin_fusinvdeploy']['files'][2]="Version";
$LANG['plugin_fusinvdeploy']['files'][3]="Plateforme";
$LANG['plugin_fusinvdeploy']['files'][4]="Fichier à télécharger";
$LANG['plugin_fusinvdeploy']['files'][5]="Dossier dans le paquet";

$LANG['plugin_fusinvdeploy']['packagefiles'][0]="Fichiers liés aux paquets";

$LANG['plugin_fusinvdeploy']['deploystatus'][0]="Etat des déploiements";

$LANG['plugin_fusinvdeploy']['config'][0]="Adresse du serveur GLPI (sans le http://)";

$LANG['plugin_fusinvdeploy']['setup'][17]="Le plugin ".$title." a besoin que le plugin FusionInventory soit activé pour être lui-même activé.";
$LANG['plugin_fusinvdeploy']['setup'][18]="Le plugin ".$title." a besoin que le plugin FusionInventory soit activé pour être lui-même désinstallé.";

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
$LANG['plugin_fusinvdeploy']['form']['label'][9] = "Si oui, pour une durée de";
$LANG['plugin_fusinvdeploy']['form']['label'][10] = "Id";
$LANG['plugin_fusinvdeploy']['form']['label'][11] = "Commande";
$LANG['plugin_fusinvdeploy']['form']['label'][12] = "Lecteur ou répertoire";
$LANG['plugin_fusinvdeploy']['form']['label'][13] = "Clef";
$LANG['plugin_fusinvdeploy']['form']['label'][14] = "Valeur de la clef";
$LANG['plugin_fusinvdeploy']['form']['label'][15] = "Fichier manquant";
$LANG['plugin_fusinvdeploy']['form']['label'][16] = "De";
$LANG['plugin_fusinvdeploy']['form']['label'][17] = "Vers";
$LANG['plugin_fusinvdeploy']['form']['label'][18] = "Suppression";

$LANG['plugin_fusinvdeploy']['form']['action'][0] = "Ajouter";
$LANG['plugin_fusinvdeploy']['form']['action'][1] = "Supprimer";
$LANG['plugin_fusinvdeploy']['form']['action'][2] = "Sauvegarder";
$LANG['plugin_fusinvdeploy']['form']['action'][3] = "Selectionnez votre fichier";
$LANG['plugin_fusinvdeploy']['form']['action'][4] = "Fichier bien enregistré!";
$LANG['plugin_fusinvdeploy']['form']['action'][5] = "Ou URL";

$LANG['plugin_fusinvdeploy']['form']['title'][0] = "Editer une vérification";
$LANG['plugin_fusinvdeploy']['form']['title'][1] = "Ajouter une vérification";
$LANG['plugin_fusinvdeploy']['form']['title'][2] = "Liste des vérifications";
$LANG['plugin_fusinvdeploy']['form']['title'][3] = "Liste des fichiers";
$LANG['plugin_fusinvdeploy']['form']['title'][4] = "Ajouter un fichier";
$LANG['plugin_fusinvdeploy']['form']['title'][5] = "Editer un fichier";
$LANG['plugin_fusinvdeploy']['form']['title'][6] = "Ajouter une action";
$LANG['plugin_fusinvdeploy']['form']['title'][7] = "Editer une action";
$LANG['plugin_fusinvdeploy']['form']['title'][8] = "Liste des actions";

$LANG['plugin_fusinvdeploy']['form']['message'][0] = "Formulaire vide";
$LANG['plugin_fusinvdeploy']['form']['message'][1] = "Formulaire invalide";
$LANG['plugin_fusinvdeploy']['form']['message'][2] = "Chargement...";

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
$LANG['plugin_fusinvdeploy']['form']['command_status'][3] = "RETURNCODE_OK";
$LANG['plugin_fusinvdeploy']['form']['command_status'][4] = "RETURNCODE_KO";
$LANG['plugin_fusinvdeploy']['form']['command_status'][5] = "REGEX_OK";
$LANG['plugin_fusinvdeploy']['form']['command_status'][6] = "REGEX_KO";

$LANG['plugin_fusinvdeploy']['form']['command_envvariable'][1] = "Variable d'environnement";

$LANG['plugin_fusinvdeploy']['form']['action_message'][1] = "Titre";
$LANG['plugin_fusinvdeploy']['form']['action_message'][2] = "Contenu";
$LANG['plugin_fusinvdeploy']['form']['action_message'][3] = "Type";
$LANG['plugin_fusinvdeploy']['form']['action_message'][4] = "Informations";
$LANG['plugin_fusinvdeploy']['form']['action_message'][5] = "Report de l\'installation";

?>
