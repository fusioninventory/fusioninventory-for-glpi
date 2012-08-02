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


$LANG['plugin_fusinvsnmp']['agents'][24]="SNMP-Threads";
$LANG['plugin_fusinvsnmp']['agents'][25]="Agent(en)";
$LANG['plugin_fusinvsnmp']['agents'][26]="Netdiscovery Modul-Version";
$LANG['plugin_fusinvsnmp']['agents'][27]="SNMPQuery Modul-Version";

$LANG['plugin_fusinvsnmp']['codetasklog'][1]="inventarisierte / abgefragte Geräte";
$LANG['plugin_fusinvsnmp']['codetasklog'][2]="entdeckte Gerät";
$LANG['plugin_fusinvsnmp']['codetasklog'][3]="Die SNMP Gerätedefinition am Agenten ist nicht aktuell. Für den nächsten Lauf, wird er eine neue Version vom Server erhalten.";
$LANG['plugin_fusinvsnmp']['codetasklog'][4]="Objekt hinzufügen";
$LANG['plugin_fusinvsnmp']['codetasklog'][5]="Objekt aktualisieren";
$LANG['plugin_fusinvsnmp']['codetasklog'][6]="Inventarisierung gestartet";
$LANG['plugin_fusinvsnmp']['codetasklog'][7]="Detail";

$LANG['plugin_fusinvsnmp']['config'][10]="Port Typen zum Import (für Netzwerk Equipment)";
$LANG['plugin_fusinvsnmp']['config'][3]="Netzwerk-Inventarisierung (SNMP)";
$LANG['plugin_fusinvsnmp']['config'][4]="Netzwerkscan";
$LANG['plugin_fusinvsnmp']['config'][8]="Nie";
$LANG['plugin_fusinvsnmp']['config'][9]="Immer";

$LANG['plugin_fusinvsnmp']['constructdevice'][0]="Geräte-MIBs verwalten";
$LANG['plugin_fusinvsnmp']['constructdevice'][1]="Modell automatisch erstellen";
$LANG['plugin_fusinvsnmp']['constructdevice'][2]="Entdeckungsdatei generieren";
$LANG['plugin_fusinvsnmp']['constructdevice'][3]="Unbenutzte Modelle löschen";
$LANG['plugin_fusinvsnmp']['constructdevice'][4]="Alle Modelle exportieren";
$LANG['plugin_fusinvsnmp']['constructdevice'][5]="Kommentar zu Modellen erneut erstellen";

$LANG['plugin_fusinvsnmp']['discovery'][5]="Anzahl importierter Geräte";
$LANG['plugin_fusinvsnmp']['discovery'][9]="Number of devices not imported because type non defined";

$LANG['plugin_fusinvsnmp']['errors'][50]="Version von GLPI ist nicht kompatibel, benötige Version 0.78";

$LANG['plugin_fusinvsnmp']['legend'][0]="Verbindung mit einem Switch oder Server der im trunking oder tagged-Modus arbeitet";
$LANG['plugin_fusinvsnmp']['legend'][1]="Andere Verbindunge (mit Computer, Drucker...)";

