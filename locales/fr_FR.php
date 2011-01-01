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

$title="FusionInventory SNMP";
$version="2.3.0-1";

$LANG['plugin_fusinvsnmp']['title'][0]="$title";
$LANG['plugin_fusinvsnmp']['title'][1]="Informations SNMP";
$LANG['plugin_fusinvsnmp']['title'][2]="Historique de connexion";
$LANG['plugin_fusinvsnmp']['title'][3]="[Trk] Erreurs";
$LANG['plugin_fusinvsnmp']['title'][4]="[Trk] Cron";
$LANG['plugin_fusinvsnmp']['title'][5]="Historique SNMP";

$LANG['plugin_fusinvsnmp']['config'][0] = "Fréquence des inventaires (en heures)";
$LANG['plugin_fusinvsnmp']['config'][1] = "Modules";
$LANG['plugin_fusinvsnmp']['config'][2] = "Snmp";
$LANG['plugin_fusinvsnmp']['config'][3] = "Inventaire";
$LANG['plugin_fusinvsnmp']['config'][4] = "Découverte d'équipements";
$LANG['plugin_fusinvsnmp']['config'][5] = "Controle de l'agent à partir de GLPI";
$LANG['plugin_fusinvsnmp']['config'][6] = "Wake On Lan";
$LANG['plugin_fusinvsnmp']['config'][7] = "Interrogation SNMP";

$LANG['plugin_fusinvsnmp']['profile'][1]="$title";
$LANG['plugin_fusinvsnmp']['profile'][2]="Configuration";
$LANG['plugin_fusinvsnmp']['profile'][3]="Authentification SNMP";
$LANG['plugin_fusinvsnmp']['profile'][4]="Plages IP";
$LANG['plugin_fusinvsnmp']['profile'][5]="SNMP des équipements réseaux";
$LANG['plugin_fusinvsnmp']['profile'][6]="SNMP des imprimantes";
$LANG['plugin_fusinvsnmp']['profile'][7]="Modèles SNMP";

$LANG['plugin_fusinvsnmp']['profile'][10]="Profiles configured";
$LANG['plugin_fusinvsnmp']['profile'][11]="Computer history";
$LANG['plugin_fusinvsnmp']['profile'][12]="Printer history";
$LANG['plugin_fusinvsnmp']['profile'][13]="Printer information";
$LANG['plugin_fusinvsnmp']['profile'][14]="Network information";
$LANG['plugin_fusinvsnmp']['profile'][15]="Erreurs";

$LANG['plugin_fusinvsnmp']['profile'][16]="SNMP networking";
$LANG['plugin_fusinvsnmp']['profile'][17]="SNMP peripheral";
$LANG['plugin_fusinvsnmp']['profile'][18]="SNMP printers";
$LANG['plugin_fusinvsnmp']['profile'][19]="SNMP models";
$LANG['plugin_fusinvsnmp']['profile'][20]="SNMP authentication";
$LANG['plugin_fusinvsnmp']['profile'][21]="Script information";
$LANG['plugin_fusinvsnmp']['profile'][22]="Network discovery";
$LANG['plugin_fusinvsnmp']['profile'][23]="General configuration";
$LANG['plugin_fusinvsnmp']['profile'][24]="SNMP model";
$LANG['plugin_fusinvsnmp']['profile'][25]="IP range";
$LANG['plugin_fusinvsnmp']['profile'][26]="Agent";
$LANG['plugin_fusinvsnmp']['profile'][27]="Agents processes";
$LANG['plugin_fusinvsnmp']['profile'][28]="Report";
$LANG['plugin_fusinvsnmp']['profile'][29]="Remote control of agents";
$LANG['plugin_fusinvsnmp']['profile'][30]="Unknown devices";
$LANG['plugin_fusinvsnmp']['profile'][31]="device inventory FusionInventory";
$LANG['plugin_fusinvsnmp']['profile'][32]="SNMP query";
$LANG['plugin_fusinvsnmp']['profile'][33]="WakeOnLan";
$LANG['plugin_fusinvsnmp']['profile'][34]="Actions";

$LANG['plugin_fusinvsnmp']['setup'][2]="Merci de vous placer sur l'entité racine (voir tous)";
$LANG['plugin_fusinvsnmp']['setup'][3]="Configuration du plugin ".$title;
$LANG['plugin_fusinvsnmp']['setup'][4]="Installer le plugin $title $version";
$LANG['plugin_fusinvsnmp']['setup'][5]="Mettre à jour le plugin $title vers la version $version";
$LANG['plugin_fusinvsnmp']['setup'][6]="Désinstaller le plugin $title $version";
$LANG['plugin_fusinvsnmp']['setup'][8]="Attention, la désinstallation du plugin est irréversible.<br> Vous perdrez toutes les données.";
$LANG['plugin_fusinvsnmp']['setup'][11]="Mode d'emploi";
$LANG['plugin_fusinvsnmp']['setup'][12]="FAQ";
$LANG['plugin_fusinvsnmp']['setup'][13]="Vérification des modules PHP nécessaires";
$LANG['plugin_fusinvsnmp']['setup'][14]="L'extension snmp de PHP n'est pas chargée";
$LANG['plugin_fusinvsnmp']['setup'][15]="L'extension runkit de PHP/PECL n'est pas chargée";
$LANG['plugin_fusinvsnmp']['setup'][16]="Documentation";
$LANG['plugin_fusinvsnmp']['setup'][17]="Le plugin ".$title." a besoin que le plugin FusionInventory soit activé pour être lui-même activé.";
$LANG['plugin_fusinvsnmp']['setup'][18]="Le plugin ".$title." a besoin que le plugin FusionInventory soit activé pour être lui-même désinstallé.";
$LANG['plugin_fusinvsnmp']['setup'][19]="Conversion de l'historique des ports";
$LANG['plugin_fusinvsnmp']['setup'][20]="Déplacement de l'historique de création des connections";
$LANG['plugin_fusinvsnmp']['setup'][21]="Déplacement de l'historique de suppression des connections";

$LANG['plugin_fusinvsnmp']['functionalities'][0]="Fonctionnalités";
$LANG['plugin_fusinvsnmp']['functionalities'][1]="Ajout / Suppression de fonctionnalités";
$LANG['plugin_fusinvsnmp']['functionalities'][2]="Configuration générale";
$LANG['plugin_fusinvsnmp']['functionalities'][3]="SNMP";
$LANG['plugin_fusinvsnmp']['functionalities'][4]="Connexion";
$LANG['plugin_fusinvsnmp']['functionalities'][5]="Script serveur";
$LANG['plugin_fusinvsnmp']['functionalities'][6]="Légende";
$LANG['plugin_fusinvsnmp']['functionalities'][7]="Champs verrouillables";

