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

$title="FusionInventory";
$version="2.2.0";

$LANG['plugin_fusioninventory']["title"][0]="$title";
$LANG['plugin_fusioninventory']["title"][1]="Informations SNMP";
$LANG['plugin_fusioninventory']["title"][2]="Historique de connexion";
$LANG['plugin_fusioninventory']["title"][3]="[Trk] Erreurs";
$LANG['plugin_fusioninventory']["title"][4]="[Trk] Cron";
$LANG['plugin_fusioninventory']["title"][5]="Verrous FusionInventory";

$LANG['plugin_fusioninventory']['config'][0] = "Fréquence des inventaires (en heures)";
$LANG['plugin_fusioninventory']['config'][1] = "Modules";
$LANG['plugin_fusioninventory']['config'][2] = "Snmp";
$LANG['plugin_fusioninventory']['config'][3] = "Inventaire";
$LANG['plugin_fusioninventory']['config'][4] = "Découverte d'équipements";
$LANG['plugin_fusioninventory']['config'][5] = "Controle de l'agent à partir de GLPI";
$LANG['plugin_fusioninventory']['config'][6] = "Wake On Lan";
$LANG['plugin_fusioninventory']['config'][7] = "Interrogation SNMP";

$LANG['plugin_fusioninventory']["profile"][0]="Gestion des droits";
$LANG['plugin_fusioninventory']["profile"][1]="$title"; //interface

$LANG['plugin_fusioninventory']["profile"][10]="Listes des profils déjà configurés";
$LANG['plugin_fusioninventory']["profile"][11]="Historique Ordinateurs";
$LANG['plugin_fusioninventory']["profile"][12]="Historique Imprimantes";
$LANG['plugin_fusioninventory']["profile"][13]="Infos Imprimantes";
$LANG['plugin_fusioninventory']["profile"][14]="Infos Réseau";
$LANG['plugin_fusioninventory']["profile"][15]="Erreurs courantes";

$LANG['plugin_fusioninventory']["profile"][16]="SNMP Réseaux";
$LANG['plugin_fusioninventory']["profile"][17]="SNMP Périphériques";
$LANG['plugin_fusioninventory']["profile"][18]="SNMP Imprimantes";
$LANG['plugin_fusioninventory']["profile"][19]="Modèles SNMP";
$LANG['plugin_fusioninventory']["profile"][20]="Authentification SNMP";
$LANG['plugin_fusioninventory']["profile"][21]="Infos scripts";
$LANG['plugin_fusioninventory']["profile"][22]="Découverte réseau";
$LANG['plugin_fusioninventory']["profile"][23]="Configuration";
$LANG['plugin_fusioninventory']["profile"][24]="Modèle SNMP";
$LANG['plugin_fusioninventory']["profile"][25]="Plages IP";
$LANG['plugin_fusioninventory']["profile"][26]="Agents";
$LANG['plugin_fusioninventory']["profile"][27]="Processus des agents";
$LANG['plugin_fusioninventory']["profile"][28]="Rapport";
$LANG['plugin_fusioninventory']["profile"][29]="Controle des agents à distance";
$LANG['plugin_fusioninventory']["profile"][30]="Matériel inconnu";
$LANG['plugin_fusioninventory']["profile"][31]="Inventaire machine FusionInventory";
$LANG['plugin_fusioninventory']["profile"][32]="Interrogation SNMP";
$LANG['plugin_fusioninventory']["profile"][33]="WakeOnLan";
$LANG['plugin_fusioninventory']["profile"][34]="Actions";

$LANG['plugin_fusioninventory']["setup"][2]="Merci de vous placer sur l'entité racine (voir tous)";
$LANG['plugin_fusioninventory']["setup"][3]="Configuration du plugin ".$title;
$LANG['plugin_fusioninventory']["setup"][4]="Installer le plugin $title $version";
$LANG['plugin_fusioninventory']["setup"][5]="Mettre à jour le plugin $title vers la version $version";
$LANG['plugin_fusioninventory']["setup"][6]="Désinstaller le plugin $title $version";
$LANG['plugin_fusioninventory']["setup"][8]="Attention, la désinstallation du plugin est irréversible.<br> Vous perdrez toutes les données.";
$LANG['plugin_fusioninventory']["setup"][11]="Mode d'emploi";
$LANG['plugin_fusioninventory']["setup"][12]="FAQ";
$LANG['plugin_fusioninventory']["setup"][13]="Vérification des modules PHP nécessaires";
$LANG['plugin_fusioninventory']["setup"][14]="L'extension snmp de PHP n'est pas chargée";
$LANG['plugin_fusioninventory']["setup"][15]="L'extension runkit de PHP/PECL n'est pas chargée";
$LANG['plugin_fusioninventory']["setup"][16]="Documentation";

$LANG['plugin_fusioninventory']["functionalities"][0]="Fonctionnalités";
$LANG['plugin_fusioninventory']["functionalities"][1]="Ajout / Suppression de fonctionnalités";
$LANG['plugin_fusioninventory']["functionalities"][2]="Configuration générale";
$LANG['plugin_fusioninventory']["functionalities"][3]="SNMP";
$LANG['plugin_fusioninventory']["functionalities"][4]="Connexion";
$LANG['plugin_fusioninventory']["functionalities"][5]="Script serveur";
$LANG['plugin_fusioninventory']["functionalities"][6]="Légende";
$LANG['plugin_fusioninventory']["functionalities"][7]="Champs verrouillables";