$LANG['plugin_fusinvsnmp']['mapping'][104]="MTU";
$LANG['plugin_fusinvsnmp']['mapping'][105]="Geschwindigkeit";
$LANG['plugin_fusinvsnmp']['mapping'][106]="Interer Status";
$LANG['plugin_fusinvsnmp']['mapping'][107]="Letze &Auml";
$LANG['plugin_fusinvsnmp']['mapping'][108]="Anzahl empfangener Bytes";
$LANG['plugin_fusinvsnmp']['mapping'][109]="Anzahl ausgehender Bytes";
$LANG['plugin_fusinvsnmp']['mapping'][10]="Netzwerk > Port > Anzahl Fehler eingehend";
$LANG['plugin_fusinvsnmp']['mapping'][110]="Anzahl input Fehler";
$LANG['plugin_fusinvsnmp']['mapping'][111]="Anzahl output Fehler";
$LANG['plugin_fusinvsnmp']['mapping'][112]="CPU-Auslastung";
$LANG['plugin_fusinvsnmp']['mapping'][114]="Verbindung";
$LANG['plugin_fusinvsnmp']['mapping'][115]="Interne MAC Adresse";
$LANG['plugin_fusinvsnmp']['mapping'][116]="Name";
$LANG['plugin_fusinvsnmp']['mapping'][117]="Model";
$LANG['plugin_fusinvsnmp']['mapping'][118]="Typ";
$LANG['plugin_fusinvsnmp']['mapping'][119]="VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][11]="Netzwerk > Port > Anzahl Fehler ausgehend";
$LANG['plugin_fusinvsnmp']['mapping'][128]="Total gedruckte Seiten";
$LANG['plugin_fusinvsnmp']['mapping'][129]="Anzahl gedruckter Seiten (S/W)";
$LANG['plugin_fusinvsnmp']['mapping'][12]="Netzwerk > CPU-Auslastung";
$LANG['plugin_fusinvsnmp']['mapping'][130]="Anzahl gedruckter Seiten (Farbe)";
$LANG['plugin_fusinvsnmp']['mapping'][131]="Anzahl gedruckter Seiten (Graustufe)";
$LANG['plugin_fusinvsnmp']['mapping'][134]="Kartusche Schwarz";
$LANG['plugin_fusinvsnmp']['mapping'][135]="Kartusche Photoschwarz";
$LANG['plugin_fusinvsnmp']['mapping'][136]="Kartusche Cyan";
$LANG['plugin_fusinvsnmp']['mapping'][137]="Kartusche Yellow";
$LANG['plugin_fusinvsnmp']['mapping'][138]="Kartusche Magenta";
$LANG['plugin_fusinvsnmp']['mapping'][139]="Kartusche Leichtes Cyan";
$LANG['plugin_fusinvsnmp']['mapping'][13]="Netzwerk > Seriennummer";
$LANG['plugin_fusinvsnmp']['mapping'][140]="Kartusche Leichtes Magenta Kartusche";
$LANG['plugin_fusinvsnmp']['mapping'][141]="Photoleiter";
$LANG['plugin_fusinvsnmp']['mapping'][1423]="Total gedruckte Seiten";
$LANG['plugin_fusinvsnmp']['mapping'][1424]="Total gedruckte Seiten (S/W)";
$LANG['plugin_fusinvsnmp']['mapping'][1425]="Total gedruckte Seiten (Farbe)";
$LANG['plugin_fusinvsnmp']['mapping'][1426]="Total gedruckte Kopien";
$LANG['plugin_fusinvsnmp']['mapping'][1427]="Total gedruckte Kopien (S/W)";
$LANG['plugin_fusinvsnmp']['mapping'][1428]="Total gedruckte Kopien (Farbe)";
$LANG['plugin_fusinvsnmp']['mapping'][1429]="Total gedruckte Faxe";
$LANG['plugin_fusinvsnmp']['mapping'][142]="Photoleiter Schwarz ";
$LANG['plugin_fusinvsnmp']['mapping'][1434]="Total gedruckte Seiten (Grossformat)";
$LANG['plugin_fusinvsnmp']['mapping'][143]="Photoleiter Farbe";
$LANG['plugin_fusinvsnmp']['mapping'][144]="Photoleiter Cyan";
$LANG['plugin_fusinvsnmp']['mapping'][145]="Photoleiter Gelb";
$LANG['plugin_fusinvsnmp']['mapping'][146]="Photoleiter Magenta";
$LANG['plugin_fusinvsnmp']['mapping'][147]="Fixiereinheit Schwarz";
$LANG['plugin_fusinvsnmp']['mapping'][148]="Fixiereinheit Cyan";
$LANG['plugin_fusinvsnmp']['mapping'][149]="Fixiereinheit Yellow";
$LANG['plugin_fusinvsnmp']['mapping'][14]="Netzwerk > Port > Verbindungszustand";
$LANG['plugin_fusinvsnmp']['mapping'][150]="Fixiereinheit Magenta";
$LANG['plugin_fusinvsnmp']['mapping'][151]="Resttonereinheit";
$LANG['plugin_fusinvsnmp']['mapping'][152]="Fixiereinheit";
$LANG['plugin_fusinvsnmp']['mapping'][153]="Reinigungsmodul";
$LANG['plugin_fusinvsnmp']['mapping'][154]="Anzahl gedruckter Duplexseiten";
$LANG['plugin_fusinvsnmp']['mapping'][155]="Anzahl gescannter Seiten";
$LANG['plugin_fusinvsnmp']['mapping'][156]="Wartungsmodul";
$LANG['plugin_fusinvsnmp']['mapping'][157]="Toner Schwarz";
$LANG['plugin_fusinvsnmp']['mapping'][158]="Toner Cyan";
$LANG['plugin_fusinvsnmp']['mapping'][159]="Toner Magenta";
$LANG['plugin_fusinvsnmp']['mapping'][15]="Netzwerk > Port > MAC-Adresse";
$LANG['plugin_fusinvsnmp']['mapping'][160]="Toner Gelb";
$LANG['plugin_fusinvsnmp']['mapping'][161]="Trommel Schwarz";
$LANG['plugin_fusinvsnmp']['mapping'][162]="Trommel Cyan";
$LANG['plugin_fusinvsnmp']['mapping'][163]="Trommel Magenta";
$LANG['plugin_fusinvsnmp']['mapping'][164]="Trommel Gelb";
$LANG['plugin_fusinvsnmp']['mapping'][165]="Informationen zusammengefasst.";
$LANG['plugin_fusinvsnmp']['mapping'][166]="Toner Schwarz Maximum2";
$LANG['plugin_fusinvsnmp']['mapping'][167]="Toner verbraucht";
$LANG['plugin_fusinvsnmp']['mapping'][168]="Toner verbleibend";
$LANG['plugin_fusinvsnmp']['mapping'][169]="Toner Cyan Maximum";
$LANG['plugin_fusinvsnmp']['mapping'][16]="Netzwerk > Port > Name";
$LANG['plugin_fusinvsnmp']['mapping'][170]="Toner Cyan benutzt";
$LANG['plugin_fusinvsnmp']['mapping'][171]="Toner Cyan verbleibend";
$LANG['plugin_fusinvsnmp']['mapping'][172]="Toner Magenta Maximum";
$LANG['plugin_fusinvsnmp']['mapping'][173]="Toner Magenta verbraucht";
$LANG['plugin_fusinvsnmp']['mapping'][174]="Toner Magenta verbleibend";
$LANG['plugin_fusinvsnmp']['mapping'][175]="Toner Gelb Maximum";
$LANG['plugin_fusinvsnmp']['mapping'][176]="Toner Gelb verbraucht";
$LANG['plugin_fusinvsnmp']['mapping'][177]="Toner Gelb verbleibend";
$LANG['plugin_fusinvsnmp']['mapping'][178]="Trommel Black Maximum";
$LANG['plugin_fusinvsnmp']['mapping'][179]="Trommel Black verbraucht";
$LANG['plugin_fusinvsnmp']['mapping'][17]="Netzwerk > Modell";
$LANG['plugin_fusinvsnmp']['mapping'][180]="Trommel Black verbleibend";
$LANG['plugin_fusinvsnmp']['mapping'][181]="Trommel Cyan Maximum";
$LANG['plugin_fusinvsnmp']['mapping'][182]="Trommel Cyan verbraucht";
$LANG['plugin_fusinvsnmp']['mapping'][183]="Trommel Cyan verbleibend";
$LANG['plugin_fusinvsnmp']['mapping'][184]="Trommel Magenta Maximum";
$LANG['plugin_fusinvsnmp']['mapping'][185]="Trommel Magenta verbraucht";
$LANG['plugin_fusinvsnmp']['mapping'][186]="Trommel Magenta verbleiend";
$LANG['plugin_fusinvsnmp']['mapping'][187]="Trommel Gelb Maximum";
$LANG['plugin_fusinvsnmp']['mapping'][188]="Trommel Gelb verbraucht";
$LANG['plugin_fusinvsnmp']['mapping'][189]="Trommel Gelb verbleibend";
$LANG['plugin_fusinvsnmp']['mapping'][18]="Netzwerk > Port > Typ";
$LANG['plugin_fusinvsnmp']['mapping'][190]="Abfalleinheit Maximum";
$LANG['plugin_fusinvsnmp']['mapping'][191]="Abfalleinheit benutzt";
$LANG['plugin_fusinvsnmp']['mapping'][192]="Abfalleinheit verbleibend";
$LANG['plugin_fusinvsnmp']['mapping'][193]="Wartungskit Maximum";
$LANG['plugin_fusinvsnmp']['mapping'][194]="Wartungskit verbraucht";
$LANG['plugin_fusinvsnmp']['mapping'][195]="Wartungskit verbleibend";
$LANG['plugin_fusinvsnmp']['mapping'][196]="Graue Tintenpatrone";
$LANG['plugin_fusinvsnmp']['mapping'][19]="Netzwerk > VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][1]="Netzwerk > Standort";
$LANG['plugin_fusinvsnmp']['mapping'][20]="Netzwerk > Name";
$LANG['plugin_fusinvsnmp']['mapping'][21]="Netzwerk > Gesamter Speicher";
$LANG['plugin_fusinvsnmp']['mapping'][22]="Netzwerk > Freier Speicher";
$LANG['plugin_fusinvsnmp']['mapping'][23]="Netzwerk > Port > Portbezeichnung";
$LANG['plugin_fusinvsnmp']['mapping'][24]="Drucker > Name";
$LANG['plugin_fusinvsnmp']['mapping'][25]="Drucker > Modell";
$LANG['plugin_fusinvsnmp']['mapping'][26]="Drucker > Gesamter Speicher";
$LANG['plugin_fusinvsnmp']['mapping'][27]="Drucker > Seriennummer";
$LANG['plugin_fusinvsnmp']['mapping'][28]="Drucker > Zähler > Anzahl gedruckte Seiten";
$LANG['plugin_fusinvsnmp']['mapping'][29]="Drucker > Zähler > Anzahl gedruckte Seiten (S/W)";
$LANG['plugin_fusinvsnmp']['mapping'][2]="Netzwerk > Firmware";
$LANG['plugin_fusinvsnmp']['mapping'][30]="Drucker > Zähler > Anzahl gedruckte Seiten (Farbe)";
$LANG['plugin_fusinvsnmp']['mapping'][31]="Drucker > Zähler > Anzahl gedruckte Seiten (Monochrom)";
$LANG['plugin_fusinvsnmp']['mapping'][33]="Netzwerk > Port > Duplex-Modus";
$LANG['plugin_fusinvsnmp']['mapping'][34]="Drucker > Verbrauchsmaterial > Kartusche Schwarz (%)";
$LANG['plugin_fusinvsnmp']['mapping'][35]="Drucker > Verbrauchsmaterial > Kartusche Kontrastschwarz (%)";
$LANG['plugin_fusinvsnmp']['mapping'][36]="Drucker > Verbrauchsmaterial > Kartusche Cyan (%)";
$LANG['plugin_fusinvsnmp']['mapping'][37]="Drucker > Verbrauchsmaterial > Kartusche Yellow (%)";
$LANG['plugin_fusinvsnmp']['mapping'][38]="Drucker > Verbrauchsmaterial > Kartusche Magenta (%)";
$LANG['plugin_fusinvsnmp']['mapping'][39]="Drucker > Verbrauchsmaterial > Kartusche Photo-Cyan (%)";
$LANG['plugin_fusinvsnmp']['mapping'][3]="Netzwerk > Uptime";
$LANG['plugin_fusinvsnmp']['mapping'][400]="Drucker > Verbrauchsmaterial > Wartungskit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][401]="Netzwerk > CPU Benutzer";
$LANG['plugin_fusinvsnmp']['mapping'][402]="Netzwerk > CPU System";
$LANG['plugin_fusinvsnmp']['mapping'][403]="Netzwerk > Kontakt";
$LANG['plugin_fusinvsnmp']['mapping'][404]="Netzwerk > Kommentar";
$LANG['plugin_fusinvsnmp']['mapping'][405]="Drucker > Kontakt";
$LANG['plugin_fusinvsnmp']['mapping'][406]="Drucker > Kommentar";
$LANG['plugin_fusinvsnmp']['mapping'][407]="Drucker > Port > IP Adresse";
$LANG['plugin_fusinvsnmp']['mapping'][408]="Netzwerk > Port > Nummerischer Index";
$LANG['plugin_fusinvsnmp']['mapping'][409]="Netzwerk > CDP-Adresse";
$LANG['plugin_fusinvsnmp']['mapping'][40]="Drucker > Verbrauchsmaterial > Kartusche Photo-Magenta (%)";
$LANG['plugin_fusinvsnmp']['mapping'][410]="Netzwerk > CDP-Port";
$LANG['plugin_fusinvsnmp']['mapping'][411]="Netzwerk > Port > Trunk/tagged";
$LANG['plugin_fusinvsnmp']['mapping'][412]="Netzwerk > MAC-Adressefilter (dot1dTpFdbAddress)";
$LANG['plugin_fusinvsnmp']['mapping'][413]="Netzwerk > Physikalische Adressen im Speicher (ipNetToMediaPhysAddress)";
$LANG['plugin_fusinvsnmp']['mapping'][414]="Netzwerk > Instanzen des Ports (dot1dTpFdbPort)";
$LANG['plugin_fusinvsnmp']['mapping'][415]="Netzwerk > Verknüpfung der Portnummerierung mit der ID des Ports (dot1dBasePortIfIndex)";
$LANG['plugin_fusinvsnmp']['mapping'][416]="Drucker > Port > Indexnummer";
$LANG['plugin_fusinvsnmp']['mapping'][417]="Netzwerk > MAC-Adresse";
$LANG['plugin_fusinvsnmp']['mapping'][418]="Drucker > Inventarnummer";
$LANG['plugin_fusinvsnmp']['mapping'][419]="Netzwerk > Inventarnummer";
$LANG['plugin_fusinvsnmp']['mapping'][41]="Drucker > Verbrauchsmaterial > Photoleiter (%)";
$LANG['plugin_fusinvsnmp']['mapping'][420]="Drucker > Hersteller";
$LANG['plugin_fusinvsnmp']['mapping'][421]="Netzwerk > IP-Adressen";
$LANG['plugin_fusinvsnmp']['mapping'][422]="Netzwerk > PVID (Port VLAN ID)";
$LANG['plugin_fusinvsnmp']['mapping'][423]="Drucker > Zähler > Total gedruckte Seiten";
$LANG['plugin_fusinvsnmp']['mapping'][424]="Drucker > Zähler > Total gedruckte Seiten (S/W)";
$LANG['plugin_fusinvsnmp']['mapping'][425]="Drucker > Zähler > Total gedruckte Seiten (Farbe)";
$LANG['plugin_fusinvsnmp']['mapping'][426]="Drucker > Zähler > Total gedruckte Kopien";
$LANG['plugin_fusinvsnmp']['mapping'][427]="Drucker > Zähler > Total gedruckte Kopien (S/W)";
$LANG['plugin_fusinvsnmp']['mapping'][428]="Drucker > Zähler > Total gedruckte Kopien (Farbe)";
$LANG['plugin_fusinvsnmp']['mapping'][429]="Drucker > Zähler > Total gedruckte Faxe";
$LANG['plugin_fusinvsnmp']['mapping'][42]="Drucker > Verbrauchsmaterial > Photoleiter Schwarz (%)";
$LANG['plugin_fusinvsnmp']['mapping'][430]="Netzwerk > Port > VLAN";
$LANG['plugin_fusinvsnmp']['mapping'][435]="Netzwerk > CDP remote sysdescr";
$LANG['plugin_fusinvsnmp']['mapping'][436]="Netzwerk > CDP remote id";
$LANG['plugin_fusinvsnmp']['mapping'][437]="Netzwerk > CDP remote model device";
$LANG['plugin_fusinvsnmp']['mapping'][438]="Netzwerk > LLDP remote Systembeschreibung";
$LANG['plugin_fusinvsnmp']['mapping'][439]="Netzwerk > LLDP remote id";
$LANG['plugin_fusinvsnmp']['mapping'][43]="Drucker > Verbrauchsmaterial > Photoleiter Farbe (%)";
$LANG['plugin_fusinvsnmp']['mapping'][440]="Netzwerk > LLDP remote port Beschreibung";
$LANG['plugin_fusinvsnmp']['mapping'][44]="Drucker > Verbrauchsmaterial > Photoleiter Cyan (%)";
$LANG['plugin_fusinvsnmp']['mapping'][45]="Drucker > Verbrauchsmaterial > Photoleiter Yellow (%)";
$LANG['plugin_fusinvsnmp']['mapping'][46]="Drucker > Verbrauchsmaterial > Photoleiter Magenta (%)";
$LANG['plugin_fusinvsnmp']['mapping'][47]="Drucker > Verbrauchsmaterial > Transfereinheit Schwarz (%)";
$LANG['plugin_fusinvsnmp']['mapping'][48]="Drucker > Verbrauchsmaterial > Transfereinheit Cyan (%)";
$LANG['plugin_fusinvsnmp']['mapping'][49]="Drucker > Verbrauchsmaterial > Transfereinheit Yellow (%)";
$LANG['plugin_fusinvsnmp']['mapping'][4]="Netzwerk > Port > MTU";
$LANG['plugin_fusinvsnmp']['mapping'][50]="Drucker > Verbrauchsmaterial > Transfereinheit Magenta (%)";
$LANG['plugin_fusinvsnmp']['mapping'][51]="Drucker > Verbrauchsmaterial > Abfalleinheit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][52]="Drucker > Verbrauchsmaterial > Fixiereinheit (%)";
$LANG['plugin_fusinvsnmp']['mapping'][53]="Drucker > Verbrauchsmaterial > Reinigungsmodul (%)";
$LANG['plugin_fusinvsnmp']['mapping'][54]="Drucker > Zähler > Anzahl der gedruckten Duplex Seiten";
$LANG['plugin_fusinvsnmp']['mapping'][55]="Drucker > Zähler > Anzahl der gescannten Seiten";
$LANG['plugin_fusinvsnmp']['mapping'][56]="Drucker > Standort";
$LANG['plugin_fusinvsnmp']['mapping'][57]="Drucker > Port > Name";
$LANG['plugin_fusinvsnmp']['mapping'][58]="Drucker > Port > MAC-Adresse";
$LANG['plugin_fusinvsnmp']['mapping'][59]="Drucker > Verbrauchsmaterial > Kartusche Schwarz (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][5]="Netzwerk > Port > Geschwindigkeit";
$LANG['plugin_fusinvsnmp']['mapping'][60]="Drucker > Verbrauchsmaterial > Kartusche Schwarz (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][61]="Drucker > Verbrauchsmaterial > Kartusche Cyan (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][62]="Drucker > Verbrauchsmaterial > Kartusche Cyan (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][63]="Drucker > Verbrauchsmaterial > Kartusche Yellow (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][64]="Drucker > Verbrauchsmaterial > Kartusche Yellow (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][65]="Drucker > Verbrauchsmaterial > Kartusche Magenta (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][66]="Drucker > Verbrauchsmaterial > Kartusche Magenta (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][67]="Drucker > Verbrauchsmaterial > Kartusche Photo-Cyan (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][68]="Drucker > Verbrauchsmaterial > Kartusche Photo-Cyan (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][69]="Drucker > Verbrauchsmaterial > Kartusche Photo-Magenta (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][6]="Netzwerk > Port > Interner Zustand";
$LANG['plugin_fusinvsnmp']['mapping'][70]="Drucker > Verbrauchsmaterial > Kartusche Photo-Magenta (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][71]="Drucker > Verbrauchsmaterial > Photoleiter (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][72]="Drucker > Verbrauchsmaterial > Photoleiter (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][73]="Drucker > Verbrauchsmaterial > Photoleiter Schwarz (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][74]="Drucker > Verbrauchsmaterial > Photoleiter Schwarz (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][75]="Drucker > Verbrauchsmaterial > Photoleiter Farbe (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][76]="Drucker > Verbrauchsmaterial > Photoleiter Farbe (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][77]="Drucker > Verbrauchsmaterial > Photoleiter Cyan (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][78]="Drucker > Verbrauchsmaterial > Photoleiter Cyan (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][79]="Drucker > Verbrauchsmaterial > Photoleiter Yellow (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][7]="Netzwerk > Ports > Letzte Änderungen";
$LANG['plugin_fusinvsnmp']['mapping'][80]="Drucker > Verbrauchsmaterial > Photoleiter Yellow (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][81]="Drucker > Verbrauchsmaterial > Photoleiter Magenta (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][82]="Drucker > Verbrauchsmaterial > Photoleiter Magenta (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][83]="Drucker > Verbrauchsmaterial > Transfereinheit Schwarz (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][84]="Drucker > Verbrauchsmaterial > Transfereinheit Schwarz (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][85]="Drucker > Verbrauchsmaterial > Transfereinheit Cyan (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][86]="Drucker > Verbrauchsmaterial > Transfereinheit Cyan (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][87]="Drucker > Verbrauchsmaterial > Transfereinheit Yellow (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][88]="Drucker > Verbrauchsmaterial > Transfereinheit Yellow (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][89]="Drucker > Verbrauchsmaterial > Transfereinheit Magenta (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][8]="Netzwerk > Port > Anzahl Bytes eingehend";
$LANG['plugin_fusinvsnmp']['mapping'][90]="Drucker > Verbrauchsmaterial > Transfereinheit Magenta (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][91]="Drucker > Verbrauchsmaterial > Abfalleinheit (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][92]="Drucker > Verbrauchsmaterial > Abfalleinheit (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][93]="Drucker > Verbrauchsmaterial > Fixiereinheit (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][94]="Drucker > Verbrauchsmaterial > Fixiereinheit (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][95]="Drucker > Verbrauchsmaterial > Reinigungsmodul (Maximum)";
$LANG['plugin_fusinvsnmp']['mapping'][96]="Drucker > Verbrauchsmaterial > Reinigungsmodul (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][97]="Drucker > Port > Typ";
$LANG['plugin_fusinvsnmp']['mapping'][98]="Drucker > Verbrauchsmaterial > Wartungskit (max.)";
$LANG['plugin_fusinvsnmp']['mapping'][99]="Drucker > Verbrauchsmaterial > Wartungskit (verbleibend)";
$LANG['plugin_fusinvsnmp']['mapping'][9]="Netzwerk > Port > Anzahl Bytes ausgehend";