$LANG['plugin_fusinvsnmp']['functionalities'][9]="Rétention en jours";
$LANG['plugin_fusinvsnmp']['functionalities'][10]="is_active de l'historique";
$LANG['plugin_fusinvsnmp']['functionalities'][11]="is_active du module connexion";
$LANG['plugin_fusinvsnmp']['functionalities'][12]="is_active du module SNMP réseaux";
$LANG['plugin_fusinvsnmp']['functionalities'][13]="is_active du module SNMP périphériques";
$LANG['plugin_fusinvsnmp']['functionalities'][14]="is_active du module SNMP téléphones";
$LANG['plugin_fusinvsnmp']['functionalities'][15]="is_active du module SNMP imprimantes";
$LANG['plugin_fusinvsnmp']['functionalities'][16]="Stockage de l'authentification SNMP";
$LANG['plugin_fusinvsnmp']['functionalities'][17]="Base de données";
$LANG['plugin_fusinvsnmp']['functionalities'][18]="Fichiers";
$LANG['plugin_fusinvsnmp']['functionalities'][19]="Veuillez configurer le stockage de l'authentification SNMP dans la configuration du plugin";
$LANG['plugin_fusinvsnmp']['functionalities'][20]="Statut du matériel actif";
$LANG['plugin_fusinvsnmp']['functionalities'][21]="Rétention de l'historique d'interconnexions entre matériels en jours (0 = infini)";
$LANG['plugin_fusinvsnmp']['functionalities'][22]="Rétention de l'historique de changement d'état des ports (0 = infini)";
$LANG['plugin_fusinvsnmp']['functionalities'][23]="Rétention de l'historique des adresses MAC inconnues (0 = infini)";
$LANG['plugin_fusinvsnmp']['functionalities'][24]="Rétention de l'historique des erreurs SNMP (0 = infini)";
$LANG['plugin_fusinvsnmp']['functionalities'][25]="Rétention de l'historique des processes des scripts (0 = infini)";
$LANG['plugin_fusinvsnmp']['functionalities'][26]="URL de GLPI pour l'agent";
$LANG['plugin_fusinvsnmp']['functionalities'][27]="SSL seulement pour l'agent";
$LANG['plugin_fusinvsnmp']['functionalities'][28]="Configuration de l'historique";
$LANG['plugin_fusinvsnmp']['functionalities'][29]="Liste des champs à historiser";

$LANG['plugin_fusinvsnmp']['functionalities'][30]="Statut du matériel actif";
$LANG['plugin_fusinvsnmp']['functionalities'][31]="Gestion des cartouches et du stock";
$LANG['plugin_fusinvsnmp']['functionalities'][32]="Effacer les informations des process agent après";
$LANG['plugin_fusinvsnmp']['functionalities'][36]="Fréquence de relevé des compteurs";

$LANG['plugin_fusinvsnmp']['functionalities'][40]="Configuration";
$LANG['plugin_fusinvsnmp']['functionalities'][41]="Statut du matériel actif";
$LANG['plugin_fusinvsnmp']['functionalities'][42]="Commutateur";
$LANG['plugin_fusinvsnmp']['functionalities'][43]="Authentification SNMP";

$LANG['plugin_fusinvsnmp']['functionalities'][50]="Nombre de process simultanés pour la découverte réseau";
$LANG['plugin_fusinvsnmp']['functionalities'][51]="Nombre de process simultanés pour l'interrogation SNMP";
$LANG['plugin_fusinvsnmp']['functionalities'][52]="is_active des journaux";
$LANG['plugin_fusinvsnmp']['functionalities'][53]="Nombre de process simultanés pour le script serveur de post-traitement";

$LANG['plugin_fusinvsnmp']['functionalities'][60]="Nettoyage de l'historique";

$LANG['plugin_fusinvsnmp']['functionalities'][70]="Configuration des champs verrouillables";
$LANG['plugin_fusinvsnmp']['functionalities'][71]="Champs non verrouillables";
$LANG['plugin_fusinvsnmp']['functionalities'][72]="Table";
$LANG['plugin_fusinvsnmp']['functionalities'][73]="Champs";
$LANG['plugin_fusinvsnmp']['functionalities'][74]="Valeurs";
$LANG['plugin_fusinvsnmp']['functionalities'][75]="Verrous";

$LANG['plugin_fusinvsnmp']['snmp'][0]="Informations SNMP du matériel";
$LANG['plugin_fusinvsnmp']['snmp'][1]="Général";
$LANG['plugin_fusinvsnmp']['snmp'][2]="Cablâge";
$LANG['plugin_fusinvsnmp']['snmp'][3]="Données SNMP";
$LANG['plugin_fusinvsnmp']['snmp'][4]="Sysdescr";

$LANG['plugin_fusinvsnmp']['snmp'][11]="Informations supplémentaires";
$LANG['plugin_fusinvsnmp']['snmp'][12]="Uptime";
$LANG['plugin_fusinvsnmp']['snmp'][13]="Utilisation du CPU (en %)";
$LANG['plugin_fusinvsnmp']['snmp'][14]="Utilisation de la mémoire (en %)";

$LANG['plugin_fusinvsnmp']['snmp'][31]="Impossible de récupérer les infos SNMP : Ce n'est pas un commutateur !";
$LANG['plugin_fusinvsnmp']['snmp'][32]="Impossible de récupérer les infos SNMP : Matériel non actif !";
$LANG['plugin_fusinvsnmp']['snmp'][33]="Impossible de récupérer les infos SNMP : IP non précisée dans la base !";
$LANG['plugin_fusinvsnmp']['snmp'][34]="Le commutateur auquel est reliée la machine n'est pas renseigné !";

