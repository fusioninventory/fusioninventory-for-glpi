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
   @comment   Not translate this file, use https://www.transifex.net/projects/p/FusionInventory/
   @copyright Copyright (c) 2010-2012 FusionInventory team
   @license   AGPL License 3.0 or (at your option) any later version
              http://www.gnu.org/licenses/agpl-3.0-standalone.html
   @link      http://www.fusioninventory.org/
   @link      http://forge.fusioninventory.org/projects/fusioninventory-for-glpi/
   @since     2010
 
   ------------------------------------------------------------------------
 */


$LANG['plugin_fusinvsnmp']['agents'][24]="Nombre de threads";
$LANG['plugin_fusinvsnmp']['agents'][25]="Agent(s)";
$LANG['plugin_fusinvsnmp']['agents'][26]="Version du module netdiscovery";
$LANG['plugin_fusinvsnmp']['agents'][27]="Version du module snmpquery";

$LANG['plugin_fusinvsnmp']['codetasklog'][1]="équipements inventoriés";
$LANG['plugin_fusinvsnmp']['codetasklog'][2]="équipements découverts";
$LANG['plugin_fusinvsnmp']['codetasklog'][3]="La base de définition des matériels SNMP de l'agent n'est plus à jour. Lors de sa prochaine exécution, il récupérera une nouvelle version sur le serveur.";
$LANG['plugin_fusinvsnmp']['codetasklog'][4]="Ajout de l'élément";
$LANG['plugin_fusinvsnmp']['codetasklog'][5]="Mise à jour de l'élément";
$LANG['plugin_fusinvsnmp']['codetasklog'][6]="L'inventaire a démarré";
$LANG['plugin_fusinvsnmp']['codetasklog'][7]="Détail";

$LANG['plugin_fusinvsnmp']['config'][10]="Types de ports à importer (pour les équipements réseau)";
$LANG['plugin_fusinvsnmp']['config'][3]="Inventaire réseau (SNMP)";
$LANG['plugin_fusinvsnmp']['config'][4]="Découverte réseau";
$LANG['plugin_fusinvsnmp']['config'][8]="Jamais";
$LANG['plugin_fusinvsnmp']['config'][9]="Toujours";

$LANG['plugin_fusinvsnmp']['constructdevice'][0]="Gestion des mib de matériel";
$LANG['plugin_fusinvsnmp']['constructdevice'][1]="Creation automatique des modèles";
$LANG['plugin_fusinvsnmp']['constructdevice'][2]="Générer le fichier de découverte";
$LANG['plugin_fusinvsnmp']['constructdevice'][3]="Supprimer modèles non utilisés";
$LANG['plugin_fusinvsnmp']['constructdevice'][4]="Exporter tous les modèles";
$LANG['plugin_fusinvsnmp']['constructdevice'][5]="Regénérer les commentaires de modèles";

$LANG['plugin_fusinvsnmp']['discovery'][5]="Nombre de matériels importés";
$LANG['plugin_fusinvsnmp']['discovery'][9]="Nombre de matériels non importés car type non défini";

$LANG['plugin_fusinvsnmp']['errors'][50]="La version de GLPI n'est pas compatible, vous avez besoin de la version 0.78";

$LANG['plugin_fusinvsnmp']['legend'][0]="Connexion avec un switch or un serveur en mode trunk ou taggé";
$LANG['plugin_fusinvsnmp']['legend'][1]="Connexion autre (avec un ordinateur, une imprimante...)";

