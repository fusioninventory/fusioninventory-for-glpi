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
$version="2.0.2";

$LANGTRACKER["title"][0]="$title";
$LANGTRACKER["title"][1]="Informations SNMP";
$LANGTRACKER["title"][2]="Historique de connexion";
$LANGTRACKER["title"][3]="[Trk] Erreurs";
$LANGTRACKER["title"][4]="[Trk] Cron";

$LANGTRACKER["profile"][0]="Gestion des droits";
$LANGTRACKER["profile"][1]="$title"; //interface

$LANGTRACKER["profile"][10]="Listes des profils déjà configurés";
$LANGTRACKER["profile"][11]="Historique Ordinateurs";
$LANGTRACKER["profile"][12]="Historique Imprimantes";
$LANGTRACKER["profile"][13]="Infos Imprimantes";
$LANGTRACKER["profile"][14]="Infos Réseau";
$LANGTRACKER["profile"][15]="Erreurs courantes";

$LANGTRACKER["profile"][16]="SNMP Réseaux";
$LANGTRACKER["profile"][17]="SNMP Périphériques";
$LANGTRACKER["profile"][18]="SNMP Imprimantes";
$LANGTRACKER["profile"][19]="Modèles SNMP";
$LANGTRACKER["profile"][20]="Authentification SNMP";
$LANGTRACKER["profile"][21]="Infos scripts";
$LANGTRACKER["profile"][22]="Découverte réseau";
$LANGTRACKER["profile"][23]="Configuration générale";
$LANGTRACKER["profile"][24]="Modèle SNMP";
$LANGTRACKER["profile"][25]="Plages IP";
$LANGTRACKER["profile"][26]="Agents";
$LANGTRACKER["profile"][27]="Infos agent";
$LANGTRACKER["profile"][28]="Rapport";

$LANGTRACKER["setup"][2]="Merci de vous placer sur l'entité racine (voir tous)";
$LANGTRACKER["setup"][3]="Configuration du plugin ".$title;
$LANGTRACKER["setup"][4]="Installer le plugin $title $version";
$LANGTRACKER["setup"][5]="Mettre à jour le plugin $title vers la version $version";
$LANGTRACKER["setup"][6]="Désinstaller le plugin $title $version";
$LANGTRACKER["setup"][8]="Attention, la désinstallation du plugin est irréversible.<br> Vous perdrez toutes les données.";
$LANGTRACKER["setup"][11]="Mode d'emploi";
$LANGTRACKER["setup"][12]="FAQ";
$LANGTRACKER["setup"][13]="Vérification des modules PHP nécessaires";
$LANGTRACKER["setup"][14]="L'extension snmp de PHP n'est pas chargée";
$LANGTRACKER["setup"][15]="L'extension runkit de PHP/PECL n'est pas chargée";

$LANGTRACKER["functionalities"][0]="Fonctionnalités";
$LANGTRACKER["functionalities"][1]="Ajout / Suppression de fonctionnalités";
$LANGTRACKER["functionalities"][2]="Configuration générale";
$LANGTRACKER["functionalities"][3]="SNMP";
$LANGTRACKER["functionalities"][4]="Connexion";
$LANGTRACKER["functionalities"][5]="Script serveur";