$LANG['plugin_fusioninventory']["functionalities"][9]="Rétention en jours";
$LANG['plugin_fusioninventory']["functionalities"][10]="Activation de l'historique";
$LANG['plugin_fusioninventory']["functionalities"][11]="Activation du module connexion";
$LANG['plugin_fusioninventory']["functionalities"][12]="Activation du module SNMP réseaux";
$LANG['plugin_fusioninventory']["functionalities"][13]="Activation du module SNMP périphériques";
$LANG['plugin_fusioninventory']["functionalities"][14]="Activation du module SNMP téléphones";
$LANG['plugin_fusioninventory']["functionalities"][15]="Activation du module SNMP imprimantes";
$LANG['plugin_fusioninventory']["functionalities"][16]="Stockage de l'authentification SNMP";
$LANG['plugin_fusioninventory']["functionalities"][17]="Base de données";
$LANG['plugin_fusioninventory']["functionalities"][18]="Fichiers";
$LANG['plugin_fusioninventory']["functionalities"][19]="Veuillez configurer le stockage de l'authentification SNMP dans la configuration du plugin";
$LANG['plugin_fusioninventory']["functionalities"][20]="Statut du matériel actif";
$LANG['plugin_fusioninventory']["functionalities"][21]="Rétention de l'historique d'interconnexions entre matériels en jours (0 = infini)";
$LANG['plugin_fusioninventory']["functionalities"][22]="Rétention de l'historique de changement d'état des ports (0 = infini)";
$LANG['plugin_fusioninventory']["functionalities"][23]="Rétention de l'historique des adresses MAC inconnues (0 = infini)";
$LANG['plugin_fusioninventory']["functionalities"][24]="Rétention de l'historique des erreurs SNMP (0 = infini)";
$LANG['plugin_fusioninventory']["functionalities"][25]="Rétention de l'historique des processes des scripts (0 = infini)";
$LANG['plugin_fusioninventory']["functionalities"][26]="URL de GLPI pour l'agent";
$LANG['plugin_fusioninventory']["functionalities"][27]="SSL seulement pour l'agent";
$LANG['plugin_fusioninventory']["functionalities"][28]="Configuration de l'historique";
$LANG['plugin_fusioninventory']["functionalities"][29]="Liste des champs à historiser";

$LANG['plugin_fusioninventory']["functionalities"][30]="Statut du matériel actif";
$LANG['plugin_fusioninventory']["functionalities"][31]="Gestion des cartouches et du stock";
$LANG['plugin_fusioninventory']["functionalities"][32]="Effacer les informations des process agent après";
$LANG['plugin_fusioninventory']["functionalities"][36]="Fréquence de relevé des compteurs";

$LANG['plugin_fusioninventory']["functionalities"][40]="Configuration";
$LANG['plugin_fusioninventory']["functionalities"][41]="Statut du matériel actif";
$LANG['plugin_fusioninventory']["functionalities"][42]="Commutateur";
$LANG['plugin_fusioninventory']["functionalities"][43]="Authentification SNMP";

$LANG['plugin_fusioninventory']["functionalities"][50]="Nombre de process simultanés pour la découverte réseau";
$LANG['plugin_fusioninventory']["functionalities"][51]="Nombre de process simultanés pour l'interrogation SNMP";
$LANG['plugin_fusioninventory']["functionalities"][52]="Activation des journaux";
$LANG['plugin_fusioninventory']["functionalities"][53]="Nombre de process simultanés pour le script serveur de post-traitement";

$LANG['plugin_fusioninventory']["functionalities"][60]="Nettoyage de l'historique";

$LANG['plugin_fusioninventory']["functionalities"][70]="Configuration des champs verrouillables";
$LANG['plugin_fusioninventory']["functionalities"][71]="Champs non verrouillables";
$LANG['plugin_fusioninventory']["functionalities"][72]="Table";
$LANG['plugin_fusioninventory']["functionalities"][73]="Champs";
$LANG['plugin_fusioninventory']["functionalities"][74]="Valeurs";
$LANG['plugin_fusioninventory']["functionalities"][75]="Verrous";

$LANG['plugin_fusioninventory']["snmp"][0]="Informations SNMP du matériel";
$LANG['plugin_fusioninventory']["snmp"][1]="Général";
$LANG['plugin_fusioninventory']["snmp"][2]="Cablâge";
$LANG['plugin_fusioninventory']["snmp"][2]="Données SNMP";

$LANG['plugin_fusioninventory']["snmp"][11]="Informations supplémentaires";
$LANG['plugin_fusioninventory']["snmp"][12]="Uptime";
$LANG['plugin_fusioninventory']["snmp"][13]="Utilisation du CPU (en %)";
$LANG['plugin_fusioninventory']["snmp"][14]="Utilisation de la mémoire (en %)";

$LANG['plugin_fusioninventory']["snmp"][31]="Impossible de récupérer les infos SNMP : Ce n'est pas un commutateur !";
$LANG['plugin_fusioninventory']["snmp"][32]="Impossible de récupérer les infos SNMP : Matériel non actif !";
$LANG['plugin_fusioninventory']["snmp"][33]="Impossible de récupérer les infos SNMP : IP non précisée dans la base !";
$LANG['plugin_fusioninventory']["snmp"][34]="Le commutateur auquel est reliée la machine n'est pas renseigné !";

$LANG['plugin_fusioninventory']["snmp"][40]="Tableau des ports";
$LANG['plugin_fusioninventory']["snmp"][41]="Description du port";
$LANG['plugin_fusioninventory']["snmp"][42]="MTU";
$LANG['plugin_fusioninventory']["snmp"][43]="Vitesse";
$LANG['plugin_fusioninventory']["snmp"][44]="Statut Interne";
$LANG['plugin_fusioninventory']["snmp"][45]="Dernier changement";
$LANG['plugin_fusioninventory']["snmp"][46]="Nb d'octets recus";
$LANG['plugin_fusioninventory']["snmp"][47]="Nb d'erreurs en entrée";
$LANG['plugin_fusioninventory']["snmp"][48]="Nb d'octets envoyés";
$LANG['plugin_fusioninventory']["snmp"][49]="Nb d'erreurs en réception";
$LANG['plugin_fusioninventory']["snmp"][50]="Connexion";
$LANG['plugin_fusioninventory']["snmp"][51]="Duplex";
$LANG['plugin_fusioninventory']["snmp"][52]="Date dernier inventaire FusionInventory";
$LANG['plugin_fusioninventory']["snmp"][53]="Dernier inventaire";

