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

$title="Tracker";
$version="2.1.0";

$LANG['tracker']["title"][0]="$title";
$LANG['tracker']["title"][1]="Informations SNMP";
$LANG['tracker']["title"][2]="Historique de connexion";
$LANG['tracker']["title"][3]="[Trk] Erreurs";
$LANG['tracker']["title"][4]="[Trk] Cron";

$LANG['tracker']["profile"][0]="Gestion des droits";
$LANG['tracker']["profile"][1]="$title"; //interface

$LANG['tracker']["profile"][10]="Listes des profils déjà configurés";
$LANG['tracker']["profile"][11]="Historique Ordinateurs";
$LANG['tracker']["profile"][12]="Historique Imprimantes";
$LANG['tracker']["profile"][13]="Infos Imprimantes";
$LANG['tracker']["profile"][14]="Infos Réseau";
$LANG['tracker']["profile"][15]="Erreurs courantes";

$LANG['tracker']["profile"][16]="SNMP Réseaux";
$LANG['tracker']["profile"][17]="SNMP Périphériques";
$LANG['tracker']["profile"][18]="SNMP Imprimantes";
$LANG['tracker']["profile"][19]="Modèles SNMP";
$LANG['tracker']["profile"][20]="Authentification SNMP";
$LANG['tracker']["profile"][21]="Infos scripts";
$LANG['tracker']["profile"][22]="Découverte réseau";
$LANG['tracker']["profile"][23]="Configuration générale";
$LANG['tracker']["profile"][24]="Modèle SNMP";
$LANG['tracker']["profile"][25]="Plages IP";
$LANG['tracker']["profile"][26]="Agents";
$LANG['tracker']["profile"][27]="Infos agent";
$LANG['tracker']["profile"][28]="Rapport";

$LANG['tracker']["setup"][2]="Merci de vous placer sur l'entité racine (voir tous)";
$LANG['tracker']["setup"][3]="Configuration du plugin ".$title;
$LANG['tracker']["setup"][4]="Installer le plugin $title $version";
$LANG['tracker']["setup"][5]="Mettre à jour le plugin $title vers la version $version";
$LANG['tracker']["setup"][6]="Désinstaller le plugin $title $version";
$LANG['tracker']["setup"][8]="Attention, la désinstallation du plugin est irréversible.<br> Vous perdrez toutes les données.";
$LANG['tracker']["setup"][11]="Mode d'emploi";
$LANG['tracker']["setup"][12]="FAQ";
$LANG['tracker']["setup"][13]="Vérification des modules PHP nécessaires";
$LANG['tracker']["setup"][14]="L'extension snmp de PHP n'est pas chargée";
$LANG['tracker']["setup"][15]="L'extension runkit de PHP/PECL n'est pas chargée";

$LANG['tracker']["functionalities"][0]="Fonctionnalités";
$LANG['tracker']["functionalities"][1]="Ajout / Suppression de fonctionnalités";
$LANG['tracker']["functionalities"][2]="Configuration générale";
$LANG['tracker']["functionalities"][3]="SNMP";
$LANG['tracker']["functionalities"][4]="Connexion";
$LANG['tracker']["functionalities"][5]="Script serveur";