$LANGTRACKER["functionalities"][10]="Activation de l'historique";
$LANGTRACKER["functionalities"][11]="Activation du module connexion";
$LANGTRACKER["functionalities"][12]="Activation du module SNMP réseaux";
$LANGTRACKER["functionalities"][13]="Activation du module SNMP périphériques";
$LANGTRACKER["functionalities"][14]="Activation du module SNMP téléphones";
$LANGTRACKER["functionalities"][15]="Activation du module SNMP imprimantes";
$LANGTRACKER["functionalities"][16]="Stockage de l'authentification SNMP";
$LANGTRACKER["functionalities"][17]="Base de données";
$LANGTRACKER["functionalities"][18]="Fichiers";
$LANGTRACKER["functionalities"][19]="Veuillez configurer le stockage de l'authentification SNMP dans la configuration du plugin";
$LANGTRACKER["functionalities"][20]="Statut du matériel actif";
$LANGTRACKER["functionalities"][21]="Rétention de l'historique d'interconnexions entre matériels en jours (0 = infini)";
$LANGTRACKER["functionalities"][22]="Rétention de l'historique de changement d'état des ports (0 = infini)";
$LANGTRACKER["functionalities"][23]="Rétention de l'historique des adresses MAC inconnues (0 = infini)";
$LANGTRACKER["functionalities"][24]="Rétention de l'historique des erreurs SNMP (0 = infini)";
$LANGTRACKER["functionalities"][25]="Rétention de l'historique des processes des scripts (0 = infini)";
$LANGTRACKER["functionalities"][26]="URL de GLPI pour l'agent";
$LANGTRACKER["functionalities"][27]="SSL seulement pour l'agent";

$LANGTRACKER["functionalities"][30]="Statut du matériel actif";
$LANGTRACKER["functionalities"][31]="Gestion des cartouches et du stock";
$LANGTRACKER["functionalities"][36]="Fréquence de relevé des compteurs";

$LANGTRACKER["functionalities"][40]="Configuration";
$LANGTRACKER["functionalities"][41]="Statut du matériel actif";
$LANGTRACKER["functionalities"][42]="Commutateur";
$LANGTRACKER["functionalities"][43]="Authentification SNMP";

$LANGTRACKER["functionalities"][50]="Nombre de process simultanés pour la découverte réseau";
$LANGTRACKER["functionalities"][51]="Nombre de process simultanés pour l'interrogation SNMP";
$LANGTRACKER["functionalities"][52]="Activation des journaux";
$LANGTRACKER["functionalities"][53]="Nombre de process simultanés pour le script serveur de post-traitement";

$LANGTRACKER["snmp"][0]="Informations SNMP du matériel";
$LANGTRACKER["snmp"][1]="Général";
$LANGTRACKER["snmp"][2]="Cablâge";
$LANGTRACKER["snmp"][2]="Données SNMP";

$LANGTRACKER["snmp"][11]="Informations supplémentaires";
$LANGTRACKER["snmp"][12]="Uptime";
$LANGTRACKER["snmp"][13]="Utilisation du CPU (en %)";
$LANGTRACKER["snmp"][14]="Utilisation de la mémoire (en %)";

$LANGTRACKER["snmp"][31]="Impossible de récupérer les infos SNMP : Ce n'est pas un commutateur !";
$LANGTRACKER["snmp"][32]="Impossible de récupérer les infos SNMP : Matériel non actif !";
$LANGTRACKER["snmp"][33]="Impossible de récupérer les infos SNMP : IP non précisée dans la base !";
$LANGTRACKER["snmp"][34]="Le commutateur auquel est reliée la machine n'est pas renseigné !";

$LANGTRACKER["snmp"][41]="";
$LANGTRACKER["snmp"][42]="MTU";
$LANGTRACKER["snmp"][43]="Vitesse";
$LANGTRACKER["snmp"][44]="Statut Interne";
$LANGTRACKER["snmp"][45]="Dernier changement";
$LANGTRACKER["snmp"][46]="Nb d'octets recus";
$LANGTRACKER["snmp"][47]="Nb d'erreurs en entrée";
$LANGTRACKER["snmp"][48]="Nb d'octets envoyés";
$LANGTRACKER["snmp"][49]="Nb d'erreurs en réception";
$LANGTRACKER["snmp"][50]="Connexion";
$LANGTRACKER["snmp"][51]="Duplex";
$LANGTRACKER["snmp"][52]="Date dernier inventaire TRACKER";
$LANGTRACKER["snmp"][53]="Dernier inventaire";