$LANG['plugin_fusinvsnmp']['mapping'][104]="MTU";
$LANG['plugin_fusinvsnmp']['mapping'][105]="Vitesse";
$LANG['plugin_fusinvsnmp']['mapping'][106]="Statut Interne";
$LANG['plugin_fusinvsnmp']['mapping'][107]="Dernier changement";
$LANG['plugin_fusinvsnmp']['mapping'][108]="Nb d'octets recus";
$LANG['plugin_fusinvsnmp']['mapping'][109]="Nb d'octets envoyés";
$LANG['plugin_fusinvsnmp']['mapping'][10]="réseaux > port > nombre d'erreurs en entrée";
$LANG['plugin_fusinvsnmp']['mapping'][110]="Nb d'erreurs en entrée";
$LANG['plugin_fusinvsnmp']['mapping'][111]="Nb d'erreurs en sortie";
$LANG['plugin_fusinvsnmp']['mapping'][112]="Utilisation du CPU";
$LANG['plugin_fusinvsnmp']['mapping'][114]="Connexion";
$LANG['plugin_fusinvsnmp']['mapping'][115]="MAC interne";
$LANG['plugin_fusinvsnmp']['mapping'][116]="Nom";
$LANG['plugin_fusinvsnmp']['mapping'][117]="Modèle";
$LANG['plugin_fusinvsnmp']['mapping'][118]="Type";
$LANG['plugin_fusinvsnmp']['mapping'][119]="VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][11]="réseaux > port > nombre d'erreurs en sortie";
$LANG['plugin_fusinvsnmp']['mapping'][128]="Nombre total de pages imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][129]="Nombre de pages noir et blanc imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][12]="réseaux > utilisation du CPU";
$LANG['plugin_fusinvsnmp']['mapping'][130]="Nombre de pages couleur imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][131]="Nombre de pages monochrome imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][134]="Cartouche noir";
$LANG['plugin_fusinvsnmp']['mapping'][135]="Cartouche noir photo";
$LANG['plugin_fusinvsnmp']['mapping'][136]="Cartouche cyan";
$LANG['plugin_fusinvsnmp']['mapping'][137]="Cartouche jaune";
$LANG['plugin_fusinvsnmp']['mapping'][138]="Cartouche magenta";
$LANG['plugin_fusinvsnmp']['mapping'][139]="Cartouche cyan clair";
$LANG['plugin_fusinvsnmp']['mapping'][13]="réseaux > numéro de série";
$LANG['plugin_fusinvsnmp']['mapping'][140]="Cartouche magenta clair";
$LANG['plugin_fusinvsnmp']['mapping'][141]="Photoconducteur";
$LANG['plugin_fusinvsnmp']['mapping'][1423]="Nombre total de pages imprimées (impression)";
$LANG['plugin_fusinvsnmp']['mapping'][1424]="Nombre de pages noir et blanc imprimées (impression)";
$LANG['plugin_fusinvsnmp']['mapping'][1425]="Nombre de pages couleur imprimées (impression)";
$LANG['plugin_fusinvsnmp']['mapping'][1426]="Nombre total de pages imprimées (copie)";
$LANG['plugin_fusinvsnmp']['mapping'][1427]="Nombre de pages noir et blanc imprimées (copie)";
$LANG['plugin_fusinvsnmp']['mapping'][1428]="Nombre de pages couleur imprimées (copie)";
$LANG['plugin_fusinvsnmp']['mapping'][1429]="Nombre total de pages imprimées (fax)";
$LANG['plugin_fusinvsnmp']['mapping'][142]="Photoconducteur noir";
$LANG['plugin_fusinvsnmp']['mapping'][1434]="Nombre total de pages larges imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][143]="Photoconducteur couleur";
$LANG['plugin_fusinvsnmp']['mapping'][144]="Photoconducteur cyan";
$LANG['plugin_fusinvsnmp']['mapping'][145]="Photoconducteur jaune";
$LANG['plugin_fusinvsnmp']['mapping'][146]="Photoconducteur magenta";
$LANG['plugin_fusinvsnmp']['mapping'][147]="Unité de transfert noir";
$LANG['plugin_fusinvsnmp']['mapping'][148]="Unité de transfert cyan";
$LANG['plugin_fusinvsnmp']['mapping'][149]="Unité de transfert jaune";
$LANG['plugin_fusinvsnmp']['mapping'][14]="réseaux > port > statut de la connexion";
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
$LANG['plugin_fusinvsnmp']['mapping'][15]="réseaux > port > adresse MAC";
$LANG['plugin_fusinvsnmp']['mapping'][160]="Toner Jaune";
$LANG['plugin_fusinvsnmp']['mapping'][161]="Tambour Noir";
$LANG['plugin_fusinvsnmp']['mapping'][162]="Tambour Cyan";
$LANG['plugin_fusinvsnmp']['mapping'][163]="Tambour Magenta";
$LANG['plugin_fusinvsnmp']['mapping'][164]="Tambour Jaune";
$LANG['plugin_fusinvsnmp']['mapping'][165]="Informations diverses regroupées";
$LANG['plugin_fusinvsnmp']['mapping'][166]="Toner Noir 2";
$LANG['plugin_fusinvsnmp']['mapping'][167]="Toner Noir Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][168]="Toner Noir Restant";
$LANG['plugin_fusinvsnmp']['mapping'][169]="Toner Cyan Max";
$LANG['plugin_fusinvsnmp']['mapping'][16]="réseaux > port > nom";
$LANG['plugin_fusinvsnmp']['mapping'][170]="Toner Cyan Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][171]="Toner Cyan Restant";
$LANG['plugin_fusinvsnmp']['mapping'][172]="Toner Magenta Max";
$LANG['plugin_fusinvsnmp']['mapping'][173]="Toner Magenta Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][174]="Toner Magenta Restant";
$LANG['plugin_fusinvsnmp']['mapping'][175]="Toner Jaune Max";
$LANG['plugin_fusinvsnmp']['mapping'][176]="Toner Jaune Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][177]="Toner Jaune Restant";
$LANG['plugin_fusinvsnmp']['mapping'][178]="Tambour Noir Max";
$LANG['plugin_fusinvsnmp']['mapping'][179]="Tambour Noir Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][17]="réseaux > modèle";
$LANG['plugin_fusinvsnmp']['mapping'][180]="Tambour Noir Restant";
$LANG['plugin_fusinvsnmp']['mapping'][181]="Tambour Cyan Max";
$LANG['plugin_fusinvsnmp']['mapping'][182]="Tambour Cyan Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][183]="Tambour Cyan Restant";
$LANG['plugin_fusinvsnmp']['mapping'][184]="Tambour Magenta Max";
$LANG['plugin_fusinvsnmp']['mapping'][185]="Tambour Magenta Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][186]="Tambour Magenta Restant";
$LANG['plugin_fusinvsnmp']['mapping'][187]="Tambour Jaune Max";
$LANG['plugin_fusinvsnmp']['mapping'][188]="Tambour Jaune Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][189]="Tambour Jaune Restant";
$LANG['plugin_fusinvsnmp']['mapping'][18]="réseau > port > type";
$LANG['plugin_fusinvsnmp']['mapping'][190]="Bac récupérateur de déchet Max";
$LANG['plugin_fusinvsnmp']['mapping'][191]="Bac récupérateur de déchet Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][192]="Bac récupérateur de déchet Restant";
$LANG['plugin_fusinvsnmp']['mapping'][193]="Kit de maintenance Max";
$LANG['plugin_fusinvsnmp']['mapping'][194]="Kit de maintenance Utilisé";
$LANG['plugin_fusinvsnmp']['mapping'][195]="Kit de maintenance Restant";
$LANG['plugin_fusinvsnmp']['mapping'][196]="Cartouche grise";
$LANG['plugin_fusinvsnmp']['mapping'][19]="réseaux > VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][1]="reseaux > lieu";
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
$LANG['plugin_fusinvsnmp']['mapping'][2]="réseaux > firmware";
$LANG['plugin_fusinvsnmp']['mapping'][30]="imprimante > compteur > nombre de pages couleur imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][31]="imprimante > compteur > nombre de pages monochrome imprimées";
$LANG['plugin_fusinvsnmp']['mapping'][33]="réseaux > port > type de duplex";
$LANG['plugin_fusinvsnmp']['mapping'][34]="imprimante > consommables > cartouche noir (%)";
$LANG['plugin_fusinvsnmp']['mapping'][35]="imprimante > consommables > cartouche noir photo (%)";
$LANG['plugin_fusinvsnmp']['mapping'][36]="imprimante > consommables > cartouche cyan (%)";
$LANG['plugin_fusinvsnmp']['mapping'][37]="imprimante > consommables > cartouche jaune (%)";
$LANG['plugin_fusinvsnmp']['mapping'][38]="imprimante > consommables > cartouche magenta (%)";
$LANG['plugin_fusinvsnmp']['mapping'][39]="imprimante > consommables > cartouche cyan clair (%)";
$LANG['plugin_fusinvsnmp']['mapping'][3]="réseaux > uptime";
$LANG['plugin_fusinvsnmp']['mapping'][400]="imprimante > consommables > kit de maintenance (%)";
$LANG['plugin_fusinvsnmp']['mapping'][401]="réseaux > CPU user";
$LANG['plugin_fusinvsnmp']['mapping'][402]="réseaux > CPU système";
$LANG['plugin_fusinvsnmp']['mapping'][403]="réseaux > contact";
$LANG['plugin_fusinvsnmp']['mapping'][404]="réseaux > description";
$LANG['plugin_fusinvsnmp']['mapping'][405]="imprimante > contact";
$LANG['plugin_fusinvsnmp']['mapping'][406]="imprimante > description";
$LANG['plugin_fusinvsnmp']['mapping'][407]="imprimante > port > adresse IP";
$LANG['plugin_fusinvsnmp']['mapping'][408]="réseaux > port > numéro index";
$LANG['plugin_fusinvsnmp']['mapping'][409]="réseaux > Adresse CDP";
$LANG['plugin_fusinvsnmp']['mapping'][40]="imprimante > consommables > cartouche magenta clair (%)";
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
$LANG['plugin_fusinvsnmp']['mapping'][41]="imprimante > consommables > photoconducteur (%)";
$LANG['plugin_fusinvsnmp']['mapping'][420]="imprimante > fabricant";
$LANG['plugin_fusinvsnmp']['mapping'][421]="réseaux > addresses IP";
$LANG['plugin_fusinvsnmp']['mapping'][422]="réseaux > pvid (port vlan id)";
$LANG['plugin_fusinvsnmp']['mapping'][423]="imprimante > compteur > nombre total de pages imprimées (impression)";
$LANG['plugin_fusinvsnmp']['mapping'][424]="imprimante > compteur > nombre de pages noir et blanc imprimées (impression)";
$LANG['plugin_fusinvsnmp']['mapping'][425]="imprimante > compteur > nombre de pages couleur imprimées (impression)";
$LANG['plugin_fusinvsnmp']['mapping'][426]="imprimante > compteur > nombre total de pages imprimées (copie)";
$LANG['plugin_fusinvsnmp']['mapping'][427]="imprimante > compteur > nombre de pages noir et blanc imprimées (copie)";
$LANG['plugin_fusinvsnmp']['mapping'][428]="imprimante > compteur > nombre de pages couleur imprimées (copie)";
$LANG['plugin_fusinvsnmp']['mapping'][429]="imprimante > compteur > nombre total de pages imprimées (fax)";
$LANG['plugin_fusinvsnmp']['mapping'][42]="imprimante > consommables > photoconducteur noir (%)";
$LANG['plugin_fusinvsnmp']['mapping'][430]="réseaux > port > vlan";
$LANG['plugin_fusinvsnmp']['mapping'][435]="réseaux > CDP sysdescr distant";
$LANG['plugin_fusinvsnmp']['mapping'][436]="réseaux > CDP id distant";
$LANG['plugin_fusinvsnmp']['mapping'][437]="réseaux > CDP modèle du matériel distant";
$LANG['plugin_fusinvsnmp']['mapping'][438]="réseau > LLDP sysdescr distant";
$LANG['plugin_fusinvsnmp']['mapping'][439]="réseaux > LLDP id distant";
$LANG['plugin_fusinvsnmp']['mapping'][43]="imprimante > consommables > photoconducteur couleur (%)";
$LANG['plugin_fusinvsnmp']['mapping'][440]="réseaux > LLDP description du port distant";
$LANG['plugin_fusinvsnmp']['mapping'][44]="imprimante > consommables > photoconducteur cyan (%)";
$LANG['plugin_fusinvsnmp']['mapping'][45]="imprimante > consommables > photoconducteur jaune (%)";
$LANG['plugin_fusinvsnmp']['mapping'][46]="imprimante > consommables > photoconducteur magenta (%)";
$LANG['plugin_fusinvsnmp']['mapping'][47]="imprimante > consommables > unité de transfert noir (%)";
$LANG['plugin_fusinvsnmp']['mapping'][48]="imprimante > consommables > unité de transfert cyan (%)";
$LANG['plugin_fusinvsnmp']['mapping'][49]="imprimante > consommables > unité de transfert jaune (%)";
$LANG['plugin_fusinvsnmp']['mapping'][4]="réseaux > port > mtu";
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
$LANG['plugin_fusinvsnmp']['mapping'][5]="réseaux > port > vitesse";
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
$LANG['plugin_fusinvsnmp']['mapping'][6]="réseaux > port > statut interne";
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
$LANG['plugin_fusinvsnmp']['mapping'][7]="réseau > ports > dernier changement";
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
$LANG['plugin_fusinvsnmp']['mapping'][8]="réseaux > port > nombre d'octets en entrée";
$LANG['plugin_fusinvsnmp']['mapping'][90]="imprimante > consommables > unité de transfert magenta (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][91]="imprimante > consommables > bac récupérateur de déchet (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][92]="imprimante > consommables > bac récupérateur de déchet (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][93]="imprimante > consommables > four (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][94]="imprimante > consommables > four (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][95]="imprimante > consommables > module de nettoyage (encre max)";
$LANG['plugin_fusinvsnmp']['mapping'][96]="imprimante > consommables > module de nettoyage (encre restant)";
$LANG['plugin_fusinvsnmp']['mapping'][97]="imprimante > port > type";
$LANG['plugin_fusinvsnmp']['mapping'][98]="imprimante > consommables > kit de maintenance (max)";
$LANG['plugin_fusinvsnmp']['mapping'][99]="imprimante > consommables > kit de maintenance (restant)";
$LANG['plugin_fusinvsnmp']['mapping'][9]="réseaux > port > nombre d'octets en sortie";