$LANG['plugin_fusioninventory']["snmpauth"][1]="Communauté";
$LANG['plugin_fusioninventory']["snmpauth"][2]="Utilisateur";
$LANG['plugin_fusioninventory']["snmpauth"][3]="Schéma d'authentification";
$LANG['plugin_fusioninventory']["snmpauth"][4]="Protocole de cryptage pour authentification ";
$LANG['plugin_fusioninventory']["snmpauth"][5]="Mot de passe";
$LANG['plugin_fusioninventory']["snmpauth"][6]="Protocole de cryptage pour les données (écriture)";
$LANG['plugin_fusioninventory']["snmpauth"][7]="Mot de passe (écriture)";

$LANG['plugin_fusioninventory']["cron"][0]="Relevé automatique du compteur";
$LANG['plugin_fusioninventory']["cron"][1]="Activer le relevé";
$LANG['plugin_fusioninventory']["cron"][2]="";
$LANG['plugin_fusioninventory']["cron"][3]="Défaut";

$LANG['plugin_fusioninventory']["errors"][0]="Erreurs";
$LANG['plugin_fusioninventory']["errors"][1]="IP";
$LANG['plugin_fusioninventory']["errors"][2]="Description";
$LANG['plugin_fusioninventory']["errors"][3]="Date 1er pb";
$LANG['plugin_fusioninventory']["errors"][4]="Date dernier pb";

$LANG['plugin_fusioninventory']["errors"][10]="Incohérence avec la base GLPI";
$LANG['plugin_fusioninventory']["errors"][11]="Poste inconnu";
$LANG['plugin_fusioninventory']["errors"][12]="IP inconnue";

$LANG['plugin_fusioninventory']["errors"][20]="Erreur SNMP";
$LANG['plugin_fusioninventory']["errors"][21]="Impossible de récupérer les informations";
$LANG['plugin_fusioninventory']["errors"][22]="Elément inattendu dans";
$LANG['plugin_fusioninventory']["errors"][23]="Impossible d identifier le matériel";

$LANG['plugin_fusioninventory']["errors"][30]="Erreur Câblage";
$LANG['plugin_fusioninventory']["errors"][31]="Problème de câblage";

$LANG['plugin_fusioninventory']["errors"][50]="La version de GLPI n'est pas compatible, vous avez besoin de la version 0.78";

$LANG['plugin_fusioninventory']["errors"][101]="Timeout";
$LANG['plugin_fusioninventory']["errors"][102]="Modele SNMP non assigné";
$LANG['plugin_fusioninventory']["errors"][103]="Authentification SNMP non assigné";
$LANG['plugin_fusioninventory']["errors"][104]="Message d'erreur";

$LANG['plugin_fusioninventory']["history"][0] = "Ancienne";
$LANG['plugin_fusioninventory']["history"][1] = "Nouvelle";
$LANG['plugin_fusioninventory']["history"][2] = "Déconnexion";
$LANG['plugin_fusioninventory']["history"][3] = "Connexion";

$LANG['plugin_fusioninventory']["prt_history"][0]="Historique et Statistiques des compteurs imprimante";

$LANG['plugin_fusioninventory']["prt_history"][10]="Statistiques des compteurs imprimante sur";
$LANG['plugin_fusioninventory']["prt_history"][11]="jour(s)";
$LANG['plugin_fusioninventory']["prt_history"][12]="Pages imprimées totales";
$LANG['plugin_fusioninventory']["prt_history"][13]="Pages / jour";

$LANG['plugin_fusioninventory']["prt_history"][20]="Historique des compteurs imprimante";
$LANG['plugin_fusioninventory']["prt_history"][21]="Date";
$LANG['plugin_fusioninventory']["prt_history"][22]="Compteur";

$LANG['plugin_fusioninventory']["prt_history"][30]="Affichage";
$LANG['plugin_fusioninventory']["prt_history"][31]="Unité de temps";
$LANG['plugin_fusioninventory']["prt_history"][32]="Ajouter une imprimante";
$LANG['plugin_fusioninventory']["prt_history"][33]="Supprimer une imprimante";
$LANG['plugin_fusioninventory']["prt_history"][34]="jour";
$LANG['plugin_fusioninventory']["prt_history"][35]="semaine";
$LANG['plugin_fusioninventory']["prt_history"][36]="mois";
$LANG['plugin_fusioninventory']["prt_history"][37]="année";

$LANG['plugin_fusioninventory']["cpt_history"][0]="Historique des sessions";
$LANG['plugin_fusioninventory']["cpt_history"][1]="Contact";
$LANG['plugin_fusioninventory']["cpt_history"][2]="Ordinateur";
$LANG['plugin_fusioninventory']["cpt_history"][3]="Utilisateur";
$LANG['plugin_fusioninventory']["cpt_history"][4]="Etat";
$LANG['plugin_fusioninventory']["cpt_history"][5]="Date";

$LANG['plugin_fusioninventory']["type"][1]="Ordinateur";
$LANG['plugin_fusioninventory']["type"][2]="Commutateur";
$LANG['plugin_fusioninventory']["type"][3]="Imprimante";

$LANG['plugin_fusioninventory']["rules"][1]="Règles";

$LANG['plugin_fusioninventory']["massiveaction"][1]="Assigner un modèle SNMP";
$LANG['plugin_fusioninventory']["massiveaction"][2]="Assigner une authentification SNMP";

$LANG['plugin_fusioninventory']["model_info"][1]="Informations SNMP";
$LANG['plugin_fusioninventory']["model_info"][2]="Version SNMP";
$LANG['plugin_fusioninventory']["model_info"][3]="Authentification SNMP";
$LANG['plugin_fusioninventory']["model_info"][4]="Modèles SNMP";
$LANG['plugin_fusioninventory']["model_info"][5]="Gestion des MIB";
$LANG['plugin_fusioninventory']["model_info"][6]="Edition de modèle SNMP";
$LANG['plugin_fusioninventory']["model_info"][7]="Création de modèle SNMP";
$LANG['plugin_fusioninventory']["model_info"][8]="Modèle déjà existant : import non effectué";
$LANG['plugin_fusioninventory']["model_info"][9]="Import effectué avec succès";
$LANG['plugin_fusioninventory']["model_info"][10]="Importation de modèle";
$LANG['plugin_fusioninventory']["model_info"][11]="Activation";
$LANG['plugin_fusioninventory']["model_info"][12]="Clé modèle pour la découverte";
$LANG['plugin_fusioninventory']["model_info"][13]="Charger le bon modèle";
$LANG['plugin_fusioninventory']["model_info"][14]="Charger le bon modèle SNMP";
$LANG['plugin_fusioninventory']["model_info"][15]="Importation en masse de modèles";
$LANG['plugin_fusioninventory']["model_info"][16]="Import en masse des modèles dans le repertoire plugins/fusioninventory/models/";