$LANG['tracker']["functionalities"][10]="Activation de l'historique";
$LANG['tracker']["functionalities"][11]="Activation du module connexion";
$LANG['tracker']["functionalities"][12]="Activation du module SNMP réseaux";
$LANG['tracker']["functionalities"][13]="Activation du module SNMP périphériques";
$LANG['tracker']["functionalities"][14]="Activation du module SNMP téléphones";
$LANG['tracker']["functionalities"][15]="Activation du module SNMP imprimantes";
$LANG['tracker']["functionalities"][16]="Stockage de l'authentification SNMP";
$LANG['tracker']["functionalities"][17]="Base de données";
$LANG['tracker']["functionalities"][18]="Fichiers";
$LANG['tracker']["functionalities"][19]="Veuillez configurer le stockage de l'authentification SNMP dans la configuration du plugin";
$LANG['tracker']["functionalities"][20]="Statut du matériel actif";
$LANG['tracker']["functionalities"][21]="Rétention de l'historique d'interconnexions entre matériels en jours (0 = infini)";
$LANG['tracker']["functionalities"][22]="Rétention de l'historique de changement d'état des ports (0 = infini)";
$LANG['tracker']["functionalities"][23]="Rétention de l'historique des adresses MAC inconnues (0 = infini)";
$LANG['tracker']["functionalities"][24]="Rétention de l'historique des erreurs SNMP (0 = infini)";
$LANG['tracker']["functionalities"][25]="Rétention de l'historique des processes des scripts (0 = infini)";
$LANG['tracker']["functionalities"][26]="URL de GLPI pour l'agent";
$LANG['tracker']["functionalities"][27]="SSL seulement pour l'agent";

$LANG['tracker']["functionalities"][30]="Statut du matériel actif";
$LANG['tracker']["functionalities"][31]="Gestion des cartouches et du stock";
$LANG['tracker']["functionalities"][36]="Fréquence de relevé des compteurs";

$LANG['tracker']["functionalities"][40]="Configuration";
$LANG['tracker']["functionalities"][41]="Statut du matériel actif";
$LANG['tracker']["functionalities"][42]="Commutateur";
$LANG['tracker']["functionalities"][43]="Authentification SNMP";

$LANG['tracker']["functionalities"][50]="Nombre de process simultanés pour la découverte réseau";
$LANG['tracker']["functionalities"][51]="Nombre de process simultanés pour l'interrogation SNMP";
$LANG['tracker']["functionalities"][52]="Activation des journaux";
$LANG['tracker']["functionalities"][53]="Nombre de process simultanés pour le script serveur de post-traitement";

$LANG['tracker']["snmp"][0]="Informations SNMP du matériel";
$LANG['tracker']["snmp"][1]="Général";
$LANG['tracker']["snmp"][2]="Cablâge";
$LANG['tracker']["snmp"][2]="Données SNMP";

$LANG['tracker']["snmp"][11]="Informations supplémentaires";
$LANG['tracker']["snmp"][12]="Uptime";
$LANG['tracker']["snmp"][13]="Utilisation du CPU (en %)";
$LANG['tracker']["snmp"][14]="Utilisation de la mémoire (en %)";

$LANG['tracker']["snmp"][31]="Impossible de récupérer les infos SNMP : Ce n'est pas un commutateur !";
$LANG['tracker']["snmp"][32]="Impossible de récupérer les infos SNMP : Matériel non actif !";
$LANG['tracker']["snmp"][33]="Impossible de récupérer les infos SNMP : IP non précisée dans la base !";
$LANG['tracker']["snmp"][34]="Le commutateur auquel est reliée la machine n'est pas renseigné !";

$LANG['tracker']["snmp"][41]="";
$LANG['tracker']["snmp"][42]="MTU";
$LANG['tracker']["snmp"][43]="Vitesse";
$LANG['tracker']["snmp"][44]="Statut Interne";
$LANG['tracker']["snmp"][45]="Dernier changement";
$LANG['tracker']["snmp"][46]="Nb d'octets recus";
$LANG['tracker']["snmp"][47]="Nb d'erreurs en entrée";
$LANG['tracker']["snmp"][48]="Nb d'octets envoyés";
$LANG['tracker']["snmp"][49]="Nb d'erreurs en réception";
$LANG['tracker']["snmp"][50]="Connexion";
$LANG['tracker']["snmp"][51]="Duplex";
$LANG['tracker']["snmp"][52]="Date dernier inventaire TRACKER";
$LANG['tracker']["snmp"][53]="Dernier inventaire";