$LANG['plugin_fusinvsnmp']['massiveaction'][1]="Assigner un modèle SNMP";
$LANG['plugin_fusinvsnmp']['massiveaction'][2]="Assigner une authentification SNMP";

$LANG['plugin_fusinvsnmp']['menu'][10]="Etat des inventaires réseaux";
$LANG['plugin_fusinvsnmp']['menu'][2]="Plages IP";
$LANG['plugin_fusinvsnmp']['menu'][5]="Historique des ports de switchs";
$LANG['plugin_fusinvsnmp']['menu'][6]="Ports de switchs inutilisés";
$LANG['plugin_fusinvsnmp']['menu'][9]="Etat des découvertes";

$LANG['plugin_fusinvsnmp']['mib'][1]="Label MIB";
$LANG['plugin_fusinvsnmp']['mib'][2]="Objet";
$LANG['plugin_fusinvsnmp']['mib'][3]="oid";
$LANG['plugin_fusinvsnmp']['mib'][4]="Ajouter un oid...";
$LANG['plugin_fusinvsnmp']['mib'][5]="Liste des oid";
$LANG['plugin_fusinvsnmp']['mib'][6]="Compteur de ports";
$LANG['plugin_fusinvsnmp']['mib'][7]="Port dynamique (.x)";
$LANG['plugin_fusinvsnmp']['mib'][8]="Liaison champs";