$LANGTRACKER["snmpauth"][1]="Communauté";
$LANGTRACKER["snmpauth"][2]="Utilisateur";
$LANGTRACKER["snmpauth"][3]="Schéma d'authentification";
$LANGTRACKER["snmpauth"][4]="Protocole de cryptage pour authentification ";
$LANGTRACKER["snmpauth"][5]="Mot de passe";
$LANGTRACKER["snmpauth"][6]="Protocole de cryptage pour les données (écriture)";
$LANGTRACKER["snmpauth"][7]="Mot de passe (écriture)";

$LANGTRACKER["cron"][0]="Relevé automatique du compteur";
$LANGTRACKER["cron"][1]="Activer le relevé";
$LANGTRACKER["cron"][2]="";
$LANGTRACKER["cron"][3]="Défaut";

$LANGTRACKER["errors"][0]="Erreurs";
$LANGTRACKER["errors"][1]="IP";
$LANGTRACKER["errors"][2]="Description";
$LANGTRACKER["errors"][3]="Date 1er pb";
$LANGTRACKER["errors"][4]="Date dernier pb";

$LANGTRACKER["errors"][10]="Incohérence avec la base GLPI";
$LANGTRACKER["errors"][11]="Poste inconnu";
$LANGTRACKER["errors"][12]="IP inconnue";

$LANGTRACKER["errors"][20]="Erreur SNMP";
$LANGTRACKER["errors"][21]="Impossible de récupérer les informations";

$LANGTRACKER["errors"][30]="Erreur Câblage";
$LANGTRACKER["errors"][31]="Problème de câblage";

$LANGTRACKER["errors"][101]="Timeout";
$LANGTRACKER["errors"][102]="Modele SNMP non assigné";
$LANGTRACKER["errors"][103]="Authentification SNMP non assigné";

$LANGTRACKER["history"][0] = "Ancienne";
$LANGTRACKER["history"][1] = "Nouvelle";
$LANGTRACKER["history"][2] = "Déconnexion";
$LANGTRACKER["history"][3] = "Connexion";

$LANGTRACKER["prt_history"][0]="Historique et Statistiques des compteurs imprimante";

$LANGTRACKER["prt_history"][10]="Statistiques des compteurs imprimante sur";
$LANGTRACKER["prt_history"][11]="jour(s)";
$LANGTRACKER["prt_history"][12]="Pages imprimées totales";
$LANGTRACKER["prt_history"][13]="Pages / jour";

$LANGTRACKER["prt_history"][20]="Historique des compteurs imprimante";
$LANGTRACKER["prt_history"][21]="Date";
$LANGTRACKER["prt_history"][22]="Compteur";


$LANGTRACKER["cpt_history"][0]="Historique des sessions";
$LANGTRACKER["cpt_history"][1]="Contact";
$LANGTRACKER["cpt_history"][2]="Ordinateur";
$LANGTRACKER["cpt_history"][3]="Utilisateur";
$LANGTRACKER["cpt_history"][4]="Etat";
$LANGTRACKER["cpt_history"][5]="Date";


$LANGTRACKER["type"][1]="Ordinateur";
$LANGTRACKER["type"][2]="Commutateur";
$LANGTRACKER["type"][3]="Imprimante";

$LANGTRACKER["rules"][1]="Règles";

$LANGTRACKER["massiveaction"][1]="Assigner un modèle SNMP";
$LANGTRACKER["massiveaction"][2]="Assigner une authentification SNMP";

$LANGTRACKER["model_info"][1]="Informations SNMP";
$LANGTRACKER["model_info"][2]="Version SNMP";
$LANGTRACKER["model_info"][3]="Authentification SNMP";
$LANGTRACKER["model_info"][4]="Modèles SNMP";
$LANGTRACKER["model_info"][5]="Gestion des MIB";
$LANGTRACKER["model_info"][6]="Edition de modèle SNMP";
$LANGTRACKER["model_info"][7]="Création de modèle SNMP";
$LANGTRACKER["model_info"][8]="Modèle déjà existant : import non effectué";
$LANGTRACKER["model_info"][9]="Import effectué avec succès";
$LANGTRACKER["model_info"][10]="Importation de modèle";
$LANGTRACKER["model_info"][11]="Activation";
$LANGTRACKER["model_info"][12]="Clé modèle pour la découverte";