$LANG['tracker']["snmpauth"][1]="Communauté";
$LANG['tracker']["snmpauth"][2]="Utilisateur";
$LANG['tracker']["snmpauth"][3]="Schéma d'authentification";
$LANG['tracker']["snmpauth"][4]="Protocole de cryptage pour authentification ";
$LANG['tracker']["snmpauth"][5]="Mot de passe";
$LANG['tracker']["snmpauth"][6]="Protocole de cryptage pour les données (écriture)";
$LANG['tracker']["snmpauth"][7]="Mot de passe (écriture)";

$LANG['tracker']["cron"][0]="Relevé automatique du compteur";
$LANG['tracker']["cron"][1]="Activer le relevé";
$LANG['tracker']["cron"][2]="";
$LANG['tracker']["cron"][3]="Défaut";

$LANG['tracker']["errors"][0]="Erreurs";
$LANG['tracker']["errors"][1]="IP";
$LANG['tracker']["errors"][2]="Description";
$LANG['tracker']["errors"][3]="Date 1er pb";
$LANG['tracker']["errors"][4]="Date dernier pb";

$LANG['tracker']["errors"][10]="Incohérence avec la base GLPI";
$LANG['tracker']["errors"][11]="Poste inconnu";
$LANG['tracker']["errors"][12]="IP inconnue";

$LANG['tracker']["errors"][20]="Erreur SNMP";
$LANG['tracker']["errors"][21]="Impossible de récupérer les informations";

$LANG['tracker']["errors"][30]="Erreur Câblage";
$LANG['tracker']["errors"][31]="Problème de câblage";

$LANG['tracker']["errors"][101]="Timeout";
$LANG['tracker']["errors"][102]="Modele SNMP non assigné";
$LANG['tracker']["errors"][103]="Authentification SNMP non assigné";

$LANG['tracker']["history"][0] = "Ancienne";
$LANG['tracker']["history"][1] = "Nouvelle";
$LANG['tracker']["history"][2] = "Déconnexion";
$LANG['tracker']["history"][3] = "Connexion";

$LANG['tracker']["prt_history"][0]="Historique et Statistiques des compteurs imprimante";

$LANG['tracker']["prt_history"][10]="Statistiques des compteurs imprimante sur";
$LANG['tracker']["prt_history"][11]="jour(s)";
$LANG['tracker']["prt_history"][12]="Pages imprimées totales";
$LANG['tracker']["prt_history"][13]="Pages / jour";

$LANG['tracker']["prt_history"][20]="Historique des compteurs imprimante";
$LANG['tracker']["prt_history"][21]="Date";
$LANG['tracker']["prt_history"][22]="Compteur";


$LANG['tracker']["cpt_history"][0]="Historique des sessions";
$LANG['tracker']["cpt_history"][1]="Contact";
$LANG['tracker']["cpt_history"][2]="Ordinateur";
$LANG['tracker']["cpt_history"][3]="Utilisateur";
$LANG['tracker']["cpt_history"][4]="Etat";
$LANG['tracker']["cpt_history"][5]="Date";


$LANG['tracker']["type"][1]="Ordinateur";
$LANG['tracker']["type"][2]="Commutateur";
$LANG['tracker']["type"][3]="Imprimante";

$LANG['tracker']["rules"][1]="Règles";

$LANG['tracker']["massiveaction"][1]="Assigner un modèle SNMP";
$LANG['tracker']["massiveaction"][2]="Assigner une authentification SNMP";

$LANG['tracker']["model_info"][1]="Informations SNMP";
$LANG['tracker']["model_info"][2]="Version SNMP";
$LANG['tracker']["model_info"][3]="Authentification SNMP";
$LANG['tracker']["model_info"][4]="Modèles SNMP";
$LANG['tracker']["model_info"][5]="Gestion des MIB";
$LANG['tracker']["model_info"][6]="Edition de modèle SNMP";
$LANG['tracker']["model_info"][7]="Création de modèle SNMP";
$LANG['tracker']["model_info"][8]="Modèle déjà existant : import non effectué";
$LANG['tracker']["model_info"][9]="Import effectué avec succès";
$LANG['tracker']["model_info"][10]="Importation de modèle";
$LANG['tracker']["model_info"][11]="Activation";
$LANG['tracker']["model_info"][12]="Clé modèle pour la découverte";