$LANG['plugin_fusinvsnmp']['massiveaction'][1]="SNMP-Modell zuordnen";
$LANG['plugin_fusinvsnmp']['massiveaction'][2]="SNMP-Authentifizierung zuordnen";

$LANG['plugin_fusinvsnmp']['menu'][10]="Status Netzwerkinventar";
$LANG['plugin_fusinvsnmp']['menu'][2]="IP-Adressbereich Konfiguration";
$LANG['plugin_fusinvsnmp']['menu'][5]="Switchs ports history";
$LANG['plugin_fusinvsnmp']['menu'][6]="Nicht verwendete Switch-Ports";
$LANG['plugin_fusinvsnmp']['menu'][9]="Discovery status";

$LANG['plugin_fusinvsnmp']['mib'][1]="MIB-Bezeichnung";
$LANG['plugin_fusinvsnmp']['mib'][2]="Objekt";
$LANG['plugin_fusinvsnmp']['mib'][3]="OID";
$LANG['plugin_fusinvsnmp']['mib'][4]="Füge eine OID hinzu...";
$LANG['plugin_fusinvsnmp']['mib'][5]="OID-Liste";
$LANG['plugin_fusinvsnmp']['mib'][6]="Portzähler";
$LANG['plugin_fusinvsnmp']['mib'][7]="Dynamische Ports (.x)";
$LANG['plugin_fusinvsnmp']['mib'][8]="Verlinkte Felder";