$LANG['plugin_fusinvsnmp']['snmp'][40]="Tableau des ports";
$LANG['plugin_fusinvsnmp']['snmp'][41]="Description du port";
$LANG['plugin_fusinvsnmp']['snmp'][42]="MTU";
$LANG['plugin_fusinvsnmp']['snmp'][43]="Vitesse";
$LANG['plugin_fusinvsnmp']['snmp'][44]="Statut Interne";
$LANG['plugin_fusinvsnmp']['snmp'][45]="Dernier changement";
$LANG['plugin_fusinvsnmp']['snmp'][46]="Nb d'octets recus";
$LANG['plugin_fusinvsnmp']['snmp'][47]="Nb d'erreurs en entrée";
$LANG['plugin_fusinvsnmp']['snmp'][48]="Nb d'octets envoyés";
$LANG['plugin_fusinvsnmp']['snmp'][49]="Nb d'erreurs en réception";
$LANG['plugin_fusinvsnmp']['snmp'][50]="Connexion";
$LANG['plugin_fusinvsnmp']['snmp'][51]="Duplex";
$LANG['plugin_fusinvsnmp']['snmp'][52]="Date dernier inventaire FusionInventory";
$LANG['plugin_fusinvsnmp']['snmp'][53]="Dernier inventaire";
$LANG['plugin_fusinvsnmp']['snmp'][54]="Données non disponibles";

$LANG['plugin_fusinvsnmp']['snmpauth'][1]="Communauté";
$LANG['plugin_fusinvsnmp']['snmpauth'][2]="Utilisateur";
$LANG['plugin_fusinvsnmp']['snmpauth'][3]="Schéma d'authentification";
$LANG['plugin_fusinvsnmp']['snmpauth'][4]="Protocole de cryptage pour authentification ";
$LANG['plugin_fusinvsnmp']['snmpauth'][5]="Mot de passe";
$LANG['plugin_fusinvsnmp']['snmpauth'][6]="Protocole de cryptage pour les données (écriture)";
$LANG['plugin_fusinvsnmp']['snmpauth'][7]="Mot de passe (écriture)";

$LANG['plugin_fusinvsnmp']['cron'][0]="Relevé automatique du compteur";
$LANG['plugin_fusinvsnmp']['cron'][1]="Activer le relevé";
$LANG['plugin_fusinvsnmp']['cron'][2]="";
$LANG['plugin_fusinvsnmp']['cron'][3]="Défaut";

$LANG['plugin_fusinvsnmp']['errors'][0]="Erreurs";
$LANG['plugin_fusinvsnmp']['errors'][1]="IP";
$LANG['plugin_fusinvsnmp']['errors'][2]="Description";
$LANG['plugin_fusinvsnmp']['errors'][3]="Date 1er pb";
$LANG['plugin_fusinvsnmp']['errors'][4]="Date dernier pb";

$LANG['plugin_fusinvsnmp']['errors'][10]="Incohérence avec la base GLPI";
$LANG['plugin_fusinvsnmp']['errors'][11]="Poste inconnu";
$LANG['plugin_fusinvsnmp']['errors'][12]="IP inconnue";

$LANG['plugin_fusinvsnmp']['errors'][20]="Erreur SNMP";
$LANG['plugin_fusinvsnmp']['errors'][21]="Impossible de récupérer les informations";
$LANG['plugin_fusinvsnmp']['errors'][22]="Elément inattendu dans";
$LANG['plugin_fusinvsnmp']['errors'][23]="Impossible d identifier le matériel";

$LANG['plugin_fusinvsnmp']['errors'][30]="Erreur Câblage";
$LANG['plugin_fusinvsnmp']['errors'][31]="Problème de câblage";

$LANG['plugin_fusinvsnmp']['errors'][50]="La version de GLPI n'est pas compatible, vous avez besoin de la version 0.78";

$LANG['plugin_fusinvsnmp']['errors'][101]="Timeout";
$LANG['plugin_fusinvsnmp']['errors'][102]="Modele SNMP non assigné";
$LANG['plugin_fusinvsnmp']['errors'][103]="Authentification SNMP non assigné";
$LANG['plugin_fusinvsnmp']['errors'][104]="Message d'erreur";

$LANG['plugin_fusinvsnmp']['history'][0] = "Ancienne";
$LANG['plugin_fusinvsnmp']['history'][1] = "Nouvelle";
$LANG['plugin_fusinvsnmp']['history'][2] = "Déconnexion";
$LANG['plugin_fusinvsnmp']['history'][3] = "Connexion";

$LANG['plugin_fusinvsnmp']['prt_history'][0]="Historique et Statistiques des compteurs imprimante";

$LANG['plugin_fusinvsnmp']['prt_history'][10]="Statistiques des compteurs imprimante sur";
$LANG['plugin_fusinvsnmp']['prt_history'][11]="jour(s)";
$LANG['plugin_fusinvsnmp']['prt_history'][12]="Pages imprimées totales";
$LANG['plugin_fusinvsnmp']['prt_history'][13]="Pages / jour";

$LANG['plugin_fusinvsnmp']['prt_history'][20]="Historique des compteurs imprimante";
$LANG['plugin_fusinvsnmp']['prt_history'][21]="Date";
$LANG['plugin_fusinvsnmp']['prt_history'][22]="Compteur";

$LANG['plugin_fusinvsnmp']['prt_history'][30]="Affichage";
$LANG['plugin_fusinvsnmp']['prt_history'][31]="Unité de temps";
$LANG['plugin_fusinvsnmp']['prt_history'][32]="Ajouter une imprimante";
$LANG['plugin_fusinvsnmp']['prt_history'][33]="Supprimer une imprimante";
$LANG['plugin_fusinvsnmp']['prt_history'][34]="jour";
$LANG['plugin_fusinvsnmp']['prt_history'][35]="semaine";
$LANG['plugin_fusinvsnmp']['prt_history'][36]="mois";
$LANG['plugin_fusinvsnmp']['prt_history'][37]="année";

$LANG['plugin_fusinvsnmp']['cpt_history'][0]="Historique des sessions";
$LANG['plugin_fusinvsnmp']['cpt_history'][1]="Contact";
$LANG['plugin_fusinvsnmp']['cpt_history'][2]="Ordinateur";
$LANG['plugin_fusinvsnmp']['cpt_history'][3]="Utilisateur";
$LANG['plugin_fusinvsnmp']['cpt_history'][4]="Etat";
$LANG['plugin_fusinvsnmp']['cpt_history'][5]="Date";

$LANG['plugin_fusinvsnmp']['type'][1]="Ordinateur";
$LANG['plugin_fusinvsnmp']['type'][2]="Commutateur";
$LANG['plugin_fusinvsnmp']['type'][3]="Imprimante";

$LANG['plugin_fusinvsnmp']['rules'][1]="Règles";