$LANGTRACKER["mib"][1]="Label MIB";
$LANGTRACKER["mib"][2]="Objet";
$LANGTRACKER["mib"][3]="oid";
$LANGTRACKER["mib"][4]="Ajouter un oid...";
$LANGTRACKER["mib"][5]="Liste des oid";
$LANGTRACKER["mib"][6]="Compteur de ports";
$LANGTRACKER["mib"][7]="Port dynamique (.x)";
$LANGTRACKER["mib"][8]="Liaison champs";
$LANGTRACKER["mib"][9]="vlan";

$LANGTRACKER["processes"][0]="Informations sur l'exécution du script serveur";
$LANGTRACKER["processes"][1]="PID";
$LANGTRACKER["processes"][2]="Statut";
$LANGTRACKER["processes"][3]="Nombre de process";
$LANGTRACKER["processes"][4]="Date de début d'exécution";
$LANGTRACKER["processes"][5]="Date de fin d'exécution";
$LANGTRACKER["processes"][6]="Equipements réseau traités";
$LANGTRACKER["processes"][7]="Imprimantes traitées";
$LANGTRACKER["processes"][8]="Ports réseau traités";
$LANGTRACKER["processes"][9]="Erreurs";
$LANGTRACKER["processes"][10]="Durée totale d'exécution du script";
$LANGTRACKER["processes"][11]="Champs ajoutés";
$LANGTRACKER["processes"][12]="Erreurs SNMP";
$LANGTRACKER["processes"][13]="MAC inconnues";
$LANGTRACKER["processes"][14]="Liste des adresse MAC inconnues";
$LANGTRACKER["processes"][15]="Premier PID";
$LANGTRACKER["processes"][16]="Dernier PID";
$LANGTRACKER["processes"][17]="Date de la première détection";
$LANGTRACKER["processes"][18]="Date de la dernière détection";
$LANGTRACKER["processes"][19]="Informations sur l'exécution des agents";
$LANGTRACKER["processes"][20]="Rapports / statistiques";
$LANGTRACKER["processes"][21]="Equipements interrogés";
$LANGTRACKER["processes"][22]="Erreurs";
$LANGTRACKER["processes"][23]="Durée totale de la découverte";
$LANGTRACKER["processes"][24]="Durée totale de l'interrogation";

$LANGTRACKER["state"][0]="Démarrage de l'ordinateur";
$LANGTRACKER["state"][1]="Arrêt de l'ordinateur";
$LANGTRACKER["state"][2]="Connexion de l'utilisateur";
$LANGTRACKER["state"][3]="Déconnexion de l'utilisateur";