$LANG['plugin_fusinvsnmp']['model_info'][10]="Importation de modèle";
$LANG['plugin_fusinvsnmp']['model_info'][11]="is_active";
$LANG['plugin_fusinvsnmp']['model_info'][12]="Clé modèle pour la découverte";
$LANG['plugin_fusinvsnmp']['model_info'][13]="Charger le bon modèle";
$LANG['plugin_fusinvsnmp']['model_info'][14]="Charger le bon modèle SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][15]="Importation en masse de modèles";
$LANG['plugin_fusinvsnmp']['model_info'][16]="Import en masse des modèles dans le répertoire plugins/fusinvsnmp/models/";
$LANG['plugin_fusinvsnmp']['model_info'][2]="Version SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][3]="Authentification SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][4]="Modèles SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][6]="Edition de modèle SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][7]="Création de modèle SNMP";
$LANG['plugin_fusinvsnmp']['model_info'][9]="Import effectué avec succès";

$LANG['plugin_fusinvsnmp']['portlogs'][0]="Configuration de l'historique";
$LANG['plugin_fusinvsnmp']['portlogs'][1]="Liste des champs à historiser";
$LANG['plugin_fusinvsnmp']['portlogs'][2]="Rétention en jours";

$LANG['plugin_fusinvsnmp']['printhistory'][1]="Trop de données à afficher";