$LANG['plugin_fusinvsnmp']['rule'][0]="Règles de critères d'existence d'inventaire de matériel réseau";
$LANG['plugin_fusinvsnmp']['rule'][1]="Critère d'existence";
$LANG['plugin_fusinvsnmp']['rule'][2]="Numéro de série";
$LANG['plugin_fusinvsnmp']['rule'][3]="Adresse MAC";
$LANG['plugin_fusinvsnmp']['rule'][5]="Modèle de matériel";
$LANG['plugin_fusinvsnmp']['rule'][6]="Nom du matériel";

$LANG['plugin_fusinvsnmp']['rule'][30]="Import dans l'inventaire";
$LANG['plugin_fusinvsnmp']['rule'][31]="Import dans le matériel inconnu";

$LANG['plugin_fusinvsnmp']['massiveaction'][1]="Assigner un modèle SNMP";
$LANG['plugin_fusinvsnmp']['massiveaction'][2]="Assigner une authentification SNMP";

$LANG['plugin_fusinvsnmp']['model_info'][1]="Informations SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][2]="Version SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][3]="Authentification SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][4]="Modèles SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][5]="Gestion des MIB";
$LANG['plugin_fusinvsnmp']['model_info'][6]="Edition de modèle SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][7]="Création de modèle SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][8]="Modèle déjà existant : import non effectué";
$LANG['plugin_fusinvsnmp']['model_info'][9]="Import effectué avec succès";
$LANG['plugin_fusinvsnmp']['model_info'][10]="Importation de modèle";
$LANG['plugin_fusinvsnmp']['model_info'][11]="is_active";
$LANG['plugin_fusinvsnmp']['model_info'][12]="Clé modèle pour la découverte";
$LANG['plugin_fusinvsnmp']['model_info'][13]="Charger le bon modèle";
$LANG['plugin_fusinvsnmp']['model_info'][14]="Charger le bon modèle SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][15]="Importation en masse de modèles";
$LANG['plugin_fusinvsnmp']['model_info'][16]="Import en masse des modèles dans le répertoire plugins/fusinvsnmp/models/";

$LANG['plugin_fusinvsnmp']['mib'][1]="Label MIB";
$LANG['plugin_fusinvsnmp']['mib'][2]="Objet";
$LANG['plugin_fusinvsnmp']['mib'][3]="oid";
$LANG['plugin_fusinvsnmp']['mib'][4]="Ajouter un oid...";
$LANG['plugin_fusinvsnmp']['mib'][5]="Liste des oid";
$LANG['plugin_fusinvsnmp']['mib'][6]="Compteur de ports";
$LANG['plugin_fusinvsnmp']['mib'][7]="Port dynamique (.x)";
$LANG['plugin_fusinvsnmp']['mib'][8]="Liaison champs";
$LANG['plugin_fusinvsnmp']['mib'][9]="vlan";

$LANG['plugin_fusinvsnmp']['processes'][0]="Informations sur l'exécution du script serveur";
$LANG['plugin_fusinvsnmp']['processes'][1]="PID";
$LANG['plugin_fusinvsnmp']['processes'][2]="Statut";
$LANG['plugin_fusinvsnmp']['processes'][3]="Nombre de process";
$LANG['plugin_fusinvsnmp']['processes'][4]="Date de début";
$LANG['plugin_fusinvsnmp']['processes'][5]="Date de fin";
$LANG['plugin_fusinvsnmp']['processes'][6]="Equipements réseau traités";
$LANG['plugin_fusinvsnmp']['processes'][7]="Imprimantes traitées";
$LANG['plugin_fusinvsnmp']['processes'][8]="Ports réseau traités";
$LANG['plugin_fusinvsnmp']['processes'][9]="Erreurs";
$LANG['plugin_fusinvsnmp']['processes'][10]="Durée totale";
$LANG['plugin_fusinvsnmp']['processes'][11]="Champs ajoutés";
$LANG['plugin_fusinvsnmp']['processes'][12]="Erreurs SNMP";
$LANG['plugin_fusinvsnmp']['processes'][13]="MAC inconnues";
$LANG['plugin_fusinvsnmp']['processes'][14]="Liste des adresse MAC inconnues";
$LANG['plugin_fusinvsnmp']['processes'][15]="Premier PID";
$LANG['plugin_fusinvsnmp']['processes'][16]="Dernier PID";
$LANG['plugin_fusinvsnmp']['processes'][17]="Date de la première détection";
$LANG['plugin_fusinvsnmp']['processes'][18]="Date de la dernière détection";
$LANG['plugin_fusinvsnmp']['processes'][19]="Informations sur l'exécution des agents";
$LANG['plugin_fusinvsnmp']['processes'][20]="Rapports / statistiques";
$LANG['plugin_fusinvsnmp']['processes'][21]="Equipements interrogés";
$LANG['plugin_fusinvsnmp']['processes'][22]="Erreurs";
$LANG['plugin_fusinvsnmp']['processes'][23]="Durée totale de la découverte";
$LANG['plugin_fusinvsnmp']['processes'][24]="Durée totale de l'interrogation";
$LANG['plugin_fusinvsnmp']['processes'][25]="Agent";
$LANG['plugin_fusinvsnmp']['processes'][26]="Découverte";
$LANG['plugin_fusinvsnmp']['processes'][27]="Interrogation";
$LANG['plugin_fusinvsnmp']['processes'][28]="Core";
$LANG['plugin_fusinvsnmp']['processes'][29]="Threads";
$LANG['plugin_fusinvsnmp']['processes'][30]="Découvert";
$LANG['plugin_fusinvsnmp']['processes'][31]="Existant";
$LANG['plugin_fusinvsnmp']['processes'][32]="Importé";
$LANG['plugin_fusinvsnmp']['processes'][33]="Interrogé";
$LANG['plugin_fusinvsnmp']['processes'][34]="En erreur";
$LANG['plugin_fusinvsnmp']['processes'][35]="Connexions créés";
$LANG['plugin_fusinvsnmp']['processes'][36]="Connexions supprimées";
$LANG['plugin_fusinvsnmp']['processes'][37]="Total IP";

$LANG['plugin_fusinvsnmp']['state'][0]="Démarrage de l'ordinateur";
$LANG['plugin_fusinvsnmp']['state'][1]="Arrêt de l'ordinateur";
$LANG['plugin_fusinvsnmp']['state'][2]="Connexion de l'utilisateur";
$LANG['plugin_fusinvsnmp']['state'][3]="Déconnexion de l'utilisateur";
$LANG['plugin_fusinvsnmp']['state'][4]="Date de début";
$LANG['plugin_fusinvsnmp']['state'][5]="Date de fin";
$LANG['plugin_fusinvsnmp']['state'][6]="Total de matériels découverts";
$LANG['plugin_fusinvsnmp']['state'][7]="Total en erreur";