$LANG['tracker']["mib"][1]="Label MIB";
$LANG['tracker']["mib"][2]="Objet";
$LANG['tracker']["mib"][3]="oid";
$LANG['tracker']["mib"][4]="Ajouter un oid...";
$LANG['tracker']["mib"][5]="Liste des oid";
$LANG['tracker']["mib"][6]="Compteur de ports";
$LANG['tracker']["mib"][7]="Port dynamique (.x)";
$LANG['tracker']["mib"][8]="Liaison champs";
$LANG['tracker']["mib"][9]="vlan";

$LANG['tracker']["processes"][0]="Informations sur l'exécution du script serveur";
$LANG['tracker']["processes"][1]="PID";
$LANG['tracker']["processes"][2]="Statut";
$LANG['tracker']["processes"][3]="Nombre de process";
$LANG['tracker']["processes"][4]="Date de début d'exécution";
$LANG['tracker']["processes"][5]="Date de fin d'exécution";
$LANG['tracker']["processes"][6]="Equipements réseau interrogés";
$LANG['tracker']["processes"][7]="Imprimantes interrogées";
$LANG['tracker']["processes"][8]="Ports réseau interrogés";
$LANG['tracker']["processes"][9]="Erreurs";
$LANG['tracker']["processes"][10]="Durée totale d'exécution du script";
$LANG['tracker']["processes"][11]="Champs ajoutés";
$LANG['tracker']["processes"][12]="Erreurs SNMP";
$LANG['tracker']["processes"][13]="MAC inconnues";
$LANG['tracker']["processes"][14]="Liste des adresse MAC inconnues";
$LANG['tracker']["processes"][15]="Premier PID";
$LANG['tracker']["processes"][16]="Dernier PID";
$LANG['tracker']["processes"][17]="Date de la première détection";
$LANG['tracker']["processes"][18]="Date de la dernière détection";
$LANG['tracker']["processes"][19]="Informations sur l'exécution des agents";
$LANG['tracker']["processes"][20]="Rapports / statistiques";
$LANG['tracker']["processes"][21]="Equipements interrogés";
$LANG['tracker']["processes"][22]="Erreurs";
$LANG['tracker']["processes"][23]="Durée totale de la découverte";
$LANG['tracker']["processes"][24]="Durée totale de l'interrogation";

$LANG['tracker']["state"][0]="Démarrage de l'ordinateur";
$LANG['tracker']["state"][1]="Arrêt de l'ordinateur";
$LANG['tracker']["state"][2]="Connexion de l'utilisateur";
$LANG['tracker']["state"][3]="Déconnexion de l'utilisateur";