$LANG['plugin_fusioninventory']["mib"][1]="Label MIB";
$LANG['plugin_fusioninventory']["mib"][2]="Objet";
$LANG['plugin_fusioninventory']["mib"][3]="oid";
$LANG['plugin_fusioninventory']["mib"][4]="Ajouter un oid...";
$LANG['plugin_fusioninventory']["mib"][5]="Liste des oid";
$LANG['plugin_fusioninventory']["mib"][6]="Compteur de ports";
$LANG['plugin_fusioninventory']["mib"][7]="Port dynamique (.x)";
$LANG['plugin_fusioninventory']["mib"][8]="Liaison champs";
$LANG['plugin_fusioninventory']["mib"][9]="vlan";

$LANG['plugin_fusioninventory']["processes"][0]="Informations sur l'exécution du script serveur";
$LANG['plugin_fusioninventory']["processes"][1]="PID";
$LANG['plugin_fusioninventory']["processes"][2]="Statut";
$LANG['plugin_fusioninventory']["processes"][3]="Nombre de process";
$LANG['plugin_fusioninventory']["processes"][4]="Date de début";
$LANG['plugin_fusioninventory']["processes"][5]="Date de fin";
$LANG['plugin_fusioninventory']["processes"][6]="Equipements réseau traités";
$LANG['plugin_fusioninventory']["processes"][7]="Imprimantes traitées";
$LANG['plugin_fusioninventory']["processes"][8]="Ports réseau traités";
$LANG['plugin_fusioninventory']["processes"][9]="Erreurs";
$LANG['plugin_fusioninventory']["processes"][10]="Durée totale";
$LANG['plugin_fusioninventory']["processes"][11]="Champs ajoutés";
$LANG['plugin_fusioninventory']["processes"][12]="Erreurs SNMP";
$LANG['plugin_fusioninventory']["processes"][13]="MAC inconnues";
$LANG['plugin_fusioninventory']["processes"][14]="Liste des adresse MAC inconnues";
$LANG['plugin_fusioninventory']["processes"][15]="Premier PID";
$LANG['plugin_fusioninventory']["processes"][16]="Dernier PID";
$LANG['plugin_fusioninventory']["processes"][17]="Date de la première détection";
$LANG['plugin_fusioninventory']["processes"][18]="Date de la dernière détection";
$LANG['plugin_fusioninventory']["processes"][19]="Informations sur l'exécution des agents";
$LANG['plugin_fusioninventory']["processes"][20]="Rapports / statistiques";
$LANG['plugin_fusioninventory']["processes"][21]="Equipements interrogés";
$LANG['plugin_fusioninventory']["processes"][22]="Erreurs";
$LANG['plugin_fusioninventory']["processes"][23]="Durée totale de la découverte";
$LANG['plugin_fusioninventory']["processes"][24]="Durée totale de l'interrogation";
$LANG['plugin_fusioninventory']["processes"][25]="Agent";
$LANG['plugin_fusioninventory']["processes"][26]="Découverte";
$LANG['plugin_fusioninventory']["processes"][27]="Interrogation";
$LANG['plugin_fusioninventory']["processes"][28]="Core";
$LANG['plugin_fusioninventory']["processes"][29]="Threads";
$LANG['plugin_fusioninventory']["processes"][30]="Découvert";
$LANG['plugin_fusioninventory']["processes"][31]="Existant";
$LANG['plugin_fusioninventory']["processes"][32]="Importé";
$LANG['plugin_fusioninventory']["processes"][33]="Interrogé";
$LANG['plugin_fusioninventory']["processes"][34]="En erreur";
$LANG['plugin_fusioninventory']["processes"][35]="Connexions créés";
$LANG['plugin_fusioninventory']["processes"][36]="Connexions supprimées";
$LANG['plugin_fusioninventory']["processes"][37]="Total IP";

$LANG['plugin_fusioninventory']["state"][0]="Démarrage de l'ordinateur";
$LANG['plugin_fusioninventory']["state"][1]="Arrêt de l'ordinateur";
$LANG['plugin_fusioninventory']["state"][2]="Connexion de l'utilisateur";
$LANG['plugin_fusioninventory']["state"][3]="Déconnexion de l'utilisateur";