$LANG['plugin_fusinvsnmp']['mapping'][1]="reseaux > lieu";
$LANG['plugin_fusinvsnmp']['mapping'][2]="réseaux > firmware";
$LANG['plugin_fusinvsnmp']['mapping'][3]="réseaux > uptime";
$LANG['plugin_fusinvsnmp']['mapping'][4]="réseaux > port > mtu";
$LANG['plugin_fusinvsnmp']['mapping'][5]="réseaux > port > vitesse";
$LANG['plugin_fusinvsnmp']['mapping'][6]="réseaux > port > statut interne";
$LANG['plugin_fusinvsnmp']['mapping'][7]="réseaux > port > Dernier changement";
$LANG['plugin_fusinvsnmp']['mapping'][8]="réseaux > port > nombre d'octets entrés";
$LANG['plugin_fusinvsnmp']['mapping'][9]="réseaux > port > nombre d'octets sortis";
$LANG['plugin_fusinvsnmp']['mapping'][10]="réseaux > port > nombre d'erreurs entrées";
$LANG['plugin_fusinvsnmp']['mapping'][11]="réseaux > port > nombre d'erreurs sorties";
$LANG['plugin_fusinvsnmp']['mapping'][12]="réseaux > utilisation du CPU";
$LANG['plugin_fusinvsnmp']['mapping'][13]="réseaux > numéro de série";
$LANG['plugin_fusinvsnmp']['mapping'][14]="réseaux > port > statut de la connexion";
$LANG['plugin_fusinvsnmp']['mapping'][15]="réseaux > port > adresse MAC";
$LANG['plugin_fusinvsnmp']['mapping'][16]="réseaux > port > nom";
$LANG['plugin_fusinvsnmp']['mapping'][17]="réseaux > modèle";
$LANG['plugin_fusinvsnmp']['mapping'][18]="réseaux > port > type";
$LANG['plugin_fusinvsnmp']['mapping'][19]="réseaux > VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][20]="réseaux > nom";
$LANG['plugin_fusinvsnmp']['mapping'][21]="réseaux > mémoire totale";
$LANG['plugin_fusinvsnmp']['mapping'][22]="réseaux > mémoire libre";
$LANG['plugin_fusinvsnmp']['mapping'][23]="réseaux > port > description du port";
$LANG['plugin_fusinvsnmp']['mapping'][24]="imprimante > nom";
$LANG['plugin_fusinvsnmp']['mapping'][25]="imprimante > modèle";
$LANG['plugin_fusinvsnmp']['mapping'][26]="imprimante > mémoire totale";
$LANG['plugin_fusinvsnmp']['mapping'][27]="imprimante > numéro de série";
$LANG['plugin_fusinvsnmp']['mapping'][28]="imprimante > compteur > nombre total de pages imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][29]="imprimante > compteur > nombre de pages noir et blanc imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][30]="imprimante > compteur > nombre de pages couleur imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][31]="imprimante > compteur > nombre de pages monochrome imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][32]="imprimante > compteur > nombre de pages bichromie imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][33]="réseaux > port > type de duplex";
$LANG['plugin_fusinvsnmp']['mapping'][34]="imprimante > consommables > cartouche noir (%)";
$LANG['plugin_fusinvsnmp']['mapping'][35]="imprimante > consommables > cartouche noir photo (%)";
$LANG['plugin_fusinvsnmp']['mapping'][36]="imprimante > consommables > cartouche cyan (%)";
$LANG['plugin_fusinvsnmp']['mapping'][37]="imprimante > consommables > cartouche jaune (%)";
$LANG['plugin_fusinvsnmp']['mapping'][38]="imprimante > consommables > cartouche magenta (%)";
$LANG['plugin_fusinvsnmp']['mapping'][39]="imprimante > consommables > cartouche cyan clair (%)";
$LANG['plugin_fusinvsnmp']['mapping'][40]="imprimante > consommables > cartouche magenta clair (%)";
$LANG['plugin_fusinvsnmp']['mapping'][41]="imprimante > consommables > photoconducteur (%)";
$LANG['plugin_fusinvsnmp']['mapping'][42]="imprimante > consommables > photoconducteur noir (%)";
$LANG['plugin_fusinvsnmp']['mapping'][43]="imprimante > consommables > photoconducteur couleur (%)";
$LANG['plugin_fusinvsnmp']['mapping'][44]="imprimante > consommables > photoconducteur cyan (%)";
$LANG['plugin_fusinvsnmp']['mapping'][45]="imprimante > consommables > photoconducteur jaune (%)";
$LANG['plugin_fusinvsnmp']['mapping'][46]="imprimante > consommables > photoconducteur magenta (%)";
$LANG['plugin_fusinvsnmp']['mapping'][47]="imprimante > consommables > unité de transfert noir (%)";
$LANG['plugin_fusinvsnmp']['mapping'][48]="imprimante > consommables > unité de transfert cyan (%)";
$LANG['plugin_fusinvsnmp']['mapping'][49]="imprimante > consommables > unité de transfert jaune (%)";
$LANG['plugin_fusinvsnmp']['mapping'][50]="imprimante > consommables > unité de transfert magenta (%)";
$LANG['plugin_fusinvsnmp']['mapping'][51]="imprimante > consommables > bac récupérateur de déchet (%)";
$LANG['plugin_fusinvsnmp']['mapping'][52]="imprimante > consommables > four (%)";
$LANG['plugin_fusinvsnmp']['mapping'][53]="imprimante > consommables > module de nettoyage (%)";
$LANG['plugin_fusinvsnmp']['mapping'][54]="imprimante > compteur > nombre de pages recto/verso imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][55]="imprimante > compteur > nombre de pages scannées";
$LANG['plugin_fusinvsnmp']['mapping'][56]="imprimante > lieu";
$LANG['plugin_fusinvsnmp']['mapping'][57]="imprimante > port > nom";
$LANG['plugin_fusinvsnmp']['mapping'][58]="imprimante > port > adresse MAC";
$LANG['plugin_fusinvsnmp']['mapping'][59]="imprimante > consommables > cartouche noir (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][60]="imprimante > consommables > cartouche noir (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][61]="imprimante > consommables > cartouche cyan (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][62]="imprimante > consommables > cartouche cyan (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][63]="imprimante > consommables > cartouche jaune (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][64]="imprimante > consommables > cartouche jaune (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][65]="imprimante > consommables > cartouche magenta (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][66]="imprimante > consommables > cartouche magenta (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][67]="imprimante > consommables > cartouche cyan clair (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][68]="imprimante > consommables > cartouche cyan clair (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][69]="imprimante > consommables > cartouche magenta clair (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][70]="imprimante > consommables > cartouche magenta clair (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][71]="imprimante > consommables > photoconducteur (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][72]="imprimante > consommables > photoconducteur (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][73]="imprimante > consommables > photoconducteur noir (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][74]="imprimante > consommables > photoconducteur noir (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][75]="imprimante > consommables > photoconducteur couleur (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][76]="imprimante > consommables > photoconducteur couleur (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][77]="imprimante > consommables > photoconducteur cyan (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][78]="imprimante > consommables > photoconducteur cyan (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][79]="imprimante > consommables > photoconducteur jaune (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][80]="imprimante > consommables > photoconducteur jaune (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][81]="imprimante > consommables > photoconducteur magenta (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][82]="imprimante > consommables > photoconducteur magenta (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][83]="imprimante > consommables > unité de transfert noir (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][84]="imprimante > consommables > unité de transfert noir (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][85]="imprimante > consommables > unité de transfert cyan (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][86]="imprimante > consommables > unité de transfert cyan (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][87]="imprimante > consommables > unité de transfert jaune (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][88]="imprimante > consommables > unité de transfert jaune (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][89]="imprimante > consommables > unité de transfert magenta (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][90]="imprimante > consommables > unité de transfert magenta (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][91]="imprimante > consommables > bac récupérateur de déchet (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][92]="imprimante > consommables > bac récupérateur de déchet (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][93]="imprimante > consommables > four (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][94]="imprimante > consommables > four (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][95]="imprimante > consommables > module de nettoyage (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][96]="imprimante > consommables > module de nettoyage (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][97]="imprimante > port > type";
$LANG['plugin_fusinvsnmp']['mapping'][98]="imprimante > consommables > Kit de maintenance (max)";
$LANG['plugin_fusinvsnmp']['mapping'][99]="imprimante > consommables > Kit de maintenance (restant)";
$LANG['plugin_fusinvsnmp']['mapping'][400]="imprimante > consommables > Kit de maintenance (%)";
$LANG['plugin_fusinvsnmp']['mapping'][401]="réseaux > CPU user";
$LANG['plugin_fusinvsnmp']['mapping'][402]="réseaux > CPU système";
$LANG['plugin_fusinvsnmp']['mapping'][403]="réseaux > contact";
$LANG['plugin_fusinvsnmp']['mapping'][404]="réseaux > description";
$LANG['plugin_fusinvsnmp']['mapping'][405]="imprimante > contact";
$LANG['plugin_fusinvsnmp']['mapping'][406]="imprimante > description";
$LANG['plugin_fusinvsnmp']['mapping'][407]="imprimante > port > adresse IP";
$LANG['plugin_fusinvsnmp']['mapping'][408]="réseaux > port > numéro index";
$LANG['plugin_fusinvsnmp']['mapping'][409]="réseaux > Adresse CDP";
$LANG['plugin_fusinvsnmp']['mapping'][410]="réseaux > port CDP";
$LANG['plugin_fusinvsnmp']['mapping'][411]="réseaux > port > trunk/tagged";
$LANG['plugin_fusinvsnmp']['mapping'][412]="réseaux > Adresses mac filtrées (dot1dTpFdbAddress)";
$LANG['plugin_fusinvsnmp']['mapping'][413]="réseaux > adresses physiques mémorisées (ipNetToMediaPhysAddress)";
$LANG['plugin_fusinvsnmp']['mapping'][414]="réseaux > instances de ports (dot1dTpFdbPort)";
$LANG['plugin_fusinvsnmp']['mapping'][415]="réseaux > numéro de ports associé id du port (dot1dBasePortIfIndex)";
$LANG['plugin_fusinvsnmp']['mapping'][416]="imprimante > port > numéro index";
$LANG['plugin_fusinvsnmp']['mapping'][417]="réseaux > adresse MAC";
$LANG['plugin_fusinvsnmp']['mapping'][418]="imprimante > numéro d'inventaire";
$LANG['plugin_fusinvsnmp']['mapping'][419]="réseaux > numéro d'inventaire";
$LANG['plugin_fusinvsnmp']['mapping'][420]="imprimante > fabricant";
$LANG['plugin_fusinvsnmp']['mapping'][421]="réseaux > addresses IP";
$LANG['plugin_fusinvsnmp']['mapping'][422]="réseaux > portVlanIndex";
$LANG['plugin_fusinvsnmp']['mapping'][423]="imprimante > compteur > nombre total de pages imprimées (impression)";
$LANG['plugin_fusinvsnmp']['mapping'][424]="imprimante > compteur > nombre de pages noir et blanc imprimées (impression)";
$LANG['plugin_fusinvsnmp']['mapping'][425]="imprimante > compteur > nombre de pages couleur imprimées (impression)";
$LANG['plugin_fusinvsnmp']['mapping'][426]="imprimante > compteur > nombre total de pages imprimées (copie)";
$LANG['plugin_fusinvsnmp']['mapping'][427]="imprimante > compteur > nombre de pages noir et blanc imprimées (copie)";
$LANG['plugin_fusinvsnmp']['mapping'][428]="imprimante > compteur > nombre de pages couleur imprimées (copie)";
$LANG['plugin_fusinvsnmp']['mapping'][429]="imprimante > compteur > nombre total de pages imprimées (fax)";
$LANG['plugin_fusinvsnmp']['mapping'][430]="réseaux > port > vlan";