$LANG['plugin_fusinvsnmp']['model_info'][10]="SNMP-Modell importieren";
$LANG['plugin_fusinvsnmp']['model_info'][11]="ist aktiv";
$LANG['plugin_fusinvsnmp']['model_info'][12]="Modellschlüssel für die Entdeckung";
$LANG['plugin_fusinvsnmp']['model_info'][13]="Lade richtiges Modell";
$LANG['plugin_fusinvsnmp']['model_info'][14]="Lade richtiges SNMP Modell";
$LANG['plugin_fusinvsnmp']['model_info'][15]="Massenimport von Modellen";
$LANG['plugin_fusinvsnmp']['model_info'][16]="Messenimport von Modellen in Ordner plugins/fusinvsnmp/models/";
$LANG['plugin_fusinvsnmp']['model_info'][2]="SNMP-Version";
$LANG['plugin_fusinvsnmp']['model_info'][3]="SNMP-Authentifizierung";
$LANG['plugin_fusinvsnmp']['model_info'][4]="SNMP-Modelle";
$LANG['plugin_fusinvsnmp']['model_info'][6]="Bearbeite SNMP Modell";
$LANG['plugin_fusinvsnmp']['model_info'][7]="Erstelle SNMP Modell";
$LANG['plugin_fusinvsnmp']['model_info'][9]="Import erfolgreich abgeschlossen";