$LANG['tracker']["mapping"][1]="reseaux > lieu";
$LANG['tracker']["mapping"][2]="réseaux > firmware";
$LANG['tracker']["mapping"][3]="réseaux > uptime";
$LANG['tracker']["mapping"][4]="réseaux > port > mtu";
$LANG['tracker']["mapping"][5]="réseaux > port > vitesse";
$LANG['tracker']["mapping"][6]="réseaux > port > statut interne";
$LANG['tracker']["mapping"][7]="réseaux > port > Dernier changement";
$LANG['tracker']["mapping"][8]="réseaux > port > nombre d'octets entrés";
$LANG['tracker']["mapping"][9]="réseaux > port > nombre d'octets sortis";
$LANG['tracker']["mapping"][10]="réseaux > port > nombre d'erreurs entrées";
$LANG['tracker']["mapping"][11]="réseaux > port > nombre d'erreurs sorties";
$LANG['tracker']["mapping"][12]="réseaux > utilisation du CPU";
$LANG['tracker']["mapping"][13]="réseaux > numéro de série";
$LANG['tracker']["mapping"][14]="réseaux > port > statut de la connexion";
$LANG['tracker']["mapping"][15]="réseaux > port > adresse MAC";
$LANG['tracker']["mapping"][16]="réseaux > port > nom";
$LANG['tracker']["mapping"][17]="réseaux > modèle";
$LANG['tracker']["mapping"][18]="réseaux > port > type";
$LANG['tracker']["mapping"][19]="réseaux > VLAN";
$LANG['tracker']["mapping"][20]="réseaux > nom";
$LANG['tracker']["mapping"][21]="réseaux > mémoire totale";
$LANG['tracker']["mapping"][22]="réseaux > mémoire libre";
$LANG['tracker']["mapping"][23]="réseaux > port > description du port";
$LANG['tracker']["mapping"][24]="imprimante > nom";
$LANG['tracker']["mapping"][25]="imprimante > modèle";
$LANG['tracker']["mapping"][26]="imprimante > mémoire totale";
$LANG['tracker']["mapping"][27]="imprimante > numéro de série";
$LANG['tracker']["mapping"][28]="imprimante > compteur > nombre total de pages imprimées";
$LANG['tracker']["mapping"][29]="imprimante > compteur > nombre de pages noir et blanc imprimées";
$LANG['tracker']["mapping"][30]="imprimante > compteur > nombre de pages couleur imprimées";
$LANG['tracker']["mapping"][31]="imprimante > compteur > nombre de pages monochrome imprimées";
$LANG['tracker']["mapping"][32]="imprimante > compteur > nombre de pages bichromie imprimées";
$LANG['tracker']["mapping"][33]="réseaux > port > type de duplex";
$LANG['tracker']["mapping"][34]="imprimante > consommables > cartouche noir (%)";
$LANG['tracker']["mapping"][35]="imprimante > consommables > cartouche noir photo (%)";
$LANG['tracker']["mapping"][36]="imprimante > consommables > cartouche cyan (%)";
$LANG['tracker']["mapping"][37]="imprimante > consommables > cartouche jaune (%)";
$LANG['tracker']["mapping"][38]="imprimante > consommables > cartouche magenta (%)";
$LANG['tracker']["mapping"][39]="imprimante > consommables > cartouche cyan clair (%)";
$LANG['tracker']["mapping"][40]="imprimante > consommables > cartouche magenta clair (%)";
$LANG['tracker']["mapping"][41]="imprimante > consommables > photoconducteur (%)";
$LANG['tracker']["mapping"][42]="imprimante > consommables > photoconducteur noir (%)";
$LANG['tracker']["mapping"][43]="imprimante > consommables > photoconducteur couleur (%)";
$LANG['tracker']["mapping"][44]="imprimante > consommables > photoconducteur cyan (%)";
$LANG['tracker']["mapping"][45]="imprimante > consommables > photoconducteur jaune (%)";
$LANG['tracker']["mapping"][46]="imprimante > consommables > photoconducteur magenta (%)";
$LANG['tracker']["mapping"][47]="imprimante > consommables > unité de transfert noir (%)";
$LANG['tracker']["mapping"][48]="imprimante > consommables > unité de transfert cyan (%)";
$LANG['tracker']["mapping"][49]="imprimante > consommables > unité de transfert jaune (%)";
$LANG['tracker']["mapping"][50]="imprimante > consommables > unité de transfert magenta (%)";
$LANG['tracker']["mapping"][51]="imprimante > consommables > bac récupérateur de déchet (%)";
$LANG['tracker']["mapping"][52]="imprimante > consommables > four (%)";
$LANG['tracker']["mapping"][53]="imprimante > consommables > module de nettoyage (%)";
$LANG['tracker']["mapping"][54]="imprimante > compteur > nombre de pages recto/verso imprimées";
$LANG['tracker']["mapping"][55]="imprimante > compteur > nombre de pages scannées";
$LANG['tracker']["mapping"][56]="imprimante > lieu";
$LANG['tracker']["mapping"][57]="imprimante > port > nom";
$LANG['tracker']["mapping"][58]="imprimante > port > adresse MAC";
$LANG['tracker']["mapping"][59]="imprimante > consommables > cartouche noir (encre max)";
$LANG['tracker']["mapping"][60]="imprimante > consommables > cartouche noir (encre restant)";
$LANG['tracker']["mapping"][61]="imprimante > consommables > cartouche cyan (encre max)";
$LANG['tracker']["mapping"][62]="imprimante > consommables > cartouche cyan (encre restant)";
$LANG['tracker']["mapping"][63]="imprimante > consommables > cartouche jaune (encre max)";
$LANG['tracker']["mapping"][64]="imprimante > consommables > cartouche jaune (encre restant)";
$LANG['tracker']["mapping"][65]="imprimante > consommables > cartouche magenta (encre max)";
$LANG['tracker']["mapping"][66]="imprimante > consommables > cartouche magenta (encre restant)";
$LANG['tracker']["mapping"][67]="imprimante > consommables > cartouche cyan clair (encre max)";
$LANG['tracker']["mapping"][68]="imprimante > consommables > cartouche cyan clair (encre restant)";
$LANG['tracker']["mapping"][69]="imprimante > consommables > cartouche magenta clair (encre max)";
$LANG['tracker']["mapping"][70]="imprimante > consommables > cartouche magenta clair (encre restant)";
$LANG['tracker']["mapping"][71]="imprimante > consommables > photoconducteur (encre max)";
$LANG['tracker']["mapping"][72]="imprimante > consommables > photoconducteur (encre restant)";
$LANG['tracker']["mapping"][73]="imprimante > consommables > photoconducteur noir (encre max)";
$LANG['tracker']["mapping"][74]="imprimante > consommables > photoconducteur noir (encre restant)";
$LANG['tracker']["mapping"][75]="imprimante > consommables > photoconducteur couleur (encre max)";
$LANG['tracker']["mapping"][76]="imprimante > consommables > photoconducteur couleur (encre restant)";
$LANG['tracker']["mapping"][77]="imprimante > consommables > photoconducteur cyan (encre max)";
$LANG['tracker']["mapping"][78]="imprimante > consommables > photoconducteur cyan (encre restant)";
$LANG['tracker']["mapping"][79]="imprimante > consommables > photoconducteur jaune (encre max)";
$LANG['tracker']["mapping"][80]="imprimante > consommables > photoconducteur jaune (encre restant)";
$LANG['tracker']["mapping"][81]="imprimante > consommables > photoconducteur magenta (encre max)";
$LANG['tracker']["mapping"][82]="imprimante > consommables > photoconducteur magenta (encre restant)";
$LANG['tracker']["mapping"][83]="imprimante > consommables > unité de transfert noir (encre max)";
$LANG['tracker']["mapping"][84]="imprimante > consommables > unité de transfert noir (encre restant)";
$LANG['tracker']["mapping"][85]="imprimante > consommables > unité de transfert cyan (encre max)";
$LANG['tracker']["mapping"][86]="imprimante > consommables > unité de transfert cyan (encre restant)";
$LANG['tracker']["mapping"][87]="imprimante > consommables > unité de transfert jaune (encre max)";
$LANG['tracker']["mapping"][88]="imprimante > consommables > unité de transfert jaune (encre restant)";
$LANG['tracker']["mapping"][89]="imprimante > consommables > unité de transfert magenta (encre max)";
$LANG['tracker']["mapping"][90]="imprimante > consommables > unité de transfert magenta (encre restant)";
$LANG['tracker']["mapping"][91]="imprimante > consommables > bac récupérateur de déchet (encre max)";
$LANG['tracker']["mapping"][92]="imprimante > consommables > bac récupérateur de déchet (encre restant)";
$LANG['tracker']["mapping"][93]="imprimante > consommables > four (encre max)";
$LANG['tracker']["mapping"][94]="imprimante > consommables > four (encre restant)";
$LANG['tracker']["mapping"][95]="imprimante > consommables > module de nettoyage (encre max)";
$LANG['tracker']["mapping"][96]="imprimante > consommables > module de nettoyage (encre restant)";
$LANG['tracker']["mapping"][97]="imprimante > port > type";
$LANG['tracker']["mapping"][98]="imprimante > consommables > Kit de maintenance (max)";
$LANG['tracker']["mapping"][99]="imprimante > consommables > Kit de maintenance (restant)";
$LANG['tracker']["mapping"][400]="imprimante > consommables > Kit de maintenance (%)";
$LANG['tracker']["mapping"][401]="réseaux > CPU user";
$LANG['tracker']["mapping"][402]="réseaux > CPU système";
$LANG['tracker']["mapping"][403]="réseaux > contact";
$LANG['tracker']["mapping"][404]="réseaux > description";
$LANG['tracker']["mapping"][405]="imprimante > contact";
$LANG['tracker']["mapping"][406]="imprimante > description";
$LANG['tracker']["mapping"][407]="imprimante > port > adresse IP";
$LANG['tracker']["mapping"][408]="réseaux > port > numéro index";
$LANG['tracker']["mapping"][409]="réseaux > Adresse CDP";
$LANG['tracker']["mapping"][410]="réseaux > port CDP";
$LANG['tracker']["mapping"][411]="réseaux > statut port Trunk";
$LANG['tracker']["mapping"][412]="réseaux > Adresses mac filtrées (dot1dTpFdbAddress)";
$LANG['tracker']["mapping"][413]="réseaux > adresses physiques mémorisées (ipNetToMediaPhysAddress)";
$LANG['tracker']["mapping"][414]="réseaux > instances de ports (dot1dTpFdbPort)";
$LANG['tracker']["mapping"][415]="réseaux > numéro de ports associé ID du port (dot1dBasePortIfIndex)";
$LANG['tracker']["mapping"][416]="imprimante > port > numéro index";
$LANG['tracker']["mapping"][417]="réseaux > adresse MAC";
$LANG['tracker']["mapping"][418]="imprimante > numéro d'inventaire";
$LANG['tracker']["mapping"][419]="réseaux > numéro d'inventaire";
$LANG['tracker']["mapping"][420]="imprimante > fabricant";
$LANG['tracker']["mapping"][421]="réseaux > addresses IP";