$LANG['plugin_fusinvsnmp']['mapping'][101]="";
$LANG['plugin_fusinvsnmp']['mapping'][102]="";
$LANG['plugin_fusinvsnmp']['mapping'][103]="";
$LANG['plugin_fusinvsnmp']['mapping'][104]="MTU";
$LANG['plugin_fusinvsnmp']['mapping'][105]="Vitesse";
$LANG['plugin_fusinvsnmp']['mapping'][106]="Statut Interne";
$LANG['plugin_fusinvsnmp']['mapping'][107]="Dernier changement";
$LANG['plugin_fusinvsnmp']['mapping'][108]="Nb d'octets recus";
$LANG['plugin_fusinvsnmp']['mapping'][109]="Nb d'octets envoyés";
$LANG['plugin_fusinvsnmp']['mapping'][110]="Nb d'erreurs en entrée";
$LANG['plugin_fusinvsnmp']['mapping'][111]="Nb d'erreurs en sortie";
$LANG['plugin_fusinvsnmp']['mapping'][112]="Utilisation du CPU";
$LANG['plugin_fusinvsnmp']['mapping'][113]="";
$LANG['plugin_fusinvsnmp']['mapping'][114]="Connexion";
$LANG['plugin_fusinvsnmp']['mapping'][115]="MAC interne";
$LANG['plugin_fusinvsnmp']['mapping'][116]="Nom";
$LANG['plugin_fusinvsnmp']['mapping'][117]="Modèle";
$LANG['plugin_fusinvsnmp']['mapping'][118]="Type";
$LANG['plugin_fusinvsnmp']['mapping'][119]="VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][128]="Nombre total de pages imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][129]="Nombre de pages noir et blanc imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][130]="Nombre de pages couleur imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][131]="Nombre de pages monochrome imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][132]="Nombre de pages bichromie imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][134]="Cartouche noir";
$LANG['plugin_fusinvsnmp']['mapping'][135]="Cartouche noir photo";
$LANG['plugin_fusinvsnmp']['mapping'][136]="Cartouche cyan";
$LANG['plugin_fusinvsnmp']['mapping'][137]="Cartouche jaune";
$LANG['plugin_fusinvsnmp']['mapping'][138]="Cartouche magenta";
$LANG['plugin_fusinvsnmp']['mapping'][139]="Cartouche cyan clair";
$LANG['plugin_fusinvsnmp']['mapping'][140]="Cartouche magenta clair";
$LANG['plugin_fusinvsnmp']['mapping'][141]="Photoconducteur";
$LANG['plugin_fusinvsnmp']['mapping'][142]="Photoconducteur noir";
$LANG['plugin_fusinvsnmp']['mapping'][143]="Photoconducteur couleur";
$LANG['plugin_fusinvsnmp']['mapping'][144]="Photoconducteur cyan";
$LANG['plugin_fusinvsnmp']['mapping'][145]="Photoconducteur jaune";
$LANG['plugin_fusinvsnmp']['mapping'][146]="Photoconducteur magenta";
$LANG['plugin_fusinvsnmp']['mapping'][147]="Unité de transfert noir";
$LANG['plugin_fusinvsnmp']['mapping'][148]="Unité de transfert cyan";
$LANG['plugin_fusinvsnmp']['mapping'][149]="Unité de transfert jaune";
$LANG['plugin_fusinvsnmp']['mapping'][150]="Unité de transfert magenta";
$LANG['plugin_fusinvsnmp']['mapping'][151]="Bac récupérateur de déchet";
$LANG['plugin_fusinvsnmp']['mapping'][152]="Four";
$LANG['plugin_fusinvsnmp']['mapping'][153]="Module de nettoyage";
$LANG['plugin_fusinvsnmp']['mapping'][154]="Nombre de pages recto/verso imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][155]="Nombre de pages scannées";
$LANG['plugin_fusinvsnmp']['mapping'][156]="Kit de maintenance";
$LANG['plugin_fusinvsnmp']['mapping'][157]="Toner Noir";
$LANG['plugin_fusinvsnmp']['mapping'][158]="Toner Cyan";
$LANG['plugin_fusinvsnmp']['mapping'][159]="Toner Magenta";
$LANG['plugin_fusinvsnmp']['mapping'][160]="Toner Jaune";
$LANG['plugin_fusinvsnmp']['mapping'][161]="Tambour Noir";
$LANG['plugin_fusinvsnmp']['mapping'][162]="Tambour Cyan";
$LANG['plugin_fusinvsnmp']['mapping'][163]="Tambour Magenta";
$LANG['plugin_fusinvsnmp']['mapping'][164]="Tambour Jaune";
$LANG['plugin_fusinvsnmp']['mapping'][165]="Informations diverses regroupées";
$LANG['plugin_fusinvsnmp']['mapping'][166]="Toner Noir 2";
$LANG['plugin_fusinvsnmp']['mapping'][1423]="Nombre total de pages imprimées (impression)";
$LANG['plugin_fusinvsnmp']['mapping'][1424]="Nombre de pages noir et blanc imprimées (impression)";
$LANG['plugin_fusinvsnmp']['mapping'][1425]="Nombre de pages couleur imprimées (impression)";
$LANG['plugin_fusinvsnmp']['mapping'][1426]="Nombre total de pages imprimées (copie)";
$LANG['plugin_fusinvsnmp']['mapping'][1427]="Nombre de pages noir et blanc imprimées (copie)";
$LANG['plugin_fusinvsnmp']['mapping'][1428]="Nombre de pages couleur imprimées (copie)";
$LANG['plugin_fusinvsnmp']['mapping'][1429]="Nombre total de pages imprimées (fax)";