$LANG['plugin_fusinvsnmp']['portlogs'][0]="Verlaufskonfiguration";
$LANG['plugin_fusinvsnmp']['portlogs'][1]="Liste der Felder, die im historischen Verlauf beibehalten werden.";
$LANG['plugin_fusinvsnmp']['portlogs'][2]="Vorhaltezeit in Tagen";

$LANG['plugin_fusinvsnmp']['printhistory'][1]="Zu viele Datenfür die Anzeige";

$LANG['plugin_fusinvsnmp']['processes'][37]="Anzahl IPs";

$LANG['plugin_fusinvsnmp']['profile'][2]="Konfiguration";
$LANG['plugin_fusinvsnmp']['profile'][4]="IP-Adressbereich";
$LANG['plugin_fusinvsnmp']['profile'][5]="SNMP-Discovery der Netzwerkgeräte";
$LANG['plugin_fusinvsnmp']['profile'][6]="SNMP-Discovery der Drucker";
$LANG['plugin_fusinvsnmp']['profile'][7]="SNMP-Modelle";
$LANG['plugin_fusinvsnmp']['profile'][8]="Druckerbericht";
$LANG['plugin_fusinvsnmp']['profile'][9]="Netzwerkbericht";

$LANG['plugin_fusinvsnmp']['prt_history'][0]="Verlauf und Statistik der Druckerzähler";
$LANG['plugin_fusinvsnmp']['prt_history'][12]="Gedruckte Seiten gesamt";
$LANG['plugin_fusinvsnmp']['prt_history'][13]="Seiten / Tag";
$LANG['plugin_fusinvsnmp']['prt_history'][20]="Verlauf des Seitenzählers";
$LANG['plugin_fusinvsnmp']['prt_history'][21]="Datum";
$LANG['plugin_fusinvsnmp']['prt_history'][22]="Zähler";
$LANG['plugin_fusinvsnmp']['prt_history'][31]="Zeiteinheit";
$LANG['plugin_fusinvsnmp']['prt_history'][32]="Drucker hinzufügen";
$LANG['plugin_fusinvsnmp']['prt_history'][33]="Drucker entfernen";
$LANG['plugin_fusinvsnmp']['prt_history'][34]="Tag";
$LANG['plugin_fusinvsnmp']['prt_history'][35]="Woche";
$LANG['plugin_fusinvsnmp']['prt_history'][36]="Monat";
$LANG['plugin_fusinvsnmp']['prt_history'][37]="Jahr";
$LANG['plugin_fusinvsnmp']['prt_history'][38]="Drucker zum vergleichen";