$LANG['plugin_fusioninventory']["mapping"][1]="reseaux > lieu";
$LANG['plugin_fusioninventory']["mapping"][2]="réseaux > firmware";
$LANG['plugin_fusioninventory']["mapping"][3]="réseaux > uptime";
$LANG['plugin_fusioninventory']["mapping"][4]="réseaux > port > mtu";
$LANG['plugin_fusioninventory']["mapping"][5]="réseaux > port > vitesse";
$LANG['plugin_fusioninventory']["mapping"][6]="réseaux > port > statut interne";
$LANG['plugin_fusioninventory']["mapping"][7]="réseaux > port > Dernier changement";
$LANG['plugin_fusioninventory']["mapping"][8]="réseaux > port > nombre d'octets entrés";
$LANG['plugin_fusioninventory']["mapping"][9]="réseaux > port > nombre d'octets sortis";
$LANG['plugin_fusioninventory']["mapping"][10]="réseaux > port > nombre d'erreurs entrées";
$LANG['plugin_fusioninventory']["mapping"][11]="réseaux > port > nombre d'erreurs sorties";
$LANG['plugin_fusioninventory']["mapping"][12]="réseaux > utilisation du CPU";
$LANG['plugin_fusioninventory']["mapping"][13]="réseaux > numéro de série";
$LANG['plugin_fusioninventory']["mapping"][14]="réseaux > port > statut de la connexion";
$LANG['plugin_fusioninventory']["mapping"][15]="réseaux > port > adresse MAC";
$LANG['plugin_fusioninventory']["mapping"][16]="réseaux > port > nom";
$LANG['plugin_fusioninventory']["mapping"][17]="réseaux > modèle";
$LANG['plugin_fusioninventory']["mapping"][18]="réseaux > port > type";
$LANG['plugin_fusioninventory']["mapping"][19]="réseaux > VLAN";
$LANG['plugin_fusioninventory']["mapping"][20]="réseaux > nom";
$LANG['plugin_fusioninventory']["mapping"][21]="réseaux > mémoire totale";
$LANG['plugin_fusioninventory']["mapping"][22]="réseaux > mémoire libre";
$LANG['plugin_fusioninventory']["mapping"][23]="réseaux > port > description du port";
$LANG['plugin_fusioninventory']["mapping"][24]="imprimante > nom";
$LANG['plugin_fusioninventory']["mapping"][25]="imprimante > modèle";
$LANG['plugin_fusioninventory']["mapping"][26]="imprimante > mémoire totale";
$LANG['plugin_fusioninventory']["mapping"][27]="imprimante > numéro de série";
$LANG['plugin_fusioninventory']["mapping"][28]="imprimante > compteur > nombre total de pages imprimées";
$LANG['plugin_fusioninventory']["mapping"][29]="imprimante > compteur > nombre de pages noir et blanc imprimées";
$LANG['plugin_fusioninventory']["mapping"][30]="imprimante > compteur > nombre de pages couleur imprimées";
$LANG['plugin_fusioninventory']["mapping"][31]="imprimante > compteur > nombre de pages monochrome imprimées";
$LANG['plugin_fusioninventory']["mapping"][32]="imprimante > compteur > nombre de pages bichromie imprimées";
$LANG['plugin_fusioninventory']["mapping"][33]="réseaux > port > type de duplex";
$LANG['plugin_fusioninventory']["mapping"][34]="imprimante > consommables > cartouche noir (%)";
$LANG['plugin_fusioninventory']["mapping"][35]="imprimante > consommables > cartouche noir photo (%)";
$LANG['plugin_fusioninventory']["mapping"][36]="imprimante > consommables > cartouche cyan (%)";
$LANG['plugin_fusioninventory']["mapping"][37]="imprimante > consommables > cartouche jaune (%)";
$LANG['plugin_fusioninventory']["mapping"][38]="imprimante > consommables > cartouche magenta (%)";
$LANG['plugin_fusioninventory']["mapping"][39]="imprimante > consommables > cartouche cyan clair (%)";
$LANG['plugin_fusioninventory']["mapping"][40]="imprimante > consommables > cartouche magenta clair (%)";
$LANG['plugin_fusioninventory']["mapping"][41]="imprimante > consommables > photoconducteur (%)";
$LANG['plugin_fusioninventory']["mapping"][42]="imprimante > consommables > photoconducteur noir (%)";
$LANG['plugin_fusioninventory']["mapping"][43]="imprimante > consommables > photoconducteur couleur (%)";
$LANG['plugin_fusioninventory']["mapping"][44]="imprimante > consommables > photoconducteur cyan (%)";
$LANG['plugin_fusioninventory']["mapping"][45]="imprimante > consommables > photoconducteur jaune (%)";
$LANG['plugin_fusioninventory']["mapping"][46]="imprimante > consommables > photoconducteur magenta (%)";
$LANG['plugin_fusioninventory']["mapping"][47]="imprimante > consommables > unité de transfert noir (%)";
$LANG['plugin_fusioninventory']["mapping"][48]="imprimante > consommables > unité de transfert cyan (%)";
$LANG['plugin_fusioninventory']["mapping"][49]="imprimante > consommables > unité de transfert jaune (%)";
$LANG['plugin_fusioninventory']["mapping"][50]="imprimante > consommables > unité de transfert magenta (%)";
$LANG['plugin_fusioninventory']["mapping"][51]="imprimante > consommables > bac récupérateur de déchet (%)";
$LANG['plugin_fusioninventory']["mapping"][52]="imprimante > consommables > four (%)";
$LANG['plugin_fusioninventory']["mapping"][53]="imprimante > consommables > module de nettoyage (%)";
$LANG['plugin_fusioninventory']["mapping"][54]="imprimante > compteur > nombre de pages recto/verso imprimées";
$LANG['plugin_fusioninventory']["mapping"][55]="imprimante > compteur > nombre de pages scannées";
$LANG['plugin_fusioninventory']["mapping"][56]="imprimante > lieu";
$LANG['plugin_fusioninventory']["mapping"][57]="imprimante > port > nom";
$LANG['plugin_fusioninventory']["mapping"][58]="imprimante > port > adresse MAC";
$LANG['plugin_fusioninventory']["mapping"][59]="imprimante > consommables > cartouche noir (encre max)";
$LANG['plugin_fusioninventory']["mapping"][60]="imprimante > consommables > cartouche noir (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][61]="imprimante > consommables > cartouche cyan (encre max)";
$LANG['plugin_fusioninventory']["mapping"][62]="imprimante > consommables > cartouche cyan (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][63]="imprimante > consommables > cartouche jaune (encre max)";
$LANG['plugin_fusioninventory']["mapping"][64]="imprimante > consommables > cartouche jaune (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][65]="imprimante > consommables > cartouche magenta (encre max)";
$LANG['plugin_fusioninventory']["mapping"][66]="imprimante > consommables > cartouche magenta (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][67]="imprimante > consommables > cartouche cyan clair (encre max)";
$LANG['plugin_fusioninventory']["mapping"][68]="imprimante > consommables > cartouche cyan clair (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][69]="imprimante > consommables > cartouche magenta clair (encre max)";
$LANG['plugin_fusioninventory']["mapping"][70]="imprimante > consommables > cartouche magenta clair (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][71]="imprimante > consommables > photoconducteur (encre max)";
$LANG['plugin_fusioninventory']["mapping"][72]="imprimante > consommables > photoconducteur (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][73]="imprimante > consommables > photoconducteur noir (encre max)";
$LANG['plugin_fusioninventory']["mapping"][74]="imprimante > consommables > photoconducteur noir (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][75]="imprimante > consommables > photoconducteur couleur (encre max)";
$LANG['plugin_fusioninventory']["mapping"][76]="imprimante > consommables > photoconducteur couleur (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][77]="imprimante > consommables > photoconducteur cyan (encre max)";
$LANG['plugin_fusioninventory']["mapping"][78]="imprimante > consommables > photoconducteur cyan (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][79]="imprimante > consommables > photoconducteur jaune (encre max)";
$LANG['plugin_fusioninventory']["mapping"][80]="imprimante > consommables > photoconducteur jaune (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][81]="imprimante > consommables > photoconducteur magenta (encre max)";
$LANG['plugin_fusioninventory']["mapping"][82]="imprimante > consommables > photoconducteur magenta (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][83]="imprimante > consommables > unité de transfert noir (encre max)";
$LANG['plugin_fusioninventory']["mapping"][84]="imprimante > consommables > unité de transfert noir (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][85]="imprimante > consommables > unité de transfert cyan (encre max)";
$LANG['plugin_fusioninventory']["mapping"][86]="imprimante > consommables > unité de transfert cyan (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][87]="imprimante > consommables > unité de transfert jaune (encre max)";
$LANG['plugin_fusioninventory']["mapping"][88]="imprimante > consommables > unité de transfert jaune (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][89]="imprimante > consommables > unité de transfert magenta (encre max)";
$LANG['plugin_fusioninventory']["mapping"][90]="imprimante > consommables > unité de transfert magenta (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][91]="imprimante > consommables > bac récupérateur de déchet (encre max)";
$LANG['plugin_fusioninventory']["mapping"][92]="imprimante > consommables > bac récupérateur de déchet (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][93]="imprimante > consommables > four (encre max)";
$LANG['plugin_fusioninventory']["mapping"][94]="imprimante > consommables > four (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][95]="imprimante > consommables > module de nettoyage (encre max)";
$LANG['plugin_fusioninventory']["mapping"][96]="imprimante > consommables > module de nettoyage (encre restant)";
$LANG['plugin_fusioninventory']["mapping"][97]="imprimante > port > type";
$LANG['plugin_fusioninventory']["mapping"][98]="imprimante > consommables > Kit de maintenance (max)";
$LANG['plugin_fusioninventory']["mapping"][99]="imprimante > consommables > Kit de maintenance (restant)";
$LANG['plugin_fusioninventory']["mapping"][400]="imprimante > consommables > Kit de maintenance (%)";
$LANG['plugin_fusioninventory']["mapping"][401]="réseaux > CPU user";
$LANG['plugin_fusioninventory']["mapping"][402]="réseaux > CPU système";
$LANG['plugin_fusioninventory']["mapping"][403]="réseaux > contact";
$LANG['plugin_fusioninventory']["mapping"][404]="réseaux > description";
$LANG['plugin_fusioninventory']["mapping"][405]="imprimante > contact";
$LANG['plugin_fusioninventory']["mapping"][406]="imprimante > description";
$LANG['plugin_fusioninventory']["mapping"][407]="imprimante > port > adresse IP";
$LANG['plugin_fusioninventory']["mapping"][408]="réseaux > port > numéro index";
$LANG['plugin_fusioninventory']["mapping"][409]="réseaux > Adresse CDP";
$LANG['plugin_fusioninventory']["mapping"][410]="réseaux > port CDP";
$LANG['plugin_fusioninventory']["mapping"][411]="réseaux > port > trunk/tagged";
$LANG['plugin_fusioninventory']["mapping"][412]="réseaux > Adresses mac filtrées (dot1dTpFdbAddress)";
$LANG['plugin_fusioninventory']["mapping"][413]="réseaux > adresses physiques mémorisées (ipNetToMediaPhysAddress)";
$LANG['plugin_fusioninventory']["mapping"][414]="réseaux > instances de ports (dot1dTpFdbPort)";
$LANG['plugin_fusioninventory']["mapping"][415]="réseaux > numéro de ports associé ID du port (dot1dBasePortIfIndex)";
$LANG['plugin_fusioninventory']["mapping"][416]="imprimante > port > numéro index";
$LANG['plugin_fusioninventory']["mapping"][417]="réseaux > adresse MAC";
$LANG['plugin_fusioninventory']["mapping"][418]="imprimante > numéro d'inventaire";
$LANG['plugin_fusioninventory']["mapping"][419]="réseaux > numéro d'inventaire";
$LANG['plugin_fusioninventory']["mapping"][420]="imprimante > fabricant";
$LANG['plugin_fusioninventory']["mapping"][421]="réseaux > addresses IP";
$LANG['plugin_fusioninventory']["mapping"][422]="réseaux > portVlanIndex";
$LANG['plugin_fusioninventory']["mapping"][423]="imprimante > compteur > nombre total de pages imprimées (impression)";
$LANG['plugin_fusioninventory']["mapping"][424]="imprimante > compteur > nombre de pages noir et blanc imprimées (impression)";
$LANG['plugin_fusioninventory']["mapping"][425]="imprimante > compteur > nombre de pages couleur imprimées (impression)";
$LANG['plugin_fusioninventory']["mapping"][426]="imprimante > compteur > nombre total de pages imprimées (copie)";
$LANG['plugin_fusioninventory']["mapping"][427]="imprimante > compteur > nombre de pages noir et blanc imprimées (copie)";
$LANG['plugin_fusioninventory']["mapping"][428]="imprimante > compteur > nombre de pages couleur imprimées (copie)";
$LANG['plugin_fusioninventory']["mapping"][429]="imprimante > compteur > nombre total de pages imprimées (fax)";
$LANG['plugin_fusioninventory']["mapping"][430]="réseaux > port > vlan";