$LANG['plugin_fusinvsnmp']['printer'][0]="pages";

$LANG['plugin_fusinvsnmp']['menu'][0]="Découverte de matériel réseau";
$LANG['plugin_fusinvsnmp']['menu'][1]="Gestion des agents";
$LANG['plugin_fusinvsnmp']['menu'][2]="Plages IP";
$LANG['plugin_fusinvsnmp']['menu'][3]="Menu";
$LANG['plugin_fusinvsnmp']['menu'][4]="Matériel inconnu";
$LANG['plugin_fusinvsnmp']['menu'][5]="Historique des ports de switchs";
$LANG['plugin_fusinvsnmp']['menu'][6]="Ports de switchs inutilisés";
$LANG['plugin_fusinvsnmp']['menu'][7]="Règles de critères découverte";
$LANG['plugin_fusinvsnmp']['menu'][8]="Règles de critères inventaire";
$LANG['plugin_fusinvsnmp']['menu'][9]="Etat des découvertes";
$LANG['plugin_fusinvsnmp']['menu'][10]="Etat des inventaires réseaux";

$LANG['plugin_fusinvsnmp']['buttons'][0]="Découvrir";

$LANG['plugin_fusinvsnmp']['discovery'][0]="Plage d'ip à scanner";
$LANG['plugin_fusinvsnmp']['discovery'][1]="Liste du matériel découvert";
$LANG['plugin_fusinvsnmp']['discovery'][2]="² dans le script en automatique";
$LANG['plugin_fusinvsnmp']['discovery'][3]="Découverte";
$LANG['plugin_fusinvsnmp']['discovery'][4]="Numéros de série";
$LANG['plugin_fusinvsnmp']['discovery'][5]="Nombre de matériels importés";
$LANG['plugin_fusinvsnmp']['discovery'][8]="Si tous les critères d'existence se confrontent à des champs vides, vous pouvez sélectionner des critères secondaires.";
$LANG['plugin_fusinvsnmp']['discovery'][9]="Nombre de matériels non importés car type non défini";