$LANG['tracker']["mapping"][101]="";
$LANG['tracker']["mapping"][102]="";
$LANG['tracker']["mapping"][103]="";
$LANG['tracker']["mapping"][104]="MTU";
$LANG['tracker']["mapping"][105]="Vitesse";
$LANG['tracker']["mapping"][106]="Statut Interne";
$LANG['tracker']["mapping"][107]="Dernier changement";
$LANG['tracker']["mapping"][108]="Nb d'octets recus";
$LANG['tracker']["mapping"][109]="Nb d'octets envoyés";
$LANG['tracker']["mapping"][110]="Nb d'erreurs en entrée";
$LANG['tracker']["mapping"][111]="Nb d'erreurs en sortie";
$LANG['tracker']["mapping"][112]="Utilisation du CPU";
$LANG['tracker']["mapping"][113]="";
$LANG['tracker']["mapping"][114]="Connexion";
$LANG['tracker']["mapping"][115]="MAC interne";
$LANG['tracker']["mapping"][116]="Nom";
$LANG['tracker']["mapping"][117]="Modèle";
$LANG['tracker']["mapping"][118]="Type";
$LANG['tracker']["mapping"][119]="VLAN";
$LANG['tracker']["mapping"][128]="Nombre total de pages imprimées";
$LANG['tracker']["mapping"][129]="Nombre de pages noir et blanc imprimées";
$LANG['tracker']["mapping"][130]="Nombre de pages couleur imprimées";
$LANG['tracker']["mapping"][131]="Nombre de pages monochrome imprimées";
$LANG['tracker']["mapping"][132]="Nombre de pages bichromie imprimées";
$LANG['tracker']["mapping"][134]="Cartouche noir";
$LANG['tracker']["mapping"][135]="Cartouche noir photo";
$LANG['tracker']["mapping"][136]="Cartouche cyan";
$LANG['tracker']["mapping"][137]="Cartouche jaune";
$LANG['tracker']["mapping"][138]="Cartouche magenta";
$LANG['tracker']["mapping"][139]="Cartouche cyan clair";
$LANG['tracker']["mapping"][140]="Cartouche magenta clair";
$LANG['tracker']["mapping"][141]="Photoconducteur";
$LANG['tracker']["mapping"][142]="Photoconducteur noir";
$LANG['tracker']["mapping"][143]="Photoconducteur couleur";
$LANG['tracker']["mapping"][144]="Photoconducteur cyan";
$LANG['tracker']["mapping"][145]="Photoconducteur jaune";
$LANG['tracker']["mapping"][146]="Photoconducteur magenta";
$LANG['tracker']["mapping"][147]="Unité de transfert noir";
$LANG['tracker']["mapping"][148]="Unité de transfert cyan";
$LANG['tracker']["mapping"][149]="Unité de transfert jaune";
$LANG['tracker']["mapping"][150]="Unité de transfert magenta";
$LANG['tracker']["mapping"][151]="Bac récupérateur de déchet";
$LANG['tracker']["mapping"][152]="Four";
$LANG['tracker']["mapping"][153]="Module de nettoyage";
$LANG['tracker']["mapping"][154]="Nombre de pages recto/verso imprimées";
$LANG['tracker']["mapping"][155]="Nombre de pages scannées";
$LANG['tracker']["mapping"][156]="Kit de maintenance";