$LANG['plugin_fusinvsnmp']['processes'][37]="Total IP";

$LANG['plugin_fusinvsnmp']['profile'][2]="Configuration";
$LANG['plugin_fusinvsnmp']['profile'][4]="Plages IP";
$LANG['plugin_fusinvsnmp']['profile'][5]="SNMP des équipements réseaux";
$LANG['plugin_fusinvsnmp']['profile'][6]="SNMP des imprimantes";
$LANG['plugin_fusinvsnmp']['profile'][7]="Modèles SNMP";
$LANG['plugin_fusinvsnmp']['profile'][8]="Rapport imprimantes";
$LANG['plugin_fusinvsnmp']['profile'][9]="Rapport réseaux";

$LANG['plugin_fusinvsnmp']['prt_history'][0]="Historique et Statistiques des compteurs imprimante";
$LANG['plugin_fusinvsnmp']['prt_history'][12]="Pages imprimées totales";
$LANG['plugin_fusinvsnmp']['prt_history'][13]="Pages / jour";
$LANG['plugin_fusinvsnmp']['prt_history'][20]="Historique des compteurs imprimante";
$LANG['plugin_fusinvsnmp']['prt_history'][21]="Date";
$LANG['plugin_fusinvsnmp']['prt_history'][22]="Compteur";
$LANG['plugin_fusinvsnmp']['prt_history'][31]="Unité de temps";
$LANG['plugin_fusinvsnmp']['prt_history'][32]="Ajouter une imprimante";
$LANG['plugin_fusinvsnmp']['prt_history'][33]="Supprimer une imprimante";
$LANG['plugin_fusinvsnmp']['prt_history'][34]="jour";
$LANG['plugin_fusinvsnmp']['prt_history'][35]="semaine";
$LANG['plugin_fusinvsnmp']['prt_history'][36]="mois";
$LANG['plugin_fusinvsnmp']['prt_history'][37]="année";
$LANG['plugin_fusinvsnmp']['prt_history'][38]="Imprimantes à comparer";