$LANG['plugin_fusioninventory']["mapping"][101]="";
$LANG['plugin_fusioninventory']["mapping"][102]="";
$LANG['plugin_fusioninventory']["mapping"][103]="";
$LANG['plugin_fusioninventory']["mapping"][104]="MTU";
$LANG['plugin_fusioninventory']["mapping"][105]="Vitesse";
$LANG['plugin_fusioninventory']["mapping"][106]="Statut Interne";
$LANG['plugin_fusioninventory']["mapping"][107]="Dernier changement";
$LANG['plugin_fusioninventory']["mapping"][108]="Nb d'octets recus";
$LANG['plugin_fusioninventory']["mapping"][109]="Nb d'octets envoyés";
$LANG['plugin_fusioninventory']["mapping"][110]="Nb d'erreurs en entrée";
$LANG['plugin_fusioninventory']["mapping"][111]="Nb d'erreurs en sortie";
$LANG['plugin_fusioninventory']["mapping"][112]="Utilisation du CPU";
$LANG['plugin_fusioninventory']["mapping"][113]="";
$LANG['plugin_fusioninventory']["mapping"][114]="Connexion";
$LANG['plugin_fusioninventory']["mapping"][115]="MAC interne";
$LANG['plugin_fusioninventory']["mapping"][116]="Nom";
$LANG['plugin_fusioninventory']["mapping"][117]="Modèle";
$LANG['plugin_fusioninventory']["mapping"][118]="Type";
$LANG['plugin_fusioninventory']["mapping"][119]="VLAN";
$LANG['plugin_fusioninventory']["mapping"][128]="Nombre total de pages imprimées";
$LANG['plugin_fusioninventory']["mapping"][129]="Nombre de pages noir et blanc imprimées";
$LANG['plugin_fusioninventory']["mapping"][130]="Nombre de pages couleur imprimées";
$LANG['plugin_fusioninventory']["mapping"][131]="Nombre de pages monochrome imprimées";
$LANG['plugin_fusioninventory']["mapping"][132]="Nombre de pages bichromie imprimées";
$LANG['plugin_fusioninventory']["mapping"][134]="Cartouche noir";
$LANG['plugin_fusioninventory']["mapping"][135]="Cartouche noir photo";
$LANG['plugin_fusioninventory']["mapping"][136]="Cartouche cyan";
$LANG['plugin_fusioninventory']["mapping"][137]="Cartouche jaune";
$LANG['plugin_fusioninventory']["mapping"][138]="Cartouche magenta";
$LANG['plugin_fusioninventory']["mapping"][139]="Cartouche cyan clair";
$LANG['plugin_fusioninventory']["mapping"][140]="Cartouche magenta clair";
$LANG['plugin_fusioninventory']["mapping"][141]="Photoconducteur";
$LANG['plugin_fusioninventory']["mapping"][142]="Photoconducteur noir";
$LANG['plugin_fusioninventory']["mapping"][143]="Photoconducteur couleur";
$LANG['plugin_fusioninventory']["mapping"][144]="Photoconducteur cyan";
$LANG['plugin_fusioninventory']["mapping"][145]="Photoconducteur jaune";
$LANG['plugin_fusioninventory']["mapping"][146]="Photoconducteur magenta";
$LANG['plugin_fusioninventory']["mapping"][147]="Unité de transfert noir";
$LANG['plugin_fusioninventory']["mapping"][148]="Unité de transfert cyan";
$LANG['plugin_fusioninventory']["mapping"][149]="Unité de transfert jaune";
$LANG['plugin_fusioninventory']["mapping"][150]="Unité de transfert magenta";
$LANG['plugin_fusioninventory']["mapping"][151]="Bac récupérateur de déchet";
$LANG['plugin_fusioninventory']["mapping"][152]="Four";
$LANG['plugin_fusioninventory']["mapping"][153]="Module de nettoyage";
$LANG['plugin_fusioninventory']["mapping"][154]="Nombre de pages recto/verso imprimées";
$LANG['plugin_fusioninventory']["mapping"][155]="Nombre de pages scannées";
$LANG['plugin_fusioninventory']["mapping"][156]="Kit de maintenance";
$LANG['plugin_fusioninventory']["mapping"][157]="Toner Noir";
$LANG['plugin_fusioninventory']["mapping"][158]="Toner Cyan";
$LANG['plugin_fusioninventory']["mapping"][159]="Toner Magenta";
$LANG['plugin_fusioninventory']["mapping"][160]="Toner Jaune";
$LANG['plugin_fusioninventory']["mapping"][161]="Tambour Noir";
$LANG['plugin_fusioninventory']["mapping"][162]="Tambour Cyan";
$LANG['plugin_fusioninventory']["mapping"][163]="Tambour Magenta";
$LANG['plugin_fusioninventory']["mapping"][164]="Tambour Jaune";
$LANG['plugin_fusioninventory']["mapping"][165]="Informations diverses regroupées";
$LANG['plugin_fusioninventory']["mapping"][1423]="Nombre total de pages imprimées (impression)";
$LANG['plugin_fusioninventory']["mapping"][1424]="Nombre de pages noir et blanc imprimées (impression)";
$LANG['plugin_fusioninventory']["mapping"][1425]="Nombre de pages couleur imprimées (impression)";
$LANG['plugin_fusioninventory']["mapping"][1426]="Nombre total de pages imprimées (copie)";
$LANG['plugin_fusioninventory']["mapping"][1427]="Nombre de pages noir et blanc imprimées (copie)";
$LANG['plugin_fusioninventory']["mapping"][1428]="Nombre de pages couleur imprimées (copie)";
$LANG['plugin_fusioninventory']["mapping"][1429]="Nombre total de pages imprimées (fax)";