$LANGTRACKER["mapping"][1]="reseaux > lieu";
$LANGTRACKER["mapping"][2]="réseaux > firmware";
$LANGTRACKER["mapping"][3]="réseaux > uptime";
$LANGTRACKER["mapping"][4]="réseaux > port > mtu";
$LANGTRACKER["mapping"][5]="réseaux > port > vitesse";
$LANGTRACKER["mapping"][6]="réseaux > port > statut interne";
$LANGTRACKER["mapping"][7]="réseaux > port > Dernier changement";
$LANGTRACKER["mapping"][8]="réseaux > port > nombre d'octets entrés";
$LANGTRACKER["mapping"][9]="réseaux > port > nombre d'octets sortis";
$LANGTRACKER["mapping"][10]="réseaux > port > nombre d'erreurs entrées";
$LANGTRACKER["mapping"][11]="réseaux > port > nombre d'erreurs sorties";
$LANGTRACKER["mapping"][12]="réseaux > utilisation du CPU";
$LANGTRACKER["mapping"][13]="réseaux > numéro de série";
$LANGTRACKER["mapping"][14]="réseaux > port > statut de la connexion";
$LANGTRACKER["mapping"][15]="réseaux > port > adresse MAC";
$LANGTRACKER["mapping"][16]="réseaux > port > nom";
$LANGTRACKER["mapping"][17]="réseaux > modèle";
$LANGTRACKER["mapping"][18]="réseaux > port > type";
$LANGTRACKER["mapping"][19]="réseaux > VLAN";
$LANGTRACKER["mapping"][20]="réseaux > nom";
$LANGTRACKER["mapping"][21]="réseaux > mémoire totale";
$LANGTRACKER["mapping"][22]="réseaux > mémoire libre";
$LANGTRACKER["mapping"][23]="réseaux > port > description du port";
$LANGTRACKER["mapping"][24]="imprimante > nom";
$LANGTRACKER["mapping"][25]="imprimante > modèle";
$LANGTRACKER["mapping"][26]="imprimante > mémoire totale";
$LANGTRACKER["mapping"][27]="imprimante > numéro de série";
$LANGTRACKER["mapping"][28]="imprimante > compteur > nombre total de pages imprimées";
$LANGTRACKER["mapping"][29]="imprimante > compteur > nombre de pages noir et blanc imprimées";
$LANGTRACKER["mapping"][30]="imprimante > compteur > nombre de pages couleur imprimées";
$LANGTRACKER["mapping"][31]="imprimante > compteur > nombre de pages monochrome imprimées";
$LANGTRACKER["mapping"][32]="imprimante > compteur > nombre de pages bichromie imprimées";
$LANGTRACKER["mapping"][33]="réseaux > port > type de duplex";
$LANGTRACKER["mapping"][34]="imprimante > consommables > cartouche noir (%)";
$LANGTRACKER["mapping"][35]="imprimante > consommables > cartouche noir photo (%)";
$LANGTRACKER["mapping"][36]="imprimante > consommables > cartouche cyan (%)";
$LANGTRACKER["mapping"][37]="imprimante > consommables > cartouche jaune (%)";
$LANGTRACKER["mapping"][38]="imprimante > consommables > cartouche magenta (%)";
$LANGTRACKER["mapping"][39]="imprimante > consommables > cartouche cyan clair (%)";
$LANGTRACKER["mapping"][40]="imprimante > consommables > cartouche magenta clair (%)";
$LANGTRACKER["mapping"][41]="imprimante > consommables > photoconducteur (%)";
$LANGTRACKER["mapping"][42]="imprimante > consommables > photoconducteur noir (%)";
$LANGTRACKER["mapping"][43]="imprimante > consommables > photoconducteur couleur (%)";
$LANGTRACKER["mapping"][44]="imprimante > consommables > photoconducteur cyan (%)";
$LANGTRACKER["mapping"][45]="imprimante > consommables > photoconducteur jaune (%)";
$LANGTRACKER["mapping"][46]="imprimante > consommables > photoconducteur magenta (%)";
$LANGTRACKER["mapping"][47]="imprimante > consommables > unité de transfert noir (%)";
$LANGTRACKER["mapping"][48]="imprimante > consommables > unité de transfert cyan (%)";
$LANGTRACKER["mapping"][49]="imprimante > consommables > unité de transfert jaune (%)";
$LANGTRACKER["mapping"][50]="imprimante > consommables > unité de transfert magenta (%)";
$LANGTRACKER["mapping"][51]="imprimante > consommables > bac récupérateur de déchet (%)";
$LANGTRACKER["mapping"][52]="imprimante > consommables > four (%)";
$LANGTRACKER["mapping"][53]="imprimante > consommables > module de nettoyage (%)";
$LANGTRACKER["mapping"][54]="imprimante > compteur > nombre de pages recto/verso imprimées";
$LANGTRACKER["mapping"][55]="imprimante > compteur > nombre de pages scannées";
$LANGTRACKER["mapping"][56]="imprimante > lieu";
$LANGTRACKER["mapping"][57]="imprimante > port > nom";
$LANGTRACKER["mapping"][58]="imprimante > port > adresse MAC";
$LANGTRACKER["mapping"][59]="imprimante > consommables > cartouche noir (encre max)";
$LANGTRACKER["mapping"][60]="imprimante > consommables > cartouche noir (encre restant)";
$LANGTRACKER["mapping"][61]="imprimante > consommables > cartouche cyan (encre max)";
$LANGTRACKER["mapping"][62]="imprimante > consommables > cartouche cyan (encre restant)";
$LANGTRACKER["mapping"][63]="imprimante > consommables > cartouche jaune (encre max)";
$LANGTRACKER["mapping"][64]="imprimante > consommables > cartouche jaune (encre restant)";
$LANGTRACKER["mapping"][65]="imprimante > consommables > cartouche magenta (encre max)";
$LANGTRACKER["mapping"][66]="imprimante > consommables > cartouche magenta (encre restant)";
$LANGTRACKER["mapping"][67]="imprimante > consommables > cartouche cyan clair (encre max)";
$LANGTRACKER["mapping"][68]="imprimante > consommables > cartouche cyan clair (encre restant)";
$LANGTRACKER["mapping"][69]="imprimante > consommables > cartouche magenta clair (encre max)";
$LANGTRACKER["mapping"][70]="imprimante > consommables > cartouche magenta clair (encre restant)";
$LANGTRACKER["mapping"][71]="imprimante > consommables > photoconducteur (encre max)";
$LANGTRACKER["mapping"][72]="imprimante > consommables > photoconducteur (encre restant)";
$LANGTRACKER["mapping"][73]="imprimante > consommables > photoconducteur noir (encre max)";
$LANGTRACKER["mapping"][74]="imprimante > consommables > photoconducteur noir (encre restant)";
$LANGTRACKER["mapping"][75]="imprimante > consommables > photoconducteur couleur (encre max)";
$LANGTRACKER["mapping"][76]="imprimante > consommables > photoconducteur couleur (encre restant)";
$LANGTRACKER["mapping"][77]="imprimante > consommables > photoconducteur cyan (encre max)";
$LANGTRACKER["mapping"][78]="imprimante > consommables > photoconducteur cyan (encre restant)";
$LANGTRACKER["mapping"][79]="imprimante > consommables > photoconducteur jaune (encre max)";
$LANGTRACKER["mapping"][80]="imprimante > consommables > photoconducteur jaune (encre restant)";
$LANGTRACKER["mapping"][81]="imprimante > consommables > photoconducteur magenta (encre max)";
$LANGTRACKER["mapping"][82]="imprimante > consommables > photoconducteur magenta (encre restant)";
$LANGTRACKER["mapping"][83]="imprimante > consommables > unité de transfert noir (encre max)";
$LANGTRACKER["mapping"][84]="imprimante > consommables > unité de transfert noir (encre restant)";
$LANGTRACKER["mapping"][85]="imprimante > consommables > unité de transfert cyan (encre max)";
$LANGTRACKER["mapping"][86]="imprimante > consommables > unité de transfert cyan (encre restant)";
$LANGTRACKER["mapping"][87]="imprimante > consommables > unité de transfert jaune (encre max)";
$LANGTRACKER["mapping"][88]="imprimante > consommables > unité de transfert jaune (encre restant)";
$LANGTRACKER["mapping"][89]="imprimante > consommables > unité de transfert magenta (encre max)";
$LANGTRACKER["mapping"][90]="imprimante > consommables > unité de transfert magenta (encre restant)";
$LANGTRACKER["mapping"][91]="imprimante > consommables > bac récupérateur de déchet (encre max)";
$LANGTRACKER["mapping"][92]="imprimante > consommables > bac récupérateur de déchet (encre restant)";
$LANGTRACKER["mapping"][93]="imprimante > consommables > four (encre max)";
$LANGTRACKER["mapping"][94]="imprimante > consommables > four (encre restant)";
$LANGTRACKER["mapping"][95]="imprimante > consommables > module de nettoyage (encre max)";
$LANGTRACKER["mapping"][96]="imprimante > consommables > module de nettoyage (encre restant)";
$LANGTRACKER["mapping"][97]="imprimante > port > type";
$LANGTRACKER["mapping"][98]="imprimante > consommables > Kit de maintenance (max)";
$LANGTRACKER["mapping"][99]="imprimante > consommables > Kit de maintenance (restant)";
$LANGTRACKER["mapping"][400]="imprimante > consommables > Kit de maintenance (%)";
$LANGTRACKER["mapping"][401]="réseaux > CPU user";
$LANGTRACKER["mapping"][402]="réseaux > CPU système";
$LANGTRACKER["mapping"][403]="réseaux > contact";
$LANGTRACKER["mapping"][404]="réseaux > description";
$LANGTRACKER["mapping"][405]="imprimante > contact";
$LANGTRACKER["mapping"][406]="imprimante > description";
$LANGTRACKER["mapping"][407]="imprimante > port > adresse IP";
$LANGTRACKER["mapping"][408]="réseaux > port > numéro index";
$LANGTRACKER["mapping"][409]="réseaux > Adresse CDP";
$LANGTRACKER["mapping"][410]="réseaux > port CDP";
$LANGTRACKER["mapping"][411]="réseaux > statut port Trunk";
$LANGTRACKER["mapping"][412]="réseaux > Adresses mac filtrées (dot1dTpFdbAddress)";
$LANGTRACKER["mapping"][413]="réseaux > adresses physiques mémorisées (ipNetToMediaPhysAddress)";
$LANGTRACKER["mapping"][414]="réseaux > instances de ports (dot1dTpFdbPort)";
$LANGTRACKER["mapping"][415]="réseaux > numéro de ports associé ID du port (dot1dBasePortIfIndex)";
$LANGTRACKER["mapping"][416]="imprimante > port > numéro index";
$LANGTRACKER["mapping"][417]="réseaux > adresse MAC";
$LANGTRACKER["mapping"][418]="imprimante > numéro d'inventaire";
$LANGTRACKER["mapping"][419]="réseaux > numéro d'inventaire";
$LANGTRACKER["mapping"][420]="imprimante > fabricant";
$LANGTRACKER["mapping"][421]="réseaux > addresses IP";