$LANG['plugin_fusinvsnmp']['report'][0]="Nombre de jours depuis dernier inventaire";
$LANG['plugin_fusinvsnmp']['report'][1]="Compteurs d'impression";

$LANG['plugin_fusinvsnmp']['setup'][17]="Le plugin FusionInventory SNMP a besoin que le plugin FusionInventory soit activé pour être lui-même activé.";
$LANG['plugin_fusinvsnmp']['setup'][18]="Le plugin FusionInventory SNMP a besoin que le plugin FusionInventory soit activé pour être lui-même désinstallé.";
$LANG['plugin_fusinvsnmp']['setup'][19]="Conversion de l'historique des ports";
$LANG['plugin_fusinvsnmp']['setup'][20]="Déplacement de l'historique de création des connections";
$LANG['plugin_fusinvsnmp']['setup'][21]="Déplacement de l'historique de suppression des connections";

$LANG['plugin_fusinvsnmp']['snmp'][12]="Uptime";
$LANG['plugin_fusinvsnmp']['snmp'][13]="Utilisation du CPU (en %)";
$LANG['plugin_fusinvsnmp']['snmp'][14]="Utilisation de la mémoire (en %)";
$LANG['plugin_fusinvsnmp']['snmp'][40]="Tableau des ports";
$LANG['plugin_fusinvsnmp']['snmp'][41]="Description du port";
$LANG['plugin_fusinvsnmp']['snmp'][46]="Nb d'octets recus";
$LANG['plugin_fusinvsnmp']['snmp'][48]="Nb d'octets envoyés";
$LANG['plugin_fusinvsnmp']['snmp'][49]="Nb d'erreurs en réception";
$LANG['plugin_fusinvsnmp']['snmp'][4]="Sysdescr";
$LANG['plugin_fusinvsnmp']['snmp'][51]="Duplex";
$LANG['plugin_fusinvsnmp']['snmp'][53]="Dernier inventaire";
$LANG['plugin_fusinvsnmp']['snmp'][54]="Données non disponibles";
$LANG['plugin_fusinvsnmp']['snmp'][55]="Nombre par seconde";