$LANG['plugin_fusioninventory']["printer"][0]="pages";

$LANG['plugin_fusioninventory']["menu"][0]="Découverte de matériel réseau";
$LANG['plugin_fusioninventory']["menu"][1]="Gestion des agents";
$LANG['plugin_fusioninventory']["menu"][2]="Plages IP";
$LANG['plugin_fusioninventory']["menu"][3]="Menu";
$LANG['plugin_fusioninventory']["menu"][4]="Matériel inconnu";
$LANG['plugin_fusioninventory']["menu"][5]="Historique des ports de switchs";
$LANG['plugin_fusioninventory']["menu"][6]="Ports de switchs inutilisés";

$LANG['plugin_fusioninventory']["buttons"][0]="Découvrir";

$LANG['plugin_fusioninventory']["discovery"][0]="Plage d'ip à scanner";
$LANG['plugin_fusioninventory']["discovery"][1]="Liste du matériel découvert";
$LANG['plugin_fusioninventory']["discovery"][2]="² dans le script en automatique";
$LANG['plugin_fusioninventory']["discovery"][3]="Découverte";
$LANG['plugin_fusioninventory']["discovery"][4]="Numéros de série";
$LANG['plugin_fusioninventory']["discovery"][5]="Nombre de matériels importés";
$LANG['plugin_fusioninventory']["discovery"][6]="Critères d'existence";
$LANG['plugin_fusioninventory']["discovery"][7]="Critères d'existence secondaires";
$LANG['plugin_fusioninventory']["discovery"][8]="Si tous les critères d'existence se confrontent à des champs vides, vous pouvez sélectionner des critères secondaires.";
$LANG['plugin_fusioninventory']["discovery"][9]="Nombre de matériels non importés car type non défini";