$LANG['plugin_fusinvsnmp']['report'][0]="Anzahl Tage seite der letzten Inventarisierung";
$LANG['plugin_fusinvsnmp']['report'][1]="Seitenzähler";

$LANG['plugin_fusinvsnmp']['setup'][17]="Das Plugin FusionInventory SNMP benötigt das FusionInventory um selber installiert zu werden.";
$LANG['plugin_fusinvsnmp']['setup'][18]="Das Plugin FusionInventory SNMP benötigt ein aktiviertes FusionInventory um deinstalliert zu werden.";
$LANG['plugin_fusinvsnmp']['setup'][19]="Konvertiere Verlauf der Ports";
$LANG['plugin_fusinvsnmp']['setup'][20]="Verschiebe Verlauf des Verbindungsaufbaus";
$LANG['plugin_fusinvsnmp']['setup'][21]="Verschiebe Verlauf Verbindungstrennungen";

$LANG['plugin_fusinvsnmp']['snmp'][12]="Uptime";
$LANG['plugin_fusinvsnmp']['snmp'][13]="CPU-Last (in %)";
$LANG['plugin_fusinvsnmp']['snmp'][14]="Speicherauslastung (in %)";
$LANG['plugin_fusinvsnmp']['snmp'][40]="Porttabelle";
$LANG['plugin_fusinvsnmp']['snmp'][41]="Portbeschreibung";
$LANG['plugin_fusinvsnmp']['snmp'][46]="Anzahl empfangener Bytes";
$LANG['plugin_fusinvsnmp']['snmp'][48]="Anzahl gesendeter Bytes";
$LANG['plugin_fusinvsnmp']['snmp'][49]="Anzahl von Fehlern beim Empfang";
$LANG['plugin_fusinvsnmp']['snmp'][4]="Sysdescr";
$LANG['plugin_fusinvsnmp']['snmp'][51]="Duplex";
$LANG['plugin_fusinvsnmp']['snmp'][53]="Letzte Inventarisierung";
$LANG['plugin_fusinvsnmp']['snmp'][54]="Keine Daten verfügbar";
$LANG['plugin_fusinvsnmp']['snmp'][55]="Anzahl pro Sekunden";