$LANGTRACKER["mapping"][101]="";
$LANGTRACKER["mapping"][102]="";
$LANGTRACKER["mapping"][103]="";
$LANGTRACKER["mapping"][104]="MTU";
$LANGTRACKER["mapping"][105]="Vitesse";
$LANGTRACKER["mapping"][106]="Statut Interne";
$LANGTRACKER["mapping"][107]="Dernier changement";
$LANGTRACKER["mapping"][108]="Nb d'octets recus";
$LANGTRACKER["mapping"][109]="Nb d'octets envoyés";
$LANGTRACKER["mapping"][110]="Nb d'erreurs en entrée";
$LANGTRACKER["mapping"][111]="Nb d'erreurs en sortie";
$LANGTRACKER["mapping"][112]="Utilisation du CPU";
$LANGTRACKER["mapping"][113]="";
$LANGTRACKER["mapping"][114]="Connexion";
$LANGTRACKER["mapping"][115]="MAC interne";
$LANGTRACKER["mapping"][116]="Nom";
$LANGTRACKER["mapping"][117]="Modèle";
$LANGTRACKER["mapping"][118]="Type";
$LANGTRACKER["mapping"][119]="VLAN";
$LANGTRACKER["mapping"][128]="Nombre total de pages imprimées";
$LANGTRACKER["mapping"][129]="Nombre de pages noir et blanc imprimées";
$LANGTRACKER["mapping"][130]="Nombre de pages couleur imprimées";
$LANGTRACKER["mapping"][131]="Nombre de pages monochrome imprimées";
$LANGTRACKER["mapping"][132]="Nombre de pages bichromie imprimées";
$LANGTRACKER["mapping"][134]="Cartouche noir";
$LANGTRACKER["mapping"][135]="Cartouche noir photo";
$LANGTRACKER["mapping"][136]="Cartouche cyan";
$LANGTRACKER["mapping"][137]="Cartouche jaune";
$LANGTRACKER["mapping"][138]="Cartouche magenta";
$LANGTRACKER["mapping"][139]="Cartouche cyan clair";
$LANGTRACKER["mapping"][140]="Cartouche magenta clair";
$LANGTRACKER["mapping"][141]="Photoconducteur";
$LANGTRACKER["mapping"][142]="Photoconducteur noir";
$LANGTRACKER["mapping"][143]="Photoconducteur couleur";
$LANGTRACKER["mapping"][144]="Photoconducteur cyan";
$LANGTRACKER["mapping"][145]="Photoconducteur jaune";
$LANGTRACKER["mapping"][146]="Photoconducteur magenta";
$LANGTRACKER["mapping"][147]="Unité de transfert noir";
$LANGTRACKER["mapping"][148]="Unité de transfert cyan";
$LANGTRACKER["mapping"][149]="Unité de transfert jaune";
$LANGTRACKER["mapping"][150]="Unité de transfert magenta";
$LANGTRACKER["mapping"][151]="Bac récupérateur de déchet";
$LANGTRACKER["mapping"][152]="Four";
$LANGTRACKER["mapping"][153]="Module de nettoyage";
$LANGTRACKER["mapping"][154]="Nombre de pages recto/verso imprimées";
$LANGTRACKER["mapping"][155]="Nombre de pages scannées";
$LANGTRACKER["mapping"][156]="Kit de maintenance";

