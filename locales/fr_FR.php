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
// Original Author of file: Nicolas SMOLYNIEC
// Purpose of file:
// ----------------------------------------------------------------------

$title="Tracker";
$version="0.1";

$LANGTRACKER["title"][0]="$title";
$LANGTRACKER["title"][1]="[Trk] Infos";
$LANGTRACKER["title"][2]="[Trk] Historique";
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


$LANGTRACKER["setup"][2]="Merci de vous placer sur l'entité racine (voir tous)";
$LANGTRACKER["setup"][3]="Configuration du plugin ".$title;
$LANGTRACKER["setup"][4]="Installer le plugin $title $version";
$LANGTRACKER["setup"][6]="Désinstaller le plugin $title $version";
$LANGTRACKER["setup"][8]="Attention, la désinstallation du plugin est irréversible.<br> Vous perdrez toutes les données.";
$LANGTRACKER["setup"][11]="Mode d'emploi";
$LANGTRACKER["setup"][12]="FAQ";


$LANGTRACKER["functionalities"][0]="Fonctionnalités";
$LANGTRACKER["functionalities"][1]="Ajout / Suppression de fonctionnalités";

$LANGTRACKER["functionalities"][10]="Connexions Ordinateurs";
$LANGTRACKER["functionalities"][11]="Contrôle du câblage";
$LANGTRACKER["functionalities"][12]="Vérification du commutateur réseau et du port de connexion.";
$LANGTRACKER["functionalities"][13]="Activation de l'historique";
$LANGTRACKER["functionalities"][14]="Mise à jour du champ contact";
$LANGTRACKER["functionalities"][15]="Mise à jour du champ utilisateur GLPI";

$LANGTRACKER["functionalities"][20]="Imprimantes";
$LANGTRACKER["functionalities"][21]="Relevé des compteurs Imprimantes";
$LANGTRACKER["functionalities"][22]="Valeur par défaut";
$LANGTRACKER["functionalities"][23]="Lorsque le relevé d'un compteur spécifique sera spécifié à défaut, c'est cette valeur qui sera prise en compte.";

$LANGTRACKER["functionalities"][30]="Epuration des historiques";
$LANGTRACKER["functionalities"][31]="Activation de l'épuration";
$LANGTRACKER["functionalities"][32]="Profondeur de nettoyage (en jours)";

$LANGTRACKER["functionalities"][40]="Configuration";
$LANGTRACKER["functionalities"][41]="Statut du matériel actif";
$LANGTRACKER["functionalities"][42]="Commutateur";


$LANGTRACKER["snmp"][0]="Informations SNMP du matériel";
$LANGTRACKER["snmp"][1]="Général";
$LANGTRACKER["snmp"][2]="Cablâge";
$LANGTRACKER["snmp"][2]="Données SNMP";

$LANGTRACKER["snmp"][11]="Informations supplémentaires";
$LANGTRACKER["snmp"][12]="Uptime";
$LANGTRACKER["snmp"][13]="Utilisation du CPU (en %)";
$LANGTRACKER["snmp"][14]="Utilisation de la m�moire";

$LANGTRACKER["snmp"][31]="Impossible de récupérer les infos SNMP : Ce n'est pas un commutateur !";
$LANGTRACKER["snmp"][32]="Impossible de récupérer les infos SNMP : Matériel non actif !";
$LANGTRACKER["snmp"][33]="Impossible de récupérer les infos SNMP : IP non précisée dans la base !";
$LANGTRACKER["snmp"][34]="Le commutateur auquel est reliée la machine n'est pas renseigné !";


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

$LANGTRACKER["model_info"][1]="Informations SNMP";
$LANGTRACKER["model_info"][2]="Version SNMP";
$LANGTRACKER["model_info"][3]="Authentification SNMP";
$LANGTRACKER["model_info"][4]="Modèles SNMP";
$LANGTRACKER["model_info"][5]="Gestion des MIB";
$LANGTRACKER["model_info"][6]="Edition de modèle SNMP";

$LANGTRACKER["mib"][1]="Label MIB";
$LANGTRACKER["mib"][2]="Objet";
$LANGTRACKER["mib"][3]="oid";
$LANGTRACKER["mib"][4]="Ajouter un oid...";
$LANGTRACKER["mib"][5]="Liste des oid";
$LANGTRACKER["mib"][6]="Compteur de ports";
$LANGTRACKER["mib"][7]="Port dynamique (.x)";
$LANGTRACKER["mib"][8]="Liaison champs";

$LANGTRACKER["processes"][0]="Informations sur l'exécution des scripts";
$LANGTRACKER["processes"][1]="PID";
$LANGTRACKER["processes"][2]="Statut";
$LANGTRACKER["processes"][3]="Nombre de threads";
$LANGTRACKER["processes"][4]="Date de début d'exécution";
$LANGTRACKER["processes"][5]="Date de fin d'exécution";
$LANGTRACKER["processes"][6]="Equipements réseau interrogés";
$LANGTRACKER["processes"][7]="Imprimantes interrogées";
$LANGTRACKER["processes"][8]="Ports interrogés";
$LANGTRACKER["processes"][9]="Erreurs";
$LANGTRACKER["processes"][10]="Durée d'exécution du script";
$LANGTRACKER["processes"][11]="Champs ajoutés";
$LANGTRACKER["processes"][12]="Erreurs d'oid";

?>