$LANG['plugin_fusioninventory']["rangeip"][0]="Début de la plage IP";
$LANG['plugin_fusioninventory']["rangeip"][1]="Fin de la plage IP";
$LANG['plugin_fusioninventory']["rangeip"][2]="Plage IP";
$LANG['plugin_fusioninventory']["rangeip"][3]="Interrogation";
$LANG['plugin_fusioninventory']["rangeip"][4]="Adresse IP incorrecte";

$LANG['plugin_fusioninventory']["agents"][0]="Agent SNMP";
$LANG['plugin_fusioninventory']["agents"][2]="Threads interrogation (par coeur)";
$LANG['plugin_fusioninventory']["agents"][3]="Threads découverte (par coeur)";
$LANG['plugin_fusioninventory']["agents"][4]="Dernière remontée";
$LANG['plugin_fusioninventory']["agents"][5]="Version de l'agent";
$LANG['plugin_fusioninventory']["agents"][6]="Verrouillage";
$LANG['plugin_fusioninventory']["agents"][7]="Export config agent";
$LANG['plugin_fusioninventory']["agents"][9]="Options avancées";
$LANG['plugin_fusioninventory']["agents"][12]="Agent découverte";
$LANG['plugin_fusioninventory']["agents"][13]="Agent interrogation";
$LANG['plugin_fusioninventory']["agents"][14]="Actions de l'agent";
$LANG['plugin_fusioninventory']["agents"][15]="Statut de l'agent";
$LANG['plugin_fusioninventory']["agents"][16]="Initialisé";
$LANG['plugin_fusioninventory']["agents"][17]="L'agent s'exécute";
$LANG['plugin_fusioninventory']["agents"][18]="L'inventaire a été reçu";
$LANG['plugin_fusioninventory']["agents"][19]="L'inventaire est envoyé au serveur OCS";
$LANG['plugin_fusioninventory']["agents"][20]="La synchronisation entre OCS et GLPI est en cours";
$LANG['plugin_fusioninventory']["agents"][21]="Inventaire terminé";
$LANG['plugin_fusioninventory']["agents"][22]="En attente";
$LANG['plugin_fusioninventory']["agents"][23]="Lié à l'ordinateur";

$LANG['plugin_fusioninventory']["unknown"][0]="Nom DNS";
$LANG['plugin_fusioninventory']["unknown"][1]="Nom port réseau";
$LANG['plugin_fusioninventory']["unknown"][2]="Matériel approuvé";
$LANG['plugin_fusioninventory']["unknown"][3]="Découvert par l'agent";
$LANG['plugin_fusioninventory']["unknown"][4]="Hub réseau";
$LANG['plugin_fusioninventory']["unknown"][5]="Importé depuis le matériel inconnu (FusionInventory)";

$LANG['plugin_fusioninventory']["task"][0]="Tâche";
$LANG['plugin_fusioninventory']["task"][1]="Gestion des tâches";
$LANG['plugin_fusioninventory']["task"][2]="Action";
$LANG['plugin_fusioninventory']["task"][3]="Unitaire";
$LANG['plugin_fusioninventory']["task"][4]="Récupérer maintenant les informations";
$LANG['plugin_fusioninventory']["task"][5]="Sélectionner l'agent OCS";
$LANG['plugin_fusioninventory']["task"][6]="Récupérer son état";
$LANG['plugin_fusioninventory']["task"][7]="Etat";
$LANG['plugin_fusioninventory']["task"][8]="Prêt";
$LANG['plugin_fusioninventory']["task"][9]="Ne peut pas être contacté";
$LANG['plugin_fusioninventory']["task"][10]="En cours d'execution... pas disponible";
$LANG['plugin_fusioninventory']["task"][11]="L'agent a été notifié et commence son exécution";
$LANG['plugin_fusioninventory']["task"][12]="Déclencher l'agent";
$LANG['plugin_fusioninventory']["task"][13]="Agent(s) indisponible(s)";

$LANG['plugin_fusioninventory']["constructdevice"][0]="Gestion des mib de matériel";
$LANG['plugin_fusioninventory']["constructdevice"][1]="Creation automatique des modèles";
$LANG['plugin_fusioninventory']["constructdevice"][2]="Générer le fichier de découverte";
$LANG['plugin_fusioninventory']["constructdevice"][3]="Supprimer modèles non utilisés";
$LANG['plugin_fusioninventory']["constructdevice"][4]="Exporter tous les modèles";
$LANG['plugin_fusioninventory']["constructdevice"][5]="Regénérer les commentaires de modèles";

$LANG['plugin_fusioninventory']["update"][0]="Votre historique fait plus de 300 000 lignes, il faut lancer la commande suivante en ligne de commande pour finir la mise à jour : ";

?>