$LANGTRACKER["printer"][0]="pages";

$LANGTRACKER["menu"][0]="Découverte de matériel réseau";
$LANGTRACKER["menu"][1]="Gestion des agents";
$LANGTRACKER["menu"][2]="Plages IP";
$LANGTRACKER["menu"][3]="Menu";

$LANGTRACKER["buttons"][0]="Découvrir";

$LANGTRACKER["discovery"][0]="Plage d'ip à scanner";
$LANGTRACKER["discovery"][1]="Liste du matériel découvert";
$LANGTRACKER["discovery"][2]="² dans le script en automatique";
$LANGTRACKER["discovery"][3]="Découverte";
$LANGTRACKER["discovery"][4]="Numéros de série";
$LANGTRACKER["discovery"][5]="Nombre de matériels importés";
$LANGTRACKER["discovery"][6]="Critères d'existence";
$LANGTRACKER["discovery"][7]="Critères d'existence secondaires";
$LANGTRACKER["discovery"][8]="Si tous les critères d'existence se confrontent à des champs vides, vous pouvez sélectionner des critères secondaires.";

$LANGTRACKER["rangeip"][0]="Début de la plage IP";
$LANGTRACKER["rangeip"][1]="Fin de la plage IP";
$LANGTRACKER["rangeip"][2]="Plage IP";
$LANGTRACKER["rangeip"][3]="Interrogation";

$LANGTRACKER["agents"][0]="Agent SNMP";
$LANGTRACKER["agents"][2]="Threads interrogation (par coeur)";
$LANGTRACKER["agents"][3]="Threads découverte (par coeur)";
$LANGTRACKER["agents"][4]="Dernière remontée";
$LANGTRACKER["agents"][5]="Version de l'agent";
$LANGTRACKER["agents"][6]="Verrouillage";
$LANGTRACKER["agents"][7]="Export config agent";
$LANGTRACKER["agents"][8]="Fragments en Ko";
$LANGTRACKER["agents"][9]="Options avancées";
$LANGTRACKER["agents"][10]="Coeurs (CPU) interrogation";
$LANGTRACKER["agents"][11]="Coeurs (CPU) découverte";

?>