$LANG['plugin_fusinvsnmp']['iprange'][0]="Début de la plage IP";
$LANG['plugin_fusinvsnmp']['iprange'][1]="Fin de la plage IP";
$LANG['plugin_fusinvsnmp']['iprange'][2]="Plage IP";
$LANG['plugin_fusinvsnmp']['iprange'][3]="Activation";
$LANG['plugin_fusinvsnmp']['iprange'][4]="Adresse IP incorrecte";
$LANG['plugin_fusinvsnmp']['iprange'][5]="Edition de plage IP";
$LANG['plugin_fusinvsnmp']['iprange'][6]="Création d'une plage IP";
$LANG['plugin_fusinvsnmp']['iprange'][7]="IP incorrecte";

$LANG['plugin_fusinvsnmp']['agents'][0]="Agent SNMP";
$LANG['plugin_fusinvsnmp']['agents'][2]="Threads interrogation (par coeur)";
$LANG['plugin_fusinvsnmp']['agents'][3]="Threads découverte (par coeur)";
$LANG['plugin_fusinvsnmp']['agents'][4]="Dernière remontée";
$LANG['plugin_fusinvsnmp']['agents'][5]="Version de l'agent";
$LANG['plugin_fusinvsnmp']['agents'][6]="Verrouillage";
$LANG['plugin_fusinvsnmp']['agents'][7]="Export config agent";
$LANG['plugin_fusinvsnmp']['agents'][9]="Options avancées";
$LANG['plugin_fusinvsnmp']['agents'][12]="Agent découverte";
$LANG['plugin_fusinvsnmp']['agents'][13]="Agent interrogation";
$LANG['plugin_fusinvsnmp']['agents'][14]="Actions de l'agent";
$LANG['plugin_fusinvsnmp']['agents'][15]="Statut de l'agent";
$LANG['plugin_fusinvsnmp']['agents'][16]="Initialisé";
$LANG['plugin_fusinvsnmp']['agents'][17]="L'agent s'exécute";
$LANG['plugin_fusinvsnmp']['agents'][18]="L'inventaire a été reçu";
$LANG['plugin_fusinvsnmp']['agents'][19]="L'inventaire est envoyé au serveur OCS";
$LANG['plugin_fusinvsnmp']['agents'][20]="La synchronisation entre OCS et GLPI est en cours";
$LANG['plugin_fusinvsnmp']['agents'][21]="Inventaire terminé";
$LANG['plugin_fusinvsnmp']['agents'][22]="En attente";
$LANG['plugin_fusinvsnmp']['agents'][23]="Lié à l'ordinateur";
$LANG['plugin_fusinvsnmp']['agents'][24]="Nombre de threads";
$LANG['plugin_fusinvsnmp']['agents'][25]="Agent(s)";
$LANG['plugin_fusinvsnmp']['agents'][26]="Version du module netdiscovery";
$LANG['plugin_fusinvsnmp']['agents'][27]="Version du module snmpquery";

$LANG['plugin_fusinvsnmp']['task'][0]="Tâche";
$LANG['plugin_fusinvsnmp']['task'][1]="Gestion des tâches";
$LANG['plugin_fusinvsnmp']['task'][2]="Action";
$LANG['plugin_fusinvsnmp']['task'][3]="Unitaire";
$LANG['plugin_fusinvsnmp']['task'][4]="Récupérer maintenant les informations";
$LANG['plugin_fusinvsnmp']['task'][5]="Sélectionner l'agent OCS";
$LANG['plugin_fusinvsnmp']['task'][6]="Récupérer son état";
$LANG['plugin_fusinvsnmp']['task'][7]="Etat";
$LANG['plugin_fusinvsnmp']['task'][8]="Prêt";
$LANG['plugin_fusinvsnmp']['task'][9]="Ne peut pas être contacté";
$LANG['plugin_fusinvsnmp']['task'][10]="En cours d'execution... pas disponible";
$LANG['plugin_fusinvsnmp']['task'][11]="L'agent a été notifié et commence son exécution";
$LANG['plugin_fusinvsnmp']['task'][12]="Déclencher l'agent";
$LANG['plugin_fusinvsnmp']['task'][13]="Agent(s) indisponible(s)";
$LANG['plugin_fusinvsnmp']['task'][14]="Planifié le";
$LANG['plugin_fusinvsnmp']['task'][15]="Tâche permanente - Découverte";
$LANG['plugin_fusinvsnmp']['task'][16]="Tâche permanente - Inventaire";
$LANG['plugin_fusinvsnmp']['task'][17]="Mode de communication";

$LANG['plugin_fusinvsnmp']['constructdevice'][0]="Gestion des mib de matériel";
$LANG['plugin_fusinvsnmp']['constructdevice'][1]="Creation automatique des modèles";
$LANG['plugin_fusinvsnmp']['constructdevice'][2]="Générer le fichier de découverte";
$LANG['plugin_fusinvsnmp']['constructdevice'][3]="Supprimer modèles non utilisés";
$LANG['plugin_fusinvsnmp']['constructdevice'][4]="Exporter tous les modèles";
$LANG['plugin_fusinvsnmp']['constructdevice'][5]="Regénérer les commentaires de modèles";

$LANG['plugin_fusinvsnmp']['update'][0]="Votre historique fait plus de 300 000 lignes, il faut lancer la commande suivante en ligne de commande pour finir la mise à jour : ";

$LANG['plugin_fusinvsnmp']['stats'][0]="Compteur total";
$LANG['plugin_fusinvsnmp']['stats'][1]="pages par jour";
$LANG['plugin_fusinvsnmp']['stats'][2]="Affichage";


$LANG['plugin_fusinvsnmp']['xml'][0]="XML FusionInventory";
?>