$LANG['plugin_fusinvsnmp']['snmpauth'][1]="Communauté";
$LANG['plugin_fusinvsnmp']['snmpauth'][2]="Utilisateur";
$LANG['plugin_fusinvsnmp']['snmpauth'][4]="Protocole de cryptage pour authentification ";
$LANG['plugin_fusinvsnmp']['snmpauth'][5]="Mot de passe";
$LANG['plugin_fusinvsnmp']['snmpauth'][6]="Protocole de cryptage pour les données";

$LANG['plugin_fusinvsnmp']['state'][10]="Equipements importés";
$LANG['plugin_fusinvsnmp']['state'][4]="Date de début";
$LANG['plugin_fusinvsnmp']['state'][5]="Date de fin";
$LANG['plugin_fusinvsnmp']['state'][6]="Total de matériels découverts";
$LANG['plugin_fusinvsnmp']['state'][7]="Total en erreur";
$LANG['plugin_fusinvsnmp']['state'][8]="Equipements non importés";
$LANG['plugin_fusinvsnmp']['state'][9]="Equipements liés";

$LANG['plugin_fusinvsnmp']['stats'][0]="Compteur total";
$LANG['plugin_fusinvsnmp']['stats'][1]="pages par jour";
$LANG['plugin_fusinvsnmp']['stats'][2]="Affichage";

$LANG['plugin_fusinvsnmp']['task'][15]="Tâche permanente";
$LANG['plugin_fusinvsnmp']['task'][17]="Mode de communication";
$LANG['plugin_fusinvsnmp']['task'][18]="Créer la tâche automatiquement";

$LANG['plugin_fusinvsnmp']['title'][0]="FusionInventory SNMP";
$LANG['plugin_fusinvsnmp']['title'][1]="Informations SNMP";
$LANG['plugin_fusinvsnmp']['title'][2]="Historique de connexion";
$LANG['plugin_fusinvsnmp']['title'][5]="Historique SNMP";
$LANG['plugin_fusinvsnmp']['title'][6]="SNMP";
?>