$LANG['plugin_fusinvsnmp']['snmpauth'][1]="SNMP Community";
$LANG['plugin_fusinvsnmp']['snmpauth'][2]="Benutzer";
$LANG['plugin_fusinvsnmp']['snmpauth'][4]="Verschlüsseungs für die Authentifizierung ";
$LANG['plugin_fusinvsnmp']['snmpauth'][5]="Passwort";
$LANG['plugin_fusinvsnmp']['snmpauth'][6]="Verschlüsselungmethode für Daten";

$LANG['plugin_fusinvsnmp']['state'][10]="Importierte Geräte";
$LANG['plugin_fusinvsnmp']['state'][4]="Startdatum";
$LANG['plugin_fusinvsnmp']['state'][5]="Enddatum";
$LANG['plugin_fusinvsnmp']['state'][6]="Anzahl entdeckte Geräte";
$LANG['plugin_fusinvsnmp']['state'][7]="Davon fehlerhaft";
$LANG['plugin_fusinvsnmp']['state'][8]="Nicht importierte Geräte";
$LANG['plugin_fusinvsnmp']['state'][9]="Verknüpfte Geräte";

$LANG['plugin_fusinvsnmp']['stats'][0]="Seitenzähler";
$LANG['plugin_fusinvsnmp']['stats'][1]="Seiten/Tag";
$LANG['plugin_fusinvsnmp']['stats'][2]="Anzeige";

$LANG['plugin_fusinvsnmp']['task'][15]="Dauerhafte Aufgaber";
$LANG['plugin_fusinvsnmp']['task'][17]="Kommunikationsmodus";
$LANG['plugin_fusinvsnmp']['task'][18]="Task automatisch erstellen (einfacher)";

$LANG['plugin_fusinvsnmp']['title'][0]="FusionInventory SNMP";
$LANG['plugin_fusinvsnmp']['title'][1]="SNMP-Information";
$LANG['plugin_fusinvsnmp']['title'][2]="Verbindungs-Historie";
$LANG['plugin_fusinvsnmp']['title'][5]="FusionInventory-Sperrungen";
$LANG['plugin_fusinvsnmp']['title'][6]="SNMP";
?>