$LANG['tracker']["printer"][0]="pages";

$LANG['tracker']["menu"][0]="Découverte de matériel réseau";
$LANG['tracker']["menu"][1]="Gestion des agents";
$LANG['tracker']["menu"][2]="Plages IP";
$LANG['tracker']["menu"][3]="Menu";

$LANG['tracker']["buttons"][0]="Découvrir";

$LANG['tracker']["discovery"][0]="Plage d'ip à scanner";
$LANG['tracker']["discovery"][1]="Liste du matériel découvert";
$LANG['tracker']["discovery"][2]="² dans le script en automatique";
$LANG['tracker']["discovery"][3]="Découverte";
$LANG['tracker']["discovery"][4]="Numéros de série";
$LANG['tracker']["discovery"][5]="Nombre de matériels importés";
$LANG['tracker']["discovery"][6]="Critères d'existence";
$LANG['tracker']["discovery"][7]="Critères d'existence secondaires";
$LANG['tracker']["discovery"][8]="Si tous les critères d'existence se confrontent à des champs vides, vous pouvez sélectionner des critères secondaires.";

$LANG['tracker']["rangeip"][0]="Début de la plage IP";
$LANG['tracker']["rangeip"][1]="Fin de la plage IP";
$LANG['tracker']["rangeip"][2]="Plage IP";
$LANG['tracker']["rangeip"][3]="Interrogation";

$LANG['tracker']["agents"][0]="Agent SNMP";
$LANG['tracker']["agents"][2]="Threads interrogation (par coeur)";
$LANG['tracker']["agents"][3]="Threads découverte (par coeur)";
$LANG['tracker']["agents"][4]="Dernière remontée";
$LANG['tracker']["agents"][5]="Version de l'agent";
$LANG['tracker']["agents"][6]="Verrouillage";
$LANG['tracker']["agents"][7]="Export config agent";
$LANG['tracker']["agents"][8]="Fragments en Ko";
$LANG['tracker']["agents"][9]="Options avancées";
$LANG['tracker']["agents"][10]="Coeurs (CPU) interrogation";
$LANG['tracker']["agents"][11]="Coeurs (CPU) découverte";

?>